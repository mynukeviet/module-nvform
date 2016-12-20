<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Tue, 08 Apr 2014 15:13:43 GMT
 */
if (! defined('NV_IS_MOD_NVFORM'))
    die('Stop!!!');

$form_info = $db->query("SELECT * FROM " . NV_PREFIXLANG . '_' . $module_data . " WHERE id = " . $fid)->fetch();
if (empty($form_info)) {
    nv_theme_nvform_alert($form_info['title'], $lang_module['error_form_not_found_detail']);
}

// Kiểm tra trạng thái biểu mẫu
// Trạng thái hoạt động
if ($form_info['status'] == 0 or ($form_info['status'] == 2 and ! defined('NV_IS_MODADMIN'))) {
    nv_theme_nvform_alert($form_info['title'], $lang_module['error_form_not_status_detail']);
}

// Thời gian hoạt động
if ($form_info['start_time'] > NV_CURRENTTIME) {
    $start_time = date("d/m/Y H:i", $form_info['start_time']);
    nv_theme_nvform_alert($form_info['title'], sprintf($lang_module['error_form_not_start'], $start_time));
}

// Thời gian kết thúc
if (! empty($form_info['end_time']) and $form_info['end_time'] < NV_CURRENTTIME) {
    $end_time = date("d/m/Y H:i", $form_info['end_time']);
    nv_theme_nvform_alert($form_info['title'], sprintf($lang_module['error_form_closed'], $end_time));
}

// Kiểm tra quyền truy cập
if (! nv_user_in_groups($form_info['groups_view'])) {
    nv_theme_nvform_alert($form_info['title'], $lang_module['error_form_not_premission_detail'], 'warning');
}

// Lấy thông tin câu hỏi
$question_info = $db->query("SELECT * FROM " . NV_PREFIXLANG . '_' . $module_data . "_question WHERE fid = " . $fid . " AND status = 1 ORDER BY weight")->fetchAll();

$info = '';
$filled = false;
$answer_info = $answer_info_extend = $old_answer_info = $old_answer_info_extend = array();
$embed = $nv_Request->isset_request('embed', 'get');

// Trạng thái trả lời
if (defined('NV_IS_USER')) {
    $sql = "SELECT * FROM " . NV_PREFIXLANG . '_' . $module_data . "_answer WHERE fid = " . $fid . " AND who_answer = " . $user_info['userid'];
    $_rows = $db->query($sql)->fetch();
    
    if ($_rows) {
        $filled = true;
        $form_info['filled'] = true;
        $answer_info = unserialize($_rows['answer']);
        $answer_info_extend = unserialize($_rows['answer_extend']);
    }
}

if ($nv_Request->isset_request('submit', 'post')) {
    $error = '';
    
    if ($filled) {
        $old_answer_info = $answer_info;
        $old_answer_info_extend = $answer_info_extend;
    }
    
    $answer_info = $nv_Request->get_array('question', 'post');
    $answer_info_extend = $nv_Request->get_array('question_extend', 'post', array());
    
    require NV_ROOTDIR . '/modules/' . $module_file . '/form.check.php';
    
    if (empty($error)) {
        if (! isset($user_info['userid']))
            $user_info['userid'] = 0;
        
        if ($filled) {
            $sth = $db->prepare("UPDATE " . NV_PREFIXLANG . '_' . $module_data . "_answer SET answer = :answer, answer_extend = :answer_extend, answer_edit_time = " . NV_CURRENTTIME . " WHERE fid = " . $fid . " AND who_answer = " . $user_info['userid']);
        } else {
            $sth = $db->prepare("INSERT INTO " . NV_PREFIXLANG . '_' . $module_data . "_answer (fid, answer, answer_extend, who_answer, answer_time) VALUES (" . $fid . ", :answer, :answer_extend, " . $user_info['userid'] . ", " . NV_CURRENTTIME . ")");
        }
        $answer_info = serialize($answer_info);
        $answer_info_extend = serialize($answer_info_extend);
        $sth->bindParam(':answer', $answer_info, PDO::PARAM_STR);
        $sth->bindParam(':answer_extend', $answer_info_extend, PDO::PARAM_STR);
        
        if ($sth->execute()) {
            // Báo cáo kết qủa qua email
            if (($form_info['form_report_type'] == 1) and ! $filled) {
                $form_report_type_email = unserialize($form_info['form_report_type_email']);
                $subject = $lang_module['reply'] . ': ' . $form_info['title'];
                $listmail = array();
                
                // Lấy danh sách email
                if ($form_report_type_email['form_report_type_email'] == 0 and ! empty($form_report_type_email['group_email'])) {
                    $result = $db->query('SELECT userid FROM ' . NV_GROUPS_GLOBALTABLE . '_users WHERE group_id IN (' . implode(',', $form_report_type_email['group_email']) . ')');
                    while (list ($userid) = $result->fetch(3)) {
                        $listmail[] = $db->query('SELECT email FROM ' . NV_USERS_GLOBALTABLE . ' WHERE userid=' . $userid)->fetchColumn();
                    }
                } elseif ($form_report_type_email['form_report_type_email'] == 1 and ! empty($form_report_type_email['listmail'])) {
                    $listmail = explode(';', $form_report_type_email['listmail']);
                    $listmail = array_map('trim', $listmail);
                }
                
                if (! empty($listmail)) {
                    $listmail = array_unique($listmail);
                    
                    // Nội dung email
                    $answer_info['username'] = empty($user_info['userid']) ? $lang_module['report_guest'] : nv_show_name_user($user_info['full_name']);
                    
                    $xtpl = new XTemplate('sendmail.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file);
                    $xtpl->assign('FORM_DATA', nv_form_result($question_info, $answer_info));
                    
                    $xtpl->parse('main');
                    $message = $xtpl->text('main');
                    $message = nv_site_theme($message, false);
                    
                    nv_sendmail($global_config['site_email'], $listmail, $subject, $message);
                }
            }
            
            $info = $lang_module['success_info'];
            if (defined('NV_IS_USER')) {
                $link_form = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $form_info['alias'] . '-' . $form_info['id'] . $global_config['rewrite_exturl'];
                if ($form_info['question_report']) {
                    $link_report = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $module_info['alias']['viewanalytics'] . '/' . $form_info['alias'] . '-' . $form_info['id'] . $global_config['rewrite_exturl'];
                    $info .= '<br />' . sprintf($lang_module['success_user_info_report'], $link_form, $link_report);
                } else {
                    $info .= '<br />' . sprintf($lang_module['success_user_info'], $link_form);
                }
            }
            nv_theme_nvform_alert($lang_module['success'], $info, 'success');
        }
    } else {
        $info = $error;
    }
}

$page_title = $form_info['title'];
if (! empty($form_info['description'])) {
    $description = $form_info['description'];
}
if (! empty($form_info['image'])) {
    $meta_property['og:image'] = NV_MY_DOMAIN . NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $form_info['image'];
}

$contents = nv_theme_nvform_viewform($form_info, $question_info, $answer_info, $answer_info_extend, $info);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents, !$embed);
include NV_ROOTDIR . '/includes/footer.php';
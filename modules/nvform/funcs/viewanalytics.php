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
if (! $form_info['status']) {
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
if (! nv_user_in_groups($form_info['groups_view']) or (! defined('NV_IS_MODADMIN') and ! $form_info['question_report'])) {
    nv_theme_nvform_alert($form_info['title'], $lang_module['error_form_not_premission_detail'], 'warning');
}

// Lấy thông tin câu hỏi
$question_info = $db->query("SELECT * FROM " . NV_PREFIXLANG . '_' . $module_data . "_question WHERE fid = " . $fid . " AND status = 1 ORDER BY weight")->fetchAll();

// Thong tin cau tra loi
$answer_info = array();
$result = $db->query("SELECT answer FROM " . NV_PREFIXLANG . '_' . $module_data . "_answer WHERE fid = " . $fid);
while (list ($answer) = $result->fetch(3)) {
    if (! empty($answer)) {
        $answer_info[] = unserialize($answer);
    }
}

$contents = nv_theme_nvform_viewanalytics($form_info, $question_info, $answer_info);
$page_title = $form_info['title'];

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
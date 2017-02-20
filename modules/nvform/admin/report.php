<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 24-06-2011 10:35
 */
if (! defined('NV_IS_FILE_ADMIN'))
    die('Stop!!!');

$fid = $nv_Request->get_int('fid', 'get', 0);
$question_data = $answer_data = array();

// Xoa cau tra loi
if ($nv_Request->isset_request('del', 'post')) {
    if (! defined('NV_IS_AJAX'))
        die('Wrong URL');
    
    $aid = $nv_Request->get_int('aid', 'post', 0);
    
    if (empty($aid))
        die('NO');
    
    $answer = $db->query('SELECT answer FROM ' . NV_PREFIXLANG . '_' . $module_data . '_answer WHERE id = ' . $aid)->fetchColumn();
    
    $sql = 'DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_answer WHERE id = ' . $aid;
    if ($db->exec($sql)) {
        if (! empty($answer)) {
            $answer = unserialize($answer);
            foreach ($answer as $qid => $ans) {
                $question_type = $db->query('SELECT question_type FROM ' . NV_PREFIXLANG . '_' . $module_data . '_question WHERE qid = ' . $qid)->fetchColumn();
                if ($question_type == 'file' and file_exists(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $ans)) {
                    @nv_deletefile(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $ans);
                }
            }
        }
    }
    $nv_Cache->delMod($module_name);
    die('OK');
}

$form_info = $db->query('SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE id = ' . $fid)->fetch();

$xtpl = new XTemplate('report.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);
$xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
$xtpl->assign('URL_ANALYTICS', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $module_info['alias']['viewanalytics'] . '/' . $form_info['alias'] . '-' . $fid);

$sql = 'SELECT t1.*, t2.username, t2.last_name, t2.first_name FROM ' . NV_PREFIXLANG . '_' . $module_data . '_answer t1 LEFT JOIN ' . NV_USERS_GLOBALTABLE . ' t2 ON t1.who_answer = t2.userid WHERE fid = ' . $fid;
$result = $db->query($sql);
$answer_data = $result->fetchAll();

$sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_question WHERE fid = ' . $fid . ' AND status=1 ORDER BY weight';
$result = $db->query($sql);

while ($row = $result->fetch()) {
    if ($row['report']) {
        $row['title'] = nv_get_plaintext($row['title']);
        $row['title_cut'] = nv_clean60($row['title']);
        $question_data[$row['qid']] = $row;
        $xtpl->assign('QUESTION', $row);
        $xtpl->parse('main.thead');
    }
}

$i = 1;
foreach ($answer_data as $answer) {
    $answer['answer'] = unserialize($answer['answer']);
    
    foreach ($answer['answer'] as $qid => $ans) {
        $answer_info = '';
        if (isset($question_data[$qid]) and $question_data[$qid]['report']) {
            $question_type = $question_data[$qid]['question_type'];
            if ($question_type == 'multiselect' or $question_type == 'select' or $question_type == 'radio' or $question_type == 'checkbox') {
                $data = unserialize($question_data[$qid]['question_choices']);
                if ($question_type == 'checkbox') {
                    $result = explode(',', $ans);
                    foreach ($result as $key) {
                        $answer_info .= $data[$key] . "<br />";
                    }
                } else {
                    $answer_info = $data[$ans];
                }
            } elseif ($question_type == 'date' and ! empty($ans)) {
                $answer_info = nv_date('d/m/Y', $ans);
            } elseif ($question_type == 'time' and ! empty($ans)) {
                $answer_info = nv_date('H:i', $ans);
            } elseif ($question_type == 'grid') {
                $data = unserialize($question_data[$qid]['question_choices']);
                $result = explode('||', $ans);
                foreach ($data['col'] as $col) {
                    if ($result[0] == $col['key']) {
                        $answer_info = $col['value'];
                        break;
                    }
                }
                foreach ($data['row'] as $row) {
                    if ($result[1] == $row['key']) {
                        $answer_info .= ' - ' . $col['value'];
                        break;
                    }
                }
            } else {
                $answer_info = $ans;
            }
            
            $answer['username'] = empty($answer['username']) ? $lang_module['report_guest'] : nv_show_name_user($answer['first_name'], $answer['last_name'], $answer['username']);
            
            $xtpl->assign('ANSWER', $answer_info);
            
            if ($question_type == 'table') {
                $xtpl->parse('main.tr.td.table');
            } elseif ($question_type == 'file' and file_exists(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $ans)) {
                $xtpl->assign('FILES', NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $ans);
                $xtpl->parse('main.tr.td.files');
            } else {
                $xtpl->parse('main.tr.td.other');
            }
            
            $xtpl->parse('main.tr.td');
        }
    }
    
    $answer['answer_time'] = nv_date('d/m/Y H:i', $answer['answer_time']);
    $answer['answer_edit_time'] = ! $answer['answer_edit_time'] ? '<span class="label label-danger">N/A</span>' : nv_date('d/m/Y H:i', $answer['answer_edit_time']);
    $answer['answer_view_url'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=view_answer&id=' . $answer['id'];
    
    $answer['no'] = $i;
    $xtpl->assign('ANSWER', $answer);
    $xtpl->parse('main.tr');
    $i ++;
}

$xtpl->assign('FID', $fid);
$xtpl->assign('COUNT', sprintf($lang_module['report_count'], count($answer_data)));

unset($answer_data, $question_data);
$page_title = sprintf($lang_module['report_page_title'], $form_info['title']);

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
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

$page_title = $lang_module['question_list'];
$array = array();
$where = '';

// Xóa câu hỏi
if ($nv_Request->isset_request('del', 'post')) {
    if (! defined('NV_IS_AJAX'))
        die('Wrong URL');
    
    $qid = $nv_Request->get_int('qid', 'post', 0);
    
    $question = $db->query('SELECT fid FROM ' . NV_PREFIXLANG . '_' . $module_data . '_question WHERE qid = ' . $qid)->fetch();
    if (! empty($question)) {
        $sql = 'DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_question WHERE qid = ' . $qid;
        $db->exec($sql);
        
        $sql = 'SELECT qid FROM ' . NV_PREFIXLANG . '_' . $module_data . '_question ORDER BY weight ASC';
        $result = $db->query($sql);
        $weight = 0;
        while ($row = $result->fetch()) {
            ++ $weight;
            $sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_question SET weight=' . $weight . ' WHERE qid = ' . $row['qid'];
            $db->query($sql);
        }
        
        nv_update_answer($question['fid']);
        
        $nv_Cache->delMod($module_name);
        die('OK');
    }
    die('NO');
}

$fid = $nv_Request->get_int('fid', 'get', 0);

$sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data;
$_rows = $db->query($sql)->fetchAll();
$num = sizeof($_rows);

if ($num < 1) {
    Header('Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=form_content');
    die();
}

if ($fid) {
    $where = ' AND fid = ' . $fid;
} else {
    $max_fid = $db->query("SELECT MAX(id) FROM " . NV_PREFIXLANG . "_" . $module_data)->fetchColumn();
    $max_fid = intval($max_fid);
    
    Header('Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=question&fid=' . $max_fid);
    die();
}

$sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_question WHERE 1 = 1 ' . $where . ' ORDER BY weight ASC';
$_rows = $db->query($sql)->fetchAll();
$num = sizeof($_rows);

if ($num < 1) {
    Header('Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=question_content');
    die();
}

$array_status = array(
    $lang_module['form_deactive'],
    $lang_module['form_active']
);

$xtpl = new XTemplate($op . '.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);
$xtpl->assign('ADD_QUESTION', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=question_content&amp;fid=' . $fid);

$i = 0;
$page = 1;
foreach ($_rows as $row) {
    $row['url_edit'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=question_content&amp;qid=' . $row['qid'];
    
    for ($i = 1; $i <= $num; ++ $i) {
        $xtpl->assign('WEIGHT', array(
            'w' => $i,
            'selected' => ($i == $row['weight']) ? ' selected="selected"' : ''
        ));
        
        $xtpl->parse('main.row.weight');
    }
    
    foreach ($array_status as $key => $val) {
        $xtpl->assign('STATUS', array(
            'key' => $key,
            'val' => $val,
            'selected' => ($key == $row['status']) ? ' selected="selected"' : ''
        ));
        
        $xtpl->parse('main.row.status');
    }
    
    $xtpl->assign('FIELD_TYPE_TEXT', $array_field_type[$row['question_type']]);
    
    if ($row['break'])
        $page ++;
    $row['page'] = $page;
    
    $row['title'] = nv_get_plaintext($row['title']);
    
    $xtpl->assign('ROW', $row);
    $xtpl->parse('main.row');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
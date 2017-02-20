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

$id = $nv_Request->get_int('id', 'get', 0);

$answer_data = $db->query('SELECT t1.*, t2.username, t2.last_name, t2.first_name FROM ' . NV_PREFIXLANG . '_' . $module_data . '_answer t1 LEFT JOIN ' . NV_USERS_GLOBALTABLE . ' t2 ON t1.who_answer = t2.userid WHERE t1.id = ' . $id)->fetch();
if (empty($answer_data)) {
    nv_info_die($lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content']);
}

$form_info = $db->query('SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE id=' . $answer_data['fid'])->fetch();
$question_data = $db->query('SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_question WHERE fid=' . $answer_data['fid'] . ' AND status=1 ORDER BY weight')->fetchAll();

$answer_data['answer'] = unserialize($answer_data['answer']);

$xtpl = new XTemplate($op . '.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('FORM_INFO', $form_info);
$xtpl->assign('FORM_DATA', nv_form_result($question_data, $answer_data['answer']));
$xtpl->assign('COUNT', sprintf($lang_module['report_count'], count($answer_data)));

unset($answer_data, $question_data);
$page_title = sprintf($lang_module['report_page_title'], $form_info['title']);

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents, false);
include NV_ROOTDIR . '/includes/footer.php';
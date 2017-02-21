<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Tue, 08 Apr 2014 15:13:43 GMT
 */
if (! defined('NV_ADMIN') or ! defined('NV_MAINFILE') or ! defined('NV_IS_MODADMIN'))
    die('Stop!!!');

$allow_func = array(
    'main',
    'config',
    'form_content',
    'alias',
    'change_status',
    'change_weight',
    'question',
    'question_content',
    'report',
    'export',
    'view_answer'
);

define('NV_IS_FILE_ADMIN', true);
require_once NV_ROOTDIR .  '/modules/' . $module_file . '/global.functions.php';

// Danh sach cac kieu du lieu
$array_field_type = array(
    'number' => $lang_module['question_type_number'],
    'date' => $lang_module['question_type_date'],
    'time' => $lang_module['question_type_time'],
    'textbox' => $lang_module['question_type_textbox'],
    'textarea' => $lang_module['question_type_textarea'],
    'editor' => $lang_module['question_type_editor'],
    'select' => $lang_module['question_type_select'],
    'radio' => $lang_module['question_type_radio'],
    'checkbox' => $lang_module['question_type_checkbox'],
    'multiselect' => $lang_module['question_type_multiselect'],
    'grid' => $lang_module['question_type_grid'],
    'table' => $lang_module['question_type_table'],
    'file' => $lang_module['question_type_file'],
    'plaintext' => $lang_module['question_type_plaintext']
);

$array_status = array(
    1 => $lang_module['form_active'],
    2 => $lang_module['form_review'],
    0 => $lang_module['form_deactive']
);

/**
 * nv_update_answer()
 *
 * @param mixed $form_id            
 * @return
 *
 */
function nv_update_answer($form_id)
{
    global $db, $module_data;
    
    $array_qid = $array_qid_old = array();
    $result = $db->query('SELECT qid FROM ' . NV_PREFIXLANG . '_' . $module_data . '_question WHERE fid=' . $form_id . ' ORDER BY weight');
    while (list ($qid) = $result->fetch(3)) {
        $array_qid[] = $qid;
    }
    
    $result = $db->query('SELECT id, answer FROM ' . NV_PREFIXLANG . '_' . $module_data . '_answer WHERE fid=' . $form_id);
    while (list ($id, $row_answer) = $result->fetch(3)) {
        $row_answer = unserialize($row_answer);
        $array_qid_old = array_keys($row_answer);
        sort($array_qid);
        sort($array_qid_old);
        if ($array_qid != $array_qid_old) {
            foreach ($array_qid as $qid) {
                if (! in_array($qid, $array_qid_old)) {
                    $row_answer[$qid] = '';
                }
            }
            
            foreach ($array_qid_old as $qid_old) {
                if (! in_array($qid_old, $array_qid)) {
                    unset($row_answer[$qid_old]);
                }
            }
        }
        $db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_answer SET answer=' . $db->quote(serialize($row_answer)) . ' WHERE id=' . $id);
    }
}
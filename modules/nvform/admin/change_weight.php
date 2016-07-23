<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-10-2010 18:49
 */
if (! defined('NV_IS_FILE_ADMIN'))
    die('Stop!!!');

$id = $nv_Request->get_int('id', 'post', 0);
$fid = $nv_Request->get_int('fid', 'post', 0);
$op = $nv_Request->get_string('op', 'post', '');

$table = '';
$field = '';
$form_id = '';

if (empty($op))
    die('NO_' . $id);

if ($op == 'form') {
    $field = 'id';
} elseif ($op == 'question') {
    $table = '_question';
    $field = 'qid';
}

if ($fid) {
    $form_id = ' AND fid = ' . $fid;
}

$sql = 'SELECT ' . $field . ' FROM ' . NV_PREFIXLANG . '_' . $module_data . $table . ' WHERE 1 = 1 AND ' . $field . '=' . $id . $form_id;
$id = $db->query($sql)->fetchColumn();
if (empty($id))
    die('NO_' . $id);

$new_weight = $nv_Request->get_int('new_weight', 'post', 0);
if (empty($new_weight))
    die('NO_' . $mod);

$sql = 'SELECT ' . $field . ' FROM ' . NV_PREFIXLANG . '_' . $module_data . $table . ' WHERE 1 = 1 AND ' . $field . ' !=' . $id . $form_id . ' ORDER BY weight ASC';
$result = $db->query($sql);

$weight = 0;
while ($row = $result->fetch()) {
    ++ $weight;
    if ($weight == $new_weight)
        ++ $weight;
    
    $sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . $table . ' SET weight=' . $weight . ' WHERE 1 = 1 AND ' . $field . '=' . $row[$field] . $form_id;
    $db->query($sql);
}

$sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . $table . ' SET weight=' . $new_weight . ' WHERE 1 = 1 AND ' . $field . '=' . $id . $form_id;
$db->query($sql);

$nv_Cache->delMod($module_name);

include NV_ROOTDIR . '/includes/header.php';
echo 'OK_' . $id;
include NV_ROOTDIR . '/includes/footer.php';
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
$op = $nv_Request->get_string('op', 'post', '');

$table = '';
$field = '';

if ($op == 'form') {
    $field = 'id';
} elseif ($op == 'question') {
    $table = '_question';
    $field = 'qid';
}

$sql = 'SELECT ' . $field . ' FROM ' . NV_PREFIXLANG . '_' . $module_data . $table . ' WHERE ' . $field . '=' . $id;
$id = $db->query($sql)->fetchColumn();
if (empty($id) or empty($op))
    die('NO_' . $id);

$new_status = $nv_Request->get_int('new_status', 'post');
$new_status = (int) $new_status;

$sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . $table . ' SET status=' . $new_status . ' WHERE ' . $field . '=' . $id;
$db->query($sql);
$nv_Cache->delMod($module_name);

include NV_ROOTDIR . '/includes/header.php';
echo 'OK_' . $id;
include NV_ROOTDIR . '/includes/footer.php';
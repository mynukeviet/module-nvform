<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Tue, 08 Apr 2014 15:13:43 GMT
 */
if (! defined('NV_SYSTEM'))
    die('Stop!!!');

define('NV_IS_MOD_NVFORM', true);
require_once NV_ROOTDIR .  '/modules/' . $module_file . '/global.functions.php';

$page = 1; // Trang mặc định
$per_page = 10; // Số lượng bản ghi trên 1 trang
$fid = 0; // ID bài viết

if ($op == 'main') {
    if (sizeof($array_op) == 1) {
        if (preg_match('/^page\-([0-9]+)$/', (isset($array_op[0]) ? $array_op[0] : ''), $m)) {
            $page = (int) $m[1];
        } elseif (preg_match('/^([a-z0-9\-]+)\-([0-9]+)$/i', $array_op[0], $m1) and ! preg_match('/^page\-([0-9]+)$/', $array_op[0], $m2)) {
            $op = 'viewform';
            $fid = $m1[2];
        }
    }
} elseif (preg_match('/^([a-z0-9\-]+)\-([0-9]+)$/i', $array_op[1], $m1) and ! preg_match('/^page\-([0-9]+)$/', $array_op[1], $m2)) {
    $fid = $m1[2];
}
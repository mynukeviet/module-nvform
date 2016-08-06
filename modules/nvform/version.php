<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Tue, 08 Apr 2014 15:13:43 GMT
 */
if (! defined('NV_MAINFILE'))
    die('Stop!!!');

$module_version = array(
    'name' => 'Nvform',
    'modfuncs' => 'main, viewform, viewanalytics',
    'change_alias' => 'viewanalytics',
    'submenu' => 'main',
    'is_sysmod' => 0,
    'virtual' => 1,
    'version' => '1.0.05',
    'date' => 'Tue, 8 Apr 2014 15:13:44 GMT',
    'author' => 'hongoctrien (hongoctrien@2mit.org)',
    'uploads_dir' => array(
        $module_name
    ),
    'note' => ''
);
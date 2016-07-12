<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Tue, 08 Apr 2014 15:13:43 GMT
 */
if (! defined('NV_IS_FILE_SITEINFO'))
    die('Stop!!!');

$lang_siteinfo = nv_get_lang_module($mod);
/*
 * // Tong so bai viet
 * $number = $db->query( 'SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $mod_data . '_rows where status= 1 AND publtime < ' . NV_CURRENTTIME . ' AND (exptime=0 OR exptime>' . NV_CURRENTTIME . ')' )->fetchColumn();
 * if ( $number > 0 )
 * {
 * $siteinfo[] = array(
 * 'key' => $lang_siteinfo['siteinfo_publtime'], 'value' => $number
 * );
 * }
 */
?>
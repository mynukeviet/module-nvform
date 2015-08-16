<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Tue, 08 Apr 2014 15:13:43 GMT
 */

if ( ! defined( 'NV_SYSTEM' ) ) die( 'Stop!!!' );

define( 'NV_IS_MOD_NVFORM', true );

$fid = 0;
$val = sizeof( $array_op ) == 1 ? $array_op[0] : $array_op[1];
if( preg_match( '/^([0-9]+)\-([a-z0-9\-]+)$/i', $val, $m1 ) )
{
	$fid = $m1[1];
}
else
{
	Header( 'Location: ' . NV_BASE_SITEURL );
	die();
}

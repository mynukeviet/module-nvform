<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Tue, 08 Apr 2014 15:13:43 GMT
 */

if ( ! defined( 'NV_IS_MOD_NVFORM' ) ) die( 'Stop!!!' );

if( ! empty( $array_op ) )
{
	$fid = $array_op[0];
	$fid = explode( '-', $fid );
	$fid = intval( $fid[0] );
}
else 
{
	Header( 'Location: ' . $global_config['site_url'] );
	die();	
}

$form_info = $db->query( "SELECT * FROM " . NV_PREFIXLANG . '_' . $module_data . " WHERE id = " . $fid )->fetch();

if( ! $form_info )
{
	nv_theme_nvform_alert( $form_info['title'], $lang_module['error_form_not_found_detail'] );
}

// Kiểm tra trạng thái biểu mẫu
if( ! $form_info['status'] )
{
	nv_theme_nvform_alert( $form_info['title'], $lang_module['error_form_not_status_detail'] );
}

// Kiểm tra quyền truy cập
if( ! nv_set_allow( $form_info['who_view'], $form_info['groups_view'] ) )
{
	nv_theme_nvform_alert( $form_info['title'], $lang_module['error_form_not_premission_detail'], 'warning' );
}

// Lấy thông tin câu hỏi
$question_info = $db->query( "SELECT * FROM " . NV_PREFIXLANG . '_' . $module_data . "_question WHERE fid = " . $fid . " AND status = 1" )->fetchAll();
if( ! empty( $question_info ) )
{
	// Không có câu hỏi
}

$page_title = $form_info['title'];
$contents = nv_theme_nvform_main( $form_info, $question_info );

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';

?>
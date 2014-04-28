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
$question_info = $db->query( "SELECT * FROM " . NV_PREFIXLANG . '_' . $module_data . "_question WHERE fid = " . $fid . " AND status = 1 ORDER BY `weight`" )->fetchAll();
if( ! empty( $question_info ) )
{
	//var_dump($question_info); exit;
}

$info = '';
$filled = false;
$answer_info = array();

// Trạng thái trả lời
if( defined( 'NV_IS_USER' ) )
{
	$sql = "SELECT * FROM " . NV_PREFIXLANG . '_' . $module_data . "_answer WHERE fid = " . $fid . " AND who_answer = " . $user_info['userid'];
	$_rows = $db->query( $sql )->fetch();
	$num = sizeof( $_rows );
	
	if( $num >= 1 )
	{
		$filled = true;
		$answer_info = unserialize( $_rows['answer'] );
	}	
}

if( $nv_Request->isset_request( 'submit', 'post') )
{
	$error = '';
	$answer_info = $nv_Request->get_array( 'question', 'post' );
	require NV_ROOTDIR . '/modules/' . $module_name . '/form.check.php';
	
	if( empty( $error ) )
	{
		$answer_info = serialize( $answer_info );
		if( ! isset( $user_info['userid'] ) ) $user_info['userid'] = 0;	
		
		if ( $filled )
		{
			$sth = $db->prepare( "UPDATE " . NV_PREFIXLANG . '_' . $module_data . "_answer SET answer = :answer, answer_edit_time = " . NV_CURRENTTIME . " WHERE fid = " . $fid . " AND who_answer = " . $user_info['userid'] );
		}
		else
		{
			$sth = $db->prepare( "INSERT INTO " . NV_PREFIXLANG . '_' . $module_data . "_answer (fid, answer, who_answer, answer_time) VALUES (" . $fid . ", :answer, " . $user_info['userid'] . ", " . NV_CURRENTTIME . ")" );
		}
		
		$sth->bindParam( ':answer', $answer_info, PDO::PARAM_STR );
		$sth->execute();
	}
	else
	{
		$info = $error;
	}
}

$page_title = $form_info['title'];
$contents = nv_theme_nvform_main( $form_info, $question_info, $answer_info, $info );

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';
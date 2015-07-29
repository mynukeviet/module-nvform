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
// Trạng thái hoạt động
if( ! $form_info['status'] )
{
	nv_theme_nvform_alert( $form_info['title'], $lang_module['error_form_not_status_detail'] );
}

// Thời gian hoạt động
if( $form_info['start_time'] > NV_CURRENTTIME )
{
	$start_time = date( "d/m/Y H:i", $form_info['start_time'] );
	nv_theme_nvform_alert( $form_info['title'], sprintf( $lang_module['error_form_not_start'], $start_time ) );
}

// Thời gian kết thúc
if( ! empty( $form_info['end_time'] ) and $form_info['end_time'] < NV_CURRENTTIME )
{
	$end_time = date( "d/m/Y H:i", $form_info['end_time'] );
	nv_theme_nvform_alert( $form_info['title'], sprintf( $lang_module['error_form_closed'], $end_time ) );
}

// Kiểm tra quyền truy cập
if( ! nv_user_in_groups( $form_info['groups_view'] ) )
{
	nv_theme_nvform_alert( $form_info['title'], $lang_module['error_form_not_premission_detail'], 'warning' );
}

// Lấy thông tin câu hỏi
$question_info = $db->query( "SELECT * FROM " . NV_PREFIXLANG . '_' . $module_data . "_question WHERE fid = " . $fid . " AND status = 1 ORDER BY weight" )->fetchAll();

$info = '';
$filled = false;
$answer_info = $old_answer_info = array();

// Trạng thái trả lời
if( defined( 'NV_IS_USER' ) )
{
	$sql = "SELECT * FROM " . NV_PREFIXLANG . '_' . $module_data . "_answer WHERE fid = " . $fid . " AND who_answer = " . $user_info['userid'];
	$_rows = $db->query( $sql )->fetch();

	if( $_rows )
	{
		$filled = true;
		$form_info['filled'] = true;
		$answer_info = unserialize( $_rows['answer'] );
	}
}

if( $nv_Request->isset_request( 'submit', 'post') )
{
	$error = '';

	if( $filled )
	{
		$old_answer_info = $answer_info;
	}

	$answer_info = $nv_Request->get_array( 'question', 'post' );
	require NV_ROOTDIR . '/modules/' . $module_file . '/form.check.php';

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
		if( $sth->execute() )
		{
			$info = $lang_module['success_info'];
			if( defined( 'NV_IS_USER' ) )
			{
				$link_form = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $form_info['id'] . '-' . $form_info['alias'] . $global_config['rewrite_exturl'];
				$info .= '<br />' . sprintf( $lang_module['success_user_info'], $link_form );
			}
			nv_theme_nvform_alert( $lang_module['success'], $info, 'success' );
		}
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
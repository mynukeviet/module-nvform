<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Tue, 08 Apr 2014 15:13:43 GMT
 */

if ( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$page_title = $lang_module['form_content'];

$xtpl = new XTemplate( 'form.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'UPLOADS_DIR_USER', NV_UPLOADS_DIR . '/' . $module_name );

$id = $nv_Request->get_int( 'id', 'get, post', 0 );
$error = '';
$phour = $pmin = $ehour = $emin = 0;
$groups_list = nv_groups_list();

$form_data = array(
	'who_view' => '',
	'groups_view' => 6,
	'description' => '',
	'start_time' => NV_CURRENTTIME,
	'end_time' => '',
	'question_display' => '');

if( $id )
{
	$sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE id = ' . $id;
	$form_data = $db->query( $sql )->fetch();

	if( empty( $form_data ) )
	{
		Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name );
		die();
	}

	$page_title = $lang_module['form_edit'] . ': ' . $form_data['title'];
	$lang_summit = $lang_module['form_edit'];
	$action = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;id=' . $id;
}
else
{
	$lang_summit = $page_title = $lang_module['form_add'];
	$action = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op;
}

if( $nv_Request->get_int( 'save', 'post' ) == '1' )
{
	$form_data['title'] = $nv_Request->get_string( 'title', 'post', '', 1 );
	$form_data['alias'] = $nv_Request->get_string( 'alias', 'post', '', 1 );
	$form_data['alias'] = empty( $form_data['alias'] ) ? change_alias( $form_data['title'] ) : change_alias( $form_data['alias'] );
	$form_data['description'] = $nv_Request->get_editor( 'description', '', NV_ALLOWED_HTML_TAGS );
	$form_data['start_time'] = $nv_Request->get_title( 'start_time', 'post', 0 );
	$form_data['end_time'] = $nv_Request->get_title( 'end_time', 'post', 0 );
	$form_data['question_display'] = $nv_Request->get_string( 'question_display', 'post', '' );

	if( ! empty( $form_data['start_time'] ) and preg_match( '/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $form_data['start_time'], $m ) )
	{
		$phour = $nv_Request->get_int( 'phour', 'post', 0 );
		$pmin = $nv_Request->get_int( 'pmin', 'post', 0 );
		$form_data['start_time'] = mktime( $phour, $pmin, 0, $m[2], $m[1], $m[3] );
	}
	else
	{
		$form_data['start_time'] = NV_CURRENTTIME;
	}

	if( ! empty( $form_data['end_time'] ) and preg_match( '/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $form_data['end_time'], $m ) )
	{
		$ehour = $nv_Request->get_int( 'ehour', 'post', 0 );
		$emin = $nv_Request->get_int( 'emin', 'post', 0 );
		$form_data['end_time'] = mktime( $ehour, $emin, 0, $m[2], $m[1], $m[3] );
	}
	else
	{
		$form_data['end_time'] = 0;
	}

	$_groups_post = $nv_Request->get_array( 'groups_view', 'post', 6 );
	$form_data['groups_view'] = ! empty( $_groups_post ) ? implode( ',', nv_groups_post( array_intersect( $_groups_post, array_keys( $groups_list ) ) ) ) : '';

	if( empty( $form_data['title'] ) )
	{
		$error = $lang_module['error_formtitle'];
	}
	elseif( ! empty( $form_data['start_time'] ) and ! empty( $form_data['end_time'] ) )
	{
		if( $form_data['start_time'] > $form_data['end_time'] )
		{
			$error = $lang_module['error_formtime'];
		}
	}

	if( empty( $error ) )
	{
		$form_data['description'] = nv_editor_nl2br( $form_data['description'] );
		if( $id )
		{
			$sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . ' SET title = :title, alias = :alias, description = :description, start_time = :start_time, end_time = :end_time, groups_view = :groups_view, question_display = :question_display WHERE id =' . $id;
		}
		else
		{
			$weight = $db->query( "SELECT MAX(weight) FROM " . NV_PREFIXLANG . "_" . $module_data )->fetchColumn();
			$weight = intval( $weight ) + 1;

			$sql = 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . ' (title, alias, description, start_time, end_time, groups_view, question_display, weight, add_time, status) VALUES (:title, :alias, :description, :start_time, :end_time, :groups_view, :question_display, ' . $weight . ', ' . NV_CURRENTTIME . ', 1)';
		}

		$query = $db->prepare( $sql );
		$query->bindParam( ':title', $form_data['title'], PDO::PARAM_STR );
		$query->bindParam( ':alias', $form_data['alias'], PDO::PARAM_STR );
		$query->bindParam( ':description', $form_data['description'], PDO::PARAM_STR );
		$query->bindParam( ':start_time', $form_data['start_time'], PDO::PARAM_STR );
		$query->bindParam( ':end_time', $form_data['end_time'], PDO::PARAM_STR );
		$query->bindParam( ':groups_view', $form_data['groups_view'], PDO::PARAM_STR );
		$query->bindParam( ':question_display', $form_data['question_display'], PDO::PARAM_STR );

		if( $query->execute() )
		{
			if( $id )
			{
				nv_insert_logs( NV_LANG_DATA, $module_name, 'Edit', 'Form: ' . $id . ' - ' . $form_data['title'], $admin_info['userid'] );
			}
			else
			{
				nv_insert_logs( NV_LANG_DATA, $module_name, 'Add', 'Form: ' . $form_data['title'], $admin_info['userid'] );
			}

			nv_del_moduleCache( $module_name );
			Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name );
			die();
		}
		else
		{
			$error = $lang_module['error_save'];
		}
	}
}

// Thời gian
if( ! empty( $form_data['start_time'] ) )
{
	$tdate = date( 'H|i', $form_data['start_time'] );
	$form_data['start_time'] = date( 'd/m/Y', $form_data['start_time'] );
	list( $phour, $pmin ) = explode( '|', $tdate );
}
else
{
	$form_data['start_time'] = '';
}

$select = '';
for( $i = 0; $i <= 23; ++$i )
{
	$select .= "<option value=\"" . $i . "\"" . ( ( $i == $phour ) ? ' selected="selected"' : '' ) . ">" . str_pad( $i, 2, "0", STR_PAD_LEFT ) . "</option>\n";
}
$xtpl->assign( 'phour', $select );

if( !empty( $form_data['end_time'] ) )
{
	$tdate = date( 'H|i', $form_data['end_time'] );
	$form_data['end_time'] = date( 'd/m/Y', $form_data['end_time'] );
	list( $ehour, $emin ) = explode( '|', $tdate );
}
else
{
	$form_data['end_time'] = '';
}
$select = '';
for( $i = 0; $i < 60; ++$i )
{
	$select .= "<option value=\"" . $i . "\"" . ( ( $i == $pmin ) ? ' selected="selected"' : '' ) . ">" . str_pad( $i, 2, "0", STR_PAD_LEFT ) . "</option>\n";
}
$xtpl->assign( 'pmin', $select );

$select = '';
for( $i = 0; $i <= 23; ++$i )
{
	$select .= "<option value=\"" . $i . "\"" . ( ( $i == $ehour ) ? ' selected="selected"' : '' ) . ">" . str_pad( $i, 2, "0", STR_PAD_LEFT ) . "</option>\n";
}
$xtpl->assign( 'ehour', $select );
$select = '';
for( $i = 0; $i < 60; ++$i )
{
	$select .= "<option value=\"" . $i . "\"" . ( ( $i == $emin ) ? ' selected="selected"' : '' ) . ">" . str_pad( $i, 2, "0", STR_PAD_LEFT ) . "</option>\n";
}
$xtpl->assign( 'emin', $select );

// System groups user
$groups_view = explode( ',', $form_data['groups_view'] );
foreach( $groups_list as $_group_id => $_title )
{
	$xtpl->assign( 'GR_VIEW', array(
		'value' => $_group_id,
		'checked' => in_array( $_group_id, $groups_view ) ? ' checked="checked"' : '',
		'title' => $_title
	) );
	$xtpl->parse( 'main.group_view' );
}

// Kieu hien thi
$style_list = array(
	'question_display_top' => $lang_module['form_question_display_top'],
	'question_display_left' => $lang_module['form_question_display_left']
);

foreach( $style_list as $key => $_title )
{
	$xtpl->assign( 'STYLE', array(
		'value' => $key,
		'title' => $_title,
		'seleced' => $form_data['question_display'] == $key ? ' selected="selected"' : ''
	) );
	$xtpl->parse( 'main.question_display' );
}

if( empty( $alias ) ) $xtpl->parse( 'main.get_alias' );

// Trình soạn thảo
if( ! empty( $form_data['description'] ) ) $form_data['description'] = nv_htmlspecialchars( $form_data['description'] );

if( defined( 'NV_EDITOR' ) ) require_once NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php';

if( defined( 'NV_EDITOR' ) and nv_function_exists( 'nv_aleditor' ) )
{
	$form_data['description'] = nv_aleditor( 'description', '100%', '300px', $form_data['description'] );
}
else
{
	$form_data['description'] = '<textarea style="width:100%;height:300px" name="bodytext">' . $form_data['description'] . '</textarea>';
}

if( $error )
{
	$xtpl->assign( 'ERROR', $error );
	$xtpl->parse( 'main.error' );
}

$xtpl->assign( 'DESCRIPTION', $form_data['description'] );
$xtpl->assign( 'LANG_SUBMIT', $lang_summit );
$xtpl->assign( 'DATA', $form_data );
$xtpl->assign( 'FORM_ACTION', $action );
$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
$xtpl->assign( 'NV_LANG_INTERFACE', NV_LANG_INTERFACE );
$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';
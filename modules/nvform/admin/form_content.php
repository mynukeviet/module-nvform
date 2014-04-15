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
$form_data = array();
$error = '';
$form_data['who_view'] = '';
$form_data['groups_view'] = '';
$form_data['description'] = '';

if( $id )
{
	$sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE id = ' . $id;
	$form_data = $db->query( $sql )->fetch();

	if(empty( $form_data ) )
	{
		Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name );
		die();
	}


	$form_data['groups_view'] = explode( ',', $form_data['groups_view'] );

	$page_title = $lang_module['form_edit'] . ': ' . $form_data['title'];
	$lang_summit = $lang_module['form_edit'];
	$action = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;id=' . $id;
}
else
{
	$lang_summit = $page_title = $lang_module['form_add'];
	$action = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op;
}

// System groups user
$groups_list = nv_groups_list();
$array_who = array( $lang_global['who_view0'], $lang_global['who_view1'], $lang_global['who_view2'] );
if( ! empty( $groups_list ) )
{
	$array_who[] = $lang_global['who_view3'];
}

$groups_view = $form_data['groups_view'];

$array['groups_view'] = array();
if( ! empty( $groups_list ) )
{
	foreach( $groups_list as $key => $title )
	{
		if( ! empty( $groups_view ) )
		{
			$array['groups_view'][] = array(
				'key' => $key,
				'title' => $title,
				'checked' => in_array( $key, $groups_view ) ? ' checked="checked"' : ''
			);
		}
		else
		{
			$array['groups_view'][] = array(
				'key' => $key,
				'title' => $title,
				'checked' => ''
			);
		}
	}
}

if( $nv_Request->get_int( 'save', 'post' ) == '1' )
{
	$form_data['title'] = $nv_Request->get_string( 'title', 'post', '', 1 );
	$form_data['alias'] = $nv_Request->get_string( 'alias', 'post', '', 1 );
	$image = $nv_Request->get_string( 'image', 'post', '' );
	$form_data['description'] = $nv_Request->get_editor( 'description', '', NV_ALLOWED_HTML_TAGS );
	$form_data['alias'] = empty( $form_data['alias'] ) ? change_alias( $form_data['title'] ) : change_alias( $form_data['alias'] );
	
	$gr = array();
	$gr = $nv_Request->get_typed_array( 'groups_view', 'post', '' );
	$form_data['groups_view'] = implode( ',', $gr );	
	$form_data['who_view'] = $nv_Request->get_int( 'who_view', 'post', 0 );
	
	if( $form_data['who_view'] != 3 )
	{
		$form_data['groups_view'] = '';
	}
	elseif( empty( $form_data['groups_view'] ) )
	{
		$error = $lang_module['error_groups_choice'];
	}

	if( empty( $form_data['title'] ) )
	{
		$error = $lang_module['error_formtitle'];
	}
	
	if( empty( $error ) ) 
	{
		$form_data['description'] = nv_editor_nl2br( $form_data['description'] );
		if( $id )
		{
			$sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . ' SET title = :title, alias = :alias, description = :description, who_view = ' . $form_data['who_view'] . ', groups_view = :groups_view WHERE id =' . $id;
		}
		else
		{
			$weight = $db->query( "SELECT MAX(weight) FROM " . NV_PREFIXLANG . "_" . $module_data )->fetchColumn();
			$weight = intval( $weight ) + 1;
	
			$sql = 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . ' (title, alias, description, who_view, groups_view, weight, add_time, status) VALUES (:title, :alias, :description, ' . intval( $form_data['who_view'] ) . ', :groups_view, ' . $weight . ', ' . NV_CURRENTTIME . ', 1)';
		}

		$query = $db->prepare( $sql );
		$query->bindParam( ':title', $form_data['title'], PDO::PARAM_STR );
		$query->bindParam( ':alias', $form_data['alias'], PDO::PARAM_STR );
		$query->bindParam( ':description', $form_data['description'], PDO::PARAM_STR );
		$query->bindParam( ':groups_view', $form_data['groups_view'], PDO::PARAM_STR );
		$query->execute();
		
		if( $query->rowCount() )
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

// Hien thi cac nhom thanh vien mac dinh
$who_view = $form_data['who_view'];
$array['who_view'] = array();
foreach( $array_who as $key => $who )
{
	$array['who_view'][] = array(
		'key' => $key,
		'title' => $who,
		'selected' => $key == $who_view ? ' selected="selected"' : ''
	);
}

foreach( $array['who_view'] as $who )
{
	$xtpl->assign( 'WHO_VIEW', $who );
	$xtpl->parse( 'main.who_view' );
}

// Hien thi cac nhom thanh vien mo rong
if( ! empty( $array['groups_view'] ) )
{
	foreach( $array['groups_view'] as $group )
	{
		$xtpl->assign( 'GROUPS_VIEW', $group );
		$xtpl->parse( 'main.group_view_empty.groups_view' );
	}
	$xtpl->parse( 'main.group_view_empty' );
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
$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';
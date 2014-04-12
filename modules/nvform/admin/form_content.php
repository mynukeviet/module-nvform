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

if( $id )
{
	$sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE id=' . $id;
	$form_data = $db->query( $sql )->fetch();

	if(empty( $form_data ) )
	{
		Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name );
		die();
	}

	$page_title = $lang_module['form_edit'];
	$action = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;id=' . $id;
}
else
{
	$page_title = $lang_module['form_add'];
	$action = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op;
}

if( $nv_Request->get_int( 'save', 'post' ) == '1' )
{
	$form_data['title'] = $nv_Request->get_string( 'title', 'post', '', 1 );
	$form_data['alias'] = $nv_Request->get_string( 'alias', 'post', '', 1 );
	$image = $nv_Request->get_string( 'image', 'post', '' );
	if( is_file( NV_DOCUMENT_ROOT . $image ) )
	{
		$lu = strlen( NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' );
		$form_data['image'] = substr( $image, $lu );
	}
	else
	{
		$form_data['image'] = '';
	}
	
	$form_data['description'] = $nv_Request->get_string( 'description', 'post', '' );
	$form_data['description'] = nv_nl2br( nv_htmlspecialchars( strip_tags( $form_data['description'] ) ), '<br />' );
	
	$form_data['alias'] = empty( $form_data['alias'] ) ? change_alias( $form_data['title'] ) : change_alias( $form_data['alias'] );
	
	if( empty( $form_data['title'] ) )
	{
		$error = $lang_module['error_formtitle'];
	}
	else 
	{
		if( $id )
		{
			$sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . ' SET title = :title, alias = :alias, image = :image, description = :description WHERE id =' . $id;
		}
		else
		{
			$weight = $db->query( "SELECT MAX(weight) FROM " . NV_PREFIXLANG . "_" . $module_data )->fetchColumn();
			$weight = intval( $weight ) + 1;
	
			$sql = 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . ' (title, alias, image, description, weight, add_time, status) VALUES (:title, :alias, :image, :description, ' . $weight . ', ' . NV_CURRENTTIME . ', 1)';
		}

		$query = $db->prepare( $sql );
		$query->bindParam( ':title', $form_data['title'], PDO::PARAM_STR );
		$query->bindParam( ':alias', $form_data['alias'], PDO::PARAM_STR );
		$query->bindParam( ':image', $form_data['image'], PDO::PARAM_STR );
		$query->bindParam( ':description', $form_data['description'], PDO::PARAM_STR );
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
			$error = $lang_module['errorsave'];
		}
	}
}

if( empty( $alias ) ) $xtpl->parse( 'main.get_alias' );

if( $error )
{
	$xtpl->assign( 'ERROR', $error );
	$xtpl->parse( 'main.error' );
}

$xtpl->assign( 'DATA', $form_data );
$xtpl->assign( 'FORM_ACTION', $action );
$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';
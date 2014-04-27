<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Tue, 08 Apr 2014 15:13:43 GMT
 */

if ( ! defined( 'NV_IS_MOD_NVFORM' ) ) die( 'Stop!!!' );

/**
 * nv_theme_nvform_main()
 * 
 * @param mixed $form_info
 * @param mixed $question
 * @return
 */
function nv_theme_nvform_main ( $form_info, $question_info, $info )
{
    global $global_config, $module_name, $module_file, $lang_module, $module_config, $module_info, $op, $my_head;
	
	$my_head .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "js/jquery/jquery.validate.min.js\"></script>\n";
	$my_head .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "js/language/jquery.validator-" . NV_LANG_INTERFACE . ".js\"></script>\n";

	$my_head .= "<script type=\"text/javascript\">\n";
	$my_head .= "$(document).ready(function(){
					$('#question').validate({
					});
				 });";
	$my_head .= " </script>\n";

    $xtpl = new XTemplate( $op . '.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );
    $xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'FORM', $form_info );

	foreach( $question_info as $row )
	{
		$row['required'] = ( $row['required'] ) ? 'required' : '';
		$xtpl->assign( 'QUESTION', $row );
		
		if( $row['required'] )
		{
			$xtpl->parse( 'main.loop.required' );
		}
		if( $row['question_type'] == 'textbox' or $row['question_type'] == 'number' )
		{
			$xtpl->parse( 'main.loop.textbox' );
		}
		elseif( $row['question_type'] == 'date' )
		{
			$row['value'] = ( empty( $row['value'] ) ) ? '' : date( 'd/m/Y', $row['value'] );
			$xtpl->assign( 'QUESTION', $row );
			$xtpl->parse( 'main.loop.date' );
		}
		
		$xtpl->parse( 'main.loop' );
	}

	if( !empty( $info ) )
	{
		$xtpl->assign( 'INFO', $info );
		$xtpl->parse( 'main.info' );
	}

    $xtpl->parse( 'main' );
    return $xtpl->text( 'main' );
}

/**
 * nv_theme_nvform_alert()
 * 
 * @param mixed $message
 * @param mixed $type
 * @return
 */
function nv_theme_nvform_alert( $message_title, $message_content, $type = 'info', $link_back = '', $time_back = 0 )
{
    global $module_file, $module_info, $page_title;
	
    $xtpl = new XTemplate( 'info.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );

	if( $type == 'success' )
	{
		$class = 'class="alert alert-success"';
	}
	elseif( $type == 'warning' )
	{
		$class = 'class="alert alert-warning"';
	}
	elseif( $type == 'danger' )
	{
		$class = 'class="alert alert-danger"';
	}
	else
	{
		$class = 'class="alert alert-info"';
	}
	
	if( ! empty( $message_title ) )
	{
		$page_title = $message_title;
		$xtpl->assign( 'TITLE', $message_title );
		$xtpl->parse( 'main.title' );
	}
	else 
	{
		$page_title = $module_info['custom_title'];
	}
	$xtpl->assign( 'CONTENT', $message_content );
	$xtpl->assign( 'CLASS', $class );
    $xtpl->parse( 'main' );
    $contents = $xtpl->text( 'main' );
	
	include ( NV_ROOTDIR . "/includes/header.php" );
	echo nv_site_theme( $contents );
	include ( NV_ROOTDIR . "/includes/footer.php" );
	exit();
}
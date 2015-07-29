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
function nv_theme_nvform_main ( $form_info, $question_info, $answer_info, $info )
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

	if( ! empty( $form_info['end_time'] ) )
	{
		$form_info['close_info'] = sprintf( $lang_module['form_close_info'], date( 'd/m/Y H:i' ) );
	}

    $xtpl = new XTemplate( $op . '.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );
    $xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'FORM', $form_info );
	$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );

	if( $form_info['question_display'] == 'question_display_left' )
	{
		$xtpl->assign( 'FORM_LEFT', 'class="form-horizontal"' );
	}

	foreach( $question_info as $row )
	{
		$row['value'] = isset( $answer_info[$row['qid']] ) ? $answer_info[$row['qid']] : '';
		$row['required'] = ( $row['required'] ) ? 'required' : '';
		$xtpl->assign( 'QUESTION', $row );

		if( $row['required'] )
		{
			$xtpl->parse( 'main.loop.required' );
		}
		if( $row['question_type'] == 'textbox' or $row['question_type'] == 'number' )
		{
			if( $answer_info and ! $row['user_editable'] and isset( $form_info['filled'] ) )
			{
				$row['readonly'] = 'readonly="readonly"';
			}
			$xtpl->assign( 'QUESTION', $row );
			$xtpl->parse( 'main.loop.textbox' );
		}
		elseif( $row['question_type'] == 'date' )
		{
			$row['value'] = ( empty( $row['value'] ) ) ? '' : date( 'd/m/Y', $row['value'] );
			$row['datepicker'] = ( $answer_info and ! $row['user_editable'] and isset( $form_info['filled'] ) ) ? '' : 'datepicker';
			$xtpl->assign( 'QUESTION', $row );
			$xtpl->parse( 'main.loop.date' );
		}
		elseif( $row['question_type'] == 'textarea' )
		{
			if( $answer_info and ! $row['user_editable'] and isset( $form_info['filled'] ) )
			{
				$row['readonly'] = 'readonly';
			}
			$row['value'] = nv_htmlspecialchars( nv_br2nl( $row['value'] ) );
			$xtpl->assign( 'QUESTION', $row );
			$xtpl->parse( 'main.loop.textarea' );
		}
		elseif( $row['question_type'] == 'editor' )
		{
			if( defined( 'NV_EDITOR' ) )
			{
				require_once NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php' ;
			}
			elseif( ! nv_function_exists( 'nv_aleditor' ) and file_exists( NV_ROOTDIR . '/' . NV_EDITORSDIR . '/ckeditor/ckeditor_php5.php' ) )
			{
				define( 'NV_EDITOR', true );
				define( 'NV_IS_CKEDITOR', true );
				require_once NV_ROOTDIR . '/' . NV_EDITORSDIR . '/ckeditor/ckeditor_php5.php' ;

				function nv_aleditor( $textareaname, $width = '100%', $height = '450px', $val = '' )
				{
					// Create class instance.
					$editortoolbar = array( array( 'Link', 'Unlink', 'Image', 'Table', 'Font', 'FontSize', 'RemoveFormat' ), array( 'Bold', 'Italic', 'Underline', 'StrikeThrough', '-', 'Subscript', 'Superscript', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', 'OrderedList', 'UnorderedList', '-', 'Outdent', 'Indent', 'TextColor', 'BGColor', 'Source' ) );
					$CKEditor = new CKEditor();

					// Do not print the code directly to the browser, return it instead
					$CKEditor->returnOutput = true;
					$CKEditor->config['skin'] = 'kama';
					$CKEditor->config['entities'] = false;
					// $CKEditor->config['enterMode'] = 2;
					$CKEditor->config['language'] = NV_LANG_INTERFACE;
					$CKEditor->config['toolbar'] = $editortoolbar;
					// Path to CKEditor directory, ideally instead of relative dir, use an
					// absolute path:
					// $CKEditor->basePath = '/ckeditor/'
					// If not set, CKEditor will try to detect the correct path.
					$CKEditor->basePath = NV_BASE_SITEURL . NV_EDITORSDIR . '/ckeditor/';
					// Set global configuration (will be used by all instances of CKEditor).
					if( ! empty( $width ) )
					{
						$CKEditor->config['width'] = strpos( $width, '%' ) ? $width : intval( $width );
					}
					if( ! empty( $height ) )
					{
						$CKEditor->config['height'] = strpos( $height, '%' ) ? $height : intval( $height );
					}
					// Change default textarea attributes
					$CKEditor->textareaAttributes = array( 'cols' => 80, 'rows' => 10 );
					$val = nv_unhtmlspecialchars( $val );
					return $CKEditor->editor( $textareaname, $val );
				}
			}

			if( defined( 'NV_EDITOR' ) and nv_function_exists( 'nv_aleditor' ) )
			{
				$row['value'] = nv_htmlspecialchars( nv_editor_br2nl( $row['value'] ) );

				$edits = nv_aleditor( 'question[' . $row['qid'] . ']', '100%', '350px' , $row['value'] );
				$xtpl->assign( 'EDITOR', $edits );
				$xtpl->parse( 'main.loop.editor' );
			}
			else
			{
				$row['value'] = nv_htmlspecialchars( nv_br2nl( $row['value'] ) );
				$row['class'] = '';
				$xtpl->assign( 'QUESTION', $row );
				$xtpl->parse( 'main.loop.textarea' );
			}
		}
		elseif( $row['question_type'] == 'select' )
		{
			$row['question_choices'] = unserialize( $row['question_choices'] );
			foreach( $row['question_choices'] as $key => $value )
			{
				$xtpl->assign( 'QUESTION_CHOICES', array(
					'key' => $key,
					'selected' => ( $key == $row['value'] ) ? ' selected="selected"' : '',
					"value" => $value
				) );
				$xtpl->parse( 'main.loop.select.loop' );
			}

			if( $answer_info and ! $row['user_editable'] and isset( $form_info['filled'] ) )
			{
				$row['readonly'] = 'readonly="readonly"';
			}
			$xtpl->assign( 'QUESTION', $row );

			$xtpl->parse( 'main.loop.select' );
		}
		elseif( $row['question_type'] == 'radio' )
		{
			$number = 0;
			$row['question_choices'] = unserialize( $row['question_choices'] );
			foreach( $row['question_choices'] as $key => $value )
			{
				$row['readonly'] = '';
				if( $answer_info and ! $row['user_editable'] and isset( $form_info['filled'] ) )
				{
					$row['readonly'] = 'onclick="return false;"';
				}
				$xtpl->assign( 'QUESTION_CHOICES', array(
					'id' => $row['qid'] . '_' . $number++,
					'key' => $key,
					'checked' => ( $key == $row['value'] ) ? ' checked="checked"' : '',
					'readonly' => $row['readonly'],
					"value" => $value
				) );
				$xtpl->parse( 'main.loop.radio' );
			}
		}
		elseif( $row['question_type'] == 'checkbox' )
		{
			$row['readonly'] = '';
			if( $answer_info and ! $row['user_editable'] and isset( $form_info['filled'] ) )
			{
				$row['readonly'] = 'onclick="return false;"';
			}

			$number = 0;
			$row['question_choices'] = unserialize( $row['question_choices'] );
			$valuecheckbox = ( ! empty( $row['value'] ) ) ? explode( ',', $row['value'] ) : array();
			foreach( $row['question_choices'] as $key => $value )
			{
				$xtpl->assign( 'QUESTION_CHOICES', array(
					'id' => $row['qid'] . '_' . $number++,
					'key' => $key,
					'checked' => ( in_array( $key, $valuecheckbox ) ) ? ' checked="checked"' : '',
					'readonly' => $row['readonly'],
					"value" => $value
				) );
				$xtpl->parse( 'main.loop.checkbox' );
			}
		}
		elseif( $row['question_type'] == 'multiselect' )
		{
			$valueselect = ( ! empty( $row['value'] ) ) ? explode( ',', $row['value'] ) : array();
			$row['question_choices'] = unserialize( $row['question_choices'] );
			foreach( $row['question_choices'] as $key => $value )
			{
				$xtpl->assign( 'QUESTION_CHOICES', array(
					'key' => $key,
					'selected' => ( in_array( $key, $valueselect ) ) ? ' selected="selected"' : '',
					"value" => $value
				) );
				$xtpl->parse( 'main.loop.multiselect.loop' );
			}

			if( $answer_info and ! $row['user_editable'] and isset( $form_info['filled'] ) )
			{
				$row['readonly'] = 'readonly="readonly"';
			}

			$xtpl->assign( 'QUESTION', $row );

			$xtpl->parse( 'main.loop.multiselect' );
		}

		if( $form_info['question_display'] == 'question_display_left' )
		{
			$xtpl->assign( 'LEFT', array( 'label' => 'class="col-sm-6 control-label"', 'div' => 'class="col-sm-18"' ) );
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
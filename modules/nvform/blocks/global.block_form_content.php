<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sat, 10 Dec 2011 06:46:54 GMT
 */

if( !defined( 'NV_MAINFILE' ) )
	die( 'Stop!!!' );

if( !nv_function_exists( 'nv_block_form_content' ) )
{
	function nv_block_config_form_content( $module, $data_block, $lang_block )
	{
		global $site_mods;

		$html = '';
		$html .= '<tr>';
		$html .= '<td>' . $lang_block['formid'] . '</td>';
		$html .= '<td><select name="config_formid" class="form-control">';
		$html .= '<option value="0"> -- </option>';
		$sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $site_mods[$module]['module_data'] . ' WHERE status = 1 ORDER BY weight ASC';
		$list = nv_db_cache( $sql, '', $module );
		foreach( $list as $l )
		{
			$html .= '<option value="' . $l['id'] . '" ' . (($data_block['formid'] == $l['id']) ? ' selected="selected"' : '') . '>' . $l['title'] . '</option>';
		}
		$html .= '</select>';
		$html .= '</tr>';
		$html .= '<tr>';
		$html .= '<td>' . $lang_block['dis_form_info'] . '</td>';
		$ck = $data_block['dis_form_info'] ? 'checked="checked"' : '';
		$html .= '<td><input type="checkbox" name="config_dis_form_info" value="1" ' . $ck . ' /></td>';
		$html .= '</tr>';
		$html .= '<tr>';
		$html .= '<td>' . $lang_block['dis_form_answered'] . '</td>';
		$ck = $data_block['dis_form_answered'] ? 'checked="checked"' : '';
		$html .= '<td><input type="checkbox" name="config_dis_form_answered" value="1" ' . $ck . ' /></td>';
		$html .= '</tr>';

		return $html;
	}

	function nv_block_config_form_content_submit( $module, $lang_block )
	{
		global $nv_Request;
		$return = array( );
		$return['error'] = array( );
		$return['config'] = array( );
		$return['config']['formid'] = $nv_Request->get_int( 'config_formid', 'post', 0 );
		$return['config']['dis_form_info'] = $nv_Request->get_int( 'config_dis_form_info', 'post' );
		$return['config']['dis_form_answered'] = $nv_Request->get_int( 'config_dis_form_answered', 'post' );
		return $return;
	}

	function nv_block_form_content( $block_config )
	{
		global $db, $site_mods, $module_info, $module_name, $lang_module, $my_head, $user_info;

		$module = $block_config['module'];
		$filled = false;
		$answer_info = $old_answer_info = $form_info = array( );

		$form_info = $db->query( 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $site_mods[$module]['module_data'] . ' WHERE status = 1 AND id = ' . $block_config['formid'] )->fetch( );

		if( !empty( $form_info ) )
		{
			if( $form_info['start_time'] > NV_CURRENTTIME or ($form_info['end_time'] > 0 and $form_info['end_time'] < NV_CURRENTTIME) or !nv_user_in_groups( $form_info['groups_view'] ) )
			{
				return '';
			}
			else
			{
				// Lấy thông tin câu hỏi
				$question_info = $db->query( "SELECT * FROM " . NV_PREFIXLANG . '_' . $site_mods[$module]['module_data'] . "_question WHERE fid = " . $block_config['formid'] . " AND status = 1 ORDER BY weight" )->fetchAll( );

				// Trạng thái trả lời
				if( defined( 'NV_IS_USER' ) )
				{
					$sql = "SELECT * FROM " . NV_PREFIXLANG . '_' . $site_mods[$module]['module_data'] . "_answer WHERE fid = " . $block_config['formid'] . " AND who_answer = " . $user_info['userid'];
					$_rows = $db->query( $sql )->fetch();

					if( $_rows )
					{
						$filled = true;
						$form_info['filled'] = true;
						$answer_info = unserialize( $_rows['answer'] );
					}

					if( !empty( $answer_info ) and !$block_config['dis_form_answered'] )
					{
						return '';
					}
				}

				if( file_exists( NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $site_mods[$module]['module_file'] . '/block_form_content.tpl' ) )
				{
					$block_theme = $module_info['template'];
				}
				else
				{
					$block_theme = 'default';
				}

				if( $module != $module_name )
				{
					$my_head .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "js/jquery/jquery.validate.min.js\"></script>\n";
					$my_head .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "js/language/jquery.validator-" . NV_LANG_INTERFACE . ".js\"></script>\n";

					$my_head .= "<script type=\"text/javascript\">\n";
					$my_head .= "$(document).ready(function(){
								$('#question_form').validate({
								});
							 });";
					$my_head .= " </script>\n";

					if( file_exists( NV_ROOTDIR . '/modules/' . $site_mods[$module]['module_file'] . '/language/' . NV_LANG_DATA . '.php' ) )
					{
						require_once NV_ROOTDIR . '/modules/' . $site_mods[$module]['module_file'] . '/language/' . NV_LANG_DATA . '.php';
					}
				}
				else
				{
					return '';
				}

				$xtpl = new XTemplate( 'block_form_content.tpl', NV_ROOTDIR . '/themes/' . $block_theme . '/modules/' . $site_mods[$module]['module_file'] );
				$xtpl->assign( 'LANG', $lang_module );
				$xtpl->assign( 'FORM', $form_info );
				$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
				$xtpl->assign( 'FORM_ACTION', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module . '/' . $form_info['id'] . '-' . $form_info['alias'] );

				if( $block_config['dis_form_info'] )
				{
					$xtpl->parse( 'main.form_info' );
				}

				if( $form_info['question_display'] == 'question_display_left' )
				{
					$xtpl->assign( 'FORM_LEFT', 'class="form-horizontal"' );
				}

				foreach( $question_info as $row )
				{
					$row['value'] = isset( $answer_info[$row['qid']] ) ? $answer_info[$row['qid']] : '';
					$row['required'] = ($row['required']) ? 'required' : '';
					$xtpl->assign( 'QUESTION', $row );

					if( $row['required'] )
					{
						$xtpl->parse( 'main.loop.required' );
					}
					if( $row['question_type'] == 'textbox' or $row['question_type'] == 'number' )
					{
						if( $answer_info and !$row['user_editable'] and isset( $form_info['filled'] ) )
						{
							$row['readonly'] = 'readonly="readonly"';
						}
						$xtpl->assign( 'QUESTION', $row );
						$xtpl->parse( 'main.loop.textbox' );
					}
					elseif( $row['question_type'] == 'date' )
					{
						$row['value'] = ( empty( $row['value'] )) ? '' : date( 'd/m/Y', $row['value'] );
						$row['datepicker'] = ($answer_info and !$row['user_editable'] and isset( $form_info['filled'] )) ? '' : 'datepicker';
						$xtpl->assign( 'QUESTION', $row );
						$xtpl->parse( 'main.loop.date' );
					}
					elseif( $row['question_type'] == 'textarea' )
					{
						if( $answer_info and !$row['user_editable'] and isset( $form_info['filled'] ) )
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
							require_once NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php';
						}
						elseif( !nv_function_exists( 'nv_aleditor' ) and file_exists( NV_ROOTDIR . '/' . NV_EDITORSDIR . '/ckeditor/ckeditor_php5.php' ) )
						{
							define( 'NV_EDITOR', true );
							define( 'NV_IS_CKEDITOR', true );
							require_once NV_ROOTDIR . '/' . NV_EDITORSDIR . '/ckeditor/ckeditor_php5.php';

							function nv_aleditor( $textareaname, $width = '100%', $height = '450px', $val = '' )
							{
								// Create class instance.
								$editortoolbar = array(
									array(
										'Link',
										'Unlink',
										'Image',
										'Table',
										'Font',
										'FontSize',
										'RemoveFormat'
									),
									array(
										'Bold',
										'Italic',
										'Underline',
										'StrikeThrough',
										'-',
										'Subscript',
										'Superscript',
										'-',
										'JustifyLeft',
										'JustifyCenter',
										'JustifyRight',
										'JustifyBlock',
										'OrderedList',
										'UnorderedList',
										'-',
										'Outdent',
										'Indent',
										'TextColor',
										'BGColor',
										'Source'
									)
								);
								$CKEditor = new CKEditor( );

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
								if( !empty( $width ) )
								{
									$CKEditor->config['width'] = strpos( $width, '%' ) ? $width : intval( $width );
								}
								if( !empty( $height ) )
								{
									$CKEditor->config['height'] = strpos( $height, '%' ) ? $height : intval( $height );
								}
								// Change default textarea attributes
								$CKEditor->textareaAttributes = array(
									'cols' => 80,
									'rows' => 10
								);
								$val = nv_unhtmlspecialchars( $val );
								return $CKEditor->editor( $textareaname, $val );
							}

						}

						if( defined( 'NV_EDITOR' ) and nv_function_exists( 'nv_aleditor' ) )
						{
							$row['value'] = nv_htmlspecialchars( nv_editor_br2nl( $row['value'] ) );

							$edits = nv_aleditor( 'question[' . $row['qid'] . ']', '100%', '350px', $row['value'] );
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
								'selected' => ($key == $row['value']) ? ' selected="selected"' : '',
								"value" => $value
							) );
							$xtpl->parse( 'main.loop.select.loop' );
						}

						if( $answer_info and !$row['user_editable'] and isset( $form_info['filled'] ) )
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
							if( $answer_info and !$row['user_editable'] and isset( $form_info['filled'] ) )
							{
								$row['readonly'] = 'onclick="return false;"';
							}
							$xtpl->assign( 'QUESTION_CHOICES', array(
								'id' => $row['qid'] . '_' . $number++,
								'key' => $key,
								'checked' => ($key == $row['value']) ? ' checked="checked"' : '',
								'readonly' => $row['readonly'],
								"value" => $value
							) );
							$xtpl->parse( 'main.loop.radio' );
						}
					}
					elseif( $row['question_type'] == 'checkbox' )
					{
						$row['readonly'] = '';
						if( $answer_info and !$row['user_editable'] and isset( $form_info['filled'] ) )
						{
							$row['readonly'] = 'onclick="return false;"';
						}

						$number = 0;
						$row['question_choices'] = unserialize( $row['question_choices'] );
						$valuecheckbox = (!empty( $row['value'] )) ? explode( ',', $row['value'] ) : array( );
						foreach( $row['question_choices'] as $key => $value )
						{
							$xtpl->assign( 'QUESTION_CHOICES', array(
								'id' => $row['qid'] . '_' . $number++,
								'key' => $key,
								'checked' => ( in_array( $key, $valuecheckbox )) ? ' checked="checked"' : '',
								'readonly' => $row['readonly'],
								"value" => $value
							) );
							$xtpl->parse( 'main.loop.checkbox' );
						}
					}
					elseif( $row['question_type'] == 'multiselect' )
					{
						$valueselect = (!empty( $row['value'] )) ? explode( ',', $row['value'] ) : array( );
						$row['question_choices'] = unserialize( $row['question_choices'] );
						foreach( $row['question_choices'] as $key => $value )
						{
							$xtpl->assign( 'QUESTION_CHOICES', array(
								'key' => $key,
								'selected' => ( in_array( $key, $valueselect )) ? ' selected="selected"' : '',
								"value" => $value
							) );
							$xtpl->parse( 'main.loop.multiselect.loop' );
						}

						if( $answer_info and !$row['user_editable'] and isset( $form_info['filled'] ) )
						{
							$row['readonly'] = 'readonly="readonly"';
						}

						$xtpl->assign( 'QUESTION', $row );

						$xtpl->parse( 'main.loop.multiselect' );
					}

					if( $form_info['question_display'] == 'question_display_left' )
					{
						$xtpl->assign( 'LEFT', array(
							'label' => 'class="col-sm-6 control-label"',
							'div' => 'class="col-sm-18"'
						) );
					}

					$xtpl->parse( 'main.loop' );
				}

				$xtpl->parse( 'main' );
				return $xtpl->text( 'main' );
			}
		}
	}

}

if( defined( 'NV_SYSTEM' ) )
{
	$module = $block_config['module'];
	$content = nv_block_form_content( $block_config );
}

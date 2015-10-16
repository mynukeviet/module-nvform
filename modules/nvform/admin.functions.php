<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Tue, 08 Apr 2014 15:13:43 GMT
 */

if ( ! defined( 'NV_ADMIN' ) or ! defined( 'NV_MAINFILE' ) or ! defined( 'NV_IS_MODADMIN' ) ) die( 'Stop!!!' );

$submenu['form_content'] = $lang_module['form_add'];
$submenu['question'] = $lang_module['question_list'];
$submenu['question_content'] = $lang_module['question_add'];

$allow_func = array( 'main', 'config','form_content', 'alias', 'change_status', 'change_weight', 'question', 'question_content', 'report', 'export' );

define( 'NV_IS_FILE_ADMIN', true );

// Danh sach cac kieu du lieu
$array_field_type = array(
	'number' => $lang_module['question_type_number'],
	'date' => $lang_module['question_type_date'],
	'time' => $lang_module['question_type_time'],
	'textbox' => $lang_module['question_type_textbox'],
	'textarea' => $lang_module['question_type_textarea'],
	'editor' => $lang_module['question_type_editor'],
	'select' => $lang_module['question_type_select'],
	'radio' => $lang_module['question_type_radio'],
	'checkbox' => $lang_module['question_type_checkbox'],
	'multiselect' => $lang_module['question_type_multiselect'],
	'grid' => $lang_module['question_type_grid'],
	'table' => $lang_module['question_type_table'],
	'file' => $lang_module['question_type_file'],
	'plaintext' => $lang_module['question_type_plaintext']
);

/**
 * nv_get_plaintext()
 *
 * @param mixed $string
 * @return
 */
function nv_get_plaintext( $string, $keep_image = false, $keep_link = false )
{
	// Get image tags
	if( $keep_image )
	{
		if( preg_match_all( "/\<img[^\>]*src=\"([^\"]*)\"[^\>]*\>/is", $string, $match ) )
		{
			foreach( $match[0] as $key => $_m )
			{
				$textimg = '';
				if( strpos( $match[1][$key], 'data:image/png;base64' ) === false )
				{
					$textimg = " " . $match[1][$key];
				}
				if( preg_match_all( "/\<img[^\>]*alt=\"([^\"]+)\"[^\>]*\>/is", $_m, $m_alt ) )
				{
					$textimg .= " " . $m_alt[1][0];
				}
				$string = str_replace( $_m, $textimg, $string );
			}
		}
	}

	// Get link tags
	if( $keep_link )
	{
		if( preg_match_all( "/\<a[^\>]*href=\"([^\"]+)\"[^\>]*\>(.*)\<\/a\>/isU", $string, $match ) )
		{
			foreach( $match[0] as $key => $_m )
			{
				$string = str_replace( $_m, $match[1][$key] . " " . $match[2][$key], $string );
			}
		}
	}

	$string = str_replace( '&nbsp;', ' ', strip_tags( $string ) );
	return preg_replace( '/[ ]+/', ' ', $string );
}
<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Tue, 08 Apr 2014 15:13:43 GMT
 */

if ( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$xtpl = new XTemplate( $op . '.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
$xtpl->assign( 'NV_LANG_INTERFACE', NV_LANG_INTERFACE );

// Danh sach cac bieu mau hien co
$sql = 'SELECT id, title FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE status = 1 ORDER BY weight ASC';
$lform = $db->query( $sql )->fetchAll();

$num = sizeof( $lform );
if( $num < 1 )
{
	Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=form_content' );
	die();
}

$qid = $nv_Request->get_int( 'qid', 'get, post', 0 );
$fid = $nv_Request->get_int( 'fid', 'get, post', 0 );
$question = array();
$question_choices = array();
$error = '';
$text_questions = $number_questions = $date_questions = $choice_questions = $choice_type_text = 0;

if( $qid )
{
	$lang_submit = $lang_module['question_edit'];
	// Bind data to form
	$question = $db->query( 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_question WHERE qid=' . $qid )->fetch();

	if( ! $question )
	{
		Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=question' );
		die();
	}
	
	if( ! empty( $question['question_choices'] ) )
	{
		$question_choices = unserialize( $question['question_choices'] );
	}
	
	$question['question_form'] = $question['fid'];
	$question['default_value_number'] = $question['default_value'];
	
	$action = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;qid=' . $qid;
}
else 
{
	$action = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op;
	$lang_submit = $lang_module['question_add'];
	$question['required'] = 0;
	$question['user_editable'] = 0;
	$question['question_type'] = 'textbox';
	$question['question_form'] = $fid;
	$question['match_type'] = 'none';
	$question['min_length'] = 0;
	$question['max_length'] = 255;
	$question['match_regex'] = $question['func_callback'] = '';
	$question['class'] = 'input';
	$question['default_value_number'] = 0;
	$question['min_number'] = 0;
	$question['max_number'] = 1000;
	$question['number_type_1'] = ' checked="checked"';
	$question['current_date_0'] = ' checked="checked"';	
}

if( $nv_Request->isset_request( 'submit', 'post' ) )
{
	$preg_replace = array( 'pattern' => '/[^a-zA-Z0-9\_]/', 'replacement' => '' );

	$question['question'] = $nv_Request->get_title( 'question', 'post', '' );
	$question['required'] = $nv_Request->get_int( 'required', 'post', 0 );
	$question['user_editable'] = $nv_Request->get_int( 'user_editable', 'post', 0 );
	$question['class'] = nv_substr( $nv_Request->get_title( 'class', 'post', '', 0, $preg_replace ), 0, 50);
	
	if( $qid )
	{
		$data_old = $db->query( 'SELECT fid, question_type FROM ' . NV_PREFIXLANG . '_' . $module_data . '_question WHERE qid=' . $qid )->fetch();
		$question['question_form'] = $data_old['fid'];
		$question['question_type'] = $data_old['question_type'];
	}
	else 
	{
		$question['question_form'] = $nv_Request->get_int( 'question_form', 'post', 0 );
		$question['question_type'] = nv_substr( $nv_Request->get_title( 'question_type', 'post', '', 0, $preg_replace ), 0, 50);
	}
	
	if( $question['question_type'] == 'textbox' || $question['question_type'] == 'textarea' || $question['question_type'] == 'editor' )
	{
		$text_questions = 1;
		$question['match_type'] = nv_substr( $nv_Request->get_title( 'match_type', 'post', '', 0, $preg_replace ), 0, 50);
		$question['match_regex'] = ( $question['match_type'] == 'regex' ) ? $nv_Request->get_string( 'match_regex', 'post', '', false ) : '';
		$question['func_callback'] = ( $question['match_type'] == 'callback' ) ? $nv_Request->get_string( 'match_callback', 'post', '', false ) : '';
		if( $question['func_callback'] != '' and ! function_exists( $question['func_callback'] ) )
		{
			$question['func_callback'] = '';
		}

		$question['min_length'] = $nv_Request->get_int( 'min_length', 'post', 255 );
		$question['max_length'] = $nv_Request->get_int( 'max_length', 'post', 255 );
		$question['default_value'] = $nv_Request->get_title( 'default_value', 'post', '' );
		$question['question_choices'] = '';
	}
	elseif( $question['question_type'] == 'number' )
	{
		$number_questions = 1;
		$question['number_type'] = $nv_Request->get_int( 'number_type', 'post', 1 ); // 1: So nguyen, 2: So thuc
		if( $question['number_type'] == 1 )
		{
			$question['default_value_number'] = $nv_Request->get_int( 'default_value_number', 'post', 0 );
		}
		else
		{
			$question['default_value_number'] = $nv_Request->get_float( 'default_value_number', 'post', 0 );
		}
		$question['min_length'] = $nv_Request->get_int( 'min_number_length', 'post', 0 );
		$question['max_length'] = $nv_Request->get_int( 'max_number_length', 'post', 0 );
		$question['match_type'] = 'none';
		$question['match_regex'] = $question['func_callback'] = '';

		$question_choices['number_type'] = $question['number_type'];
		$question['default_value'] = $question['default_value_number'];

		if( $question['min_length'] >= $question['max_length'] )
		{
			$error = $lang_module['question_number_error'];
		}
		else
		{
			$question['question_choices'] = serialize( array( 'number_type' => $question['number_type'] ) );
		}
	}
	elseif( $question['question_type'] == 'date' )
	{
		$date_questions = 1;
		if( preg_match( '/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $nv_Request->get_string( 'min_date', 'post' ), $m ) )
		{
			$question['min_length'] = mktime( 0, 0, 0, $m[2], $m[1], $m[3] );
		}
		else
		{
			$question['min_length'] = 0;
		}
		if( preg_match( '/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $nv_Request->get_string( 'max_date', 'post' ), $m ) )
		{
			$question['max_length'] = mktime( 0, 0, 0, $m[2], $m[1], $m[3] );
		}
		else
		{
			$question['max_length'] = 0;
		}

		$question['current_date'] = $nv_Request->get_int( 'current_date', 'post', 0 );
		if( ! $question['current_date'] and preg_match( '/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $nv_Request->get_string( 'default_date', 'post' ), $m ) )
		{
			$question['default_value'] = mktime( 0, 0, 0, $m[2], $m[1], $m[3] );
		}
		else
		{
			$question['default_value'] = 0;
		}
		$question['match_type'] = 'none';
		$question['match_regex'] = $question['func_callback'] = '';
		$question_choices['current_date'] = $question['current_date'];
		if( $question['min_length'] >= $question['max_length'] )
		{
			$error = $lang_module['question_date_error'];
		}
		else
		{
			$question['question_choices'] = serialize( array( 'current_date' => $question['current_date'] ) );
		}
	}
	else
	{
		$choice_type_text = 1;
		$question['match_type'] = 'none';
		$question['match_regex'] = $question['func_callback'] = '';
		$question['min_length'] = 0;
		$question['max_length'] = 255;
		$question['default_value'] = $nv_Request->get_int( 'default_value_choice', 'post', 0 );

		$question_choice_value = $nv_Request->get_array( 'question_choice', 'post' );
		$question_choice_text = $nv_Request->get_array( 'question_choice_text', 'post' );
		$question_choices = array_combine( array_map( 'strip_punctuation', $question_choice_value ), array_map( 'strip_punctuation', $question_choice_text ) );
		
		if( ! empty( $question_choices ) )
		{
			unset( $question_choices[''] );
			$question['question_choices'] = serialize( $question_choices );
		}
		else
		{
			$error = $lang_module['question_choices_empty'];
		}
	}

	if( empty( $error ) )
	{
		if(  ! $qid )
		{
			$weight = $db->query( "SELECT MAX(weight) FROM " . NV_PREFIXLANG . "_" . $module_data . "_question WHERE fid = " . $question['question_form'] )->fetchColumn();
			$weight = intval( $weight ) + 1;

			$sql = "INSERT INTO " . NV_PREFIXLANG . "_" . $module_data . "_question
				(title, fid, weight, question_type, question_choices, match_type, match_regex, func_callback, min_length, max_length, required, user_editable, class, default_value, status) VALUES
				('" . $question['question'] . "', " . $question['question_form'] . ", " . $weight . ", '" . $question['question_type'] . "', '" . $question['question_choices'] . "', '" . $question['match_type'] . "',
				'" . $question['match_regex'] . "', '" . $question['func_callback'] . "', " . $question['min_length'] . ", " . $question['max_length'] . ",
				" . $question['required'] . ", '" . $question['user_editable'] . "', :class, :default_value, 1)";

			$data_insert = array();
            $data_insert['class'] = $question['class'];
			$data_insert['default_value'] = $question['default_value'];
			$save = $db->insert_id( $sql, 'qid', $data_insert );
		}
		else
		{
			$query = "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_question SET";
			$query .= " question_choices='" . $question['question_choices'] . "', match_type='" . $question['match_type'] . "',
				match_regex='" . $question['match_regex'] . "', func_callback='" . $question['func_callback'] . "', ";
			$query .= " max_length=" . $question['max_length'] . ", min_length=" . $question['min_length'] . ",
				title = '" . $question['question'] . "',
				fid = " . $question['question_form'] . ",
				required = '" . $question['required'] . "',
				question_type = '" . $question['question_type'] . "',
				user_editable = '" . $question['user_editable'] . "',
				class = :class,
				default_value= :default_value
				WHERE qid = " . $qid;
				
			$stmt = $db->prepare( $query ) ;
            $stmt->bindParam( ':class', $question['class'], PDO::PARAM_STR );
			$stmt->bindParam( ':default_value', $question['default_value'], PDO::PARAM_STR, strlen( $question['default_value'] ) );
			$save = $stmt->execute();
		}

		if( $save )
		{
			Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=question&fid=' . $question['question_form'] );
			die();
		}
	}

}

if( ! $qid )
{
	foreach ( $lform as $row )
	{
		$form_list = array(
			'id' => $row['id'],
			'title' => $row['title'],
			'selected' => $question['question_form'] == $row['id'] ? 'selected="selected"' : '' );
		$xtpl->assign( 'FLIST', $form_list );
		$xtpl->parse( 'main.form.flist' );
	}
	$xtpl->parse( 'main.form' );
}
else
{
	$ftitle = $db->query( "SELECT title FROM " . NV_PREFIXLANG . "_" . $module_data . " WHERE id = " . $question['question_form'] )->fetchColumn();
	$xtpl->assign( 'FORM_TEXT', $ftitle );
}

if( $question['question_type'] == 'textbox' || $question['question_type'] == 'textarea' || $question['question_type'] == 'editor' )
{
	$text_questions = 1;
}
elseif( $question['question_type'] == 'number' )
{
	$number_questions = 1;
	$question['min_number'] = $question['min_length'];
	$question['max_number'] = $question['max_length'];
	$question['number_type_1'] = ( $question_choices['number_type'] == 1 ) ? ' checked="checked"' : '';
	$question['number_type_2'] = ( $question_choices['number_type'] == 2 ) ? ' checked="checked"' : '';
}
elseif( $question['question_type'] == 'date' )
{
	$date_questions = 1;
	$question['current_date_1'] = ( $question_choices['current_date'] == 1 ) ? ' checked="checked"' : '';
	$question['current_date_0'] = ( $question_choices['current_date'] == 0 ) ? ' checked="checked"' : '';
	$question['default_date'] = empty( $question['default_value'] ) ? '' : date( 'd/m/Y', $question['default_value'] );
	$question['min_date'] = empty( $question['min_length'] ) ? '' : date( 'd/m/Y', $question['min_length'] );
	$question['max_date'] = empty( $question['max_length'] ) ? '' : date( 'd/m/Y', $question['max_length'] );
}
else
{
	$choice_type_text = 1;
}

// Load các lựa chọn cho select, radio,...
$number = 1;
if( ! empty( $question_choices ) )
{
	foreach( $question_choices as $key => $value )
	{
		$xtpl->assign( 'FIELD_CHOICES', array(
			'checked' => ( $number == $question['default_value'] ) ? ' checked="checked"' : '',
			"number" => $number++,
			'key' => $key,
			'value' => $value
		) );
		$xtpl->parse( 'main.loop_field_choice' );
		$xtpl->assign( 'FIELD_CHOICES_NUMBER', $number );
	}
}
	
$xtpl->assign( 'FIELD_CHOICES', array(
	'number' => $number,
	'key' => '',
	'value' => ''
) );
$xtpl->parse( 'main.loop_field_choice' );
$xtpl->assign( 'FIELD_CHOICES_NUMBER', $number );

// Hien thi tuy chon theo kieu cau hoi
$question['display_textquestions'] = ( $text_questions ) ? '' : 'style="display: none;"';
$question['display_numberquestions'] = ( $number_questions ) ? '' : 'style="display: none;"';
$question['display_datequestions'] = ( $date_questions ) ? '' : 'style="display: none;"';
$question['display_choiceitems'] = ( $choice_type_text ) ? '' : 'style="display: none;"';

$question['editordisabled'] = ( $question['question_type'] != 'editor' ) ? ' style="display: none;"' : '';
$question['classdisabled'] = ( $question['question_type'] == 'editor' ) ? ' style="display: none;"' : '';

$question['checked_required'] = ( $question['required'] ) ? ' checked="checked"' : '';
$question['checked_user_editable'] = ( $question['user_editable'] ) ? ' checked="checked"' : '';

if( ! $qid ) // Neu sua thi khong cho phep thay doi kieu cau hoi
{	
	foreach( $array_field_type as $key => $value )
	{
		$xtpl->assign( 'FIELD_TYPE', array(
			'key' => $key,
			'value' => $value,
			'checked' => ( $question['question_type'] == $key ) ? ' checked="checked"' : ''
		) );
		$xtpl->parse( 'main.question_type' );
	}	
}
else 
{
	$xtpl->assign( 'FIELD_TYPE_TEXT', $array_field_type[$question['question_type']] );
}

// Danh sach kieu rang buoc
$array_match_type = array();
$array_match_type['none'] = $lang_module['question_match_type_none'];
if( $question['question_type'] != 'editor' and $question['question_type'] != 'textarea' )
{
	$array_match_type['alphanumeric'] = $lang_module['question_match_type_alphanumeric'];
	$array_match_type['email'] = $lang_global['email'];
	$array_match_type['url'] = $lang_module['question_match_type_url'];
}
$array_match_type['regex'] = $lang_module['question_match_type_regex'];
$array_match_type['callback'] = $lang_module['question_match_type_callback'];
foreach( $array_match_type as $key => $value )
{
	$xtpl->assign( 'MATCH_TYPE', array(
		'key' => $key,
		'value' => $value,
		'match_value' => ( $key == 'regex' ) ? $question['match_regex'] : $question['func_callback'],
		"checked" => ( $question['match_type'] == $key ) ? ' checked="checked"' : '',
		"match_disabled" => ( $question['match_type'] != $key ) ? ' disabled="disabled"' : ''
	) );

	if( $key == 'regex' or $key == 'callback' )
	{
		$xtpl->parse( 'main.match_type.match_input' );
	}
	$xtpl->parse( 'main.match_type' );
}

if( ! empty( $error ) )
{
	$xtpl->assign( 'ERROR', $error );
	$xtpl->parse( 'main.error' );
}

$page_title = $lang_submit;
$xtpl->assign( 'LANG_SUBMIT', $lang_submit );
$xtpl->assign( 'DATAFORM', $question );
$xtpl->assign( 'FORM_ACTION', $action );
$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';
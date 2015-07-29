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

$allow_func = array( 'main', 'config','form_content', 'alias', 'change_status', 'change_weight', 'question', 'question_content', 'report', 'export_excel' );

define( 'NV_IS_FILE_ADMIN', true );

// Danh sach cac kieu du lieu
$array_field_type = array(
	'number' => $lang_module['question_type_number'],
	'date' => $lang_module['question_type_date'],
	'textbox' => $lang_module['question_type_textbox'],
	'textarea' => $lang_module['question_type_textarea'],
	'editor' => $lang_module['question_type_editor'],
	'select' => $lang_module['question_type_select'],
	'radio' => $lang_module['question_type_radio'],
	'checkbox' => $lang_module['question_type_checkbox'],
	'multiselect' => $lang_module['question_type_multiselect']
);
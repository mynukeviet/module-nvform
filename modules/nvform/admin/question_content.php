<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Tue, 08 Apr 2014 15:13:43 GMT
 */
if (! defined('NV_IS_FILE_ADMIN'))
    die('Stop!!!');

$ini = nv_parse_ini_file(NV_ROOTDIR . '/includes/ini/mime.ini', true);
$myini = array(
    'types' => array(
        ''
    ),
    'exts' => array(
        ''
    ),
    'mimes' => array(
        ''
    )
);

foreach ($ini as $type => $extmime) {
    $myini['types'][] = $type;
    $myini['exts'] = array_merge($myini['exts'], array_keys($extmime));
    $m = array_values($extmime);
    
    if (is_string($m)) {
        $myini['mimes'] = array_merge($myini['mimes'], $m);
    } else {
        foreach ($m as $m2) {
            if (! is_array($m2))
                $m2 = array(
                    $m2
                );
            $myini['mimes'] = array_merge($myini['mimes'], $m2);
        }
    }
}

sort($myini['types']);
unset($myini['types'][0]);
sort($myini['exts']);
unset($myini['exts'][0]);

$xtpl = new XTemplate($op . '.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('MODULE_NAME', $module_name);

// Danh sach cac bieu mau hien co
$sql = 'SELECT id, title FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE status = 1 ORDER BY weight ASC';
$lform = $db->query($sql)->fetchAll();

$num = sizeof($lform);
if ($num < 1) {
    Header('Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=form_content');
    die();
}

$qid = $nv_Request->get_int('qid', 'get, post', 0);
$fid = $nv_Request->get_int('fid', 'get, post', 0);
$question = array();
$question_choices = $question_choices_extend = array();
$error = '';
$text_questions = $editor_questions = $number_questions = $date_questions = $time_questions = $choice_questions = $choice_type_text = $plaintext_question = $grid_questions = $file_questions = 0;

if ($qid) {
    $lang_submit = $lang_module['question_edit'];
    // Bind data to form
    $question = $db->query('SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_question WHERE qid=' . $qid)->fetch();
    
    if (! $question) {
        Header('Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=question');
        die();
    }
    
    if (! empty($question['question_choices'])) {
        $question_choices = unserialize($question['question_choices']);
        $question_choices_extend = unserialize($question['question_choices_extend']);
    }
    
    $question['question_form'] = $question['fid'];
    $question['default_value_number'] = $question['default_value'];
    
    $action = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;qid=' . $qid;
} else {
    $action = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op;
    $lang_submit = $lang_module['question_add'];
    $question['title'] = '';
    $question['required'] = 0;
    $question['user_editable'] = - 1;
    $question['question_type'] = 'textbox';
    $question['question_form'] = $fid;
    $question['match_type'] = 'none';
    $question['min_length'] = 0;
    $question['max_length'] = 255;
    $question['match_regex'] = $question['func_callback'] = '';
    $question['default_value_number'] = 0;
    $question['min_number'] = 0;
    $question['max_number'] = 1000;
    $question['break'] = 0;
    $question['report'] = 1;
    $question['number_type_1'] = ' checked="checked"';
    $question['current_date_0'] = ' checked="checked"';
    $question['current_time_0'] = ' checked="checked"';
    $question['editor_mode_0'] = ' checked="checked"';
    $question['class'] = '';
}

if ($nv_Request->isset_request('submit', 'post')) {
    $preg_replace = array(
        'pattern' => '/[^a-zA-Z0-9\_]/',
        'replacement' => ''
    );
    
    $question['title'] = $nv_Request->get_editor('title', '', NV_ALLOWED_HTML_TAGS);
    $question['required'] = $nv_Request->get_int('required', 'post', 0);
    $question['user_editable'] = $nv_Request->get_int('user_editable', 'post', 0);
    $question['break'] = $nv_Request->get_int('break', 'post', 0);
    $question['report'] = $nv_Request->get_int('report', 'post', 1);
    $question['class'] = $nv_Request->get_title('class', 'post', '');
    
    if ($qid) {
        $data_old = $db->query('SELECT fid, question_type FROM ' . NV_PREFIXLANG . '_' . $module_data . '_question WHERE qid=' . $qid)->fetch();
        $question['question_form'] = $data_old['fid'];
        $question['question_type'] = $data_old['question_type'];
    } else {
        $question['question_form'] = $nv_Request->get_int('question_form', 'post', 0);
        $question['question_type'] = nv_substr($nv_Request->get_title('question_type', 'post', '', 0, $preg_replace), 0, 50);
    }
    
    // Set default value
    $question['default_value'] = '';
    $question['min_length'] = 0;
    $question['max_length'] = 0;
    $question['match_type'] = 'none';
    $question['func_callback'] = '';
    $question['match_regex'] = '';
    
    if ($question['question_type'] == 'textbox' || $question['question_type'] == 'textarea' || $question['question_type'] == 'editor') {
        $text_questions = 1;
        $question['match_type'] = nv_substr($nv_Request->get_title('match_type', 'post', '', 0, $preg_replace), 0, 50);
        $question['match_regex'] = ($question['match_type'] == 'regex') ? $nv_Request->get_string('match_regex', 'post', '', false) : '';
        $question['func_callback'] = ($question['match_type'] == 'callback') ? $nv_Request->get_string('match_callback', 'post', '', false) : '';
        if ($question['func_callback'] != '' and ! function_exists($question['func_callback'])) {
            $question['func_callback'] = '';
        }
        
        $question['min_length'] = $nv_Request->get_int('min_length', 'post', 255);
        $question['max_length'] = $nv_Request->get_int('max_length', 'post', 255);
        $question['default_value'] = $nv_Request->get_title('default_value', 'post', '');
        
        $editor_mode = $nv_Request->get_int('editor_mode', 'post', 0);
        $question['question_choices'] = serialize(array(
            'editor_mode' => $editor_mode
        ));
    } elseif ($question['question_type'] == 'number') {
        $number_questions = 1;
        $question['number_type'] = $nv_Request->get_int('number_type', 'post', 1); // 1: So nguyen, 2: So thuc
        if ($question['number_type'] == 1) {
            $question['default_value_number'] = $nv_Request->get_int('default_value_number', 'post', 0);
        } else {
            $question['default_value_number'] = $nv_Request->get_float('default_value_number', 'post', 0);
        }
        $question['min_length'] = $nv_Request->get_int('min_number_length', 'post', 0);
        $question['max_length'] = $nv_Request->get_int('max_number_length', 'post', 0);
        
        $question_choices['number_type'] = $question['number_type'];
        $question['default_value'] = $question['default_value_number'];
        
        if ($question['min_length'] >= $question['max_length']) {
            $error = $lang_module['question_number_error'];
        } else {
            $question['question_choices'] = serialize(array(
                'number_type' => $question['number_type']
            ));
        }
    } elseif ($question['question_type'] == 'date') {
        $date_questions = 1;
        if (preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $nv_Request->get_string('min_date', 'post'), $m)) {
            $question['min_length'] = mktime(0, 0, 0, $m[2], $m[1], $m[3]);
        }
        
        if (preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $nv_Request->get_string('max_date', 'post'), $m)) {
            $question['max_length'] = mktime(0, 0, 0, $m[2], $m[1], $m[3]);
        }
        
        $question['current_date'] = $nv_Request->get_int('current_date', 'post', 0);
        if (! $question['current_date'] and preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $nv_Request->get_string('default_date', 'post'), $m)) {
            $question['default_value'] = mktime(0, 0, 0, $m[2], $m[1], $m[3]);
        } else {
            $question['default_value'] = 0;
        }
        $question_choices['current_date'] = $question['current_date'];
        if ($question['min_length'] >= $question['max_length']) {
            $error = $lang_module['question_date_error'];
        } else {
            $question['question_choices'] = serialize(array(
                'current_date' => $question['current_date']
            ));
        }
    } elseif ($question['question_type'] == 'time') {
        $time_questions = 1;
        
        $question['current_time'] = $nv_Request->get_int('current_time', 'post', 0);
        if (! $question['current_time'] and preg_match('/^([0-9]{1,2})\:([0-9]{1,2})$/', $nv_Request->get_string('default_time', 'post'), $m)) {
            $question['default_value'] = mktime($m[1], $m[2], 0, 0, 0, 0);
        } else {
            $question['default_value'] = 0;
        }
        $question_choices['current_time'] = $question['current_time'];
        $question['question_choices'] = serialize(array(
            'current_time' => $question['current_time']
        ));
    } elseif ($question['question_type'] == 'grid' or $question['question_type'] == 'table') {
        $grid_questions = 1;
        
        $question_grid = array(
            'col' => $nv_Request->get_array('question_grid_col', 'post', array()),
            'row' => $nv_Request->get_array('question_grid_row', 'post', array())
        );
        
        // Loai bo gia tri rong
        if (! empty($question_grid['col'])) {
            foreach ($question_grid['col'] as $key => $choices) {
                if (empty($choices['key']) or empty($choices['value'])) {
                    unset($question_grid['col'][$key]);
                }
            }
        }
        if (! empty($question_grid['row'])) {
            foreach ($question_grid['row'] as $key => $choices) {
                if (empty($choices['key']) or empty($choices['value'])) {
                    unset($question_grid['row'][$key]);
                }
            }
        }
        
        // Thiet dat gia tri mac dinh
        $default_col = $nv_Request->get_title('question_grid_col_default', 'post');
        $default_col = $question_grid['col'][$default_col]['key'];
        $default_row = $nv_Request->get_title('question_grid_row_default', 'post');
        $default_row = $question_grid['row'][$default_row]['key'];
        $question['default_value'] = $default_col . '||' . $default_row;
        
        $question['question_choices'] = serialize($question_grid);
    } elseif ($question['question_type'] == 'file') {
        $file_questions = 1;
        
        $question['max_length'] = $nv_Request->get_float('nv_max_size', 'post');
        $question['max_length'] = min(nv_converttoBytes(ini_get('upload_max_filesize')), nv_converttoBytes(ini_get('post_max_size')), $question['max_length']);
        
        $question_file['type'] = $nv_Request->get_typed_array('type', 'post', 'int');
        $question_file['type'] = array_flip($question_file['type']);
        $question_file['type'] = array_intersect_key($myini['types'], $question_file['type']);
        $question_file['type'] = implode(',', $question_file['type']);
        
        $question_file['ext'] = $nv_Request->get_typed_array('ext', 'post', 'int');
        $question_file['ext'] = array_flip($question_file['ext']);
        $question_file['ext'] = array_intersect_key($myini['exts'], $question_file['ext']);
        $question_file['ext'][] = 'php';
        $question_file['ext'][] = 'php3';
        $question_file['ext'][] = 'php4';
        $question_file['ext'][] = 'php5';
        $question_file['ext'][] = 'phtml';
        $question_file['ext'][] = 'inc';
        $question_file['ext'] = array_unique($question_file['ext']);
        $question_file['ext'] = implode(',', $question_file['ext']);
        
        $question['question_choices'] = serialize($question_file);
    } else {
        $choice_type_text = 1;
        $question['max_length'] = 255;
        $question['default_value'] = $nv_Request->get_int('default_value_choice', 'post', 0);
        
        $question_choice_value = $nv_Request->get_array('question_choice', 'post');
        $question_choice_text = $nv_Request->get_array('question_choice_text', 'post');
        $question_choices = array_combine(array_map('strip_punctuation', $question_choice_value), array_map('strip_punctuation', $question_choice_text));
        
        if (! empty($question_choices)) {
            unset($question_choices['']);
            $question['question_choices'] = serialize($question_choices);
            
            $question_choice_extend = $nv_Request->get_array('question_choice_extend', 'post');
            foreach ($question_choice_value as $key => $value) {
                if (isset($question_choice_extend[$key])) {
                    $question_choice_extend[$key] = array_diff($question_choice_extend[$key], array(
                        ''
                    ));
                    $question_choice_extend[$value] = $question_choice_extend[$key];
                }
            }
            if (! empty($question_choice_extend)) {
                $question['question_choices_extend'] = serialize($question_choice_extend);
            }
        } else {
            $error = $lang_module['question_choices_empty'];
        }
    }
    
    if (empty($error)) {
        if (! $qid) {
            $weight = $db->query("SELECT MAX(weight) FROM " . NV_PREFIXLANG . "_" . $module_data . "_question WHERE fid = " . $question['question_form'])->fetchColumn();
            $weight = intval($weight) + 1;
            
            $sql = "INSERT INTO " . NV_PREFIXLANG . "_" . $module_data . "_question
				(title, fid, weight, question_type, question_choices, question_choices_extend, match_type, match_regex, func_callback, min_length, max_length, required, user_editable, default_value, break, report, class, status) VALUES
				('" . $question['title'] . "', " . $question['question_form'] . ", " . $weight . ", '" . $question['question_type'] . "', '" . $question['question_choices'] . "', '" . $question['question_choices_extend'] . "', '" . $question['match_type'] . "',
				'" . $question['match_regex'] . "', '" . $question['func_callback'] . "', " . $question['min_length'] . ", " . $question['max_length'] . ",
				" . $question['required'] . ", '" . $question['user_editable'] . "', :default_value, " . $question['break'] . ", " . $question['report'] . ", '" . $question['class'] . "', 1)";
            
            $data_insert = array();
            $data_insert['default_value'] = $question['default_value'];
            $save = $db->insert_id($sql, 'qid', $data_insert);
            if ($save > 0) {
                nv_update_answer($question['question_form']);
            }
        } else {
            $query = "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_question SET";
            $query .= " question_choices='" . $question['question_choices'] . "', question_choices_extend='" . $question['question_choices_extend'] . "', match_type='" . $question['match_type'] . "',
				match_regex='" . $question['match_regex'] . "', func_callback='" . $question['func_callback'] . "', ";
            $query .= " max_length=" . $question['max_length'] . ", min_length=" . $question['min_length'] . ",
				title = '" . $question['title'] . "',
				fid = " . $question['question_form'] . ",
				required = '" . $question['required'] . "',
				question_type = '" . $question['question_type'] . "',
				user_editable = '" . $question['user_editable'] . "',
				default_value= :default_value,
				break = " . $question['break'] . ",
				report = " . $question['report'] . ",
			    class = '" . $question['class'] . "'
				WHERE qid = " . $qid;
            
            $stmt = $db->prepare($query);
            $stmt->bindParam(':default_value', $question['default_value'], PDO::PARAM_STR, strlen($question['default_value']));
            $save = $stmt->execute();
        }
        
        if ($save) {
            Header('Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=question&fid=' . $question['question_form']);
            die();
        }
    }
}

if (! $qid) {
    foreach ($lform as $row) {
        $form_list = array(
            'id' => $row['id'],
            'title' => $row['title'],
            'selected' => $question['question_form'] == $row['id'] ? 'selected="selected"' : ''
        );
        $xtpl->assign('FLIST', $form_list);
        $xtpl->parse('main.form.flist');
    }
    $xtpl->parse('main.form');
} else {
    $ftitle = $db->query("SELECT title FROM " . NV_PREFIXLANG . "_" . $module_data . " WHERE id = " . $question['question_form'])->fetchColumn();
    $xtpl->assign('FORM_TEXT', $ftitle);
}

if ($question['question_type'] == 'textbox' || $question['question_type'] == 'textarea') {
    $text_questions = 1;
} elseif ($question['question_type'] == 'editor') {
    $editor_questions = 1;
    $text_questions = 1;
    $question['editor_mode_0'] = ($question_choices['editor_mode'] == 0) ? ' checked="checked"' : '';
    $question['editor_mode_1'] = ($question_choices['editor_mode'] == 1) ? ' checked="checked"' : '';
} elseif ($question['question_type'] == 'number') {
    $number_questions = 1;
    $question['min_number'] = $question['min_length'];
    $question['max_number'] = $question['max_length'];
    $question['number_type_1'] = ($question_choices['number_type'] == 1) ? ' checked="checked"' : '';
    $question['number_type_2'] = ($question_choices['number_type'] == 2) ? ' checked="checked"' : '';
} elseif ($question['question_type'] == 'date') {
    $date_questions = 1;
    $question['current_date_2'] = ($question_choices['current_date'] == 2) ? ' checked="checked"' : '';
    $question['current_date_1'] = ($question_choices['current_date'] == 1) ? ' checked="checked"' : '';
    $question['current_date_0'] = ($question_choices['current_date'] == 0) ? ' checked="checked"' : '';
    $question['default_date_display'] = '';
    if ($question_choices['current_date'] != 0) {
        $question['default_date_display'] = 'style="display: none"';
    } else {
        $question['default_date'] = empty($question['default_value']) ? '' : date('d/m/Y', $question['default_value']);
    }
    $question['min_date'] = empty($question['min_length']) ? '' : date('d/m/Y', $question['min_length']);
    $question['max_date'] = empty($question['max_length']) ? '' : date('d/m/Y', $question['max_length']);
} elseif ($question['question_type'] == 'time') {
    $time_questions = 1;
    $question['current_time_1'] = ($question_choices['current_time'] == 1) ? ' checked="checked"' : '';
    $question['current_time_0'] = ($question_choices['current_time'] == 0) ? ' checked="checked"' : '';
    $question['default_time'] = empty($question['default_value']) ? '' : date('H:i', $question['default_value']);
} elseif ($question['question_type'] == 'grid' or $question['question_type'] == 'table') {
    $grid_questions = 1;
} elseif ($question['question_type'] == 'file') {
    $file_questions = 1;
} elseif ($question['question_type'] == 'plaintext') {
    $plaintext_questions = 1;
} else {
    $choice_type_text = 1;
}

$number = $number_grid_col = $number_grid_row = 1;
if (! empty($question_choices)) {
    if ($question['question_type'] == 'grid' or $question['question_type'] == 'table') {
        $default_value = explode('||', $question['default_value_number']);
        
        // Loop collumn
        if (! empty($question_choices['col'])) {
            foreach ($question_choices['col'] as $choices) {
                if (! empty($choices['value'])) {
                    $ck = $default_value[0] == $choices['key'] ? 'checked="checked"' : '';
                    $xtpl->assign('COL', array(
                        'number' => $number_grid_col,
                        'key' => $choices['key'],
                        'value' => $choices['value'],
                        'checked' => $ck
                    ));
                    $xtpl->parse('main.loop_question_grid_col');
                    $number_grid_col ++;
                }
            }
            $xtpl->assign('COL_NUMFIELD', $number_grid_col);
        }
        
        // Loop row
        if (! empty($question_choices['row'])) {
            foreach ($question_choices['row'] as $key => $choices) {
                if (! empty($choices['value'])) {
                    $ck = $default_value[1] == $choices['key'] ? 'checked="checked"' : '';
                    $xtpl->assign('ROW', array(
                        'number' => $number_grid_row,
                        'key' => $choices['key'],
                        'value' => $choices['value'],
                        'checked' => $ck
                    ));
                    $xtpl->parse('main.loop_question_grid_row');
                    $number_grid_row ++;
                }
            }
            $xtpl->assign('ROW_NUMFIELD', $number_grid_row);
        }
    } else {
        // Load các lựa chọn cho select, radio,...
        foreach ($question_choices as $key => $value) {
            $xtpl->assign('FIELD_CHOICES', array(
                'checked' => ($number == $question['default_value']) ? ' checked="checked"' : '',
                "number" => $number ++,
                'key' => $key,
                'value' => $value
            ));
            
            if (isset($question_choices_extend[$key])) {
                $number_extend = 0;
                foreach ($question_choices_extend[$key] as $choices_extend) {
                    $xtpl->assign('FIELD_CHOICES_EXTEND', array(
                        "number" => $number_extend ++,
                        'value' => $choices_extend
                    ));
                    $xtpl->parse('main.loop_field_choice.loop_field_choice_extend');
                }
                $xtpl->assign('FIELD_CHOICES_EXTEND_NUMBER', $number_extend);
            }
            $xtpl->parse('main.loop_field_choice');
            $xtpl->assign('FIELD_CHOICES_NUMBER', $number);
        }
    }
}

// grid default
$xtpl->assign('COL', array(
    'number' => $number_grid_col,
    'key' => '',
    'value' => '',
    'checked' => $number_grid_col == 1 ? 'checked="checked"' : ''
));
$xtpl->parse('main.loop_question_grid_col');
$xtpl->assign('COL_NUMFIELD', $number_grid_col);

$xtpl->assign('ROW', array(
    'number' => $number_grid_row,
    'key' => '',
    'value' => '',
    'checked' => $number_grid_row == 1 ? 'checked="checked"' : ''
));
$xtpl->parse('main.loop_question_grid_row');
$xtpl->assign('ROW_NUMFIELD', $number_grid_row);

// field choices default
$xtpl->assign('FIELD_CHOICES', array(
    'number' => $number,
    'key' => '',
    'value' => ''
));
$xtpl->parse('main.loop_field_choice');
$xtpl->assign('FIELD_CHOICES_NUMBER', $number);

// Hien thi tuy chon theo kieu cau hoi
$question['display_editorquestions'] = ($editor_questions) ? '' : 'style="display: none;"';
$question['display_textquestions'] = ($text_questions) ? '' : 'style="display: none;"';
$question['display_numberquestions'] = ($number_questions) ? '' : 'style="display: none;"';
$question['display_datequestions'] = ($date_questions) ? '' : 'style="display: none;"';
$question['display_timequestions'] = ($time_questions) ? '' : 'style="display: none;"';
$question['display_choiceitems'] = ($choice_type_text) ? '' : 'style="display: none;"';
$question['display_gridfields'] = ($grid_questions) ? '' : 'style="display: none;"';
$question['display_filefields'] = ($file_questions) ? '' : 'style="display: none;"';

$question['editordisabled'] = ($question['question_type'] != 'editor') ? ' style="display: none;"' : '';
$question['requireddisabled'] = '';
$question['user_editdisabled'] = '';
if ($question['question_type'] == 'plaintext') {
    $question['requireddisabled'] = ' style="display: none;"';
    $question['user_editdisabled'] = ' style="display: none;"';
    $question['reportdisabled'] = ' disabled="disabled"';
}

$question['checked_required'] = ($question['required']) ? ' checked="checked"' : '';
$question['checked_break'] = ($question['break']) ? ' checked="checked"' : '';
$question['checked_report'] = (! $question['report']) ? ' checked="checked"' : '';

if (! $qid) // Neu sua thi khong cho phep thay doi kieu cau hoi
{
    foreach ($array_field_type as $key => $value) {
        $xtpl->assign('FIELD_TYPE', array(
            'key' => $key,
            'value' => $value,
            'checked' => ($question['question_type'] == $key) ? ' checked="checked"' : ''
        ));
        $xtpl->parse('main.question_type');
    }
} else {
    $xtpl->assign('FIELD_TYPE_TEXT', $array_field_type[$question['question_type']]);
}

// Danh sach kieu rang buoc
$array_match_type = array();
$array_match_type['none'] = $lang_module['question_match_type_none'];
if ($question['question_type'] != 'editor' and $question['question_type'] != 'textarea') {
    $array_match_type['alphanumeric'] = $lang_module['question_match_type_alphanumeric'];
    $array_match_type['email'] = $lang_global['email'];
    $array_match_type['url'] = $lang_module['question_match_type_url'];
}
$array_match_type['regex'] = $lang_module['question_match_type_regex'];
$array_match_type['callback'] = $lang_module['question_match_type_callback'];
foreach ($array_match_type as $key => $value) {
    $xtpl->assign('MATCH_TYPE', array(
        'key' => $key,
        'value' => $value,
        'match_value' => ($key == 'regex') ? $question['match_regex'] : $question['func_callback'],
        "checked" => ($question['match_type'] == $key) ? ' checked="checked"' : '',
        "match_disabled" => ($question['match_type'] != $key) ? ' disabled="disabled"' : ''
    ));
    
    if ($key == 'regex' or $key == 'callback') {
        $xtpl->parse('main.match_type.match_input');
    }
    $xtpl->parse('main.match_type');
}

$sys_max_size = min(nv_converttoBytes(ini_get('upload_max_filesize')), nv_converttoBytes(ini_get('post_max_size')));
$p_size = $sys_max_size / 100;
for ($index = 1; $index <= 100; ++ $index) {
    $size = floor($index * $p_size);
    
    $xtpl->assign('SIZE', array(
        'key' => $size,
        'title' => nv_convertfromBytes($size),
        'selected' => ($size == $question['max_length']) ? ' selected="selected"' : ''
    ));
    
    $xtpl->parse('main.size');
}

$question_choices['type'] = ! empty($question_choices['type']) ? explode(',', $question_choices['type']) : array();
foreach ($myini['types'] as $key => $name) {
    $xtpl->assign('TYPES', array(
        'key' => $key,
        'title' => $name,
        'checked' => in_array($name, $question_choices['type']) ? ' checked="checked"' : ''
    ));
    $xtpl->parse('main.types');
}

$question_choices['ext'] = ! empty($question_choices['ext']) ? explode(',', $question_choices['ext']) : array();
foreach ($myini['exts'] as $key => $name) {
    $xtpl->assign('EXTS', array(
        'key' => $key,
        'title' => $name,
        'checked' => in_array($name, $question_choices['ext']) ? ' checked="checked"' : ''
    ));
    $xtpl->parse('main.exts');
}

if (defined('NV_EDITOR'))
    require_once NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php';

$question['title'] = htmlspecialchars(nv_editor_br2nl($question['title']));
if (defined('NV_EDITOR') and nv_function_exists('nv_aleditor')) {
    $question['title'] = nv_aleditor('title', '100%', '200px', $question['title'], 'Basic');
} else {
    $question['title'] = '<textarea style="width:100%;height:200px" name="title">' . $question['title'] . '</textarea>';
}

$array_user_editable = array(
    '-1' => $lang_module['form_user_editable_form'],
    '1' => $lang_global['yes'],
    '0' => $lang_global['no']
);
foreach ($array_user_editable as $key => $value) {
    $ck = $key == $question['user_editable'] ? 'checked="checked"' : '';
    $xtpl->assign('EDITABLE', array(
        'key' => $key,
        'value' => $value,
        'checked' => $ck
    ));
    $xtpl->parse('main.user_editable');
}

if (! empty($error)) {
    $xtpl->assign('ERROR', $error);
    $xtpl->parse('main.error');
}

$xtpl->assign('LANG_SUBMIT', $lang_submit);
$xtpl->assign('DATAFORM', $question);
$xtpl->assign('FORM_ACTION', $action);

$page_title = $lang_submit;

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 12/5/2012 11:29
 */
if (! defined('NV_MAINFILE'))
    die('Stop!!!');

$i = 1;
$page = 1;
$break = 0;
$datepicker = 0;
foreach ($question_info as $row) {
    // Giá trị mặc định
    $row['value'] = isset($answer_info[$row['qid']]) ? $answer_info[$row['qid']] : $row['default_value'];
    $row['required'] = ($row['required']) ? 'required' : '';
    $row['user_editable'] = $row['user_editable'] == - 1 ? $form_info['user_editable'] : $row['user_editable'];
    $xtpl->assign('QUESTION', $row);
    
    if ($row['required']) {
        $xtpl->parse('main.loop.required');
    }
    if ($row['question_type'] == 'textbox' or $row['question_type'] == 'number') {
        if ($answer_info and ! $row['user_editable'] and isset($form_info['filled'])) {
            $row['readonly'] = 'readonly="readonly"';
        }
        $xtpl->assign('QUESTION', $row);
        $xtpl->parse('main.loop.textbox');
    } elseif ($row['question_type'] == 'date') {
        $datepicker = 1;
        $row['question_choices'] = unserialize($row['question_choices']);
        if ($row['question_choices']['current_date'] == 1) {
            $row['value'] = NV_CURRENTTIME;
        }
        $row['value'] = (empty($row['value'])) ? '' : date('d/m/Y', $row['value']);
        $row['datepicker'] = ($answer_info and ! $row['user_editable'] and isset($form_info['filled'])) ? '' : 'datepicker';
        $xtpl->assign('QUESTION', $row);
        $xtpl->parse('main.loop.date');
    } elseif ($row['question_type'] == 'time') {
        $row['question_choices'] = unserialize($row['question_choices']);
        $row['value'] = $row['question_choices']['current_time'] ? NV_CURRENTTIME : $row['value'];
        $row['value'] = (empty($row['value'])) ? '' : date('H:i', $row['value']);
        $xtpl->assign('QUESTION', $row);
        $xtpl->parse('main.loop.time');
    } elseif ($row['question_type'] == 'textarea') {
        if ($answer_info and ! $row['user_editable'] and isset($form_info['filled'])) {
            $row['readonly'] = 'readonly';
        }
        $row['value'] = nv_htmlspecialchars(nv_br2nl($row['value']));
        $xtpl->assign('QUESTION', $row);
        $xtpl->parse('main.loop.textarea');
    } elseif ($row['question_type'] == 'editor') {
        if (! defined('NV_EDITOR_LOADED')) {
            if (defined('NV_EDITOR')) {
                require_once NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php';
            } elseif (! nv_function_exists('nv_aleditor') and file_exists(NV_ROOTDIR . '/' . NV_EDITORSDIR . '/ckeditor/ckeditor.js')) {
                define('NV_EDITOR', true);
                define('NV_IS_CKEDITOR', true);
                $my_head .= '<script type="text/javascript" src="' . NV_BASE_SITEURL . NV_EDITORSDIR . '/ckeditor/ckeditor.js"></script>';

                function nv_aleditor($textareaname, $width = '100%', $height = '450px', $val = '', $customtoolbar = '')
                {
                    global $module_data;
                    
                    $return = '<textarea style="width: ' . $width . '; height:' . $height . ';" id="' . $module_data . '_' . $textareaname . '" name="' . $textareaname . '">' . $val . '</textarea>';
                    $return .= "<script type=\"text/javascript\">
					CKEDITOR.replace( '" . $module_data . "_" . $textareaname . "', {" . (! empty($customtoolbar) ? 'toolbar : "' . $customtoolbar . '",' : '') . " width: '" . $width . "',height: '" . $height . "',});
					</script>";
                    return $return;
                }
            }
            define('NV_EDITOR_LOADED', true);
        }
        
        if (defined('NV_EDITOR') and nv_function_exists('nv_aleditor')) {
            $row['question_choices'] = unserialize($row['question_choices']);
            $row['value'] = nv_htmlspecialchars(nv_editor_br2nl($row['value']));
            
            $edits = nv_aleditor('question[' . $row['qid'] . ']', '100%', '350px', $row['value'], ! $row['question_choices']['editor_mode'] ? 'Basic' : '');
            $xtpl->assign('EDITOR', $edits);
            $xtpl->parse('main.loop.editor');
        } else {
            $row['value'] = nv_htmlspecialchars(nv_br2nl($row['value']));
            $row['class'] = '';
            $xtpl->assign('QUESTION', $row);
            $xtpl->parse('main.loop.textarea');
        }
    } elseif ($row['question_type'] == 'select') {
        $row['question_choices'] = unserialize($row['question_choices']);
        $row['question_choices_extend'] = ! empty($row['question_choices_extend']) ? unserialize($row['question_choices_extend']) : array();
        
        foreach ($row['question_choices'] as $key => $value) {
            $xtpl->assign('QUESTION_CHOICES', array(
                'key' => $key,
                'selected' => ($key == $row['value']) ? ' selected="selected"' : '',
                'display' => ($key == $row['value']) ? 'style="display: block"' : 'style="display: none"',
                "value" => $value
            ));
            $xtpl->parse('main.loop.select.loop');
            
            if (isset($row['question_choices_extend'][$key])) {
                $number = 0;
                if ($answer_info and ! $row['user_editable'] and isset($form_info['filled'])) {
                    $readonly = 'readonly="readonly"';
                }
                foreach ($row['question_choices_extend'][$key] as $key => $value) {
                    $xtpl->assign('FIELD_CHOICES_EXTEND', array(
                        "key" => $key,
                        'value' => isset($answer_info_extend[$row['qid']][$number][$key]) ? $answer_info_extend[$row['qid']][$number][$key] : '',
                        'text' => $value,
                        'number' => $number ++,
                        'readonly' => $readonly
                    ));
                    $xtpl->parse('main.loop.select.choice_extend.loop');
                }
                $xtpl->parse('main.loop.select.choice_extend');
            }
        }
        
        if ($answer_info and ! $row['user_editable'] and isset($form_info['filled'])) {
            $row['readonly'] = 'readonly="readonly"';
        }
        $xtpl->assign('QUESTION', $row);
        
        $xtpl->parse('main.loop.select');
    } elseif ($row['question_type'] == 'radio') {
        $number = 0;
        $row['question_choices'] = unserialize($row['question_choices']);
        $row['question_choices_extend'] = ! empty($row['question_choices_extend']) ? unserialize($row['question_choices_extend']) : array();
        
        foreach ($row['question_choices'] as $key => $value) {
            $readonly = '';
            $row['readonly'] = '';
            if ($answer_info and ! $row['user_editable'] and isset($form_info['filled'])) {
                $row['readonly'] = 'onclick="return false;"';
                $readonly = 'readonly="readonly"';
            }
            $xtpl->assign('QUESTION_CHOICES', array(
                'id' => $row['qid'] . '_' . $number ++,
                'key' => $key,
                'checked' => ($key == $row['value']) ? ' checked="checked"' : '',
                'display' => ($key == $row['value']) ? 'style="display: block"' : 'style="display: none"',
                'readonly' => $row['readonly'],
                "value" => $value,
                'number' => $number
            ));
            
            if (isset($row['question_choices_extend'][$key])) {
                foreach ($row['question_choices_extend'][$key] as $key => $value) {
                    $xtpl->assign('FIELD_CHOICES_EXTEND', array(
                        "key" => $key,
                        'value' => isset($answer_info_extend[$row['qid']][$number][$key]) ? $answer_info_extend[$row['qid']][$number][$key] : '',
                        'text' => $value,
                        'readonly' => $readonly
                    ));
                    $xtpl->parse('main.loop.radio.choice_extend.loop');
                }
                
                $xtpl->parse('main.loop.radio.choice_extend');
            }
            
            $xtpl->parse('main.loop.radio');
        }
    } elseif ($row['question_type'] == 'checkbox') {
        $row['readonly'] = '';
        if ($answer_info and ! $row['user_editable'] and isset($form_info['filled'])) {
            $row['readonly'] = 'onclick="return false;"';
        }
        
        $number = 0;
        $row['question_choices'] = unserialize($row['question_choices']);
        $valuecheckbox = (! empty($row['value'])) ? explode(',', $row['value']) : array();
        foreach ($row['question_choices'] as $key => $value) {
            $xtpl->assign('QUESTION_CHOICES', array(
                'id' => $row['qid'] . '_' . $number ++,
                'key' => $key,
                'checked' => (in_array($key, $valuecheckbox)) ? ' checked="checked"' : '',
                'readonly' => $row['readonly'],
                "value" => $value
            ));
            $xtpl->parse('main.loop.checkbox');
        }
    } elseif ($row['question_type'] == 'multiselect') {
        $valueselect = (! empty($row['value'])) ? explode(',', $row['value']) : array();
        $row['question_choices'] = unserialize($row['question_choices']);
        foreach ($row['question_choices'] as $key => $value) {
            $xtpl->assign('QUESTION_CHOICES', array(
                'key' => $key,
                'selected' => (in_array($key, $valueselect)) ? ' selected="selected"' : '',
                "value" => $value
            ));
            $xtpl->parse('main.loop.multiselect.loop');
        }
        
        if ($answer_info and ! $row['user_editable'] and isset($form_info['filled'])) {
            $row['readonly'] = 'readonly="readonly"';
        }
        
        $xtpl->assign('QUESTION', $row);
        
        $xtpl->parse('main.loop.multiselect');
    } elseif ($row['question_type'] == 'grid') {
        $question_choices = unserialize($row['question_choices']);
        
        // Loop collumn
        if (! empty($question_choices['col'])) {
            foreach ($question_choices['col'] as $choices) {
                $xtpl->assign('COL', array(
                    'key' => $choices['key'],
                    'value' => $choices['value']
                ));
                $xtpl->parse('main.loop.grid.col');
            }
        }
        
        // Loop row
        if (! empty($question_choices['row'])) {
            foreach ($question_choices['row'] as $choices) {
                $xtpl->assign('ROW', array(
                    'key' => $choices['key'],
                    'value' => $choices['value']
                ));
                
                if (! empty($question_choices['col'])) {
                    foreach ($question_choices['col'] as $col) {
                        $value = $col['key'] . '||' . $choices['key'];
                        $ck = $row['value'] == $value ? 'checked' : '';
                        $xtpl->assign('GRID', array(
                            'value' => $value,
                            'checked' => $ck
                        ));
                        $xtpl->parse('main.loop.grid.row.td');
                    }
                }
                
                $xtpl->parse('main.loop.grid.row');
            }
        }
        
        $xtpl->parse('main.loop.grid');
    } elseif ($row['question_type'] == 'table') {
        $question_choices = unserialize($row['question_choices']);
        $row['value'] = isset($answer_info[$row['qid']]) ? $answer_info[$row['qid']] : '';
        
        // Loop collumn
        if (! empty($question_choices['col'])) {
            foreach ($question_choices['col'] as $choices) {
                $xtpl->assign('COL', array(
                    'key' => $choices['key'],
                    'value' => $choices['value']
                ));
                $xtpl->parse('main.loop.table.col');
            }
        }
        
        // Loop row
        if (! empty($question_choices['row'])) {
            foreach ($question_choices['row'] as $choices) {
                $xtpl->assign('ROW', array(
                    'key' => $choices['key'],
                    'value' => $choices['value']
                ));
                
                if (! empty($question_choices['col'])) {
                    foreach ($question_choices['col'] as $col) {
                        $xtpl->assign('NAME', array(
                            'col' => $col['key'],
                            'row' => $choices['key']
                        ));
                        $xtpl->assign('VALUE', isset($row['value'][$col['key']][$choices['key']]) ? $row['value'][$col['key']][$choices['key']] : '');
                        $xtpl->parse('main.loop.table.row.td');
                    }
                }
                
                $xtpl->parse('main.loop.table.row');
            }
        }
        
        $xtpl->parse('main.loop.table');
    } elseif ($row['question_type'] == 'file') {
        $row['value'] = str_replace('form_' . $row['qid'] . '/', '', $row['value']);
        $row['question_choices'] = unserialize($row['question_choices']);
        $row['file_type'] = str_replace(',', ', ', $row['question_choices']['type']);
        $xtpl->assign('QUESTION', $row);
        
        $xtpl->parse('main.loop.file');
        $xtpl->parse('main.enctype');
    }
    
    if ($form_info['question_display'] == 'question_display_left') {
        $xtpl->parse('main.loop.display_left_label');
        $xtpl->parse('main.loop.display_left_div');
    } elseif ($form_info['question_display'] == 'question_display_two_column') {
        $xtpl->parse('main.loop.display_two_column');
    }
    
    if ($row['break']) {
        $page ++;
        $break ++;
    }
    $xtpl->assign('PAGE', $page);
    
    $xtpl->parse('main.loop');
    $i ++;
}

if (isset($dis_title) and $dis_title) {
    $xtpl->parse('main.dis_title');
}

if (isset($dis_description) and $dis_description) {
    $xtpl->parse('main.dis_description');
}

if (isset($dis_description_html) and $dis_description_html) {
    $xtpl->parse('main.dis_description_html');
}

if (isset($dis_form_info) and $dis_form_info) {
    $xtpl->parse('main.dis_form_info');
}

if ($datepicker) {
    $xtpl->parse('main.datepicker');
}

if (empty($break)) {
    $xtpl->assign('BREAK_PAGE', 'style="display: none;"');
}
$xtpl->assign('MAX_PAGE', $page);
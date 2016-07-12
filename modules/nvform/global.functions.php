<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Tue, 08 Apr 2014 15:13:43 GMT
 */
if (! defined('NV_MAINFILE'))
    die('Stop!!!');

function nv_form_result($question_data, $answer_data)
{
    global $lang_module, $global_config, $module_info, $module_name, $module_data, $module_file;
    
    $xtpl = new XTemplate('view_answer.tpl', NV_ROOTDIR . '/themes/' . $global_config['site_theme'] . '/modules/' . $module_file);
    $xtpl->assign('LANG', $lang_module);
    
    if (! empty($question_data)) {
        foreach ($question_data as $data) {
            $qid = $data['qid'];
            $data['title'] = nv_get_plaintext($data['title']);
            $xtpl->assign('QUESTION', $data);
            
            $answer = $answer_data;
            if (isset($answer[$qid]) and $data['report']) {
                $ans = $answer[$qid];
                $question_type = $data['question_type'];
                
                if ($question_type == 'plaintext')
                    continue;
                
                if ($question_type == 'multiselect' or $question_type == 'select' or $question_type == 'radio' or $question_type == 'checkbox') {
                    $data = unserialize($data['question_choices']);
                    if ($question_type == 'checkbox') {
                        $result = explode(',', $ans);
                        foreach ($result as $key) {
                            $answer_result .= $data[$key] . "<br />";
                        }
                    } else {
                        $answer_result = $data[$ans];
                    }
                } elseif ($question_type == 'date' and ! empty($ans)) {
                    $answer_result = nv_date('d/m/Y', $ans);
                } elseif ($question_type == 'time' and ! empty($ans)) {
                    $answer_result = nv_date('H:i', $ans);
                } else {
                    $answer_result = $ans;
                }
                
                $answer['username'] = empty($answer['username']) ? $lang_module['report_guest'] : nv_show_name_user($answer['first_name'], $answer['last_name'], $answer['username']);
                
                $xtpl->assign('ANSWER', $answer_result);
                
                if ($question_type == 'table') {
                    $data = unserialize($data['question_choices']);
                    
                    // Loop collumn
                    if (! empty($data['col'])) {
                        foreach ($data['col'] as $choices) {
                            $xtpl->assign('COL', array(
                                'key' => $choices['key'],
                                'value' => $choices['value']
                            ));
                            $xtpl->parse('main.question.answer.table.col');
                        }
                    }
                    
                    // Loop row
                    if (! empty($data['row'])) {
                        foreach ($data['row'] as $choices) {
                            $xtpl->assign('ROW', array(
                                'key' => $choices['key'],
                                'value' => $choices['value']
                            ));
                            
                            if (! empty($data['col'])) {
                                foreach ($data['col'] as $col) {
                                    $xtpl->assign('NAME', array(
                                        'col' => $col['key'],
                                        'row' => $choices['key']
                                    ));
                                    $xtpl->assign('VALUE', isset($answer[$qid][$col['key']][$choices['key']]) ? $answer[$qid][$col['key']][$choices['key']] : '');
                                    $xtpl->parse('main.question.answer.table.row.td');
                                }
                            }
                            
                            $xtpl->parse('main.question.answer.table.row');
                        }
                    }
                    $xtpl->parse('main.question.answer.table');
                } elseif ($question_type == 'grid') {
                    $data = unserialize($data['question_choices']);
                    
                    // Loop collumn
                    if (! empty($data['col'])) {
                        foreach ($data['col'] as $choices) {
                            $xtpl->assign('COL', array(
                                'key' => $choices['key'],
                                'value' => $choices['value']
                            ));
                            $xtpl->parse('main.question.answer.grid.col');
                        }
                    }
                    
                    // Loop row
                    if (! empty($data['row'])) {
                        foreach ($data['row'] as $choices) {
                            $xtpl->assign('ROW', array(
                                'key' => $choices['key'],
                                'value' => $choices['value']
                            ));
                            
                            if (! empty($data['col'])) {
                                foreach ($data['col'] as $col) {
                                    $value = $col['key'] . '||' . $choices['key'];
                                    if ($answer[$qid] == $value) {
                                        $xtpl->parse('main.question.answer.grid.row.td.check');
                                    } else {
                                        $xtpl->parse('main.question.answer.grid.row.td.no_check');
                                    }
                                    $xtpl->parse('main.question.answer.grid.row.td');
                                }
                            }
                            
                            $xtpl->parse('main.question.answer.grid.row');
                        }
                    }
                    
                    $xtpl->parse('main.question.answer.grid');
                } else {
                    $xtpl->parse('main.question.answer.other');
                }
                
                $xtpl->parse('main.question.answer');
            }
            
            $answer['answer_time'] = nv_date('d/m/Y H:i', $answer['answer_time']);
            $answer['answer_edit_time'] = ! $answer['answer_edit_time'] ? '-' : nv_date('d/m/Y H:i', $answer['answer_edit_time']);
            $xtpl->assign('ANSWER', $answer);
            
            $xtpl->parse('main.question');
        }
    }
    $xtpl->parse('main');
    return $xtpl->text('main');
}

/**
 * nv_get_plaintext()
 *
 * @param mixed $string            
 * @return
 *
 */
function nv_get_plaintext($string, $keep_image = false, $keep_link = false)
{
    // Get image tags
    if ($keep_image) {
        if (preg_match_all("/\<img[^\>]*src=\"([^\"]*)\"[^\>]*\>/is", $string, $match)) {
            foreach ($match[0] as $key => $_m) {
                $textimg = '';
                if (strpos($match[1][$key], 'data:image/png;base64') === false) {
                    $textimg = " " . $match[1][$key];
                }
                if (preg_match_all("/\<img[^\>]*alt=\"([^\"]+)\"[^\>]*\>/is", $_m, $m_alt)) {
                    $textimg .= " " . $m_alt[1][0];
                }
                $string = str_replace($_m, $textimg, $string);
            }
        }
    }
    
    // Get link tags
    if ($keep_link) {
        if (preg_match_all("/\<a[^\>]*href=\"([^\"]+)\"[^\>]*\>(.*)\<\/a\>/isU", $string, $match)) {
            foreach ($match[0] as $key => $_m) {
                $string = str_replace($_m, $match[1][$key] . " " . $match[2][$key], $string);
            }
        }
    }
    
    $string = str_replace('&nbsp;', ' ', strip_tags($string));
    return preg_replace('/[ ]+/', ' ', $string);
}
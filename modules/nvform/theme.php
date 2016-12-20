<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Tue, 08 Apr 2014 15:13:43 GMT
 */
if (! defined('NV_IS_MOD_NVFORM'))
    die('Stop!!!');

/**
 * nv_theme_nvform_main()
 *
 * @param mixed $array_data            
 * @return
 *
 */
function nv_theme_nvform_main($array_data, $nv_alias_page)
{
    global $global_config, $module_name, $module_file, $module_upload, $lang_module, $module_config, $module_info, $op;
    
    $xtpl = new XTemplate($op . '.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file);
    $xtpl->assign('LANG', $lang_module);
    
    if (! empty($array_data)) {
        foreach ($array_data as $data) {
            $data['time'] = nv_date('H:i d/m/Y', $data['start_time']);
            $data['time'] = ! empty($data['end_time']) ? $data['time'] . ' - ' . nv_date('H:i d/m/Y', $data['end_time']) : $data['time'];
            $data['link'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '/' . $data['alias'] . '-' . $data['id'];
            $xtpl->assign('DATA', $data);
            $xtpl->parse('main.loop');
        }
    }
    
    if (! empty($nv_alias_page)) {
        $xtpl->assign('PAGE', $nv_alias_page);
        $xtpl->parse('main.page');
    }
    
    $xtpl->parse('main');
    $contents = $xtpl->text('main');
    
    include (NV_ROOTDIR . "/includes/header.php");
    echo nv_site_theme($contents);
    include (NV_ROOTDIR . "/includes/footer.php");
    exit();
}

/**
 * nv_theme_nvform_viewform()
 *
 * @param mixed $form_info            
 * @param mixed $question_info            
 * @param mixed $answer_info            
 * @param mixed $answer_info_extend            
 * @param mixed $info            
 * @return
 *
 */
function nv_theme_nvform_viewform($form_info, $question_info, $answer_info, $answer_info_extend, $info)
{
    global $global_config, $module_name, $module_data, $module_file, $module_upload, $lang_module, $module_config, $module_info, $op, $my_head, $my_footer;
    
    $my_footer .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . NV_ASSETS_DIR . "/js/jquery/jquery.validate.min.js\"></script>\n";
    $my_footer .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . NV_ASSETS_DIR . "/js/language/jquery.validator-" . NV_LANG_INTERFACE . ".js\"></script>\n";
    
    $my_footer .= "<script type=\"text/javascript\">\n";
    $my_footer .= "$(document).ready(function(){
					$('#question').validate({
					});
				 });";
    $my_footer .= " </script>\n";
    
    if (! empty($form_info['end_time'])) {
        $form_info['close_info'] = sprintf($lang_module['form_close_info'], date('d/m/Y H:i'));
    }
    
    $form_info['template'] = unserialize($form_info['template']);
    
    $xtpl = new XTemplate($op . '.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('FORM', $form_info);
    
    if ($form_info['question_display'] == 'question_display_left') {
        $xtpl->parse('main.display_left_form');
    }
    
    $dis_title = 1;
    $dis_description = 1;
    $dis_description_html = 1;
    $dis_form_info = 1;
    if (! file_exists(NV_ROOTDIR . '/modules/' . $module_file . '/form.build.php')) {
        return '';
    }
    require_once NV_ROOTDIR . '/modules/' . $module_file . '/form.build.php';
    
    $tem = $form_info['template'];
    $style = "<style>\n";
    $style .= "#question{\n";
    
    if (! empty($tem['background_color'])) {
        $style .= "\tbackground-color: " . $tem['background_color'] . ";\n";
    }
    
    if (! empty($tem['background_image'])) {
        $tem['background_image'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $tem['background_image'];
        $style .= "\tbackground-image: url('" . $tem['background_image'] . "');\n";
    }
    
    if (! empty($tem['background_imgage_repeat'])) {
        $style .= "\tbackground-repeat: " . $tem['background_imgage_repeat'] . ";\n";
    }
    
    if (! empty($tem['background_imgage_position'])) {
        $tem['background_imgage_position'] = str_replace('_', ' ', $tem['background_imgage_position']);
        $style .= "\tbackground-position: " . $tem['background_imgage_position'] . ";\n";
    }
    
    $style .= "}\n";
    $style .= "</style>\n";
    $my_head .= $style;
    
    if (! empty($info)) {
        $xtpl->assign('INFO', $info);
        $xtpl->parse('main.info');
    }
    
    $xtpl->parse('main');
    return $xtpl->text('main');
}

/**
 * nv_theme_nvform_alert()
 *
 * @param mixed $message            
 * @param mixed $type            
 * @return
 *
 */
function nv_theme_nvform_alert($message_title, $message_content, $type = 'info', $link_back = '', $time_back = 0)
{
    global $module_file, $module_info, $page_title;
    
    $xtpl = new XTemplate('info.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file);
    
    if ($type == 'success') {
        $class = ' class="alert alert-success"';
    } elseif ($type == 'warning') {
        $class = ' class="alert alert-warning"';
    } elseif ($type == 'danger') {
        $class = ' class="alert alert-danger"';
    } else {
        $class = ' class="alert alert-info"';
    }
    
    if (! empty($message_title)) {
        $page_title = $message_title;
        $xtpl->assign('TITLE', $message_title);
        $xtpl->parse('main.title');
    } else {
        $page_title = $module_info['custom_title'];
    }
    $xtpl->assign('CONTENT', $message_content);
    $xtpl->assign('CLASS', $class);
    $xtpl->parse('main');
    $contents = $xtpl->text('main');
    
    include (NV_ROOTDIR . "/includes/header.php");
    echo nv_site_theme($contents);
    include (NV_ROOTDIR . "/includes/footer.php");
    exit();
}

/**
 * nv_theme_nvform_viewanalytics()
 *
 * @param mixed $form_info            
 * @param mixed $question_info            
 * @param mixed $answer_info            
 * @return
 *
 */
function nv_theme_nvform_viewanalytics($form_info, $question_info, $answer_info)
{
    global $module_info, $module_file;
    
    $xtpl = new XTemplate('viewanalytics.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file);
    $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
    $xtpl->assign('MODULE_FILE', $module_file);
    $xtpl->assign('TEMPLATE', $module_info['template']);
    
    if (! empty($question_info)) {
        foreach ($question_info as $row) {
            if ($row['report']) {
                if ($row['question_type'] == 'textbox' or $row['question_type'] == 'number' or $row['question_type'] == 'date' or $row['question_type'] == 'time') {
                    foreach ($answer_info as $answer) {
                        if (isset($answer[$row['qid']])) {
                            if ($row['question_type'] == 'date') {
                                $answer[$row['qid']] = nv_date('d/m/Y', $answer[$row['qid']]);
                            }
                            $xtpl->assign('ANSWER', $answer[$row['qid']]);
                            $xtpl->parse('main.loop.textbox.loop');
                        }
                    }
                    $xtpl->parse('main.loop.textbox');
                } elseif ($row['question_type'] == 'radio' or $row['question_type'] == 'select' or $row['question_type'] == 'checkbox' or $row['question_type'] == 'multiselect') {
                    $question_choices = unserialize($row['question_choices']);
                    foreach ($question_choices as $key => $value) {
                        $count = 0;
                        foreach ($answer_info as $answer) {
                            if (isset($answer[$row['qid']]) and $key == $answer[$row['qid']]) {
                                $count ++;
                            }
                        }
                        $row['data'][] = array(
                            'label' => $value,
                            'value' => $count,
                            'color' => sprintf('#%06X', mt_rand(0, 0xFFFFFF)),
                            'highlight' => 'red'
                        );
                    }
                    $row['data'] = json_encode($row['data']);
                    $xtpl->assign('QUESTION', $row);
                    $xtpl->parse('main.loop.radio');
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
                                    $count = 0;
                                    $value = $col['key'] . '||' . $choices['key'];
                                    foreach ($answer_info as $answer) {
                                        if ($answer[$row['qid']] == $value) {
                                            $count ++;
                                        }
                                    }
                                    $xtpl->assign('COUNT', $count);
                                    $xtpl->parse('main.loop.grid.row.td');
                                }
                            }
                            $xtpl->parse('main.loop.grid.row');
                        }
                    }
                    
                    $xtpl->parse('main.loop.grid');
                }
                
                $xtpl->assign('QUESTION', $row);
                $xtpl->parse('main.loop');
            }
        }
    }
    
    $xtpl->parse('main');
    $contents = $xtpl->text('main');
    
    include (NV_ROOTDIR . "/includes/header.php");
    echo nv_site_theme($contents);
    include (NV_ROOTDIR . "/includes/footer.php");
    exit();
}

<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sat, 10 Dec 2011 06:46:54 GMT
 */
if (! defined('NV_MAINFILE'))
    die('Stop!!!');

if (! nv_function_exists('nv_block_form_content')) {

    function nv_block_config_form_content($module, $data_block, $lang_block)
    {
        global $site_mods, $nv_Cache;
        
        $html = '';
        $html .= '<tr>';
        $html .= '<td>' . $lang_block['formid'] . '</td>';
        $html .= '<td><select name="config_formid" class="form-control">';
        $html .= '<option value="0"> -- </option>';
        $sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $site_mods[$module]['module_data'] . ' WHERE status = 1 ORDER BY weight ASC';
        $list = $nv_Cache->db($sql, '', $module);
        foreach ($list as $l) {
            $html .= '<option value="' . $l['id'] . '" ' . (($data_block['formid'] == $l['id']) ? ' selected="selected"' : '') . '>' . $l['title'] . '</option>';
        }
        $html .= '</select>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td>' . $lang_block['dis_form_title'] . '</td>';
        $ck = $data_block['dis_form_title'] ? 'checked="checked"' : '';
        $html .= '<td><input type="checkbox" name="config_dis_form_title" value="1" ' . $ck . ' /></td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td>' . $lang_block['dis_form_description'] . '</td>';
        $ck = $data_block['dis_form_description'] ? 'checked="checked"' : '';
        $html .= '<td><input type="checkbox" name="config_dis_form_description" value="1" ' . $ck . ' /></td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td>' . $lang_block['dis_form_description_html'] . '</td>';
        $ck = $data_block['dis_form_description_html'] ? 'checked="checked"' : '';
        $html .= '<td><input type="checkbox" name="config_dis_form_description_html" value="1" ' . $ck . ' /></td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td>' . $lang_block['dis_form_answered'] . '</td>';
        $ck = $data_block['dis_form_answered'] ? 'checked="checked"' : '';
        $html .= '<td><input type="checkbox" name="config_dis_form_answered" value="1" ' . $ck . ' /></td>';
        $html .= '</tr>';
        
        return $html;
    }

    function nv_block_config_form_content_submit($module, $lang_block)
    {
        global $nv_Request;
        $return = array();
        $return['error'] = array();
        $return['config'] = array();
        $return['config']['formid'] = $nv_Request->get_int('config_formid', 'post', 0);
        $return['config']['dis_form_title'] = $nv_Request->get_int('config_dis_form_title', 'post');
        $return['config']['dis_form_description'] = $nv_Request->get_int('config_dis_form_description', 'post');
        $return['config']['dis_form_description_html'] = $nv_Request->get_int('config_dis_form_description_html', 'post');
        $return['config']['dis_form_answered'] = $nv_Request->get_int('config_dis_form_answered', 'post');
        return $return;
    }

    function nv_block_form_content($block_config)
    {
        global $db, $site_mods, $global_config, $module_info, $module_name, $lang_module, $my_footer, $user_info;
        
        $module = $block_config['module'];
        $mod_data = $site_mods[$module]['module_data'];
        $mod_file = $site_mods[$module]['module_file'];
        
        $filled = false;
        $answer_info = $old_answer_info = $form_info = array();
        
        $form_info = $db->query('SELECT * FROM ' . NV_PREFIXLANG . '_' . $mod_data . ' WHERE status = 1 AND id = ' . $block_config['formid'])->fetch();
        
        if (! empty($form_info)) {
            if ($form_info['start_time'] > NV_CURRENTTIME or ($form_info['end_time'] > 0 and $form_info['end_time'] < NV_CURRENTTIME) or ! nv_user_in_groups($form_info['groups_view'])) {
                return '';
            } else {
                // Lấy thông tin câu hỏi
                $question_info = $db->query("SELECT * FROM " . NV_PREFIXLANG . '_' . $mod_data . "_question WHERE fid = " . $block_config['formid'] . " AND status = 1 ORDER BY weight")->fetchAll();
                
                // Trạng thái trả lời
                if (defined('NV_IS_USER')) {
                    $sql = "SELECT * FROM " . NV_PREFIXLANG . '_' . $mod_data . "_answer WHERE fid = " . $block_config['formid'] . " AND who_answer = " . $user_info['userid'];
                    $_rows = $db->query($sql)->fetch();
                    
                    if ($_rows) {
                        $filled = true;
                        $form_info['filled'] = true;
                        $answer_info = unserialize($_rows['answer']);
                    }
                    
                    if (! empty($answer_info) and ! $block_config['dis_form_answered']) {
                        return '';
                    }
                }
                
                $template = 'viewform.tpl';
                if (file_exists(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $site_mods[$module]['module_file'] . '/block_form_content.tpl')) {
                    $block_theme = $global_config['module_theme'];
                    $template = 'block_form_content.tpl';
                } elseif (file_exists(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $site_mods[$module]['module_file'] . '/viewform.tpl')) {
                    $block_theme = $global_config['module_theme'];
                } else {
                    $block_theme = 'default';
                }
                
                if ($module != $module_name) {
                    if (file_exists(NV_ROOTDIR . '/themes/' . $block_theme . '/js/nvform.js')) {
                        $my_footer .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "themes/" . $block_theme . "/js/nvform.js\"></script>\n";
                    }
                    $my_footer .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . NV_ASSETS_DIR . "/js/jquery/jquery.validate.min.js\"></script>\n";
                    $my_footer .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . NV_ASSETS_DIR . "/js/language/jquery.validator-" . NV_LANG_INTERFACE . ".js\"></script>\n";
                    
                    $my_footer .= "<script type=\"text/javascript\">\n";
                    $my_footer .= "$(document).ready(function(){
								$('#question_form').validate({
								});
							 });";
                    $my_footer .= " </script>\n";
                    
                    if (file_exists(NV_ROOTDIR . '/modules/' . $site_mods[$module]['module_file'] . '/language/' . NV_LANG_INTERFACE . '.php')) {
                        require_once NV_ROOTDIR . '/modules/' . $site_mods[$module]['module_file'] . '/language/' . NV_LANG_INTERFACE . '.php';
                    }
                } else {
                    return '';
                }
                
                $xtpl = new XTemplate($template, NV_ROOTDIR . '/themes/' . $block_theme . '/modules/' . $site_mods[$module]['module_file']);
                $xtpl->assign('LANG', $lang_module);
                $xtpl->assign('FORM', $form_info);
                $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
                $xtpl->assign('NV_ASSETS_DIR', NV_ASSETS_DIR);
                $xtpl->assign('FORM_ACTION', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module . '/' . $form_info['alias'] . '-' . $form_info['id']);
                
                if ($form_info['question_display'] == 'question_display_left') {
                    $xtpl->assign('FORM_LEFT', 'class="form-horizontal"');
                }
                
                $dis_title = $block_config['dis_form_title'];
                $dis_description = $block_config['dis_form_description'];
                $dis_description_html = $block_config['dis_form_description_html'];
                if (! file_exists(NV_ROOTDIR . '/modules/' . $mod_file . '/form.build.php')) {
                    return '';
                }
                require_once NV_ROOTDIR . '/modules/' . $mod_file . '/form.build.php';
                
                $xtpl->parse('main');
                return $xtpl->text('main');
            }
        }
    }
}

if (defined('NV_SYSTEM')) {
    $module = $block_config['module'];
    $content = nv_block_form_content($block_config);
}

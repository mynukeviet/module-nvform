<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2015 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Wed, 02 Dec 2015 08:26:04 GMT
 */
define('NV_SYSTEM', true);

// Xac dinh thu muc goc cua site
define('NV_ROOTDIR', pathinfo(str_replace(DIRECTORY_SEPARATOR, '/', __file__), PATHINFO_DIRNAME));

require NV_ROOTDIR . '/includes/mainfile.php';
require NV_ROOTDIR . '/includes/core/user_functions.php';

// Duyệt tất cả các ngôn ngữ
$language_query = $db->query('SELECT lang FROM ' . $db_config['prefix'] . '_setup_language WHERE setup = 1');
while (list ($lang) = $language_query->fetch(3)) {
    // Duyet nvform va module ao
    $mquery = $db->query("SELECT title, module_data FROM " . $db_config['prefix'] . "_" . $lang . "_modules WHERE module_file = 'nvform'");
    while (list ($mod, $mod_data) = $mquery->fetch(3)) {
        try {
            $db->query("ALTER TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $mod_data . " CHANGE title title VARCHAR(250) NOT NULL, CHANGE alias alias VARCHAR(250) NOT NULL;");
        } catch (PDOException $e) {
            //
        }

        try {
            $db->query("ALTER TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $mod_data . " ADD description_html TEXT NOT NULL AFTER description;");
        } catch (PDOException $e) {
            //
        }

        try {
            $db->query("ALTER TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $mod_data . " ADD image VARCHAR(255) NOT NULL AFTER description_html;");
        } catch (PDOException $e) {
            //
        }

        try {
            $db->query("ALTER TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $mod_data . " ADD user_editable TINYINT(1) UNSIGNED NOT NULL DEFAULT '1' AFTER groups_view;");
        } catch (PDOException $e) {
            //
        }

        try {
            $db->query("ALTER TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $mod_data . " ADD question_report TINYINT(1) UNSIGNED NOT NULL DEFAULT '1' AFTER question_display, ADD form_report_type TINYINT(1) UNSIGNED NOT NULL DEFAULT '0' AFTER question_report, ADD form_report_type_email TEXT NOT NULL AFTER form_report_type, ADD template TEXT NOT NULL AFTER form_report_type_email;");
        } catch (PDOException $e) {
            //
        }

        try {
            $db->query("ALTER TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $mod_data . "_question ADD question_choices_extend TEXT NOT NULL AFTER question_choices;");
        } catch (PDOException $e) {
            //
        }

        try {
            $db->query("ALTER TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $mod_data . "_question DROP class;");
        } catch (PDOException $e) {
            //
        }

        try {
            $db->query("ALTER TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $mod_data . "_question ADD break TINYINT(1) UNSIGNED NOT NULL DEFAULT '0' AFTER default_value, ADD report TINYINT(1) UNSIGNED NOT NULL DEFAULT '1' AFTER break;");
        } catch (PDOException $e) {
            //
        }

        try {
            $db->query("ALTER TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $mod_data . "_answer ADD answer_extend TEXT NOT NULL AFTER answer;");
        } catch (PDOException $e) {
            //
        }

        $db->query("UPDATE " . $db_config['prefix'] . "_setup_extensions SET version='1.0.05 " . NV_CURRENTTIME . "' WHERE type='module' and basename=" . $db->quote($mod));

        try {
            $db->query("ALTER TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $mod_data . "_question ADD class VARCHAR(255) NOT NULL AFTER report;");
        } catch (PDOException $e) {
            //
        }

        try {
            $db->query("ALTER TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $mod_data . "_question CHANGE question_type question_type ENUM('number','date','time','textbox','textarea','editor','select','radio','checkbox','multiselect','grid', 'grid_row','table','file','plaintext') NOT NULL DEFAULT 'textbox';");
        } catch (PDOException $e) {
            //
        }

    }
    die('OK');
}
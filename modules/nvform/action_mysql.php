<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-10-2010 20:59
 */

if( ! defined( 'NV_IS_FILE_MODULES' ) ) die( 'Stop!!!' );

$sql_drop_module = array();

$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . ";";
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_question" . ";";
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_answer" . ";";

$sql_create_module = $sql_drop_module;

$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . " (
	id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
	title varchar(255) NOT NULL,
 	alias varchar(255) NOT NULL,
 	description text,
 	start_time int(11) NOT NULL DEFAULT '0',
 	end_time int(11) NOT NULL DEFAULT '0',
	groups_view varchar(255) DEFAULT '',
	question_display varchar(100) DEFAULT '',
 	weight smallint(4) NOT NULL DEFAULT '0',
 	add_time int(11) NOT NULL DEFAULT '0',
	status tinyint(1) unsigned NOT NULL DEFAULT '0',
	PRIMARY KEY (id),
	UNIQUE KEY alias (alias)
) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_question (
	qid mediumint(8) NOT NULL AUTO_INCREMENT,
	title text NOT NULL,
	fid mediumint(8) NOT NULL DEFAULT 0,
	weight int(10) unsigned NOT NULL DEFAULT '1',
	question_type enum('number','date','textbox','textarea','editor','select','radio','checkbox','multiselect') NOT NULL DEFAULT 'textbox',
	question_choices text NOT NULL,
	match_type enum('none','alphanumeric','email','url','regex','callback') NOT NULL DEFAULT 'none',
	match_regex varchar(250) NOT NULL DEFAULT '',
	func_callback varchar(75) NOT NULL DEFAULT '',
	min_length int(11) NOT NULL DEFAULT '0',
	max_length bigint(20) unsigned NOT NULL DEFAULT '0',
	required tinyint(3) unsigned NOT NULL DEFAULT '0',
	user_editable tinyint(3) unsigned NOT NULL DEFAULT '0',
	class varchar(50) NOT NULL,
	default_value varchar(255) NOT NULL DEFAULT '',
	status tinyint(1) unsigned NOT NULL DEFAULT '0',
	PRIMARY KEY (qid)
) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_answer (
	id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
	fid mediumint(8) NOT NULL DEFAULT 0,
	answer text,
	who_answer tinyint(2) NOT NULL DEFAULT '0',
 	answer_time int(11) NOT NULL DEFAULT '0',
 	answer_edit_time int(11) NOT NULL DEFAULT '0',
	PRIMARY KEY (id)
) ENGINE=MyISAM";
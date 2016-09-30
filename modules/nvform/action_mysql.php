<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2015 VINADES.,JSC. All rights reserved
 * @License: GNU/GPL version 2 or any later version
 * @Createdate Fri, 25 Dec 2015 03:14:14 GMT
 */
if (! defined('NV_IS_FILE_MODULES'))
    die('Stop!!!');

$sql_drop_module = array();
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "";
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_answer";
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_question";

$sql_create_module = $sql_drop_module;
$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "(
  id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  title varchar(250) NOT NULL,
  alias varchar(250) NOT NULL,
  description text COMMENT 'Mô tả biểu mẫu',
  description_html text COMMENT 'Nội dung chi tiết biểu mẫu',
  image varchar(255) NOT NULL COMMENT 'Hình ảnh',
  start_time int(11) NOT NULL DEFAULT '0' COMMENT 'Thời gian bắt đầu hiệu lực',
  end_time int(11) NOT NULL DEFAULT '0' COMMENT 'Thời gian kết thúc',
  groups_view varchar(255) DEFAULT '' COMMENT 'Nhóm được xem',
  user_editable tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Cho phép người dùng thay đổi câu trả lời',
  question_display varchar(100) DEFAULT '' COMMENT 'Phuơng án hiển thị',
  question_report tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Hiển thị báo cáo',
  form_report_type tinyint(1) unsigned NOT NULL DEFAULT '0',
  form_report_type_email text NOT NULL,
  template text NOT NULL COMMENT 'Cài đặt giao diện biểu mẫu',
  weight smallint(4) NOT NULL DEFAULT '0',
  add_time int(11) NOT NULL DEFAULT '0',
  status tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (id),
  UNIQUE KEY alias (alias)
) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_answer(
  id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  fid mediumint(8) NOT NULL DEFAULT '0',
  answer text,
  answer_extend text,
  who_answer tinyint(2) NOT NULL DEFAULT '0',
  answer_time int(11) NOT NULL DEFAULT '0',
  answer_edit_time int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (id)
) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_question(
  qid mediumint(8) NOT NULL AUTO_INCREMENT,
  title text NOT NULL,
  fid mediumint(8) NOT NULL DEFAULT '0',
  weight int(10) unsigned NOT NULL DEFAULT '1',
  question_type enum('number','date','time','textbox','textarea','editor','select','radio','checkbox','multiselect','grid','table','file','plaintext') NOT NULL DEFAULT 'textbox',
  question_choices text NOT NULL,
  question_choices_extend text NOT NULL,
  match_type enum('none','alphanumeric','email','url','regex','callback') NOT NULL DEFAULT 'none',
  match_regex varchar(250) NOT NULL DEFAULT '',
  func_callback varchar(75) NOT NULL DEFAULT '',
  min_length int(11) NOT NULL DEFAULT '0',
  max_length bigint(20) unsigned NOT NULL DEFAULT '0',
  required tinyint(3) unsigned NOT NULL DEFAULT '0',
  user_editable tinyint(3) NOT NULL DEFAULT '0',
  default_value varchar(255) NOT NULL DEFAULT '',
  break tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Ngắt trang',
  report tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Hiển thị trong báo cáo',
  class varchar(255) NOT NULL,
  status tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (qid)
) ENGINE=MyISAM";
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
$submenu['config'] = $lang_module['config'];

$allow_func = array( 'main', 'config','form_content', 'alias', 'change_status', 'change_weight', 'del', 'question', 'question_content' );

define( 'NV_IS_FILE_ADMIN', true );

?>
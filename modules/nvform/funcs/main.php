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

$page_title = $module_info['custom_title'];
$key_words = $module_info['keywords'];

$array_data = array();
$base_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name;

$db->sqlreset()
    ->select('COUNT(*)')
    ->from(NV_PREFIXLANG . '_' . $module_data)
    ->where('status=1');

$all_page = $db->query($db->sql())
    ->fetchColumn();

$db->select('*')
    ->order('weight')
    ->limit($per_page)
    ->offset(($page - 1) * $per_page);

$_query = $db->query($db->sql());
while ($row = $_query->fetch()) {
    if (nv_user_in_groups($row['groups_view'])) {
        $row['answer_count'] = $db->query('SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_answer WHERE fid=' . $row['id'])->fetchColumn();
        $array_data[$row['id']] = $row;
    }
}

$nv_alias_page = nv_alias_page($page_title, $base_url, $all_page, $per_page, $page);

$contents = nv_theme_nvform_main($array_data, $nv_alias_page);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
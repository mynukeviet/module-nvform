<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES., JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES ., JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Dec 3, 2010 11:33:22 AM
 */
if (! defined('NV_IS_FILE_ADMIN'))
    die('Stop!!!');

$fid = $nv_Request->get_int('fid', 'get,post', 0);

$form_info = $db->query('SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE id=' . $fid)->fetch();

if ($nv_Request->isset_request('export', 'post, get')) {
    $download = $nv_Request->get_int('download', 'get, post', 1);
    $type = $nv_Request->get_title('type', 'get, post', '');
    $is_zip = $nv_Request->get_int('is_zip', 'get, post', 0);
    
    if (empty($type))
        die('NO');
    
    $question_data = array();
    $result = $db->query('SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_question WHERE fid = ' . $fid);
    while ($row = $result->fetch()) {
        $question_data[$row['qid']] = $row;
    }
    
    $answer_data = array();
    $result = $db->query('SELECT t1.*, t2.username FROM ' . NV_PREFIXLANG . '_' . $module_data . '_answer t1 LEFT JOIN ' . NV_USERS_GLOBALTABLE . ' t2 ON t1.who_answer = t2.userid WHERE fid = ' . $fid);
    while ($row = $result->fetch()) {
        $answer_data[] = $row;
    }
    
    if (! class_exists('PHPExcel')) {
        die('NO_' . $lang_module['report_required_phpexcel']);
    }
    
    if ($type == 'pdf') {
        $rendererName = PHPExcel_Settings::PDF_RENDERER_MPDF;
        $rendererLibrary = 'mpdf/mpdf';
        $rendererLibraryPath = NV_ROOTDIR . '/vendor/' . $rendererLibrary;
    }
    
    $array = array(
        'objType' => '',
        'objExt' => ''
    );
    switch ($type) {
        case 'xlsx':
            $array['objType'] = 'Excel2007';
            $array['objExt'] = 'xlsx';
            break;
        case 'ods':
            $array['objType'] = 'OpenDocument';
            $array['objExt'] = 'ods';
            break;
        case 'pdf':
            $array['objType'] = 'PDF';
            $array['objExt'] = 'pdf';
            break;
        default:
            $array['objType'] = 'CSV';
            $array['objExt'] = 'csv';
    }
    
    $objPHPExcel = new PHPExcel();
    $objPHPExcel->setActiveSheetIndex(0);
    
    // Set properties
    $objPHPExcel->getProperties()
        ->setCreator($admin_info['username'])
        ->setLastModifiedBy($admin_info['username'])
        ->setTitle($form_info['title'])
        ->setSubject($form_info['title'])
        ->setDescription($form_info['title'])
        ->setCategory($module_name);
    
    $columnIndex = 4; // Cot bat dau ghi du lieu
    $rowIndex = 3; // Dong bat dau ghi du lieu
                   
    // Tieu de cot
    $objPHPExcel->getActiveSheet()
        ->setCellValue(PHPExcel_Cell::stringFromColumnIndex(0) . $rowIndex, $lang_module['question_number'])
        ->setCellValue(PHPExcel_Cell::stringFromColumnIndex(1) . $rowIndex, $lang_module['report_who_answer'])
        ->setCellValue(PHPExcel_Cell::stringFromColumnIndex(2) . $rowIndex, $lang_module['report_answer_time'])
        ->setCellValue(PHPExcel_Cell::stringFromColumnIndex(3) . $rowIndex, $lang_module['report_answer_edit_time']);
    
    // Tieu de cot cau hoi
    $_columnIndex = $columnIndex;
    foreach ($question_data as $question) {
        $TextColumnIndex = PHPExcel_Cell::stringFromColumnIndex($_columnIndex);
        $objPHPExcel->getActiveSheet()->setCellValue($TextColumnIndex . $rowIndex, nv_get_plaintext($question['title']));
        $_columnIndex ++;
    }
    
    // Hien thi cau tra loi
    $i = $rowIndex + 1;
    $number = 1;
    foreach ($answer_data as $answer) {
        $j = $columnIndex;
        $answer['username'] = ! $answer['username'] ? $lang_module['report_guest'] : $answer['username'];
        $answer['answer_time'] = nv_date('d/m/Y H:i', $answer['answer_time']);
        $answer['answer_edit_time'] = ! $answer['answer_edit_time'] ? 'N/A' : nv_date('d/m/Y H:i', $answer['answer_edit_time']);
        
        $col = PHPExcel_Cell::stringFromColumnIndex(0);
        $CellValue = $number;
        $objPHPExcel->getActiveSheet()->setCellValue($col . $i, $CellValue);
        
        $col = PHPExcel_Cell::stringFromColumnIndex(1);
        $CellValue = nv_unhtmlspecialchars($answer['username']);
        $objPHPExcel->getActiveSheet()->setCellValue($col . $i, $CellValue);
        
        $col = PHPExcel_Cell::stringFromColumnIndex(2);
        $CellValue = nv_unhtmlspecialchars($answer['answer_time']);
        $objPHPExcel->getActiveSheet()->setCellValue($col . $i, $CellValue);
        
        $col = PHPExcel_Cell::stringFromColumnIndex(3);
        $CellValue = nv_unhtmlspecialchars($answer['answer_edit_time']);
        $objPHPExcel->getActiveSheet()->setCellValue($col . $i, $CellValue);
        
        $number ++;
        
        $answer['answer'] = unserialize($answer['answer']);
        foreach ($answer['answer'] as $qid => $ans) {
            if (isset($question_data[$qid])) {
                $question_type = $question_data[$qid]['question_type'];
                if ($question_type == 'multiselect' or $question_type == 'select' or $question_type == 'radio' or $question_type == 'checkbox') {
                    $data = unserialize($question_data[$qid]['question_choices']);
                    if ($question_type == 'checkbox') {
                        $result = explode(',', $ans);
                        $ans = '';
                        foreach ($result as $key) {
                            $ans .= $data[$key] . "<br />";
                        }
                    } else {
                        $ans = $data[$ans];
                    }
                } elseif ($question_type == 'date' and ! empty($ans)) {
                    $ans = nv_date('d/m/Y', $ans);
                } elseif ($question_type == 'time' and ! empty($ans)) {
                    $ans = nv_date('H:i', $ans);
                } elseif ($question_type == 'grid') {
                    $data = unserialize($question_data[$qid]['question_choices']);
                    $result = explode('||', $ans);
                    foreach ($data['col'] as $col) {
                        if ($result[0] == $col['key']) {
                            $ans = $col['value'];
                            break;
                        }
                    }
                    foreach ($data['row'] as $row) {
                        if ($result[1] == $row['key']) {
                            $ans .= ' - ' . $col['value'];
                            break;
                        }
                    }
                } elseif ($question_type == 'file' and file_exists(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $ans)) {
                    $ans = '<a href="' . NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $ans . '" title="">' . $lang_module['question_options_file_dowload'] . '</a>';
                }
                
                $answer['username'] = empty($answer['username']) ? $lang_module['report_guest'] : nv_show_name_user($answer['first_name'], $answer['last_name'], $answer['username']);
            } else {
                $ans = '';
            }
            $col = PHPExcel_Cell::stringFromColumnIndex($j);
            $CellValue = htmlspecialchars(nv_editor_br2nl(($ans)));
            $objPHPExcel->getActiveSheet()->setCellValue($col . $i, $CellValue);
            $j ++;
        }
        $i ++;
    }
    
    $highestRow = $i - 1;
    $highestColumn = PHPExcel_Cell::stringFromColumnIndex($j - 1);
    
    // Rename sheet
    $objPHPExcel->getActiveSheet()->setTitle('Sheet 1');
    
    // Set page orientation and size
    $objPHPExcel->getActiveSheet()
        ->getPageSetup()
        ->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
    $objPHPExcel->getActiveSheet()
        ->getPageSetup()
        ->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
    
    // Excel title
    $objPHPExcel->getActiveSheet()->mergeCells('A2:' . $highestColumn . '2');
    $objPHPExcel->getActiveSheet()->setCellValue('A2', strtoupper($form_info['title']));
    $objPHPExcel->getActiveSheet()
        ->getStyle('A2')
        ->getAlignment()
        ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()
        ->getStyle('A2')
        ->getAlignment()
        ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
    
    // Set color
    $styleArray = array(
        'borders' => array(
            'outline' => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN,
                'color' => array(
                    'argb' => 'FF000000'
                )
            )
        )
    );
    $objPHPExcel->getActiveSheet()
        ->getStyle('A3' . ':' . $highestColumn . $highestRow)
        ->applyFromArray($styleArray);
    
    // Set font size
    $objPHPExcel->getActiveSheet()
        ->getStyle("A1:" . $highestColumn . $highestRow)
        ->getFont()
        ->setSize(12);
    
    // Set auto column width
    foreach (range('A', $highestColumn) as $columnID) {
        $objPHPExcel->getActiveSheet()
            ->getColumnDimension($columnID)
            ->setAutoSize(true);
    }
    
    $objPHPExcel->getActiveSheet()
        ->getStyle("A3:" . $highestColumn . $highestRow)
        ->applyFromArray(array(
        'borders' => array(
            'allborders' => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN,
                'color' => array(
                    'rgb' => 'DDDDDD'
                )
            )
        )
    ));
    
    if ($type == 'pdf') {
        if (! PHPExcel_Settings::setPdfRenderer($rendererName, $rendererLibraryPath)) {
            die('NOTICE: Please set the $rendererName and $rendererLibraryPath values' . '<br />' . 'at the top of this script as appropriate for your directory structure');
        }
    }
    
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, $array['objType']);
    $file_src = NV_ROOTDIR . '/' . NV_TEMP_DIR . '/' . $form_info['alias'] . '.' . $array['objExt'];
    $objWriter->save($file_src);
    
    if (! $download and file_exists($file_src))
        die('OK_' . str_replace(NV_ROOTDIR . NV_BASE_SITEURL, '', $file_src));
    
    if (! $is_zip) {
        $download = new NukeViet\Files\Download($file_src, NV_ROOTDIR . '/' . NV_TEMP_DIR);
        $download->download_file();
        die('OK');
    } else {
        $arry_file_zip = array();
        if (file_exists($file_src)) {
            $arry_file_zip[] = $file_src;
        }
        
        $file_src = NV_ROOTDIR . '/' . NV_TEMP_DIR . '/' . NV_TEMPNAM_PREFIX . change_alias($lang_module['report_ex']) . '_' . md5(nv_genpass(10) . session_id()) . '.zip';
        $zip = new PclZip($file_src);
        $zip->create($arry_file_zip, PCLZIP_OPT_REMOVE_PATH, NV_ROOTDIR . "/" . NV_TEMP_DIR);
        $filesize = @filesize($file_src);
        
        foreach ($arry_file_zip as $file) {
            nv_deletefile($file);
        }
        
        // Download file
        $download = new NukeViet\Files\Download($file_src, NV_ROOTDIR . "/" . NV_TEMP_DIR, $form_info['alias'] . ".zip");
        $download->download_file();
        exit();
    }
}

$xtpl = new XTemplate('export.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('OP', $op);
$xtpl->assign('FID', $fid);

if (! class_exists('PHPExcel')) {
    $xtpl->parse('main.PHPExcel_req');
} else {
    $default = 'xlsx';
    $array_type = array(
        'xlsx' => 'Microsoft Excel (XLSX)',
        'csv' => 'Comma-separated values (CSV)',
        'ods' => 'LibreOffice Calc (ODS)',
        'pdf' => 'PDF'
    );
    
    foreach ($array_type as $key => $value) {
        $ck = $key == $default ? 'checked="checked"' : '';
        $xtpl->assign('TYPE', array(
            'key' => $key,
            'value' => $value,
            'checked' => $ck
        ));
        $xtpl->parse('main.export.type');
    }
    $xtpl->parse('main.export');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo $contents;
include NV_ROOTDIR . '/includes/footer.php';
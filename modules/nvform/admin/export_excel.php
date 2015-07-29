<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES., JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES ., JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Dec 3, 2010 11:33:22 AM
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

if( ! file_exists( NV_ROOTDIR . '/includes/class/PHPExcel.php' ) )
{
    die( strip_tags( $lang_module['report_required_phpexcel'] ) );
}

$step = $nv_Request->get_int( 'step', 'get,post', 1 );
$fid = $nv_Request->get_int( 'fid', 'get,post', 0 );

require_once NV_ROOTDIR . '/includes/class/PHPExcel.php' ;

if( extension_loaded( 'zip' ) )
{
	$excel_ext = "xlsx";
	$writerType = 'Excel2007';
}
else
{
	$excel_ext = "xls";
	$writerType = 'Excel5';
}

// Lay tieu de form
$sql = 'SELECT title FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE id = ' . $fid;
$result = $db->query( $sql );
list( $title ) = $result->fetch( 3 );
$page_title = sprintf( $lang_module['report_page_title'], $title );

if( $step == 1 )
{
	// Create new PHPExcel object
	$objPHPExcel = PHPExcel_IOFactory::load( NV_ROOTDIR . '/modules/' . $module_file . '/template.' . $excel_ext );
	$objWorksheet = $objPHPExcel->getActiveSheet();

	// Setting a spreadsheet’s metadata
	$objPHPExcel->getProperties()->setCreator( "NukeViet CMS" );
	$objPHPExcel->getProperties()->setLastModifiedBy( "NukeViet CMS" );
	$objPHPExcel->getProperties()->setTitle( $page_title );
	$objPHPExcel->getProperties()->setSubject( $page_title );
	$objPHPExcel->getProperties()->setDescription( $page_title );
	$objPHPExcel->getProperties()->setKeywords( $page_title );
	$objPHPExcel->getProperties()->setCategory( $module_name );

	// Rename sheet
	$objWorksheet->setTitle( nv_clean60( $page_title, 30 ) );

	// Set page orientation and size
	$objWorksheet->getPageSetup()->setOrientation( PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE );
	$objWorksheet->getPageSetup()->setPaperSize( PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4 );
	$objWorksheet->getPageSetup()->setHorizontalCentered( true );
	$objWorksheet->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd( 1, 3 );
	
	$columnIndex = 3; // Cot bat dau ghi du lieu
	$rowIndex = 3; // Dong bat dau ghi du lieu

	$sql = 'SELECT t1.*, t2.username FROM ' . NV_PREFIXLANG . '_' . $module_data . '_answer t1 LEFT JOIN ' . NV_USERS_GLOBALTABLE . ' t2 ON t1.who_answer = t2.userid WHERE fid = ' . $fid;
	$result = $db->query( $sql );
	$answer_data = $result->fetchAll();
	
	$sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_question WHERE fid = ' . $fid;
	$result = $db->query( $sql );
	
	$TextColumnIndex = PHPExcel_Cell::stringFromColumnIndex( 0 );
	$objWorksheet->setCellValue( $TextColumnIndex . $rowIndex, $lang_module['report_who_answer'] );
	$TextColumnIndex = PHPExcel_Cell::stringFromColumnIndex( 1 );
	$objWorksheet->setCellValue( $TextColumnIndex . $rowIndex, $lang_module['report_answer_time'] );
	$TextColumnIndex = PHPExcel_Cell::stringFromColumnIndex( 2 );
	$objWorksheet->setCellValue( $TextColumnIndex . $rowIndex, $lang_module['report_answer_edit_time'] );
	
	while( $row = $result->fetch() )
	{
		$question_data[$row['qid']] = $row;
		$TextColumnIndex = PHPExcel_Cell::stringFromColumnIndex( $columnIndex );
		$objWorksheet->setCellValue( $TextColumnIndex . $rowIndex, $row['title'] );
		$columnIndex++;
	}
	
	$i = $rowIndex + 1;
	foreach( $answer_data as $answer )
	{
		$j = 3;

		$answer['username'] = ! $answer['username'] ? $lang_module['report_guest'] : $answer['username'];
		$answer['answer_time'] = nv_date( 'd/m/Y H:i', $answer['answer_time'] );
		$answer['answer_edit_time'] = ! $answer['answer_edit_time'] ? 'N/A' : nv_date( 'd/m/Y H:i', $answer['answer_edit_time'] );
		
		$col = PHPExcel_Cell::stringFromColumnIndex( 0 );
		$CellValue = nv_unhtmlspecialchars( $answer['username'] );
		$objWorksheet->setCellValue( $col . $i, $CellValue );
		
		$col = PHPExcel_Cell::stringFromColumnIndex( 1 );
		$CellValue = nv_unhtmlspecialchars( $answer['answer_time'] );
		$objWorksheet->setCellValue( $col . $i, $CellValue );
		
		$col = PHPExcel_Cell::stringFromColumnIndex( 2 );
		$CellValue = nv_unhtmlspecialchars( $answer['answer_edit_time'] );
		$objWorksheet->setCellValue( $col . $i, $CellValue );
		
		$answer['answer'] = unserialize( $answer['answer'] );
	
		foreach( $answer['answer'] as $qid => $ans )
		{
			if( isset( $question_data[$qid] ) )
			{
				$question_type = $question_data[$qid]['question_type'];
				if( $question_type == 'multiselect' OR $question_type == 'select' OR $question_type == 'radio' OR $question_type == 'checkbox' )
				{
					$data = unserialize( $question_data[$qid]['question_choices'] );
					if( $question_type == 'checkbox' )
					{
						$result = explode( ',', $ans );
						$ans = '';
						foreach( $result as $key )
						{
							$ans .= $data[$key] . " | ";
						}
					}
					else
					{
						$ans = $data[$ans];
					}
				}
			}
			else
			{
				$ans = '';		
			}
			
			$answer['username'] = empty( $answer['username'] ) ? $lang_module['report_guest'] : $answer['username'];
			
			$col = PHPExcel_Cell::stringFromColumnIndex( $j );
			$CellValue = nv_unhtmlspecialchars( $ans );
			$objWorksheet->setCellValue( $col . $i, $CellValue );
			$j++;
		}
		$i++;
	}

	$highestRow = $i - 1;
	$highestColumn = PHPExcel_Cell::stringFromColumnIndex( $j - 1 );

	$objWorksheet->mergeCells( 'A2:' . $highestColumn . '2' );
	$objWorksheet->setCellValue( 'A2', strtoupper( $page_title ) );
	$objWorksheet->getStyle( 'A2' )->getAlignment()->setHorizontal( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
	$objWorksheet->getStyle( 'A2' )->getAlignment()->setVertical( PHPExcel_Style_Alignment::VERTICAL_CENTER );

	$styleArray = array( 'borders' => array( 'outline' => array(
				'style' => PHPExcel_Style_Border::BORDER_THIN,
				'color' => array( 'argb' => 'FF000000' )
			)
		)
	);
	$objWorksheet->getStyle( 'A3' . ':' . $highestColumn . $highestRow )->applyFromArray( $styleArray );
	
	$styleArray = array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'F0E8E8')
        )
    );
	$objWorksheet->getStyle( 'A3' . ':' . $highestColumn . '3' )->applyFromArray( $styleArray );

	$objWriter = PHPExcel_IOFactory::createWriter( $objPHPExcel, $writerType );
	if( $writerType == 'Excel2007' )
	{
		$objWriter->setOffice2003Compatibility( true );
	}

	$export_filename = $nv_Request->get_string( $module_data . '_export_filename', 'session', '' );

	if( $id_export == 0 and $num_items <= $limit_export_data )
	{
		$file_name = change_alias( $page_title );
		$result = "OK_COMPLETE";
		$nv_Request->set_Session( $module_data . '_export_filename', $file_name );
	}
	elseif( $number_page < $limit_export_data )
	{
		$file_name = change_alias( $page_title ) . "_" . $id_export_save;
		$result = "OK_COMPLETE";
		$nv_Request->set_Session( $module_data . '_export_filename', $export_filename . "@" . $file_name );
	}
	else
	{
		$file_name = change_alias( $page_title ) . "_" . $id_export_save;
		$result = "OK_GETFILE";
		$nv_Request->set_Session( $module_data . '_id_export', $id_export_save );
		$nv_Request->set_Session( $module_data . '_export_filename', $export_filename . "@" . $file_name );
	}

	$objWriter->save( NV_ROOTDIR . "/" . NV_CACHEDIR . "/" . $file_name . "." . $excel_ext );
	die( $result );
}
elseif( $step == 2 and $nv_Request->isset_request( $module_data . '_export_filename', 'session' ) )
{
	$export_filename = $nv_Request->get_string( $module_data . '_export_filename', 'session', '' );
	$array_filename = explode( "@", $export_filename );
	$arry_file_zip = array();
	foreach( $array_filename as $file_name )
	{
		if( ! empty( $file_name ) and file_exists( NV_ROOTDIR . '/' . NV_CACHEDIR . '/' . $file_name . '.' . $excel_ext ) )
		{
			$arry_file_zip[] = NV_ROOTDIR . "/" . NV_CACHEDIR . "/" . $file_name . "." . $excel_ext;
		}
	}

	$file_src = NV_ROOTDIR . '/' . NV_TEMP_DIR . '/' . NV_TEMPNAM_PREFIX . change_alias( $lang_module['export'] ) . '_' . md5( nv_genpass( 10 ) . session_id() ) . '.zip';
	require_once NV_ROOTDIR . '/includes/class/pclzip.class.php';
	$zip = new PclZip( $file_src );
	$zip->create( $arry_file_zip, PCLZIP_OPT_REMOVE_PATH, NV_ROOTDIR . "/" . NV_CACHEDIR );
	$filesize = @filesize( $file_src );

	$nv_Request->unset_request( $module_data . '_export_filename', 'session' );

	foreach( $arry_file_zip as $file )
	{
		nv_deletefile( $file );
	}

	//Download file
	require_once NV_ROOTDIR . '/includes/class/download.class.php' ;
	$download = new download( $file_src, NV_ROOTDIR . "/" . NV_TEMP_DIR, basename( change_alias( $lang_module['export'] ) . ".zip" ) );
	$download->download_file();
	exit();
}
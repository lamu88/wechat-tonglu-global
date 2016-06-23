<?php
require_once('load.php');

require_once SYS_PATH.'lib/class/PHPExcel.php';
//require_once SYS_PATH.'lib/class/PHPExcel/IOFactory.php';
$objPHPExcel = new PHPExcel();

// 设置基本属性 
$objPHPExcel->getProperties()->setCreator("Sun Star Data Center") ->setLastModifiedBy("Sun Star Data Center") ->setTitle("Microsoft Office Excel Document") ->setSubject("Test Data Report -- From Sunstar Data Center") ->setDescription("LD Test Data Report, Generate by Sunstar Data Center") ->setKeywords("sunstar ld report") ->setCategory("Test result file"); 

 // 创建多个工作薄 
 $sheet1 = $objPHPExcel->createSheet(); 
 $sheet2 = $objPHPExcel->createSheet();
 
 // 设置第一个工作簿为活动工作簿 
 $objPHPExcel->setActiveSheetIndex(0);
 
 // 设置活动工作簿名称 
 // 如果是中文一定要使用iconv函数转换编码 
 $objPHPExcel->getActiveSheet()->setTitle(iconv('gbk', 'utf-8', '测试工作簿'));  
 
 // 设置默认字体和大小 
 $objPHPExcel->getDefaultStyle()->getFont()->setName(iconv('gbk', 'utf-8', '宋体')); 
 $objPHPExcel->getDefaultStyle()->getFont()->setSize(10);
 
 // 设置一列的宽度 
 $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
 
 // 设置一行的高度 
 $objPHPExcel->getActiveSheet()->getRowDimension('6')->setRowHeight(50); 
 
 // 定义一个样式，加粗，居中 
 $styleArray1 = array( 'font' => array( 'bold' => true, 'color'=>array( 'argb' => '00000000', ), ),  'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, ), );
 
 // 将样式应用于A1单元格 
 $objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($styleArray1); 
 
 // 设置单元格样式（黑色字体） 
 $objPHPExcel->getActiveSheet()->getStyle('H5')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_BLACK); // 黑色
 
 
 // 设置单元格格式（背景） 
 $objPHPExcel->getActiveSheet()->getStyle('H5')->getFill()->getStartColor()->setARGB('00ff99cc'); // 将背景设置为浅粉色
 
 // 设置单元格格式（数字格式） 
 $objPHPExcel->getActiveSheet()->getStyle('F1')->getNumberFormat()->setFormatCode('0.000'); 
 
 // 给特定单元格中写入内容 
 $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Hello Baby');
 $objPHPExcel->getActiveSheet()->setCellValue('B1', 'Hello Baby');
 $objPHPExcel->getActiveSheet()->setCellValue('H5', '444');
 
 // 设置单元格样式（居中） 
 $objPHPExcel->getActiveSheet()->getStyle('H5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
 
 // 给单元格中放入图片, 将数据图片放在J1单元格内 
 $objDrawing = new PHPExcel_Worksheet_Drawing(); 
 $objDrawing->setName('Logo'); 
 $objDrawing->setDescription('Logo');
 $objDrawing->setPath("./images/logo.png"); // 图片路径，只能是相对路径 
 $objDrawing->setWidth(400); // 图片宽度 
 $objDrawing->setHeight(123); // 图片高度 
 $objDrawing->setCoordinates('J1'); 
 $objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
 
 
 // 设置A5单元格内容并增加超链接 
 $objPHPExcel->getActiveSheet()->setCellValue('A5', iconv('gbk', 'utf-8', '超链接keiyi.com')); 
 $objPHPExcel->getActiveSheet()->getCell('A5')->getHyperlink()->setUrl('http://www.keiyi.com/');
 
$objWriter = PHPExcel_IOFactory::createWriter($m_objPHPExcel, 'Excel5');
$m_strOutputExcelFileName = date('Y-m-j_H_i_s').".xls"; // 输出EXCEL文件名
header("Pragma: public");
header("Expires: 0");
header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
header("Content-Type:application/force-download");
header("Content-Type: application/vnd.ms-excel;");
header("Content-Type:application/octet-stream");
header("Content-Type:application/download");
header("Content-Disposition:attachment;filename=".$m_strOutputExcelFileName);
header("Content-Transfer-Encoding:binary");
$objWriter->save("php://output"); 



?>
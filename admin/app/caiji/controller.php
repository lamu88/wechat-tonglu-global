<?php
class CaijiController extends Controller{
	//构造函数，自动新建对象
 	function  __construct() {
		/*
		*构造函数，自动新建session对象
		*/
		$this->css('content.css');
		
	}
	//导出商品
	function export_goods_list(){
		$cname =  $_GET['cname'];
		$list =  $_GET['list'];

		$w = '';
		if(!empty($cname) && $cname!='all'){
			//$cname = str_replace(' ','',trim($cname));
			$w = " WHERE goods_cate LIKE '%$cname%'";
		}
		$sql = "SELECT * FROM `{$this->App->prefix()}goods_cache_list` $w ORDER BY goods_cate ASC, goods_id DESC LIMIT $list";
		$rt = $this->App->find($sql);
		
		$iconv = Import::gz_iconv();
		
		require_once SYS_PATH.'lib/class/PHPExcel.php';
		//require_once SYS_PATH.'lib/class/PHPExcel/IOFactory.php';
		$objPHPExcel = new PHPExcel();
		
		// 设置基本属性 
		$objPHPExcel->getProperties()->setCreator("Sun Star Data Center") ->setLastModifiedBy("Sun Star Data Center") ->setTitle("Microsoft Office Excel Document") ->setSubject("Test Data Report -- From Sunstar Data Center") ->setDescription("LD Test Data Report, Generate by Sunstar Data Center") ->setKeywords("sunstar ld report") ->setCategory("Test result file"); 
		//print_r($rt); exit;
		 // 创建多个工作薄 
		 $sheet1 = $objPHPExcel->createSheet(); 
		 //$sheet2 = $objPHPExcel->createSheet();
		 
		 // 设置第一个工作簿为活动工作簿 
		 //$objPHPExcel->setActiveSheetIndex(0);
		 
		 // 设置活动工作簿名称 
		 // 如果是中文一定要使用iconv函数转换编码
		 $objPHPExcel->getActiveSheet()->setTitle(empty($cname) ? '商品导出' : $cname);  
		 
		 // 设置默认字体和大小 
		 $objPHPExcel->getDefaultStyle()->getFont()->setName('宋体'); 
		 $objPHPExcel->getDefaultStyle()->getFont()->setSize(10);
		 
		 // 设置一列的宽度 
		 $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
		 $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
		 $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
		 $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(45);
		 $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
		 $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
		 $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(12);
		 		 $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(10);
		 $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(12);
		 $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
		 $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(10);
		 $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(10);
		 $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(10);
		 $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(10);
		 		 $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(10);
		 $objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(10);
		 $objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(10);
		 $objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(20);
		 $objPHPExcel->getActiveSheet()->getColumnDimension('S')->setWidth(20);
		 $objPHPExcel->getActiveSheet()->getColumnDimension('T')->setWidth(15);
		 $objPHPExcel->getActiveSheet()->getColumnDimension('U')->setWidth(15);
		 $objPHPExcel->getActiveSheet()->getColumnDimension('V')->setWidth(40);
		 $objPHPExcel->getActiveSheet()->getColumnDimension('W')->setWidth(30);
		 
		 // 设置行的高度 
		// $objPHPExcel->getActiveSheet()->getRowDimension(1)->setRowHeight(55); 
				 
		 // 定义一个样式，加粗，居中 
		 //$styleArray1 = array( 'font' => array( 'bold' => true, 'color'=>array( 'argb' => '00000000', ), ),  'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, ), );
		 //$styleArray2 = array( 'font' => array( 'color'=>array( 'argb' => '00000000', ), ),  'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, ), );
		  //居中
		  $objPHPExcel->getActiveSheet()->getStyle('A')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		  $objPHPExcel->getActiveSheet()->getStyle('B')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		  $objPHPExcel->getActiveSheet()->getStyle('C')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		  $objPHPExcel->getActiveSheet()->getStyle('D')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		  $objPHPExcel->getActiveSheet()->getStyle('E')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		  $objPHPExcel->getActiveSheet()->getStyle('F')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		  $objPHPExcel->getActiveSheet()->getStyle('G')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		   $objPHPExcel->getActiveSheet()->getStyle('H')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		  $objPHPExcel->getActiveSheet()->getStyle('I')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		  $objPHPExcel->getActiveSheet()->getStyle('J')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		  $objPHPExcel->getActiveSheet()->getStyle('K')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		  $objPHPExcel->getActiveSheet()->getStyle('L')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		  $objPHPExcel->getActiveSheet()->getStyle('M')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		  $objPHPExcel->getActiveSheet()->getStyle('N')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		   $objPHPExcel->getActiveSheet()->getStyle('O')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		  $objPHPExcel->getActiveSheet()->getStyle('P')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		  $objPHPExcel->getActiveSheet()->getStyle('Q')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		  $objPHPExcel->getActiveSheet()->getStyle('R')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		  $objPHPExcel->getActiveSheet()->getStyle('S')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		  $objPHPExcel->getActiveSheet()->getStyle('T')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		  $objPHPExcel->getActiveSheet()->getStyle('U')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		  $objPHPExcel->getActiveSheet()->getStyle('V')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		   $objPHPExcel->getActiveSheet()->getStyle('W')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				  
		 //垂直居中
		 $objPHPExcel->getActiveSheet()->getStyle('A')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		 $objPHPExcel->getActiveSheet()->getStyle('B')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		 $objPHPExcel->getActiveSheet()->getStyle('C')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		 $objPHPExcel->getActiveSheet()->getStyle('D')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		 $objPHPExcel->getActiveSheet()->getStyle('E')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		 $objPHPExcel->getActiveSheet()->getStyle('F')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		 $objPHPExcel->getActiveSheet()->getStyle('G')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		 $objPHPExcel->getActiveSheet()->getStyle('H')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		 $objPHPExcel->getActiveSheet()->getStyle('I')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		 $objPHPExcel->getActiveSheet()->getStyle('J')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		 $objPHPExcel->getActiveSheet()->getStyle('K')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		 $objPHPExcel->getActiveSheet()->getStyle('L')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		 $objPHPExcel->getActiveSheet()->getStyle('M')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		 $objPHPExcel->getActiveSheet()->getStyle('N')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		 $objPHPExcel->getActiveSheet()->getStyle('O')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		 $objPHPExcel->getActiveSheet()->getStyle('P')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		 $objPHPExcel->getActiveSheet()->getStyle('Q')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		 $objPHPExcel->getActiveSheet()->getStyle('R')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		 $objPHPExcel->getActiveSheet()->getStyle('S')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		 $objPHPExcel->getActiveSheet()->getStyle('T')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		 $objPHPExcel->getActiveSheet()->getStyle('U')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		 $objPHPExcel->getActiveSheet()->getStyle('V')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		 $objPHPExcel->getActiveSheet()->getStyle('W')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		 
		  // 将样式应用于A1单元格 
/* 		  $objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($styleArray1); 
		  $objPHPExcel->getActiveSheet()->getStyle('B1')->applyFromArray($styleArray1); 
		  $objPHPExcel->getActiveSheet()->getStyle('C1')->applyFromArray($styleArray1); 
		  $objPHPExcel->getActiveSheet()->getStyle('D1')->applyFromArray($styleArray1); 
		  $objPHPExcel->getActiveSheet()->getStyle('E1')->applyFromArray($styleArray1); 
		  $objPHPExcel->getActiveSheet()->getStyle('F1')->applyFromArray($styleArray1); 
		  $objPHPExcel->getActiveSheet()->getStyle('G1')->applyFromArray($styleArray1); */
		  
		   // 给特定单元格中写入内容 
		  $objPHPExcel->getActiveSheet()->setCellValue('A1', '图片');
		  $objPHPExcel->getActiveSheet()->setCellValue('B1', '商品编号');
		  $objPHPExcel->getActiveSheet()->setCellValue('C1', '商品条形码');
		  $objPHPExcel->getActiveSheet()->setCellValue('D1', '商品名称');
		  $objPHPExcel->getActiveSheet()->setCellValue('E1', '商品分类');
		  $objPHPExcel->getActiveSheet()->setCellValue('F1', '商品品牌');
		  $objPHPExcel->getActiveSheet()->setCellValue('G1', '商品规格');
		  $objPHPExcel->getActiveSheet()->setCellValue('H1', '商品重量');
		  $objPHPExcel->getActiveSheet()->setCellValue('I1', '产地');
		  $objPHPExcel->getActiveSheet()->setCellValue('J1', '生产商');
		  $objPHPExcel->getActiveSheet()->setCellValue('K1', '保质期');
		  $objPHPExcel->getActiveSheet()->setCellValue('L1', '商品单位');
		  $objPHPExcel->getActiveSheet()->setCellValue('M1', '供应价');
		  $objPHPExcel->getActiveSheet()->setCellValue('N1', '批发价');
		  $objPHPExcel->getActiveSheet()->setCellValue('O1', '零售价');
		  $objPHPExcel->getActiveSheet()->setCellValue('P1', '商品库存');
		  $objPHPExcel->getActiveSheet()->setCellValue('Q1', '库存警告数量');
		  $objPHPExcel->getActiveSheet()->setCellValue('R1', 'meta关键字');
		  $objPHPExcel->getActiveSheet()->setCellValue('S1', 'meta描述');
		  $objPHPExcel->getActiveSheet()->setCellValue('T1', '商品赠送');
		  $objPHPExcel->getActiveSheet()->setCellValue('U1', '供应商帐号');
		  $objPHPExcel->getActiveSheet()->setCellValue('V1', '商品图片路径');
		  $objPHPExcel->getActiveSheet()->setCellValue('W1', '商品相册[多个用|分隔]');
		  //循环
		  $k = 1;
		
		  if(!empty($rt))foreach($rt as $row){
		  		  ++$k;
				  //居中
/*				  $objPHPExcel->getActiveSheet()->getStyle('A'.$k)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				  $objPHPExcel->getActiveSheet()->getStyle('B'.$k)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				  $objPHPExcel->getActiveSheet()->getStyle('C'.$k)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				 // $objPHPExcel->getActiveSheet()->getStyle('D'.$k)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				  $objPHPExcel->getActiveSheet()->getStyle('E'.$k)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				  $objPHPExcel->getActiveSheet()->getStyle('F'.$k)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				  $objPHPExcel->getActiveSheet()->getStyle('G'.$k)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				  
				//垂直居中
				$objPHPExcel->getActiveSheet()->getStyle('A'.$k)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
				$objPHPExcel->getActiveSheet()->getStyle('B'.$k)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
				$objPHPExcel->getActiveSheet()->getStyle('C'.$k)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
				$objPHPExcel->getActiveSheet()->getStyle('D'.$k)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
				$objPHPExcel->getActiveSheet()->getStyle('E'.$k)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
				$objPHPExcel->getActiveSheet()->getStyle('F'.$k)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
				$objPHPExcel->getActiveSheet()->getStyle('G'.$k)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);*/
				
				 // 设置行的高度 
		 		 $objPHPExcel->getActiveSheet()->getRowDimension($k)->setRowHeight(50); 
		 
				  //赋值
				 // $objPHPExcel->getActiveSheet()->getStyle('A'.$k)->applyFromArray($styleArray1); 
				  //$objPHPExcel->getActiveSheet()->setCellValue('B'.$k, '图片');
				  // 给单元格中放入图片, 将数据图片放在J1单元格内 
				  $objDrawing = new PHPExcel_Worksheet_Drawing(); 
				  $objDrawing->setName('Logo'); 
				  $objDrawing->setDescription('Logo');
				  $objDrawing->setPath(!empty($row['goods_thumb'])&&file_exists(SYS_PATH.$row['goods_thumb'])?'../'.$row['goods_thumb'] : './images/no_picture.gif'); // 图片路径，只能是相对路径 
				  //$objDrawing->setWidth(60); // 图片宽度 
				  $objDrawing->setHeight(50); // 图片高度 
				  $objDrawing->setCoordinates('A'.$k); 
				  $objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
				  
				  $objPHPExcel->getActiveSheet()->setCellValue('B'.$k, $row['goods_bianhao']);
				  $objPHPExcel->getActiveSheet()->setCellValue('C'.$k, $row['goods_sn']);
				  $objPHPExcel->getActiveSheet()->setCellValue('D'.$k, $row['goods_name']);
				  $objPHPExcel->getActiveSheet()->setCellValue('E'.$k, empty($row['goods_cate_sub'])?$row['goods_cate']:$row['goods_cate_sub']);
				  $brand_name = !empty($row['brand_name']) ? trim(stripcslashes(strip_tags(nl2br($row['brand_name'])))) : '---';
				  $objPHPExcel->getActiveSheet()->setCellValue('F'.$k, $brand_name);
				  $objPHPExcel->getActiveSheet()->setCellValue('G'.$k, $row['goods_brief']);
				  $objPHPExcel->getActiveSheet()->setCellValue('H'.$k, $row['goods_weight']);
				  $objPHPExcel->getActiveSheet()->setCellValue('I'.$k, '');
				  $objPHPExcel->getActiveSheet()->setCellValue('J'.$k, '');
				  $objPHPExcel->getActiveSheet()->setCellValue('K'.$k, '');
				  $objPHPExcel->getActiveSheet()->setCellValue('L'.$k, $row['goods_unit']);
				  $objPHPExcel->getActiveSheet()->setCellValue('M'.$k, $row['market_price']);
				  $objPHPExcel->getActiveSheet()->setCellValue('N'.$k, $row['pifa_price']);
				  $objPHPExcel->getActiveSheet()->setCellValue('O'.$k, $row['shop_price']);
				  $objPHPExcel->getActiveSheet()->setCellValue('P'.$k, $row['goods_number']);
				  $objPHPExcel->getActiveSheet()->setCellValue('Q'.$k, $row['warn_number']);
				  $objPHPExcel->getActiveSheet()->setCellValue('R'.$k, $row['meta_keys']);
				  $objPHPExcel->getActiveSheet()->setCellValue('S'.$k, $row['meta_desc']);
				  $objPHPExcel->getActiveSheet()->setCellValue('T'.$k, '');
				  $objPHPExcel->getActiveSheet()->setCellValue('U'.$k, '');
				  $objPHPExcel->getActiveSheet()->setCellValue('V'.$k, $row['original_img']);
				  $objPHPExcel->getActiveSheet()->setCellValue('W'.$k, '');
		   }
		  
		  	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
			$m_strOutputExcelFileName = (empty($cname)?'商品列表':$cname).date('Y-m-j_H_i_s').".xls"; // 输出EXCEL文件名
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
	}
	
	function goodslist_cache(){
	
		//分页
		$page= isset($_GET['page']) ? $_GET['page'] : '';
		if(empty($page)){
			  $page = 1;
		}
		$list = isset($_GET['list'])&&intval($_GET['list'])>0 ? intval($_GET['list']) : 50;
		$cname = isset($_GET['cname']) ? $_GET['cname'] : '';
		$w = '';
		if(!empty($cname)){
			//$cname = str_replace(' ','',trim($cname));
			$w = " WHERE goods_cate LIKE '%$cname%'";
		}
		$start = ($page-1)*$list;
		$sql = "SELECT COUNT(goods_id) FROM `{$this->App->prefix()}goods_cache_list`$w";
		$tt = $this->App->findvar($sql);
		$pagelink = Import::basic()->getpage($tt, $list, $page,'?page=',true);
		$this->set("pagelink",$pagelink);
		
		
		$sql = "SELECT * FROM `{$this->App->prefix()}goods_cache_list` $w ORDER BY goods_cate ASC, goods_id DESC LIMIT $start,$list";
		
		$this->set('rt',$this->App->find($sql));		
		
		$sql = "SELECT DISTINCT goods_cate FROM `{$this->App->prefix()}goods_cache_list` ORDER BY goods_cate ASC, goods_id DESC";
		$this->set('catelist',$this->App->findcol($sql));
		$this->template('goodslist_cache');
	}
	
	function goodslist(){
	
        $this->js('jquery.json-1.3.js');
		$cname = isset($_GET['cname']) ? $_GET['cname'] : '';
		$w = '';
		if(!empty($cname)){
			$w = " WHERE goods_cate LIKE '%$cname%'";
		}
		
		//分页
		$page= isset($_GET['page']) ? $_GET['page'] : '';
		if(empty($page)){
			  $page = 1;
		}
		$list = 10;
		$start = ($page-1)*$list;
		$sql = "SELECT COUNT(goods_id) FROM `{$this->App->prefix()}goods_cache_list`$w";
		$tt = $this->App->findvar($sql);
		$pagelink = Import::basic()->getpage($tt, $list, $page,'?page=',true);
		$this->set("pagelink",$pagelink);
		
		
		$sql = "SELECT * FROM `{$this->App->prefix()}goods_cache_list` $w ORDER BY goods_id DESC LIMIT $start,$list";
		
		$this->set('rt',$this->App->find($sql));
		
		//分类列表
		$this->set('catelist',$this->action('common','get_goods_cate_tree'));
		
		//品牌列表
		$this->set('brandlist',$this->action('common','get_brand_cate_tree'));
			
		 //供应商列表
		$sql = "SELECT distinct tb1.user_name,tb1.user_id,tb1.nickname FROM `{$this->App->prefix()}user` AS tb1 LEFT JOIN `{$this->App->prefix()}goods` AS tb2 ON tb1.user_id=tb2.uid WHERE tb1.user_rank='10' ORDER BY tb1.user_id DESC";
		$this->set('uidlist',$this->App->find($sql));
			
		$sql = "SELECT DISTINCT goods_cate FROM `{$this->App->prefix()}goods_cache_list` ORDER BY goods_cate ASC, goods_id DESC";
		$this->set('catelist_caiji',$this->App->findcol($sql));
		
		$this->template('goodslist');
	}
	
	function setpreg(){
		$this->css('jquery_dialog.css');
		$this->js(array('jquery_dialog.js'));
		if(!empty($_POST) && !empty($_POST['sitename']) && !empty($_POST['url_preg']) && !empty($_POST['goods_cate_preg'] )){
			if($this->App->insert('goods_cache_site',$_POST)){
				$this->jump('',0,'添加成功'); exit;
			}
		}
		$sql = "SELECT * FROM `{$this->App->prefix()}goods_cache_site`";
		$this->set('rt',$this->App->find($sql));
		$this->template('setpreg');
	}
	
	function starecaiji(){
		$this->template('starecaiji');
	}
	
	function ajaxsetcaijipreg($data=array()){
			$gcid = $data['gcid'];
			$datas['sitename'] = $data['sitename'];
			!empty($data['url_preg']) ? $datas['url_preg'] = str_replace("'",'"',$data['url_preg']) : "";
			!empty($data['goods_cate_preg']) ? $datas['goods_cate_preg'] = str_replace("'",'"',$data['goods_cate_preg']) : "";
			!empty($data['meta_title']) ? $datas['meta_title'] = str_replace("'",'"',$data['meta_title']) : "";
			!empty($data['meta_desc']) ? $datas['meta_desc'] = str_replace("'",'"',$data['meta_desc']) : "";
			!empty($data['meta_keys']) ? $datas['meta_keys'] = str_replace("'",'"',$data['meta_keys']) : "";
			!empty($data['goods_preg_1']) ? $datas['goods_preg_1'] = str_replace("'",'"',$data['goods_preg_1']) : "";
			!empty($data['goods_preg_2']) ? $datas['goods_preg_2'] = str_replace("'",'"',$data['goods_preg_2']) : "";
			!empty($data['goods_preg_3']) ? $datas['goods_preg_3'] = str_replace("'",'"',$data['goods_preg_3']) : "";
			!empty($data['goods_preg_4']) ? $datas['goods_preg_4'] = str_replace("'",'"',$data['goods_preg_4']) : "";
			!empty($data['goods_preg_5']) ? $datas['goods_preg_5'] = str_replace("'",'"',$data['goods_preg_5']) : "";
			$this->App->update('goods_cache_site',$datas,'gcid',$gcid);
			unset($data,$datas);
	}
	
	
	function ajax_caiji_cateurl_start(){
		$data = $_GET;
		unset($_GET);
		$gcid = $data['gcid'];
		$page = $data['kk'];
		$json = Import::json();
		$imgobj = Import::img();
		$crawler = Import::crawler();
		$iconv = Import::gz_iconv();
		
		$rts = array('kk' => 1,'url'=>'','gcid'=>$gcid,'message'=>'ID为空');
		if(!($gcid>0)){
			 die($json->encode($rts));
		}
		 
		$sql = "SELECT * FROM `{$this->App->prefix()}goods_cache_site` WHERE gcid='$gcid'";
		$rt = $this->App->findrow($sql);
		if(!isset($rt['url_preg']) || empty($rt['url_preg']) || empty($rt['goods_cate_preg'])){
			 $rts['message'] = "请先设置抓取网站URL表达式！";
			 die($json->encode($rts));
		}
		$url_preg = $rt['url_preg'];
		$goods_cate_preg = $rt['goods_cate_preg'];
		
		$con = $crawler->curl_get_con(sprintf($url_preg,$page));
		if(!empty($con)){
			 $rts['url'] = '';
			 $rts['message'] = "正在抓取。。。";
			   //匹配
			    $con = $iconv->ec_iconv('GB2312', 'UTF8', $con);
				@preg_match_all($goods_cate_preg,$con,$arr); 
				if(isset($arr[1]) && !empty($arr[1])){
					foreach($arr[1] as $val){
						if(!empty($val)){
							$val = "http://www.21ej.com/".$val;
							//检查是否已经添加
							$sql = "SELECT gcuid FROM `{$this->App->prefix()}goods_cache_url` WHERE url='$val'";
							$gcuid = $this->App->findvar($sql);
							if($gcuid>0){
								$val = "<font color=red>该记录已经存在数据库中</font>";
							}else{
								$dd = array();
								$dd['gcid'] = $gcid;
								$dd['url'] = $val;
								$this->App->insert('goods_cache_url',$dd);
							}
							//加入数据库
							
							$rts['url'] .= $val."<br />";
						}
					}
					$rts['url'] .= "page:".$page."。。。。。。<br/>";
					unset($arr);
				}
				
				//
				/*if($kc>8){
					$rts['url'] = "";
					$rts['message'] = "抓取完成！";
				}*/
		}else{
			$rts['url'] = "";
			$rts['message'] = "抓取完成！";
		}
		
		sleep(1);
		++$page;
		$rts['kk'] = $page;
		die($json->encode($rts));
	}
	
	function ajax_caiji_goodsurl_start(){
		$json = Import::json();
		$imgobj = Import::img();
		$crawler = Import::crawler();
		$iconv = Import::gz_iconv();
		$fileop = Import::fileop();
		
		require_once(SYS_PATH_ADMIN.'inc'.DS.'download.php');
		
		if(isset($_GET['kk']) && isset($_GET['maxpage'])){
			 $imgobj = Import::img();
			 $gcid = $_GET['gcid'];
			 $kk = $_GET['kk'];
			 
			 $list = 1;
			 if($kk==0){
				$tt = $this->App->findvar("SELECT COUNT(gcuid) FROM `{$this->App->prefix()}goods_cache_url`");
				$maxpage = ceil($tt/$list);
			 }else{
				$maxpage = $_GET['maxpage'];
			 }
			
			 $start = $kk*$list;
			 
			 $sql = "SELECT tb1.url,tb2.* FROM `{$this->App->prefix()}goods_cache_url` AS tb1 LEFT JOIN `{$this->App->prefix()}goods_cache_site` AS tb2 ON tb2.gcid = tb1.gcid WHERE tb1.gcid='$gcid' ORDER BY tb1.gcuid DESC LIMIT $start,$list"; //AND tb1.active='1'
			 $rt = $this->App->find($sql);
			 $str = "";
			 //$rts = array('gcid'=>$gcid,'kk' => $kk,'url'=>$sql,'maxpage'=>$maxpage);
			 //die($json->encode($rts));
			 $tw_s = (intval($GLOBALS['LANG']['th_width_s']) > 0) ? intval($GLOBALS['LANG']['th_width_s']) : 200;
			 $th_s = (intval($GLOBALS['LANG']['th_height_s']) > 0) ? intval($GLOBALS['LANG']['th_height_s']) : 200;
			 $tw_b = (intval($GLOBALS['LANG']['th_width_b']) > 0) ? intval($GLOBALS['LANG']['th_width_b']) : 450;
			 $th_b = (intval($GLOBALS['LANG']['th_height_b']) > 0) ? intval($GLOBALS['LANG']['th_height_b']) : 450;
			
			 if(!empty($rt))foreach($rt as $row){
					$url = $row['url'];
					$data = array();
					$con = $crawler->curl_get_con($url);
					if(empty($con)) continue;
					$con = $iconv->ec_iconv('GB2312', 'UTF8', $con);
					
					//分类
					$goods_preg_1 = $row['goods_preg_1'];
					if(!empty($goods_preg_1)){
						@preg_match($goods_preg_1,$con,$arr1);
						$goods_cate = isset($arr1[1]) ? $arr1[1] : "";
						if(!empty($goods_cate)){
							 $data['goods_cate_all'] = $goods_cate;
							 $rr = explode('&gt;', $goods_cate);
							 if(count($rr)<2){
								 $rr = explode('>', $goods_cate);
							 }
							 if(count($rr)>2){
							  	$ra = $rr[count($rr)-2];
							 }else{
							 	 $ra = $rr[count($rr)-1];
							 }
							
							 if(!empty($ra)){
							 	$data['goods_cate'] = trim(stripcslashes(strip_tags(nl2br($ra))));
							 }
						}
					}		
					//标题
					$goods_preg_2 = $row['goods_preg_2'];
					if(!empty($goods_preg_2)){
						@preg_match($goods_preg_2,$con,$arr2);
						$goods_name = isset($arr2[1]) ? $arr2[1] : "";
						if(!empty($goods_name)){ $data['goods_name'] = $goods_name; }else{ continue;}
					}
					
					
					//价格
					$goods_preg_3 = $row['goods_preg_3'];
					$shop_price = 0.00;
					if(!empty($goods_preg_3)){
						@preg_match($goods_preg_3,$con,$arr3);
						$shop_price = isset($arr3[1]) ? $arr3[1] : "";
						if(!empty($shop_price)) $data['shop_price'] = $shop_price;
					}
					
					//检查是否已经采集了
					$sql = "SELECT goods_id FROM `{$this->App->prefix()}goods_cache_list` WHERE goods_name='$goods_name' AND shop_price='$shop_price'";
					$g = $this->App->findvar($sql);
					if($g>0){
						$str = $row['url']."--<font color=red>已经被采集过</font><br/>";
						continue;
					}
					
					$goods_preg_4 = $row['goods_preg_4'];
					if(!empty($goods_preg_4)){
						$goods_preg_4 = str_replace('"',"'",$goods_preg_4);
						@preg_match($goods_preg_4,$con,$arr4);
						$goodsimg = isset($arr4[1]) ? $arr4[1] : "";
						if(empty($goodsimg) || !strpos($goodsimg,".")){
							$str = $row['url']."--<font color=red>采集的数据为空？</font><br/>";
							continue;
						}
						$simg = "photos/g/".date('Ym',mktime())."/ej".mktime().substr($goodsimg,-4);
						$fileop->checkDir(SYS_PATH.$simg);
						DownImageKeep("http://www.21ej.com/".$goodsimg,"http://www.baidu.com",SYS_PATH.$simg,"",0,1);
						//$imgobj->imagescopy("http://www.21ej.com/".$goodsimg,SYS_PATH.$simg);
						//$imgobj->grabImage("http://www.21ej.com/".$goodsimg,SYS_PATH.$simg);
						//DownImageKeep("http://www.21ej.com/".$goodsimg,"http://www.baidu.com",SYS_PATH.$kk."test.gif","",0,1);
						$pa = dirname($simg);
						$thumb = basename($simg);

						if(is_file(SYS_PATH.$simg) && file_exists(SYS_PATH.$simg)){
							$data['original_img'] = $simg;
							$imgobj->thumb(SYS_PATH.$simg,dirname(SYS_PATH.$simg).DS.'thumb_b'.DS.$thumb,$tw_b,$th_b); //大缩略图
							$data['goods_img'] = $pa.'/thumb_b/'.$thumb;
							
							
							$imgobj->thumb(SYS_PATH.$simg,dirname(SYS_PATH.$simg).DS.'thumb_s'.DS.$thumb,$tw_s,$th_s); //小缩略图
							$data['goods_thumb'] = $pa.'/thumb_s/'.$thumb;
						}
					}
					
					
					$goods_preg_5 = $row['goods_preg_5'];
					if(!empty($goods_preg_5)){
						@preg_match($goods_preg_5,$con,$arr5);
						$goods_desc = isset($arr5[1]) ? $arr5[1] : "";
						if(!empty($goods_desc)) $data['goods_desc'] = $goods_desc;
					}
					
					//$str = $row['url']."<br/>";
					$str = '<img src="'.SITE_URL.$simg.'" width="80" alt="'.substr($row['url'],-4).'" />';
					if(!empty($data)){
						$title_preg = $row['meta_title'];
						$metadesc_preg = $row['meta_desc'];
						$metakey_preg = $row['meta_keys'];
				
						$title = $this->__get_meta($title_preg,$con);
						if(!empty($title)) $data['meta_title'] = $title;
						
						$desc = $this->__get_meta($metadesc_preg,$con);
						if(!empty($desc)) $data['meta_desc'] = $desc;
						
						$keys = $this->__get_meta($metakey_preg,$con);
						if(!empty($keys)) $data['meta_keys'] = $keys;
						
						//$data['add_time'] = mktime();
						$this->App->insert('goods_cache_list',$data);
					}
                                        sleep(2);
			 } //end foreach
			
			 $kk = $kk+1;
		 	 $rts['message'] = "";
			 if($kk>$maxpage){
				$kk="";
				$str = "";
				$rts['message'] = "抓取完成！";
			 }
			 $rts = array('gcid'=>$gcid,'kk' => $kk,'url'=>$str,'maxpage'=>$maxpage);
			 die($json->encode($rts));
		} 
	}
	
        function __get_meta($preg,$con){
		  if(empty($preg) || empty($con)) return "";
		  @preg_match($preg,$con,$arr); 
		  return isset($arr[1]) ? trim($arr[1]) : "";
  	}

        function ajax_show_goods_url($gcid=0){
            if(!($gcid>0)) return array();
				$w = "WHERE gcid='$gcid'";
                //分页
				$page= isset($_GET['page']) ? $_GET['page'] : '';
				if(empty($page)){
					  $page = 1;
				}
				$list = 12;
				$start = ($page-1)*$list;
				$sql = "SELECT COUNT(gcuid) FROM `{$this->App->prefix()}goods_cache_url` $w";
				$tt = $this->App->findvar($sql);
				$pagelink = Import::basic()->getpage($tt, $list, $page,'?page=',true);
						$rt['pagelink'] = $pagelink;
		
				$sql = "SELECT * FROM `{$this->App->prefix()}goods_cache_url` $w ORDER BY active DESC,gcuid DESC LIMIT $start,$list";
                $rt['list'] = $this->App->find($sql);
                return $rt;
        }

        function ajax_goods_url_active(){
               $gcuid = $_GET['gcuid'];
               $val = $_GET['val'];
               if(!($gcuid>0)){ echo '非法';exit; }
               $dd = array();		
	       $dd['active'] = $val;

	       $this->App->update('goods_cache_url',$dd,'gcuid',$gcuid);
               exit;
        }

        //删除需要采集的连接
        function ajax_del_goodsurl(){
                $ids = $_GET['ids'];
                if(empty($ids)) die("非法删除，删除ID为空！");
		if(!is_array($ids))
			$id_arr = @explode('+',$ids);
		else
			$id_arr = $ids;

		$this->App->delete('goods_cache_url','gcuid',$id_arr); 
		
		$this->action('system','add_admin_log','删除采集到的链接：'.@implode(',',$id_arr));
		return true;
        
        }

        function ajax_save_caijigoods(){
            $data = $_GET['message'];
            $err = 0;
            $result = array('error' => $err, 'message' => '');
            $json = Import::json();

            if (empty($data))
            {
                $result['error'] = 2;
                $result['message'] = '传送的数据为空！';
                die($json->encode($result));
            }
            $mesobj = $json->decode($data); //反json ,返回值为对象

            //以下字段对应评论的表单页面 一定要一致
            $goods_name = $mesobj->goods_name;
			!empty($goods_name) ? $datas['goods_name'] = $goods_name : "";
            $cat_id = $mesobj->cat_id;
			!empty($cat_id) ? $datas['cat_id'] = $cat_id : "";
			$brand_id = $mesobj->brand_id;
			!empty($brand_id) ? $datas['brand_id'] = $brand_id : "";
            $goods_bianhao = $mesobj->goods_bianhao; 
			!empty($goods_bianhao) ? $datas['goods_bianhao'] = $goods_bianhao : "";
			$goods_sn = $mesobj->goods_sn;
			!empty($goods_sn) ? $datas['goods_sn'] = $goods_sn : "";
            $goods_unit = $mesobj->goods_unit; 
			!empty($goods_unit) ? $datas['goods_unit'] = $goods_unit : "";
			$goods_brief = $mesobj->goods_brief;
			!empty($goods_brief) ? $datas['goods_brief'] = $goods_brief : "";
            $shop_price = $mesobj->shop_price;
			$shop_price>0 ? $datas['shop_price'] = $shop_price : "";
			$pifa_price = $mesobj->pifa_price;
			if(intval($pifa_price)>0){
			 	 $datas['pifa_price'] = intval($pifa_price);
				 $datas['market_price'] = $datas['pifa_price'];
			}else{
				 $datas['pifa_price'] = $shop_price;
				 $datas['market_price'] = $shop_price;
			}
            $goods_number = $mesobj->goods_number;
			intval($goods_number)>0 ? $datas['goods_number'] = intval($goods_number) : "";
			$warn_number = $mesobj->warn_number;
			intval($warn_number)>0 ? $datas['warn_number'] = intval($warn_number) : "";
			$meta_keys = $mesobj->meta_keys;
			!empty($meta_keys) ? $datas['meta_keys'] = $meta_keys : "";
			$meta_desc = $mesobj->meta_desc;
			!empty($meta_desc) ? $datas['meta_desc'] = $meta_desc : "";
			  
			 $original_img = $mesobj->original_img;
			 if(!empty($original_img)){
			 	 $datas['original_img'] = $original_img;
				 $pa = dirname($original_img);
            	 $thumb = basename($original_img);
				 $datas['goods_img'] = $pa.'/thumb_b/'.$thumb;
				 $datas['goods_thumb'] = $pa.'/thumb_s/'.$thumb;
			 }
											
			$gid = $mesobj->goods_id;
			
			$this->App->update('goods_cache_list',$datas,'goods_id',$gid);
            $result['error'] = 0;
            $result['message'] ='修改成功';
			unset($data,$datas);
            die($json->encode($result));
    }
	
	function ajax_save_and_caijigoods(){
		 	$data = $_GET['message'];
            $err = 0;
            $result = array('error' => $err, 'message' => '');
            $json = Import::json();

            if (empty($data))
            {
                $result['error'] = 2;
                $result['message'] = '传送的数据为空！';
                die($json->encode($result));
            }
            $mesobj = $json->decode($data); //反json ,返回值为对象

            //以下字段对应评论的表单页面 一定要一致
            $goods_name = $mesobj->goods_name;
			!empty($goods_name) ? $datas['goods_name'] = $goods_name : "";
            $cat_id = $mesobj->cat_id;
			!empty($cat_id) ? $datas['cat_id'] = $cat_id : "";
			$brand_id = $mesobj->brand_id;
			!empty($brand_id) ? $datas['brand_id'] = $brand_id : "";
            $goods_bianhao = $mesobj->goods_bianhao; 
			!empty($goods_bianhao) ? $datas['goods_bianhao'] = $goods_bianhao : "";
			$goods_sn = $mesobj->goods_sn;
			!empty($goods_sn) ? $datas['goods_sn'] = $goods_sn : "";
            $goods_unit = $mesobj->goods_unit; 
			!empty($goods_unit) ? $datas['goods_unit'] = $goods_unit : "";
			$goods_brief = $mesobj->goods_brief;
			!empty($goods_brief) ? $datas['goods_brief'] = $goods_brief : "";
            $shop_price = $mesobj->shop_price;
			$shop_price>0 ? $datas['shop_price'] = $shop_price : "";
			$pifa_price = $mesobj->pifa_price;
			if(intval($pifa_price)>0){
			 	 $datas['pifa_price'] = intval($pifa_price);
				 $datas['market_price'] = $datas['pifa_price'];
			}else{
				 $datas['pifa_price'] = $shop_price;
				 $datas['market_price'] = $shop_price;
			}
            $goods_number = $mesobj->goods_number;
			intval($goods_number)>0 ? $datas['goods_number'] = intval($goods_number) : "";
			$warn_number = $mesobj->warn_number;
			intval($warn_number)>0 ? $datas['warn_number'] = intval($warn_number) : "";
			$meta_keys = $mesobj->meta_keys;
			!empty($meta_keys) ? $datas['meta_keys'] = $meta_keys : "";
			$meta_desc = $mesobj->meta_desc;
			!empty($meta_desc) ? $datas['meta_desc'] = $meta_desc : "";
			  
			$original_img = $mesobj->original_img;
			if(!empty($original_img)){
			 	 $datas['original_img'] = $original_img;
				 $pa = dirname($original_img);
            	 $thumb = basename($original_img);
				 $datas['goods_img'] = $pa.'/thumb_b/'.$thumb;
				 $datas['goods_thumb'] = $pa.'/thumb_s/'.$thumb;
			 }
			 
			$gid = $mesobj->goods_id;
			 
			if(empty($datas['goods_bianhao'])){
				$gids = $this->App->findvar("SELECT MAX(goods_id) + 1 FROM `{$this->App->prefix()}goods`");
				$gids = empty($gids) ? 1 : $gids;
				$datas['goods_bianhao'] = '2EJ' . str_repeat('0', 6 - strlen($gids)) . $gids.'-'.$gid;
			}
			//检查是否已经存在
			if(empty($datas['goods_sn'])){
				$datas['goods_sn'] = $datas['goods_bianhao'];
			}
			
			$uid = 50;
			$this->App->update('goods_cache_list',$datas,'goods_id',$gid);//更新
			//转移
			$dd = array();
			$dd['goods_number'] = $datas['goods_number']>0 ? $datas['goods_number'] : 1000;
			$dd['warn_number'] = $datas['warn_number']>0 ? $datas['warn_number'] : 10;
			if($datas['market_price']>0)$dd['market_price'] = $datas['market_price'];
			if($datas['pifa_price']>0)$dd['pifa_price'] = $datas['pifa_price'];
			if($datas['shop_price']>0)$dd['shop_price'] = $datas['shop_price'];
			
			$sql = "SELECT add_time FROM `{$this->App->prefix()}goods_cache_list` WHERE goods_id='$gid'";
			$ad = $this->App->findvar($sql);
			if(empty($ad)){
				$datas['add_time'] = mktime();
				$this->App->insert('goods',$datas);
				$lastid = $this->App->iid();
				$this->App->update('goods_cache_list',array('add_time'=>$datas['add_time'],'is_zhuanyi'=>'1'),'goods_id',$gid);
				
				//添加到供应商商品表
				$sql = "SELECT sgid FROM `{$this->App->prefix()}suppliers_goods` WHERE goods_id='$lastid' AND suppliers_id='$uid'";
				$sgid = $this->App->findvar($sql);

				$dd['is_check'] = 1;
				$dd['is_on_sale'] = 1;
				$dd['goods_id'] = $lastid;
				$dd['suppliers_id'] = $uid;
				$dd['addtime'] = mktime();
				$this->App->insert('suppliers_goods',$dd);
								
				$result['message'] ='保存并转移成功';
			}else{
				$sql = "SELECT goods_id FROM `{$this->App->prefix()}goods` WHERE add_time='$ad'";
				$goodid = $this->App->findvar($sql);
				if($goodid>0){ //更新
					$datas['last_update'] = mktime();
					$this->App->update('goods',$datas,'goods_id',$goodid);
					
					//更新供应商商品表
					$sql = "SELECT sgid FROM `{$this->App->prefix()}suppliers_goods` WHERE goods_id='$goodid' AND suppliers_id='$uid'";
					$sgid = $this->App->findvar($sql);

					$dd['is_check'] = 1;
					if(empty($sgid) || !($sgid>0)){
						$dd['is_on_sale'] = 1;
						$dd['goods_id'] = $goodid;
						$dd['suppliers_id'] = $uid;
						$dd['addtime'] = mktime();
						$this->App->insert('suppliers_goods',$dd);
					}else{
						$this->App->update('suppliers_goods',$dd,array("suppliers_id='$uid'","goods_id='$goodid'"));
					}
					
					$result['message'] ='保存并转移修改成功';
				}else{
					$datas['add_time'] = mktime();
					$this->App->insert('goods',$datas);
					$lastid = $this->App->iid();
					$this->App->update('goods_cache_list',array('add_time'=>$datas['add_time'],'is_zhuanyi'=>'1'),'goods_id',$gid);
					
					//添加到供应商商品表
					$sql = "SELECT sgid FROM `{$this->App->prefix()}suppliers_goods` WHERE goods_id='$lastid' AND suppliers_id='$uid'";
					$sgid = $this->App->findvar($sql);

					$dd['is_check'] = 1;
					$dd['is_on_sale'] = 1;
					$dd['goods_id'] = $lastid;
					$dd['suppliers_id'] = $uid;
					$dd['addtime'] = mktime();
					$this->App->insert('suppliers_goods',$dd);
				
					$result['message'] ='保存并转移成功';
				}
			}
            $result['error'] = 0;
           
			unset($data,$datas,$dd);
            die($json->encode($result));
	}
	
	function ajax_del_cache_goods(){
		$goods_id = $_GET['goods_id'];
		if($goods_id>0){
			$sql = "SELECT goods_thumb, goods_img, original_img,is_zhuanyi FROM `{$this->App->prefix()}goods_cache_list` WHERE goods_id ='$goods_id'";
			$imgs = $this->App->findrow($sql);
			
			if(!empty($imgs) && $imgs['is_zhuanyi']='0'){
				if(!empty($imgs['goods_thumb']))
					Import::fileop()->delete_file(SYS_PATH.$imgs['goods_thumb']); //
				if(!empty($imgs['goods_img']))
					Import::fileop()->delete_file(SYS_PATH.$imgs['goods_img']); //
				if(!empty($imgs['original_img']))
					Import::fileop()->delete_file(SYS_PATH.$imgs['original_img']); //
				unset($imgs);
			}
			$this->App->delete('goods_cache_list','goods_id',$goods_id);
		}else{
			die("非法删除，删除ID为空！");
		}
		exit;
	}
	
	//测试之用
	function ttest(){
		$imgobj = Import::img();
		$crawler = Import::crawler();
		$iconv = Import::gz_iconv();
		require_once(SYS_PATH_ADMIN.'inc'.DS.'download.php');
		
		//$crawler->curl_get_con('http://www.womai.com/Product-0-526333.htm');exit;
		//$imgobj->grabImage("http://www.21ej.com/picture/139-848-892.gif",SYS_PATH."test.gif");
		DownImageKeep("http://www.21ej.com/picture/139-848-d892.gif","http://www.baidu.com",SYS_PATH."test.gif","",0,1);
		exit;
		 $sql = "SELECT tb1.url,tb2.goods_preg_1,tb2.goods_preg_2,tb2.goods_preg_3,tb2.goods_preg_4,tb2.goods_preg_5 FROM `{$this->App->prefix()}goods_cache_url` AS tb1 LEFT JOIN `{$this->App->prefix()}goods_cache_site` AS tb2 ON tb2.gcid = tb1.gcid WHERE tb1.gcid='1' LIMIT 3";
		 $rt = $this->App->find($sql);
		 $str = "";
		 if(!empty($rt))foreach($rt as $row){
			$url = $row['url'];
			echo $con = $crawler->curl_get_con($url);exit;
			if(empty($con)) continue;
			$con = $iconv->ec_iconv('GB2312', 'UTF8', $con);
			
			//分类
			$goods_preg_1 = $row['goods_preg_1'];
			@preg_match($goods_preg_1,$con,$arr1);
			$catetitle = isset($arr1[1]) ? $arr1[1] : "";
					
			//标题
			$goods_preg_2 = $row['goods_preg_2'];
			@preg_match($goods_preg_2,$con,$arr2);
			$goodstitle = isset($arr2[1]) ? $arr2[1] : "";
			
			//价格
			$goods_preg_3 = $row['goods_preg_3'];
			@preg_match($goods_preg_3,$con,$arr3);
			$goodsprice = isset($arr3[1]) ? $arr3[1] : "";
			
			$goods_preg_4 = $row['goods_preg_4'];
			if(!empty($goods_preg_4)){
				$goods_preg_4 = str_replace('"',"'",$goods_preg_4);
				@preg_match($goods_preg_4,$con,$arr4);
				$goodsimg = isset($arr4[1]) ? $arr4[1] : "";
				$simg = "photos/g/".date('Ym',mktime())."/ej".mktime().substr($goodsimg,-4);
				$imgobj->imagescopy("http://www.21ej.com/".$goodsimg,SYS_PATH.$simg);
			}
			
			
			$goods_preg_5 = $row['goods_preg_5'];
			if(!empty($goods_preg_5)){
				@preg_match($goods_preg_5,$con,$arr5);
				$goodsdesc = isset($arr5[1]) ? $arr5[1] : "";
			}
			
		 }
		 
	}

}
?>
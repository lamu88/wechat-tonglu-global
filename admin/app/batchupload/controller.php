<?php
class BatchuploadController extends Controller{
	var $rtData= array();
	var $rtData_gallery= array();
	
	//商品的上传
 	function  __construct() {
		
	}
	//显示批量上传页面
	function batchupload(){
		$aa = array('aa'=>'aa','bb'=>'','cc'=>'cc');
		$k = array_keys($aa);
		$v = array_values($aa);// print_r($k); print_r($v);
			$this->template('goods_batch_upload_text');
	}
	
	/*
	*ajax上传
	*$filename: 文件域名称
	*/
	function ajax_upload($filename=""){
		@set_time_limit(600); //最大运行时间
		$fop = Import::fileop();
		$aid = $this->Session->read('adminid');
		$fn = SYS_PATH.'cache'.DS.'admin-'.$aid.'.php'; //记录错误日记
		$fop->writefile($fn,"");
		
		$tw_s = (intval($GLOBALS['LANG']['th_width_s']) > 0) ? intval($GLOBALS['LANG']['th_width_s']) : 200;
		$th_s = (intval($GLOBALS['LANG']['th_height_s']) > 0) ? intval($GLOBALS['LANG']['th_height_s']) : 200;
		$tw_b = (intval($GLOBALS['LANG']['th_width_b']) > 0) ? intval($GLOBALS['LANG']['th_width_b']) : 450;
		$th_b = (intval($GLOBALS['LANG']['th_height_b']) > 0) ? intval($GLOBALS['LANG']['th_height_b']) : 450;
					
		if(!empty($_FILES[$filename]['tmp_name'])){
			$fn = SYS_PATH.'cache'.DS.'admin-upload-'.$aid.'.xls';
			if(file_exists($fn)) unlink($fn); //删除原来文件
			
			$fop->copyfile($filename,$fn); //复制文件到服务器
				if(!file_exists($fn)){
					$fop->copyfile($filename,$fn);
					if(!file_exists($fn)){
						echo '<script> alert("上传时发生意外错误！"); </script>';
						return false;
					}
				}
				$data = Import::excel(); 
				//$data->read($_FILES[$filename]['tmp_name']);
				$data->read($fn); //读取文件
				$importkey = $data->sheets[0]['cells'][1];
				
				for ($i = 2; $i <= $data->sheets[0]['numRows']; $i++) {
					//以下注释的for循环打印excel表数据
					$this->rtData = array();  //商品数据
					$this->rtData_gallery = array();  //商品相册
					
					for ($j = 1; $j <= $data->sheets[0]['numCols']; $j++) {
						 $this->goods_key($importkey[$j],$data->sheets[0]['cells'][$i][$j]); //传送 键=>值 处理
					}
					$this->rtData['is_on_sale'] = '0';
					$uuid = 0;
					$uuid = $this->rtData['uid'];
					if(!($uuid>0)) $uuid = '18';
					$this->rtData['uid'] = $uuid;
					$this->rtData['add_time'] = mktime();
					
					$inData = $this->rtData; //print_r($inData); exit;
					$goods_id = 0;
					//检查该商品已经存在数据库中
					$sn = $inData['goods_bianhao'];//优先级是商品编号检查
					if(!empty($sn)){
						$sql = "SELECT goods_id FROM `{$this->App->prefix()}goods` WHERE goods_bianhao='$sn' AND uid='$uuid'";
						$goods_id = $this->App->findvar($sql);
						//if(!empty($snvar)) continue;
					}else{
						$sa = $inData['goods_name']; //商品名称检查是否该商品已经存在
						if(!empty($sa)){ //最后更新：2012-12-11 10:26
							$sql = "SELECT goods_id FROM `{$this->App->prefix()}goods` WHERE goods_name='$sa' AND uid='$uuid'";
							$goods_id = $this->App->findvar($sql);
							//if(!empty($savar)) continue;
						}
					}
					
					if(!empty($inData['goods_name'])){
						 //商品图片
						$val = $inData['original_img'];
						if(!empty($val)){
							$pa = dirname($val);
							$thumb = basename($val);
			
							if(is_file(SYS_PATH.$pa.DS.'thumb_s'.DS.$thumb)){ $pp = SYS_PATH.$pa.DS.'thumb_s'.DS.mktime().$thumb;  $pps = $pa.'/thumb_s/'.mktime().$thumb;}else{  $pp = SYS_PATH.$pa.DS.'thumb_s'.DS.$thumb; $pps = $pa.'/thumb_s/'.$thumb;}
							Import::img()->thumb(SYS_PATH.$val,$pp,$tw_s,$th_s); //小缩略图
							$inData['goods_thumb'] = $pps;
							
							if(is_file(SYS_PATH.$pa.DS.'thumb_b'.DS.$thumb)){ $pp = SYS_PATH.$pa.DS.'thumb_b'.DS.mktime().$thumb; $pps = $pa.'/thumb_b/'.mktime().$thumb;}else{ $pp = SYS_PATH.$pa.DS.'thumb_b'.DS.$thumb; $pps = $pa.'/thumb_b/'.$thumb;}
							Import::img()->thumb(SYS_PATH.$val,$pp,$tw_b,$th_b); //大缩略图
							$inData['goods_img'] = $pps;
						}
						
							if($goods_id>0){ //更新
								 if($this->App->update('goods',$inData,'goods_id',$goods_id)){
								 	
								 	//将原来的相册图片删除
									$sql = "SELECT img_url FROM `{$this->App->prefix()}goods_gallery` WHERE goods_id ='$goods_id'";
									$gallery_img = $this->App->findcol($sql);
									if(!empty($gallery_img)){
										foreach($gallery_img as $img){
											$q = dirname($img);
											$h = basename($img);
											Import::fileop()->delete_file(SYS_PATH.$q.DS.'thumb_s'.DS.$h);
											Import::fileop()->delete_file(SYS_PATH.$q.DS.'thumb_b'.DS.$h);
											Import::fileop()->delete_file(SYS_PATH.$img); //
										}
										unset($gallery_img);
									}
									$this->App->delete('goods_gallery','goods_id',$goods_id);
									
									//重新处理商品相册
									$rt_gallery = $this->rtData_gallery; 
									if(!empty($rt_gallery)){
										foreach($rt_gallery as $vv){
											$vv = trim($vv);
											$pa = dirname($vv);
											$thumb = basename($vv);
											if(empty($vv) || !is_file(SYS_PATH.$vv)) continue;
											//生成缩略图
											if(is_file(SYS_PATH.$pa.DS.'thumb_s'.DS.$thumb)){ $p = SYS_PATH.$pa.DS.'thumb_s'.DS.mktime().$thumb; }else{ $p = SYS_PATH.$pa.DS.'thumb_s'.DS.$thumb;}
											Import::img()->thumb(SYS_PATH.$vv,$p,$tw_s,$th_s); //小缩略图
											
											if(is_file(SYS_PATH.$pa.DS.'thumb_b'.DS.$thumb)){ $p = SYS_PATH.$pa.DS.'thumb_b'.DS.mktime().$thumb; }else{ $p = SYS_PATH.$pa.DS.'thumb_b'.DS.$thumb;}
											Import::img()->thumb(SYS_PATH.$vv,$p,$tw_b,$th_b); //大缩略图
											
											//插入商品相册属性表
											$dd = array();
											$dd['goods_id'] = $goods_id;
											$dd['img_url'] = $vv;
											
											$this->App->insert('goods_gallery',$dd);
										}// end foreach
									} //end if 商品相册
									
								}else{ //插入失败，写入日记
									$aid = $this->Session->read('adminid');   
									$fn = SYS_PATH.'cache'.DS.'admin-'.$aid.'.php';
									$fop->writefile($fn,"批量导入错误：\n".'商品编号--'.implode('--',array_keys($inData))."\n".$inData['goods_bianhao'].'--'.implode('--',$inData)."\n");
								}
							}else{//插入数据库
								if($this->App->insert('goods',$inData)){
									$lastid = $this->App->iid();
									
									$rt_gallery = $this->rtData_gallery; //商品相册
									//处理商品相册
									if(!empty($rt_gallery)){
										foreach($rt_gallery as $vv){
											$vv = trim($vv);
											$pa = dirname($vv);
											$thumb = basename($vv);
											if(empty($vv) || !is_file(SYS_PATH.$vv)) continue;
											//生成缩略图
											if(is_file(SYS_PATH.$pa.DS.'thumb_s'.DS.$thumb)){ $p = SYS_PATH.$pa.DS.'thumb_s'.DS.mktime().$thumb; }else{ $p = SYS_PATH.$pa.DS.'thumb_s'.DS.$thumb;}
											Import::img()->thumb(SYS_PATH.$vv,$p,$tw_s,$th_s); //小缩略图
											
											if(is_file(SYS_PATH.$pa.DS.'thumb_b'.DS.$thumb)){ $p = SYS_PATH.$pa.DS.'thumb_b'.DS.mktime().$thumb; }else{ $p = SYS_PATH.$pa.DS.'thumb_b'.DS.$thumb;}
											Import::img()->thumb(SYS_PATH.$vv,$p,$tw_b,$th_b); //大缩略图
											
											//插入商品相册属性表
											$dd = array();
											$dd['goods_id'] = $lastid;
											$dd['img_url'] = $vv;
											
											$this->App->insert('goods_gallery',$dd);
										}// end foreach
									} //end if 商品相册
									
								}else{ //插入失败，写入日记
									$aid = $this->Session->read('adminid');
									$fn = SYS_PATH.'cache'.DS.'admin-'.$aid.'.php';
									$fop->writefile($fn,"批量导入错误：\n".'商品编号--'.implode('--',array_keys($inData))."\n".$inData['goods_bianhao'].'--'.implode('--',$inData)."\n");
								}
						} 
					}else{ //写入错误日记
							$aid = $this->Session->read('adminid');
							$fn = SYS_PATH.'cache'.DS.'admin-'.$aid.'.php';
							$fop->writefile($fn,"批量导入错误：\n".implode('--',array_keys($inData))."\n".implode('--',$inData)."\n");
					} //end if
				 }//end for
				 echo '<script> alert("上传成功！");</script>';	return false;
		}
		return $_FILES[$filename];
	}
	
	//导入在商品表的键
	function goods_key($key="",$val=""){
		$key = trim($key);
		$val = trim($val);
		$aid = $this->Session->read('adminid');
		if(!($aid>0)){  echo '<script> alert("非法操作！");</script>'; exit;}	
					
		$arr = array(
			'商品名称'=>'goods_name',
			'商品编号'=>'goods_bianhao',
			'商品条形码'=>'goods_sn',
			'商品规格'=>'goods_brief',
			
		//	'生产商'=>'attr_value_list[]',
		//	'产地'=>'attr_value_list[]',
		//	'保质期'=>'attr_value_list[]',
			'供应价'=>'market_price',
			'批发价'=>'pifa_price',
			'零售价'=>'shop_price',
			'商品重量'=>'goods_weight',
			'商品库存'=>'goods_number',
			'库存警告数量'=>'warn_number',
			'商品单位'=>'goods_unit',
			'商品赠送'=>'buy_more_best',
			'meta关键字'=>'meta_keys',
			'meta描述'=>'meta_desc'
		);
		
		if(isset($arr[$key])){
			if(!empty($val)){
				$this->rtData[$arr[$key]] = addslashes($val);
			}
		}elseif($key=='商品分类'){ //求出商品分类ID
			$sql = "SELECT cat_id FROM `{$this->App->prefix()}goods_cate` WHERE cat_name='$val'";
			$cid = $this->App->findvar($sql);
			if($cid>0){
				$this->rtData['cat_id'] = $cid;
			}
		}elseif($key=='商品品牌'){
			$sql = "SELECT brand_id FROM `{$this->App->prefix()}brand` WHERE brand_name='$val'";
			$bid = $this->App->findvar($sql);
			if($bid>0){
				$this->rtData['brand_id'] = $bid;
			}
			
		/**********  look添加  批量上传品牌名称自动添加  开始   *****************************************/	
			elseif( empty($bid)){
			//	 $n = Import::basic()->Pinyin(trim('$val'));
			//	 $_POST['p_fix'] = !empty($n) ? ucwords(substr($n,0,1)) : "NAL";
            //     $_POST['meta_keys'] = !empty($_POST['meta_keys']) ? str_replace(array('，','。','.'),',',$_POST['meta_keys']) : "";	
				 $brand_name = array("brand_name"=>$val);
                 $sql =  $this->App->insert('brand',$brand_name);
			 
			//	$this->action('system','add_admin_log','添加商品品牌:'.'$val');
			//	$this->action('common','showdiv2',$this->getthisurl());
			//  $sql = "SELECT brand_id FROM `{$this->App->prefix()}brand` WHERE brand_name='$val'";
			//  $bid = $this->App->findvar($sql);
				$this->rtData['brand_id'] = $this->App->iid();
			}
		/**********  look添加  批量上传品牌名称自动添加结束   *****************************************/		
			elseif($key=='产地'){
				$this->rtData['attr_value_list[]'] = "aa";
				if(!empty($val)){
					$this->rtData['attr_value_list[]'] = 'bbb';
				}
			}
			
			
		}elseif($key=='供应商帐号'){
			$this->rtData['uid'] = 0;
			$sql = "SELECT user_id,user_rank FROM `{$this->App->prefix()}user` WHERE user_name='$val'";
			$ur = $this->App->findrow($sql);
			if(!empty($ur) && $ur['user_id']>0 && $ur['user_rank'] =='10' && $ur['active'] =='1'){
				$this->rtData['uid'] = $uid;
			}
		}elseif($key=='商品图片路径'){
			$this->rtData['original_img'] = "";
			//if(!empty($val) && file_exists(SYS_PATH.'photos'.DS.'goods'.DS.$aid.DS.$val)){
				//$this->rtData['original_img'] = 'photos/goods/'.$aid.'/'.$val;
			//}
			if(!empty($val) && file_exists(SYS_PATH.$val)){
				$this->rtData['original_img'] = $val;
			}
		}elseif($key=='商品相册[多个用|分隔]'){
			if(!empty($val)){
				$s = @explode('|',$val);
				if(!empty($s)){
					foreach($s as $v){
						$this->rtData_gallery[] = trim($v);
					}
				}
			} // end if
		} else{//end if
			return true;
		}
	}


	//导入商品属性表的键
	function goods_attr_key(){
	
	}
	
	//下载上传模版
	function download_tpl(){
		  /*header("Content-Type:text/html;charset=utf-8");
          header("Content-type:application/vnd.ms-excel");
          header("Cache-Control: no-cache");
          header("Pragma: no-cache");
          header("Content-Disposition:filename=goods_".date('Y-m-d',time()).".xls");
		  $iconv = Import::gz_iconv();
		  $ar = array('商品名称','商品分类','商品品牌','供应价','批发价','零售价','商品重量','商品单位','供应商帐号','meta关键字','meta描述','商品图片路径','商品相册（多个用|分隔）');		  
		  $str  ='<table border="1" cellspacing="0" cellpadding="0"><tr>';
		  foreach($ar as $val){
				$val = $iconv->ec_iconv('UTF8', 'GB2312', $val);
		  		$str .= '<td>'.$val.'</td>';
		  }
		  $str .='<td>&nbsp;</td>';
		  $str .='</tr><tr>';
		  $c = count($ar)+1;
		  for($i=0;$i<$c;$i++){ $str .= "<td>&nbsp;</td>"; } 
		  $str .='</tr></table>';
		  echo $str;*/
		  
		   /*header("Content-Type: text/csv");   
			 header("Content-Disposition: attachment; filename=test.csv");   
			 header('Cache-Control:must-revalidate,post-check=0,pre-check=0');   
			 header('Expires:0');   
			 header('Pragma:public');   
			echo "id,areaCode,areaName\n";   
			echo "1,cn,china\n";   
			echo "2,us,America\n";   */
		  $fop = Import::fileop();
		  $fop->downloadfile(SYS_PATH.'data/2ej.xls');
		  exit;
	}
	
	function ajax_upload_order($filename=""){
		@set_time_limit(3000); //最大运行时间
		$fop = Import::fileop();
		if(!empty($_FILES[$filename]['tmp_name'])){
				$yuming = str_replace(array('www','.',),'',$_SERVER["HTTP_HOST"]);
				if(!empty($yuming)) $yuming = $yuming.DS;

				$fn = SYS_PATH.'cache'.DS.$yuming.'order'.DS.'vgoodssn.xls';
				if(file_exists($fn)) unlink($fn); //删除原来文件
				
				$fop->copyfile($filename,$fn); //复制文件到服务器
				if(!file_exists($fn)){
					$fop->copyfile($filename,$fn);
					if(!file_exists($fn)){
						echo '<script> alert("上传时发生意外错误！"); </script>';
						return false;
					}
				}
				$data = Import::excel(); 
				$data->read($fn); //读取文件
				$importkey = $data->sheets[0]['cells'][1];
				
				for ($i = 2; $i <= $data->sheets[0]['numRows']; $i++) {
						$rD = array();
						for ($j = 1; $j <= $data->sheets[0]['numCols']; $j++){
							 $rD[] = $data->sheets[0]['cells'][$i][$j];
						}
						
						$wecha_id = $rD[0];
						$ordersn = $rD[1];
						$this->App->insert('goods_order_cache',array('wecha_id'=>$wecha_id,'ordersn'=>$ordersn));
						
				}
				echo '<script> alert("上传成功！"); </script>';
				unset($data,$rD);
		}
	}
	
	//批量上传虚拟商品的卡号跟密码
	function ajax_upload_vgoods_sn($filename="",$rt=array()){
			@set_time_limit(600); //最大运行时间
			$gid = $rt['gid'];
			$fop = Import::fileop();
			unset($rt);
			$vgoods_type = $this->App->findvar("SELECT vgoods_type FROM `{$this->App->prefix()}userconfig` WHERE type = 'basic' LIMIT 1");
			
			if(!empty($_FILES[$filename]['tmp_name'])){
				$yuming = str_replace(array('www','.',),'',$_SERVER["HTTP_HOST"]);
				if(!empty($yuming)) $yuming = $yuming.DS;

				$fn = SYS_PATH.'cache'.DS.$yuming.'vgoods'.DS.'vgoodssn.xls';
				if(file_exists($fn)) unlink($fn); //删除原来文件
				
				$fop->copyfile($filename,$fn); //复制文件到服务器
				if(!file_exists($fn)){
					$fop->copyfile($filename,$fn);
					if(!file_exists($fn)){
						echo '<script> alert("上传时发生意外错误！"); </script>';
						return false;
					}
				}
				$data = Import::excel(); 
				$data->read($fn); //读取文件
				$importkey = $data->sheets[0]['cells'][1];
				
				for ($i = 2; $i <= $data->sheets[0]['numRows']; $i++) {
						$rD = array();
						for ($j = 1; $j <= $data->sheets[0]['numCols']; $j++){
							 $rD[] = $data->sheets[0]['cells'][$i][$j];
							
						}
						if(empty($rD[0])) continue;
						$rtd = array();
						$rtd['goods_id'] = $gid;
						if($vgoods_type=='2'){
							$rtd['goods_pass'] = $rD[0];
							$sn = $rD[0];
						}else{
							$rtd['goods_pass'] = $rD[1];
							$rtd['goods_sn'] = $rD[0];
							$sn = $rD[1];
						}

						$rtd['addtime'] = mktime();
						$sql = "SELECT id FROM `{$this->App->prefix()}goods_sn` WHERE goods_id='$gid' AND goods_pass='$sn' LIMIT 1";
						$id = $this->App->findvar($sql);
						if($id > 0){
							//continue;
						}
						$this->App->insert('goods_sn',$rtd);
				}	
			
				 echo '<script> alert("上传成功！");</script>';	
				 return false;
			}
			 echo '<script> alert("上传失败！");</script>';	
			return true;
	}
}
?>
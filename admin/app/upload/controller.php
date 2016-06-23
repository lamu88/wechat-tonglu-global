<?php
 /*
 * 上传模块
 */
class UploadController extends Controller{
	//构造函数，自动新建对象
 	function  __construct() {
		/*
		*构造函数，自动新建session对象
		*/
	}
	function upload($filename,$filedir=""){
		if(empty($filedir) || empty($filename)) return false;
		$imgobj = Import::img();
		$f_name=$_FILES[$filename]['name'];//获取上传源文件名 
		$t = strrchr($f_name,'.'); //图片类型
		$name = time().time().$t; //原始图名称
		//设置生成缩略图图片的大小
		$dir = basename(dirname($filedir));
		$tw_s=260;
		$th_s=260;
		$tw_b=600;
		$th_b=600;
		$result = false;
		switch($dir){
			case 'articlephoto': //文章图片
			
				break;
			case 'catephoto': //分类图片
			
				break;
			case 'companylogo': //客户公司图片
			 

				break;
			case 'friendlogo': //友情链接图片图片

				break;
			case 'site_icon': //网站图标
			
				break;
			case 'brand': //品牌的banner图
			case 'goods': //商品
				$tw_s = (intval($GLOBALS['LANG']['th_width_s']) > 0) ? intval($GLOBALS['LANG']['th_width_s']) : 260;
				$th_s = (intval($GLOBALS['LANG']['th_height_s']) > 0) ? intval($GLOBALS['LANG']['th_height_s']) : 260;
				$tw_b = (intval($GLOBALS['LANG']['th_width_b']) > 0) ? intval($GLOBALS['LANG']['th_width_b']) : 600;
				$th_b = (intval($GLOBALS['LANG']['th_height_b']) > 0) ? intval($GLOBALS['LANG']['th_height_b']) : 600;
				break;
			case 'avatar';
				$tw_s=150;
				$th_s=150;
				$tw_b=150;
				$th_b=150;
				$result = true;
				break;
		}
		$imgname = $imgobj->upload($filename,$filedir.$name);
		$other = array('banner','brand','cover','top_img');
		if(file_exists($filedir.$name)){
			 if($dir == 'avatar'){  //头像 生成150
			 	$imgobj->thumb($filedir.$name,$filedir.'thumb_s'.DS.$name,$tw_s,$th_s);
				@unlink($filedir.$name);
				//Import::->fileop()->delete_file($filedir.$name);
			 	$imgobj->thumb($filedir.'thumb_s'.DS.$name,$filedir.$name,$tw_b,$th_b);
				@unlink($filedir.'thumb_b'.DS.$name);
				//Import::->fileop()->delete_file($filedir.'thumb_b'.DS.$name);
			 }else if(!in_array($dir,$other)){
			 	$imgobj->thumb($filedir.$name,$filedir.'thumb_s'.DS.$name,$tw_s,$th_s);
			 	$imgobj->thumb($filedir.$name,$filedir.'thumb_b'.DS.$name,$tw_b,$th_b);
			 }
		}
		return $name;
	}
	
	//上传文件
	function uploadfile($filename,$filedir="",$data=array()){
		if(empty($filedir) || empty($filename)) return false;
		$f_name=$_FILES[$filename]['name'];//获取上传源文件名 
		$t = strrchr($f_name,'.'); //类型
		$name = time().time().$t; //原始图名称
		$dir = basename(dirname($filedir));
		if($dir=="excle"){
				$this->action('batchupload','ajax_upload',$filename);
		}elseif($dir=="vgexcle"){ //虚拟卡号码
				//$gid = $data['gid'];
				$this->action('batchupload','ajax_upload_vgoods_sn',$filename,$data);
		}elseif($dir=="orderexcle"){
				$this->action('batchupload','ajax_upload_order',$filename);
		}else{
				$imgname = Import::fileop()->copyfile($filename,$filedir.$name);
		}
		return $name;
	}
}
?>
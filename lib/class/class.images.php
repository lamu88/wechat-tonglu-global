<?php
/*图片处理类
* edit date:2012-12-25
* author   :ajin
*/
 /******************************************************************************
    参数说明:
	$max_file_size  : 上传文件大小限制, 单位BYTE
	$destination_folder : 上传文件路径
	$watermark   : 是否附加水印(1为加水印,其他为不加水印);
	******************************************************************************/
	
class ajinimg{
  //上传文件类型列表
	var $uptypes=array(
		'image/jpg',
		'image/jpeg',
		'image/png',
		'image/pjpeg',
		'image/gif',
		'image/bmp',
		'image/x-png'
	);
	var $DS=DIRECTORY_SEPARATOR;
	var $FormName=''; //文件域名称
	var $max_file_size=1048576;    //上传文件大小限制, 单位BYTE
	var $watermark=0;      //是否附加水印(1为加水印,其他为不加水印);
	var $watertype=1;      //水印类型(1为文字,2为图片)
	var $waterposition=5;     //水印位置(1为左下角,2为右下角,3为左上角,4为右上角,5为居中);
	var $waterstring="CosplayProp.com";  //水印字符串
	var $waterimg= 'xplore.gif';    //水印图片【绝对路径】
	var $imgpreview=1;      //是否生成预览图(1为生成,其他为不生成);
	var $thumb_fla=85;   //缩略图生成的大小参数
        var $mult = 0;
	var $is_upload=true; //符合上传条件为true
	var $Error=array();  //记录错误信息
	var $imgpreviewsize=0.5; 
	var $h = 0;  //当前图片的高度
	var $w = 0; //当前图片的宽度
	
	function __construct()
        {

        }
	
	/*
	*
	* 获取文件大小 
	*/
	function getSize($format = 'B',$key="0",$type=1)
	{
	    $tmp_size = ($type == 1) ?  $_FILES[$this->FormName]['size'] : $_FILES[$this->FormName]['size'][$key];
		if ($this->is_upload){
			if ($tmp_size == 0){
			  $this->seterror("文件仅部分被上传 <br />");
			  $this->is_upload = false;
			 }
			 switch ($format){
			 case 'B':
			 return $tmp_size;
			 break;
			 case 'M':
			 return ($tmp_size)/(1024*1024);
			 }
		}
	}
	 
	 //上传文件
	 /*
	 *@formname:文件域的名称
	 *@filename:上传到服务器的文件名称
	 *@image_dir :图片存放目录
	 */
	function upload($formname,$image_dir = '',$key="no",$type=1)
	{   
		$this->checkDir($image_dir);
	    $DS=$this->DS;
		if(!empty($formname)) { $this->FormName = $formname;} else { $this->seterror("文件域为空!<br />"); return false;}
		if(empty($image_dir)) { $this->seterror( "图片存放路径为空!!<br />");}
		 
		if($type == 1){ 
			$tmp_name = $_FILES[$this->FormName]['tmp_name'];
			$tmp_type = $_FILES[$this->FormName]["type"];
			$tmp_size = $_FILES[$this->FormName]["size"];
		}else{ 
		    $tmp_name = $_FILES[$this->FormName]['tmp_name'][$key];
		    $tmp_type = $_FILES[$this->FormName]["type"][$key];
		    $tmp_size = $_FILES[$this->FormName]["size"][$key];
		}
		
		//如果设置为水印，则检查水印图片是否存在
		if($this->watertype==2){
		  if(!file_exists($this->waterimg)){
			   $this->seterror("添加水印的图片不存在 ！ <br />".$this->waterimg.'<br />');
			   $this->is_upload=false;
		  }
		}
		
		if ($tmp_size == 0){
			$this->seterror("文件仅部分被上传 <br />");
			$this->is_upload = false;
			return false;
			break;
		}
	
		if (!is_uploaded_file($tmp_name))
		//是否存在文件
		{
			 $this->seterror("上传的文件不存在 ！<br />");
			 $this->is_upload=false;
			 return false;
		}
		
		if($this->max_file_size < $tmp_size)
		//检查文件大小
		{
			$this->seterror( "文件太大! <br />");
			$this->is_upload=false;
			return false;
		}
	
		if(!in_array($tmp_type, $this->uptypes))
		//检查文件类型
		{
			$this->seterror("文件类型不符!".$tmp_type.'<br />');
			$this->is_upload=false;
			return false;
		}

		if (is_file($image_dir)&&file_exists($image_dir))
		{ 
			$this->seterror("同名文件已经存在了");
			$this->is_upload=false;
			return false;
		}
		
		if($this->is_upload){ 
		   $doUpload=@copy($tmp_name, $image_dir);
			if($doUpload)
			{
				@chmod($image_dir, 0777);
			}else{
				$this->seterror("没有找到要上传的文件 <br />");
				$this->is_upload=false;
				return false;
			}
		}
		
		//是否添加水印
		 if($this->is_upload){
		   if($this->watermark==1)
		   { 
			$iinfo=getimagesize($image_dir);
			$nimage=imagecreatetruecolor($iinfo[0],$iinfo[1]);
			$white=imagecolorallocate($nimage,255,255,255);
			$black=imagecolorallocate($nimage,0,0,0);
			$red=imagecolorallocate($nimage,255,0,0);
			//imagefill($nimage,0,0,$white);
			switch ($iinfo[2])
			{
				case 1:
				$simage =imagecreatefromgif($image_dir);
				break;
				case 2:
				$simage =imagecreatefromjpeg($image_dir);
				break;
				case 3:
				$simage =imagecreatefrompng($image_dir);
				break;
				case 6:
				$simage =imagecreatefromwbmp($image_dir);
				break;
				default:
				$this->seterror("不支持的文件类 <br />型");
				$this->is_upload=false;
				return false;
			}
	
			imagecopy($nimage,$simage,0,0,0,0,$iinfo[0],$iinfo[1]);
	
			switch($this->watertype)
			{
				case 1:   //加水印字符串
				imagestring($nimage,2,3,$iinfo[1]-15,$this->waterstring,$black);
				break;
				case 2:   //加水印图片
				{
				$inn=getimagesize($this->waterimg);  
				switch($inn[2]){    //1:GIG  2:JPEG  3:PNG
					case 1:
					  $in=@imagecreatefromGIF($this->waterimg);
					  break;
				
					case 2:
					  $in=@imagecreatefromJPEG($this->waterimg);
					  break;
					case 3:
					  $in=@imagecreatefromPNG($this->waterimg);
					  break;
			      }	
				$wh = imagecolorallocate($in, 255, 255, 255); 
				imagecolortransparent($in,$wh);   
				imagecopy($nimage,$in,rand(10,$iinfo[0]-50),rand(10,$iinfo[1]-50),0,0,$inn[0],$inn[1]);
				imagedestroy($in);
				break;
				}
			}
	
			switch ($iinfo[2])
			{
				case 1:
				imagejpeg($nimage,$image_dir);
				break;
				case 2:
				imagejpeg($nimage,$image_dir);
				break;
				case 3:
				imagepng($nimage,$image_dir);
				break;
				case 6:
				imagewbmp($nimage,$image_dir);
				break;
			}		
		   } //end 水印IF
		 }//end 是否符合上传IF
	} //end function 
	
	 /*
	 *
	 *远程或本地图片的复制
	 *$imgname :原始图片
	 *$d_img:新生成的路劲
	 */
	 function imagescopy($imgname,$d_img){
	 	$this->checkDir($d_img);
		$imgname = Import::gz_iconv()->ec_iconv('UTF8', 'GB2312',$imgname);
		$d_img = Import::gz_iconv()->ec_iconv('UTF8', 'GB2312',$d_img);
	 	$src_im = @imagecreatefromjpeg($imgname);  
		$srcW = @imagesx($src_im);                                                       //获得图像的宽  
		$srcH = @imagesy($src_im);                                                       //获得图像的高  
		
		$dst_im = @imagecreatetruecolor($srcW,$srcH);                    //创建新的图像对象  
		
		@imagecopy($dst_im, $src_im, 0, 0, 0, 0, $srcW, $srcH);  
		@imagejpeg($dst_im, $d_img);    
	 }
	 
	 /**
	 * 抓取远程图片
	 *
	 * @param string $url 远程图片路径
	 * @param string $filename 本地存储文件名
	 */
	function grabImage($url, $filename = '') {
		if($url == '') {
			return false; //如果 $url 为空则返回 false;
		}
		$ext_name = strrchr($url, '.'); //获取图片的扩展名
		if($ext_name != '.gif' && $ext_name != '.jpg' && $ext_name != '.bmp' && $ext_name != '.png') {
			return false; //格式不在允许的范围
		}
		if($filename == '') {
			$filename = time().$ext_name; //以时间戳另起名
		}
		//开始捕获
		ob_start();
		readfile($url);
		$img_data = ob_get_contents();
		ob_end_clean();
		$size = strlen($img_data);
		$local_file = fopen($filename , 'a');
		fwrite($local_file, $img_data);
		fclose($local_file);
		return $filename;
	}

	 //文件复制
	function & filecopy($path, $topath){
		$path = Import::gz_iconv()->ec_iconv('UTF8', 'GB2312',$path);
		$topath = Import::gz_iconv()->ec_iconv('UTF8', 'GB2312',$topath);
		
        $pathArr = pathinfo($topath);
        $dirArr = explode(DIRECTORY_SEPARATOR, $pathArr['dirname']);
        unset($pathArr);
        $dir = null;
        foreach($dirArr as $folder){
            $dir .= $folder.DIRECTORY_SEPARATOR;
            if(!@file_exists($dir))@mkdir($dir,0777);
        }
		if(!@file_exists($path)) return "";
		return @copy($path, $topath);
    }
	
	 /*
	 *检查路径，不存在则创建
	 */
	 function checkDir($fn){ 
	    $fn = str_replace('/',DS,$fn);
		$pathArr = pathinfo($fn);
		if(is_dir($pathArr['dirname'])) return true;
		$dirArr = explode($this->DS, $pathArr['dirname']); 
        unset($pathArr);
        $dir = null;
        foreach($dirArr as $folder){
            $dir .= $folder.$this->DS;
            if(!@is_dir($dir))@mkdir($dir,0777);
        }
        unset($dirArr);
	 }
	 
   /*
   * @创建图片缩略图
   * @
   */
	function thumb($source_dir,$thumb_dir,$width=85,$height=85,$op=false)
	{   
		$source_dir = Import::gz_iconv()->ec_iconv('UTF8', 'GB2312',$source_dir);
		$thumb_dir = Import::gz_iconv()->ec_iconv('UTF8', 'GB2312',$thumb_dir);
		
		$this->checkDir($thumb_dir);
	    if(!file_exists($source_dir) && is_dir(dirname($source_dir))){
		  $this->seterror("不能生成缩略图，源文件不存在！<br />");
		  return false;
		}
		
	     $DS=$this->DS;

		 $data = getimagesize($source_dir);  
		 switch ($data[2]) {
		 case 1:
		 $im = @imagecreatefromgif($source_dir);
		 break;
	  
		 case 2:
		 $im = @imagecreatefromjpeg($source_dir);
		 break;
	  
		 case 3:
		 $im = @imagecreatefrompng($source_dir);
		 break;
		 }
		 
		 $srcW=imagesx($im);
		 $this->setwidth($srcW);
		 $srcH=imagesy($im);
		 $this->setheight($srcH);	
		 
		 /*if($srcW>$srcH){ //宽大于高
		 	 if($srcW<$width){
				$width = $srcW;
				$height = $srcH*($width/$srcW);
			 }
		 }else{ //高大于宽
		 	 if($srcH<$height){
				$height = $srcH;
				$width = $srcW*($height/$srcH);
			 }
		 }
		 $tw = $width;
		 $th = $height;
		 $ni=imagecreatetruecolor($width,$height);
		 //用白色填充	  	
		 sscanf('FFFFFF', "%2x%2x%2x", $red, $green, $blue); 
		 $clr = imagecolorallocate($ni, $red, $green, $blue);
         imagefilledrectangle($ni, 0, 0, $width, $height, $clr);
		 		 
		 $mult = $width/$srcW;
		 if($height/$srcH<$mult) {
			  $mult = $height/$srcH;
		 }
		 $width = $srcW*$mult;
		 $height =$srcH*$mult;
		 
		 $dst_x = ($tw  - $width)  / 2;
         $dst_y = ($th - $height) / 2;
		
		// imagecopyresized($ni,$im,0,0,0,0,$width,$height,$srcW,$srcH); //生成一般缩略图
		 imagecopyresampled($ni,$im, $dst_x, $dst_y, 0, 0,$width,$height,$srcW,$srcH); //生成高清缩略图 必须支持GD2
		// imagecopyresampled($ni,$im, 0, 0, 0, 0,$width,$height,$srcW,$srcH); //生成高清缩略图
		 $cr = imagejpeg($ni,$thumb_dir);
		 chmod($thumb_dir, 0777); 
		 */ 
		 $mult = $width/$srcW;
		 if($height/$srcH<$mult) {
			  $mult = $height/$srcH;
		 }
		 $width = $srcW*$mult;
		 $height =$srcH*$mult;
		 /*
		 $dst_x = ($tw  - $width)  / 2;
         $dst_y = ($th - $height) / 2;
		 */
		 $ni=imagecreatetruecolor($width,$height);
		 sscanf('FFFFFF', "%2x%2x%2x", $red, $green, $blue); 
		 $clr = imagecolorallocate($ni, $red, $green, $blue);
         imagefilledrectangle($ni, 0, 0, $width, $height, $clr);
		 
		// imagecopyresized($ni,$im,0,0,0,0,$width,$height,$srcW,$srcH); //生成一般缩略图
		 //imagecopyresampled($ni,$im, $dst_x, $dst_y, 0, 0,$width,$height,$srcW,$srcH); //生成高清缩略图 必须支持GD2
		 imagecopyresampled($ni,$im, 0, 0, 0, 0,$width,$height,$srcW,$srcH); //生成高清缩略图
		 //imagecolorallocatealpha();
		 $cr = imagejpeg($ni,$thumb_dir);
		 chmod($thumb_dir, 0777);  
		 
		 if (!$cr){
			  $this->seterror("缩略图生成失败 <br />");
			  return false;
		 }
		 
		 //是否添加水印
		 if($this->is_upload){ 
		   if($this->watermark==1)
		   { 
			$iinfo=getimagesize($thumb_dir);
			$nimage=imagecreatetruecolor($iinfo[0],$iinfo[1]);
			$white=imagecolorallocate($nimage,255,255,255);
			$black=imagecolorallocate($nimage,0,0,0);
			$red=imagecolorallocate($nimage,255,0,0);

			switch ($iinfo[2])
			{
				case 1:
				$simage =imagecreatefromgif($thumb_dir);
				break;
				case 2:
				$simage =imagecreatefromjpeg($thumb_dir);
				break;
				case 3:
				$simage =imagecreatefrompng($thumb_dir);
				break;
				case 6:
				$simage =imagecreatefromwbmp($thumb_dir);
				break;
				default:
				$this->seterror("不支持的文件类 <br />型");
				$this->is_upload=false;
				return false;
			}
	
			imagecopy($nimage,$simage,0,0,0,0,$iinfo[0],$iinfo[1]);
	
			switch($this->watertype)
			{
				case 1:   //加水印字符串
				imagestring($nimage,2,3,$iinfo[1]-15,$this->waterstring,$black);
				break;
				case 2:   //加水印图片
				{
				$inn=getimagesize($this->waterimg);  
				switch($inn[2]){    //1:GIG  2:JPEG  3:PNG
					case 1:
					  $in=@imagecreatefromGIF($this->waterimg);
					  break;
				
					case 2:
					  $in=@imagecreatefromJPEG($this->waterimg);
					  break;
					case 3:
					  $in=@imagecreatefromPNG($this->waterimg);
					  break;
			      }	
				$wh = imagecolorallocate($in, 255, 255, 255); 
				imagecolortransparent($in,$wh);   
				imagecopy($nimage,$in,rand(20,$iinfo[0]-100),rand(20,$iinfo[1]-100),0,0,$inn[0],$inn[1]);
				imagedestroy($in);
				break;
				}
			}

			switch ($iinfo[2])
			{
				case 1:
				imagejpeg($nimage,$thumb_dir);
				break;
				case 2:
				imagejpeg($nimage,$thumb_dir);
				break;
				case 3:
				imagepng($nimage,$thumb_dir);
				break;
				case 6:
				imagewbmp($nimage,$thumb_dir);
				break;
			}		
		   } //end 水印IF
		 }//end 是否符合上传IF
		 return true;
       }// end function 
	 
	 //图片的宽度
	 function getwidth($imgpath=null){
	   if($this->w){
	     return  $this->w;
	   }
	   if(@file_exists($imgpath)){
	     $info = @getimagesize($imgpath);  
		 return $info[0];
	    }else{
		  return 0;
	    }
	 }
	 //设置图片的宽度
	 function setwidth($val){
	  $this->w= $val;
	 }
	 //获取图片的高度
	 function getheight($imgpath=null){
	   if($this->h){
	     return  $this->h;
	   }
	   if(@file_exists($imgpath)){
	     $info = @getimagesize($imgpath);  
		 return $info[1];
	    }else{
		  return 0;
	    }
	 }
	  //设置图片的高度
	 function setheight($val){
	  $this->h= $val;
	 }
	 
	//错误信息空间
	function seterror($var){
	  if(!empty($var))
	     $this->Error[]=$val;
	}
    //获取错误信息 return array;
	function geterror(){
	  return $this->Error;
	}

} //end class

?>
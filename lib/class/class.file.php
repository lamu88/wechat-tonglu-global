<?php
/*
* edit date:2011-11-10
* author : ajin
* 
*/

if(!defined('DS'))  define('DS',DIRECTORY_SEPARATOR);
//文件操作类
class FileOp{
	var $error = array(); //记录错误信息
	/*
		 默认允许下载的文件类型
		 type of file which allow to down load
		 is array
	*/
	var $allow=array(".jpg",".txt",".gif",".png",".rar",".zip",".doc",".xls");
	
	/*
		 检查文件是否存在并检测是否允许下载此类型的文件
	*/
	function get_ext($file){
		$file_ext=substr($file,-4);
	  
		if(!file_exists($file)){
		 	$this->seterror("未找到此文件.");
			return false;
		}
	    if(!in_array($file_ext,$this->allow)){
			$this->seterror("不允许下载此类文件.");
			return false;
	    }
		return true;
	}
  
   /*
   	  设置允许下载的文件类型
   */
   function set_ext($filetype = array()){
   		if(!is_array($filetype)){
			$filetype = "array('".$filetype."')";
		}
   		$this->allow = $filetype;
   }
	/*
	* 文件下载
	* @parme $fullname 绝对路径
	*/
	function downloadfile($fullname, $mimeType = 'application/octet-stream'){
		  if($this->get_ext($fullname)){
			  header("Content-Type: {$mimeType}");
			  $filename = date('Ymd',time()).'-'.basename($fullname);
			  $filesize = filesize($fullname);
			  header("Content-Disposition: attachment; filename={$filename}; charset=gb2312");
			  header("Content-Length: {$filesize}");
			  readfile($fullname);
			  exit;
			}else{
				$error_ar = $this->geterror();
				die(@implode("\n",$error_ar));
			}
	}
	
	//获取错误数组数据
	function geterror(){
		return $this->error;
	}
	
	//设置一个错误
	function seterror($var){
		$this->error[] = $var;
	}
	
	 /*
     * 遍历文件夹文件
     * @$folder:表示遍历的目录
     */
    function list_files( $folder = '', $levels = 100 ) { 
        if( empty($folder) )
            return array();

        if( ! $levels )
            return array();

        $files = array();
        if ( $dir = @opendir( $folder ) ) {
            while (($file = readdir( $dir ) ) !== false ) {
                if ( in_array($file, array('.', '..') ) )
                    continue;
                if ( is_dir( $folder . '/' . $file ) ) {
                    $files2 =$this->list_files( $folder . '/' . $file, $levels - 1);
                    if( $files2 )
                        $files = array_merge($files, $files2 );
                    else
                        $files[] = $folder . '/' . $file . '/';
                } else {
                    $files[] = $folder . '/' . $file;
                }
            }
        }
        @closedir( $dir );
        return $files;
    }
	/*
     * 多重判断删除文件
     * @clearstatcache() 函数清除文件状态缓存。
     */
   function delete_file($file="")
   {
   	if(!$file) return false;
    if (file_exists($file))
    { 
        $delete = chmod ($file, 0777);
        $delete = unlink($file);
        if(file_exists($file))
        {
            $filesys = eregi_replace("/","\\",$file);
            $delete = system("del $filesys");
            clearstatcache();
            if(file_exists($file))
            {
                $delete = chmod ($file, 0777);
                $delete = unlink($file);
                $delete = system("del $filesys");
            }
        }
        clearstatcache();
        if(file_exists($file))
            return false;
        else
            return true;
    }
    else
    {
        return true;
    }
  }
  //删除目录
   function delete_dir($dir=""){ 
  	if($dir){
		if(is_dir($dir)){
			chmod ($dir, 0777);
			rmdir($dir); 
			return true;
		}
	}else{
	 return false;
	}
  }
  
   //删除指定目录下的目录及文件
    function dir2delete($dir){
        if(!file_exists($dir)){
            return false;
        }
        if(is_dir($dir)){
            $result = array_reverse(FileOp::dir2arr($dir));
            if(empty($result)){
                return true;
            }
            foreach($result as $folder => $files){
                foreach($files as $file){
                    @unlink($file);
                }
                @rmdir($folder);
            }
        }else if(is_file($dir)){
            @unlink($dir);
        }
    }
	
	 function dir2arr($dir, $unread=array()){
        $result[$dir] = array();
        $resource = opendir($dir);
        while($file = readdir($resource)){
            if(!empty($unread)){
                if(in_array($file, $unread)){
                    continue;
                }
            }
            if($file <> '..' && $file <> '.'){
                is_dir($dir.DS.$file)
                ? $result = array_merge($result,FileOp::dir2arr($dir.DS.$file))
                : $result[$dir][] = $dir.DS.$file;
            }
        }
        return  $result;
    }
  
  /*一切文件复制
  * $filesname : 原文件
  * $uploaddir : 复制到的路径
  * 都市绝对路径
  */
  function copyfile($filesname="",$uploaddir="")
  { 
  		if(empty($uploaddir)) { $this->seterror('dir is empty'); return false;}
		if(empty($filesname)) { $this->seterror('FILES is empty'); return false;}
		$file_size_max = 1024*1024*20;
		$tmp_name =$_FILES[$filesname]['tmp_name'];  // 文件上传后得临时文件名
		$name     =$_FILES[$filesname]['name'];     // 被上传文件的名称
		$size     =$_FILES[$filesname]['size'];    //  被上传文件的大小
		$type     =$_FILES[$filesname]['type'];   // 被上传文件的类型
		//$dir      = $uploaddir.date("Ym")."/".time().$name;
		if($size > $file_size_max){
			 $this->seterror('Upload file is too big!'.$size); return false;
		}
		$this->checkDir($uploaddir); 
		if(!move_uploaded_file($tmp_name,$uploaddir)){
			$this->seterror('Upload file Failure!'); return false;
		}else{
			return $dir;
		}
    }
	
  	/*
	 *检查路径，不存在则创建
	 */
	function checkDir($fn){
		if(empty($fn)) die('设置的路径为空！');
		$fn = str_replace('/',DS,$fn);
		$pathArr = pathinfo($fn); 
		if(is_dir($pathArr['dirname'])) return true;
		$dirArr = explode(DS, $pathArr['dirname']);
        unset($pathArr);
        $dir = null;
        foreach($dirArr as $folder){
            $dir .= $folder.DS;
            if(!file_exists($dir))@mkdir($dir,0777);
        }
        unset($dirArr);
	 }
	 
	 //生成静态页
	 function markhtml($url,$markpath){
	 	@set_time_limit(300);
	 	$this->checkDir($markpath);
		if (is_file($markpath)){ @unlink($markpath);  }
		$con = "";
	 	$con =Import::crawler()->cfile_get_con($url);
		if(!empty($con)){
			//$con = iconv("GBK", "UTF-8", $con);
		}
		 //生成
		$con = str_replace(array('gb2312','GB2312','GBK'),'UTF-8',$con);
		
		$newstr=fopen($markpath,"w"); //打开
		
		if (!is_writable ($markpath))
		{     
		
			die ("文件：".$markpath."不可写，请检查其属性后重试！");   
		
		}  
		if (!fwrite ($newstr,$con)){   die ("生成文件".$markpath."失败！");   }    
		
		fclose($newstr);
		/*
		ob_start();
		@readfile("http://localhost/?package=pricab&place_port=4");
		$text = ob_get_flush();
		$myfile = fopen("myfile.html","w");
		$text = str_replace ("{counent}",$string,$text);    
		fwrite($myfile,$text);    
		ob_clean(); 
		*/
	 }
	 
	 /**
	 * 文件或目录权限检查函数
	 *
	 * @access          public
	 * @param           string  $file_path   文件路径
	 * @param           bool    $rename_prv  是否在检查修改权限时检查执行rename()函数的权限
	 *
	 * @return          int     返回值的取值范围为{0 <= x <= 15}，每个值表示的含义可由四位二进制数组合推出。
	 *                          返回值在二进制计数法中，四位由高到低分别代表
	 *                          可执行rename()函数权限、可对文件追加内容权限、可写入文件权限、可读取文件权限。
	 */
	function file_mode_info($file_path)
	{ 
		/* 如果不存在，则不可读、不可写、不可改 */
		if (!file_exists($file_path))
		{
			return false;
		}
	
		$mark = 0;
	
		if (strtoupper(substr(PHP_OS, 0, 3)) == 'WIN')
		{
			/* 测试文件 */
			$test_file = $file_path . '/cf_test.txt';
	
			/* 如果是目录 */
			if (is_dir($file_path))
			{
				/* 检查目录是否可读 */
				$dir = @opendir($file_path);
				if ($dir === false)
				{
					return $mark; //如果目录打开失败，直接返回目录不可修改、不可写、不可读
				}
				if (@readdir($dir) !== false)
				{
					$mark ^= 1; //目录可读 001，目录不可读 000
				}
				@closedir($dir);
	
				/* 检查目录是否可写 */
				$fp = @fopen($test_file, 'wb');
				if ($fp === false)
				{
					return $mark; //如果目录中的文件创建失败，返回不可写。
				}
				if (@fwrite($fp, 'directory access testing.') !== false)
				{
					$mark ^= 2; //目录可写可读011，目录可写不可读 010
				}
				@fclose($fp);
	
				@unlink($test_file);
	
				/* 检查目录是否可修改 */
				$fp = @fopen($test_file, 'ab+');
				if ($fp === false)
				{
					return $mark;
				}
				if (@fwrite($fp, "modify test.\r\n") !== false)
				{
					$mark ^= 4;
				}
				@fclose($fp);
	
				/* 检查目录下是否有执行rename()函数的权限 */
				if (@rename($test_file, $test_file) !== false)
				{
					$mark ^= 8;
				}
				@unlink($test_file);
			}
			/* 如果是文件 */
			elseif (is_file($file_path))
			{
				/* 以读方式打开 */
				$fp = @fopen($file_path, 'rb');
				if ($fp)
				{
					$mark ^= 1; //可读 001
				}
				@fclose($fp);
	
				/* 试着修改文件 */
				$fp = @fopen($file_path, 'ab+');
				if ($fp && @fwrite($fp, '') !== false)
				{
					$mark ^= 6; //可修改可写可读 111，不可修改可写可读011...
				}
				@fclose($fp);
	
				/* 检查目录下是否有执行rename()函数的权限 */
				if (@rename($test_file, $test_file) !== false)
				{
					$mark ^= 8;
				}
			}
		}
		else
		{
			if (@is_readable($file_path))
			{
				$mark ^= 1;
			}
	
			if (@is_writable($file_path))
			{
				$mark ^= 14;
			}
		}
	
		return $mark;
	}
	
	//获取文件夹保存子文件夹和文件夹以下文件的大小
	function holdersize($hold='/',$holdersize=0){
		if(!is_dir($hold)) return "先指定获取的路径！";
		
		if (@$handle = @opendir($hold)) {
		 	while (false !== ($file = @readdir($handle))) {
			if ($file != "." && $file != "..") {
			 if(is_dir($hold.'/'.$file)) $this->holdersize($hold.'/'.$file,$holdersize);
				$holdersize=$holdersize+filesize($hold.'/'.$file);
			}
			}return $holdersize;
		}else return '无法获取'.$hold.'目录';
	}
	
	 function writefile($fn, $content,$mode = 'a'){
        $pathArr = pathinfo($fn);
        $dirArr = explode(DS, $pathArr['dirname']);
        unset($pathArr);
        $dir = null;
        foreach($dirArr as $folder){
            $dir .= $folder.DS;
            if(!@file_exists($dir))@mkdir($dir,0777);
        }
        unset($dirArr);

        $re = fopen($fn, $mode);
        if(!is_resource($re)){
            return false;
        }
        @fwrite($re, $content);
        @fclose($re);
        @chmod($fn, 0777);
        return true;
    } 
	
	//下载图片、
	function download_remote_file_with_curl($file_url, $save_to)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_POST, 0); 
		curl_setopt($ch,CURLOPT_URL,$file_url); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
		$file_content = curl_exec($ch);
		curl_close($ch);
 
		$downloaded_file = fopen($save_to, 'w');
		fwrite($downloaded_file, $file_content);
		fclose($downloaded_file);
	}
  // end funciton
}
?>
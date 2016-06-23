<?php
require_once('../load.php');

@set_time_limit(1800); //最大执行时间1800S
$sort=12;
$f_type=strtolower("zip,jpg,rar,png,gif");//设置可上传的文件类型 
$file_size_max=1024*1024*15;//限制单个文件上传最大容量  最大10M 
$overwrite = 0;//是否允许覆盖相同文件,1:允许,0:不允许 
$f_input="Files";//设置上传域名称

$uids = $app->action('suppliers','get_user_id');
$uids = empty($uids) ? 'user-default' : 'user-'.$uids;

$attdir=SYS_PATH_PHOTOS."temp".DS.$uids.DS; 
$fop = Import::fileop(); //文件操作对象

clearfile(SYS_PATH_PHOTOS."temp".DS.$uids); //遍历文件，将该库里的文件删除
//循环调用多层文件夹文件
function clearfile($dir=''){
	global $fop;
	if(empty($dir)) return false;
	$file_arr = $fop->list_files($dir);
	if(!empty($file_arr)){
		foreach($file_arr as $filename){
			if(is_file($filename)){
				$fop->delete_file($filename); //删除文件
			}else if(is_dir($filename)){ 
				$fop->delete_dir($filename); //删除目录
			}
		}
		unset($file_arr);
		$file_arr = $fop->list_files($dir);
		if(!empty($file_arr)){
			clearfile($dir);
		}
	}
}

//end

foreach($_FILES[$f_input]["error"] as $key => $error){ 
	$up_error="no"; 
	if ($error == UPLOAD_ERR_OK){ 
		$f_name=$_FILES[$f_input]['name'][$key];//获取上传源文件名 
		if(preg_match('/^.*$/u', $f_name) > 0){
			$f_name = Import::gz_iconv()->ec_iconv('UTF8', 'GB2312', $f_name);  //将编码转为GB2312
		}
		$uploadfile=strtolower(basename($f_name)); 
		$tmp_type=substr(strrchr($f_name,"."),1);//获取文件扩展名
		$tmp_type=strtolower($tmp_type); 

		if(!stristr($f_type,$tmp_type)){ 
			echo "<script>alert('对不起,不能上传".$tmp_type."格式文件, ".$f_name." 文件上传失败!')</script>"; 
			$up_error="yes"; 
		} 
		 
		if ($_FILES[$f_input]['size'][$key]>$file_size_max) { 
		
			echo "<script>alert('对不起,你上传的文件 ".$f_name." 容量为".round($_FILES[$f_input]
['size'][$key]/1024)."Kb,大于规定的".($file_size_max/1024)."Kb,上传失败!')</script>"; 
			$up_error="yes"; 
		} 
		 
		if (file_exists($uploadfile)&&!$overwrite){ 
			echo "<script>alert('对不起,文件 ".$f_name." 已经存在,上传失败!')</script>"; 
			$up_error="yes"; 
		} 
		
		//给上传的文件命名
		/*$string = 'abcdefghijklmnopgrstuvwxyz0123456789';
		$rand = '';
		for ($x=0;$x<12;$x++)
		  $rand .= substr($string,mt_rand(0,strlen($string)-1),1);
		  
		  $t=date("ymdHis").substr($gettime[0],2,6).$rand;*/
			if(!is_dir($attdir))   
			{  mkdir($attdir);}
					//$uploadfile=$attdir.$t.".".$tmp_type; 
					//$uploadfile = $attdir.iconv("UTF-8","GB2312",$f_name ); 
					$uploadfile = $attdir.$f_name;
					if(is_file($uploadfile)&&file_exists($uploadfile)) $fop->delete_file($uploadfile);
					$fop->checkDir($uploadfile);
				
					if(($up_error!="yes") && (move_uploaded_file($_FILES[$f_input]['tmp_name'][$key], $uploadfile))){ 
						//$_msg=$_msg.$f_name.'上传成功\n';
						$_msg = "";
						//如果是zip rar文件的就解压 通过系统doc命令解压
						if($tmp_type=='rar' || $tmp_type=='zip'){
							if(is_file($uploadfile)&&file_exists($uploadfile)){
								$obj=new com("wscript.shell");
								$winrar="winrar x ".$attdir.$f_name."  ".SYS_PATH_PHOTOS.'\\temp'.DS.(empty($_SESSION['adminname'])?'\\admin':$_SESSION['adminname']);
								$obj->run($winrar,1,true);
								$fop->delete_file($uploadfile);
							}
							/*Import::zip()->unZip($attdir.$f_name,$attdir);
							if(is_file($uploadfile)&&file_exists($uploadfile)) $fop->delete_file($uploadfile);*/
						}
					} 
					else{
						$_msg=$_msg.$f_name.'上传失败\n';
					}
			} 
			
			//$ac = explode('.',$f_name);
			//$rts[$t] = $ac[0];
			//unset($ac);
	} //foreach
	
	//$fn = SYS_PATH.'cache/photoscache.php';
	//$cache = Import::ajincache();
	//$cache->write($fn, $rts,'rts');
	
	echo "<script>window.parent.Finish('".$_msg."');</script>";	
?>

<?php
require_once("../load.php");
$yuming = str_replace(array('www','.',),'',$_SERVER["HTTP_HOST"]);
if(!empty($yuming)) $yuming = $yuming.'/';
/*
*检查路径，不存在则创建
*/
function checkDir($fn){ 
	if(strstr($fn,'.')===false){
		$fn = dirname($fn);
	}
	$fn = str_replace('/',DS,$fn);
	$pathArr = pathinfo($fn);
	if(is_dir($pathArr['dirname'])) return true;
	$dirArr = explode(DS, $pathArr['dirname']); 
	unset($pathArr);
	$dir = null;
	foreach($dirArr as $folder){
		$dir .= $folder.DS;
		if(!@is_dir($dir))@mkdir($dir,0777);
	}
	unset($dirArr);
}
	 
$action = $_GET['act'];
if($action=='delimg'){
	$filename = $_POST['imagename'];
	if(!empty($filename)){
		unlink(SYS_PATH.$filename);
		echo '1';
	}else{
		echo '删除失败.';
	}
}else{
	$picname = $_FILES['mypic']['name'];
	$picsize = $_FILES['mypic']['size'];
	if ($picname != "") {
		if ($picsize > 1024000) {
			//echo '图片大小不能超过1M';
			$arr = array(
				'name'=>'图片大小不能超过1M',
				'pic'=>'',
				'size'=>'',
				'error'=>'1'
			);
			echo json_encode($arr);
			exit;
		}
		$type = strstr($picname, '.');
		if ($type != ".gif" && $type != ".jpg" && $type != ".png") {
			//echo '图片格式不对！';
			$arr = array(
				'name'=>'图片格式不对',
				'pic'=>'',
				'size'=>'',
				'error'=>'1'
			);
			echo json_encode($arr);
			exit;
		}
		$rand = rand(100, 999);
		$pics = date("YmdHis") . $rand . $type;
		//上传路径
		$pic_path = SYS_PATH.'photos/'.$yuming.'files/'.date('Ym').'/'.date('d').'/'. $pics;
		checkDir($pic_path);
		if(move_uploaded_file($_FILES['mypic']['tmp_name'], $pic_path)){
			//生成缩略图
			
		}
	}
	$size = round($picsize/1024,2);
	$arr = array(
		'name'=>$picname,
		'pic'=>'photos/'.$yuming.'files/'.date('Ym').'/'.date('d').'/'.$pics,
		'size'=>$size,
		'error'=>'0'
	);
	echo json_encode($arr);
}
?>
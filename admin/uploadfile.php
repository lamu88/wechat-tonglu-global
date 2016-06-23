<?php
define('LOGIN',1);
require_once('load.php');
$yuming = str_replace(array('www','.',),'',$_SERVER["HTTP_HOST"]);
if(!empty($yuming)) $yuming = $yuming.'/';
@set_time_limit(400); //最大运行时间
if(!defined('IS_TRUE')) die('无法访问');
$action			= isset($_REQUEST['action']) ? trim($_REQUEST['action']) : "";
$GLOBALS['picdir'] = SYS_PATH_PHOTOS.$yuming.(isset($_REQUEST['tyy'])&&!empty($_REQUEST['tyy'])? trim($_REQUEST['tyy']) : trim($_REQUEST['ty'])).'/'.date('Ym',mktime()).'/'; 

if(isset($_POST)&&!empty($_POST)){
	$name = $app->action('upload','uploadfile','sf_upfile',$GLOBALS['picdir'],$_REQUEST);
	if(!empty($name)) $action	= "show";
	$GLOBALS['picurl'] = 'photos/'.$yuming.(isset($_REQUEST['tyy'])&&!empty($_REQUEST['tyy'])? trim($_REQUEST['tyy']) : trim($_REQUEST['ty'])).'/'.date('Ym',mktime()).'/'.$name;
}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language="javascript" type="text/javascript">
var right_type=new Array(".swf",".xls",".doc",'.pdf','rar','zip','ppt')
function checkImgType(fileURL){
	var right_typeLen=right_type.length;
	var imgUrl=fileURL.toLowerCase();
	var postfixLen=imgUrl.length;
	var len4=imgUrl.substring(postfixLen-4,postfixLen);
	var len5=imgUrl.substring(postfixLen-5,postfixLen);
	for (i=0;i<right_typeLen;i++){
		if((len4==right_type[i])||(len5==right_type[i])){
			return true;
		}
	}
}
function Dom(ID){
	var Dom=document.getElementById(ID);
	return Dom;
}
function IsUpLoad(){
	if(Dom("sf_upfile").value==""){
		alert("请先用浏览按钮选择您要上传的文件，然后点浏览按钮傍边的上传按钮上传。");
		return false;
	}
	if(checkImgType(Dom("sf_upfile").value)){
	}else{
		alert("文件格式不正确,仅swf格式的图! ")
		return false;
	}
	Dom("UpBox").style.display="none";
	Dom("LoadingBar").style.display="block";
}
function del(ty){
	parent.document.getElementById(ty).value='';
}
</script>
<style type="text/css">
body {font-size: 9pt;margin: 0px;padding: 0px;background-color:#fff;}
.bj {padding-top: 3px;padding-bottom: 3px;}
form {margin: 0px;padding: 0px;}
.border {border: 1px solid #2B2B2B;font-size: 9pt;width:250px;}
.button{background:none repeat scroll 0 0 #4e6a81;
border-color:#dddddd #000000 #000000 #dddddd;
border-style:solid;
border-width:2px;
color:#FFFFFF;cursor:pointer;letter-spacing:0.1em;overflow:visible;padding:3px 15px;width:auto;cursor:pointer;text-decoration:none;}
</style>
</head>
<body >
<?php
switch($action){
	case "show";
	if(!empty($_REQUEST['files'])) $GLOBALS['picurl'] = $_REQUEST['files'];
	show();
	break;

	case "del";
	$GLOBALS['picurl'] = $_REQUEST['files'];
	del();
	break;
	
	case "newload";
	$GLOBALS['picurl'] = $_REQUEST['files'];
	del();
	break;
	
	default;
	upload();
	break;
}
function show(){
	if(!file_exists(SYS_PATH.$GLOBALS['picurl'])){
		echo("<script language='javascript'>window.location.href='uploadfile.php?action=&ty=".$_REQUEST['ty']."&tyy=".(isset($_REQUEST['tyy'])&&!empty($_REQUEST['tyy'])? trim($_REQUEST['tyy']) : trim($_REQUEST['ty']))."';</script>");
		exit;
	}
	echo '<script> parent.document.getElementById("'.trim($_REQUEST['ty']).'").value="'.$GLOBALS['picurl'].'"; parent.document.getElementById("'.trim($_REQUEST['ty']).'").style.display="none"; </script>';
	echo("上传成功，[<a href='".SITE_URL.'/'.$GLOBALS['picurl']."' title='点击预览上传的文件' target='_blank'>预览</a>]，[<a href='uploadfile.php?action=newload&ty=".$_REQUEST['ty']."&tyy=".(isset($_REQUEST['tyy'])&&!empty($_REQUEST['tyy'])? trim($_REQUEST['tyy']) : trim($_REQUEST['ty']))."&files=".$GLOBALS['picurl']."' onclick='return(confirm(\"确定要重新上传文件吗？\"))'>重新上传</a>]，[<a href='uploadfile.php?action=del&ty=".$_REQUEST['ty']."&tyy=".(isset($_REQUEST['tyy'])&&!empty($_REQUEST['tyy'])? trim($_REQUEST['tyy']) : trim($_REQUEST['ty']))."&files=".$GLOBALS['picurl']."' onclick='return del(\"".(isset($_REQUEST['tyy'])&&!empty($_REQUEST['tyy'])? trim($_REQUEST['tyy']) : trim($_REQUEST['ty']))."\");'>删除</a>]");
}
function del(){
	Import::fileop()->delete_file(SYS_PATH.$GLOBALS['picurl']);
	$q = dirname($GLOBALS['picurl']);
	$h = basename($GLOBALS['picurl']);
	Import::fileop()->delete_file(SYS_PATH.$q.DS.'thumb_s'.DS.$h);
	Import::fileop()->delete_file(SYS_PATH.$q.DS.'thumb_b'.DS.$h);
	echo("<script language='javascript'> parent.document.getElementById('".trim($_REQUEST['ty'])."').value=''; window.location.href='uploadfile.php?action=&ty=".$_REQUEST['ty']."&tyy=".(isset($_REQUEST['tyy'])&&!empty($_REQUEST['tyy'])? trim($_REQUEST['tyy']) : trim($_REQUEST['ty']))."';</script>");
}
?>
<?php
function upload(){
?>
<div id="UpBox" style="display:block">
<form action="" method="post" enctype="multipart/form-data" name="form1" id="form1" onSubmit="return IsUpLoad();">
<table border="0" cellspacing="0" cellpadding="0">
  <tr>
	<td width="300"><input name="sf_upfile" type="file"  class="border" id="sf_upfile" style="width:280px"/></td>
    <td>
	<input type="submit" name="InputUpload" id="InputUpload" value="上 传" style="" />&nbsp;<span style="color:#FF0000">*</span>
	 <input name="ty"  type="hidden" value="<?php echo $_REQUEST['ty'];?>" />
	 <input name="tyy"  type="hidden" value="<?php echo isset($_REQUEST['tyy'])?$_REQUEST['tyy'] : "";?>" /> <!--存放图片的路劲-->
    </td>
  </tr>
</table>
</form>
</div>
<div id="LoadingBar" style="display:none" class="bj"><img src="./images/loading.gif" alt="文件上传中，请稍后..." width="220" height="19" border="0" /></div>
<?php
}		
?>
</body>
</html>
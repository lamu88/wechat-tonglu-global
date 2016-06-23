<?php require_once('load.php');
/*用户权限管理start*/
$permi_var = $app->action('manager','admin_Permissions');
$Permissions = !empty($permi_var) ? @explode("+",$permi_var) : array();
if(file_exists(SYS_PATH_ADMIN.'inc'.DS.'menulist.php'))
	require_once(SYS_PATH_ADMIN.'inc'.DS.'menulist.php');
if(file_exists(SYS_PATH_ADMIN.'inc'.DS.'admingroup.php'))
	require_once(SYS_PATH_ADMIN.'inc'.DS.'admingroup.php');
$permis_key = !empty($groupname_arr)&&is_array($groupname_arr) ? array_keys($groupname_arr) : array("","","","","","","","");
if(!empty($menu)){
	$menu_ar = array();
	foreach($menu as $row){
		if(!in_array($row['big_mod'],$menu_ar))  $menu_ar[] = $row['big_mod']; //过滤重复菜单名称
	}
}else{
	die("请先定义菜单列表！");
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="css/common.css" type="text/css" />
<title>后台管理系统</title>
</head>
<script type="text/javascript" src="<?php echo ADMIN_URL;?>js/jquery1.6.js"></script>
<script  type="text/javascript">
var preClassName = "man_nav_1";
function list_sub_nav(Id,sortname){
   if(preClassName != ""){
      getObject(preClassName).className="bg_image";
   }
   if(getObject(Id).className == "bg_image"){
      getObject(Id).className="bg_image_onclick";
      preClassName = Id;
	  showInnerText(Id);
	  window.top.frames['leftFrame'].outlookbar.getbytitle(sortname);
	  window.top.frames['leftFrame'].outlookbar.getdefaultnav(sortname);
   }
}

function showInnerText(Id){
    var switchId = parseInt(Id.substring(8));
	var showText = "对不起没有信息！";
	switch(switchId){
	    case 1:
		   showText =  "微信分销管理系统V5.1.3!";
		   break;
		 <?php 
		 foreach($menu_ar as $k=>$var){ 
		 $k++;
		 if($k==1) continue; 
		 ?>
	    case <?php echo $k;?>:
		   showText =  "<?php echo $var;?>!";
		   break;
		  <?php } ?>	
		//case <?php echo ++$k;?>:
		   //showText =  "定制网站!";
		  // break;   		   
	}
	getObject('show_text').innerHTML = showText;
}
 //获取对象属性兼容方法
 function getObject(objectId) {
    if(document.getElementById && document.getElementById(objectId)) {
	// W3C DOM
	return document.getElementById(objectId);
    } else if (document.all && document.all(objectId)) {
	// MSIE 4 DOM
	return document.all(objectId);
    } else if (document.layers && document.layers[objectId]) {
	// NN 4 DOM.. note: this won't find nested layers
	return document.layers[objectId];
    } else {
	return false;
    }
}
</script>
<style>
#nav ul li{
float:left; width:87px; height:88px; margin-left:10px; display:inline; padding:0px; text-align:center;}
.bg_image_onclick{
background-image:url(images/navbg.png);}
#nav ul li a{
float:left; margin:0px; padding:33px 0px 0px 0px; width:87px; text-align:center; color:#fff; display:block; height:55px; background-position:center; background-repeat:no-repeat; font-weight:normal}
#nav ul li#man_nav_1 a{
background-image:url(images/icon01.png); background-position:center 10px; background-repeat:no-repeat;}
#nav ul li#man_nav_2 a{
background-image:url(images/icon02.png); background-position:center 10px; background-repeat:no-repeat;}
#nav ul li#man_nav_3 a{
background-image:url(images/icon03.png); background-position:center 10px; background-repeat:no-repeat;}
#nav ul li#man_nav_4 a{
background-image:url(images/icon04.png); background-position:center 10px; background-repeat:no-repeat;}
#nav ul li#man_nav_5 a{
background-image:url(images/icon05.png); background-position:center 10px; background-repeat:no-repeat;}
#nav ul li#man_nav_6 a{
background-image:url(images/icon06.png); background-position:center 10px; background-repeat:no-repeat;}
#nav ul li#man_nav_7 a{
background-image:url(images/icon07.png); background-position:center 10px; background-repeat:no-repeat;}
#nav ul li#man_nav_8 a{
background-image:url(images/icon08.png); background-position:center 10px; background-repeat:no-repeat;}
</style>
<body>
<div class="header_content" style="position:fixed; background:none; border:none; height:88px; background-image:url(images/topbg.gif); background-position:top; background-repeat:repeat-x;">
	 <div class="right_nav" style="padding-left:0px; height:88px; background:url(images/topleft.jpg) left center no-repeat">
	 	<img src="images/logo.png" style="float:left; margin-left:10px; display:inline; margin-top:20px;" />
	    <div id="nav" style="height:88px;">
			<ul style="float:left; height:88px;">
			<?php 
				foreach($menu_ar as $k=>$var){ 
			?>
			<li <?php
			if(!in_array($permis_key[$k],$Permissions)){ echo 'style="display:none"';} ?> id="man_nav_<?php echo ++$k;?>" onclick="list_sub_nav(id,'<?php echo $var;?>')"  class="<?php echo ($k==1) ? 'bg_image_onclick' : 'bg_image';?>"><a href="javascript:void(0);"><?php echo $var;?></a></li>
			<?php } ?>
			<div style="clear:both"></div>
			</ul>
		</div>
	 </div>
	 <div class="text_right" style="width:230px; padding-right:5px; position:absolute; right:0px; top:22px;">
	   <p style="line-height:18px; height:18px; padding:0px; margin:0px; color:#FFF; padding-left:18px">欢迎您，<strong style="color:#FF0000"><?php echo $_SESSION['adminname'];?></strong></p>
	   <ul class="nav_return"><li><img src="images/return.gif" width="13" height="21" />&nbsp;[ <a href="../m/" target="_blank">前台首页</a> ]&nbsp;[ <a href="./" target="_parent">后台首页</a> ]&nbsp;[ <a href="logout.php" onclick="return confirm('确认退出吗')">退出</a> ]</li>
		</ul>
	 </div>
</div>
</body>
</html>

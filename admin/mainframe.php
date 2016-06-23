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
<title>管理导航区域</title>
</head>
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
		   showText =  "商城后台管理系统!";
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
<body>
<div id="nav">
    <ul>
	<?php 
		foreach($menu_ar as $k=>$var){ 
	?>
    <li <?php
    if(!in_array($permis_key[$k],$Permissions)){ echo 'style="display:none"';} ?> id="man_nav_<?php echo ++$k;?>" onclick="list_sub_nav(id,'<?php echo $var;?>')"  class="<?php echo ($k==1) ? 'bg_image_onclick' : 'bg_image';?>"><?php echo $var;?></li>
    <?php } ?>
	<!--<li id="man_nav_<?php echo ++$k;?>" onclick="list_sub_nav(id,'定制网站')"  class="bg_image">定制网站</li>-->
	</ul>
</div>
<div id="sub_info">&nbsp;&nbsp;<img src="images/hi.gif" />&nbsp;<span id="show_text"></span></div>
</body>
</html>

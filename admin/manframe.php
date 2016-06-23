<?php 
require_once('load.php');
//
$rt = $app->action('system','getcount');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="css/common.css" type="text/css" />
<title>管理区域</title>
</head>

<body>
<div id="man_zone">
  <table cellspacing="1" cellpadding="5" width="100%">
<tr>
	<th colspan="4" style="font-size:16px; line-height:50px">烟台通路网络 - 通路微信分销系统</th>
</tr>
<tr>
	<th>网址：</th>
	<td width="30%"><a href="http://www.gope.cn" target="_blank"><font color="#FF0000">www.gope.cn</font></a></td>
	<th>售后专属客服QQ</th>
	<td><font color="#FF0000">75943938</font></td>
</tr>
<tr>
<tr>
<th width="15%">订单数量：</th>
<td><font color="#FF0000"><?php echo $rt['order']['zcount'];?></font>个</td>
<th width="15%">成功订单数量：</th>
<td><font color="#FF0000"><?php echo $rt['order']['yescount'];?></font>个</td>
</tr>
<tr>
<th width="15%">使用的操作系统：</th>
<td><?php echo $rt['os'];?></td>
<th width="15%">当前的浏览器：</th>
<td><?php echo $rt['browser'];?></td>
</tr>
<tr>
<th width="15%">当前访问ip地址：</th>
<td><?php echo $rt['bsip'].'  [<font color=red>'.$rt['ip_from'].'</font>]';?></td>
<th width="15%">服务器IP地址：</th>
<td><?php echo $rt['csip'];?></td>
</tr>
</table>

</div>

<script type="text/javascript" src="<?php echo ADMIN_URL;?>js/jquery1.6.js"></script>
<script language="javascript" type="text/javascript">
//根据屏幕的高度决定左侧菜单的高度
changesize();
function changesize(){ 	
	// 获取窗口高度 
	if (window.innerHeight) 
	winHeight = window.innerHeight; 
	else if ((document.body) && (document.body.clientHeight)) 
	winHeight = document.body.clientHeight; 
	// 通过深入 Document 内部对 body 进行检测，获取窗口大小 
	if (document.documentElement && document.documentElement.clientHeight && document.documentElement.clientWidth) 
	{ 
	winHeight = document.documentElement.clientHeight; 
	} 
	}
	document.getElementById('man_zone').style.height=(winHeight-10)+"px";
</script>
</body>
</html>

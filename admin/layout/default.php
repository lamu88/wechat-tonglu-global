<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php //echo $this->title();?>通路网络微信分销后台管理系统V5.1.3</title>
<?php echo $this->meta();?>
<?php echo $this->css(array('style.css','order_remind.css'));?>
<?php echo $this->js(array('jquery1.6.js','common.js','jquery.cookie.js'));?>
<script language="javascript">ADMIN_URL = '<?php echo ADMIN_URL;?>';</script>
</head>
<body>
<div id="mainss" class="main" style="height:auto; padding-top:1px;">
	<?php echo $this->content();?>
</div>
<div style="clear:both"></div>
<div id="msg_win" style="display:none;top:490px;visibility:visible;opacity:1;">
	<div class="icos"><a id="msg_min" title="最小化" href="javascript:void 0">_</a><a id="msg_close" title="关闭" href="javascript:void 0">×</a></div>
	<div id="msg_title">提醒系统</div>
	<div id="msg_content"><a href="<?php echo ADMIN_URL;?>goods_order.php?type=list">你有新订单(*^__^*)<br />马上查看</a></div>
</div>
<div class="bgsound"></div>
<script type="text/javascript" src="<?php echo ADMIN_URL;?>js/order_remind.js"></script> 

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
	document.getElementById('mainss').style.height=(winHeight-10)+"px";
</script>
</body>
</html>

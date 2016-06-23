<!doctype html>
<html>
<head>
<!-- 
*********************************技术支持：狗扑源码社区*********************************
*********************************客服QQ：75943938    企业QQ：800014291*********************************
-->
<meta content="text/html; charset=utf-8" http-equiv="Content-Type" /><meta charset="utf-8" />

<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport" />
<meta content="yes" name="apple-mobile-web-app-capable" />
<meta content="black" name="apple-mobile-web-app-status-bar-style" />
<meta name="format-detection" content="telephone=no"/>
<title><?php echo $this->title();?></title><?php echo $this->meta();?>
<?php echo $this->css(array('comman.css','jquery_dialog.css','style.css?v=v21'));?>
<?php echo '<script> var SITE_URL="'.ADMIN_URL.'";</script>'."\n";?>
<?php echo $this->js(array('jquery.min.js','jquery_dialog.js','common.js?v=21'));?>
<script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script> 
</head>
<body>
<p id="back-to-top"><a href="#top"><img src="/m/images/top2.png" width="70px" height="70px" /></a></p>
<?php echo $this->content();?>
<?php if(!empty($lang['custome_email'])&& strpos($lang['custome_email'],'kuaishang')){?>
<div style="height:40px; width:30px; position:fixed; left:5px; bottom:110px;border-radius:5px; z-index:999; line-height:16px; text-align:center; font-size:12px; color:#FFF; filter:alpha(opacity=90); -moz-opacity:0.9; -khtml-opacity:0.9;opacity:0.9;background:url(<?php echo $this->img('sdd.png');?>) center 3px no-repeat #e7e7e7; padding-top:35px; border:1px solid #ccc; z-index:9999999">
<a href="<?php echo $lang['custome_email'];?>" style="cursor:pointer; color:blue; display:block;">客<br>服</a>
</div>
<?php } ?>
<script type="text/javascript">
$(document).ready(function(){
//首先将#back-to-top隐藏
 $("#back-to-top").hide();
//当滚动条的位置处于距顶部100像素以下时，跳转链接出现，否则消失
$(function () {
$(window).scroll(function(){
	if ($(window).scrollTop()>100){
		$("#back-to-top").fadeIn(1500);
	}
	else
	{
		$("#back-to-top").fadeOut(1500);
	}
});
//当点击跳转链接后，回到页面顶部位置
$("#back-to-top").click(function(){
	$('body,html').animate({scrollTop:0},1000);
	return false;
});
});
});
</script>
</body>
</html>
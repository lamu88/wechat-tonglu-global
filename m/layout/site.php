<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php echo $this->title();?></title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,height=device-height,inital-scale=1.0,maximum-scale=1.0,user-scalable=no;">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta name="format-detection" content="telephone=no">
<?php echo $this->meta();?>
<?php echo $this->css(array('comman.css'));?>
<?php echo '<script> var SITE_URL="'.ADMIN_URL.'";</script>'."\n";?>
<?php echo $this->js(array('jquery.min.js'));?>
<script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script> 
</head>
<body>
<?php echo $this->content();?>
</body>
</html>

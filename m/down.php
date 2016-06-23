<?php
$url = $_GET['url'];
$pwd = $_GET['pwd'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport" />
<title>下载页面</title>

</head>

<body style="line-height:25px;text-align:center">
<?php
	if($pwd){
?>
	<div>
    	<h1>微信多开下载</h1>
        <div><b>密 码：</b>&nbsp;&nbsp;<span style="color:#F00; font-size:18px"><?php echo $pwd; ?></span><Br />(长按复制，然后在右上角选择在浏览器中打开)</div><br />
        <a href="<?php echo $url; ?>" style="padding:5px; background:#DB383E; color:#FFF">点击跳转下载</a>
    </div>
<?php
	}else{
		$url = "Location:".$url;
		header($url);
	}
?>
<br /><br /><br /><br />

</body>
</html>
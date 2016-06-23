<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Page-Enter" content="blendTrans(Duration=0.5)" /> 
<meta http-equiv="Page-Exit" content="blendTrans(Duration=0.5)" />
<title><?php echo $this->title();?></title><?php echo $this->meta();?>
<?php echo $this->css(array('comman.css','jquery_dialog.css','style.css'));?>
<!--[if IE 6]><link type="text/css" rel="stylesheet" href="<?php echo SITE_URL;?>css/ie6hover.css" media="all" /><![endif]-->
<?php echo '<script> var SITE_URL="'.SITE_URL.'";</script>'."\n";?>
<?php echo $this->js(array('jquery.min.js','jquery_dialog.js','common.js'));?>
<!--[if IE 6]><script language="javascript" src="<?php echo SITE_URL;?>js/iepngfix_tilebg.js"></script><![endif]-->
</head>
<body>
	<?php $this->element('top',array('lang'=>$lang,'keyword'=>$keyword)); ?>
	<div class="main">
		<div class="mainbox2">
				<?php echo $this->content();?>
		</div>
		
	</div>
	<div class="mainbot"></div>
	<?php $this->element('footer',array('lang'=>$lang)); ?>
</body>
</html>

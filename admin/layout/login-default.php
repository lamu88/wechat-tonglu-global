<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>通路微信分销系统高级版</title>
<?php echo $this->meta();?>
<link href="<?php echo ADMIN_URL ?>css/style2.css" rel="stylesheet" type="text/css" />
<script language="JavaScript" src="<?php echo ADMIN_URL ?>js/jquery1.6.js"></script>
<script src="<?php echo ADMIN_URL ?>js/cloud.js" type="text/javascript"></script>

<script language="javascript">
	$(function(){
    $('.loginbox').css({'position':'absolute','left':($(window).width()-692)/2});
	$(window).resize(function(){  
    $('.loginbox').css({'position':'absolute','left':($(window).width()-692)/2});
    })  
});  
</script> 
<style>
html{
overflow:hidden}
</style>
</head>

<body style="background-color:#1c77ac; background-image:url(<?php echo ADMIN_URL ?>images/light.png); background-repeat:no-repeat; background-position:center top; overflow:hidden;">
 <form id="form1" name="form1" method="post" action="" style="background:url(<?php echo $this->img('ybg.png');?>) center center no-repeat">


    <div id="mainBody">
      <div id="cloud1" class="cloud"></div>
      <div id="cloud2" class="cloud"></div>
    </div>  


<div class="logintop" style="position:fixed">    
    <span>欢迎使用通路微信分销系统高级版</span>    
    <ul>
    <li><a href="<?php echo SITE_URL;?>m/">回首页</a></li>
   
    </ul>    
    </div>
    
    <div class="loginbody">
    
    <span class="systemlogo"></span> 
       
    <div class="loginbox">
    
    <ul style="margin-top:75px; float:left  ">
    <li style="margin-bottom:15px;"><span>用户名：</span><input name="adminname" type="text" class="loginuser uname" value="" onclick="JavaScript:this.value=''"/></li>
    <li style="margin-bottom:15px;"><span>密　码：</span><input name="password" type="password" id="password" class="loginpwd pass"   value=""  /></li>
	 
    <li style="margin-bottom:15px;"><span>验证码：</span><input type="text" value="" onclick="JavaScript:this.value=''" name="vifcode"  size="30" class="vifcode loginpwd"  style="width:138px; float:left; background-image:url(<?php echo ADMIN_URL ?>images/vp.png);"/><img    src="<?php echo ADMIN_URL;?>captcha.php" onclick="this.src='<?php echo ADMIN_URL;?>captcha.php?'+Math.random()" align="absmiddle" style=" margin-left:5px; float:left; height:40px; margin-top:3px;"/></li>
	<li>
	<input name="sub" type="button" class="loginbtn login_button" value="登录" style="float:left; margin-left:60px; display:inline"/><span style=" color:#FF0000; padding-left:10px;" class="error_msg"></span>
	</li>
    </ul>
    
    
    </div>
    
    </div>
    
    
    
    <div class="loginbm" style="left:0; position:fixed">版权所有 <?php echo date('Y',time());?>　<?php echo $this->title();?> </div>
	</form>
  <?php  $thisurl = ADMIN_URL.'login.php'; ?>
<script type="text/javascript">
$('.login_button').click(function(){
	submit_data(this);
});
	
//回车键提交
document.onkeypress=function(e)
{
	　　var code;
	　　if  (!e)
	　　{
	　　		var e=window.event;
	　　}
	　　if(e.keyCode)
	　　{
	　　		code=e.keyCode;
	　　}
	　　else if(e.which)
	　　{
	　　		code   =   e.which;
	　　}
	　　if(code==13) //回车键
	　　{
			submit_data();
	　　}
}

function submit_data(obj){
	 
		name = $('.uname').val(); 
        pas = $('.pass').val();
		vifcodes = $('.vifcode').val();
		if(name == "" || pas == "" || vifcodes == ""){ $('.error_msg').html("输入完整信息！"); return false;}
		$(obj).val("Login...");
		$.post('<?php echo $thisurl;?>',{action:'login',adminname:name,password:pas,vifcode:vifcodes},function(data){ 
			 
			if(data != ""){
				alert(data);
				$('.error_msg').html(data);
			}else{
			 	location.href='<?php echo ADMIN_URL;?>';
				return;
			}
			$(obj).val("登陆");
		});
}	

</script>  
</body>

</html>












 
<?php require_once('../load.php');
$userinfo = $app->action('user','get_user_session_info');
$rank = $userinfo['rank'];

if(!($rank>0)){
	
	 //exit;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>comment</title>
<link type="text/css" rel="stylesheet" href="<?php echo str_replace('pop/','',SITE_URL);?>css/jquery_dialog.css" media="all" />
<script>var keyword=""; var SITE_URL="<?php echo str_replace('pop/','',SITE_URL);?>";</script>
<script type="text/javascript" src="<?php echo str_replace('pop/','',SITE_URL);?>js/jquery.min.js"></script> 
<script type="text/javascript" src="<?php echo str_replace('pop/','',SITE_URL);?>js/jquery.cookie.js"></script> 
<script type="text/javascript" src="<?php echo str_replace('pop/','',SITE_URL);?>js/jquery.json-1.3.js"></script> 
<script type="text/javascript" src="<?php echo str_replace('pop/','',SITE_URL);?>js/jquery_dialog.js"></script> 
<script type="text/javascript" src="<?php echo str_replace('pop/','',SITE_URL);?>js/common.js"></script> 
<script type="text/javascript" src="<?php echo str_replace('pop/','',SITE_URL);?>js/user.js"></script> 
<script type="text/javascript" src="<?php echo str_replace('pop/','',SITE_URL);?>js/goods.js"></script> 
<style type="text/css">
.comment_con table img{ cursor:pointer}
.relo a{ color:#333; text-decoration:none}
.relo a:hover{ color:#AD3231; text-decoration:underline}
.login_con input.loginbut{cursor:pointer} 
.comment_con{ font-family:"新宋体"; font-size:14px; width:390px; margin:0px auto; background-color:#FFF; padding-top:20px}
</style>
</head>

<body>
<form action="" method="post"  name="ORDEROP" id="ORDEROP">
    <div class="comment_con">
		<table width="100%" border="0" cellspacing="5" cellpadding="1" style="line-height:18px">
			<tr>
			<td align="center">
				亲爱的<b><?php echo $userinfo['username'];?>,</b>非常感谢你成为我们的新会员！
			  </td>
			</tr>
<!--			<tr>
			<td align="center" colspan="2">
			 你的会员级别是：<b><?php if($rank==1){echo '个人会员';}else if($rank==10){ echo '供应商';}else if($rank==11){ echo '企业会员';}else if($rank==12){echo '零售店';}?></b>;
			</td>
			</tr>-->
			<tr>
			<td align="center">
			 你的会员登陆账号是：<b style="font-size:32px;"><?php echo $userinfo['email'];?></b>,请牢记！
			</td>
			</tr>

			<tr>
				<td align="center">
				<a style="display:block; height:25px; width:110px; background-color:#222;text-decoration:none; text-align:center; line-height:25px; color:#FFF" href="<?php echo str_replace('pop/','',SITE_URL);?>user.php" target="_parent">立刻入用户后台</a>
				</td>
			</tr>
		</table>
    </div>
</form>
<script language="javascript" type="text/javascript">
function ajax_option_order(){
	var formObj      = document.forms['ORDEROP']; //表单
	var mesobj        = new Object();
	if(formObj){
		mesobj = getFormAttrs(formObj);
	}else{
		alert('不存在留言表单对象！');	
		return false;
	}
	
	$.ajax({
	   type: "POST",
	   url: SITE_URL+"ajaxfile/suppliers.php",
	   data: "action=orderop&message=" + $.toJSON(mesobj),
	   dataType: "json",
	   success: function(data){
			if(data.error==0){
				window.parent.setbutton(data.message,data.orderid);
				window.parent.JqueryDialog.Close();
			}else{
				alert(data.message);
			}
			
	   } //end sucdess
	}); //end ajax
}

var t = setInterval("changvar(30)", 1000);
function changvar(i){
		dd = $('.changevar').html();
		dd = parseInt(dd)-1;
		if(dd==0){
			 clearInterval(t);
		}
		$('.changevar').html(dd);
}
</script>
</body>
</html>

<?php require_once('../load.php');
$rt = $app->action('suppliers','_get_order',$_GET['oid']);
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
.comment_con{ font-family:Verdana, Geneva, sans-serif;}
body{ font-size:12px;}
</style>
</head>

<body>
<form action="" method="post" style="width:375px" name="ORDEROP" id="ORDEROP">
    <div class="comment_con">
		<table width="100%" border="0" cellspacing="5" cellpadding="1" style="line-height:18px">
			<tr>
			<td align="center" colspan="2">
				<span style="height:20px; line-height:20px; text-align:left; display:block">订单备注：</span>
				<textarea name="desc" style="width:368px; height:100px; border:1px solid #ccc;"></textarea>			
			  </td>
			</tr>
			<tr>
			<td align="left" colspan="2">
			  邮件提醒:<label><input type="radio" name="is_send_eamil" value="1" checked="checked"/>提醒</label>
			  <label><input type="radio" name="is_send_eamil" value="0" />不提醒</label>
			</td>
			</tr>
			<tr>
			<td>收货客户：<?php echo isset($rt['consignee']) ? $rt['consignee'] : '';?></td>
			<td>发送邮箱：<?php echo isset($rt['email']) ? $rt['email'] : '';?></td>
			</tr>
			<tr>
				<td colspan="2">
					<input type="hidden" name="orderid" value="<?php echo $_GET['oid'];?>" /><input type="hidden" name="status" value="<?php echo $_GET['status'];?>" />
				  <input type="button" value="确认<?php echo $_GET['val'];?>"  style="cursor:pointer" onclick="ajax_option_order()"/><span style="padding-left:10px;" class="mes"></span>
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
				window.parent.setbutton(data.message,data.orderid,data.status);
				window.parent.JqueryDialog.Close();
			}else{
				alert(data.message);
			}
			
	   } //end sucdess
	}); //end ajax
}
</script>
</body>
</html>

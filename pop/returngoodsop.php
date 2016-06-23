<?php require_once('../load.php');
$info = $app->action('store','return_status',$_GET['oid'],$_GET['gid']);
$_GET['status'] =$info['status']; 
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
				<span style="height:20px; line-height:20px; text-align:left; display:block">退换货说明：</span>
				<textarea name="desc" style="width:368px; height:100px; border:1px solid #ccc;"><?php echo $info['remark'];?></textarea>			
			  </td>
			</tr>
			<tr>
				<td colspan="2">在退货/换货之前，请你务必想清楚，如果有任何问题，请联系我们的在线客服！</td>
			</tr>
			<tr>
				<td colspan="2">申请时间：<?php echo isset($info['addtime'])&&!empty($info['addtime']) ? date('Y-m-d H:i:s',$info['addtime']) : date('Y-m-d H:i:s',mktime());?></td>
			</tr>
			<tr>
				<td colspan="2">
				  	<input type="button" value="退货"  style="cursor:pointer" id="1" onclick="ajax_option_order('<?php echo $_GET['oid'];?>','<?php echo $_GET['gid'];?>',this)" class="option_order1"/>
					<input type="button" value="换货"  style="cursor:pointer" id="2" onclick="ajax_option_order('<?php echo $_GET['oid'];?>','<?php echo $_GET['gid'];?>',this)" class="option_order2"/>
					<span style="padding-left:10px;" class="mes"></span>
				</td>
			</tr>
		</table>
    </div>
</form>
<script language="javascript" type="text/javascript">

function ajax_option_order(oid,gid,obj){
	if(!confirm("确定吗")) return false;
	status = $(obj).attr('id');
	
	$.post(SITE_URL+'store.php',{action:'ajax_return_goods',oid:oid,gid:gid,status:status,remark:$('textarea[name="desc"]').val()},function(data){ 
		if(status=='1'){
			$('.option_order1').val("退货申请中");
			$('.option_order1').attr('disabled','disabled');
			
			$('.option_order2').attr('disabled','disabled');
			val = "退货申请中";
		}else if(t=='2'){
			$('.option_order2').val("换货申请中");
			$('.option_order2').attr('disabled','disabled');
			
			$('.option_order1').attr('disabled','disabled');
				
			val = "换货申请中";
		}else if(t=='3'){
			$('.option_order1').val("已退货");
			$('.option_order1').attr('disabled','disabled');
			$('.option_order2').val("换货");
			$('.option_order2').attr('disabled','disabled');
			val = "已退货";
		}else if(t=='4'){
			$('.option_order1').val("退货");
			$('.option_order1').attr('disabled','disabled');
			$('.option_order2').val("已换货");
			$('.option_order2').attr('disabled','disabled');
			val = "已换货";
		}
		window.parent.setreturnbutton(val,gid,oid);
	});
	
}

function load_op(){
	t = '<?php echo $_GET['status'];?>';
	if(t=='1'){
		$('.option_order1').val("退货申请中");
		$('.option_order1').attr('disabled','disabled');
		
		$('.option_order2').attr('disabled','disabled');
	}else if(t=='2'){
		$('.option_order2').val("换货申请中");
		$('.option_order2').attr('disabled','disabled');
		
		$('.option_order1').attr('disabled','disabled');
	}else if(t=='3'){
		$('.option_order1').val("已退货");
		$('.option_order1').attr('disabled','disabled');
		$('.option_order2').val("换货");
		$('.option_order2').attr('disabled','disabled');
	}else if(t=='4'){
		$('.option_order1').val("退货");
		$('.option_order1').attr('disabled','disabled');
		$('.option_order2').val("已换货");
		$('.option_order2').attr('disabled','disabled');
	}
}
window.load=load_op();

</script>
</body>
</html>

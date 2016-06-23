
<?php $this->element('24/top',array('lang'=>$lang)); ?>

<style type="text/css">
body{ background:#FFF !important;}
.pw,.pwt{
height:26px; line-height:26px;
border:none;
border-bottom: 1px solid #F0F0F0;
background-color: #fff; padding-left:5px; padding-right:5px;
}
.pw{ width:98%;}
.usertitle{
height:22px; line-height:22px;color:#666; font-weight:bold; font-size:14px; padding:5px;
border-radius: 5px;
background-color: #ededed; padding-left:5px; padding-right:5px;
-moz-border-radius:5px;/*仅Firefox支持，实现圆角效果*/
-webkit-border-radius:5px;/*仅Safari,Chrome支持，实现圆角效果*/
-khtml-border-radius:5px;/*仅Safari,Chrome支持，实现圆角效果*/
border-radius:5px;/*仅Opera，Safari,Chrome支持，实现圆角效果*/
}
.myuserinfo{ height:36px; line-height:36px;-webkit-box-shadow:0px 2px 2px #ddd}
.myuserinfo a{ display:block; width:33.3%; float:left; text-align:center}
.myuserinfo a.acc{ background:#ddd; color:#FFF}
</style>
<div id="main" style="min-height:300px">
	<div class="myuserinfo">
		<a href="<?php echo ADMIN_URL.'user.php?act=myinfos_u';?>"><i></i>我的注册资料</a>
		<a href="<?php echo ADMIN_URL.'user.php?act=myinfos_s';?>"><i></i>我的收货资料</a>
	</div>
	<div class="clear10"></div>
	
	<div style="padding:10px; overflow:hidden;">
	<form name="USERINFO2" id="USERINFO2" action="" method="post">
		<table width="100%" border="0" cellpadding="0" cellspacing="0" style="line-height:30px;">
		   <tr>
			<td width="100%" align="left" style="padding-bottom:2px;">
			<input type="text" value="<?php echo isset($rts['bankname']) ? $rts['bankname'] : '';?>" name="bankname"  class="pw" placeholder="例如:支付宝、财付通、工商..."/></td>
		  </tr>
		   <tr>
			<td width="100%" align="left" style="padding-bottom:2px;">
			<input type="text" value="<?php echo isset($rts['uname']) ? $rts['uname'] : '';?>" name="uname"  class="pw" placeholder="输入与银行对应的真实姓名"/></td>
		  </tr>
		   <tr>
			<td width="100%" align="left" style="padding-bottom:2px;">
			<input type="text" value="<?php echo isset($rts['banksn']) ? $rts['banksn'] : '';?>" name="banksn"  class="pw" placeholder="例如:支付宝账号、财付通账号、银行卡账号"/></td>
		  </tr>
		   <tr>
			<td width="100%" align="left" style="padding-bottom:2px;">
			<input type="text" value="<?php echo isset($rts['bankaddress']) ? $rts['bankaddress'] : '';?>" name="bankaddress"  class="pw" placeholder="填写正确有效手机号码方便紧急联系"/></td>
		  </tr>
		  <tr>
			<td align="center" style="padding-top:10px;" colspan="2">
			<a href="javascript:;" onclick="return update_user_bank();" style="border-radius:5px;display:block;background:#3083CE;cursor:pointer;width:150px; height:32px; line-height:32px; font-size:14px; color:#FFF">确认提交</a>
		<!--	<a href="<?php echo ADMIN_URL.'daili.php?act=setpasspay';?>" style="border-radius:5px;display:block;background:#E13934;cursor:pointer;width:150px; height:25px; line-height:25px; font-size:14px; color:#FFF; margin-top:10px;">设置或修改提款密码</a>--><span class="returnmes2" style="padding-left:10px; color:#FF0000"></span>
			</td>
		  </tr>
		</table>
	</form>
	</div>
</div>
<script type="text/javascript">
function update_user_bank(){
	//passs = $('input[name="ppass"]').val();
	banknames = $('input[name="bankname"]').val();
	bankaddresss = $('input[name="bankaddress"]').val();
	unames = $('input[name="uname"]').val();
	banksns = $('input[name="banksn"]').val();

	if( banknames=="" || unames=="" || banksns==""){
		$('.returnmes2').html('请输入完整信息');
		return false;
	}

	if(confirm('确认修改吗')){
		createwindow();
		
		$.post('<?php echo ADMIN_URL;?>daili.php',{action:'update_user_bank',bankname:banknames,bankaddress:bankaddresss,uname:unames,banksn:banksns},function(data){ 
			$('.returnmes2').html(data);
			removewindow();
		});
	}
	return false;
}

function update_user_pass2(){
	passs = $('input[name="pass"]').val();
	newpasss = $('input[name="newpass"]').val();
	rpnewpasss = $('input[name="rpnewpass"]').val();
	if(passs=="" || newpasss=="" || newpasss==""){
		$('.returnmes').html('请输入完整信息');
		return false;
	}
	if(newpasss!=rpnewpasss){
		$('.returnmes').html('密码与确认密码不一致');
		return false;
	}
	if(confirm('确认修改吗')){
		createwindow();
		
		$.post('<?php echo ADMIN_URL;?>daili.php',{action:'update_user_pass',pass:passs,newpass:newpasss,rpnewpass:rpnewpasss},function(data){ 
			$('.returnmes').html(data);
			removewindow();
		});
	}
	return false;
}
function ajax_open_dailiapply(tt){
	if(tt==true){
		ty = '1';
	}else{
		ty = '2';
	}
	$.post('<?php echo ADMIN_URL;?>daili.php',{action:'ajax_open_dailiapply',ty:ty},function(data){ 
		
	});
}
</script>
<?php $this->element('24/footer',array('lang'=>$lang)); ?>
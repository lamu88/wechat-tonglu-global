<div id="main">
	<div style="text-align:center; line-height:55px; font-size:16px">
		欢迎使用积分充值，1元=100积分
		<div style="clear:both"></div>
	</div>	
</div>
<div class="footffont">
	<div class="footffontbox">
		<form id="Csumit" name="Csumit" method="post" action="<?php echo ADMIN_URL.'paypoint.php?act=jfconfirmpay';?>">
			<table cellpadding="3" cellspacing="5" border="0" width="100%">
			<tr>
				<td width="100%" align="center" style="text-align:center; font-size:16px;">
				充值金额：<input type="text" name="Cmoney" style="width:60%; height:44px;" class="pw pw2"/>
				</td>
			</tr>
			<tr>
				<td align="center" style="color:#FF0000; font-size:14px;">
				<span class="results"></span>
				</td>
			</tr>
			<tr>
				<td align="center" width="100%">
				<a href="javascript:;" onclick="return check_senddata()" style="background:#00c800; color:#FFF; font-size:14px; text-align:center; display:block; width:100%; padding-bottom:7px; padding-top:5px; height:24px; font-weight:bold;border-radius:5px;"><img src="<?php echo $this->img('24/images/wxioc.png');?>" align="absmiddle" style="height:24px; margin-right:10px" />立即微信支付</a>
				</td>
			</tr>
			</table>
		</form>
	</div>
	<div style="clear:both"></div>
</div>
<script type="text/javascript">
function check_senddata(){
	Cmoney = $('input[name="Cmoney"]').val();
	if(isNaN(Cmoney)||Cmoney==''){
		alert('请输入正确数值！');
		return false;
	}else{
		$('#Csumit').submit();
		return true;
	}
}
</script>
<?php $this->element('24/footer',array('lang'=>$lang)); ?>
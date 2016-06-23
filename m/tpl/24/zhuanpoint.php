<div id="main">
	<div style="text-align:center; line-height:35px; font-size:16px">
		<b>积分转账</b>（转账后，接收人收到等额度购物币。100积分=1币）<br /> ID号请在个人中心查看<br />可以自己转给自己(我的ID：<b><?php echo $this->Session->read('User.uid'); ?></b>)。
		<div style="clear:both"></div>
	</div>	
</div>
<div class="footffont">
	<div class="footffontbox">
		<form id="Pointsumit" name="Pointsumit" method="post" action="<?php echo ADMIN_URL.'paypoint.php?act=zhuanpoint';?>">
			<table cellpadding="3" cellspacing="5" border="0" width="100%">
			<tr>
				<td width="100%" align="center" style="text-align:center; font-size:16px; color:#FF0000">
				接收人ID：<input type="text" name="AccId" value='' style="width:60%; height:44px;" class="pw pw2"/>
				</td>
			</tr>
			<tr>
				<td width="100%" align="center" style="text-align:center; font-size:16px;">
				转账个数：<input type="text" name="PointNum" value='' style="width:60%; height:44px;" class="pw pw2"/>
				</td>
			</tr>
			<tr>
				<td align="center" style="color:#FF0000; font-size:14px;">
				<span class="results"></span>
				</td>
			</tr>
			<tr>
				<td align="center" width="100%">
				<a href="javascript:;" onclick="return check_senddata()" style="background:#00c800; color:#FFF; font-size:14px; text-align:center; display:block; width:100%; padding-bottom:7px; padding-top:7px; height:24px; font-weight:bold;border-radius:5px;">确认转账积分</a>
				</td>
			</tr>
			</table>
		</form>
	</div>
	<div style="clear:both"></div>
</div>
<script type="text/javascript">
function check_senddata(){
	AccId = $('input[name="AccId"]').val();
	if(isNaN(AccId)||AccId==''){
		alert('请输入正确数值！');
		return false;
	}
	PointNum = $('input[name="PointNum"]').val();
	if(isNaN(PointNum)||PointNum==''){
		alert('请输入正确数值！');
		return false;
	}
	if(PointNum>=50&&(PointNum%50==0)){
		$('#Pointsumit').submit();
		return true;
	}else{
		alert('转账个数必须大于50且是50的倍数。');
		return false;
	}
	$('#Pointsumit').submit();
	return true;
}
</script>
<?php $this->element('24/footer',array('lang'=>$lang)); ?>
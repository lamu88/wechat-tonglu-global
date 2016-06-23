<div class="contentbox">
	 <table cellspacing="2" cellpadding="5" width="100%">
	 <tr>
		<th colspan="9" align="left">[<?php echo $user['nickname']; ?>] 信息核实</th>
	</tr>
	<tr style="text-align:center; font-weight:bold">
		<td width="115px">用户ID</td>
		<td width="275px">openID</td>
		<td width="160px">昵称</td>
		<td width="115px">等级</td>
		<td width="115px">已领取金额</td>
		<td width="115px">用户余额</td>
		<td></td>
		<td></td>
		<td width="40%">&nbsp;</td>
	</tr>
	<tr>
		<td><?php echo $user['user_id']; ?></td>
		<td><?php echo $user['wecha_id']; ?></td>
		<td><?php echo $user['nickname']; ?></td>
		<td><?php echo $user['user_rank']; ?></td>
		<td><?php echo $user['money_ucount']; ?></td>
		<td><?php echo $user['mymoney']; ?></td>
		<td></td>
		<td></td>
		<td></td>
	</tr>
	</table>
	

	<table cellspacing="2" cellpadding="5" width="45%" style="float:left; border:#056DA0 solid 1px">
	 <tr>
		<th colspan="4" align="left">用户资金记录</th>
	</tr>
	<tr style="text-align:center; font-weight:bold">
		<td width="85px">购买者</td>
		<td width="80px">收入/支出</td>
		<td width="115px">描述</td>
		<td width="115px">时间</td>
	</tr>
	<?php
		foreach($money as $key=>$m){
	?>
	<tr>
		<td><?php echo $m['buyuid']; ?></td>
		<td><?php echo $m['money']; ?></td>
		<td><?php echo $m['changedesc']; ?></td>
		<td><?php echo date('Y-m-d H:i:s',$m['time']); ?></td>
	</tr>
		<?php } ?>
	</table>
	<table cellspacing="2" cellpadding="5" width="45%" style="maring-left:20px; border:#056DA0 solid 1px">
	 <tr>
		<th colspan="9" align="left">验证码记录</th>
	</tr>
	<tr style="text-align:center; font-weight:bold">
		<td width="85px">订单ID</td>
		<td width="80px">领取状态</td>
		<td width="115px">验证码</td>
		<td width="115px">订单时间</td>
		<td width="115px">购买者ID</td>
		<td width="115px">父级ID</td>
		<td width="115px">层级</td>
		<td width="115px">金额</td>
		<td width="115px">已领取金额</td>
	</tr>
	<?php
		foreach($sn as $key=>$s){
	?>
	<tr>
		<td><?php echo $s['order_id']; ?></td>
		<td><?php echo $s['is_use']==1? '':'已领取'; ?></td>
		<td><?php echo $s['goods_pass']; ?></td>
		<td><?php echo date('Y-m-d H:i:s',$s['usetime']); ?></td>
		<td><?php echo $s['uid']; ?></td>
		<td><?php echo $s['pid']; ?></td>
		<td><?php echo $s['cengji']; ?></td>
		<td><?php echo $s['money']; ?></td>
		<td><?php echo $s['lmoney']; ?></td>
	</tr>
		<?php } ?>
	</table>	
</div>
<div style="clear:both"><br /></div>
<br /><br /><br />
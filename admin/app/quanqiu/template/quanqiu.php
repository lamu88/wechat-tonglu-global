
<div class="contentbox">
    <form id="form1" name="form1" method="post" action="">
	 <table cellspacing="2" cellpadding="5" width="100%">
	  <tr>
		<th colspan="3" align="left">全球分红</th>
	  </tr>
	  <tr>
		<td align="right"><input type="submit" name="submit" value="开始分红" /></td>
		<td><input type="text" name="money" value="<?= $yesterdayMoney ?>" />元</td>
		<td>全球分红为昨日24小时内的分红&nbsp;&nbsp;&nbsp;&nbsp;昨日营收(<font color="red"><?= $yesterdayMoney ?>元)</font></td>
	  </tr>
	  <tr>
		<td colspan="3">
			<a href="/admin/user.php?type=djuser&qqlevel=1">部长：<?php echo $qq1num; ?>&nbsp;&nbsp;</a>|
			<a href="/admin/user.php?type=djuser&qqlevel=2">经理：<?php echo $qq2num; ?>&nbsp;&nbsp;</a>|
			<a href="/admin/user.php?type=djuser&qqlevel=3">董事：<?php echo $qq3num; ?>&nbsp;&nbsp;</a>|
			<a href="/admin/user.php?type=djuser&qqlevel=4">星级董事：<?php echo $qq4num; ?>&nbsp;&nbsp;</a>|
			<a href="/admin/user.php?type=djuser&qqlevel=5">至尊董事：<?php echo $qq5num; ?>&nbsp;&nbsp;</a>|
		</td>
	  </tr>
	  </table>
	</form>
	<br />
	<table cellspacing="2" cellpadding="5" width="100%" style="text-align:center">
	  <tr>
		<th colspan="6" align="left">分红明细</th>
	  </tr>
	  <tr style="font-weight:bold;">
		<td>序号</td><td>分红时间</td><td>发放分红时间</td><td>当天订单总收入</td><td>分红总费用</td><td>税 费</td>
	  </tr>
	  <?php if(!empty($rt))foreach($rt as $k=>$row){
	  ?>
	  <tr>
		<td><?= ($i+1) ?></td>
		<td><?php echo date('Y-m-d',$row['qqtime']); ?></td>
		<td><?php echo date('Y-m-d H:i:s',$row['addtime']); ?></td>
		<td><?php echo '￥'.$row['yesterdayMoney']; ?></td>
		<td><?php echo '￥'.$row['qqmoney']; ?></td>
		<td><?php echo '￥'.$row['shuifei']; ?></td>
	  </tr>
	  <?php 
	  $i++;
	  } ?>
	  </table>
</div>
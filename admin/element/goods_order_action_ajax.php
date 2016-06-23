<table cellspacing="2" cellpadding="2" width="100%">	
 <tr>
		<th>操作者：</th>
			<th>操作时间</th>
			<th>订单状态</th>
			<th>付款状态</th>
			<th>发货状态</th>
			<th>备注</th>
		  </tr>
		  <?php if(!empty($action_info)){
		  	foreach($action_info as $row){
		  ?>
		  <tr>
		  	<td><?php echo $row['action_user'];?></td>
			<td><?php echo $row['log_time'];?></td>
			<td><?php echo $row['order_status'];?></td>
			<td><?php echo $row['pay_status'];?></td>
			<td><?php echo $row['shipping_status'];?></td>
			<td><?php echo $row['action_note'];?></td>
		  </tr>
		  <?php }} ?>
  		</table>
		</td>
	</tr>
</table>
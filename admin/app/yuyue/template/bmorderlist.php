<?php
$thisurl = ADMIN_URL.'yuyue.php'; 
?>
<style>.vieworder{ width:55px; height:25px; display:block; cursor:pointer}</style>
<div class="contentbox">
     <table cellspacing="2" cellpadding="5" width="100%">
	 <tr>
		<th colspan="9" align="left"><span style="float:left">报名订单</span></th>
	</tr>
    <tr>
	   <th>编号</th>
	   <th>标题</th>
	   <th>价格</th>
	   <th>大名</th>
	   <th>微信昵称</th>
	   <th>手机</th>
	   <th>状态</th>
	   <th>时间</th>
	   <th>操作</th>
	</tr>
	<?php
	if(!empty($rt)){ 
	foreach($rt as $row){
	?>
	<tr>
	<td><font color="blue"><?php echo $row['order_sn'];?></font></td>
	<td><?php echo $row['title'];?></td>
	<td>￥<?php echo $row['order_amount'];?></td>
	<td><?php echo $row['uname'];?></td>
	<td><?php echo $row['nickname'];?></td>
	<td><?php echo $row['upne'];?></td><?php $p = date('Y-m-d',$row['pay_time']);?>
	<td><?php echo $row['pay_status']=='0' ? '未支付' : '<font color=blue>已支付('.$p.')</font>';?></td>
	<td><?php echo date('Y-m-d',$row['add_time']);?></td>
	<td>
	<a href="yuyue.php?type=bmorderlist&id=<?php echo $row['id'];?>" onclick="return confirm('确定删除吗')" style="padding:3px 5px 3px 5px; background:#ededed; border-bottom:2px solid #ccc; border-right:2px solid #ccc; color:#FF0000">删除</a>
	</td>
	</tr>
	<?php
	 } ?>
		<?php } ?>
	 </table>
	 <?php $this->element('page',array('pagelink'=>$pagelink));?>
</div>
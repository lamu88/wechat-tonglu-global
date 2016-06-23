<div class="contentbox">
 <table cellspacing="2" cellpadding="5" width="100%" style="line-height:25px">
 <tr>
	 <th colspan="7" align="left" style="position:relative">红包列表<span style=" position:absolute; right:5px; top:3px"><a href="coupon.php?type=list">返回红包类型</a></span></th>
</tr>
<tr>
 	<th>编号</th><th>发放名称</th><th>红包序列号</th><th>红包类型</th><th>使用会员</th><th>使用时间</th><th>操作</th>
</tr>
<?php
$send_type = array('按用户发放','按商品发放','按订单金额发放','线下发放的红包');
if(!empty($rt)){
foreach($rt as $row){
?>
<tr>
<td><?php echo $row['bonus_id'];?></td>
<td><?php echo $row['type_name'];?></td>
<td><?php echo $row['bonus_sn'];?></td>
<td>&nbsp;<?php echo $send_type[$row['send_type']];?></td>
<td>&nbsp;<a href="user.php?type=info&id=<?php echo $row['user_id'];?>&goto=list"><?php echo $row['user_name'];?></a></td>
<td><?php echo !empty($row['used_time']) ? date('Y-m-d H:i:s',$row['used_time']) : "未使用";?></td>
<td><a href="coupon.php?type=couponview&id=<?php echo $_GET['id']?>&op=del&delid=<?php echo $row['bonus_id'];?>" onclick="return confirm('确定删除吗？')">移除</a></td>
</tr>
<?php } } ?>
 </table>
 <?php $this->element('page',array('pagelink'=>$pagelink));?>
</div>
<div class="contentbox">
     <table cellspacing="2" cellpadding="5" width="100%">
	 <tr>
	 		<th colspan="7" align="left" style="position:relative">红包类型列表<span style=" position:absolute; right:5px; top:3px"><a href="coupon.php?type=coupontype">添加红包类型</a></span></th>
	 </tr>
	 <tr>
      <th>类型名称</th>
      <th>发放类型</th>
      <th>红包金额</th>
      <th>订单下限</th>
      <th>发放数量</th>
      <th>使用数量</th>
      <th>操作</th>
    </tr>
	<?php 
	function return_type($k){
		$arr = array('按用户发放','按商品发放','按订单金额发放','线下发放的红包');
		return isset($arr[$k]) ? $arr[$k] : '无类型';
	}
	
	if(!empty($rt)){
	foreach($rt as $row){
	?>
       <tr>
      <td><?php echo $row['type_name'];?></td>
      <td><?php echo return_type($row['send_type']);?></td>
      <td align="right"><?php echo $row['type_money'];?></td>
      <td align="right"><?php echo $row['min_goods_amount'];?></td>
      <td align="right"><?php echo $row['zcount'];?></td>
      <td align="right"><?php echo $row['ucount'];?></td>
      <td align="right">
        <?php  if($row['send_start_date']<mktime() && $row['send_end_date']>mktime()){ ?><a href="coupon.php?type=couponsend&type_id=<?php echo $row['type_id'];?>&send_by=<?php echo $row['send_type'];?>">发放</a><?php }else{?><font color="#CCCCCC">发放[过期]</font><?php } ?> |
        <a href="coupon.php?type=couponview&id=<?php echo $row['type_id'];?>&send_by=<?php echo $row['send_type'];?>">查看</a> |
        <a href="coupon.php?type=coupontype&id=<?php echo $row['type_id'];?>">编辑</a> |
        <a href="coupon.php?type=list&id=<?php echo $row['type_id'];?>" onclick="return confirm('确定删除吗')">移除</a>
	  </td>
    </tr>
   <?php } } ?>
	 </table>
	 
	 <?php $this->element('page',array('pagelink'=>$pagelink));?>
</div>
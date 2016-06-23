<style type="text/css">
body{padding:10px; background:#FFF}
body,td {font-size:13px;}
p{ height:22px; line-height:22px; margin:0px; padding:0px}
</style>
<?php
function format_price($price=0){
	if(empty($price)) return '0.00';
	return number_format($price, 2, '.', '');
}
?>
<?php if(!empty($rt))foreach($rt as $sn=>$item){?>
<table width="100%" border="1" style="border-collapse:collapse;border-color:#333;">
	<tr>
		<td style="padding-left:2px;padding-bottom:5px;" valign="middle" colspan="2"><!--<img src="<?php echo $this->img('logo.png');?>" />--><b style="font-weight:bold; font-size:22px;">商品发货单</b></td>
		<td style="font-size:20px; font-weight:bold;padding-bottom:5px;" valign="middle">订单号：<?php echo $sn;?></td>
		<td align="center"><img src="<?php echo $this->img('sn.jpg');?>" /><br/><?php echo $sn;?></td>
	</tr>
	<tr>
		<td colspan="5" style="padding:5px; text-align:left">
		<!--<p>如你有任何疑问，请根据以下信息与我们联系；</p>-->
		<p>微信：<?php echo $rts['site_name'];?></p>
		<p>公司地址：<?php echo $rts['company_url'];?><!--&nbsp;&nbsp;电话：<?php echo implode(',',$rts['custome_phone']);?>&nbsp;&nbsp;邮箱：<?php echo $rts['custome_email'];?></p>-->
		</td>
	</tr>
</table>

<?php 
$ab = array_slice($item,0,1);
$abc = $ab[0];
unset($ab);
?>
<p style="border-left:1px solid #333;border-right:1px solid #333;height:16px;"></p>
<table width="100%" border="1" style="border-collapse:collapse;border-color:#333;">
    <tr>
        <td width="11%">客户:</td>
        <td><?php echo $abc['consignee'];?><!-- 购货人姓名 --></td>
        <td align="right">下单时间：</td><td><?php echo date('Y-m-d H:i:s',$abc['add_time']);?><!-- 下订单时间 --></td>
        <td align="right">支付方式：</td><td><?php echo $abc['pay_name'];?><!-- 支付方式 --></td>
       <td align="right">配送方式：</td><td><?php echo $abc['shipping_name'];?><!-- 配送方式 --></td>
    </tr>
    <tr>
        <td>收货地址：</td>
        <td colspan="7">
        <?php echo $abc['dizhi']['province'].'&nbsp;'.$abc['dizhi']['city'].'&nbsp;'.$abc['dizhi']['district'].'&nbsp;'.$abc['dizhi']['towns'].'&nbsp;'.$abc['dizhi']['villages'].'&nbsp;';?><?php echo $abc['shipping_id']==6? $abc['user_name'] : $abc['address'];?><!-- 收货人地址 -->
        收货人：<?php echo $abc['consignee'];?>&nbsp;<!-- 收货人姓名 -->
        <!-- 邮政编码 -->
        电话：<?php echo $abc['tel'];?>&nbsp; <!-- 联系电话 -->
        手机：<?php echo $abc['mobile'];?><!-- 手机号码 -->
        </td>
		<!--<td align="right">要求配送时间：</td>
		<td align="left" width="12%"><?php echo $abc['best_time'];?></td>-->
    </tr>
	<tr>
			<td><b>备注：</b></td><td colspan="7"><?php echo $abc['postscript'];?></td>
	</tr>
</table>

<p style="border-left:1px solid #333;border-right:1px solid #333; height:16px;"></p>
<table width="100%" border="1" style="border-collapse:collapse;border-color:#333;">
    	<tr align="center">
			  <th bgcolor="#cccccc">序号</th>
			  <th bgcolor="#cccccc">商品名称</th>
			  <th bgcolor="#cccccc">规格</th>
			  <th bgcolor="#cccccc">优惠价</th>
			  <th bgcolor="#cccccc">购买数量</th>
			  <th bgcolor="#cccccc">小计</th>
		 </tr>
		 <?php
		  if(!empty($item)){
		  $total= 0;
		  $jifen_total = 0;
		  foreach($item as $k=>$rows){
		  
		  $stotal = $rows['goods_amount']; //每个单子的总价
		  $totaloff = $rows['offprice']; //每个单子的总折扣价

			  $zgoods = 0;
		  	  if(!empty($rows['goods']))foreach($rows['goods'] as $kk=>$row){
				  if($row['from_jifen'] > 0){
					$jifen_total +=$row['from_jifen'];
				  }else{
					$total +=$row['goods_price']*$row['goods_number'];
				  }
				  $zgoods += $row['goods_price']*$row['goods_number'];
		  ?>
		 <tr>
		 	  <td align="center"><?php echo ++$kk;?></td>
			  <td align="left" style="padding-left:5px;">
			  <?php echo ($row['is_gift']==1 ? '<font color="red">[赠品]</font>'.$row['goods_name'] : $row['goods_name']); if(!empty($row['buy_more_best'])){echo '<br />该商品实行<font style="color:#FE0000;font-weight:bold">['.$row['buy_more_best'].']</font>促销活动，欢迎订购！';}?>
			  </td>
			  <td align="center"><?php echo $row['goods_attr'];?></td>
			  <td align="center">￥<?php echo $row['goods_price'];?></td>
			  <td align="center"><?php echo $row['goods_number'].' '.$row['goods_unit'];?></td>
			  <td align="center">￥<?php echo $row['goods_price']*$row['goods_number']; if($row['from_jifen']>0) echo '&nbsp;&nbsp;<font color="red">[积分换取商品]</font>';?></td>
		   </tr>
		 <?php }
		 ?>
		
<!--		 <tr>
				<td colspan="6" align="right">供应商会员号：<font color="red" style="font-size:14"><?php echo $rows['suppliers_id'];?></font>&nbsp;&nbsp;&nbsp;&nbsp;</td>
		</tr>
		<tr>
				<td colspan="6" align="right" style="padding-right:22px">实际支付: <font color="#FF0000">￥<?php echo format_price($zgoods-$p2);?></font></td>
		</tr>-->
		 <tr><td colspan="6">&nbsp;</td></tr>
		 <?php
		  } ?>
		<tr>
		  <td colspan="6">
		  商品总价(不计邮费): <font color="#FF0000">￥<?php echo $abc['order_amount'];?></font>&nbsp;&nbsp;实际收款: <font color="#FF0000">￥<?php echo $abc['order_amount']+$abc['shipping_fee'];?></font> &nbsp;&nbsp; <?php if($jifen_total>0){?>&nbsp;&nbsp;支付积分：<span style="color:red"><?php echo $jifen_total;?></span>&nbsp;&nbsp;&nbsp;<?php } ?>其中配运费：<font color="#FF0000">￥<?php echo $abc['shipping_fee'];?></font>
		  </td>
		</tr>
	<?php 
	}
	?>
</table>
<table width="100%" border="0">
    <tr align="right"><!-- 订单操作员以及订单打印的日期 -->
        <td>打印时间：<?php echo date('Y-m-d H:i:s',mktime());?>&nbsp;&nbsp;&nbsp;操作者：<?php echo $this->Session->read('adminname');?></td>
    </tr>
</table>
<p style="height:20px"></p>
<?php } ?>
<script type="text/javascript">
function load_suppliers_address(sid,obj){
	/*$.post('<?php echo SITE_URL;?>user.php',{action:'get_suppliers_address',suppliers_id:sid},function(data){
		$(obj).parent().html(data);
	});
	return false;*/
}
</script>
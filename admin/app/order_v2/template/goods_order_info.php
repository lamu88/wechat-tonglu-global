<div class="contentbox">
<style type="text/css">
.order_basic table td{ border:1px solid #F4F6F1; }
.order_basic td p{background:#F5F7F2; text-align:center; line-height:25px; font-size:13px; font-weight:bold; margin-bottom:0px; margin-top:0px}
</style>
<div class="openwindow"><img src="<?php echo $this->img('loading.gif');?>"  align="absmiddle"/><br />正在操作，请稍后。。。</div>
<table cellspacing="2" cellpadding="5" width="100%" class="order_basic">
	 <tr>
		<th align="left">订单详情列表</th>
	</tr>
	<tr>
		<td>
		<p>基本信息</p>
		</td>
	</tr>
	<tr>
		<td>
		<table cellspacing="0" cellpadding="0" width="100%">
			<tr>
				<td class="label" width="15%">订单号：</td>
				<td width="35%"><?php echo $rt['orderinfo']['order_sn'];?></td>
				<td class="label" width="15%">订单状态：</td>
				<td width="35%"><?php echo $rt['orderinfo']['status'];?></td>
			</tr>
			<tr>
				<td class="label" width="15%">购货人：</td>
				<td width="35%"><?php echo $rt['orderinfo']['consignee'];?></td>
				<td class="label" width="15%">下单时间：</td>
				<td width="35%"><?php echo !empty($rt['orderinfo']['add_time']) ? date('Y-m-d H:i:s',$rt['orderinfo']['add_time']) : '无知';?></td>
			</tr>
			<tr>
				<td class="label" width="15%">支付方式：</td>
				<td width="35%"><?php echo $rt['orderinfo']['pay_name'];?></td>
				<td class="label" width="15%">付款时间：</td>
				<td width="35%"><?php echo !empty($rt['orderinfo']['shipping_time']) ? date('Y-m-d H:i:s',$rt['orderinfo']['shipping_time']) : '未知';?></td>
			</tr>
			<tr>
				<td class="label" width="15%">电子邮件：</td>
				<td width="35%"><?php echo $rt['userinfo']['email'];?></td>
				<td class="label" width="15%">发货时间：</td>
				<td width="35%"><?php echo !empty($rt['orderinfo']['shipping_time']) ? date('Y-m-d H:i:s',$rt['orderinfo']['shipping_time']) : '未知';?></td>
			</tr>
			<tr> 
				<td class="label" width="15%">订单附言：</td>
				<td ><?php echo $rt['orderinfo']['postscript'];?></td>
				<td class="label" width="15%">缺货说明：</td>
				<td ><?php echo $rt['orderinfo']['how_oos'];?></td>
			</tr>
			<tr> 
				<td class="label" width="15%">邮费：</td>
				<td >￥<?php echo $rt['orderinfo']['shipping_fee'];?></td>
				<td class="label" width="15%">产品总价：</td>
				<td >￥<?php echo $rt['orderinfo']['goods_amount'];?></td>
			</tr>
		</table>
		</td>
	</tr>
	<tr>
		<td>
		<p>收货人信息</p>
		</td>
	</tr>
	<tr>
		<td>
		<table cellspacing="0" cellpadding="0" width="100%" class="order_basic">
			<tr>
				<td class="label" width="15%">收货人：</td>
				<td width="35%"><a href="user.php?type=userress&id=<?php echo $rt['orderinfo']['user_id'];?>" style="color:#FE0000" title="查看详情"><?php echo $rt['orderinfo']['consignee'];?></a><em>[点击可进入收货人详情]</em></td>
				
				<td class="label" width="15%">配送方式：</td>
				<td width="35%"><?php echo $rt['orderinfo']['shipping_name'];?></td>
			</tr>
			<tr>
				<td class="label" width="15%"><?php echo $rt['orderinfo']['shipping_id']=='6' ? '提货店址' : '收货地址';?>：</td>
				<td width="35%"><?php echo $rt['orderinfo']['province'].' '.$rt['orderinfo']['city'].' '.$rt['orderinfo']['district'].' '.$rt['orderinfo']['town'].' '.$rt['orderinfo']['village'].' '.($rt['orderinfo']['shipping_id']=='6' ? $rt['orderinfo']['user_name'] : $rt['orderinfo']['address']);?></td>
				<td class="label" width="15%">邮编：</td>
				<td width="35%"><?php echo $rt['orderinfo']['zipcode'];?></td>
			</tr>
			<tr>
				<td class="label" width="15%">电话|手机：</td>
				<td width="35%"><?php echo $rt['orderinfo']['tel'];?>|<?php echo $rt['orderinfo']['mobile'];?></td>
				<td class="label" width="15%">要求送货时间：</td>
				<td width="35%"><?php echo !empty($rt['orderinfo']['best_time']) ? $rt['orderinfo']['best_time'] : '无说明';?></td>
			</tr>
			
			<tr>
				<td class="label" width="15%">订单附言：</td>
				<td width="85%"><?php echo $rt['orderinfo']['postscript'];?></td>
			</tr>
		</table>
		</td>
	</tr>
	<tr>
		<td>
		<p>商品信息</p>
		</td>
	</tr>
	<tr>
		<td>
		<table cellspacing="0" cellpadding="0" width="100%" >
			<tr align="center" >
				<td ><strong>商品条形码</strong></td>
				<td ><strong>商品名称[品牌]</td>
				<td ><strong>规格</strong></td>
				<td ><strong>单位</strong></td>
				<td><strong>数量</strong></td>
				<td ><strong>单价</strong></td>
				<td ><strong>库存</strong></td>
				<td><strong>金额</strong></td>
				
			</tr>
			<?php if(!empty($rt['ordergoods'])){
			$totalprice = 0;
			foreach($rt['ordergoods'] as $row){ 
			$totalprice += (!empty($row['goods_price'])? $row['goods_price'] : $row['market_price'])*$row['goods_number'];
			?>
			<tr align="center">
				<td><?php echo $row['goods_sn'];?></td>
				<td><a href="<?php echo SITE_URL;?>goods.php?id=<?php echo $row['goods_id'];?>" target="_blank"><?php echo $row['goods_name'].(!empty($row['brand_name']) ? '['.$row['brand_name'].']' : '').'</a>'.(!empty($row['buy_more_best']) ? '<br /><em>实行<font style="color:#FE0000;font-weight:bold">['.$row['buy_more_best'].']</font>促销活动！</em>' : '');?></td>
				<td><?php echo $row['goods_attr'];?></td>
				<td><?php echo $row['goods_unit'];?></td>
				<td><?php echo $row['goods_number'];?></td>
				<td><?php echo $row['goods_price'];?></td>
				<td><?php echo $row['storage'];?></td>
				<td><?php echo '￥'.(!empty($row['goods_price'])? $row['goods_price'] : $row['market_price'])*$row['goods_number'];?></td>
				
			</tr>
			<?php } ?>
			<tr align="center">
				<td colspan="7" align="right">总价:</td><td><?php echo '￥'.$totalprice;?></td>
			</tr>
			<?php }  ?>
		</table>
		</td>
	</tr>
	<tr>
		<td>
		<p>操作信息</p>
		</td>
	</tr>
	<tr>
		<td>
		<table cellspacing="0" cellpadding="0" width="100%">
		<tr>
			<td width="15%">		
			<strong>操作备注:</strong>
			</td>
			<td>
			<textarea name="action_note" cols="80" rows="3"></textarea>
			</td>
		</tr>
		<tr>
			<td width="15%"><strong>当前可执行操作:</strong></td>
			<td id="get_button">
			<?php echo $rt['order_action_button'];?>
			 <!--<input name="confirm" value="确认" class="confirm_order" type="button">
			 <input name="remove" value="移除" class="remove_order" onclick="return window.confirm('删除订单将清除该订单的所有信息。您确定要这么做吗？');" type="submit">
             <input name="order_id" value="18" type="hidden">-->
			</td>
		</tr>
		</table> 
		</td>
	</tr>
	<tr>
		<td id="action_list">
		<?php $this->element('goods_order_action_ajax',array('action_info'=>$rt['action_info']));?>
		</td>
	</tr>
</table>
</div>
<?php  $thisurl = ADMIN_URL.'goods_order.php'; ?>
<script type="text/javascript">
<!--
/*$.ajax({
   type: "POST",
   url: "<?php echo $thisurl;?>",
   data: "action=op_status&opstatus=" + opstatus + "&opremark=" + opremark + "&opid=" + id,
   dataType: "json",
   success: function(data){
		if (data.err_msg == 0)
		{
		  var layer = document.getElementById('RECOMEND_GOODS');
		  if (layer)
		  {
			layer.innerHTML = data.result;
		  }
		}
   }
}); //end ajax
*/

$('.order_action').live('click',function(){
	createwindow();
	opstatus = $(this).attr('id');
	opremark = $("textarea[name='action_note']").val();
	id = '<?php echo $_REQUEST['id']?>';
	$.post('<?php echo $thisurl;?>',{action:'op_status',opstatus:opstatus,opremark:opremark,opid:id},function(data){
		$("textarea[name='action_note']").val("");
		if(data !=""){
			$.post('<?php echo $thisurl;?>',{action:'get_status_button',status:opstatus},function(datas){
				if(datas !=""){
					$("#get_button").html("")
					$("#get_button").html(datas)
				}
			});
			$("#action_list").html("")
			//$('#action_list').html(data);
			$("#action_list").html(data)
		}else{
			alert("操作失败！");
		}
		removewindow();

	});
});
-->
</script>
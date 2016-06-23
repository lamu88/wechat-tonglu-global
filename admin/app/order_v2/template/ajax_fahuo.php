<style type="text/css">
p{ margin:0px; padding:0px}
.pw,.pwt{
height:26px; line-height:26px;
border: 1px solid #ddd;
border-radius: 5px;
background-color: #fff; padding-left:5px; padding-right:5px;
-moz-border-radius:5px;/*仅Firefox支持，实现圆角效果*/
-webkit-border-radius:5px;/*仅Safari,Chrome支持，实现圆角效果*/
-khtml-border-radius:5px;/*仅Safari,Chrome支持，实现圆角效果*/
border-radius:5px;/*仅Opera，Safari,Chrome支持，实现圆角效果*/
}
.pw{ width:90%;}
.usertitle{
height:22px; line-height:22px;color:#333; font-weight:bold; font-size:14px; padding:5px;
border-radius: 5px;
background-color: #ededed; padding-left:5px; padding-right:5px;
-moz-border-radius:5px;/*仅Firefox支持，实现圆角效果*/
-webkit-border-radius:5px;/*仅Safari,Chrome支持，实现圆角效果*/
-khtml-border-radius:5px;/*仅Safari,Chrome支持，实现圆角效果*/
border-radius:5px;/*仅Opera，Safari,Chrome支持，实现圆角效果*/
}
table p{ line-height:22px;}
.order_basic table td{ border:1px solid #F4F6F1; }
.usertitle{background:#F5F7F2; text-align:center; line-height:25px; font-size:13px; font-weight:bold; margin-bottom:0px; margin-top:0px}
</style>
<div class="contentbox">
 	<div class="usertitle">发货操作</div>
	<table cellspacing="2" cellpadding="5" width="100%" class="order_basic" style="width:100%;">
	<?php if(!empty($rt))foreach($rt as $row){
	if(!($row['shipping_id']>0)){
		$this->action('order_v2','update_shipping_id',$row['id'],$sid);
	}else{
		$sid = $row['shipping_id'];
	}
	?>
	<tr>
		<td align="left">
		<p style=" margin:0px ; padding:0px;line-height:22px;"><?php echo $row['province'].$row['city'].$row['district'].$row['address'].'--'.$row['consignee'].'('.$row['moblie'].')';?></p>
		<p>
		  订单号:<?php echo $row['order_sn'].'&nbsp;&nbsp;时间:'.(date('Y-m-d H:i:s',$row['add_time']))."<br/>";?>
		  <select name="shoppingid" onchange="return update_shipping_id('<?php echo $row['id'];?>',this)">
		  <option value="0">选择物流</option>
		  <?php foreach($sp as $rows){
		  $s = $rows['shipping_id']==$sid ? ' selected="selected"' : '';
		  echo '<option value="'.$rows['shipping_id'].'"'.$s.'>'.$rows['shipping_name'].'</option>';
		   } ?>
	      </select>&nbsp; 物流单号:<font color="blue"><?php echo !empty($row['shipping_sn']) ? $row['shipping_sn'] : '待发货';?></font>&nbsp;&nbsp;<a href="javascript:;" onclick="return ajax_fahuo('<?php echo $row['id'];?>','0','<?php echo $row['shipping_status'];?>')" style="background:#ededed; border-bottom:2px solid #ccc; border-right:2px solid #ccc; padding:1px 5px 1px 5px"><?php echo $row['shipping_status']=='0' ? '发货' : ($row['shipping_status']=='5' ? '已收货' : ($row['shipping_status']=='4' ? '已退回' : '已发出'));?></a>
		</p>
		</td>
	</tr>
	<?php }?>
	<tr>
		<td align="left">
		 <a href="javascript:;" onclick="return ajax_fahuo('0','<?php echo $row['rec_id'];?>')" style=" color:#FF0000;background:#ededed; border-bottom:2px solid #ccc; border-right:2px solid #ccc; padding:2px 5px 2px 5px">批量发货</a>
		</td>
	</tr>
	</table>
</div>
<script type="text/javascript">
function update_shipping_id(id,obj){
	if(confirm('确定改变吗?物流单号将会发生改变')){
		sid = $(obj).val();
		$.post('<?php echo ADMIN_URL.'goods_order_v2.php';?>',{action:'update_shipping_id2',id:id,sid:sid},function(data){
			if(data=='2'){
				alert('请再次生成物流单号');
			}
		});
	}
	return false;
}

function ajax_fahuo(id,rid,ss){
	//if(ss=='2') return false;
	if(confirm('确定发货吗')){
		createwindow();
		$.post('<?php echo ADMIN_URL.'goods_order_v2.php';?>',{action:'ajax_fahuo_op',id:id,rid:rid},function(data){
			removewindow();
			window.location.href='<?php echo ADMIN_URL.'goods_order_v2.php';?>?type=ajax_fahuo&rid=<?php echo $rid;?>&oid=<?php echo $oid;?>';
		});
	}
	return false;
}
</script>
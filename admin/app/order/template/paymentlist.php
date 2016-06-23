<div class="contentbox">
     <table cellspacing="2" cellpadding="5" width="100%">
	 <tr>
		<th colspan="4" align="left">支付方式列表<span style="float:right"><a href="payment.php?type=info">添加支付方式</a></span></th>
	</tr>
    <tr>
	   <th width="25%">支付名称</th>
	   <th>支付描述</th>
	   <th>状态</th>
	   <th width="10%">操作</th>
	</tr>
	<?php 
	if(!empty($rt)){ 
	foreach($rt as $row){
	?>
	<tr>
	<td><?php echo $row['pay_name'];?></td>
	<td><?php echo $row['pay_desc'];?></td>
	<td><img src="<?php echo $this->img($row['enabled']==1 ? 'yes.gif' : 'no.gif');?>" alt="<?php echo $row['enabled']==1 ? '0' : '1';?>" class="activeop" lang="active" id="<?php echo $row['pay_id'];?>"/></td>
	<td>
	<a href="payment.php?type=info&id=<?php echo $row['pay_id'];?>" title="编辑"><img src="<?php echo $this->img('icon_view.gif');?>" title="编辑"/></a>&nbsp;
	<!--<a href="payment.php?type=list&id=<?php echo $row['pay_id'];?>" onclick="if(confirm('确定删除吗?')){return true;}else{return false;}"><img src="<?php echo $this->img('icon_drop.gif');?>" title="删除" alt="删除"/></a>-->
	</td>
	</tr>
	<?php
	 } ?>
		<?php } ?>
	 </table>
</div>
<script type="text/javascript">
   	$('.activeop').live('click',function(){
		star = $(this).attr('alt');
		gid = $(this).attr('id'); 
		type = $(this).attr('lang');
		obj = $(this);
		$.post('<?php echo ADMIN_URL.'payment.php';?>',{action:'ajax_pay_activeop',active:star,id:gid,type:type},function(data){
			//if(data == ""){
				if(type=='active'){
					if(star == 1){
						id = 0;
						src = '<?php echo $this->img('yes.gif');?>';
					}else{
						id = 1;
						src = '<?php echo $this->img('no.gif');?>';
						
					}
				}
				
				obj.attr('src',src);
				obj.attr('alt',id);
			/*}else{
				alert(data);
			}*/
		});
	});
</script>
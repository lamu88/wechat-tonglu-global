<div class="contentbox">
     <table cellspacing="2" cellpadding="5" width="100%">
	 <tr>
		<th colspan="3" align="left">配送方式列表<span style="float:right"><a href="delivery.php?type=info">添加配送方式</a></span></th>
	</tr>
    <tr>
	   <th width="25%">配送名称</th>
	   <th>配送描述</th>
	   <th width="15%">操作</th>
	</tr>
	<?php 
	if(!empty($rt)){ 
	foreach($rt as $row){
	?>
	<tr>
	<td><?php echo $row['shipping_name'];?></td>
	<td><?php echo $row['shipping_desc'];?></td>
	<td>
	<a href="delivery.php?type=arealist&id=<?php echo $row['shipping_id'];?>" title="设置区域">设置区域</a>&nbsp;
	<a href="delivery.php?type=info&id=<?php echo $row['shipping_id'];?>" title="编辑">编辑</a>&nbsp;
	<a href="delivery.php?type=list&id=<?php echo $row['shipping_id'];?>" onclick="if(confirm('确定删除吗?')){return true;}else{return false;}"><img src="<?php echo $this->img('icon_drop.gif');?>" title="删除" alt="删除"/></a>
	</td>
	</tr>
	<?php
	 } ?>
		<?php } ?>
	 </table>
</div>

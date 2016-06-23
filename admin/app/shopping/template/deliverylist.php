<div class="contentbox">
     <table cellspacing="2" cellpadding="5" width="100%">
	 <tr>
		<th colspan="3" align="left">物流列表<span style="float:right"><a href="shopping.php?type=shoppinginfo">添加物流</a></span></th>
	</tr>
    <tr>
	   <th width="25%">物流名称</th>
	   <th>描述</th>
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
	<a href="shopping.php?type=shoppinginfo&id=<?php echo $row['shipping_id'];?>" title="编辑">编辑</a>&nbsp;
	<a href="shopping.php?type=shoppinglist&id=<?php echo $row['shipping_id'];?>" onclick="if(confirm('确定删除吗?')){return true;}else{return false;}"><img src="<?php echo $this->img('icon_drop.gif');?>" title="删除" alt="删除"/></a>
	</td>
	</tr>
	<?php
	 } ?>
		<?php } ?>
	 </table>
</div>

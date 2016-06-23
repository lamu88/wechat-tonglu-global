<div class="contentbox">
     <table cellspacing="2" cellpadding="5" width="100%">
	 <tr>
		<th colspan="4" align="left"><a href="delivery.php?type=list">返回配送方式列表</a><span style="float:right"><a href="delivery.php?type=areainfo&cid=<?php echo $_GET['id'];?>">添加配送区域</a></span></th>
	</tr>
    <tr>
	   <th width="25%">配送名称</th>
	   <th>配送描述</th>
	   <th>所在地区</th>
	   <th width="10%">操作</th>
	</tr>
	<?php 
	if(!empty($rt)){ 
	foreach($rt as $row){
	?>
	<tr>
	<td><?php echo $row['shipping_area_name'];?></td>
	<td><?php echo $row['shipping_desc'];?></td>
	<td><?php echo !empty($row['configure']) ? implode('&nbsp;&nbsp;',$row['configure']) : "";?></td>
	<td>
	<a href="delivery.php?type=areainfo&cid=<?php echo $_GET['id'];?>&id=<?php echo $row['shipping_area_id'];?>" title="编辑">编辑</a>&nbsp;
	<a href="delivery.php?type=arealist&id=<?php echo $_GET['id'];?>&delid=<?php echo $row['shipping_area_id'];?>" onclick="if(confirm('确定删除吗?')){return true;}else{return false;}"><img src="<?php echo $this->img('icon_drop.gif');?>" title="删除" alt="删除"/></a>
	</td>
	</tr>
	<?php
	 } ?>
		<?php } ?>
	 </table>
</div>

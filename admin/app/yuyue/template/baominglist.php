<?php
$thisurl = ADMIN_URL.'goods.php'; 
?>
<style>.vieworder{ width:55px; height:25px; display:block; cursor:pointer}</style>
<div class="contentbox">
     <table cellspacing="2" cellpadding="5" width="100%">
	 <tr>
		<th colspan="5" align="left"><span style="float:left">报名列表</span><a href="yuyue.php?type=baominginfo" style="float:right">添加报名</a></th>
	</tr>
    <tr>
	   <th>图片</th>
	   <th>标题</th>
	   <th>价格</th>
	    <th>发布时间</th>
	   <th>操作</th>
	</tr>
	<?php
	if(!empty($rt)){ 
	foreach($rt as $row){
	?>
	<tr>
	<td><a target="_blank" href="<?php echo SITE_URL.'m/bm.php?id='.$row['id'];?>"><img src="<?php echo !empty($row['img']) ? 	dirname(ADMIN_URL).'/'.$row['img'] : $this->img('no_picture.gif');?>" width="60"/></a></td>
	<td><?php echo $row['title'];?></td>
	<td>￥<?php echo $row['price'];?></td>
	<td><?php echo date('Y-m-d',$row['addtime']);?></td>
	<td>
	<a target="_blank" href="<?php echo SITE_URL.'m/bm.php?id='.$row['id'];?>" style="padding:3px 5px 3px 5px; background:#ededed; border-bottom:2px solid #ccc; border-right:2px solid #ccc; color:#0000FF">查看</a>&nbsp;
	<a href="yuyue.php?type=baominginfo&id=<?php echo $row['id'];?>" title="编辑" style="padding:3px 5px 3px 5px; background:#ededed; border-bottom:2px solid #ccc; border-right:2px solid #ccc">编辑</a>&nbsp;
	<a href="yuyue.php?type=baominglist&id=<?php echo $row['id'];?>" onclick="return confirm('确定删除吗')" style="padding:3px 5px 3px 5px; background:#ededed; border-bottom:2px solid #ccc; border-right:2px solid #ccc; color:#FF0000" id="<?php echo $row['id'];?>" class="delgoodsid">删除</a>
	</td>
	</tr>
	<?php
	 } ?>
		<?php } ?>
	 </table>
	 <?php $this->element('page',array('pagelink'=>$pagelink));?>
</div>
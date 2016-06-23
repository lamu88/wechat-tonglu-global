<div class="contentbox">
     <table cellspacing="2" cellpadding="5" width="100%">
	 <tr>
		<th colspan="5" align="left" style="position:relative">专题管理列表<span style=" position:absolute; right:5px; top:3px"><a href="topic.php?type=info">添加专题</a></span></th>
	</tr>
    <tr>
	   <th width="70">编号</th>
	   <th>专题名称</th>
	   <th>开始时间</th>
	   <th>结束时间</th>
	   <th>操作</th>
	</tr>
	<?php
	 if(!empty($rt)){
	 foreach($rt as $row){
	?>
	<tr>
	<td><?php echo $row['topic_id'];?></td>
	<td><?php echo $row['topic_name'];?></td>
	<td><?php echo date('Y-m-d',$row['start_time']);?></td>
	<td><?php echo date('Y-m-d',$row['end_time']);?></td>
	<td><a href="<?php echo SITE_URL;?>top.php?id=<?php echo $row['topic_id'];?>" target="_blank">查看</a>&nbsp;&nbsp; <a href="<?php echo ADMIN_URL;?>topic.php?type=info&id=<?php echo $row['topic_id'];?>">编辑</a>&nbsp;&nbsp; <a href="<?php echo ADMIN_URL;?>topic.php?type=list&id=<?php echo $row['topic_id'];?>" onclick="return confirm('确定删除吗')">删除</a></td>
	</tr>
	<?php } } ?>
	 </table>
	 <?php $this->element('page',array('pagelink'=>$pagelink));?>
</div>

<?php  $thisurl = ADMIN_URL.'topic.php'; ?>
<script type="text/javascript" language="javascript">
   $('.delgoodsid').click(function(){
   		ids = $(this).attr('id');
		thisobj = $(this).parent().parent();
		if(confirm("确定删除吗？")){
			createwindow();
			$.get('<?php echo $thisurl;?>',{type:'delgoods',ids:ids},function(data){
				removewindow();
				if(data == ""){
					thisobj.hide(300);
				}else{
					alert(data);	
				}
			});
		}else{
			return false;	
		}
   });
 </script>
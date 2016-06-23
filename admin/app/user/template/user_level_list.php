<div class="contentbox">
     <table cellspacing="2" cellpadding="5" width="100%">
	 <tr>
		<th colspan="4" align="left">会员等级<!--<span style="float:right"><a href="user.php?type=levelinfo">添加会员等级</a>--></span></th>
	</tr>
    <tr>
		<th width="50px">等级LID</th>
		<th>会员等级名称</th>
		<th>操作</th>
	</tr>
	<?php 
	if(!empty($rt)){ 
	foreach($rt as $row){
	//if($row['lid']!='1') continue;
	?>
	<tr>
	<td><?php echo $row['lid'];?></td>
	<td><?php echo $row['level_name'];?></td>
	<td>	
	<a href="user.php?type=levelinfo&id=<?php echo $row['lid'];?>" title="编辑"><img src="<?php echo $this->img('icon_edit.gif');?>" title="编辑"/></a>&nbsp;<?php //if($row['lid']!='11' && $row['lid']!='12'){?>
	</td>
	</tr>
	<?php
	 }
	 }
	  ?>
	 </table>
</div>

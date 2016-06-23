<div class="contentbox">
     <table cellspacing="2" cellpadding="5" width="100%">
	 <tr>
		<th colspan="15" align="left">列表</th>
	</tr>
    <tr>
	   <th width="60">编号</th>
	   <th width="">姓名</th>
	   <th width="">生日</th>
	   <th>顾客号</th>
	   <th>性别</th>
	   <th>地址</th>
	   <th>邮编</th>
	   <th>白天电话</th>
	   <th>晚上电话</th>
	   <th>手机</th>
	   <th>电子邮箱</th>
	   <th>来源</th>
	   <th>添加时间</th>
	   <th>目录类型</th>
	   <th>操作</th>
	</tr>
	<?php 
	if(!empty($rt)){
	foreach($rt as $row){
	?>
	<tr>
	<td><?php echo $row['mes_id'];?></td>
	<td><?php echo $row['user_name'];?></td>
	<td><?php echo $row['birthday'];?></td>
	<td><?php echo $row['user_no'];?></td>
	<td><?php echo $row['sex']=='1' ? '男' : ($row['sex']=='2' ? '女' : '保密') ;?></td>
<td><?php echo $row['province'];?><?php echo $row['city'];?><?php echo $row['district'];?><?php echo $row['address'];?></td>
<td><?php echo $row['postcode'];?></td>
<td><?php echo $row['dayphone'];?></td>
<td><?php echo $row['nightphone'];?></td>
<td><?php echo $row['mobile'];?></td>
<td><?php echo $row['email'];?></td>
<td><?php echo $row['ip_from'];?></td>
<td><?php echo date('Y-m-d H:i:s',$row['addtime']);?></td>
<td><?php echo $row['dir_ids'];?></td>
	<td>
	<img src="<?php echo $this->img('icon_drop.gif');?>" title="删除" alt="删除" id="<?php echo $row['mes_id'];?>" class="delcateid"/>
	</td>
	</tr>
			<?php } } ?>
	 </table>
</div>
<?php  $thisurl = ADMIN_URL.'goods.php'; ?>
<script type="text/javascript">

   $('.delcateid').click(function(){
   		ids = $(this).attr('id');
		thisobj = $(this).parent().parent();
		if(confirm("确定删除吗？")){
			createwindow();
			$.post('<?php echo $thisurl;?>',{action:'catalog_dels',ids:ids},function(data){
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
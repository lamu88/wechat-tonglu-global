<div class="contentbox">
     <table cellspacing="2" cellpadding="5" width="100%">
	 <tr>
	 		<th colspan="3" align="left" style="position:relative">公众号列表<!--<span style=" position:absolute; right:5px; top:3px"><a href="weixin.php?type=wxconfiginfo">添加公众号</a></span>--></th>
	 </tr>
	 <tr>
      <th>微信名称</th>
      <th>微信类型</th>
      <th>操作</th>
    </tr>
	<?php 
	if(!empty($rt))foreach($rt as $row){
	?>
       <tr>
      <td><?php echo $row['wxname'];?></td>
      <td><?php echo $row['winxintype']=='1' ? '订阅号' : ($row['winxintype']=='2' ? '服务号' : '高级服务号');?></td>
      <td align="right">       
	   <a href="weixin.php?type=wxconfiginfo&id=<?php echo $row['id'];?>">编辑</a> | <a href="weixin.php?type=wxconfigview&id=<?php echo $row['id'];?>">查看API</a> | <!--<a href="weixin.php?type=wxconfig&id=<?php echo $row['id'];?>" onclick="return confirm('确定吗')">删除</a>-->
	  </td>
    </tr>
   <?php } ?>
	 </table>
</div>
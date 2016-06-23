<div class="contentbox">
     <table cellspacing="2" cellpadding="5" width="100%">
	 <tr>
		<th colspan="6" align="left">开通分销的商品<span style="float:right"><a href="cuxiao.php?type=fenxiao_goods_info">添加商品</a></span></th>
	</tr>
    <tr>
	   <th width="70"><label><input type="checkbox" class="quxuanall" value="checkbox" />选择</label></th>
	   <th>名称</th>
	   <th>图片</th>
	   <th>价格</th>
	   <th>状态</th>
	   <th>操作</th>
	</tr>
	<?php 
	if(!empty($rt)){ 
	foreach($rt as $row){
	?>
	<tr>
	<td><input type="checkbox" name="quanxuan" value="<?php echo $row['group_id'];?>" class="gids"/></td>
    <td><?php echo $row['title'];?></td>
	<td><img src="<?php echo SITE_URL.$row['goods_thumb'];?>" style="width:80px" /></td>
	<td><?php echo $row['pifa_price'];?></td>
	<td><img src="<?php echo $this->img($row['is_show']==1 ? 'yes.gif' : 'no.gif');?>" alt="<?php echo $row['is_show']==1 ? '0' : '1';?>" /></td>
	<td>
	<a href="cuxiao.php?type=fenxiao_goods_info&id=<?php echo $row['id'];?>" title="编辑"><img src="<?php echo $this->img('icon_edit.gif');?>" title="编辑"/></a>&nbsp;
	<a href="cuxiao.php?type=fenxiao_goods&id=<?php echo $row['id'];?>" title="删除" onclick="return confirm('确定删除吗')"><img src="<?php echo $this->img('icon_drop.gif');?>" title="删除" alt="删除" id="<?php echo $row['id'];?>"/></a>
	</td>
	</tr>
	<?php
	 } ?>
	<tr>
		 <td colspan="6"> <input type="checkbox" class="quxuanall" value="checkbox" />
			  <input type="button" name="button" value="批量删除" disabled="disabled" class="bathdel" id="bathdel"/>
		 </td>
	</tr>
		<?php } ?>
	 </table>
	 <?php $this->element('page',array('pagelink'=>$pagelink));?>
</div>
<script type="text/javascript" language="javascript">
//全选
 $('.quxuanall').click(function (){
      if(this.checked==true){
         $("input[name='quanxuan']").each(function(){this.checked=true;});
		 document.getElementById("bathdel").disabled = false;
	  }else{
	     $("input[name='quanxuan']").each(function(){this.checked=false;});
		 document.getElementById("bathdel").disabled = true;
	  }
  });
  
  //是删除按钮失效或者有效
  $('.gids').click(function(){ 
  		var checked = false;
  		$("input[name='quanxuan']").each(function(){
			if(this.checked == true){
				checked = true;
			}
		}); 
		document.getElementById("bathdel").disabled = !checked;
  });
 </script>
<div class="contentbox">
     <table cellspacing="2" cellpadding="5" width="100%">
	 <tr>
		<th colspan="6" align="left">大牌惠商品<span style="float:right"><a href="cuxiao.php?type=dph_info">添加商品</a></span></th>
	</tr>
	<tr><td colspan="6" align="left" class="label">
    	<img src="<?php echo $this->img('icon_search.gif');?>" alt="SEARCH" width="26" border="0" height="22" align="absmiddle">
    	 <select name="is_goods_attr">
			 <option value="">全部</option>
		 </select>
		 
    	关键字 <input name="keyword" size="15" type="text" value="<?php echo isset($_GET['keyword']) ? $_GET['keyword'] : "";?>">
    	<input value=" 搜索 " class="cate_search" type="button">
	</td></tr>
    <tr>
	   <th width="70"><label><input type="checkbox" class="quxuanall" value="checkbox" />选择</label></th>
	   <th>名称</th>
	   <th>开始时间</th>
	   <th>结束时间</th>
	   <th>活动状态</th>
	   <th>操作</th>
	</tr>
	<?php 
	if(!empty($rt)){ 
	foreach($rt as $row){
	?>
	<tr>
	<td><input type="checkbox" name="quanxuan" value="<?php echo $row['group_id'];?>" class="gids"/></td>
    <td><?php echo $row['title'];?></td>
	<td><?php echo date('Y-m-d',$row['start_time']);?></td>
	<td><?php echo date('Y-m-d',$row['end_time']);?></td>
	<td><?php 
	$pr = ($row['start_time']< mktime()&&$row['end_time'] > mktime()) ? 1 : 0;
	echo $pr==0 ? "<font color=red>结束</font>" : ($row['is_show']==0 ? "<font style='color:#6633FF'>活动无开启</font>" : "进行中");
	?></td>
	<td>
	<a href="cuxiao.php?type=dph_info&id=<?php echo $row['id'];?>" title="编辑"><img src="<?php echo $this->img('icon_edit.gif');?>" title="编辑"/></a>&nbsp;
	<a href="cuxiao.php?type=dapaihui&id=<?php echo $row['id'];?>" title="删除" onclick="return confirm('确定删除吗')"><img src="<?php echo $this->img('icon_drop.gif');?>" title="删除" alt="删除" id="<?php echo $row['id'];?>"/></a>
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
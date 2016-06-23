<div class="contentbox">
<form action="" method="post" enctype="multipart/form-data" name="theForm" id="theForm">
 <div class="menu_content">
 	<!--start 通用信息-->
	 <table cellspacing="2" cellpadding="5" width="100%" id="tab1">
	 	
	 	<tr>
			<th colspan="2" align="left"><span style="float:left"><?php echo $id > 0 ? '修改推荐' : '添加推荐';?></span><a href="goods.php?type=goods_tuijian" style="float:right">返回列表</a></th>
		</tr>
		<tr>
			<td class="label" width="150">关联产品:</td>
			<td>
			<select name="goods_id">
			<?php if(!empty($rt['goods_name'])){?>
			<option value="<?php echo $rt['goods_id'];?>"><?php echo $rt['goods_name'];?></option>
			<?php }else{?>
			<option value="0">选择商品</option>
			<?php } ?>
		    </select>
			【关键字搜索
			<input type="text" class="searchval" style="width:100px; border:1px solid #330066" />
	 		<input type="button" value=" 搜索 "  style="cursor:pointer" onclick="ajax_goods_name(this)"/>】
		  </td>
		</tr>
		<tr>
		<td class="label" width="150">介绍详情:</td>
		<td><textarea name="goods_desc" id="content" style="width:95%;height:400px;display:none;"><?php echo isset($rt['goods_desc']) ? $rt['goods_desc'] : '';?></textarea>
		<script>KE.show({id : 'content',cssPath : '<?php echo ADMIN_URL.'/css/edit.css';?>'});</script>
		</td>
	  </tr>
	 </table>
	 <!--end 通用信息-->
	 	 
	
	
	  <p style="text-align:center">
		<input class="new_save" value="<?php echo $type=='newedit' ? '修改' : '添加';?>保存" type="Submit" style="cursor:pointer" />
		<div class="clear"></div>
	  </p>
 </div> 
  </form>
</div>

<?php  $thisurl = ADMIN_URL.'goods.php'; ?>
<script type="text/javascript">
<!--
function ajax_goods_name(obj){
	va = $(obj).parent().find('.searchval').val();
	$.post('<?php echo $thisurl;?>',{action:'ajax_goods_name',searchval:va},function(data){
		if(data == ""){
			alert("未找到！");
		}else{
			$(obj).parent().find('select').html(data);
		}
	});
}
-->
</script>

	  
	  
	  <!-------------- look修改  结束-------------- ------------------------------------------------->

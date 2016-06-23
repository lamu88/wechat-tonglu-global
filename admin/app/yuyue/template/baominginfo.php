<div class="contentbox">
<form action="" method="post" enctype="multipart/form-data" name="theForm" id="theForm">
 <div class="menu_content">
 	<!--start 通用信息-->
	 <table cellspacing="2" cellpadding="5" width="100%" id="tab1">
	 	
	 	<tr>
			<th colspan="2" align="left"><span style="float:left"><?php echo $id > 0 ? '修改信息' : '添加信息';?></span><a href="yuyue.php?type=baominglist" style="float:right">返回列表</a></th>
		</tr>
		<tr>
		<td class="label">标题:</td>
		<td><input name="title" id="title"  type="text" size="43" value="<?php echo isset($rt['title']) ? $rt['title'] : '';?>"></td>
	  </tr>
	  <tr>
		<td class="label">支付价格:</td>
		<td><input name="price" id="price"  type="text" size="43" value="<?php echo isset($rt['price']) ? $rt['price'] : '0.00';?>">元</td>
	  </tr>
	  <tr>
		<td class="label">图片:</td>
		<td>
		  <?php if(isset($rt['img'])){ ?><img src="<?php echo !empty($rt['img']) ? SITE_URL.$rt['img'] : $this->img('no_picture.gif');?>" width="100" style="padding:1px; border:1px solid #ccc"/><?php } ?>
		  <input name="img" id="baomingimg" type="hidden" value="<?php echo isset($rt['img']) ? $rt['img'] : '';?>"/>
		  <iframe id="iframe_t" name="iframe_t" border="0" src="upload.php?action=<?php echo isset($rt['img'])&&!empty($rt['img'])? 'show' : '';?>&ty=baomingimg&files=<?php echo isset($rt['img']) ? $rt['img'] : '';?>" scrolling="no" width="445" frameborder="0" height="25"></iframe>
		</td>
	  </tr>
		<script type="text/javascript">
			KindEditor.ready(function(K) {
				K.create('#content', {
					themeType : 'default'
				});
			});
		</script>
		<tr>
		<td class="label" width="150">介绍详情:</td>
		<td><textarea name="content" id="content" style="width:95%;height:400px;display:none;"><?php echo isset($rt['content']) ? $rt['content'] : '';?></textarea>
		</td>
	  </tr>
	 </table>
	 <!--end 通用信息-->
	 	 
	
	
	  <p style="text-align:center">
		<input class="new_save" value="<?php echo $type=='newedit' ? '修改' : '添加';?>保存" type="Submit" style="cursor:pointer" onclick="return checkvar();" />
		<div class="clear"></div>
	  </p>
 </div> 
  </form>
</div>

<?php  $thisurl = ADMIN_URL.'yuyue.php'; ?>
<script type="text/javascript">
<!--
function checkvar(){
	ti = $('input[name="title"]').val();
	if(ti=="" || typeof(ti)=='undefined'){
		alert("标题不能为空");
		return false;
	}
	return true;
}

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

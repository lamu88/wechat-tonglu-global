<div class="contentbox">
<form id="form1" name="form1" method="post" action="">
     <table cellspacing="2" cellpadding="5" width="100%">
	 <tr>
		<th colspan="2" align="left"><?php echo $type=='edit' ? '修改' : '添加';?>抢购</th>
	</tr>
	<tr>
	   <td class="label">查找关联商品</td>
	   <td><input type="hidden" name="goods_id" value="<?php echo isset($rt['goods_id']) ? $rt['goods_id'] : '0';?>" />
		 <img src="<?php echo $this->img('icon_search.gif');?>" alt="SEARCH" width="26" border="0" height="22" align="absmiddle">
		 <a href="javascript:;" style="padding:5px; border-bottom:1px solid #ccc;border-right:1px solid #ccc; background:#ededed; color:#FF0000" onclick="return open_select_goods()">选择商品</a>
		 <span class="return_img">
		 <?php
		 $img = isset($rt['img'])&&!empty($rt['img']) ? SITE_URL.$rt['img'] : (isset($rt['goods_thumb'] ) ? SITE_URL.$rt['goods_thumb'] : '');
		 if(!empty($img)){
		 echo '<img src="'.$img.'" style="width:90px;padding:1px;border:1px solid #ccc" alt="" />';
		 }
		 ?>
		 </span>		 
		 </td>
	</tr>
		
	<tr>
		<td class="label" width="150">标题:</td>
		<td><input name="title" size="50" value="<?php echo isset($rt['title']) ? $rt['title'] : '';?>" type="text"><span class="require-field">*</span>
		</td>
	  </tr>
	  
	  <tr>
		<td class="label">抢时:</td>
		<td>
		  <select name="time_h">
		  <option value="0">选择时间</option>
		  <?php
		  for($i=1;$i<=24;$i++){
		  echo '<option'.(isset($rt['time_h'])&&$i==$rt['time_h'] ? ' selected="selected"' : '').' value="'.$i.'">'.($i<10 ? '0'.$i : $i).'</option>';
		  }
		  ?>
	      </select>		
		  </td>
	  </tr>
	  
	  <tr>
		<td class="label">封面:</td>
		<td>
		  <input name="img" id="img" type="hidden" value="<?php echo isset($rt['img']) ? $rt['img'] : '';?>" size="43"/>
		  <iframe id="iframe_t" name="iframe_t" border="0" src="upload.php?action=<?php echo isset($rt['img'])&&!empty($rt['img'])? 'show' : '';?>&ty=img&tyy=qianggou&files=<?php echo isset($rt['img']) ? $rt['img'] : '';?>" scrolling="no" width="445" frameborder="0" height="25"></iframe>
		  <span class="notice-span" style="display: block;" id="warn_brandlogo">
			请上传图片，作为产品封面图片！</span>
		</td>
	  </tr>
	  <tr>
		<td class="label">是否显示:</td>
		<td>
		<input name="is_show" value="1" <?php echo !isset($rt['is_show']) || $rt['is_show']==1 ? 'checked="checked"' : '';?> type="radio"> 是       
		<input name="is_show" value="0" <?php echo isset($rt['is_show'])&&$rt['is_show']==0 ? 'checked="checked"' : '';?>type="radio"> 否 
		</td>
	  </tr>
	 
	  <tr>
		<td class="label">&nbsp;</td>
		<td>
		<input class="new_save" value="<?php echo $type=='edit' ? '修改' : '添加';?>保存" type="Submit">
		</td>
	  </tr>
	 </table>
	 </form>
</div>

<script type="text/javascript">
function setrun(gname,gid,img){
	$('input[name="goods_id"]').val(gid);
	str = '<img src="'+img+'" style="width:90px;padding:1px;border:1px solid #ccc" alt="'+gname+'" />';
	$('.return_img').html(str);
}

function open_select_goods(){
	JqueryDialog.Open('','<?php echo ADMIN_URL;?>selectgoods.php',600,350,'frame');
	return false;
}
</script>
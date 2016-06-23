<div class="contentbox">
<form id="form1" name="form1" method="post" action="">
     <table cellspacing="2" cellpadding="5" width="100%">
	 <tr>
		<th colspan="2" align="left"><?php echo $type=='edit' ? '修改' : '添加';?>商品品牌</th>
	</tr>
	<tr>
		<td class="label" width="150">品牌名称:</td>
		<td><input name="brand_name" id="brand_name" maxlength="60" value="<?php echo isset($rt['brand_name']) ? $rt['brand_name'] : '';?>" type="text"><span class="require-field">*</span>
		<span class="brand_name_mes">
		</span></td>
	  </tr>
	<tr>
    <td class="label">品牌标题:</td>
    <td><input name="brand_title" size="50" value="<?php echo isset($rt['brand_title']) ? $rt['brand_title'] : '';?>" type="text"></td>
    </tr>
	 
	<tr>
    <td class="label">上级分类:</td>
    <td>
      <select name="parent_id">
	    <option value="0">顶级分类</option>
		<?php 
		if(!empty($brandlist)){
		 foreach($brandlist as $row){ 
		 	if($type=='edit' && $rt['brand_name']==$row['name']) continue;
		?>
        <option value="<?php echo $row['id'];?>"  <?php echo isset($rt['parent_id'])&&$row['id']==$rt['parent_id'] ? 'selected="selected"' : '';?>><?php echo $row['name'];?></option>
			<?php 
				if(!empty($row['brand_id'])){
				foreach($row['brand_id'] as $rows){ 
					if($type=='cateedit' && $rt['brand_name']==$rows['name']) continue;
					?>
					<option value="<?php echo $rows['id'];?>"  <?php echo isset($rt['parent_id'])&&$rows['id']==$rt['parent_id'] ? 'selected="selected"' : '';?>>&nbsp;&nbsp;<?php echo $rows['name'];?></option>
					<?php 
					if(!empty($rows['brand_id'])){
					foreach($rows['brand_id'] as $rowss){ 
						if($type=='edit' && $rt['brand_name']==$rowss['name']) continue;
					?>
							<option value="<?php echo $rowss['id'];?>"  <?php echo isset($rt['parent_id'])&&$rowss['id']==$rt['parent_id'] ? 'selected="selected"' : '';?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $rowss['name'];?></option>
							
					<?php
					}//end foreach
					}//end if
					?>
			<?php
				}//end foreach
		 		} // end if
			?>
		<?php
		 }//end foreach
		} ?>
	 </select>
    </td>
  </tr>
  	<tr>
    <td class="label">关联产品分类:</td>
    <td>
      <select name="cat_id">
	    <option value="0">顶级分类</option>
		<?php 
		if(!empty($catelist))foreach($catelist as $row){ 
		?>
        <option value="<?php echo $row['id'];?>"  <?php echo isset($rt['cat_id'])&&$row['id']==$rt['cat_id'] ? 'selected="selected"' : '';?>><?php echo $row['name'];?></option>
		<?php
		} ?>
	 </select>
    </td>
  </tr>
  
<!--	  <tr>
		<td class="label">品牌网址:</td>
		<td><input name="site_url" maxlength="60" size="40" value="<?php echo isset($rt['site_url']) ? $rt['site_url'] : '';?>" type="text"></td>
	
	  </tr>-->
	  <tr>
		<td class="label">品牌LOGO:</td>
		<td>
		  <input name="brand_logo" id="brandlogo" type="hidden" value="<?php echo isset($rt['brand_logo']) ? $rt['brand_logo'] : '';?>" size="43"/>
		  <iframe id="iframe_t" name="iframe_t" border="0" src="upload.php?action=<?php echo isset($rt['brand_logo'])&&!empty($rt['brand_logo'])? 'show' : '';?>&ty=brandlogo&tyy=brand&files=<?php echo isset($rt['brand_logo']) ? $rt['brand_logo'] : '';?>" scrolling="no" width="445" frameborder="0" height="25"></iframe>
		  <br><span class="notice-span" style="display: block;" id="warn_brandlogo">
			请上传图片，做为品牌的LOGO！</span>
		</td>
	  </tr>
	  <!--<tr>
		<td class="label">品牌Banner:</td>
		<td>
		  <input name="brand_banner" id="brandbanner" type="hidden" value="<?php echo isset($rt['brand_banner']) ? $rt['brand_banner'] : '';?>" size="43"/>
		  <iframe id="iframe_t" name="iframe_t" border="0" src="upload.php?action=<?php echo isset($rt['brand_banner'])&&!empty($rt['brand_banner'])? 'show' : '';?>&ty=brandbanner&tyy=brand&files=<?php echo isset($rt['brand_banner']) ? $rt['brand_banner'] : '';?>" scrolling="no" width="445" frameborder="0" height="25"></iframe>
		  <br><span class="notice-span" style="display: block;" id="warn_brandlogo">
			请上传图片，做为品牌的Banner！</span>
		</td>
	  </tr>
	  <tr>
		<td class="label">品牌描述:</td>
		<td><textarea name="brand_desc" id="content" style="width:95%;height:500px;display:none;"><?php echo isset($rt['brand_desc']) ? $rt['brand_desc'] : '';?></textarea>
		<script>KE.show({id : 'content',cssPath : '<?php echo ADMIN_URL.'/css/edit.css';?>'});</script>
		</td>
	  </tr>-->
	  <tr>
		<td class="label">排序:</td>
	
		<td><input name="sort_order" size="15" value="<?php echo isset($rt['sort_order']) ? $rt['sort_order'] : '50';?>" type="text"></td>
	  </tr>
	 <!-- <tr>
		<td class="label">首页商品数:</td>
	
		<td><input name="index_show_count" size="15" value="<?php echo isset($rt['index_show_count']) ? $rt['index_show_count'] : '10';?>" type="text"></td>
	  </tr>-->
	  <tr>
		<td class="label">是否显示:</td>
		<td>
		<input name="is_show" value="1" <?php echo !isset($rt['is_show']) || $rt['is_show']==1 ? 'checked="checked"' : '';?> type="radio"> 是       
		<input name="is_show" value="0" <?php echo isset($rt['is_show'])&&$rt['is_show']==0 ? 'checked="checked"' : '';?>type="radio"> 否 
		</td>
	  </tr>
	  <tr>
		<td class="label">Meta关键字:</td>
		<td><textarea name="meta_keys" id="meta_keys" style="width: 60%; height: 65px; overflow: auto;"><?php echo isset($rt['meta_keys']) ? $rt['meta_keys'] : '';?></textarea></td>
	  </tr>
	  <tr>
		<td class="label">Meta描述:</td>
		<td><textarea name="meta_desc" id="meta_desc" style="width: 60%; height: 65px; overflow: auto;"><?php echo isset($rt['meta_desc']) ? $rt['meta_desc'] : '';?></textarea></td>
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
<?php
$thisurl = ADMIN_URL.'brand.php'; 
?>
<script type="text/javascript">
<!--
//jQuery(document).ready(function($){
	$('.new_save').click(function(){
		b_name = $('#brand_name').val();
		if(b_name=='undefined' || b_name==""){
			$('.brand_name_mes').html("标题不能为空！");
			$('.brand_name_mes').css('color','#FE0000');
			return false;
		}
		return true;
	});
	
	/*增删子分类控件*/
	$('.addsubcate').live('click',function(){
		str = $(this).parent().parent().html();
		str = str.replace('addsubcate','removesubcate');
		str = str.replace('点击[+]增加一个','点击[-]减少一个');
		str = str.replace(/cat_id/g,'sub_cat_id[]');
		str= str.replace('[+]','[-]');
		$(this).parent().parent().after('<tr>'+str+'</tr>');
	});
	
	$('.removesubcate').live('click',function(){
		$(this).parent().parent().remove();
		return false;
	});
	
	$('.delbrandtype').click(function(){
		if(confirm("确定删除吗？")){
			id = $(this).attr('id');
			thisobj = $(this);
			$.post('<?php echo $thisurl;?>',{action:'brand_type_del',id:id},function(data){ 
				if(data == ""){
					$(thisobj).parent().parent().hide(200);
				}else{
					alert(data);
				}
			});
		}
	});
//});
-->
</script>

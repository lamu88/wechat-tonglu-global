<div class="contentbox">
<form id="form1" name="form1" method="post" action="">
<table cellspacing="2" cellpadding="5" width="100%">
	 <tr>
		<th colspan="2" align="left"><span style="float:left"><?php echo $id>0 ? '修改' : '添加';?>分类</span><span style="float:right"><a href="weixin.php?type=catelist">返回分类列表</a></span></th>
	</tr>
	<tr>
    <td class="label">分类名称:</td>
    <td><input name="cat_name" id="cat_name"  maxlength="60" size="30" value="<?php echo isset($rt['cat_name']) ? $rt['cat_name'] : '';?>" type="text"><span class="require-field">*</span><span class="cat_name_mes"></span></td>
  </tr>
  <tr>
    <td class="label">分类描述:</td>
    <td><textarea name="cat_about" cols="60" rows="4"><?php echo isset($rt['cat_about']) ? $rt['cat_about'] : '';?></textarea></td>
  </tr>
   <tr>
		<td class="label">分类图片:</td>
		<td>
		  <input name="cat_img" id="catimg" type="hidden" value="<?php echo isset($rt['cat_img']) ? $rt['cat_img'] : '';?>" size="43"/>
		  <iframe id="iframe_t" name="iframe_t" border="0" src="upload.php?action=<?php echo isset($rt['cat_img'])&&!empty($rt['cat_img'])? 'show' : '';?>&ty=catimg&files=<?php echo isset($rt['cat_img']) ? $rt['cat_img'] : '';?>" scrolling="no" width="445" frameborder="0" height="25"></iframe>
		</td>
	  </tr>
  <tr>
    <td class="label">上级分类:</td>
    <td>
      <select name="parent_id">
	    <option value="0">顶级分类</option>
		<?php 
		if(!empty($catelist)){
		 foreach($catelist as $row){ 
		 	if($id>0 && $rt['cat_name']==$row['name']) continue;
		?>
        <option value="<?php echo $row['id'];?>"  <?php echo isset($rt['parent_id'])&&$row['id']==$rt['parent_id'] ? 'selected="selected"' : '';?>><?php echo $row['cat_name'];?></option>
			<?php 
				if(!empty($row['cat_id'])){
				foreach($row['cat_id'] as $rows){ 
					if($id>0 && $rt['cat_name']==$rows['cat_name']) continue;
					?>
					<option value="<?php echo $rows['id'];?>"  <?php echo isset($rt['parent_id'])&&$rows['id']==$rt['parent_id'] ? 'selected="selected"' : '';?>>&nbsp;&nbsp;<?php echo $rows['cat_name'];?></option>
					<?php 
					if(!empty($rows['cat_id'])){
					foreach($rows['cat_id'] as $rowss){ 
						if($id>0 && $rt['cat_name']==$rowss['cat_name']) continue;
					?>
							<option value="<?php echo $rowss['id'];?>"  <?php echo isset($rt['parent_id'])&&$rowss['id']==$rt['parent_id'] ? 'selected="selected"' : '';?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $rowss['cat_name'];?></option>
					<?php
					}//end foreach
					}//end if
				}//end foreach
		 		} // end if
		 }//end foreach
		} ?>
	 </select>
    </td>
  </tr>
   <tr>
  <td class="label">排序:</td>
  <td><input name="vieworder" maxlength="60" size="50" value="<?php echo isset($rt['vieworder']) ? $rt['vieworder'] : '50';?>" type="text"></td>
  </tr>
  <tr>
    <tr>
    <td class="label">状态:</td>
    <td>
      <label>
        <input type="checkbox" name="is_show" value="1" <?php echo !isset($rt['is_show']) || $rt['is_show']==1 ? 'checked="checked"' : '';?> />审核
      </label>
    </td>
  </tr>
  <tr>
    <td colspan="2" align="center"><br>
	  <input value="<?php echo isset($rt['cat_id']) ? $rt['cat_id'] : '';?>" type="hidden" class="cat_id"/>
      <input class="new_save" value=" 确定 " type="submit">
      <input class="button" value=" 重置 " type="reset">
    </td>
  </tr>
  </table>
 </form>
</div>
<?php  $thisurl = ADMIN_URL.'con_new.php'; ?>
<script type="text/javascript">
<!--
jQuery(document).ready(function($){
	$('.new_save').click(function(){
		cname = $('#cat_name').val();
		if(typeof(cname)=="undefined" || cname==""){
		    $('.cat_name_mes').html("分类名称不能为空！");
			$('.cat_name_mes').css('color','#FE0000');
			return false;
		}
		cid = $('.cat_id').val();
		type = $('.types').val(); 
		$.post('<?php echo $thisurl;?>',{action:'check_cat_name',cat_id:cid,cat_name:cname,type:type},function(data){
			if(data !=""){ 
				$('.cat_name_mes').html(data);
				$('.cat_name_mes').css('color','#FE0000');
			}else{
				$('.cat_name_mes').html("");
				$('#form1').submit();
			}
		});
		return false;
	});
});
-->
</script>
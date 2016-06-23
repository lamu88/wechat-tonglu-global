<div class="contentbox">
<form id="form1" name="form1" method="post" action="">
<table cellspacing="2" cellpadding="5" width="100%">
	 <tr>
		<th colspan="2" align="left"><?php echo $type=='edit' ? '修改' : '添加';?>案例颜色分类</th>
	</tr>
	<tr>
    <td class="label">颜色分类名称:</td>
    <td><input name="cat_name" id="cat_name"  maxlength="60" size="30" value="<?php echo isset($rt['cat_name']) ? $rt['cat_name'] : '';?>" type="text"><span class="require-field">*</span><span class="cat_name_mes"></span></td>
  </tr>
    <tr>
    <td class="label">颜色分类标题:</td>
    <td><input name="cat_title" maxlength="60" size="50" value="<?php echo isset($rt['cat_title']) ? $rt['cat_title'] : '';?>" type="text"></td>
  </tr>
	   <tr>
    <td class="label">颜色分类简介:</td>
    <td><textarea name="cat_about" cols="60" rows="4"><?php echo isset($rt['cat_about']) ? $rt['cat_about'] : '';?></textarea></td>
  </tr>
    <tr>
  <td class="label">排序:</td>
  <td><input name="vieworder" maxlength="60" size="50" value="<?php echo isset($rt['vieworder']) ? $rt['vieworder'] : '50';?>" type="text"></td>
  </tr>
  <tr>
    <td class="label">Meta关键字:</td>
    <td><input name="meta_keys" maxlength="60" size="50" value="<?php echo isset($rt['meta_keys']) ? $rt['meta_keys'] : '';?>" type="text">
    <br><span style="display: block;" id="notice_keywords">关键字为选填项，其目的在于方便外部搜索引擎搜索</span>
    </td>
  </tr>
  <tr>
    <td class="label">Meta描述:</td>
    <td><textarea name="meta_desc" cols="60" rows="4"><?php echo isset($rt['meta_desc']) ? $rt['meta_desc'] : '';?></textarea></td>
  </tr>
  <tr>
  	<td>&nbsp;</td>
    <td align="left"><br>
	  <input value="<?php echo isset($rt['cat_id']) ? $rt['cat_id'] : '';?>" type="hidden" class="cat_id"/>
      <input class="new_save" value=" 确定 " type="submit">
      <input class="button" value=" 重置 " type="reset">
    </td>
  </tr>
  </table>
</form>
</div>
<?php  $thisurl = ADMIN_URL.'con_case.php'; ?>
<script type="text/javascript">
<!--
jQuery(document).ready(function($){
	$('.new_save').click(function(){
		cname = $('#cat_name').val();
		if(typeof(cname)=="undefined" || cname==""){
		    $('.cat_name_mes').html("颜色分类名称不能为空！");
			$('.cat_name_mes').css('color','#FE0000');
			return false;
		}
		cid = $('.cat_id').val();
		$.post('<?php echo $thisurl;?>',{action:'check_color_cat_name',cat_id:cid,cat_name:cname},function(data){
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
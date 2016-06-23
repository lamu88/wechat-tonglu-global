<div class="contentbox">
<form id="form1" name="form1" method="post" action="">
     <table cellspacing="2" cellpadding="5" width="100%">
	 	 <tr>
			<th colspan="2" align="left"><?php echo $id>0 ? '修改' : '添加';?>自定义导航</th>
		</tr>
		<tr>
			<td class="label" width="15%">名称:</td> <td><input name="name" value="<?php echo isset($rt['name']) ? $rt['name'] : '';?>" id="name" size="70" type="text"><span class="name_mes"></span></td>
		</tr>
		<tr>
			<td class="label">链接地址:</td> <td><input name="url" value="<?php echo isset($rt['url']) ? $rt['url'] : '';?>" id="url" size="70" type="text"><a href="javascript:;" style="padding:3px 5px 3px 5px; border-bottom:1px solid #ccc;border-right:1px solid #ccc; background:#ededed" onclick="return open_select_url()">从库选择</a><br/><em>前面必须带"htpp://"</em></td>
		</tr>
		<tr>
			<td class="label">图标:</td> <td>
			<input name="img" value="<?php echo isset($rt['img']) ? $rt['img'] : '';?>" id="url" size="70" type="text"><a href="javascript:;" style="padding:3px 5px 3px 5px; border-bottom:1px solid #ccc;border-right:1px solid #ccc; background:#ededed" onclick="return open_select_img()">从库选择</a></td>
		</tr>
		<tr>
			<td class="label">是否显示:</td> <td>
			<select name="is_show">
		  <option value="1" <?php echo !isset($rt['is_show']) || $rt['is_show']==1 ? 'selected="selected"' : '';?>>是</option><option value="0" <?php echo isset($rt['is_show'])&&$rt['is_show']==0 ? 'selected="selected"' : '';?>>否</option>
		  </select></td>
		</tr>
		<tr>
		<td class="label">位置:</td> <td>
		<select name="type">
		  <option value='top' <?php echo isset($rt['type'])&&$rt['type']=='top' ? 'selected="selected"' : '';?>>顶部</option>
		  <option value="middle" <?php echo !isset($rt['type']) || $rt['type']=='middle' ? 'selected="selected"' : '';?>>中间</option>
		  <option value="bottom" <?php echo isset($rt['type'])&&$rt['type']=='bottom' ? 'selected="selected"' : '';?>>底部 </option>
		  </select></td>
		</tr>
	  <tr>
		<td class="label">&nbsp;</td>
		<td>
		<input class="nav_save" value="<?php echo $type=='nav_edit' ? '修改' : '添加';?>保存" type="Submit">
		</td>
	  </tr>
	 </table>
	 </form>
</div>
<script type="text/javascript">
function setrun(url){
	$('input[name="url"]').val(url);
}

function open_select_url(){
	JqueryDialog.Open('','<?php echo ADMIN_URL;?>selecturl.php',600,350,'frame');
	return false;
}

function setrunimg(url){
	$('input[name="img"]').val(url);
	//$('.img').attr('src','<?php echo SITE_URL;?>'+url);
}

function open_select_img(){
	JqueryDialog.Open('','<?php echo ADMIN_URL;?>selectimg.php',600,350,'frame');
	return false;
}
</script>
<script type="text/javascript">
<!--
	$('.nav_save').click(function(){
		cname = $('#name').val();
		if(cname=='undefined' || cname==""){
			$('.name_mes').html('名称不能为空！');
			$('.name_mes').css('color','#FE0000');
			return false;
		}
		return true;
	});
-->
</script>
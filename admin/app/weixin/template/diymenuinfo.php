<style type="text/css">
.cLineB {
overflow: hidden;
padding: 8px 0;
border-bottom: 1px solid #EEEEEE;
}
.contentbox .cLineB h4 {
font-size: 16px; padding:5px; margin:0px;
}
.contentbox .cLineB button a{ color:#fff} 
.btnGreen {
border: 1px solid #FFFFFF;
box-shadow: 0 1px 1px #0A8DE4;
-moz-box-shadow: 0 1px 1px #0A8DE4;
-webkit-box-shadow: 0 1px 1px #0A8DE4;
padding: 5px 20px;
cursor: pointer;
display: inline-block;
text-align: center;
vertical-align: bottom;
overflow: visible;
border-radius: 3px;
-moz-border-radius: 3px;
-webkit-border-radius: 3px;
background-color: #5ba607;
background-image: linear-gradient(bottom, #107BAD 3%, #18C2D1 97%, #18C2D1 100%);
background-image: -moz-linear-gradient(bottom, #107BAD 3%, #0A8DE40 97%, #18C2D1 100%);
background-image: -webkit-linear-gradient(bottom, #107BAD 3%,#0A8DE4 97%, #18C2D1 100%);
color: #fff;
font-size: 14px;
line-height: 1.5;
}
.right {
float: right; margin-right:5px;
}
.pw,.pwt{
height:24px; line-height:24px;
border: 1px solid #ddd;
border-radius: 5px;
background-color: #fff; padding-left:5px; padding-right:5px;
-moz-border-radius:5px;/*仅Firefox支持，实现圆角效果*/
-webkit-border-radius:5px;/*仅Safari,Chrome支持，实现圆角效果*/
-khtml-border-radius:5px;/*仅Safari,Chrome支持，实现圆角效果*/
border-radius:5px;/*仅Opera，Safari,Chrome支持，实现圆角效果*/
}
.btn{ padding:3px; cursor:pointer}
</style>

<div class="contentbox">
  <div class="cLineB">
  <h4><span class=""></span></h4>
  <a style="float:right; margin-right:5px; color:#FFF" class="btnGreen " href="weixin.php?type=diymenu" title="返回菜单">返回菜单</a>
  </div>
<form action="" method="post" id="realinfo_form">
	<table border="0" cellpadding="0" cellspacing="0" width="100%">
		<tbody>
		<tr bgcolor="#F1F1F1">
			<td align="right" height="40" width="" style="border-top:1px dotted #B4C9C6">父级菜单：</td>
			<td style="border-top:1px dotted #B4C9C6">
				<div class="mr15 l">
				<select name="parent_id" id="parent_id" class="pwt">
					<option value="0">请选择菜单：</option>
					<?php if(!empty($rt['nav']))foreach($rt['nav'] as $row){
						if($id>0&&$row['id']==$rt['id']) continue;
					?>
					<option value="<?php echo $row['id'];?>"<?php echo isset($rt['parent_id'])&&$row['id']==$rt['parent_id'] ? ' selected="selected"' : '';?>><?php echo $row['title'];?></option>			
						<?php 
						if(!empty($row['cat_id']))foreach($row['cat_id'] as $rows){ 
						if($id>0&&$rows['id']==$rt['id']) continue;
						?>
						<option value="<?php echo $rows['id'];?>"<?php echo isset($rt['parent_id'])&&$rows['id']==$rt['parent_id'] ? ' selected="selected"' : '';?>>&nbsp;&nbsp;<?php echo $rows['title'];?></option>
						<?php } ?>
					<?php } ?>
				</select>
				</div>
			</td>
		</tr>
		<tr>
			<td align="right" height="40" width="">主菜单名称：</td>
			<td>
				<div class="mr15 l">
				<input id="menu_title" class="pw" name="title" title="主菜单名称" value="<?php echo isset($rt['title']) ? $rt['title'] : '';?>" type="text"></div>
				<div class="system l"></div>
			</td>
		</tr>					
		<tr bgcolor="#F1F1F1">
			<td align="right" height="40" width="">关联关键词：</td>
			<td>
				<div class="mr15 l"><input id="menu_keyword" class="pw" name="keyword" title="关联关键词" value="<?php echo isset($rt['keyword']) ? $rt['keyword'] : '';?>" type="text"></div>
				<div class="system l"></div>
			</td>
		</tr>
		<tr>
			<td align="right" height="40" width="">外链接url：</td>
			<td style="padding-top:5px;">
				<div class="mr15 l"><input id="menu_key" class="pw" name="url" size="60" title="外链接url" value="<?php echo isset($rt['url']) ? $rt['url'] : '';?>" type="text"><a href="javascript:;" style="padding:5px; border-bottom:1px solid #ccc;border-right:1px solid #ccc; background:#ededed" onclick="return open_select_url()">从库选择</a><br/><em>前面必须带"htpp://"</em></div>
				<div class="system l"></div>
			</td>
		</tr>
		<tr bgcolor="#F1F1F1">
			<td align="right" height="40">显示：</td>
			<td>
				<div class="mr15 l">
				<input type="radio" name="is_show"<?php echo ($rt['is_show']==1 ||!isset($rt['is_show'])) ? ' checked="checked"' : '';?> value="1">是&nbsp;
				<input type="radio" name="is_show"<?php echo ($rt['is_show']==0&&isset($rt['is_show'])) ? ' checked="checked"' : '';?> value="0">否&nbsp;
				</div>
				<div class="system l"></div>
			</td>
		</tr>
		<tr>
			<td align="right" height="40">排序：</td>
			<td>
				<div class="mr15 l">
				<input id="sortid" class="pw" name="sort" title="排序" value="<?php echo isset($rt['sort']) ? $rt['sort'] : '50';?>" type="text"></div>
				<div class="system l"></div>
			</td>
		</tr>
		<tr bgcolor="#F1F1F1">
			<td height="42">&nbsp;</td>
			<td>
				<input class="btn" type="submit" value="提交">
			</td>
		</tr>
			
	</tbody>
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
</script>
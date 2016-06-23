<div class="contentbox">
<form id="form1" name="form1" method="post" action="">
     <table cellspacing="2" cellpadding="5" width="100%">
	 <tr>
		<th colspan="2" align="left"><span style="float:left"><?php echo $id > 0 ? '修改' : '添加';?>文章</span><span style="float:right"><a href="weixin.php?type=wxnewlisttxt">返回内容列表</a></span></th>
	</tr>
	  <tr>
		<td class="label">文章标题:</td>
		<td><input name="article_title" id="article_title"  type="text" size="43" value="<?php echo isset($rt['article_title']) ? $rt['article_title'] : '';?>"><span style="color:#FF0000">*</span><span class="article_title_mes"></span></td>
	  </tr>
	   <tr>
		<td class="label">关键词:</td>
		<td><input name="keyword" id="keyword"  type="text" size="43" value="<?php echo isset($rt['keyword']) ? $rt['keyword'] : '';?>"></td>
	  </tr>	 
	  <tr>
		<td class="label">状态设置:</td>
		<td><input id="is_show" name="is_show" value="1" type="checkbox" <?php echo !isset($rt['is_show']) || $rt['is_show']==1 ? 'checked="checked"' : '';?>>审核</td>
	  </tr>
	  <tr>
		<td class="label">文章内容:</td>
		<td><textarea name="content" id="content" style="width:95%;height:200px;"><?php echo isset($rt['content']) ? $rt['content'] : '';?></textarea>
		</td>
	  </tr>
	  <tr>
		<td>&nbsp;</td>
		<td align="left">
		  <input type="hidden" name="type" value="txt" />
		<input class="new_save" value="<?php echo $type=='newedit' ? '修改' : '添加';?>保存" type="Submit" style="cursor:pointer">
		</td>
	  </tr>
	 </table>
	 </form>
</div>

<?php  $thisurl = ADMIN_URL.'weixin.php'; ?>
<script type="text/javascript">
<!--
//jQuery(document).ready(function($){
	$('.new_save').click(function(){
		art_title = $('#article_title').val();
		if(art_title=='undefined' || art_title==""){
			$('.article_title_mes').html("文章标题不能为空！");
			$('.article_title_mes').css('color','#FE0000');
			return false;
		}
		return true;
	});
//});
-->
</script>

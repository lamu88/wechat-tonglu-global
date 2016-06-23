<div class="contentbox">
<form id="form1" name="form1" method="post" action="">
     <table cellspacing="2" cellpadding="5" width="100%">
	 <tr>
		<th colspan="2" align="left"><span style="float:left"><?php echo $id > 0 ? '修改' : '添加';?>文章</span><span style="float:right"><a href="weixin.php?type=wxnewlist">返回内容列表</a></span></th>
	</tr>
	<tr>
		<td class="label" width="15%">所在分类:</td>
		<td width="85%">
		<select name="cat_id" id="cat_id">
		<?php 
		if(!empty($catids)){
		 foreach($catids as $row){ 
		?>
        <option value="<?php echo $row['id'];?>"  <?php echo $row['id']==$rt['cat_id'] ? 'selected="selected"' : '';?>><?php echo $row['cat_name'];?></option>
			<?php 
				if(!empty($row['cat_id'])){
				foreach($row['cat_id'] as $rows){ 
					?>
					<option value="<?php echo $rows['id'];?>"  <?php echo $rows['id']==$rt['cat_id'] ? 'selected="selected"' : '';?>>&nbsp;&nbsp;<?php echo $rows['cat_name'];?></option>
					<?php 
					if(!empty($rows['cat_id'])){
					foreach($rows['cat_id'] as $rowss){ 
					?>
							<option value="<?php echo $rowss['id'];?>"  <?php echo $rowss['id']==$rt['cat_id'] ? 'selected="selected"' : '';?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $rowss['cat_name'];?></option>
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
		<td class="label">文章标题:</td>
		<td><input name="article_title" id="article_title"  type="text" size="43" value="<?php echo isset($rt['article_title']) ? $rt['article_title'] : '';?>"><span style="color:#FF0000">*</span><span class="article_title_mes"></span></td>
	  </tr>
	   <tr>
		<td class="label">关键词:</td>
		<td><input name="keyword" id="keyword"  type="text" size="43" value="<?php echo isset($rt['keyword']) ? $rt['keyword'] : '';?>"></td>
	  </tr>
	  <tr>
		<td class="label">外部链接:</td>
		<td><input name="art_url" id="art_url"  type="text" size="43" value="<?php echo isset($rt['art_url']) ? $rt['art_url'] : '';?>"></td>
	  </tr>
	  <tr>
		<td class="label">图文:</td>
		<td>
		  <input name="article_img" id="articlephoto" type="hidden" value="<?php echo isset($rt['article_img']) ? $rt['article_img'] : '';?>" size="43"/>
		  <br />
		  <iframe id="iframe_t" name="iframe_t" border="0" src="upload.php?action=<?php echo isset($rt['article_img'])&&!empty($rt['article_img'])? 'show' : '';?>&ty=articlephoto&files=<?php echo isset($rt['article_img']) ? $rt['article_img'] : '';?>" scrolling="no" width="445" frameborder="0" height="25"></iframe>
		</td>
	  </tr>
	  <tr>
		<td class="label">文章描述:</td>
		<td><textarea name="about" id="meta_desc" style="width: 60%; height: 65px; overflow: auto;"><?php echo isset($rt['about']) ? $rt['about'] : '';?></textarea></td>
	  </tr>
	  <tr>
		<td class="label">状态设置:</td>
		<td><input id="is_show" name="is_show" value="1" type="checkbox" <?php echo !isset($rt['is_show']) || $rt['is_show']==1 ? 'checked="checked"' : '';?>>审核</td>
	  </tr>
	  <tr>
		<td class="label">文章内容:</td>
		<td><textarea name="content" id="content" style="width:95%;height:500px;display:none;"><?php echo isset($rt['content']) ? $rt['content'] : '';?></textarea>
		<script>KE.show({id : 'content',cssPath : '<?php echo ADMIN_URL.'/css/edit.css';?>'});</script>
		</td>
	  </tr>
	  <tr>
		<td>&nbsp;</td>
		<td align="left">
		  <input type="hidden" name="type" value="img" />
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

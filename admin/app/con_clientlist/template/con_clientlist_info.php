<div class="contentbox">
<form id="form1" name="form1" method="post" action="">
     <table cellspacing="2" cellpadding="5" width="100%">
	 <tr>
		<th colspan="2" align="left"><?php echo $type=='newedit' ? '修改' : '添加';?>文章列表</th>
	</tr>
	<tr>
		<td class="label" width="15%">选择分类:</td>
		<td width="85%">
		<select name="cat_id" id="cat_id">
		<?php 
		if(!empty($catids)){
		 foreach($catids as $row){ 
		?>
        <option value="<?php echo $row['id'];?>"  <?php echo $row['id']==$rt['cat_id'] ? 'selected="selected"' : '';?>><?php echo $row['name'];?></option>
			<?php 
				if(!empty($row['cat_id'])){
				foreach($row['cat_id'] as $rows){ 
					?>
					<option value="<?php echo $rows['id'];?>"  <?php echo $rows['id']==$rt['cat_id'] ? 'selected="selected"' : '';?>>&nbsp;&nbsp;<?php echo $rows['name'];?></option>
					<?php 
					if(!empty($rows['cat_id'])){
					foreach($rows['cat_id'] as $rowss){ 
					?>
							<option value="<?php echo $rowss['id'];?>"  <?php echo $rowss['id']==$rt['cat_id'] ? 'selected="selected"' : '';?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $rowss['name'];?></option>
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
		<td class="label">标题:</td>
		<td><input name="article_title" id="article_title"  type="text" size="43" value="<?php echo isset($rt['article_title']) ? $rt['article_title'] : '';?>"><span style="color:#FF0000">*</span><span class="article_title_mes"></span></td>
	  </tr>
	  <?php if(isset($rt['article_img'])&&!empty($rt['article_img'])){?>
	  <tr class="showimg">
	  	<td class="label">图片预览：</td>
		<td>
		<img src="../<?php echo $rt['article_img'];?>" alt="资讯图文预览" width="100"/>
		</td>
	  </tr>
	  <?php } ?>
	  <tr>
		<td class="label">上传图片:</td>
		<td>
		  <input name="article_img" id="articlephoto" type="hidden" value="<?php echo isset($rt['article_img']) ? $rt['article_img'] : '';?>" size="43"/>
		  <br />
		  <iframe id="iframe_t" name="iframe_t" border="0" src="upload.php?action=<?php echo isset($rt['article_img'])&&!empty($rt['article_img'])? 'show' : '';?>&ty=articlephoto&files=<?php echo isset($rt['article_img']) ? $rt['article_img'] : '';?>" scrolling="no" width="445" frameborder="0" height="25"></iframe>
		</td>
	  </tr>
	 <!-- <tr>
		<td class="label">公司Logo:</td>
		<td>
		  <input name="article_logo" id="companylogo" type="hidden" value="<?php echo isset($rt['article_img']) ? $rt['article_img'] : '';?>" size="43"/>
		  <br />
		  <iframe id="iframe_t" name="iframe_t" border="0" src="upload.php?action=<?php echo isset($rt['article_logo'])&&!empty($rt['article_logo'])? 'show' : '';?>&ty=companylogo&files=<?php echo isset($rt['article_logo']) ? $rt['article_logo'] : '';?>" scrolling="no" width="445" frameborder="0" height="25"></iframe>
		</td>
	  </tr>
	  <tr>
		<td class="label">浏览次数</td>
		<td><input name="viewcount" id="viewcount" type="text"  value="<?php echo isset($rt['viewcount']) ? $rt['viewcount'] : 10;?>"></td>
	  </tr>-->
	  <tr>
		<td class="label">状态设置:</td>
		<td><input id="is_show" name="is_show" value="1" type="checkbox" <?php echo !isset($rt['is_show']) || $rt['is_show']==1 ? 'checked="checked"' : '';?>>审核</td>
	  </tr>
	  <tr>
    <td class="label">文章重要性:</td>
    <td>
	  <input name="is_top" value="0" checked="true" type="radio" <?php echo $rt['is_top']==0 ? 'checked="checked"' : '';?> /> 普通
	  <input name="is_top" value="1" type="radio" <?php echo $rt['is_top']==1 ? 'checked="checked"' : '';?> /> 顶端   
	  </td>
  	 </tr>
	  <!--<tr>
		<td class="label">简单介绍:</td>
		<td><textarea name="about" id="about" style="width: 60%; height: 65px; overflow: auto;"><?php echo isset($rt['about']) ? $rt['about'] : '';?></textarea></td>
	  </tr>-->
	  <tr>
		<td class="label">详情介绍:</td>
		<td><textarea name="content" id="content" style="width:95%;height:500px;display:none;"><?php echo isset($rt['content']) ? $rt['content'] : '';?></textarea>
		<script>KE.show({id : 'content',cssPath : '<?php echo ADMIN_URL.'/css/edit.css';?>'});</script>
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
		<td>&nbsp;</td>
		<td>
		<input class="new_save" value="<?php echo $type=='newedit' ? '修改' : '添加';?>保存" type="Submit">
		</td>
	  </tr>
	 </table>
	 </form>
</div>

<?php  $thisurl = ADMIN_URL.'con_clientlist.php'; ?>
<script type="text/javascript">
<!--
//jQuery(document).ready(function($){
	$('.new_save').click(function(){
		count = $('#viewcount').val();
		if(count=='undefined' || !(count > 0)){
			$('#viewcount').val('10');
		}
		art_title = $('#article_title').val();
		if(art_title=='undefined' || art_title==""){
			$('.article_title_mes').html("标题不能为空！");
			$('.article_title_mes').css('color','#FE0000');
			return false;
		}
		return true;
	});
//});
-->
</script>

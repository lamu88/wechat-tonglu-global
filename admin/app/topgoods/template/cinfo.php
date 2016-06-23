<div class="contentbox">
<style type="text/css">
.menu_content .tab{ display:none}
.nav .active{
	 /*background: url(<?php echo $this->img('manage_r2_c13.jpg');?>) no-repeat;*/
	 background-color:#F5F5F5;
} 
.nav .other{
	/* background: url(<?php echo $this->img('manage_r2_c14.jpg');?>) no-repeat;*/
	 background-color:#E9E9E9;
} 
h2.nav{ border-bottom:1px solid #B4C9C6;font-size:13px; height:25px; line-height:25px; margin-top:0px; margin-bottom:0px}
h2.nav a{ color:#999999; display:block; float:left; height:24px;width:113px; text-align:center; margin-right:1px; margin-left:1px; cursor:pointer}
.addi{ margin:0px; padding:0px;}
.vipprice td{ border-bottom:1px dotted #ccc}
.vipprice th{ background-color:#EEF2F5}
</style>
 <h2 class="nav">
 <a class="other" href="<?php echo ADMIN_URL;?>topgoods.php?type=clist">专区列表</a>  
 <a class="active" href="<?php echo ADMIN_URL;?>topgoods.php?type=cinfo">添加专区</a> 
 <a class="other" href="<?php echo ADMIN_URL;?>topgoods.php?type=lists">产品列表</a> 
 <a class="other" href="<?php echo ADMIN_URL;?>topgoods.php?type=info">添加产品</a> 
</h2>

 <div class="menu_content">
 	<form action="" method="post" enctype="multipart/form-data" name="theForm" id="theForm">
	  <table cellspacing="2" cellpadding="5" width="100%">
	  <tr>
          <td class="label" width="150">名称</td>
          <td><input name="cat_name" type="text" value="<?php echo isset($rt['cat_name']) ? $rt['cat_name'] : '';?>" size="50" /></td>
        </tr>
		 <tr>
    <td class="label">上级分类:</td>
    <td>
      <select name="parent_id">
	    <option value="0">顶级分类</option>
		<?php 
		if(!empty($catelist)){
		 foreach($catelist as $row){ 
		 	if($type=='edit' && $rt['cat_name']==$row['name']) continue;
		?>
        <option value="<?php echo $row['id'];?>"  <?php echo isset($rt['parent_id'])&&$row['id']==$rt['parent_id'] ? 'selected="selected"' : '';?>><?php echo $row['name'];?></option>
			<?php 
				if(!empty($row['cat_id'])){
				foreach($row['cat_id'] as $rows){ 
					if($type=='cateedit' && $rt['cat_name']==$rows['name']) continue;
					?>
					<option value="<?php echo $rows['id'];?>"  <?php echo isset($rt['parent_id'])&&$rows['id']==$rt['parent_id'] ? 'selected="selected"' : '';?>>&nbsp;&nbsp;<?php echo $rows['name'];?></option>
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
		<td class="label">关联分类:</td>
		<td>
		 <select name="cat_id" id="cat_id">
	    <option value="0">选择分类</option>
		<?php 
		if(!empty($catelist2)){
		 foreach($catelist2 as $row){ 
		?>
        <option value="<?php echo $row['id'];?>" <?php if(isset($rt['cat_id'])&&$rt['cat_id']==$row['id']){ echo 'selected="selected""'; } ?>><?php echo $row['name'];?></option>
			<?php 
				if(!empty($row['cat_id'])){
				foreach($row['cat_id'] as $rows){ 
					?>
					<option value="<?php echo $rows['id'];?>"  <?php if(isset($rt['cat_id'])&&$rt['cat_id']==$rows['id']){ echo 'selected="selected""'; } ?>>&nbsp;&nbsp;<?php echo $rows['name'];?></option>
					<?php 
					if(!empty($rows['cat_id'])){
					foreach($rows['cat_id'] as $rowss){ 
					?>
							<option value="<?php echo $rowss['id'];?>"  <?php if(isset($rt['cat_id'])&&$rt['cat_id']==$rowss['id']){ echo 'selected="selected""'; } ?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $rowss['name'];?></option>
							
							
						<?php 
						if(!empty($rows['cat_id'])){
						foreach($rowss['cat_id'] as $rowsss){ 
						?>
								<option value="<?php echo $rowsss['id'];?>" <?php if(isset($rt['cat_id'])&&$rt['cat_id']==$rowsss['id']){ echo 'selected="selected""'; } ?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $rowsss['name'];?></option>
								
						<?php
						}//end foreach
						}//end if
						?>
							
							
							
							
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
	 </select><em style="color:#FF0000">默认不选择</em> 
		</td>
	  </tr>
        <tr>
          <td class="label">链接</td>
          <td><input name="cat_url" type="text" value="<?php echo isset($rt['cat_url']) ? $rt['cat_url'] : '';?>" size="50" /><br/>可留空</td>
        </tr>
		<tr>
          <td class="label">广告图1</td>
          <td>
		  <input name="cat_img" id="topimg" type="hidden" value="<?php echo isset($rt['cat_img']) ? $rt['cat_img'] : '';?>" />
		  <iframe id="iframe_t" name="iframe_t" border="0" src="upload.php?action=<?php echo isset($rt['cat_img'])&&!empty($rt['cat_img'])? 'show' : '';?>&ty=topimg&files=<?php echo isset($rt['cat_img']) ? $rt['cat_img'] : '';?>" scrolling="no" width="445" frameborder="0" height="25"></iframe>
		  <br/>可留空,需处理好图片再上传</td>
        </tr>
		<tr>
          <td class="label">广告图2</td>
          <td>
		  <input name="cat_img2" id="topimg2" type="hidden" value="<?php echo isset($rt['cat_img2']) ? $rt['cat_img2'] : '';?>" />
		  <iframe id="iframe_t" name="iframe_t" border="0" src="upload.php?action=<?php echo isset($rt['cat_img2'])&&!empty($rt['cat_img2'])? 'show' : '';?>&ty=topimg2&tyy=topimg&files=<?php echo isset($rt['cat_img2']) ? $rt['cat_img2'] : '';?>" scrolling="no" width="445" frameborder="0" height="25"></iframe>
		  <br/>可留空,需处理好图片再上传</td>
        </tr>
		<tr>
			<td class="label">描述:</td>
			<td><textarea name="cat_desc" id="cat_desc" style="width: 60%; height: 65px; overflow: auto;"><?php echo isset($rt['cat_desc']) ? $rt['cat_desc'] : '';?></textarea></td>
		  </tr>
	  </table>
	  <p style="text-align:center">
		<input value=" 操作 " type="Submit">
	  </p>
	 </form>
 </div> 
 
</div>
	  

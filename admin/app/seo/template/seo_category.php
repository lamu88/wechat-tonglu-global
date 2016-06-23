<div class="contentbox">
<form id="form1" name="form1" method="post" action="">
     <table cellspacing="2" cellpadding="5" width="100%">
	 <tr>
		<th colspan="5" align="left"><?php echo $catename;?>分类SEO优化</th>
	</tr>
    <tr>
	   <th width="80"><label><input type="checkbox" class="quxuanall" value="checkbox" />选择</label></th>
	   <th>分类名称</th>
	   <th>分类标题</th>
	   <th>Meta关键字</th>
	   <th>Meta描述</th>
	</tr>
	<?php 
	if(!empty($catelist)){ 
	foreach($catelist as $row){
	?>
	<tr>
	<td>
	<input type="checkbox" name="quanxuan"  class="gids"/>
	<input type="hidden" name="cat_id[]" value="<?php echo $row['id'];?>"/>
	</td>
	<td><input  type="text" value="<?php echo $row['name'];?>" class="names" name="names[]"/></td>
	<td><input  type="text" value="<?php echo $row['cat_title'];?>" class="cat_title" name="cat_title[]"/></td>
	<td><input  type="text" value="<?php echo $row['meta_keys'];?>" class="meta_keys" name="meta_keys[]"/></td>
	<td><textarea cols="45" rows="2" class="meta_desc" name="meta_desc[]"><?php echo $row['meta_desc'];?></textarea></td>
	</tr>
	
		<?php 
			if(!empty($row['cat_id'])){ 
				foreach($row['cat_id'] as $rows){
				?>
					<tr>
					<td>
					<input type="checkbox" name="quanxuan" class="gids"/>
					<input type="hidden" name="cat_id[]" value="<?php echo $rows['id'];?>"/>
					</td>
					<td>&nbsp;&nbsp;├ &nbsp;<input  type="text" value="<?php echo $rows['name'];?>" class="names" name="names[]"/></td>
					<td><input  type="text" value="<?php echo $rows['cat_title'];?>" class="cat_title" name="cat_title[]"/></td>
					<td><input  type="text" value="<?php echo $rows['meta_keys'];?>" class="meta_keys" name="meta_keys[]"/></td>
					<td><textarea cols="45" rows="2" class="meta_desc" name="meta_desc[]"><?php echo $rows['meta_desc'];?></textarea></td>
					</tr>
							<?php 
							if(!empty($rows['cat_id'])){ 
								foreach($rows['cat_id'] as $rowss){
							?>
									<tr>
									<td>
									<input type="checkbox" name="quanxuan" class="gids"/>
									<input type="hidden" name="cat_id[]" value="<?php echo $rowss['id'];?>"/>
									</td>
									<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;├ &nbsp;<input  type="text" value="<?php echo $rowss['name'];?>" class="names" name="names[]"/></td>
									<td><input  type="text" value="<?php echo $rowss['cat_title'];?>" class="cat_title" name="cat_title[]"/></td>
									<td><input  type="text" value="<?php echo $rowss['meta_keys'];?>" class="meta_keys" name="meta_keys[]"/></td>
									<td><textarea cols="45" rows="2" class="meta_desc" name="meta_desc[]"><?php echo $rowss['meta_desc'];?></textarea></td>
									</tr>
						<?php
								}
							}
						?>
				<?php
				}
			}
		?>
	<?php } ?>
	<tr>
		 <td colspan="5"> <input type="checkbox" class="quxuanall" value="checkbox" />
			  <input type="Submit"  value="批量更改" disabled="disabled" class="bathset" id="bathset"/>
		 </td>
	</tr>
		<?php } ?>
	 </table>
	 </form>
</div>
<?php  $thisurl = ADMIN_URL.'seo_youhua.php'; ?>
<script type="text/javascript">
//全选
 $('.quxuanall').click(function (){
      if(this.checked==true){
         $("input[name='quanxuan']").each(function(){this.checked=true;});
		 document.getElementById("bathset").disabled = false;
	  }else{
	     $("input[name='quanxuan']").each(function(){this.checked=false;});
		 document.getElementById("bathset").disabled = true;
	  }
  });
  
  //是删除按钮失效或者有效
  $('.gids').click(function(){ 
  		var checked = false;
  		$("input[name='quanxuan']").each(function(){
			if(this.checked == true){
				checked = true;
			}
		}); 
		document.getElementById("bathset").disabled = !checked;
  });


   
   	/*$('.bathset').click(function(){
		var name_arr = [];
		$('input[class="names"]').each(function(){
				name_arr.push($(this).val());
		});
		var str_name=name_arr.join('+'); 
		
		var title_arr = [];
		$('input[class="cat_title"]').each(function(){
				title_arr.push($(this).val());
		});
		var str_title=title_arr.join('+'); 
		
		var keys_arr = [];
		$('input[class="meta_keys"]').each(function(){
				keys_arr.push($(this).val());
		});
		var str_keys=keys_arr.join('+'); 
		
		var desc_arr = [];
		$('textarea[class="meta_desc"]').each(function(){
				desc_arr.push($(this).val());
		});
		var str_desc=desc_arr.join('+'); 
		
		alert(str_name);
		alert(str_title);
		alert(str_keys);
		alert(str_desc);
		
	});*/
	
</script>
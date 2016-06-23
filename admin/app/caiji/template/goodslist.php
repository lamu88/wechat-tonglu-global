<div class="contentbox">
     <table cellspacing="2" cellpadding="5" width="100%">
	 <tr>
		<th align="left"><?php echo $catename;?>商品列表</th>
	</tr>
    <tr>
		<td>
		分类筛选=><select name="goods_cate" onchange="window.location.href='<?php echo ADMIN_URL;?>caiji.php?type=goodslist&cname='+$(this).val()">
		<option value="">选择分类</option>
		<?php if(!empty($catelist_caiji))foreach($catelist_caiji as $val){?>
		<option value="<?php echo $val;?>"  <?php if(isset($_GET['cname'])&&$_GET['cname']==$val){ echo 'selected="selected""'; } ?>>&nbsp;&nbsp;<?php echo $val;?></option>
		<?php } ?>
		 </select>
		</td>
	</tr>
	<?php 
	if(!empty($rt)) foreach($rt as $item){
	?>
	<tr><td>
	<form id="SAVEINFO<?php echo $item['goods_id'];?>" name="SAVEINFO<?php echo $item['goods_id'];?>" method="post" action="">
	 	<table cellspacing="2" cellpadding="5" width="100%" style="border-top:1px dotted #ccc">
		<tr>
		<td width="10%" align="right">商品名：</td>
		<td align="left"><input name="goods_name"  type="text" id="goods_name" style="width:260px; height:20px; line-height:20px" value="<?php echo $item['goods_name'];?>"/></td>
		<td width="10%" align="right">分类名：</td>
		<td align="left">采集分类=><?php echo $item['goods_cate_all'];?><br/>
		 改变分类=> <select name="cat_id">
			<option value="">所有分类</option>
			<?php
			if(!empty($catelist)){
			 foreach($catelist as $row){ 
			?>
			<option value="<?php echo $row['id'];?>" <?php if(isset($item['cat_id'])&&$item['cat_id']==$row['id']){ echo 'selected="selected""'; } ?>><?php echo $row['name'];?></option>
				<?php 
					if(!empty($row['cat_id'])){
					foreach($row['cat_id'] as $rows){ 
						?>
						<option value="<?php echo $rows['id'];?>"  <?php if(isset($item['cat_id'])&&$item['cat_id']==$rows['id']){ echo 'selected="selected""'; } ?>>&nbsp;&nbsp;<?php echo $rows['name'];?></option>
						<?php 
						if(!empty($rows['cat_id'])){
						foreach($rows['cat_id'] as $rowss){ 
						?>
								<option value="<?php echo $rowss['id'];?>"  <?php if(isset($item['cat_id'])&&$item['cat_id']==$rowss['id']){ echo 'selected="selected""'; } ?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $rowss['name'];?></option>
							<?php 
							if(!empty($rows['cat_id'])){
							foreach($rowss['cat_id'] as $rowsss){ 
							?>
									<option value="<?php echo $rowsss['id'];?>"<?php if(isset($item['cat_id'])&&$item['cat_id']==$rowsss['id']){ echo 'selected="selected""'; } ?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $rowsss['name'];?></option>
									
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
		 </select>
		 
		  </td>
			<td width="10%" align="right">设置品牌：</td>
		<td align="left">><?php echo $item['brand_name'];?><br/>
		 <select name="brand_id">
			  <option value="">所有品牌</option>
			 <?php 
			if(!empty($brandlist)){
			 foreach($brandlist as $row){ 
			?>
			<option value="<?php echo $row['id'];?>" <?php if(isset($item['brand_id'])&&$item['brand_id']==$row['id']){ echo 'selected="selected""'; } ?>><?php echo $row['name'];?></option>
				<?php 
					if(!empty($row['brand_id'])){
					foreach($row['brand_id'] as $rows){ 
						?>
						<option value="<?php echo $rows['id'];?>"  <?php if(isset($item['brand_id'])&&$item['brand_id']==$rows['id']){ echo 'selected="selected""'; } ?>>&nbsp;&nbsp;<?php echo $rows['name'];?></option>
						<?php 
						if(!empty($rows['brand_id'])){
						foreach($rows['brand_id'] as $rowss){ 
						?>
								<option value="<?php echo $rowss['id'];?>"  <?php if(isset($item['brand_id'])&&$item['brand_id']==$rowss['id']){ echo 'selected="selected""'; } ?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $rowss['name'];?></option>
								
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
		<td width="10%" align="right">编码：</td>
		<td align="left"><input name="goods_bianhao"  type="text" id="goods_bianhao" style="width:260px; height:20px; line-height:20px" value="<?php echo $item['goods_bianhao'];?>"/></td>
		<td width="10%" align="right">条形码：</td>
		<td align="left"><input name="goods_sn"  type="text" id="goods_sn" style="width:260px; height:20px; line-height:20px" value="<?php echo $item['goods_sn'];?>"/></td>
		<td align="right">单位:</td>
		<td align="left"><input name="goods_unit"  type="text" id="oods_unit" style="width:260px; height:20px; line-height:20px" value="<?php echo $item['goods_unit'];?>"/></td>
		</tr>
			<tr>
		<td width="10%" align="right">规格：</td>
		<td align="left"><input name="goods_brief"  type="text" id="goods_brief" style="width:260px; height:20px; line-height:20px" value="<?php echo $item['goods_brief'];?>"/></td>
		<td width="10%" align="right">零售价：</td>
		<td align="left"><input name="shop_price"  type="text" id="shop_price" style="width:260px; height:20px; line-height:20px" value="<?php echo $item['shop_price'];?>"/></td>
		<td align="right">批发价:</td>
		<td align="left"><input name="pifa_price"  type="text" id="pifa_price" style="width:260px; height:20px; line-height:20px" value="<?php echo $item['pifa_price'];?>"/></td>
		</tr>
		<tr>
		<td width="10%" align="right">库存：</td>
		<td align="left"><input name="goods_number"  type="text" id="meta_title" style="width:260px; height:20px; line-height:20px" value="<?php echo $item['goods_number'];?>"/></td>
		<td width="10%" align="right">Meta Desc：</td>
		<td align="left"><input name="meta_keys"  type="text" id="meta_keys" style="width:260px; height:20px; line-height:20px" value="<?php echo $item['meta_keys'];?>"/></td>
		<td align="right">Meta Keyword:</td>
		<td align="left"><input name="meta_desc"  type="text" id="meta_desc" style="width:260px; height:20px; line-height:20px" value="<?php echo $item['meta_desc'];?>"/></td>
		</tr>
		<tr>
			<td align="left">库存警告数:&nbsp;<input name="warn_number"  type="text" id="meta_title" style="width:70px; height:20px; line-height:20px" value="<?php echo $item['warn_number'];?>"/></td>
			<td align="right" colspan="3"><img src="<?php echo empty($item['goods_thumb']) ? $this->img('no_picture.gif') : SITE_URL.$item['goods_thumb'];?>" alt="<?php echo $item['goods_name'];?>" width="70"/><br/>
			 <input name="original_img" id="original_img" type="hidden" value="<?php echo isset($item['original_img']) ? $item['original_img'] : '';?>"/>
			 <iframe id="iframe_t" name="iframe_t" border="0" src="upload.php?action=<?php echo isset($item['original_img'])&&!empty($item['original_img'])? 'show' : '';?>&ty=original_img&tyy=g&files=<?php echo isset($item['original_img']) ? $item['original_img'] : '';?>" scrolling="no" width="370" frameborder="0" height="25"></iframe>
			</td>
			<td colspan="2" align="left">
			<!--<select name="uid">
			<option value="0">--选择供应商--</option>
			 <?php 
			if(!empty($uidlist)){
			 foreach($uidlist as $row){ 
			?>
			<option value="<?php echo $row['user_id'];?>" <?php if(isset($rt['uid'])&&$rt['uid']==$row['user_id']){ echo 'selected="selected""'; } ?>><?php echo $row['user_name'].(!empty($row['nickname']) ? '&nbsp;&nbsp;['.$row['nickname'].']' : "");?></option>
			<?php
			 }//end foreach
			} ?>
			</select><br />-->
			  <input type="button" value=" 保存修改 " style="cursor:pointer; padding:3px" onclick="saveinfo('<?php echo $item['goods_id'];?>')"/>
			  &nbsp;&nbsp;<input type="button" name="" value=" 保存并<?php echo $item['is_zhuanyi']=='1' ? '修改' : '';?>转移到出售区 " style="cursor:pointer; padding:3px" onclick="save_and_zhuanyi_info('<?php echo $item['goods_id'];?>')"/> &nbsp;&nbsp;<input type="button" name="" value=" 删除当条 " style="cursor:pointer; padding:3px" onclick="delinfo('<?php echo $item['goods_id'];?>')"/>
			</td>
		</tr>
		</table>
		</form>
		</td></tr>
	<?php } ?>
	 </table>
	  <?php $this->element('page',array('pagelink'=>$pagelink));?>
</div>

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

	function saveinfo(gid){
			var formObj      = document.forms['SAVEINFO'+gid]; //表单
			var mesobj        = new Object();
			if(formObj){
				mesobj = getFormAttrs(formObj);
			}else{
				alert('不存在表单对象！');	
				return false;
			}
			mesobj.goods_id = gid;
			$.ajax({
			   type: "GET",
			   url: "<?php echo ADMIN_URL;?>caiji.php",
			   data: "type=ajax_save_caijigoods&message=" + $.toJSON(mesobj),
			   dataType: "json",
			   success: function(data){
					alert(data.message);
			   } //end sucdess
			}); //end ajax
	}
	
	
	function save_and_zhuanyi_info(gid){
			var formObj      = document.forms['SAVEINFO'+gid]; //表单
			var mesobj        = new Object();
			if(formObj){
				mesobj = getFormAttrs(formObj);
			}else{
				alert('不存在表单对象！');	
				return false;
			}
			mesobj.goods_id = gid;
			$.ajax({
			   type: "GET",
			   url: "<?php echo ADMIN_URL;?>caiji.php",
			   data: "type=ajax_save_and_caijigoods&message=" + $.toJSON(mesobj),
			   dataType: "json",
			   success: function(data){
					alert(data.message);
			   } //end sucdess
			}); //end ajax
	}
	
	
	function delinfo(gid){
		if(confirm('确定删除吗？')){
			$.get('<?php  echo ADMIN_URL.'caiji.php'; ?>',{type:'ajax_del_cache_goods',goods_id:gid},function(data){
				if(data!=""){
					alert(data);
				}else{
					$('#SAVEINFO'+gid).parent().parent().remove();
				}
				
			});
		}
		return false;
	}
</script>

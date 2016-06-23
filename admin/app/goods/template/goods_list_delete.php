<div class="contentbox">
     <table cellspacing="2" cellpadding="5" width="100%">
	 <tr>
		<th colspan="8" align="left">我的回收站</th>
	</tr>
	<tr><td colspan="8" align="left" class="label">
    	<img src="<?php echo $this->img('icon_search.gif');?>" alt="SEARCH" width="26" border="0" height="22" align="absmiddle">
    	<select name="cat_id">
	    <option value="">所有分类</option>
		<?php
		if(!empty($catelist)){
		 foreach($catelist as $row){ 
		?>
        <option value="<?php echo $row['id'];?>" <?php if(isset($_GET['cat_id'])&&$_GET['cat_id']==$row['id']){ echo 'selected="selected""'; } ?>><?php echo $row['name'];?></option>
			<?php 
				if(!empty($row['cat_id'])){
				foreach($row['cat_id'] as $rows){ 
					?>
					<option value="<?php echo $rows['id'];?>"  <?php if(isset($_GET['cat_id'])&&$_GET['cat_id']==$rows['id']){ echo 'selected="selected""'; } ?>>&nbsp;&nbsp;<?php echo $rows['name'];?></option>
					<?php 
					if(!empty($rows['cat_id'])){
					foreach($rows['cat_id'] as $rowss){ 
					?>
							<option value="<?php echo $rowss['id'];?>"  <?php if(isset($_GET['cat_id'])&&$_GET['cat_id']==$rowss['id']){ echo 'selected="selected""'; } ?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $rowss['name'];?></option>
					<!--------------------  look添加 开始------------------------------------------------------------>						
						<?php 
						if(!empty($rows['cat_id'])){
						foreach($rowss['cat_id'] as $rowsss){ 
						?>
								<option value="<?php echo $rowsss['id'];?>"<?php if(isset($_GET['cat_id'])&&$_GET['cat_id']==$rowsss['id']){ echo 'selected="selected""'; } ?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $rowsss['name'];?></option>
								
						<?php
						}//end foreach
						}//end if
						?>
		<!--------------------  look添加 结束    -------------------------------------------------------------->			
							
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

    	 <select name="is_goods_attr">
			<option value="">全部</option>
			<option value="is_hot" <?php if(isset($_GET['is_goods_attr'])&&$_GET['is_goods_attr']=='is_hot'){ echo 'selected="selected""'; } ?>>热销</option>
			<option value="is_new" <?php if(isset($_GET['is_goods_attr'])&&$_GET['is_goods_attr']=='is_new'){ echo 'selected="selected""'; } ?>>新品</option>
			<option value="is_best" <?php if(isset($_GET['is_goods_attr'])&&$_GET['is_goods_attr']=='is_best'){ echo 'selected="selected""'; } ?>>精品</option>
<!--			<option value="is_alone_sale" <?php if(isset($_GET['is_alone_sale'])&&$_GET['is_alone_sale']=='is_alone_sale'){ echo 'selected="selected""'; } ?>>礼包</option>-->
			<option value="is_promote" <?php if(isset($_GET['is_goods_attr'])&&$_GET['is_goods_attr']=='is_promote'){ echo 'selected="selected""'; } ?>>特价商品</option>
<!--			<option value="is_qianggou" <?php if(isset($_GET['is_goods_attr'])&&$_GET['is_goods_attr']=='is_qianggou'){ echo 'selected="selected""'; } ?>>抢购商品</option>
			<option value="is_jifen" <?php if(isset($_GET['is_jifen'])&&$_GET['is_jifen']=='is_jifen'){ echo 'selected="selected""'; } ?>>积分商品</option>-->
		 </select>
		 
		 <!--品牌-->
		  <select name="brand_id">
		  <option value="">所有品牌</option>
		 <?php 
		if(!empty($brandlist)){
		 foreach($brandlist as $row){ 
		?>
        <option value="<?php echo $row['id'];?>" <?php if(isset($_GET['brand_id'])&&$_GET['brand_id']==$row['id']){ echo 'selected="selected""'; } ?>><?php echo $row['name'];?></option>
			<?php 
				if(!empty($row['brand_id'])){
				foreach($row['brand_id'] as $rows){ 
					?>
					<option value="<?php echo $rows['id'];?>"  <?php if(isset($_GET['brand_id'])&&$_GET['brand_id']==$rows['id']){ echo 'selected="selected""'; } ?>>&nbsp;&nbsp;<?php echo $rows['name'];?></option>
					<?php 
					if(!empty($rows['brand_id'])){
					foreach($rows['brand_id'] as $rowss){ 
					?>
							<option value="<?php echo $rowss['id'];?>"  <?php if(isset($_GET['brand_id'])&&$_GET['brand_id']==$rowss['id']){ echo 'selected="selected""'; } ?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $rowss['name'];?></option>
							
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
    	关键字 <input name="keyword" size="15" type="text" value="<?php echo isset($_GET['keyword']) ? $_GET['keyword'] : "";?>">
    	<input value=" 搜索 " class="cate_search" type="button">
	</td></tr>
    <tr>
	   <th width="70"><label><input type="checkbox" class="quxuanall" value="checkbox" />选择</label></th>
	   <th><a href="<?php echo $cate;?>">所在分类</a></th>
	   <th>商品图</th>
	   <th style="width:225px;"><a href="<?php echo $goods_name;?>">标题</a></th>
<!--	   <th><a href="<?php echo $price;?>">供应价</a><br/>[<font color="red">供应商</font>]</th>-->
	   <th><a href="<?php echo $vipprice;?>">原始价</a></th>
	   <th><a href="<?php echo $vipprice;?>">折扣价</a></th>
	   <th><a href="<?php echo $addtime;?>">录入时间</a></th>
	   <th>操作</th>
	</tr>
	<?php
	if(!empty($rt)){ 
	foreach($rt as $row){
	?>
	<tr>
	<td><input type="checkbox" name="quanxuan" value="<?php echo $row['goods_id'];?>" class="gids"/></td>
	<td><?php echo $row['cat_name'];?></td>
	<td><?php echo !empty($row['goods_thumb']) ? '<a target="_blank" href="../goods.php?id='.$row['goods_id'].'"><img src="'.dirname(ADMIN_URL).'/'.$row['goods_thumb'].'" width="60"/></a>' : "";?></td>
	<td><?php echo $row['goods_name'];?></td>
<!--	<td><?php echo $row['market_price'];?><br />[<font color="red"><?php echo !empty($row['nickname'])?$row['nickname'] : (!empty($row['user_name'])?$row['user_name']:'E姐商城');?></font>]</td>-->
	<td><?php echo $row['shop_price'];?></td>
	<td><?php echo $row['pifa_price'];?></td>
  	<td><?php echo !empty($row['add_time']) ? date('Y-m-d',$row['add_time']) : "无知";?></td>
	<td>
	<img src="<?php echo $this->img('icon_drop.gif');?>" title="测底删除" alt="测底删除" id="<?php echo $row['goods_id'];?>" class="delgoodsid"/>
	</td>
	</tr>
	<?php
	 } ?>
	<tr>
		 <td colspan="8"> <input type="checkbox" class="quxuanall" value="checkbox" />
			  <input type="button" name="button" value="测底批量删除" disabled="disabled" class="bathdel" id="bathdel" style="cursor:pointer"/>
			  <input type="button" name="button" value="还原商品" disabled="disabled" class="redugoods" id="redugoods" style="cursor:pointer"/>
		 </td>
	</tr>
		<?php } ?>
	 </table>
	 <?php $this->element('page',array('pagelink'=>$pagelink));?>
</div>
<?php  $thisurl = ADMIN_URL.'goods.php'; ?>
<script type="text/javascript">
//全选
 $('.quxuanall').click(function (){
      if(this.checked==true){
         $("input[name='quanxuan']").each(function(){this.checked=true;});
		 document.getElementById("bathdel").disabled = false;
		 document.getElementById("redugoods").disabled = false;
	  }else{
	     $("input[name='quanxuan']").each(function(){this.checked=false;});
		 document.getElementById("bathdel").disabled = true;
		 document.getElementById("redugoods").disabled = true;
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
		document.getElementById("bathdel").disabled = !checked;
		document.getElementById("redugoods").disabled = !checked;
  });
  
  //批量删除
   $('.bathdel').click(function (){
   		if(confirm("确定删除吗？")){
			createwindow();
			var arr = [];
			$('input[name="quanxuan"]:checked').each(function(){
				arr.push($(this).val());
			});
			var str=arr.join('+');
			$.post('<?php echo $thisurl;?>',{action:'delgoods',ids:str},function(data){
				removewindow();
				if(data == ""){
					location.reload();
				}else{
					alert(data);
				}
			});
		}else{
			return false;
		}
   });
   
     //批量还元
   $('.redugoods').click(function (){
   		if(confirm("确定删除吗？")){
			createwindow();
			var arr = [];
			$('input[name="quanxuan"]:checked').each(function(){
				arr.push($(this).val());
			});
			var str=arr.join('+');
			$.post('<?php echo $thisurl;?>',{action:'ajax_redugoods',ids:str},function(data){
				removewindow();
				if(data == ""){
					location.reload();
				}else{
					alert(data);
				}
			});
		}else{
			return false;
		}
   });
   
   $('.delgoodsid').click(function(){
   		ids = $(this).attr('id');
		thisobj = $(this).parent().parent();
		if(confirm("确定删除吗？")){
			createwindow();
			$.post('<?php echo $thisurl;?>',{action:'delgoods',ids:ids},function(data){
				removewindow();
				if(data == ""){
					thisobj.hide(300);
				}else{
					alert(data);	
				}
			});
		}else{
			return false;	
		}
   });
   
   	$('.activeop').live('click',function(){
		star = $(this).attr('alt');
		gid = $(this).attr('id'); 
		type = $(this).attr('lang');
		obj = $(this);
		$.post('<?php echo $thisurl;?>',{action:'activeop',active:star,gid:gid,type:type},function(data){
			if(data == ""){
				if(type=='is_alone_sale'){
					if(star == 1){
						id = 0;
						src = '<?php echo $this->img('no.gif');?>';
					}else{
						id = 1;
						src = '<?php echo $this->img('yes.gif');?>';
						alert("设为礼品后，将以礼品形式赠品，不能再独立出售！");
					}
				}else{
					if(star == 1){
						id = 0;
						src = '<?php echo $this->img('yes.gif');?>';
					}else{
						id = 1;
						src = '<?php echo $this->img('no.gif');?>';
					}
				}
				obj.attr('src',src);
				obj.attr('alt',id);
			}else{
				alert(data);
			}
		});
	});
	
	//sous
	$('.cate_search').click(function(){
		catid = $('select[name="cat_id"]').val();
		
		is_goods = $('select[name="is_goods_attr"]').val();
		
		bid = $('select[name="brand_id"]').val();;
		
		keys = $('input[name="keyword"]').val();
		
		location.href='<?php echo $thisurl;?>?type=goods_list&cat_id='+catid+'&is_goods_attr='+is_goods+'&brand_id='+bid+'&keyword='+keys+'&is_delete=1';
	});
</script>
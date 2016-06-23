<?php
$thisurl = ADMIN_URL.'goods.php'; 
if(isset($_GET['asc'])){
$cate = $thisurl.'?type=goods_list&desc=gc.cat_name';
$goods_name = $thisurl.'?type=goods_list&desc=g.goods_name';
$price = $thisurl.'?type=goods_list&desc=sg.market_price';
$vipprice = $thisurl.'?type=goods_list&desc=sg.shop_price';
$is_on_sale = $thisurl.'?type=goods_list&desc=sg.is_on_sale';
$addtime = $thisurl.'?type=goods_list&desc=sg.addtime';
}else{
$cate = $thisurl.'?type=goods_list&asc=gc.cat_name';
$goods_name = $thisurl.'?type=goods_list&asc=g.goods_name';
$price = $thisurl.'?type=goods_list&asc=sg.market_price';
$vipprice = $thisurl.'?type=goods_list&asc=sg.shop_price';
$is_on_sale = $thisurl.'?type=goods_list&asc=sg.is_on_sale';
$addtime = $thisurl.'?type=goods_list&asc=sg.addtime';
}
?>
<style>.vieworder{ width:55px; height:25px; display:block; cursor:pointer}</style>
<div class="contentbox">
     <table cellspacing="2" cellpadding="5" width="100%">
	 <tr>
		<th colspan="13" align="left"><?php echo $_GET['sale']=='yes' ? '已审核商品' : '待审核商品';?></th>
	</tr>
	<tr><td colspan="13" align="left" class="label">
    	<img src="<?php echo $this->img('icon_search.gif');?>" alt="SEARCH" width="26" border="0" height="22" align="absmiddle">
		
	
	<!-- <select name="uid">
		<option value="">--选择供应商--</option>
		 <?php 
		if(!empty($uidlist)){
		 foreach($uidlist as $row){ 
		?>
        <option value="<?php echo $row['user_id'];?>" <?php if(isset($_GET['uid'])&&$_GET['uid']==$row['user_id']){ echo 'selected="selected""'; } ?>><?php echo $row['user_name'].(!empty($row['nickname']) ? '&nbsp;&nbsp;['.$row['nickname'].']' : "");?></option>
		<?php
		 }//end foreach
		} ?>
        </select>-->
	 
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
			 <option value="is_on_sale1" <?php if(isset($_GET['is_goods_attr'])&&$_GET['is_goods_attr']=='is_on_sale1'){ echo 'selected="selected""'; } ?>>上架</option>
			 <option value="is_on_sale0" <?php if(isset($_GET['is_goods_attr'])&&$_GET['is_goods_attr']=='is_on_sale0'){ echo 'selected="selected""'; } ?>>下架</option>
			 
			<option value="is_hot" <?php if(isset($_GET['is_goods_attr'])&&$_GET['is_goods_attr']=='is_hot'){ echo 'selected="selected""'; } ?>>热销</option>
			<option value="is_new" <?php if(isset($_GET['is_goods_attr'])&&$_GET['is_goods_attr']=='is_new'){ echo 'selected="selected""'; } ?>>新品</option>
			<option value="is_best" <?php if(isset($_GET['is_goods_attr'])&&$_GET['is_goods_attr']=='is_best'){ echo 'selected="selected""'; } ?>>精品</option>
<!--			<option value="is_alone_sale" <?php if(isset($_GET['is_alone_sale'])&&$_GET['is_alone_sale']=='is_alone_sale'){ echo 'selected="selected""'; } ?>>礼包</option>
-->			<option value="is_promote" <?php if(isset($_GET['is_goods_attr'])&&$_GET['is_goods_attr']=='is_promote'){ echo 'selected="selected""'; } ?>>特价商品</option>
			<!--<option value="is_qianggou" <?php if(isset($_GET['is_goods_attr'])&&$_GET['is_goods_attr']=='is_qianggou'){ echo 'selected="selected""'; } ?>>抢购商品</option>
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
	   <th width="60"><label><input type="checkbox" class="quxuanall" value="checkbox" />选择</label></th>
	   <th><a href="<?php echo $cate;?>">所在分类</a></th>
	   <th>商品图</th>
	   <th style="width:225px;"><a href="<?php echo $goods_name;?>">标题</a></th>
<!--	   <th><a href="<?php echo $price;?>">供应价</a><br/>[<font color="red">供应商</font>]</th>
-->	   <th><a href="<?php echo $vipprice;?>">原始价</a></th>
	   <th><a href="<?php echo $vipprice;?>">折扣价</a></th>
	   <th>库存</th>
	   <th>库存警告数</th>
	   <th>单位</th>
	   <th><a href="<?php echo $is_on_sale;?>">上架</a></th>
	   <th>审核</th>
	   <th><a href="<?php echo $addtime;?>">录入时间</a></th>
	   <th>操作</th>
	</tr>
	<?php
	if(!empty($rt)){ 
	foreach($rt as $row){
	?>
	<tr>
	<td><input type="checkbox" name="quanxuan" value="<?php echo $row['goods_id'].'-'.$row['suppliers_id'];?>" class="gids"/></td>
	<td><?php echo $row['cat_name'];?></td>
	<td><a target="_blank" href="<?php echo $row['url'];?>"><img src="<?php echo !empty($row['goods_thumb']) ? 	dirname(ADMIN_URL).'/'.$row['goods_thumb'] : $this->img('no_picture.gif');?>" width="60"/></a></td>
	<td><?php echo $row['goods_name'];?></td>
<!--	<td><span class="vieworder" id="<?php echo $row['goods_id'].'-'.$row['suppliers_id'];?>" lang="market_price"><?php echo $row['market_price'];?></span><br />[<font color="red"><?php echo !empty($row['nickname'])?$row['nickname'] : (!empty($row['user_name'])?$row['user_name']:'E姐商城');?></font>]</td>-->
	<td><span class="vieworder" id="<?php echo $row['goods_id'].'-'.$row['suppliers_id'];?>" lang="shop_price"><?php echo $row['shop_price'];?></span></td>
	<td><span class="vieworder" id="<?php echo $row['goods_id'].'-'.$row['suppliers_id'];?>" lang="pifa_price"><?php echo $row['pifa_price'];?></span></td>
	<td><span class="vieworder" id="<?php echo $row['goods_id'].'-'.$row['suppliers_id'];?>" lang="goods_number"><?php echo $row['goods_number'];?></span></td>
	<td><span class="vieworder" id="<?php echo $row['goods_id'].'-'.$row['suppliers_id'];?>" lang="warn_number"><?php echo $row['warn_number'];?></span></td>
	<td><?php echo $row['goods_unit'];?></td>
<td><img src="<?php echo $this->img($row['is_on_sale']==1 ? 'yes.gif' : 'no.gif');?>" alt="<?php echo $row['is_on_sale']==1 ? '0' : '1';?>" class="activeop" lang="is_on_sale" id="<?php echo $row['goods_id'].'-'.$row['suppliers_id'];?>"/></td>
<td><img src="<?php echo $this->img($row['is_check']==1 ? 'yes.gif' : 'no.gif');?>" alt="<?php echo $row['is_check']==1 ? '0' : '1';?>" class="activeop" lang="is_check" id="<?php echo $row['goods_id'].'-'.$row['suppliers_id'];?>"/></td>
  <td><?php echo !empty($row['addtime']) ? date('Y-m-d',$row['addtime']) : "无知";?></td>
	<td>
	<!--<a href="goods.php?type=goods_info&id=<?php echo $row['goods_id'];?>" title="编辑"><img src="<?php echo $this->img('icon_edit.gif');?>" title="编辑"/></a>&nbsp;-->
	<img src="<?php echo $this->img('icon_drop.gif');?>" title="删除" alt="删除" id="<?php echo $row['goods_id'].'-'.$row['suppliers_id'];?>" class="delgoodsid"/>&nbsp;
	</td>
	</tr>
	<?php
	 } ?>
	<tr>
		 <td colspan="13"> <input type="checkbox" class="quxuanall" value="checkbox" />
			  <input type="button" name="button" value="批量删除" disabled="disabled" class="bathdel" id="bathdel" lang="del"/>
			  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			  <input type="button" name="button"  value="批量审核" disabled="disabled"  class="bathdel" id="all_activeop" lang="check"/>
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
		  document.getElementById("all_activeop").disabled = false;
	  }else{
	     $("input[name='quanxuan']").each(function(){this.checked=false;});
		 document.getElementById("bathdel").disabled = true;
		  document.getElementById("all_activeop").disabled = false;
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
		document.getElementById("all_activeop").disabled = !checked;   //look添加
  });
  
  //批量删除
   $('.bathdel').click(function (){
   		if(confirm("确定操作吗？")){
			createwindow();
			var type = $(this).attr('lang');
			var arr = [];
			$('input[name="quanxuan"]:checked').each(function(){
				arr.push($(this).val());
			});
			var str=arr.join('+');
			$.post('<?php echo $thisurl;?>',{action:'delgoods_suppliers',ids:str,reduction:'1',type:type},function(data){

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
		if(confirm("确定加入回收站吗？")){
			createwindow();
			$.post('<?php echo $thisurl;?>',{action:'delgoods_suppliers',ids:ids,reduction:'1',type:""},function(data){
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
		$.post('<?php echo $thisurl;?>',{action:'activeop_suppliers',active:star,gid:gid,type:type},function(data){
			if(data == ""){
				if(type=='is_check'){
					if(star == 1){
						id = 0;
						//src = '<?php echo $this->img('no.gif');?>';
					}else{
						id = 1;
						//src = '<?php echo $this->img('yes.gif');?>';
						
					}
					$(obj).parent().parent().hide(300);
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
		
		u_id = $('select[name="uid"]').val();
		
		catid = $('select[name="cat_id"]').val();
		
		is_goods = $('select[name="is_goods_attr"]').val();
		
		bid = $('select[name="brand_id"]').val();;
		
		keys = $('input[name="keyword"]').val();
		
		location.href='<?php echo $thisurl;?>?type=goods_list_check&uid='+u_id+'&cat_id='+catid+'&is_goods_attr='+is_goods+'&brand_id='+bid+'&keyword='+keys+'&is_delete=0&sale=<?php echo $_GET['sale']?>';
	});
	
	//ajax排序处理
	$('.vieworder').click(function (){ edit_2012127(this); });
	function edit_2012127(object){
		thisvar = $(object).html();
		ids = $(object).attr('id');
		tname = $(object).attr('lang');
		
		//$(object).css('background-color','#FFFFFF');
		 if(typeof($(object).find('input').val()) == 'undefined'){
             var input = document.createElement('input');
			 $(input).attr('value', thisvar);
			 $(input).css('width', '50px');
             $(input).change(function(){
                 update_2012127(ids, this,tname)
             })
             $(input).blur(function(){
                 $(this).parent().html($(this).val());
             });
             $(object).html(input);
			 $(input).select();
             $(object).find('input').focus();
         }
	}
	
	function update_2012127(id, object,type){
       var editval = $(object).val();
       var obj = $(object).parent();
	   $.post('<?php echo $thisurl;?>',{action:'goodsedit_suppliers',gid:id,val:editval,type:type},function(data){ 
			 obj.html(editval);
           	 $(object).unbind('click');
           	 $(object).click(function(){
               edit_2012127(object);
             })
		});
    }
</script>
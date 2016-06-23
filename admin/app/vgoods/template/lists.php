<?php
$thisurl = ADMIN_URL.'vgoods.php'; 
if(isset($_GET['asc'])){
$cate = $thisurl.'?type=lists&desc=tb2.cat_name';
$goods_name = $thisurl.'?type=lists&desc=tb1.goods_name';
$price = $thisurl.'?type=lists&desc=tb1.market_price';
$vipprice = $thisurl.'?type=lists&desc=tb1.shop_price';
$is_on_sale = $thisurl.'?type=lists&desc=tb1.is_on_sale';
$addtime = $thisurl.'?type=lists&desc=tb1.add_time';
}else{
$cate = $thisurl.'?type=lists&asc=tb2.cat_name';
$goods_name = $thisurl.'?type=lists&asc=tb1.goods_name';
$price = $thisurl.'?type=lists&asc=tb1.market_price';
$vipprice = $thisurl.'?type=lists&asc=tb1.shop_price';
$is_on_sale = $thisurl.'?type=lists&asc=tb1.is_on_sale';
$addtime = $thisurl.'?type=lists&asc=tb1.add_time';
}
?>
<style>.vieworder{ width:55px; height:25px; display:block; cursor:pointer}</style>
<div class="contentbox">
     <table cellspacing="2" cellpadding="5" width="100%">
	 <tr>
		<th colspan="14" align="left"><span style="float:left">虚拟商品列表</span><a href="<?php echo ADMIN_URL.'vgoods.php?type=info';?>" style="float:right; padding:2px 4px 2px 4px; background:#ededed; border-bottom:2px solid #ccc; border-right:2px solid #ccc">添加商品</a></th>
	</tr>
	<tr><td colspan="14" align="left" class="label">
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
						<?php 
						if(!empty($rows['cat_id'])){
						foreach($rowss['cat_id'] as $rowsss){ 
						?>
								<option value="<?php echo $rowsss['id'];?>"<?php if(isset($_GET['cat_id'])&&$_GET['cat_id']==$rowsss['id']){ echo 'selected="selected""'; } ?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $rowsss['name'];?></option>
								
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
	 
    	 <select name="is_goods_attr">
			 <option value="">全部</option>
			 <option value="is_on_sale1" <?php if(isset($_GET['is_goods_attr'])&&$_GET['is_goods_attr']=='is_on_sale1'){ echo 'selected="selected""'; } ?>>上架</option>
			 <option value="is_on_sale0" <?php if(isset($_GET['is_goods_attr'])&&$_GET['is_goods_attr']=='is_on_sale0'){ echo 'selected="selected""'; } ?>>下架</option>
			 
			<option value="is_hot" <?php if(isset($_GET['is_goods_attr'])&&$_GET['is_goods_attr']=='is_hot'){ echo 'selected="selected""'; } ?>>热销</option>
			<option value="is_new" <?php if(isset($_GET['is_goods_attr'])&&$_GET['is_goods_attr']=='is_new'){ echo 'selected="selected""'; } ?>>新品</option>
			<option value="is_best" <?php if(isset($_GET['is_goods_attr'])&&$_GET['is_goods_attr']=='is_best'){ echo 'selected="selected""'; } ?>>推荐</option>
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
	   <th><a href="<?php echo $price;?>">采购价</a></th>
	   <th><a href="<?php echo $vipprice;?>">市场价</a></th>
	    <th><a href="<?php echo $vipprice;?>">折扣价</a></th>
	   <th><a href="<?php echo $is_on_sale;?>">上架</a></th>
	   <!--<th>审核</th>-->
	   <th>热销</th>
	   <th>新品</th>
	   <th>推荐</th>
	   <th><a href="<?php echo $addtime;?>">录入时间</a></th>
	   <th>排序</th>
	   <th>操作</th>
	</tr>
	<?php
	if(!empty($rt)){ 
	foreach($rt as $row){
	?>
	<tr>
	<td><input type="checkbox" name="quanxuan" value="<?php echo $row['goods_id'];?>" class="gids"/></td>
	<td><?php echo $row['cat_name'];?></td>
	<td><a target="_blank" href="<?php echo $row['url'];?>"><img src="<?php echo !empty($row['goods_thumb']) ? 	dirname(ADMIN_URL).'/'.$row['goods_thumb'] : $this->img('no_picture.gif');?>" width="60"/></a></td>
	<td><?php echo $row['goods_name'];?></td>
	<td><span class="viewmarket" id="<?php echo $row['goods_id'];?>"><?php echo $row['market_price'];?></span></td>
	<td><span class="viewshop" id="<?php echo $row['goods_id'];?>"><?php echo $row['shop_price'];?></span></td>
	<td><span class="viewpifa" id="<?php echo $row['goods_id'];?>"><?php echo $row['pifa_price'];?></span></td>
	<?php 
	$pr = ($row['promote_start_date']< mktime()&&$row['promote_end_date'] > mktime() && $row['is_promote']=='1') ? 1 : 0;
	$pn = ($row['qianggou_start_date']< mktime()&&$row['qianggou_end_date'] > mktime() && $row['is_qianggou']=='1') ? 1 : 0;
	?>
<td><img src="<?php echo $this->img($row['is_on_sale']==1 ? 'yes.gif' : 'no.gif');?>" alt="<?php echo $row['is_on_sale']==1 ? '0' : '1';?>" class="activeop" lang="is_on_sale" id="<?php echo $row['goods_id'];?>"/></td>
<td><img src="<?php echo $this->img($row['is_hot']==1 ? 'yes.gif' : 'no.gif');?>" alt="<?php echo $row['is_hot']==1 ? '0' : '1';?>" class="activeop" lang="is_hot" id="<?php echo $row['goods_id'];?>"/></td>
<td><img src="<?php echo $this->img($row['is_new']==1 ? 'yes.gif' : 'no.gif');?>" alt="<?php echo $row['is_new']==1 ? '0' : '1';?>" class="activeop" lang="is_new" id="<?php echo $row['goods_id'];?>"/></td>
<td><img src="<?php echo $this->img($row['is_best']==1 ? 'yes.gif' : 'no.gif');?>" alt="<?php echo $row['is_best']==1 ? '0' : '1';?>" class="activeop" lang="is_best" id="<?php echo $row['goods_id'];?>"/></td>
  <td><?php echo !empty($row['add_time']) ? date('Y-m-d',$row['add_time']) : "无知";?></td>
  <td><span class="vieworder" id="<?php echo $row['goods_id'];?>" lang="sort_order"><?php echo $row['sort_order'];?></span></td>
	<td>
	<a href="vgoods.php?type=info&id=<?php echo $row['goods_id'];?>" title="编辑"><img src="<?php echo $this->img('icon_edit.gif');?>" title="编辑"/></a>&nbsp;
	<img src="<?php echo $this->img('icon_drop.gif');?>" title="删除" alt="删除" id="<?php echo $row['goods_id'];?>" class="delgoodsid"/>
	<p style="padding:0px; margin:0px"><a onclick="return open_import(<?php echo $row['goods_id'];?>)" href="javascript:;" style="padding:2px 5px 2px 5px; background:#ededed; border-bottom:1px solid #ccc; border-right:1px solid #ccc;">导入账号密码</a></p>
	</td>
	</tr>
	<?php
	 } ?>
	<tr>
		 <td colspan="14"> <input type="checkbox" class="quxuanall" value="checkbox" />
			  <input type="button" name="button" value="批量删除" disabled="disabled" class="bathdel" id="bathdel"/>
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
	  }else{
	     $("input[name='quanxuan']").each(function(){this.checked=false;});
		 document.getElementById("bathdel").disabled = true;
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
  });
  
  //批量删除
   $('.bathdel').click(function (){
   		if(confirm("确定加入回收站吗？")){
			createwindow();
			var arr = [];
			$('input[name="quanxuan"]:checked').each(function(){
				arr.push($(this).val());
			});
			var str=arr.join('+');
			$.post('<?php echo $thisurl;?>',{action:'delgoods',ids:str,reduction:'1'},function(data){
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
			$.post('<?php echo $thisurl;?>',{action:'delgoods',ids:ids,reduction:'1'},function(data){
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
				}else if(type=='is_check'){
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

<!----------------- look添加 开始 ----------------------------------------------------------->
//批量审核


 $('.bathdel').click(function (){
   		if(confirm("确定加入回收站吗？")){
			createwindow();
			var arr = [];
			$('input[name="quanxuan"]:checked').each(function(){
				arr.push($(this).val());
			});
			var str=arr.join('+');
			$.post('<?php echo $thisurl;?>',{action:'delgoods',ids:str,reduction:'1'},function(data){
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


$('.all_activeop').click('click',function(){
	if(confirm("确定全部审核吗？")){
		createwindow();
		var arr = [];
		$('input[name="quanxuan"]:checked').each(function(){
				arr.push($(this).val());
			});
		var str=arr.join('+');
		star = <?php echo $_GET['sale']=='yes' ? '1' : '0';?> ;
		 
	//	gid = $(this).attr('id'); 
		type = 'is_check';
		obj = $(this);
		$.post('<?php echo $thisurl;?>',{action:'activeop_all',active:star,gid:str,type:type},function(data){
			removewindow();
			if(data == ""){
				location.reload();
				 if(type=='is_check'){
					if(star == 1){
						id = 0;
					}else{
						id = 1;
					}
					$(obj).parent().parent().hide(100);
				}
				obj.attr('src',src);
				obj.attr('alt',id);
			}else{
				alert(data);
			}
		});
	   }
	   else{
			return false;
		}			
		
	});




//ajax价格批量修改
	$('.viewmarket').click(function (){ edit(this); });
	function edit(object){
		thisvar = $(object).html();
		ids = $(object).attr('id');
		if(!(thisvar>0)){
			thisvar = 999;
		}
		//$(object).css('background-color','#FFFFFF');
		 if(typeof($(object).find('input').val()) == 'undefined'){
             var input = document.createElement('input');
			 $(input).attr('value', thisvar);
			  $(input).css('width', '40px');
             $(input).change(function(){
                 update(ids, this)
             })
             $(input).blur(function(){
                 $(this).parent().html($(this).val());
             });
             $(object).html(input);
             $(object).find('input').focus();
         }
	}
	
	$('.viewshop').click(function (){ edit_shop(this); });
	function edit_shop(object){
		thisvar = $(object).html();
		ids = $(object).attr('id');
		if(!(thisvar>0)){
			thisvar = 999;
		}
		//$(object).css('background-color','#FFFFFF');
		 if(typeof($(object).find('input').val()) == 'undefined'){
             var input = document.createElement('input');
			 $(input).attr('value', thisvar);
			  $(input).css('width', '40px');
             $(input).change(function(){
                 shop_update(ids, this)
             })
             $(input).blur(function(){
                 $(this).parent().html($(this).val());
             });
             $(object).html(input);
             $(object).find('input').focus();
         }
	}
	
	$('.viewpifa').click(function (){ edit_pifa(this); });
	function edit_pifa(object){
		thisvar = $(object).html();
		ids = $(object).attr('id');
		if(!(thisvar>0)){
			thisvar = 999;
		}
		//$(object).css('background-color','#FFFFFF');
		 if(typeof($(object).find('input').val()) == 'undefined'){
             var input = document.createElement('input');
			 $(input).attr('value', thisvar);
			  $(input).css('width', '40px');
             $(input).change(function(){
                 pifa_update(ids, this)
             })
             $(input).blur(function(){
                 $(this).parent().html($(this).val());
             });
             $(object).html(input);
             $(object).find('input').focus();
         }
	}
	
	function update(id, object){
       var editval = $(object).val();
       var obj = $(object).parent();
	   $.post('<?php echo $thisurl;?>',{action:'goods_order_market',id:id,val:editval},function(data){ 
			 obj.html(editval);
           	 $(object).unbind('click');
           	 $(object).click(function(){
               edit(object);
             })
		});
    }
	
	function shop_update(id, object){
       var editval = $(object).val();
       var obj = $(object).parent();
	   $.post('<?php echo $thisurl;?>',{action:'goods_order_shop',id:id,val:editval},function(data){ 
			 obj.html(editval);
           	 $(object).unbind('click');
           	 $(object).click(function(){
               edit(object);
             })
		});
    }
	
	
	function pifa_update(id, object){
       var editval = $(object).val();
       var obj = $(object).parent();
	   $.post('<?php echo $thisurl;?>',{action:'goods_order_pifa',id:id,val:editval},function(data){ 
			 obj.html(editval);
           	 $(object).unbind('click');
           	 $(object).click(function(){
               edit(object);
             })
		});
    }
	
<!----------------- look添加 结束 ----------------------------------------------------------->	
	
	
	
	//sous
	$('.cate_search').click(function(){
		
		u_id = $('select[name="uid"]').val();
		
		catid = $('select[name="cat_id"]').val();
		
		is_goods = $('select[name="is_goods_attr"]').val();
		
		bid = $('select[name="brand_id"]').val();;
		
		keys = $('input[name="keyword"]').val();
		
		location.href='<?php echo $thisurl;?>?type=lists&uid='+u_id+'&cat_id='+catid+'&is_goods_attr='+is_goods+'&brand_id='+bid+'&keyword='+keys+'&is_delete=0&sale=<?php echo $_GET['sale']?>';
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
	   $.post('<?php echo $thisurl;?>',{action:'goodsedit',gid:id,val:editval,type:type},function(data){ 
			 obj.html(editval);
           	 $(object).unbind('click');
           	 $(object).click(function(){
               edit_2012127(object);
             })
		});
    }
	
	function open_import(id){
		JqueryDialog.Open('','<?php echo ADMIN_URL;?>vgoods.php?type=ajax_open_import&id='+id,600,460,'frame');
		return false;
	}
</script>
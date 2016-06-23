<div class="contentbox">
     <table cellspacing="2" cellpadding="5" width="100%">
	 <tr>
		<th colspan="7" align="left">商品分类</th>
	</tr>
    <tr>
	   <th width="60"><label><input type="checkbox" class="quxuanall" value="checkbox" />选择</label></th>
	   <th width="40%">分类名称</th>
	   <th>商品数量</th>
	   <th>推荐分类</th>
	   <th>状态</th>
	   <th width="35">排序</th>
	   <th>操作</th>
	</tr>
	<?php 
	if(!empty($catelist)){ 
	foreach($catelist as $row){
	$sid = empty($row['parent_id']) ? $row['id'] : 0;
	?>
	<tr>
	<td><input type="checkbox" name="quanxuan" value="<?php echo $row['id'];?>" class="gids"/></td>
	<td><strong><?php echo $sid>0 ? "<a href=\"javascript:;\" onclick=\"return showhide(this,'".$row['id']."','0')\">[+]</a>&nbsp;" : "";?></strong><?php echo $row['name'];?></td>
	<!--<td><?php echo $row['cat_title'];?></td>-->
	<td><?php echo $row['goods_count'];?></td>
	<td><img src="<?php echo $this->img($row['is_index']==1 ? 'yes.gif' : 'no.gif');?>" alt="<?php echo $row['is_index']==1 ? '0' : '1';?>" class="activeop" lang="is_index" id="<?php echo $row['id'];?>"/></td>
<td><img src="<?php echo $this->img($row['is_show']==1 ? 'yes.gif' : 'no.gif');?>" alt="<?php echo $row['is_show']==1 ? '0' : '1';?>" class="activeop" lang="is_show" id="<?php echo $row['id'];?>"/></td>
<!--	<td><img src="<?php echo $this->img($row['show_in_nav']==1 ? 'yes.gif' : 'no.gif');?>" alt="<?php echo $row['show_in_nav']==1 ? '0' : '1';?>" class="activeop" lang="show_in_nav" id="<?php echo $row['id'];?>"/></td>
--><td><span class="vieworder" id="<?php echo $row['id'];?>"><?php echo $row['sort_order'];?></span></td>
	<td>
	<a href="goods.php?type=cate_info&id=<?php echo $row['id'];?>" title="编辑"><img src="<?php echo $this->img('icon_edit.gif');?>" title="编辑"/></a>&nbsp;
	<img src="<?php echo $this->img('icon_drop.gif');?>" title="删除" alt="删除" id="<?php echo $row['id'];?>" class="delcateid"/>
	</td>
	</tr>
		<?php 
			if(!empty($row['cat_id'])){
				foreach($row['cat_id'] as $rows){
				?>
					<tr class="tab<?php  echo $row['id'];?>">
					<td><input type="checkbox" name="quanxuan" value="<?php echo $rows['id'];?>" class="gids"/></td>
					<td>&nbsp;&nbsp;&nbsp;&nbsp;<strong><?php echo $sid==$rows['parent_id'] ? "<a href=\"javascript:;\" onclick=\"return showhide(this,'".$row['id']."','".$rows['id']."')\">[+]</a>&nbsp;" : "";?></strong>├ &nbsp;<?php echo $rows['name'];?></td>
					<!--<td><?php echo $rows['cat_title'];?></td>-->
					<td><?php echo $rows['goods_count'];?></td>
					<td><img src="<?php echo $this->img($rows['is_index']==1 ? 'yes.gif' : 'no.gif');?>" alt="<?php echo $rows['is_index']==1 ? '0' : '1';?>" class="activeop" lang="is_index" id="<?php echo $rows['id'];?>"/></td>
				<td><img src="<?php echo $this->img($rows['is_show']==1 ? 'yes.gif' : 'no.gif');?>" alt="<?php echo $rows['is_show']==1 ? '0' : '1';?>" class="activeop" lang="is_show" id="<?php echo $rows['id'];?>"/></td>
<!--									<td><img src="<?php echo $this->img($rows['show_in_nav']==1 ? 'yes.gif' : 'no.gif');?>" alt="<?php echo $rows['show_in_nav']==1 ? '0' : '1';?>" class="activeop" lang="show_in_nav" id="<?php echo $rows['id'];?>"/></td>
-->
				<td><span class="vieworder" id="<?php echo $rows['id'];?>"><?php echo $rows['sort_order'];?></span></td>
					<td>
					<a href="goods.php?type=cate_info&id=<?php echo $rows['id'];?>" title="编辑"><img src="<?php echo $this->img('icon_edit.gif');?>" title="编辑"/></a>&nbsp;
					<img src="<?php echo $this->img('icon_drop.gif');?>" title="删除" alt="删除" id="<?php echo $rows['id'];?>" class="delcateid"/>
					</td>
					</tr>
							<?php 
							if(!empty($rows['cat_id'])){ 
								foreach($rows['cat_id'] as $rowss){
								$ssid = !empty($rowss['cat_id']) ? $rows['id'] : 0;
							?>
									<tr class="tab<?php  echo $row['id'];?> tab<?php  echo $rows['id'];?>" style="display:none">
									<td><input type="checkbox" name="quanxuan" value="<?php echo $rowss['id'];?>" class="gids"/></td>
									<td style="padding-left:58px"><strong><?php echo $ssid>0 ? "<a href=\"javascript:;\" onclick=\"return showhide(this,'".$row['id']."','".$rows['id']."','".$rowss['id']."')\">[+]</a>&nbsp;" : "";?></strong>├ &nbsp;<?php echo $rowss['name'];?></td>
									<!--<td><?php echo $rowss['cat_title'];?></td>-->
									<td><?php echo $rowss['goods_count'];?></td>
									<td><img src="<?php echo $this->img($rowss['is_index']==1 ? 'yes.gif' : 'no.gif');?>" alt="<?php echo $rowss['is_index']==1 ? '0' : '1';?>" class="activeop" lang="is_index" id="<?php echo $rowss['id'];?>"/></td>
								<td><img src="<?php echo $this->img($rowss['is_show']==1 ? 'yes.gif' : 'no.gif');?>" alt="<?php echo $rowss['is_show']==1 ? '0' : '1';?>" class="activeop" lang="is_show" id="<?php echo $rowss['id'];?>"/></td>
<!--									<td><img src="<?php echo $this->img($rowss['show_in_nav']==1 ? 'yes.gif' : 'no.gif');?>" alt="<?php echo $rowss['show_in_nav']==1 ? '0' : '1';?>" class="activeop" lang="show_in_nav" id="<?php echo $rowss['id'];?>"/></td>
-->
								<td><span class="vieworder" id="<?php echo $rowss['id'];?>"><?php echo $rowss['sort_order'];?></span></td>
									<td>
									<a href="goods.php?type=cate_info&id=<?php echo $rowss['id'];?>" title="编辑"><img src="<?php echo $this->img('icon_edit.gif');?>" title="编辑"/></a>&nbsp;
									<img src="<?php echo $this->img('icon_drop.gif');?>" title="删除" alt="删除" id="<?php echo $rowss['id'];?>" class="delcateid"/>
									</td>
									</tr>
									<?php 
									if(!empty($rowss['cat_id'])){ 
										foreach($rowss['cat_id'] as $rowsss){
									?>
									<tr class="tab<?php  echo $row['id'];?> tab<?php  echo $rows['id'];?> tab<?php  echo $rowss['id'];?>" style="display:none">
									<td><input type="checkbox" name="quanxuan" value="<?php echo $rowsss['id'];?>" class="gids"/></td>
									<td style="padding-left:90px">├ &nbsp;<?php echo $rowsss['name'];?></td>
									<!--<td><?php echo $rowsss['cat_title'];?></td>-->
									<td><?php echo $rowsss['goods_count'];?></td>
									<td><img src="<?php echo $this->img($rowsss['is_index']==1 ? 'yes.gif' : 'no.gif');?>" alt="<?php echo $rowsss['is_index']==1 ? '0' : '1';?>" class="activeop" lang="is_index" id="<?php echo $rowsss['id'];?>"/></td>
								<td><img src="<?php echo $this->img($rowsss['is_show']==1 ? 'yes.gif' : 'no.gif');?>" alt="<?php echo $rowsss['is_show']==1 ? '0' : '1';?>" class="activeop" lang="is_show" id="<?php echo $rowsss['id'];?>"/></td>
<!--									<td><img src="<?php echo $this->img($rowsss['show_in_nav']==1 ? 'yes.gif' : 'no.gif');?>" alt="<?php echo $rowsss['show_in_nav']==1 ? '0' : '1';?>" class="activeop" lang="show_in_nav" id="<?php echo $rowsss['id'];?>"/></td>
-->
								<td><span class="vieworder" id="<?php echo $rowsss['id'];?>"><?php echo $rowsss['sort_order'];?></span></td>
									<td>
									<a href="goods.php?type=cate_info&id=<?php echo $rowsss['id'];?>" title="编辑"><img src="<?php echo $this->img('icon_edit.gif');?>" title="编辑"/></a>&nbsp;
									<img src="<?php echo $this->img('icon_drop.gif');?>" title="删除" alt="删除" id="<?php echo $rowsss['id'];?>" class="delcateid"/>
									</td>
									</tr>
									<?php
										}
									}
									?>
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
		 <td colspan="7"> <input type="checkbox" class="quxuanall" value="checkbox" />
			  <input type="button" name="button" value="批量删除" disabled="disabled" class="bathdel" id="bathdel"/>
		 </td>
	</tr>
		<?php } ?>
	 </table>
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
   		if(confirm("确定删除吗？将会删除下级分类及所在的商品！考虑清楚吗")){
			createwindow();
			var arr = [];
			$('input[name="quanxuan"]:checked').each(function(){
				arr.push($(this).val());
			});
			var str=arr.join('+'); 
			$.post('<?php echo $thisurl;?>',{action:'cate_dels',ids:str},function(data){
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
   
   $('.delcateid').click(function(){
   		ids = $(this).attr('id');
		thisobj = $(this).parent().parent();
		if(confirm("确定删除吗？将会删除分类下的文章！考虑清楚吗")){
			createwindow();
			$.post('<?php echo $thisurl;?>',{action:'cate_dels',ids:ids},function(data){
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
		cid = $(this).attr('id'); 
		type = $(this).attr('lang');
		obj = $(this);
		$.post('<?php echo $thisurl;?>',{action:'cate_active',active:star,cid:cid,type:type},function(data){
			if(data == ""){
				if(star == 1){
					id = 0;
					src = '<?php echo $this->img('yes.gif');?>';
				}else{
					id = 1;
					src = '<?php echo $this->img('no.gif');?>';
				}
				obj.attr('src',src);
				obj.attr('alt',id);
			}else{
				alert(data);
			}
		});
	});
	
	
	//ajax排序处理
	$('.vieworder').click(function (){ edit(this); });
	function edit(object){
		thisvar = $(object).html();
		ids = $(object).attr('id');
		if(!(thisvar>0)){
			thisvar = 50;
		}
		//$(object).css('background-color','#FFFFFF');
		 if(typeof($(object).find('input').val()) == 'undefined'){
             var input = document.createElement('input');
			 $(input).attr('value', thisvar);
			  $(input).css('width', '25px');
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
	
	function update(id, object){
       var editval = $(object).val();
       var obj = $(object).parent();
	   $.post('<?php echo $thisurl;?>',{action:'cate_sort',id:id,val:editval},function(data){ 
			 obj.html(editval);
           	 $(object).unbind('click');
           	 $(object).click(function(){
               edit(object);
             })
		});
    }
	
	function showhide(obj,a,b,c){
		a = parseInt(a);
		b = parseInt(b);
		c = parseInt(c);
		if(c>0){
			$(".tab"+c).toggle();
			if($(".tab"+c).css("display")=='none'){
				$(obj).html("[+]");
			}else{
				$(obj).html("[-]");
			}
			return false;
		}
		if(b>0){
			//$(".tab"+b).toggle();
			if($(".tab"+b).css("display")=='none'){
				$(obj).html("[-]");
				$(".tab"+b).show();
			}else{
				$(obj).html("[+]");
				$(".tab"+b).hide();
			}
			return false;
		}
		if(a>0){
			var t = $(obj).html();
			if(t=="[+]"){
				$(".tab"+a).hide();
				$(obj).html("[-]");
			}else{
				$(".tab"+a).show();
				$(obj).html("[+]");
			}
			return false;
		}
		return true;
	}
</script>
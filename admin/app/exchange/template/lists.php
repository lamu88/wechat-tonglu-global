<style type="text/css">
table th a{ text-decoration:underline}
</style>
<div class="contentbox">
     <table cellspacing="2" cellpadding="5" width="100%">
	 <tr>
		<th colspan="8" align="left"><span style="float:left">积分商品列表</span><a href="<?php echo ADMIN_URL.'exchange.php?type=info';?>" style="float:right; padding:2px 4px 2px 4px; background:#ededed; border-bottom:2px solid #ccc; border-right:2px solid #ccc">添加商品</a></th>
	</tr>
    <tr>
	   <th width="110"><label><input type="checkbox" class="quxuanall" value="checkbox" />选择</label></th>
	   <th>名称</th>
	   <th>图标</th>
	   <th>所需积分</th>
	   <th>是否显示</th>
	   <th>是否推荐</th>
	   <th>排序</th>
	   <th>操作</th>
	</tr>
	<?php
	if(!empty($rt)){
	foreach($rt as $row){
	?>
	<tr>
	<td><input type="checkbox" name="quanxuan" value="<?php echo $row['goods_id'];?>" class="gids"/><?php echo $row['id'];?></td>
	<td><?php echo $row['goods_name'];?></td>
	<td><img src="<?php echo !empty($row['goods_thumb']) ? 	dirname(ADMIN_URL).'/'.$row['goods_thumb'] : $this->img('no_picture.gif');?>" width="60"/></td>
	<td><?php echo $row['need_jifen'];?></td>
<td><img src="<?php echo $this->img($row['is_on_sale']==1 ? 'yes.gif' : 'no.gif');?>" alt="<?php echo $row['is_on_sale']==1 ? '0' : '1';?>" class="activeop" lang="is_on_sale" id="<?php echo $row['goods_id'];?>"/></td>
<td><img src="<?php echo $this->img($row['is_best']==1 ? 'yes.gif' : 'no.gif');?>" alt="<?php echo $row['is_best']==1 ? '0' : '1';?>" class="activeop" lang="is_best" id="<?php echo $row['goods_id'];?>"/></td>
	<td><span class="vieworder" lang="sort_order" id="<?php echo $row['goods_id'];?>"><?php echo $row['sort_order'];?></span></td>
	<td>
	<a href="exchange.php?type=info&id=<?php echo $row['goods_id'];?>" title="编辑"><img src="<?php echo $this->img('icon_edit.gif');?>" title="编辑"/></a>&nbsp;
	<img src="<?php echo $this->img('icon_drop.gif');?>" title="删除" alt="删除" id="<?php echo $row['goods_id'];?>" class="delgoodsid"/>
	</td>
	</tr>				
	<?php } ?>
	<tr>
		 <td colspan="8">
		 	  <input type="checkbox" class="quxuanall" value="checkbox" />
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
</script>
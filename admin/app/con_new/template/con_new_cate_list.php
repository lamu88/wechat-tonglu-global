<div class="contentbox">
     <table cellspacing="2" cellpadding="5" width="100%">
	 <tr>
		<th colspan="9" align="left">新闻咨询分类</th>
	</tr>
    <tr>
	   <th width="60"><label><input type="checkbox" class="quxuanall" value="checkbox" />选择</label></th>
	   <th width="20%">分类名称</th>
	   <th width="25%">分类标题</th>
	   <th>文章数</th>
	   <!--<th>分类图标</th>-->
	   <th>是否显示在导航栏</th>
	   <th>状态</th>
	   <th width="35">排序</th>
	   <th>录入时间</th>
	   <th>操作</th>
	</tr>
	<?php 
	if(!empty($catelist)){ 
	foreach($catelist as $row){
	?>
	<tr>
	<td><input type="checkbox" name="quanxuan" value="<?php echo $row['id'];?>" class="gids"/></td>
	<td><?php echo $row['name'];?></td>
	<td><?php echo $row['cat_title'];?></td>
	<td><?php echo $row['article_count'];?></td>
	<!--<td><?php echo !empty($row['cat_img']) ? '<img src="../'.$row['cat_img'].'" alt="分类图标" width="60"/>' : '无图标';?></td>-->
	<td><img src="<?php echo $this->img($row['show_in_nav']==1 ? 'yes.gif' : 'no.gif');?>" alt="<?php echo $row['show_in_nav']==1 ? '0' : '1';?>" class="activeop" lang="show_in_nav" id="<?php echo $row['id'];?>"/></td>
<td><img src="<?php echo $this->img($row['is_show']==1 ? 'yes.gif' : 'no.gif');?>" alt="<?php echo $row['is_show']==1 ? '0' : '1';?>" class="activeop" lang="is_show" id="<?php echo $row['id'];?>"/></td>
<td><span class="vieworder" id="<?php echo $row['id'];?>"><?php echo $row['vieworder'];?></span></td>
  <td><?php echo !empty($row['addtime']) ? date('Y-m-d',$row['addtime']) : "无知";?></td>
	<td>
	<a href="con_new.php?type=cateedit&id=<?php echo $row['id'];?>" title="编辑"><img src="<?php echo $this->img('icon_edit.gif');?>" title="编辑"/></a>&nbsp;
	<img src="<?php echo $this->img('icon_drop.gif');?>" title="删除" alt="删除" id="<?php echo $row['id'];?>" class="delcateid"/>
	</td>
	</tr>
		<?php 
			if(!empty($row['cat_id'])){ 
				foreach($row['cat_id'] as $rows){
				?>
					<tr>
					<td><input type="checkbox" name="quanxuan" value="<?php echo $rows['id'];?>" class="gids"/></td>
					<td>&nbsp;&nbsp;├ &nbsp;<?php echo $rows['name'];?></td>
					<td><?php echo $rows['cat_title'];?></td>
					<td><?php echo $rows['article_count'];?></td>
					<!--<td><?php echo !empty($rows['cat_img']) ? '<img src="../'.$rows['cat_img'].'" alt="分类图标"  width="60"/>' : '无图标';?></td>-->
					<td><img src="<?php echo $this->img($rows['show_in_nav']==1 ? 'yes.gif' : 'no.gif');?>" alt="<?php echo $rows['show_in_nav']==1 ? '0' : '1';?>" class="activeop" lang="show_in_nav" id="<?php echo $rows['id'];?>"/></td>
				<td><img src="<?php echo $this->img($rows['is_show']==1 ? 'yes.gif' : 'no.gif');?>" alt="<?php echo $rows['is_show']==1 ? '0' : '1';?>" class="activeop" lang="is_show" id="<?php echo $rows['id'];?>"/></td>
				<td><span class="vieworder" id="<?php echo $rows['id'];?>"><?php echo $rows['vieworder'];?></span></td>
				  <td><?php echo !empty($rows['addtime']) ? date('Y-m-d',$rows['addtime']) : "无知";?></td>
					<td>
					<a href="con_new.php?type=cateedit&id=<?php echo $rows['id'];?>" title="编辑"><img src="<?php echo $this->img('icon_edit.gif');?>" title="编辑"/></a>&nbsp;
					<img src="<?php echo $this->img('icon_drop.gif');?>" title="删除" alt="删除" id="<?php echo $rows['id'];?>" class="delcateid"/>
					</td>
					</tr>
							<?php 
							if(!empty($rows['cat_id'])){ 
								foreach($rows['cat_id'] as $rowss){
							?>
									<tr>
									<td><input type="checkbox" name="quanxuan" value="<?php echo $rowss['id'];?>" class="gids"/></td>
									<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;├ &nbsp;<?php echo $rowss['name'];?></td>
									<td><?php echo $rowss['cat_title'];?></td>
									<td><?php echo $rowss['article_count'];?></td>
									<!--<td><?php echo !empty($rowss['cat_img']) ? '<img src="../'.$rowss['cat_img'].'" alt="分类图标"  width="60"/>' : '无图标';?></td>-->
									<td><img src="<?php echo $this->img($rowss['show_in_nav']==1 ? 'yes.gif' : 'no.gif');?>" alt="<?php echo $rowss['show_in_nav']==1 ? '0' : '1';?>" class="activeop" lang="show_in_nav" id="<?php echo $rowss['id'];?>"/></td>
								<td><img src="<?php echo $this->img($rowss['is_show']==1 ? 'yes.gif' : 'no.gif');?>" alt="<?php echo $rowss['is_show']==1 ? '0' : '1';?>" class="activeop" lang="is_show" id="<?php echo $rowss['id'];?>"/></td>
								<td><span class="vieworder" id="<?php echo $rowss['id'];?>"><?php echo $rowss['vieworder'];?></span></td>
								  <td><?php echo !empty($rowss['addtime']) ? date('Y-m-d',$rowss['addtime']) : "无知";?></td>
									<td>
									<a href="con_new.php?type=cateedit&id=<?php echo $rowss['id'];?>" title="编辑"><img src="<?php echo $this->img('icon_edit.gif');?>" title="编辑"/></a>&nbsp;
									<img src="<?php echo $this->img('icon_drop.gif');?>" title="删除" alt="删除" id="<?php echo $rowss['id'];?>" class="delcateid"/>
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
	<?php } ?>
	<tr>
		 <td colspan="9"> <input type="checkbox" class="quxuanall" value="checkbox" />
			  <input type="button" name="button" value="批量删除" disabled="disabled" class="bathdel" id="bathdel"/>
		 </td>
	</tr>
		<?php } ?>
	 </table>
</div>
<?php  $thisurl = ADMIN_URL.'con_new.php'; ?>
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
   		if(confirm("确定删除吗？将会删除下级分类及所在的文章！考虑清楚吗")){
			createwindow();
			var arr = [];
			$('input[name="quanxuan"]:checked').each(function(){
				arr.push($(this).val());
			});
			var str=arr.join('+'); 
			$.post('<?php echo $thisurl;?>',{action:'delcate',ids:str},function(data){
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
			$.post('<?php echo $thisurl;?>',{action:'delcate',ids:ids},function(data){
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
		$.post('<?php echo $thisurl;?>',{action:'activeop',active:star,cid:cid,type:type},function(data){
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
	   $.post('<?php echo $thisurl;?>',{action:'vieworder',id:id,val:editval},function(data){ 
			 obj.html(editval);
           	 $(object).unbind('click');
           	 $(object).click(function(){
               edit(object);
             })
		});
    }
</script>
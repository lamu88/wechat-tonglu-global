<?php  
$thisurl = ADMIN_URL.'brand.php'; 
if(isset($_GET['asc'])){
$bimg = $this->img('up_list.gif');
$bid = $thisurl.'?type=band_list&desc=brand_id';
$bname = $thisurl.'?type=band_list&desc=brand_name';
$bsite = $thisurl.'?type=band_list&desc=site_url';
$bdesc = $thisurl.'?type=band_list&desc=brand_desc';
$ac = $thisurl.'?type=band_list&desc=is_show';
$order = $thisurl.'?type=band_list&desc=sort_order';
}else{
$bimg = $this->img('down_list.gif');
$bid = $thisurl.'?type=band_list&asc=brand_id';
$bname = $thisurl.'?type=band_list&asc=brand_name';
$bsite = $thisurl.'?type=band_list&asc=site_url';
$bdesc = $thisurl.'?type=band_list&asc=brand_desc';
$ac = $thisurl.'?type=band_list&asc=is_show';
$order = $thisurl.'?type=band_list&asc=sort_order';

}
?>
<style type="text/css">
table th a{ text-decoration:underline}
</style>
<div class="contentbox">
     <table cellspacing="2" cellpadding="5" width="100%">
	 <tr>
		<th colspan="9" align="left">商品品牌列表</th>
	</tr>
    <tr>
	   <th width="110"><label><input type="checkbox" class="quxuanall" value="checkbox" />选择</label>&nbsp;<a href="<?php echo $bid;?>"><img src="<?php echo $bimg;?>" align="absmiddle"/></a></th>
	   <th width="170"><a href="<?php echo $bname;?>">品牌名称</a></th>
	   <th>品牌图标</th>
	   <th>商品数量</th>
	   <th width="90"><a href="<?php echo $ac;?>">是否显示</a></th>
	   <th width="40">推荐</th>
	   <th width="40">热门</th>
	   <th width="40"><a href="<?php echo $order;?>">排序</a></th>
	   <th width="100">操作</th>
	</tr>
	<?php
	if(!empty($brandlist)){ 
	foreach($brandlist as $row){
	?>
	<tr>
	<td><input type="checkbox" name="quanxuan" value="<?php echo $row['id'];?>" class="gids"/><?php echo $row['id'];?></td>
	<td><?php echo $row['name'];?></td>
	<td><?php echo !empty($row['brand_logo']) ? '<img src="../'.$row['brand_logo'].'" width="100"/>' : '无图标';?></td>
	<td><?php echo $row['goods_count'];?></td>
<td><img src="<?php echo $this->img($row['is_show']==1 ? 'yes.gif' : 'no.gif');?>" alt="<?php echo $row['is_show']==1 ? '0' : '1';?>" class="activeop" lang="is_show" id="<?php echo $row['id'];?>"/></td>
	<td><img src="<?php echo $this->img($row['is_promote']==1 ? 'yes.gif' : 'no.gif');?>" alt="<?php echo $row['is_promote']==1 ? '0' : '1';?>" class="activeop" lang="is_promote" id="<?php echo $row['id'];?>"/></td>
	<td><img src="<?php echo $this->img($row['is_hot']==1 ? 'yes.gif' : 'no.gif');?>" alt="<?php echo $row['is_hot']==1 ? '0' : '1';?>" class="activeop" lang="is_hot" id="<?php echo $row['id'];?>"/></td>
	<td><span class="vieworder" id="<?php echo $row['id'];?>"><?php echo $row['sort_order'];?></span></td>
	<td>
	<a href="brand.php?type=band_info&id=<?php echo $row['id'];?>" title="编辑"><img src="<?php echo $this->img('icon_edit.gif');?>" title="编辑"/></a>&nbsp;
	<img src="<?php echo $this->img('icon_drop.gif');?>" title="删除" alt="删除" id="<?php echo $row['id'];?>" class="delcateid"/>
	</td>
	</tr>
				<?php 
				if(!empty($row['brand_id'])){ 
				foreach($row['brand_id'] as $rows){
				?>
				<tr>
				<td><input type="checkbox" name="quanxuan" value="<?php echo $rows['id'];?>" class="gids"/><?php echo $rows['id'];?></td>
				<td>&nbsp;&nbsp;├ &nbsp;<?php echo $rows['name'];?></td>
				<td><?php echo !empty($rows['brand_logo']) ? '<img src="../'.$rows['brand_logo'].'" width="100"/>' : '无图标';?></td>
				<td><?php echo $rows['goods_count'];?></td>
			<td><img src="<?php echo $this->img($rows['is_show']==1 ? 'yes.gif' : 'no.gif');?>" alt="<?php echo $rows['is_show']==1 ? '0' : '1';?>" class="activeop" lang="is_show" id="<?php echo $rows['id'];?>"/></td>
			<td><img src="<?php echo $this->img($rows['is_promote']==1 ? 'yes.gif' : 'no.gif');?>" alt="<?php echo $rows['is_promote']==1 ? '0' : '1';?>" class="activeop" lang="is_promote" id="<?php echo $rows['id'];?>"/></td>
			<td><img src="<?php echo $this->img($rows['is_hot']==1 ? 'yes.gif' : 'no.gif');?>" alt="<?php echo $rows['is_hot']==1 ? '0' : '1';?>" class="activeop" lang="is_hot" id="<?php echo $rows['id'];?>"/></td>
			<td><span class="vieworder" id="<?php echo $rows['id'];?>"><?php echo $rows['sort_order'];?></span></td>
				<td>
				<a href="brand.php?type=band_info&id=<?php echo $rows['id'];?>" title="编辑"><img src="<?php echo $this->img('icon_edit.gif');?>" title="编辑"/></a>&nbsp;
				<img src="<?php echo $this->img('icon_drop.gif');?>" title="删除" alt="删除" id="<?php echo $rows['id'];?>" class="delcateid"/>
				</td>
				</tr>
							<?php 
							if(!empty($rows['brand_id'])){ 
								foreach($rows['brand_id'] as $rowss){
							?>
											<tr>
											<td><input type="checkbox" name="quanxuan" value="<?php echo $rowss['id'];?>" class="gids"/><?php echo $rowss['id'];?></td>
											<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;├ &nbsp;<?php echo $rowss['name'];?></td>
											<td><?php echo !empty($rowss['brand_logo']) ? '<img src="../'.$rowss['brand_logo'].'" width="100"/>' : '无图标';?></td>
											<td><?php echo $rowss['goods_count'];?></td>
										<td><img src="<?php echo $this->img($rowss['is_show']==1 ? 'yes.gif' : 'no.gif');?>" alt="<?php echo $rowss['is_show']==1 ? '0' : '1';?>" class="activeop" lang="is_show" id="<?php echo $rowss['id'];?>"/></td>
											<td><img src="<?php echo $this->img($rowss['is_promote']==1 ? 'yes.gif' : 'no.gif');?>" alt="<?php echo $rowss['is_promote']==1 ? '0' : '1';?>" class="activeop" lang="is_promote" id="<?php echo $rowss['id'];?>"/></td>
											<td><img src="<?php echo $this->img($rowss['is_hot']==1 ? 'yes.gif' : 'no.gif');?>" alt="<?php echo $rowss['is_hot']==1 ? '0' : '1';?>" class="activeop" lang="is_hot" id="<?php echo $rowss['id'];?>"/></td>
											<td><span class="vieworder" id="<?php echo $rowss['id'];?>"><?php echo $rowss['sort_order'];?></span></td>
											<td>
											<a href="brand.php?type=band_info&id=<?php echo $rowss['id'];?>" title="编辑"><img src="<?php echo $this->img('icon_edit.gif');?>" title="编辑"/></a>&nbsp;
											<img src="<?php echo $this->img('icon_drop.gif');?>" title="删除" alt="删除" id="<?php echo $rowss['id'];?>" class="delcateid"/>
											</td>
											</tr>
							<?php }} ?>
				<?php } } ?>
				
	<?php } ?>
	<tr>
		 <td colspan="9"> <input type="checkbox" class="quxuanall" value="checkbox" />
			  <input type="button" name="button" value="批量删除" disabled="disabled" class="bathdel" id="bathdel"/>
		 </td>
	</tr>
		<?php } ?>
	 </table>
	  <?php //$this->element('page',array('pagelink'=>$pagelink));?>
</div>
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
   		if(confirm("确定删除吗？")){
			createwindow();
			var arr = [];
			$('input[name="quanxuan"]:checked').each(function(){
				arr.push($(this).val());
			});
			var str=arr.join('+'); 
			$.post('<?php echo $thisurl;?>',{action:'brand_dels',ids:str},function(data){
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
		if(confirm("确定删除吗？")){
			createwindow();
			$.post('<?php echo $thisurl;?>',{action:'brand_dels',ids:ids},function(data){
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
		bid = $(this).attr('id'); 
		type = $(this).attr('lang');
		obj = $(this);
		$.post('<?php echo $thisurl;?>',{action:'brand_active',active:star,bid:bid,type:type},function(data){
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
	   $.post('<?php echo $thisurl;?>',{action:'brand_order',id:id,val:editval},function(data){ 
			 obj.html(editval);
           	 $(object).unbind('click');
           	 $(object).click(function(){
               edit(object);
             })
		});
    }
</script>
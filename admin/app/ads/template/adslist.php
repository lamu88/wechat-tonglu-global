<?php  
$thisurl = ADMIN_URL.'ads.php'; 
if(isset($_GET['asc'])){
$adname = $thisurl.'?type=adslist&desc=tb1.ad_name';
$adname_img = $this->img('up_list.gif');
$adtag = $thisurl.'?type=adslist&desc=tb2.ad_name';
$ac = $thisurl.'?type=adslist&desc=tb1.is_show';
$dt = $thisurl.'?type=adslist&desc=tb1.addtime';
}else{
$adname = $thisurl.'?type=adslist&asc=tb1.ad_name';
$adname_img = $this->img('down_list.gif');
$adtag = $thisurl.'?type=adslist&asc=tb2.ad_name';
$ac = $thisurl.'?type=adslist&asc=tb1.is_show';
$dt = $thisurl.'?type=adslist&asc=tb1.addtime';
}
?>
<div class="contentbox">
     <table cellspacing="2" cellpadding="5" width="100%">
	 <tr>
		<th colspan="8" align="left">广告列表</th>
	</tr>
    <tr>
	   <th><label><input type="checkbox" class="quxuanall" value="checkbox" />选择</label></th>
	   <th><a href="<?php echo $adname;?>">广告名称</a></th>
	   <th><a href="<?php echo $adtag;?>">广告标签</a><img src="<?php echo $adname_img;?>" align="absmiddle"/></th>
	   <th>广告图片</th>
	   <th><a href="<?php echo $ac;?>">状态</a></th>
	   <th>排序</th>
	   <th><a href="<?php echo $dt;?>">时间</a></th>
	   <th>操作</th>
	</tr>
	<?php 
	if(!empty($adslist)){ 
	foreach($adslist as $row){
	?>
	<tr>
	<td><input type="checkbox" name="quanxuan" value="<?php echo $row['pid'];?>" class="gids"/></td>
	<td><?php echo '['.(empty($row['nickname'])?'总部':$row['nickname']).']'.$row['ad_name'];?></td>
	<td><?php echo $row['ad_tag'];?></td>
	<td><a href="<?php echo $row['ad_url'];?>" target="_blank"><img  src="../<?php echo $row['ad_img'];?>" alt="<?php echo $row['ad_name'];?>" width="80"/></a></td>
	<td><img src="<?php echo $this->img($row['is_show']==1 ? 'yes.gif' : 'no.gif');?>" alt="<?php echo $row['is_show']==1 ? '0' : '1';?>" class="activeop" id="<?php echo $row['pid'];?>"/></td>
	<td><span class="vieworder" id="<?php echo $row['pid'];?>"><?php echo $row['vieworder'];?></span></td>
    <td><?php echo date('Y-m-d',$row['addtime']);?></td>
	<td>
	<a href="ads.php?type=ads_edit&id=<?php echo $row['pid'];?>" title="编辑"><img src="<?php echo $this->img('icon_edit.gif');?>" title="编辑"/></a>&nbsp;
	<img src="<?php echo $this->img('icon_drop.gif');?>" title="删除" alt="删除" id="<?php echo $row['pid'];?>" class="delads"/>
	</td>
	</tr>
	<?php } ?>
	<tr>
		 <td colspan="8"> <input type="checkbox" class="quxuanall" value="checkbox" />
			  <input type="button" name="button" value="批量删除" disabled="disabled" class="bathdel" id="bathdel"/>
		 </td>
	</tr>
		<?php } ?>
	 </table>
	 <?php $this->element('page',array('pagelink'=>$pagelink));?>
</div>
<?php $this->element('showdiv');?>
<script type="text/javascript">
<!--
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
			$.post('<?php echo $thisurl;?>',{action:'delads',pids:str},function(data){
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
   
   $('.delads').click(function(){
		id = $(this).attr('id');
		thisobj = $(this).parent().parent();
		if(confirm("确定删除吗？")){
			createwindow();
			$.post('<?php echo $thisurl;?>',{action:'delads',pids:id},function(data){
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
		pid = $(this).attr('id'); 
		obj = $(this);
		$.post('<?php echo $thisurl;?>',{action:'activeadsop',active:star,pid:pid},function(data){
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
	   $.post('<?php echo $thisurl;?>',{action:'ajax_ads_vieworder',id:id,val:editval},function(data){ 
			 obj.html(editval);
           	 $(object).unbind('click');
           	 $(object).click(function(){
               edit(object);
             })
		});
    }
-->	
</script>
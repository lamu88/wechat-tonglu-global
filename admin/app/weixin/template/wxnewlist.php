<div class="contentbox">
     <table cellspacing="2" cellpadding="5" width="100%">
	 <tr>
		<th colspan="7" align="left"><span style="float:left">图文信息</span><a style=" float:right" href="weixin.php?type=infos">添加信息</a></th>
	</tr>
    <tr>
	   <th width="60"><label><input type="checkbox" class="quxuanall" value="checkbox" />选择</label></th>
	   <th>分类</th>
	   <th>标题</th>
	   <th>状态</th>
	   <th>时间</th>
	    <th>排序</th>
	   <th>操作</th>
	</tr>
	<?php 
	if(!empty($newlist)){ 
	foreach($newlist as $row){
	?>
	<tr>
	<td><input type="checkbox" name="quanxuan" value="<?php echo $row['article_id'];?>" class="gids"/></td>
	<td><?php echo $row['cat_name'];?></td>
	<td><?php echo $row['article_title'];?></td>
	<td><img src="<?php echo $this->img($row['is_show']==1 ? 'yes.gif' : 'no.gif');?>" alt="<?php echo $row['is_show']==1 ? '0' : '1';?>" class="activeop" lang="is_show" id="<?php echo $row['article_id'];?>"/></td>
  <td><?php echo !empty($row['addtime']) ? date('Y-m-d',$row['addtime']) : "无知";?></td>
  <td><span class="vieworder" id="<?php echo $row['article_id'];?>"><?php echo $row['vieworder'];?></span></td>
	<td>
	<a href="weixin.php?type=infos&id=<?php echo $row['article_id'];?>" title="编辑"><img src="<?php echo $this->img('icon_edit.gif');?>" title="编辑"/></a>&nbsp;
	<img src="<?php echo $this->img('icon_drop.gif');?>" title="删除" alt="删除" id="<?php echo $row['article_id'];?>" class="delarticleid"/>
	</td>
	</tr>
	<?php
	 } ?>
	<tr>
		 <td colspan="7"> <input type="checkbox" class="quxuanall" value="checkbox" />
			  <input type="button" name="button" value="批量删除" disabled="disabled" class="bathdel" id="bathdel"/>
		 </td>
	</tr>
		<?php } ?>
	 </table>
	 <?php $this->element('page',array('pagelink'=>$pagelink));?>
</div>
<?php  $thisurl = ADMIN_URL.'weixin.php'; ?>
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
			$.post('<?php echo $thisurl;?>',{action:'ajax_delarticle',ids:str},function(data){
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
   
   $('.delarticleid').click(function(){
   		ids = $(this).attr('id');
		thisobj = $(this).parent().parent();
		if(confirm("确定删除吗？")){
			createwindow();
			$.post('<?php echo $thisurl;?>',{action:'ajax_delarticle',ids:ids},function(data){
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
		$.post('<?php echo $thisurl;?>',{action:'alt_activeop',active:star,cid:cid,type:type},function(data){
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
	   $.post('<?php echo $thisurl;?>',{action:'ajax_vieworder',id:id,val:editval},function(data){ 
			 obj.html(editval);
           	 $(object).unbind('click');
           	 $(object).click(function(){
               edit(object);
             })
		});
    }
</script>
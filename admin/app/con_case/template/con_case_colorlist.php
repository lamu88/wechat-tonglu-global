<div class="contentbox">
	<div class="openwindow"><img src="<?php echo $this->img('loading.gif');?>"  align="absmiddle"/><br />正在删除，请稍后。。。</div>
     <table cellspacing="2" cellpadding="5" width="100%">
	 <tr>
		<th colspan="7" align="left">模板案例颜色分类</th>
	</tr>
    <tr>
	   <th width="60"><label><input type="checkbox" class="quxuanall" value="checkbox" />选择</label></th>
	   <th width="20%">分类名称</th>
	   <th width="25%">分类标题</th>
	   <th>文章数</th>
	   <th width="35">排序</th>
	   <th>录入时间</th>
	   <th>操作</th>
	</tr>
	<?php 
	if(!empty($colorlist)){ 
	foreach($colorlist as $row){
	?>
	<tr>
	<td><input type="checkbox" name="quanxuan" value="<?php echo $row['cat_id'];?>" class="gids"/></td>
	<td><?php echo $row['cat_name'];?></td>
	<td><?php echo $row['cat_title'];?></td>
	<td><?php echo $row['article_count'];?></td>
<td><span class="vieworder" id="<?php echo $row['cat_id'];?>"><?php echo $row['vieworder'];?></span></td>
  <td><?php echo !empty($row['addtime']) ? date('Y-m-d',$row['addtime']) : "无知";?></td>
	<td>
	<a href="con_case.php?type=colorinfo&id=<?php echo $row['cat_id'];?>" title="编辑"><img src="<?php echo $this->img('icon_edit.gif');?>" title="编辑"/></a>&nbsp;
	<img src="<?php echo $this->img('icon_drop.gif');?>" title="删除" alt="删除" id="<?php echo $row['cat_id'];?>" class="delcateid"/>
	</td>
	</tr>
	<?php } ?>
	<tr>
		 <td colspan="7"> <input type="checkbox" class="quxuanall" value="checkbox" />
			  <input type="button" name="button" value="批量删除" disabled="disabled" class="bathdel" id="bathdel"/>
		 </td>
	</tr>
		<?php } ?>
	 </table>
</div>
<?php  $thisurl = ADMIN_URL.'con_case.php'; ?>
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
   		if(confirm("确定删除吗？考虑清楚吗")){
			$('.openwindow').show(200);
			var arr = [];
			$('input[name="quanxuan"]:checked').each(function(){
				arr.push($(this).val());
			});
			var str=arr.join('+'); 
			$.post('<?php echo $thisurl;?>',{action:'delcate_color',ids:str},function(data){
				$('.openwindow').hide(200);
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
		if(confirm("确定删除吗？考虑清楚吗")){
			$('.openwindow').show(200);
			$.post('<?php echo $thisurl;?>',{action:'delcate_color',ids:ids},function(data){
				$('.openwindow').hide(200);
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
	   $.post('<?php echo $thisurl;?>',{action:'vieworder_color',id:id,val:editval},function(data){ 
			 obj.html(editval);
           	 $(object).unbind('click');
           	 $(object).click(function(){
               edit(object);
             })
		});
    }
   
</script>
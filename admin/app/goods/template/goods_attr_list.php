<div class="contentbox">
<style type="text/css">
.contentbox table a{ text-decoration:underline}
.contentbox table a:hover{ text-decoration:none}
</style>
     <table cellspacing="2" cellpadding="5" width="100%">
	 <tr>
		<th colspan="5" align="left">商品属性列表</th>
		<th style="text-align:right"><a href="goods.php?type=goods_attr_info">添加商品属性</a></th>
	</tr>
    <tr>
	   <th>属性名称</th>
	   <th>属性是否可选</th>
	   <th>属性值的录入方式</th>
	   <th>可选值列表</th>
	   <th>排序</th>
	   <th>操作</th>
	</tr>
	<?php 
	if(!empty($rt)){
	foreach($rt as $row){
	?>
	<tr>
	<td>&nbsp;<?php echo $row['attr_name'];?></td>
	<td>
	<?php
	if($row['attr_is_select']==1){
	echo "唯一属性";
	}else if($row['attr_is_select']==2){
	echo "单选属性";
	}else if($row['attr_is_select']==3){
	echo "复选属性";
	}
	?>
	</td>
	<td>
	<?php
	if($row['input_type']==1){
	echo "手工录入";
	}else if($row['input_type']==2){
	echo "从下面的列表中选择";
	}else if($row['input_type']==3){
	echo "多行文本框";
	}
	?>
	</td>
	<td>&nbsp;
	<?php
	echo $row['input_values'];
	?>
	</td>
	<td>&nbsp;<span class="vieworder" id="<?php echo $row['attr_id'];?>"><?php echo $row['sort_order'];?></span></td>
	<td>&nbsp;
	<a href="goods.php?type=goods_attr_info&id=<?php echo $row['attr_id'];?>" title="编辑"><img src="<?php echo $this->img('icon_edit.gif');?>" title="编辑"/></a>&nbsp;
	<img src="<?php echo $this->img('icon_drop.gif');?>" title="删除" alt="删除" id="<?php echo $row['attr_id'];?>" class="delattr"/>
	</td>
	</tr>
	<?php
	 }  } ?>
	 </table>
</div>
<?php  $thisurl = ADMIN_URL.'goods.php'; ?>
<script type="text/javascript">
  //删除
   $('.delattr').click(function(){
   		ids = $(this).attr('id');
		thisobj = $(this).parent().parent();
		if(confirm("确定删除吗")){
			createwindow();
			$.post('<?php echo $thisurl;?>',{action:'goods_attr_del',id:ids},function(data){
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
   
	//ajax排序处理
	$('.vieworder').click(function (){ edit(this); });
	function edit(object){
		thisvar = $(object).html();
		ids = $(object).attr('id');
		if(!(thisvar>0)){
			thisvar = 1;
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
	   $.post('<?php echo $thisurl;?>',{action:'attr_sort',id:id,val:editval},function(data){
			 obj.html(editval);
           	 $(object).unbind('click');
           	 $(object).click(function(){
               edit(object);
             })
		});
    }
	
</script>
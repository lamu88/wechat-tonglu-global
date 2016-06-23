<div class="contentbox">
     <table cellspacing="2" cellpadding="5" width="100%">
	 	<tr>
		<th colspan="9" align="left">自定义导航栏列表</th>
		</tr>
     	<tr>
        	<th>名称</th><th>是否显示</th><th>是否新窗口</th><th>位置</th><th>排序</th><th>操作</th>
        </tr>
		<?php 
		if(!empty($rts)){
		foreach($rts as $row){
		?>
        <tr>
			<td><?php echo $row['name'];?></td>
			<td><img src="<?php echo $this->img($row['is_show']==1 ? 'yes.gif' : 'no.gif');?>" alt="<?php echo $row['is_show']==1 ? '0' : '1';?>" class="activeop" lang="is_show" id="<?php echo $row['id'];?>"/></td>
			<td><img src="<?php echo $this->img($row['is_opennew']==1 ? 'yes.gif' : 'no.gif');?>" alt="<?php echo $row['is_opennew']==1 ? '0' : '1';?>" class="activeop" lang="is_opennew" id="<?php echo $row['id'];?>"/></td>
  			<td><?php echo $row['type']=='top' ? '顶部' : ($row['type']=='bottom' ? '底部' : '中间');?></td>
			<td><span class="vieworder" id="<?php echo $row['id'];?>"><?php echo $row['vieworder'];?></span></td>
			<td>
	<a href="systemconfig.php?type=nav_edit&id=<?php echo $row['id'];?>" title="编辑"><img src="<?php echo $this->img('icon_edit.gif');?>" title="编辑"/></a>&nbsp;
	<img src="<?php echo $this->img('icon_drop.gif');?>" title="删除" alt="删除" id="<?php echo $row['id'];?>" class="delnav"/>
			</td>
		</tr>
		<?php 
		}  
		}  ?>
     </table>
	  <?php $this->element('page',array('pagelink'=>$pagelink));?>
</div>
<?php  $thisurl = ADMIN_URL.'systemconfig.php'; ?>
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
  
   $('.delnav').click(function(){
		id = $(this).attr('id');
		thisobj = $(this).parent().parent();
		if(confirm("确定删除吗？")){
			createwindow();
			$.post('<?php echo $thisurl;?>',{action:'delnav',ids:id},function(data){
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
		tid = $(this).attr('id'); 
		type = $(this).attr('lang');
		obj = $(this);
		$.post('<?php echo $thisurl;?>',{action:'activeop',active:star,cid:tid,type:type},function(data){
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
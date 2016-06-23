<div class="contentbox">
     咚
</div>

<?php  $thisurl = ADMIN_URL.'topic.php'; ?>
<script type="text/javascript" language="javascript">
   $('.delgoodsid').click(function(){
   		ids = $(this).attr('id');
		thisobj = $(this).parent().parent();
		if(confirm("确定删除吗？")){
			createwindow();
			$.get('<?php echo $thisurl;?>',{type:'delgoods',ids:ids},function(data){
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
 </script>
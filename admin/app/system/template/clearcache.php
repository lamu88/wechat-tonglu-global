<div class="contentbox">
  <input type="button" class="clearcache" value="你确定需要毫无犹豫地清空缓存吗?" />
</div>
<?php  $thisurl = ADMIN_URL.'systemconfig.php'; ?>
<script type="text/javascript">
	$('.clearcache').click(function (){
		createwindow();
		$.post('<?php echo $thisurl;?>',{action:'clearcache'},function(data){
				removewindow();
				alert(data);
		});
	});
	
	$('.testfearch').click(function (){
		$.post('<?php echo $thisurl;?>',{action:'testfearch'},function(data){
				alert(data);
		});
	});
</script>

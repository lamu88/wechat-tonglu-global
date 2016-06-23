<div style="padding:10px">
<style type="text/css">
.kjnav a, .kjnav a:visited{background: url(<?php echo $this->img('kjico.png');?>) no-repeat;}
</style>
<div id="artlist">
	<div class="mod">
		<a style="cursor:pointer; padding:3px; border:1px solid #ccc; color:#FF0000" onclick="return ajax_check_update();">检查并开始在线升级</a>
	</div>   	
</div>
<div class="cr"></div>
<div style="padding:10px;">
<p>当前版本：<?php echo $thissn;?></p>
	<div style="border:1px solid #ccc; padding:5px; height:300px; width:350px; overflow-y:auto; margin-top:5px; cursor:pointer; float:left" class="mianban">
	
	</div>
	<div style=" margin-left:10px;border:1px solid #ccc; padding:5px; height:300px; width:400px; overflow-y:auto; margin-top:5px; cursor:pointer; float:left; text-align:left; line-height:18px;" class="ajax_get_new">
	Loading...
	</div>
	<div class="clear"></div>
</div>
</div>
<script type="text/javascript">
function ajax_check_update(){
	if(confirm("确定在线升级吗？将会覆盖原有的部分文件。你备份好了吗")){
		$('.mianban').html('升级前请保证你的站点是否可写权限!<br/>正在检查。。。<br/>');
		ajax_run_step('step1','');
	}
	return false;
}

function ajax_run_step(step,surl){
	var mesobj        = new Object();
	mesobj.step = step;
	mesobj.surl = surl;
	mesobj.site = '<?php echo ADMIN_URL;?>';
	$.ajax({
	   type: "POST",
	   url: "<?php echo ADMIN_URL.'updates.php'?>",
	   data: "action=ajax_check_update&message=" + $.toJSON(mesobj),
	   dataType: "json",
	   success: function(data){
			$('.mianban').append(data.message);
			if(data.step=='step2'){
				ajax_run_step('step2',data.url);
			}
			if(data.step=='step3'){
				ajax_run_step('step3',data.url);
			}
			if(data.step=='step4'){
				ajax_run_step('step4',data.url);
			}
	   } //end sucdess
	}); //end ajax
	
	
}

jQuery(document).ready(function($){
	$.post('<?php echo ADMIN_URL.'updates.php';?>',{action:'ajax_get_new'},function(data){
			$('.ajax_get_new').html(data);	 
	});
});
</script>
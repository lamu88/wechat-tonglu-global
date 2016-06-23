<style type="text/css">
.contentbox li{ width:25%; text-align:left; line-height:26px; float:left}
</style>
<div class="contentbox" style="height:450px; overflow:hidden; overflow-y:auto">
   <table cellspacing="1" cellpadding="5" width="100%">
<!--	 <tr>
		<td>
		<div style="line-height:20px;width:500px; background-color:#e2e8eb; border-bottom:3px solid #bec6ce; border-right:3px solid #bec6ce; text-align:left; padding-left:10px; padding-top:10px; padding-bottom:5px">
		<input name="upload_file" id="upload_file" type="hidden" value="" size="43">
		<iframe id="iframe_t" name="iframe_t" border="0" src="<?php echo ADMIN_URL;?>uploadfile.php?action=&ty=upload_file&tyy=orderexcle&files=" scrolling="no" width="445" frameborder="0" height="25"></iframe>
		</div>
		</td>
	</tr>-->
	<tr>
		<td>
		  <label>
			<input type="submit" name="Submit" value="不要犹豫点击批量更正返佣关系" style="cursor:pointer" onclick="ajax_change_order_state()" /><em>不要经常点击，以免数据出错</em>
		  </label>		
		  </td>
	</tr>
	</table>
</div>
<div class="htmlll"></div>
<script type="text/javascript">
function ajax_change_order_state(){

	$.post('<?php echo ADMIN_URL.'user.php';?>',{action:'ajax_change_order_state'},function(data){
			$('.htmlll').html(data);
	});
}
</script>
	
<div class="contentbox">
<div style="margin-left:10px;">
	 <table cellspacing="2" cellpadding="5" width="100%">
		<tr>
		<td>
		  <div class="returndata" style="width:620px; height:350px; border:1px solid #ccc; overflow:auto"></div>
		  <p>
		    <input type="submit" name="Submit" value="确认转移商品到供应商表?" style="padding-left:4px; padding-right:4px; background-color:#E2E8EB; border-bottom:2px solid #ccc; border-right:2px solid #ccc; cursor:pointer; display:block; padding:3px" onclick="ajax_zhuanyi()"/>
		  </p>
		  </td>
	 	</tr>
	</table>	
	
</div>
</div>
<script language="javascript" type="text/javascript">
k = 0;
page = 1;
function ajax_zhuanyi(k,page){
	$.ajax({
		   type: "GET",
		   url: "<?php echo ADMIN_URL;?>goods.php",
		   data: "type=zhuanyi&kk=" + k + "&maxpage=" + page,
		   dataType: "json",
		   success: function(data){
			 	if (data.kk != "")
				{
					if(data.kk == "1"){
						$('.returndata').html("");
					}
					if(parseInt(data.kk)%5==0){
						$('.returndata').html("");
					}
					$('.returndata').append(data.url);
					ajax_zhuanyi(data.kk,data.maxpage);
				}else{
					if(data.url!=""){
						$('.returndata').append("<p style='color:#fe0000'>"+data.url+"</p>");
					}else{
						$('.returndata').append("<p>转移完毕！</p>");
					}
				}
		   }
		}); 
}
</script>

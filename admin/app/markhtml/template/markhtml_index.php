<label>
<input type="button" class="markindex" value="点击开始生成首页" />
</label>
<div class="returndata" style="border:2px dashed #ccc; height:550px; margin-top:20px; padding:10px;">

</div>
<?php  $thisurl = ADMIN_URL.'markhtml.php'; ?>
<script type="text/javascript">
<!--
	var k=0;
	$('.markindex').click(function(){
		$('.returndata').html("正在生成，请稍等。。。。");
		senddata(k,'index');
	});
	
	function senddata(k,types){
		$.ajax({
		   type: "POST",
		   url: "<?php echo $thisurl;?>",
		   data: "action=marknav&kk=" + k + "&types=" + types,
		   dataType: "json",
		   success: function(data){ 
			 	if (data.kk == "1")
				{
					$('.returndata').html("");
					$('.returndata').append(data.url);
					$('.returndata').append("<p>生成完毕！</p>");
				}else{
					if(data.url!=""){
					$('.returndata').append("<p style='color:#fe0000'>"+data.url+"</p>");
					return false;
					}else{
					$('.returndata').append("<p>生成失败！</p>");
					}
				}
		   } //end sucdess
		}); 
	}	
</script>
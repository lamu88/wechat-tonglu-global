<label>
<input type="button" class="marknav" value="点击开始生成导航菜单" />
<br /><em>生成之后将会覆盖原来旧的文件</em>
</label>
<div class="returndata" style="border:2px dashed #ccc; height:550px; margin-top:20px; padding:10px;">

</div>
<?php  $thisurl = ADMIN_URL.'markhtml.php'; ?>
<script type="text/javascript">
<!--
	var k=0;
	$('.marknav').click(function(){
		$('.returndata').html("正在转载数据，请稍等。。。。");
		senddata(k,'nav');
	});
	
	function senddata(k,types){
		$.ajax({
		   type: "POST",
		   url: "<?php echo $thisurl;?>",
		   data: "action=marknav&kk=" + k + "&types=" + types,
		   dataType: "json",
		   success: function(data){ 
			 	if (data.kk != "")
				{
					if(data.kk == "1"){
						$('.returndata').html("");
					}

					if(parseInt(data.kk)%22==0){
						$('.returndata').html("");
					}
					$('.returndata').append(data.url);
					senddata(data.kk,types);

				}else{
					if(data.url!=""){
					$('.returndata').append("<p style='color:#fe0000'>"+data.url+"</p>");
					}else{
					$('.returndata').append("<p>生成完毕！</p>");
					}
				}
		   } //end sucdess
		}); 
	}	
</script>
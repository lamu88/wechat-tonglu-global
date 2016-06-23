<label>
<input type="button" class="markall" value="点击一键生成全站" />
</label>
<div class="returndata" style="border:2px dashed #ccc; height:550px; margin-top:20px; padding:10px;">

</div>
<?php  $thisurl = ADMIN_URL.'markhtml.php'; ?>
<script type="text/javascript">
<!--
	var k=0;
	$('.markall').click(function(){
		$('.returndata').html("正在转载数据，请稍等。。。。");
		senddata(k,"nav");
	});
	
	function senddata(k,types){
		$.ajax({
		   type: "POST",
		   url: "<?php echo $thisurl;?>",
		   data: "action=markall&kk=" + k + "&types=" + types,
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
					senddata(data.kk,data.type);
					//alert(k);
				}else{					
					if(data.type=='end'){
						$('.returndata').append("<p>生成完毕！</p>");
					}else if(data.type=="cache"){
						if(data.url!=""){
						$('.returndata').append("<p style='color:#fe0000'>"+data.url+"</p>");
						}
					}else{
						kk = 0;
						$('.returndata').html("");
						senddata(kk,data.type);
					}
				}
		   },
		   error: function (){
		   		alert("意外错误！");
		   }
		}); 
	}	
</script>
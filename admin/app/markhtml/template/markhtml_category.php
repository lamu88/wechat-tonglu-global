<label>
<input type="button" class="markcate_all" value="生成所有分类" />
</label>
<label>
<input type="button" class="markcate_two_w" value="生成前两个星期分类" />
</label>
<label>
<input type="button" class="markcate_two_m" value="生成前两月分类" />
</label>
<div class="returndata" style="border:2px dashed #ccc; height:550px; margin-top:20px; padding:10px;">

</div>
<?php  $thisurl = ADMIN_URL.'markhtml.php'; ?>
<script type="text/javascript">
<!--
//jQuery(document).ready(function($){
	var k=0;
		
	$('.markcate_all').click(function(){
		$('.returndata').html("正在转载数据，请稍等。。。。");
		senddata(k,'cate','all');
	});
	
	$('.markcate_two_w').click(function(){ 
		$('.returndata').html("正在转载数据，请稍等。。。。");
		senddata(k,'cate','two_w'); 
	});
	
	$('.markcate_two_m').click(function(){ 
		$('.returndata').html("正在转载数据，请稍等。。。。");
		senddata(k,'cate','two_m'); 
	});
	
	function senddata(k,types,dtime){
		$.ajax({
		   type: "POST",
		   url: "<?php echo $thisurl;?>",
		   data: "action=marknav&kk=" + k + "&types=" + types + "&times=" + dtime,
		   dataType: "json",
		   success: function(data){ 
			 	if (data.kk != "")
				{
					if(data.kk == "1"){
						$('.returndata').html("");
					}
					//$('.returndata').html(data.kk);
					if(parseInt(data.kk)%22==0){
						$('.returndata').html("");
					}
					$('.returndata').append(data.url);
					senddata(data.kk,types,dtime);
					//alert(k);
				}else{
					if(data.url!=""){
					$('.returndata').append("<p style='color:#fe0000'>"+data.url+"</p>");
					}else{
					$('.returndata').append("<p>生成完毕！</p>");
					}
				}
		   }
		}); 
	}	
</script>
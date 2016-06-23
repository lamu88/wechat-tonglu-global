<?php
require_once("../load.php");
?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title>文件上传</title>
<style type="text/css">
body{ margin:0px; padding:0px; text-align:center}
.demo{margin:0px; width:400px; text-align:left}
.demo p{line-height:30px; font-size:12px; margin:0px;}
.btn{position: relative;overflow: hidden;margin-right: 4px;display:inline-block;*display:inline;padding:4px 10px 4px;font-size:14px;line-height:18px;*line-height:20px;color:#fff;text-align:center;vertical-align:middle;cursor:pointer;}
.btn{ width:113px; height:32px; background:url(upbut.jpg) center center no-repeat}
.btn input {position: absolute;top: 0; right: 0;margin: 0;border: solid transparent;opacity: 0;filter:alpha(opacity=0); cursor: pointer; height:32px; width:115px;}
.progress { position:relative; margin-left:100px; margin-top:-24px; width:200px;padding: 1px; border-radius:3px; display:none}
.bar {background-color: green; display:block; width:0%; height:20px; border-radius: 3px; }
.percent { position:absolute; height:20px; display:inline-block; top:3px; left:2%; color:#fff }
.files{height:22px; line-height:22px; margin:5px 0; font-size:12px;}
.delimg{margin-left:20px; color:#090; cursor:pointer}
</style>
<script type="text/javascript" src="jquery.min.js"></script>
<script type="text/javascript" src="jquery.form.js"></script>
</head>

<body>
<div id="main">
   <div class="demo">
   		<div class="btn">
            <span>&nbsp;</span>
            <input id="fileupload" type="file" name="mypic">
        </div>
   </div>
</div>

<script type="text/javascript">
$(function () {
	var bar = $('.bar');
	var percent = $('.percent');
	var showimg = $('#showimg');
	var progress = $(".progress");
	var files = $(".files");
	var btn = $(".btn span");
	$("#fileupload").wrap("<form id='myupload' action='action.php' method='post' enctype='multipart/form-data'></form>");
    $("#fileupload").change(function(){
		$("#myupload").ajaxSubmit({
			dataType:  'json',
			beforeSend: function() {
        		showimg.empty();
				progress.show();
        		var percentVal = '0%';
        		bar.width(percentVal);
        		percent.html(percentVal);
				btn.html("上传中...");
    		},
    		uploadProgress: function(event, position, total, percentComplete) {
        		var percentVal = percentComplete + '%';
        		bar.width(percentVal);
        		percent.html(percentVal);
    		},
			success: function(data) {
				if(data.error=='1'){
					btn.html("&nbsp;");
					alert(data.name);
					location.reload();
					return false;
				}else{
					//files.html("<b>"+data.name+"("+data.size+"k)</b> <span class='delimg' rel='"+data.pic+"'>删除</span>");
					var img = "<?php echo dirname(SITE_URL);?>/"+data.pic;
					//showimg.html("<img src='"+img+"'>");
					btn.html("&nbsp;");
					//parent.document.getElementById("imgimg").value = img;
					window.parent.run(data.pic);
				}
				//parent.document.getElementById('test')=data.pic;
			},
			error:function(xhr){
				btn.html("上传失败");
				bar.width('0')
				files.html(xhr.responseText);
			}
		});
	});
	
	$(".delimg").live('click',function(){
		var pic = $(this).attr("rel");
		$.post("action.php?act=delimg",{imagename:pic},function(msg){
			if(msg==1){
				files.html("删除成功.");
				showimg.empty();
				progress.hide();
			}else{
				alert(msg);
			}
		});
	});
});
</script>
</body>
</html>
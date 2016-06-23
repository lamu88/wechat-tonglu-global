
<?php $this->element('24/top',array('lang'=>$lang)); ?>
<style type="text/css">
body{ background:#FFF !important;}
#main li:hover{ background:#ededed}
</style>
<div id="main" style="min-height:300px;margin-bottom:20px;">
	
	<div class="clear10"></div>
	<div class="loadsss" style="text-align:center">
	<form name="form1" action="/m/daili.php?act=liuyan" method="post">
		<input type="hidden" name="t_uid" value="<?php echo $t_uid; ?>">
		<div><textarea style="line-height:30px; height:100px; border:#CCC 1px solid; width:90%; font-size:16px; padding:5px" name="content"></textarea></div>
		<div><input type="submit" name="submit" value=" 提 交 " style="background:#DB383E; color:#FFF; padding:5px" onclick="return check()" /></div>
	</form>
	
	</div>
</div>
<script type="text/javascript">
function check(){
	content = $('textarea[name="content"]').val();
	if(content=="" || typeof(content)=='undefinde'){
		alert('请输入留言内容');
		return false;
	}
	$('#form1').submit();
	return true;
}

var hh = 0;
var isrun = true;
var tops = 0;
function page_init(){
	hh = $('.v12_ul').height();
	tops = parseInt(hh);
}
//获取滚动条当前的位置 
function getScrollTop() { 
var scrollTop = 0; 
if (document.documentElement && document.documentElement.scrollTop) { 
scrollTop = document.documentElement.scrollTop; 
} 
else if (document.body) { 
scrollTop = document.body.scrollTop; 
} 
return scrollTop; 
} 

//获取当前可是范围的高度 
function getClientHeight() { 
var clientHeight = 0; 
if (document.body.clientHeight && document.documentElement.clientHeight) { 
clientHeight = Math.min(document.body.clientHeight, document.documentElement.clientHeight); 
} 
else { 
clientHeight = Math.max(document.body.clientHeight, document.documentElement.clientHeight); 
} 
return clientHeight; 
} 

//获取文档完整的高度 
function getScrollHeight() { 
return Math.max(document.body.scrollHeight, document.documentElement.scrollHeight); 
}

window.onscroll = function () {

if (getScrollTop() + getClientHeight() == getScrollHeight()) { 
	//tops = getScrollHeight();
	
	$('.loadsss').html('<img src="<?php echo $this->img('loadings.gif');?>" style="width:16px!important; height:16px;" />加载中');
	setTimeout(function(){
		isrun = true;
	},15000);
	if(isrun==true){
		isrun = false;
		$.post('<?php echo ADMIN_URL;?>daili.php',{action:'ajax_myuser_page',tops:tops,hh:hh,level:'<?php echo $level;?>'},function(data){ 
			$('.loadsss').html("");
			if(data!=""){
				tops += hh;
				$('.v12_ul').append(data);
				isrun = true;
			}
		})
	}
} 
}

$(document).ready(function(){
	page_init();
});
</script>
<?php $this->element('24/footer',array('lang'=>$lang)); ?>

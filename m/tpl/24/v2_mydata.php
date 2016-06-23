
<?php $this->element('24/top',array('lang'=>$lang)); ?>
<style type="text/css">
#main li:hover{ background:#ededed}
.dailicenter{ margin:5px;}
.dailicenter li a i{background-size:80%;list-style:decimal; width:20px; height:40px; float:left; margin-left:7%;background:url(<?php echo $this->img('pot.png');?>) 10% center no-repeat}
.dailicenter li a:hover{ background:#cfccbd}
.dailicenter li a span{border-radius:10px; height:24px; line-height:24px; padding-left:15px; padding-right:15px;display:block;background:#497bae; text-align:center; font-size:12px; font-weight:bold; color:#FFF; cursor:pointer; position:absolute;right:6%; top:8px; z-index:99;}
.dailicenter{ margin:5px;border-radius:5px; border:1px solid #dcd9d8; overflow:hidden;}
.dailicenter li{ position:relative; height:44px; line-height:44px;background-image: -webkit-gradient(linear,left top,left bottom,from(#FFFFFF),to(#F1F1F1));background-image: -webkit-linear-gradient(#FFFFFF,#F1F1F1);background-image: linear-gradient(#FFFFFF,#F1F1F1); border-bottom:1px solid #dcd9d8; text-align:center}
.dailicenter li a{ padding-right:10%;font-size:14px; display:block; }
.dailicenter li a:hover{ background:#EAEAEA}

</style>
<div id="main" style="min-height:300px;margin-bottom:20px;">
	<ul class="dailicenter">
		<li>
		<a href="<?php echo ADMIN_URL.'user.php?act=myshare';?>"><i></i>点击链接<span><?php echo empty($rt['userinfo']['share_ucount']) ? '0' : $rt['userinfo']['share_ucount'];?>人</span></a>
		</li>
		<li>
		<a href="<?php echo ADMIN_URL.'user.php?act=myuser';?>"><i></i>成功关注<span><?php echo empty($rt['userinfo']['guanzhu_ucount']) ? '0' : $rt['userinfo']['guanzhu_ucount'];?>人</span></a>
		</li>
		<li>
		<a href="javascript:void(0)"><i></i>下单购买<span><?php echo empty($rt['userinfo']['ordercount']) ? '0' : $rt['userinfo']['ordercount'];?>单</span></a>
		</li>
		<li>
		<a href="<?php echo ADMIN_URL.'daili.php?act=my_is_daili';?>"><i></i>成为分销<span><?php echo empty($rt['userinfo']['fxcount']) ? '0' : $rt['userinfo']['fxcount'];?>人</span></a>
		</li>
		<li style="border-bottom:none; font-weight:bold"><a href="<?php echo ADMIN_URL.'user.php?act=myerweima';?>"><i></i>我的二维码</a></li>
	</ul>
</div>
<script type="text/javascript">
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
		$.post('<?php echo ADMIN_URL;?>daili.php',{action:'ajax_myuser_page',tops:tops,hh:hh},function(data){ 
			$('.loadsss').html("");
			if(data!=""){
				tops += hh;
				$('.v12_ul').append(data);
				isrun = true;
			}else{
				$('.loadsss').html("加载完毕");
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


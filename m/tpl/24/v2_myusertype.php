
<?php $this->element('24/top',array('lang'=>$lang)); ?>
<style type="text/css">
#main li:hover{ background:#ededed}
.dailicenter{ margin:5px;}
.dailicenter li{ position:relative; height:44px; line-height:44px;margin-bottom:7px; border:1px solid #d1d1d1;border-radius:5px; text-align:left;background-image: -webkit-gradient(linear,left top,left bottom,from(#FFFFFF),to(#F1F1F1));background-image: -webkit-linear-gradient(#FFFFFF,#F1F1F1);background-image: linear-gradient(#FFFFFF,#F1F1F1); overflow:hidden}
.dailicenter li a{ font-size:14px; display:block;padding-right:10%;  /*background:url(<?php echo $this->img('404-2.png');?>) 92% center no-repeat*/}
.dailicenter li a i{background-size:80%;list-style:decimal; width:20px; height:40px; float:left; margin-left:7%;background:url(<?php echo $this->img('pot.png');?>) 10% center no-repeat}
.dailicenter li a:hover{ background:#cfccbd}
.dailicenter li a span{border-radius:10px; height:24px; line-height:24px; padding-left:15px; padding-right:15px;display:block;background:#497bae; text-align:center; font-size:12px; font-weight:bold; color:#FFF; cursor:pointer; position:absolute;right:12%; top:8px; z-index:99;}
</style>
<div id="main" style="min-height:300px;margin-bottom:20px;">
	<ul class="dailicenter">
	<?php if($rank=='10'){?>
		<li>
		<a href="<?php echo ADMIN_URL.'daili.php?act=myuser&t=4';?>"><i></i>我的微商<span><?php echo empty($rt['dzcount2']) ? '0' : $rt['dzcount2'];?>人</span></a>
		</li>
		<li>
		<a href="<?php echo ADMIN_URL.'daili.php?act=myuser&t=5';?>"><i></i>我的会员<span><?php echo empty($rt['vipcount']) ? '0' : $rt['vipcount'];?>人</span></a>
		</li>
	<?php }elseif($rank=='11'){ ?>
		<li>
		<a href="<?php echo ADMIN_URL.'daili.php?act=myuser&t=5';?>"><i></i>我的会员<span><?php echo empty($rt['vipcount']) ? '0' : $rt['vipcount'];?>人</span></a>
		</li>
	<?php }else{?>
		<li>
		<a href="<?php echo ADMIN_URL.'daili.php?act=myuser&t=1';?>"><i></i>我的金友<span><?php echo empty($rt['zcount1']) ? '0' : $rt['zcount1'];?>人</span></a>
		</li>
		<li>
		<a href="<?php echo ADMIN_URL.'daili.php?act=myuser&t=2';?>"><i></i>我的银友<span><?php echo empty($rt['zcount2']) ? '0' : $rt['zcount2'];?>人</span></a>
		</li>
	<?php } ?>
		<!--<li>
		<a href="<?php echo ADMIN_URL.'daili.php?act=myuser&t=3';?>"><i></i>我的C级分店<span><?php echo empty($rt['zcount3']) ? '0' : $rt['zcount3'];?>人</span></a>
		</li>-->
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

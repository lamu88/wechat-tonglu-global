<link href="<?php echo ADMIN_URL;?>tpl/24/iscroll.css" rel="stylesheet" type="text/css" />
<link href="<?php echo ADMIN_URL;?>tpl/24/css.css" rel="stylesheet" type="text/css" />
<script src="<?php echo ADMIN_URL;?>tpl/24/iscroll.js" type="text/javascript"></script>
<script type="text/javascript">
var myScroll;
function loaded() {
myScroll = new iScroll('wrapper', {
snap: true,
momentum: false,
hScrollbar: false,
onScrollEnd: function () {
}
 });
}
document.addEventListener('DOMContentLoaded', loaded, false);
</script>
<!--music-->
<div class="banner">
	<div id="wrapper">
		<div id="scroller">
			<ul id="thelist">
			<?php if(!empty($rt))foreach($rt as $k=>$row){?>
							<li><p><?php echo ++$k;?></p><img src="<?php echo SITE_URL.$row['ad_img'];?>"></li>
			<?php } ?>
			</ul>
		</div>
	</div>
    <div class="clr"></div>
</div>

<div class="mainmenu">
	<ul> 
		 <div id="insert1" ></div>
		<li><a href="<?php echo ADMIN_URL.'site.php?act=about';?>" ><img src="<?php echo ADMIN_URL.'tpl/2/images/h_1.png';?>" height="20" />路易劳莎</a></li>
		<li><a href="<?php echo ADMIN_URL.'site.php?act=news';?>" ><img src="<?php echo ADMIN_URL.'tpl/2/images/h_3.png';?>" />最新资讯</a></li>
		<li><a href="<?php echo ADMIN_URL.'in.php';?>" ><img src="<?php echo ADMIN_URL.'tpl/2/images/li1.png';?>" />微商城</a></li>
		<li><a href="<?php echo ADMIN_URL.'site.php?act=shishang';?>" ><img src="<?php echo ADMIN_URL.'tpl/2/images/h_4.png';?>" />时尚美容荟</a></li>
		<li><a href="<?php echo ADMIN_URL.'site.php?act=contact';?>" ><img src="<?php echo ADMIN_URL.'tpl/2/images/h_5.png';?>" />联系我们</a></li>
		<div class="clr"></div>
		<div id="insert2" ></div>
			
	</ul>
</div>

<script>
var count = document.getElementById("thelist").getElementsByTagName("img").length;	

var count2 = document.getElementsByClassName("menuimg").length;
for(i=0;i<count;i++){
 document.getElementById("thelist").getElementsByTagName("img").item(i).style.cssText = " width:"+document.body.clientWidth+"px";

}
document.getElementById("scroller").style.cssText = " width:"+document.body.clientWidth*count+"px";

 setInterval(function(){
myScroll.scrollToPage('next', 0,400,count);
},3500 );
window.onresize = function(){ 
for(i=0;i<count;i++){
document.getElementById("thelist").getElementsByTagName("img").item(i).style.cssText = " width:"+document.body.clientWidth+"px";

}
 document.getElementById("scroller").style.cssText = " width:"+document.body.clientWidth*count+"px";
} 
</script>
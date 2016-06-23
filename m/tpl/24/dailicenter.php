<link type="text/css" rel="stylesheet" href="<?php echo ADMIN_URL;?>tpl/24/css.css?v=1" media="all" />
<?php $this->element('24/top',array('lang'=>$lang)); ?>

<style type="text/css">
body{ background:#fff}
.jbjb{
background-image: -webkit-gradient(linear,left top,left bottom,from(#FBFBFB),to(#D6C6AC));
background-image: -webkit-linear-gradient(#FBFBFB,#D6C6AC);
background-image: -moz-linear-gradient(#FBFBFB,#D6C6AC);
background-image: -ms-linear-gradient(#FBFBFB,#D6C6AC);
background-image: -o-linear-gradient(#FBFBFB,#D6C6AC);
background-image: linear-gradient(#FBFBFB,#D6C6AC);
}
.pw{
border: 1px solid #ddd;
border-radius: 5px;
background-color: #fff; padding-left:5px; padding-right:5px;
-moz-border-radius:5px;/*仅Firefox支持，实现圆角效果*/
-webkit-border-radius:5px;/*仅Safari,Chrome支持，实现圆角效果*/
-khtml-border-radius:5px;/*仅Safari,Chrome支持，实现圆角效果*/
border-radius:5px;/*仅Opera，Safari,Chrome支持，实现圆角效果*/
}
.meCenterTitle {
background: #fff;
line-height: 24px;
height: 24px;
overflow: hidden;
padding: 2px;
color: #999;
padding-left: 10px;
}
.meCenterBox {
position: relative;
}
.meCenterBoxWriting {
position: absolute;
left: 36%;
top: 20%;
}
.meCenterBoxAvatar {
display: block;
position: absolute;
width: 18%;
left: 10%;
top: 20%;
}
.meCenterBoxEditor {
 position: absolute; 
right: 10px;
top: 10px;
}
.meCenterBoxWriting p {
margin-bottom: 8px;
line-height: 14px;
color: #fff;
}
.meCenterBoxWriting p {
margin-bottom: 8px;
line-height: 14px;
color: #fff;
}

.meCenterBoxAvatar a img {
display: block;
border: 6px solid #fff;
border-radius: 10px;
overflow: hidden;
width:100%;
}
.gonglist{border-radius: 5px; border:1px solid #d1d1d1; border-bottom:none; overflow:hidden; margin:5px; display:none}
.gonglist li{ text-align:center;width:100%;line-height:44px; height:44px; float:left; overflow:hidden;padding-bottom:2px;background-image: -webkit-gradient(linear,left top,left bottom,from(#FEFEFE),to(#eeeeee));background-image: -webkit-linear-gradient(#FEFEFE,#eeeeee);background-image: linear-gradient(#FEFEFE,#eeeeee); border-bottom:1px solid #d1d1d1}
.gonglist li a{ font-size:14px; display:block;background:url(<?php echo $this->img('pot.png');?>) 93% center no-repeat}
.gonglist li a:hover{ background:url(<?php echo $this->img('pot.png');?>) 93% center no-repeat #EAEAEA;font-weight:bold;}
.gonglist li.uli2 a{} 
.gonglist li p{ position:relative}
.gonglist li p a{ text-align:left}
.gonglist li p i{ background-size:80%;list-style:decimal; width:20px; height:44px; float:left; margin-left:7%;background:url(<?php echo $this->img('m.png');?>) center center no-repeat; margin-right:3px}
.gonglist li p a span{height:24px; line-height:24px;display:block;text-align:center; font-size:12px; font-weight:bold; color:#B70000; cursor:pointer; position:absolute;right:25%; top:12px; z-index:99;}
.uitem{ margin-bottom:10px;}
.uitem p.pp{ position:relative; height:40px; line-height:40px;margin-bottom:7px; border:1px solid #ccc;border-radius:5px; text-align:left;background-image:-webkit-gradient(linear,left top,left bottom,from(#fff4de),to(#f5e7cc));}
.uitem p.pp a{ font-size:14px; display:block; padding-right:10%; /*background:url(<?php echo $this->img('404-2.png');?>) 92% center no-repeat*/}
.uitem p.pp a i{background-size:80%;list-style:decimal; width:20px; height:40px; float:left; margin-left:7%;background:url(<?php echo $this->img('+h.png');?>) 10% center no-repeat; margin-right:5px}
.uitem p.pp a:hover{ background:#fff4de; font-weight:bold}
.uitem p.pp a span{border-radius:10px; height:24px; line-height:24px; padding-left:15px; padding-right:15px;display:block;background:#ff0000; text-align:center; font-size:12px; font-weight:bold; color:#FFF; cursor:pointer; position:absolute;right:10%; top:8px; z-index:99;}

.userindex a{ display:block; text-align:center; float:left; width:33.3%}
.userindex a p{ padding:10px;}
.userindex a img{ border:2px solid #FFF}
.userindex a:hover img{ border:2px solid #de4943}
</style>
<link rel="stylesheet" type="text/css" href="<?php echo ADMIN_URL; ?>css/styles.css?v=12"/>
<link rel="stylesheet" type="text/css" href="<?php echo ADMIN_URL; ?>css/jquery.mobile-1.3.2.min.css?v=12"/>
<?php $ad = $this->action('banner','banner','会员中心',1);?>
<div style="min-height:300px; padding-bottom:10px; font-size:14px; background:#FFF" class="ucenter">

  	<div data-role="content" class="ui-content" role="main" style="padding-top:8px; overflow:inherit">
		<!--
		<div class="uitem">
			<p class="pp">
				<a href="javascript:;" onclick="ajax_show_sub(1,this);"><i></i>我的经销商<span><?php echo empty($rt['zcount']) ? '0' : $rt['zcount'];?>人</span></a>
			</p>
			<ul class="gonglist gg1">
				<li class="uli6"><p><a href="javascript:void(0)"><i></i>合伙人<span><?php echo empty($rt['zcount1']) ? '0' : $rt['zcount1'];?>人</span></a></p></li>
				<li class="uli9"><p><a href="javascript:void(0)"><i></i>小伙伴<span><?php echo empty($rt['zcount2']) ? '0' : $rt['zcount2'];?>人</span></a></p></li>
				<li class="uli10"><p><a href="javascript:void(0)"><i></i>店友<span><?php echo empty($rt['zcount3']) ? '0' : $rt['zcount3'];?>人</span></a></p></li>		
				<div class="clear"></div>
			</ul>
		</div>
		-->
		<div class="uitem">
			<!--
			<p class="pp">
				<a href="javascript:;" onclick="ajax_show_sub(2,this);"><i></i>我的推广<span><?php echo $rt['userinfo']['share_ucount'] > 0 ? $rt['userinfo']['share_ucount'] : '0';?>人</span></a>
			</p>
			<ul class="gonglist gg2">
			-->
			<ul class="gonglist gg2" style="display:block">
				<li class="uli10"><p><a href="<?php echo ADMIN_URL.'daili.php?act=my_is_daili';?>"><i></i>一级分销<span><?php echo empty($rt['userinfo']['fxcount']) ? '0' : $rt['userinfo']['fxcount'];?>人</span></a></p></li>
				<li class="uli10"><p><a href="javascript:void(0)"><i></i>分佣总计<span><?php echo empty($rt['userinfo']['myfenyong']) ? '0.00' : $rt['userinfo']['myfenyong'];?>元</span></a></p></li>
				<li class="uli6"><p><a href="<?php echo ADMIN_URL.'user.php?act=myshare';?>"><i></i>点击链接<span><?php echo $rt['userinfo']['share_ucount'] > 0 ? $rt['userinfo']['share_ucount'] : '0';?>人</span></a></p></li>
				<li class="uli9"><p><a href="<?php echo ADMIN_URL.'user.php?act=myuser';?>"><i></i>成功关注<span><?php echo $rt['userinfo']['guanzhu_ucount'] > 0 ? $rt['userinfo']['guanzhu_ucount'] : '0';?>人</span></a></p></li>
				<li class="uli10"><p><a href="javascript:void(0)"><i></i>下单购买<span><?php echo empty($rt['userinfo']['ordercount']) ? '0' : $rt['userinfo']['ordercount'];?>单</span></a></p></li>	
				<li class="uli10"><p><a href="<?php echo ADMIN_URL.'user.php?act=myerweima';?>"><i></i>我的二维码</a></p></li>	
				<div class="clear"></div>
			</ul>
		</div>
		<!--
		<div class="uitem">
			<p class="pp">
				<a href="<?php echo ADMIN_URL;?>daili.php?act=postmoney" style="background:url(<?php echo $this->img('404-2.png');?>) 90% center no-repeat"><i style="background:url(<?php echo $this->img('x.png');?>) 10% center no-repeat"></i>申请提款</a>
			</p>
		</div>
		
		<div class="uitem">
			<p class="pp">
				<a href="<?php echo ADMIN_URL;?>daili.php?act=postmoneydata" style="background:url(<?php echo $this->img('404-2.png');?>) 90% center no-repeat"><i style="background:url(<?php echo $this->img('x.png');?>) 10% center no-repeat"></i>提款记录</a>
			</p>
		</div>

		<div class="uitem">
			<p class="pp">
				<a href="<?php echo ADMIN_URL.'daili.php?act=gonggao';?>" style="background:url(<?php echo $this->img('404-2.png');?>) 90% center no-repeat"><i style="background:url(<?php echo $this->img('x.png');?>) 10% center no-repeat"></i>代理公告</a>
			</p>
		</div>
		-->
		<!--<div class="uitem">
			<p class="pp">
				<a href="<?php echo ADMIN_URL;?>user.php?act=mygift"><i style="float:right;background:url(<?php echo $this->img('bottomNavRecommend.png');?>) 10% center no-repeat"></i>我的礼包</a>
			</p>
		</div>
		
		<div class="uitem">
			<p class="pp">
				<a href="<?php echo ADMIN_URL.'user.php?act=zpoints';?>"><i style="float:right;background:url(<?php echo $this->img('bottomNavRecommend.png');?>) 10% center no-repeat "></i>积分榜</a>
			</p>
		</div>-->
		
		
  </div>
  
</div>
<script type="text/javascript">
function ajax_show_sub(k,obj){
	$(".gg"+k).toggle();
	ll = $(".gg"+k).css('display');
	if(ll=='none'){
		$(obj).find('i').css('background','url(<?php echo $this->img('+h.png');?>) 10% center no-repeat');
	}else{
		$(obj).find('i').css('background','url(<?php echo $this->img('-h.png');?>) 10% center no-repeat');
	}
}
function ajax_checked_fenxiao(obj){
	//createwindow();
	$.post('<?php echo ADMIN_URL;?>user.php',{action:'ajax_checked_fenxiao'},function(data){ 
			//removewindow();
			if(data=='1'){
				window.location.href='<?php echo ADMIN_URL.'user.php?act=dailicenter';?>';
			}else{
				$(obj).parent().parent().hide(200);
				$('.ajax_checked_fenxiao').show();
				$('.ajax_checked_fenxiao').html(data);
				return false;
			}
	})
	return false;
}
</script>
<?php $this->element('24/footer',array('lang'=>$lang)); ?>


<link type="text/css" rel="stylesheet" href="<?php echo ADMIN_URL;?>tpl/24/css.css?v=1" media="all" />
<?php $this->element('24/top',array('lang'=>$lang)); ?>

<link rel="stylesheet" type="text/css" href="<?php echo ADMIN_URL; ?>css/styles.css?v=12"/>
<link rel="stylesheet" type="text/css" href="<?php echo ADMIN_URL; ?>css/jquery.mobile-1.3.2.min.css?v=12"/>
<?php $ad = $this->action('banner','banner','会员中心',1);?>
<div style="min-height:300px; padding-bottom:10px; font-size:14px; background:#FFF" class="ucenter">

  	<div data-role="content" class="ui-content" role="main" style="padding-top:8px; overflow:inherit">
		<div class="uitem">
			<p class="pp">
				<a href="javascript:void(0);" onclick="ajax_show_sub(1,this);" style="background:url(<?php echo $this->img('404-2.png');?>) 90% center no-repeat"><i style="background:url(<?php echo $this->img('x.png');?>) 10% center no-repeat"></i>我的收入</a>
			</p>
			<ul class="gonglist gg1">
				<li class="uli6"><p><a href="javascript:void(0)"><i></i>我的佣金<span><?php echo empty($rt['userinfo']['allmoney']) ? '0' : $rt['userinfo']['allmoney'];?>元</span></a></p></li>
				<li class="uli6"><p><a href="<?php echo ADMIN_URL.'daili.php?act=postmoney';?>"><i></i>可提现金额<span><?php echo empty($rt['userinfo']['mymoney']) ? '0' : $rt['userinfo']['mymoney'];?>元</span></a></p></li>
				<li class="uli6"><p><a href="javascript:void(0)"><i></i>购物币<span><?php echo empty($rt['userinfo']['mygouwubi']) ? '0' : $rt['userinfo']['mygouwubi'];?>个</span></a></p></li>
				<div class="clear"></div>
			</ul>
		</div>

		<div class="uitem">
			<p class="pp">
				<a href="javascript:;" onclick="ajax_show_sub(2,this);"><i style="background:url(<?php echo $this->img('x.png');?>) 10% center no-repeat"></i>绩效分红<span><?php echo $rt['userinfo']['jixiao'] > 0 ? $rt['userinfo']['jixiao'] : '0';?>元</span></a>
			</p>
		</div>
		
		<div class="uitem">
			<p class="pp">
				<a href="javascript:;" onclick="ajax_show_sub(3,this);"><i></i>全球分红</a>
			</p>
			<ul class="gonglist gg3">
				<li class="uli6"><p><a href="javascript:void(0)"><i></i>累计全球分红<span><?php echo !empty($rt['userinfo']['quanqiu']) ? $rt['userinfo']['quanqiu'] : '0.00';?>元</span></a></p></li>
				<li class="uli9"><p><a href="javascript:void(0)"><i></i>昨日全球分红<span><?php echo !empty($rt['userinfo']['yesterdaymoney']) ? $rt['userinfo']['yesterdaymoney'] : '0.00';?>元</span></a></p></li>
				<div class="clear"></div>
			</ul>
		</div>
		
		<div class="uitem">
			<p class="pp">
				<a href="javascript:;" onclick="ajax_show_sub(4,this);"><i></i>我的贡献</a>
			</p>
			<ul class="gonglist gg4">
				<li class="uli6"><p><a href="javascript:void(0)"><i></i>税金<span><?php echo !empty($rt['fei']['shuijin']) ? $rt['fei']['shuijin']: '0.00';?>元</span></a></p></li>
				<li class="uli9"><p><a href="javascript:void(0)"><i></i>旅游基金<span><?php echo !empty($rt['fei']['lvyou']) ? $rt['fei']['lvyou']: '0.00';?>元</span></a></p></li>
				<li class="uli6"><p><a href="javascript:void(0)"><i></i>公益基金<span><?php echo !empty($rt['fei']['gongyi']) ? $rt['fei']['gongyi']: '0.00';?>元</span></a></p></li>
				<div class="clear"></div>
			</ul>
		</div>
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


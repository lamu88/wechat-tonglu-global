
<style type="text/css">
body{ background:#FFF !important;}
.jbjb{background:#FDCE81}
.cengji{background:#FDCE81; line-height:30px; text-align:center}
</style>
<link rel="stylesheet" type="text/css" href="<?php echo ADMIN_URL; ?>css/styles.css?v=12"/>
<link rel="stylesheet" type="text/css" href="<?php echo ADMIN_URL; ?>css/jquery.mobile-1.3.2.min.css?v=12"/>
<?php $ad = $this->action('banner','banner','会员中心',1);?>
<div style="min-height:300px; padding-bottom:10px;" class="ucenter">
	<div class="meCenter">
		<ul class="meCenterBox">
		  <li class="meCenterBoxWriting">
			<p>会员ID：<?php echo $rt['userinfo']['user_id'];?><!--&nbsp;&nbsp;&nbsp;积分:<?php echo empty($rt['userinfo']['mypoints']) ? '0' : $rt['userinfo']['mypoints'];?>--></p>
			<p>昵称：<?php echo empty($rt['userinfo']['nickname']) ? '未知' : $rt['userinfo']['nickname'];?></p>
			<p>
			<?php if(empty($rt['userinfo']['subscribe_time'])){ ?>
			关注时间：<font class="price1"><?php echo date('Y-m-d',$rt['userinfo']['reg_time']);?></font>
			<?php 
			}else{
			?>
			关注时间：<font class="price"><?php echo date('Y-m-d',$rt['userinfo']['subscribe_time']);?></font>
			<?php
			}
			?>
			</p>
			<p>族长：<?php echo $rt['userinfo']['level_name']=='会员'?'否':'是' ?></p>
			<p>会员级别：<?php echo $rt['userinfo']['level_name'];?></p>
		  </li>
		  <li class="meCenterBoxAvatar" style="max-height:120px; overflow:hidden"><a href="<?php echo ADMIN_URL;?>user.php?act=myinfos_u" data-ajax="false"><img src="<?php echo !empty($rt['userinfo']['headimgurl']) ? $rt['userinfo']['headimgurl'] : (!empty($rt['userinfo']['avatar']) ? SITE_URL.$rt['userinfo']['avatar'] : $this->img('noavatar_big.jpg'));?>" style="border-radius:50%; padding:1px; width:97%"></a></li>
		  <li><?php  if(!empty($ad['ad_img'])){?><img src="<?php echo SITE_URL.$ad['ad_img'];?>" width="100%" style="min-height:100px"><?php }else{?><p style="display:block; width:100%; min-height:130px;" class="jbjb"></p><?php } ?></li>
		</ul>
        </div>
	<div style="background:#E03106; height:8px">&nbsp;</div>
	<br />
	<div class="cengji">
		<?php
			switch($cengji){
				case 1:
					echo "发放超级粉丝红包";
					break;
				case 2:
					echo "发放铁杆粉丝红包";
					break;
				case 3:
					echo "发放忠实粉丝红包";
					break;
			}
		?>
	</div>
	<br />
	<div data-role="content" class="ui-content" role="main" style="font-size:1rem">
		<?php
			foreach($sn as $key=>$s){
				echo $s[0]['order_id'].'订单验证码：<br />';
				foreach($s as $k=>$n){
					echo $n['goods_pass'];
					echo '&nbsp;&nbsp;';
				}
				if($s[0]['is_use']==1){
					echo "&nbsp;&nbsp;<a href='".ADMIN_URL."hongbao.php?act=fahuo&oid=".$s[0]['order_id']."' style='background:#DB383E; color:#FFF'> 申领红包 </a>";
				}elseif($s[0]['is_use']==2){
					echo "已申领";
				}
				echo "<br /><hr style='margin-top:5px; border:#FDCE81 1px solid' /><br />";
			}
		
		?>
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


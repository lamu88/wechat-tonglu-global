
<?php $this->element('24/top',array('lang'=>$lang)); ?>
<style type="text/css">
#main li:hover{ background:#ededed}
.dailicenter{ margin:10px;}
.dailicenter li{ position:relative; height:60px; margin-bottom:10px; border:1px solid #d1d1d1;text-align:center;background:#F3F3F3}
.dailicenter li a{ height:60px; font-size:14px; display:block;padding-right:10%; background:url(<?php echo $this->img('pot.png');?>) 93% center no-repeat}
.dailicenter li a:hover{ background:#EAEAEA;background:url(<?php echo $this->img('pot.png');?>) 93% center no-repeat}
.imgimgs{background:#FFF;width:40px; height:40px; float:left; margin-right:10px; margin-top:9px; margin-left:10px;-moz-border-radius:21px;-webkit-border-radius:21px;border-radius:21px;}
.shopnav{height:80px; background:#ededed; position:relative; }
.shopfav{ display:block; width:40px; height:26px; background:url(<?php echo $this->img('star-off.png');?>) center 5px no-repeat #ededed; position:absolute; right:10px; top:-45px; z-index:9;border-radius: 5px 5px 0px 0px; text-align:center; padding-top:25px;}
.imgimgs { position:absolute; left:10px; top:-5px; z-index:99;background: #FFF;width: 50px;height: 50px;border-radius:50px;}
.gototop{ display:block; height:50px;  position:fixed; z-index:10}
.gototop .pushf,.gototop .addcar{ height:22px; line-height:22px; width:70px; font-size:12px; float:none}
.gototop .pushf{ position:absolute; right:10px; top:0px; z-index:99}
.gototop .addcar{ position:absolute; right:10px; top:25px; z-index:99}
</style>
<div id="main" style="min-height:300px;margin-bottom:20px;">
	<p><img src="<?php echo SITE_URL.$rt['article_img'];?>" style="width:100%; max-width:100%"/></p>
	<div class="shopnav">
	<img src="<?php echo SITE_URL.$rt['article_img'];?>" class="imgimgs">
	<a href="javascirpt:;" class="shopfav">收藏</a>
		<div style="padding-left:80px; line-height:26px;font-size:14px;">
		<p><?php echo $rt['article_title'];?></p>
		<p style="background:url(<?php echo $this->img('wz.png');?>) 3px center no-repeat; padding-left:25px"><?php echo $rt['address'];?></p>
		<p style="background:url(<?php echo $this->img('pne.png');?>) left center no-repeat; padding-left:25px;"><?php echo $rt['author'];?></p>
		</div>
	</div>
	<div style="padding:10px; line-height:22px">
		<?php echo $rt['meta_desc'];?>
	</div>
</div>
<p class="gototop">
		<a href="<?php echo ADMIN_URL.'user.php?act=shopyuyue&id='.$rt['article_id'];?>"><input type="button" class="pushf" value="马上预约" style="cursor:pointer;"></a>
        <input type="button" id="cart" class="addcar" value="一键导航" style="cursor:pointer;">
</p>
<?php $this->element('24/footer',array('lang'=>$lang)); ?>
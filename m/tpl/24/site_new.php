<link href="<?php echo ADMIN_URL;?>tpl/2/css.css" rel="stylesheet" type="text/css" />
<style type="text/css">
body{ background:url(<?php echo $bgimg;?>) repeat}
</style>
<a name="news"></a>
<div id="ui-header">
<div class="fixed">
<a class="ui-title" id="popmenu" style="color:#FFF"><?php echo $rt['cat']['cat_name'];?></a>
<a class="ui-btn-left_pre" href="javascript:history.go(-1)"></a>
<a class="ui-btn-right_home" href="<?php echo ADMIN_URL.'site.php';?>"></a>
</div>
</div>
<div style="height:46px; clear:both"></div>

<div class="news">
	<ul>
	<?php if(!empty($rt['art']))foreach($rt['art'] as $row){?>
		<li>
		<a style="display:block" href="<?php echo ADMIN_URL.'site.php?act=newinfo&id='.$row['article_id'];?>">
			<div class="liLeft">
				<div class="llogo" style="position:relative">
					<div style="position:absolute; top:20%; right:20px; z-index:99; width:90%; text-align:center; color:#FFF; line-height:18px">
					<span style="font-size:18px; font-weight:bold"><?php echo date('m',$row['addtime']);?></span>/<span style="font-size:14px;"><?php echo date('d',$row['addtime']);?></span>
					<br/><span style="font-size:14px;"><?php echo date('Y',$row['addtime']);?></span>
					</div>
					<img src="<?php echo ADMIN_URL.'tpl/2/images/newbu.png';?>" style="width:90%; float:right; margin-right:15px;" />
				</div>
			</div>
			<div class="liRight">
				<div style="padding:8px; padding-bottom:0px; position:relative;">
				<h1 style="font-size:20px; padding-bottom:10px"><?php echo $row['article_title'];?></h1>
				<img src="<?php echo SITE_URL.$row['article_img'];?>" style="width:100%;" />
				<p style="height:50px; line-height:50px; border-top:1px solid #ededed; margin-top:15px; background:url(<?php echo ADMIN_URL.'tpl/2/images/d.png';?>)  right center no-repeat; font-size:18px; font-weight:bold">
					查看全文
				</p>
				<img src="<?php echo ADMIN_URL.'tpl/2/images/newtop_b.png';?>" style="width:100%; position:absolute; bottom:-6px; left:0px; z-index:99" />
				</div>
			</div>
			<div class="clear"></div>
			</a>
		</li>
	<?php } ?>
	</ul>
</div>
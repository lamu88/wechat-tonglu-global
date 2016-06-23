
<?php $this->element('24/top',array('lang'=>$lang)); ?>
<style type="text/css">
#main li:hover{ background:#ededed}
.dailicenter{ margin:10px;}
.dailicenter li{ position:relative; height:60px; margin-bottom:10px; border:1px solid #d1d1d1;text-align:center;background-image: -webkit-gradient(linear,left top,left bottom,from(#FFFFFF),to(#F1F1F1));background-image: -webkit-linear-gradient(#FFFFFF,#F1F1F1);background-image: linear-gradient(#FFFFFF,#F1F1F1); border-radius: 5px; overflow:hidden}
.dailicenter li a{ height:60px; font-size:14px; display:block;padding-right:10%; background:url(<?php echo $this->img('pot.png');?>) 93% center no-repeat}
.dailicenter li a:hover{ background:#EAEAEA;background:url(<?php echo $this->img('pot.png');?>) 93% center no-repeat}
.imgimgs{background:#FFF;width:40px; height:40px; float:left; margin-right:10px; margin-top:9px; margin-left:10px;-moz-border-radius:21px;-webkit-border-radius:21px;border-radius:21px;}
</style>
<div id="main" style="min-height:300px;margin-bottom:20px;">
	<ul class="dailicenter">
	<?php if(!empty($rt))foreach($rt as $row){?>
		<li>
		<a href="<?php echo ADMIN_URL.'user.php?act=shopinfo&id='.$row['article_id'];?>">
		<img src="<?php echo SITE_URL.$row['article_img'];?>" class="imgimgs" />
		<p style=" text-align:left; font-size:14px; color:#000; padding-top:12px; padding-bottom:2px;">
		<?php echo $row['article_title'];?>
		</p>
		<p style=" text-align:left;font-size:12px; color:#999;padding-top:2px; padding-bottom:10px;">
		<?php echo $row['author'];?>
		</p>
		</a>
		</li>
	 <?php } ?>
	</ul>
</div>
<?php $this->element('24/footer',array('lang'=>$lang)); ?>


<?php $this->element('24/top',array('lang'=>$lang)); ?>
<?php $this->element('guanzhu',array('shareinfo'=>$lang['shareinfo']));?>

<div id="main">
<style type="text/css">
.search_index {
margin: 5px 0px 3px 5px;
height: 46px;
border-radius: 2px;
background: url(<?php echo $this->img('input_bg.png');?>) repeat-x top left;
}
.search_index .right {
width: 64px;
float: right;
text-align: left;
}
.search_index .left {
text-align: center; height:46px;
}
.search_index .input1 {
height: 46px;
line-height: 46px;
text-indent: 5px;
color: #787575;
border: none;
background: url(<?php echo $this->img('611.png');?>) no-repeat center left;
display: block;
float: left;
width: 75%;
}
.goodslists{ min-height:300px;}
</style>
<ul class="goodslists">
<?php if(!empty($rt['lists']))foreach($rt['lists'] as $k=>$row){?>
	<li style="width:50%; float:left;">
		<div style="padding:4px;">
		<a style="background:#fff; padding:5px; display:block;border:1px solid #ededed;border-radius:5px;" href="<?php echo ADMIN_URL.'exchange.php?id='.$row['goods_id'];?>">
			<div style=" height:120px; overflow:hidden; text-align:center;">
				<img src="<?php echo SITE_URL.$row['goods_img'];?>" style="max-height:99%; max-width:99%;display:inline;" alt="<?php echo $row['goods_name'];?>"/>
			</div>
			<p style="line-height:20px; height:20px; overflow:hidden; text-align:center"><?php echo $row['goods_name'];?></p>
			<p style="line-height:22px; height:22px; overflow:hidden; text-align:center; background:#fafafa;">所需积分:<b class="price" style="font-size:12px;"><?php echo $row['need_jifen'];?></b></p>
		</a>
		</div>
	</li>
<?php } ?>
<div class="clear"></div>
</ul>
</div>
<?php $this->element('24/footer',array('lang'=>$lang)); ?>
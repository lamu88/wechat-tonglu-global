<link type="text/css" rel="stylesheet" href="<?php echo ADMIN_URL;?>tpl/24/css.css?v=13" media="all" />
<link href="<?php echo ADMIN_URL.'tpl/24/';?>main.css" rel="stylesheet" type="text/css" />
<link href="<?php echo ADMIN_URL.'tpl/24/';?>cates.css?v=4" rel="stylesheet" type="text/css" />
<?php $this->element('guanzhu',array('shareinfo'=>$lang['shareinfo']));?>
<?php $this->element('24/top',array('lang'=>$lang)); ?>

<style type="text/css">
.search_index {
margin: 7px 5px 7px 5px;
height: 46px;
border-radius: 2px;
background: url(<?php echo $this->img('input_bg.png');?>) repeat-x top left;
}
.search_index .right {
width: 44px;
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
</style>
<form id="form1" name="form1" method="get" action="<?php echo ADMIN_URL;?>catalog.php">
  <div class="search_index">
    <div class="right"><input type="image" src="<?php echo $this->img('submit4.png');?>" value="" style="height:25px;margin-top:10px; margin-right:10px"></div>
    <div class="left"><input type="text" name="keyword" id="title" class="input1" value="<?php echo !empty($keyword)&&!in_array($keyword,array('is_promote','is_qianggou','is_hot','is_best','is_new')) ? $keyword : "寻找你喜欢的宝贝、店铺";?>" onfocus="if(this.value=='寻找你喜欢的宝贝、店铺'){this.value='';}" onblur="if(this.value==''){this.value='寻找你喜欢的宝贝、店铺';}"></div>
  </div>
</form>

<div class="cateindex" style="background:#FFF">
	<div class="catesleft">
		<div class="catesleft2">
			<ul>
			<li id="tabs1">
					<a href="<?php echo ADMIN_URL.'cates.php';?>"<?php echo !isset($_GET['cid']) ? ' class="ac"' : '';?>>热点品牌</a>
			</li>
			<?php $k=1;if(!empty($rt['menu']))foreach($rt['menu'] as $row){?>
				<li id="tabs<?php echo ++$k;?>">
					<a href="<?php echo ADMIN_URL.'cates.php?cid='.$row['id'];?>"<?php echo isset($_GET['cid'])&&$cid==$row['id'] ? ' class="ac"' : '';?>><?php echo $row['name'];?></a>
				</li>
			<?php } ?>
			</ul>
		</div>
	</div>
	<div class="catesright">
		<div style="padding-left:5px; background:#FFF; min-height:400px; border-top:1px solid #ccc; padding-top:5px;">
			<div class="tabs tabs1"<?php echo !isset($_GET['cid']) ? ' style="display:block"' : ' style="display:none"';?>>
				<div class="subcate">
					<div class="subcate">
					<?php if(!empty($rt['blist']))foreach($rt['blist'] as $rows){
						if(!empty($rows['brand_logo'])){
					?>
						<a href="<?php echo ADMIN_URL.'catalog.php?bid='.$rows['brand_id'];?>"><p style="padding:10px; padding-bottom:5px"><img src="<?php echo SITE_URL.$rows['brand_logo'];?>"  style="max-width:100%;"/></p><p style="padding-bottom:15px; height:24px; line-height:24px; overflow:hidden"><?php echo $rows['brand_name'];?></p></a>
					<?php }else{?>
					<a href="<?php echo ADMIN_URL.'catalog.php?bid='.$rows['brand_id'];?>"><p style="height:24px; line-height:24px; overflow:hidden; margin:0px 2px 10px 2px; border-bottom:1px solid #ededed;  background:#FFF"><?php echo $rows['brand_name'];?></p></a>
					<?php	} 
						} //end foreach
					?>
						<div class="clear"></div>
					</div>
				</div>
			</div>
		<?php $k=1;if(!empty($rt['menu']))foreach($rt['menu'] as $row){?>
			<div class="tabs tabs<?php echo ++$k;?>"<?php echo isset($_GET['cid'])&&$cid==$row['id'] ? ' style="display:block"' : ' style="display:none"';?>>
				<?php if(!empty($row['icon'])){?><p><a href="<?php echo $row['url'];?>"><img src="<?php echo SITE_URL.$row['icon'];?>"  style="max-width:100%;"/></a></p><?php } ?>
				<?php if(!empty($row['cat_id'])){?>
				<div class="subcate">
				<?php if(!empty($subcate))foreach($subcate as $rows){
					if(!empty($rows['icon'])){
				?>
					<a href="<?php echo $rows['url'];?>"><p style="padding:10px; padding-bottom:5px"><img src="<?php echo SITE_URL.$rows['icon'];?>"  style="max-width:100%;"/></p><p style="padding-bottom:15px; height:24px; line-height:24px; overflow:hidden"><?php echo $rows['name'];?></p></a>
				<?php }else{?>
				<a href="<?php echo $rows['url'];?>"><p style="height:24px; line-height:24px; overflow:hidden; margin:0px 2px 10px 2px; border-bottom:1px solid #ededed;  background:#FFF"><?php echo $rows['name'];?></p></a>
				<?php	} 
					} //end foreach
				?>
					<div class="clear"></div>
				</div>
				<?php } ?>
			</div>
		<?php } ?>			
		</div>
	</div>
	<div class="clear"></div>
</div>
<script type="text/javascript">
/*$('.catesleft2 li').click(function(){
	$(this).parent().find('a').removeClass('ac');
	$(this).find('a').addClass('ac');
	$('.tabs').hide();
	art = $(this).attr('id');
	$('.'+art).show();
	$('body,html').animate({scrollTop:0},500);
});*/
</script>
<?php  $this->element('24/footer',array('lang'=>$lang)); ?>
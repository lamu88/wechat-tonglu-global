<br />
<!--QUYU-->
<div id="opquyu">
	
</div>
<div id="opquyubox">
	<div style="line-height:26px;">
	<?php if(!empty($lang['menu']))foreach($lang['menu'] as $row){?>
		<h2><a href="<?php echo ADMIN_URL.'catalog.php?cid='.$row['id'];?>"><?php echo $row['name'];?></a></h2>
		<?php if(!empty($row['cat_id'])){?>
		<div>
			<?php foreach($row['cat_id'] as $rows){?>
			<a href="<?php echo ADMIN_URL.'catalog.php?cid='.$rows['id'];?>"><?php echo $rows['name'];?></a>
			<?php } ?>
		</div>
	<?php } } ?>
	</div>
	<div style="height:45px;"></div>
</div>

<!--FOOTER-->
<?php if(!strpos($_SERVER['PHP_SELF'],'user.php') && !strpos($_SERVER['PHP_SELF'],'daili.php')){?>
<?php } if(!empty($lang['copyright'])){?>
<div style="text-align:center; padding-top:10px;">
	<?php echo $lang['copyright'];?>
	<script><?php echo $lang['tongjicode'];?></script>
</div>
<?php } ?>

<?php
$nums = 0;
$thiscart = $this->Session->read('cart');
if(!empty($thiscart))foreach($thiscart as $row){
	$nums +=$row['number'];
}
?>

<div id="collectBox"></div>
 
<link rel="stylesheet" type="text/css" href="<?php echo ADMIN_URL;?>tpl/24/css/font-awesome.css">
<link rel="stylesheet" type="text/css" href="<?php echo ADMIN_URL;?>tpl/24/css/PreFoot.css">
 
<div class="fixed bottom">
<dl class="sub-nav nav-b5">
      <dd class="active">
        <div class="nav-b5-relative"><a href="<?php echo ADMIN_URL;?>"><i class="icon-nav-bag"></i>商城首页</a></div>
    </dd>
    <dd>
        <div class="nav-b5-relative"><a href="<?php echo ADMIN_URL.'catalog.php' ?>"><i class="icon-nav-store"></i>全部产品</a></div>
    </dd>
    <dd>
        <div class="nav-b5-relative"><a href="<?php echo ADMIN_URL.'mycart.php';?>"><i class="icon-nav-cart"></i>购物车</a></div>
    </dd>
    <dd>
        <div class="nav-b5-relative"><a href="<?php echo ADMIN_URL.'user.php';?>"><i class="icon-nav-heart"></i>会员中心</a></div>
    </dd>
</dl>
</div>
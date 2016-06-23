<link rel="stylesheet" href="<?php echo ADMIN_URL;?>tpl/24/font-awesome/css/font-awesome.min.css">
 
<i class=""></i>
<div id="home">
	<div id="header">
		<div class="logo" style="height:28px; padding-top:10px;  "><span onclick=" history.go(-1);" class="fa fa-arrow-left" style=" float:left; width:auto; font-weight:normal; padding:0px; margin-left:10%; ">&nbsp;</span></div>
		<div class="shoptitle"><span><?php echo NAVNAME;?></span></div>
		<div class="logoright">
			<div style="background-image:none; float:right;">
			<a href="javascript:;" class="fa fa-list-ul" style="background-image:none; color:#fff; font-size:25px; float:right; width:auto; margin-right:10%;" onclick="ajax_show_menu()"></a>
			<div class="showmenu">
					<p><a href="<?php echo ADMIN_URL;?>">返回首页</a></p>
					<p><a href="<?php echo ADMIN_URL.'user.php';?>">会员中心</a></p>
					<p><a href="<?php echo ADMIN_URL.'user.php?act=orderlist';?>">我的订单</a></p>
					<p><a href="<?php echo ADMIN_URL.'mycart.php';?>">购&nbsp;物&nbsp;车</a></p>
					<p style="border:none"><a href="<?php echo Import::basic()->thisurl();;?>">刷新页面</a></p>
			</div>
			</div>
		</div>
	</div>
</div>	
<script type="text/javascript">
function ajax_show_menu(){
	$(".showmenu").toggle();
}
</script>

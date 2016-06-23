<!--QUYU-->
<div id="opquyu">
	
</div>
<div id="opquyubox">
	<p><img src="<?php echo $this->img('homeMenuTop.png');?>" width="100%" /></p>
	<div style="line-height:26px;">
		<h2 style="border-bottom:1px solid #ededed;"><a href="<?php echo ADMIN_URL.'exchange.php';?>">积分兑换</a></h2>
	<?php if(!empty($lang['menu']))foreach($lang['menu'] as $row){?>
		<h2 style="border-bottom:1px solid #ededed;"><a href="<?php echo ADMIN_URL.'catalog.php?cid='.$row['id'];?>"><?php echo $row['name'];?></a></h2>
		<?php if(!empty($row['cat_id'])){?>
		<div style=" line-height:14px;">
			<?php foreach($row['cat_id'] as $rows){?>
			<a href="<?php echo ADMIN_URL.'catalog.php?cid='.$rows['id'];?>"><?php echo $rows['name'];?></a><a href="<?php echo ADMIN_URL.'catalog.php?cid='.$rows['id'];?>"><?php echo $rows['name'];?></a><a href="<?php echo ADMIN_URL.'catalog.php?cid='.$rows['id'];?>"><?php echo $rows['name'];?></a>
			<?php } ?>
		</div>
	<?php } } ?>
	</div>
	<div style=" height:45px;"></div>
</div>

<!--FOOTER-->
<div style="height:40px; clear:both">&nbsp;</div>
<div id="footer" >
		<ul>
			<li class="homes"><a class="abc" href="<?php echo ADMIN_URL;?>">
			<table border="0" cellpadding="0" cellspacing="0" width="100%" style="line-height:38px; padding:0px; margin:0px;">
				<tr>
					<td valign="middle" align="right"><span></span></td>
					<td valign="middle" align="left">首页</td>
				</tr>
			</table>
			</a>
			</li>
			
			<li class="daohang"><a class="abc" href="javascript:;" onclick="ajaxopquyu()">
			<table border="0" cellpadding="0" cellspacing="0" width="100%" style="line-height:34px">
				<tr>
					<td valign="middle" align="right"><span></span></td>
					<td valign="middle" align="left">导航</td>
				</tr>
			</table>
			</a>
			</li>
			<?php
			$uid = $this->Session->read('User.uid');
			?>
			<li class="reglog" style="width:28%"><a class="abc" href="<?php if(empty($uid)){ echo ADMIN_URL.'user.php?act=login'; }else{ echo ADMIN_URL.'user.php'; }?>">
			<table border="0" cellpadding="0" cellspacing="0" width="100%" style="line-height:34px">
				<tr>
					<td valign="middle" align="right"><span></span></td>
					<td valign="middle" align="left">会员</td>
				</tr>
			</table>
			</a>
			</li>
			<li class="lianxi"><a class="abc" href="<?php echo ADMIN_URL;?>mycart.php">
			<table border="0" cellpadding="0" cellspacing="0" width="100%" style="line-height:34px">
				<tr>
					<td valign="middle" align="right"><span></span></td>
					<td valign="middle" align="left">购物车</td>
				</tr>
			</table>
			</a>
			</li>
		</ul>
</div>
<!--FOOTER-->
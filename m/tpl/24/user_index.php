<body>
<?php $ad = $this->action('banner','banner','会员中心',1);?>
	<div id="meCenter">
		<dl>
			<dt><img src="<?php echo !empty($rt['userinfo']['headimgurl']) ? $rt['userinfo']['headimgurl'] : (!empty($rt['userinfo']['avatar']) ? SITE_URL.$rt['userinfo']['avatar'] : $this->img('noavatar_big.jpg'));?>"></dt>
			<dd>
				<p>用户编号：<?php echo $rt['userinfo']['user_id'];?></p>
				<p>微信昵称：<?php echo empty($rt['userinfo']['nickname']) ? '未知' : $rt['userinfo']['nickname'];?></p>
				<p>关注时间：<?php if(empty($rt['userinfo']['subscribe_time'])){ ?>
			<?php echo date('Y-m-d',$rt['userinfo']['reg_time']);?>
			<?php 
			}else{
			?>
			<?php echo date('Y-m-d',$rt['userinfo']['subscribe_time']);?>
			<?php
			}
			?></p>
				<p>会员等级：<?php echo $rt['userinfo']['level_name'];?></p>
				<p>总收入：<b><?php echo empty($rt['userinfo']['zmoney']) ? '0' : $rt['userinfo']['zmoney'];?></b> 元</p>
			</dd>
		</dl>
	</div>
	<div class="methis superior">
		<?php
			if(!empty($rt['tjren']))	$superior = "<a href='/m/daili.php?act=liuyan&t_uid=".$rt['tjrenid']."'>".$rt['tjren']."</a>";
		?>
		上级：【<?php echo empty($rt['tjren']) ? '官网':$superior;?>】
	</div><br />
	<!-- 这里是会员横向滚动 -->
	<div class="methis notice">
		<marquee scrollamount="4" direction="left"><?php echo $lang['site_notice'];?></marquee>
	</div>
	
	
	<a href="<?php echo ADMIN_URL;?>user.php?act=myinfos_u" class="mebar">
		<div class="methis meone">
			<div class="icon info"></div>
			<div class="metext">个人资料</div>
			<div class="jt"></div>
		</div>
	</a>
	
	<div class="methis meone">
		<div class="icon fendian"></div>
		<div class="metext">我的分店</div>
	</div>
	<div class="median">
		<ul>
			<a href="<?php echo ADMIN_URL.'daili.php?act=myuser&t=1';?>">
			<li>
				<p><?php echo $rt['zcount1'];?>人</p>
				<p class="p2">一级分店</p>
			</li>
			</a>
			<a href="<?php echo ADMIN_URL.'daili.php?act=myuser&t=2';?>">
			<li>
				<p><?php echo $rt['zcount2'];?>人</p>
				<p class="p2">二级分店</p>
			</li>
			</a>
			<a href="<?php echo ADMIN_URL.'daili.php?act=myuser&t=3';?>">
			<li>
				<p><?php echo $rt['zcount3'];?>人</p>
				<p class="p2">三级分店</p>
			</li>
			</a>
		</ul>
	</div>
	
	<div class="methis meone">
		<div class="icon caifu"></div>
		<div class="metext">我的财富(点击红包提现)</div>
	</div>
	<div class="mecaifu">
		<ul>
			<a href="/m/hongbao.php?act=yueti_fenxiao" onclick="confirm('确认余额提现吗？大于200元时请点击多次提现。')">
			<li style="width:50%">
				<p><span><?php echo empty($rt['userinfo']['fxmoney']) ? '0' : $rt['userinfo']['fxmoney'];?></span></p>
				<p class="p2">分销红包</p>
			</li>
			</a>
			<li style="width:50%">
				<p><?php echo empty($rt['userinfo']['money_ucount']) ? '0' : $rt['userinfo']['money_ucount'];?></p>
				<p class="p2">累计分销</p>
			</li>
		</ul>
	</div>
	<div class="mecaifu">
		<ul>
			<a href="/m/hongbao.php?act=yueti" onclick="confirm('确认余额提现吗？大于200元时请点击多次提现。')">
			<li>
				<p><span><?php echo empty($rt['userinfo']['mymoney']) ? '0' : $rt['userinfo']['mymoney'];?></span></p>
				<p class="p2">商城红包</p>
			</li>
			</a>
			<a href="/m/hongbao.php?act=yueti_fenhong" onclick="confirm('确认提现吗？大于200元时请点击多次提现。')">
			<li>
				<p><span><?php echo empty($rt['userinfo']['qqmoney']) ? '0' : $rt['userinfo']['qqmoney'];?></span></p>
				<p class="p2">分红红包</p>
			</li>
			</a>
			<li>
				<p><?php echo empty($rt['userinfo']['money_ucount']) ? '0' : $rt['userinfo']['money_ucount'];?></p>
				<p class="p2">累计佣金</p>
			</li>
			<li>
				<p><?php echo empty($rt['userinfo']['qqmoney_ucount']) ? '0' : $rt['userinfo']['qqmoney_ucount'];?></p>
				<p class="p2">累计分红</p>
			</li>
		</ul>
	</div>

	<a href="<?php echo ADMIN_URL.'user.php?act=orderlist';?>" class="mebar">
		<div class="methis meone">
			<div class="icon order"></div>
			<div class="metext">我的订单</div>
			<div class="jt"></div>
		</div>
	</a>
	<br />
	<a href="tel:<?php echo $custome_phone ?>" class="mebar">
		<div class="methis metwo">
			<div class="icon phone"></div>
			<div class="metext">客服电话：<span><?php echo $custome_phone ?></span></div>
		</div>
	</a>
	
	<Br /><Br /><Br />
	<?php $this->element('24/footer',array('lang'=>$lang)); ?>
</body>
</html>
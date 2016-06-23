<?php if(!empty($ulist))foreach($ulist as $k=>$row){?>
	<li style="padding:5px; border-bottom:1px solid #d8d8d8; position:relative">

		<div style="position:relative; width:20%;float:left;"><img src="<?php echo !empty($row['headimgurl']) ? $row['headimgurl'] : ADMIN_URL.'images/noavatar_big.jpg';?>" width="100%" style="margin-right:5px; padding:1px; border:1px solid #fafafa" />
		<?php if($row['is_subscribe']=='1'){?><img src="<?php echo ADMIN_URL.'images/dui2.png';?>" style="position:absolute; bottom:5px; right:-2px; z-index:7" /><?php } ?>
		</div>
		<div style="float:right; width:78%;">
		<p style="line-height:23px"><?php echo $row['nickname'];?></p>
		<p style="line-height:23px">
			<?php
				$sql = "select `mobile` from `{$this->App->prefix()}goods_order_info` where user_id=".$row['user_id'];
				$r = $this->App->findvar($sql);
				if($r) echo "电话：<a href='tel:$r'>$r</a>";
			?></p>
		<p style="line-height:23px">
			<?php
				switch($row['user_rank']){
					case 1:
						echo "普通会员";
						break;
					case 12:
						echo "新近";
						break;
					case 11:
						echo "高手";
						break;
					case 10:
						echo "明者";
						break;
					case 9:
						echo "智士";
						break;
					case 8:
						echo "元老";
						break;
				}
			?>
		</p>
		<p style="line-height:23px"><?php echo $row['subscribe_time']>0 ? date('Y-m-d H:i:s',$row['subscribe_time']) : date('Y-m-d H:i:s',$row['reg_time']);?></p>
		<p style="line-height:40px"><span style="color:#F00">
			<?php
				if($row['user_rank']>1){
					echo '已购买';
				}else{
					echo "<font color='#4BB349'>未购买</font>";
				}
			?></span><span style="background:#DB383E; margin-left:15px;padding:8px"><a href="<?php echo ADMIN_URL.'daili.php?act=liuyan&t_uid='.$row['user_id'];?>" style="color:#FFF; ">&nbsp;&nbsp;留&nbsp;言&nbsp;&nbsp;</a></span>
		</p>
			
			</div>
		<div class="clear"></div>

		<span style="border-radius:50%; padding:3px; float:right; display:block;background:#B70000; text-align:center; font-size:12px; font-weight:bold; color:#FFF; cursor:pointer; position:absolute;right:10px; top:17px; z-index:99" id="62"><i style="font-style:normal"><?php echo $pagec+(++$k);?></i></span>
	</li>
<?php } ?>
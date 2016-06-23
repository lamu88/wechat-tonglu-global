<?php if(!empty($ulist))foreach($ulist as $k=>$row){?>
	<li style="padding:5px; border-bottom:1px solid #d8d8d8; position:relative">
		<a href="javascript:void(0)" style="display:block">
		<div style="position:relative; width:20%;float:left;"><img src="<?php echo !empty($row['headimgurl']) ? $row['headimgurl'] : ADMIN_URL.'images/noavatar_big.jpg';?>" width="100%" style="margin-right:5px; padding:1px; border:1px solid #fafafa" />
		<?php if($row['is_subscribe']=='1'){?><img src="<?php echo ADMIN_URL.'images/dui2.png';?>" style="position:absolute; bottom:5px; right:-2px; z-index:7" /><?php } ?>
		</div>
		<div style="float:right; width:78%;">
		<p style="line-height:23px"><?php echo $row['nickname'];?>&nbsp;&nbsp;<?php echo !empty($row['subscribe_time']) ? date('Y-m-d H:i:s',$row['subscribe_time']) : date('Y-m-d H:i:s',$row['reg_time']);?></p>
		<p style="line-height:23px">积分&nbsp;<font color="#FF0000"><?php echo $row['points_ucount'];?></font>&nbsp;|&nbsp;邀请&nbsp;<font color="#FF0000"><?php echo $row['share_ucount'];?></font></p>
		</div>
		<div class="clear"></div>
		</a>
		<span style="border-radius:50%; padding:3px; float:right; display:block;background:#B70000; text-align:center; font-size:12px; font-weight:bold; color:#FFF; cursor:pointer; position:absolute;right:10px; top:17px; z-index:99" id="62"><i style="font-style:normal"><?php echo $pagec+(++$k);?></i></span>
	</li>
<?php } ?>
<?php if(!empty($rt['messagelist'])){?>
<div class="ajxx_feedbook">
	<?php foreach($rt['messagelist'] as $row){?> 
		<div class="items">
			<p class="p1">
			<b style="float:left"><?php echo $row['nickname'];?></b>
			<u><?php echo !empty($row['addtime']) ? date('Y-m-d H:i:s',$row['addtime']):"";?></u>
			</p>
			<p class="p2"><?php echo $row['content'];?></p>
		</div>
	<?php } ?>
</div>
 <div  class="num_list"  style="text-align:right;">
<?php echo $rt['messagepage']['first'].'&nbsp;'.$rt['messagepage']['prev'].'&nbsp;'.(!empty($rt['messagepage']['list'])?implode('&nbsp;',$rt['messagepage']['list']).'&nbsp;':"").$rt['messagepage']['next'].'&nbsp;'.$rt['messagepage']['last'];?>
 </div>
<?php }else{ ?>
<p style="padding:20px; font-size:16px; text-align:center">暂无评论</p>
<?php } ?>
<?php if(!empty($rt['commentlist'])){?>
<div class="clear"></div>
<table width="100%" cellpadding="3" cellspacing="5" border="0">
<?php foreach($rt['commentlist'] as $row){?>
	<tr>
		<td align="left">
		<div style="border-bottom:1px solid #ededed; padding-bottom:3px; line-height:22px; margin-bottom:5px;">
			<div style="width:70%; float:left; color:#999999">
			<?php echo $row['content'];?>
			<br/>
			<?php echo date('Y-m-d',$row['add_time']);?>
			</div>
			<div style="width:28%; float:right">
			<?php echo $row['nickname'];?>
			<br/>
			<img src="<?php echo ADMIN_URL.'images/xing'.$row['comment_rank'].'.png';?>" />
			</div>
			<div class="clear"></div>
			<?php if(!empty($row['pics'])){
			$pics = explode('|',$row['pics']);
			foreach($pics as $imgs){
				?>
				<a href="<?php echo SITE_URL.$imgs;?>"><img src="<?php echo SITE_URL.$imgs;?>" height="40" style=" padding:1px; border:1px solid #ededed; cursor:pointer; float:left" /></a>
				<?php
			}
			}
			?>
			<div class="clear"></div>
		</div>
		</td>
	</tr>
<?php } ?>
</table>
<?php if(!empty($rt['commentpage'])){?>
<div  class="pages">
<?php echo $rt['commentpage']['first'].'&nbsp;'.$rt['commentpage']['prev'].'&nbsp;'.(!empty($rt['commentpage1']['list'])?implode('&nbsp;',$rt['commentpage']['list']).'&nbsp;':"").$rt['commentpage']['next'].'&nbsp;'.$rt['commentpage']['last'];?>
 </div>
<?php }}else{ ?>
暂无评论
<?php } ?>
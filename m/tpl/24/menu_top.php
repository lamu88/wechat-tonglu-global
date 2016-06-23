<?php
$rt = $this->action('page','get_site_nav','top');
if(!empty($rt)){
?>
<style type="text/css">
<?php foreach($rt as $k=>$row){ if(empty($row['img'])) continue; ?>
.menunav a:nth-child(<?php echo ++$k;?>) i{background:url(<?php echo SITE_URL.$row['img'];?>) no-repeat center;background-size:auto 26px;}
<?php } ?>
</style>
<?php } ?>
<div class="logoqu" style="border:none">
	 <?php if(!empty($lang['site_logo'])){?>
		<img src="<?php echo  SITE_URL.$lang['site_logo'];?>" class="logos" style=" max-width:20%; left:5px; bottom:5px;border-radius:5px"/>
	<?php } ?>
	<div class="menunav">
	<?php if(!empty($rt))foreach($rt as $row){?>
	<a href="<?php echo $row['url'];?>"><i></i><?php echo $row['name'];?></a>
	<?php } ?>
	</div>
</div>
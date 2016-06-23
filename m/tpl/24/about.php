
<?php $this->element('24/top',array('lang'=>$lang)); ?>
<style type="text/css">
#main div img{ max-width:100%;}
</style>
<div id="main" style="padding:10px; padding-top:0px; min-height:300px">
	<p style="height:28px; line-height:28px;"><font color="#888"><?php echo date('Y-m-d',$rt['addtime']);?></font>&nbsp;&nbsp;&nbsp;<font color="#00761d"><?php echo $lang['site_name'];?></font></p>
	<div>
	<?php echo $rt['content'];?>
	</div>
</div>

<?php $this->element('24/footer',array('lang'=>$lang)); ?>

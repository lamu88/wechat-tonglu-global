<?php 
	if(isset($pagelink) && !empty($pagelink)){
?>
<p style="text-align:left; padding-left:8px">
	<?php echo $pagelink['showmes'];?>&nbsp;
	<?php echo $pagelink['first'];?>
	<?php echo $pagelink['previ'];?>
	<?php 
	if(!empty($pagelink['list'])){
	$t = 'class="thispage"';
	$page = isset($_GET['page'])&&!empty($_GET['page']) ? $_GET['page'] : 1;
	foreach($pagelink['list'] as $kk=>$var){
		echo '<a href="'.$var.'" '.($page==$kk ? $t : "").'>'.$kk.'</a>&nbsp;';
	}
	}
	?>
	<?php echo $pagelink['next'];?>
	<?php echo $pagelink['Last'];?>
</p>
<?php } ?>
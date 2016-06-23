
<?php $this->element('24/top',array('lang'=>$lang)); ?>
<style type="text/css">
table td:hover{ background:#ededed;}
</style>
<div id="main" style="padding:5px; min-height:300px">
<p class="radiustibox"><span class="radiusti">我的积分：<font color="#FF0000"><?php echo empty($rt['zpoints']) ? 0 : $rt['zpoints'];?></font></span></p>
<table  width="100%" border="0" cellpadding="0" cellspacing="0">
<?php if(!empty($rt['lists']))foreach($rt['lists'] as $k=>$row){
?>
<tr>
	<td align="left">
		<div style="padding:0px 0px 5px 20px; background:url(<?php echo $this->img('404-2.png');?>) 5px 12px no-repeat">
		<?php echo !empty($row['time']) ? date('Y-m-d H:i:s',$row['time']) : '无知';?>&nbsp;
		&nbsp;<?php if($row['accid']==$uid){ echo '<font color="#3333FF">收入:'.abs($row['points']).'</font>'; }else{ echo '<font color="#fe0000">支出:'.abs($row['points']).'</font>'; }?><br />
		<?php
		if($row['type']=='moneytopoint')
			echo '佣金转积分';
		elseif($row['type']=='pointtogouwubi')
			echo '积分转购物币';
		?>
		接收人ID：<?php echo $row['accid']; ?><br /><br /> 
		</div>
		</td>
</tr>
<?php } ?>
<tr>
<td style="text-align:left;" class="pagesmoney">
<div class="clear10"></div>
<style>
.pagesmoney a{ display:block; line-height:20px;margin-right:5px; color:#222; background-color:#ededed; border-bottom:2px solid #ccc; border-right:2px solid #ccc; text-decoration:none; float:left; padding-left:5px; padding-right:5px; text-align:center}
.pagesmoney a:hover{ text-decoration:underline}
</style>
<?php
if(!empty($rt['pages'])){
echo $rt['pages']['previ'];
echo $rt['pages']['next'];
}
?>
</td>
</tr>
</table>

</div>
<script type="text/javascript">
function delmycoll(ids,obj){
	thisobj = $(obj).parent().parent();
	if(confirm("确定删除吗？")){
		createwindow();
		$.post(SITE_URL+'user.php',{action:'delmycoll',ids:ids},function(data){
			removewindow();
			if(data == ""){
				thisobj.hide(300);
			}else{
				alert(data);	
			}
		});
	}else{
		return false;	
	}
}
</script>
<?php $this->element('24/footer',array('lang'=>$lang)); ?>

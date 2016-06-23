
<?php $this->element('24/top',array('lang'=>$lang)); ?>
<table  width="100%" border="0" cellpadding="0" cellspacing="0" style="height:50px; border:1px solid #d8d8d8; background:#F1F1F1">
	<tr>
		<td align="center" width="50%">
		<p style="font-size:14px; padding-top:16px"><a href="<?php echo ADMIN_URL.'daili.php?act=postmoney';?>" style="display:block">申请提款</a></p>
		</td>
		<td align="center" style="border-left:1px solid #d8d8d8">
		<p style="font-size:14px; padding-top:2px"><a href="<?php echo ADMIN_URL.'daili.php?act=postmoneydata';?>" style="display:block">提款记录</a></p>
		</td>
	</tr>
</table>
<style type="text/css">
#main table td{ background:#fff}
#main table td:hover{ background:#ededed;}
.radiustibox{ margin-left:5px;}
</style>
<div id="main" style="min-height:300px; background:#FFF">
<div class="clear10"></div>
<p class="radiustibox"><span class="radiusti">我的余额：<font color="#FF0000">￥<?php echo empty($rt['mymoney']) ? '0.00' : $rt['mymoney'];?></font></span></p>
<table  width="100%" border="0" cellpadding="0" cellspacing="0">
<?php if(!empty($rt['lists']))foreach($rt['lists'] as $k=>$row){
?>
<tr>
	<td align="left" style="border-bottom:1px solid #f0f0f0;">
		<div style="padding:8px; position:relative">
		<?php echo !empty($row['time']) ? date('Y-m-d H:i:s',$row['time']) : '无知';?>
		&nbsp;<?php if($row['money']>0){ echo '<span style="position:absolute; right:10px; top:17px;font-size:16px;font-weight:bold"><font color="#3333FF">+￥'.$row['money'].'</font></span>'; }else{ echo '<span style="position:absolute; right:10px; top:17px;font-size:16px;font-weight:bold"><font color="#fe0000">-￥'.abs($row['money']).'</font></span>'; }?>
		<p style="line-height:22px; position:relative;"><?php echo $row['changedesc'];echo !empty($row['nickname']) ? '&nbsp;&nbsp;用户:'.$row['nickname'] : '';?><!--<a href="<?php echo ADMIN_URL.'daili.php?act=monrydeial&id='.$row['cid'];?>" onclick="return confirm('确定删除吗');" style="position:absolute; right:5px; bottom:7px; z-index:99"><img src="<?php echo $this->img('delete.png');?>" align="middle" style="cursor:pointer; height:20px" /></a>--></p>
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
echo $rt['pages']['previ'];?>
<?php echo $rt['pages']['next'];
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
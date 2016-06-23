
<?php $this->element('24/top',array('lang'=>$lang)); ?>
<style type="text/css">
body{ background:#FFF !important;}
#main table td{ background:#fff}
#main table td:hover{ background:#ededed;}
.radiustibox{ margin-left:5px;}
</style>
<div id="main" style="min-height:300px; background:#FFF">
<div class="clear10"></div>
<p class="radiustibox"><span class="radiusti">佣金：<font color="#FF0000">￥<?php echo empty($rt['zmoney']) ? '0.00' : $rt['zmoney'];?></font></span></p>
<table  width="100%" border="0" cellpadding="0" cellspacing="0">
<?php if(!empty($rt['lists']))foreach($rt['lists'] as $k=>$row){
?>
<tr>
	<td align="left" style="border-bottom:1px solid #d5d5d5">
		<div style="padding:8px">
		<?php echo !empty($row['time']) ? date('Y-m-d H:i:s',$row['time']) : '无知';?>
		&nbsp;<?php if($row['money']>0){ echo '<font color="#3333FF">收入:'.$row['money'].'</font>'; }else{ echo '<font color="#fe0000">支出:'.abs($row['money']).'</font>'; }?>
		<p style="line-height:22px; position:relative;"><?php echo $row['changedesc'];echo !empty($row['nickname']) ? '&nbsp;&nbsp;用户:'.$row['nickname'] : '';?></p>
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
<?php $this->element('24/footer',array('lang'=>$lang)); ?>
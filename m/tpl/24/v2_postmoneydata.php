
<?php $this->element('24/top',array('lang'=>$lang)); ?>

<style type="text/css">
body{ background:#FFF !important;}
table td:hover{ background:#ededed;}
</style>
<div id="main" style=" min-height:300px">
<table  width="100%" border="0" cellpadding="0" cellspacing="0">
<?php if(!empty($rt))foreach($rt as $k=>$row){
?>
<tr>
	<td align="left">
		<div style="padding:10px;border-bottom:1px solid #d5d5d5"> 
		申请时间:<?php echo !empty($row['addtime']) ? date('Y-m-d H:i:s',$row['addtime']) : '无知';?>
		<p style="line-height:26px; height:26px; position:relative;"><span style="float:left">金额:<font color="#FF0000">￥<?php echo $row['money'];?></font></span><span style="float:right">状态:<font color="#FF0000"><?php echo $row['state']=='0' ? '审核中' : '已结算';?></font></span></p>
		<p style="line-height:26px;">姓名:<?php echo $row['uname'];?>&nbsp;&nbsp;&nbsp;&nbsp;手机:<?php echo $row['mobile'];?></p>
		<p style="line-height:26px;"><?php echo $row['bankname'].$row['banksn'];?></p>
		</div>
	</td>
</tr>
<?php }else{ ?>
<tr>
	<td style="text-align:center">
		<div style=" font-size:18px;padding:15%">
		暂无提款记录
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
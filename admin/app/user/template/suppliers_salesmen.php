<div class="contentbox">
     <table cellspacing="2" cellpadding="5" width="100%">
	 <tr>
		<th colspan="5" align="left">工厂业务员</th>
	</tr>
    <tr>
	   <th>会员名称</th>
	   <th>申请品牌</th>
	   <th>最后申请</th>
	   <th>审核</th>
	   <td>详细</td>
	</tr>
	<?php 
	if(!empty($rt)){ 
	foreach($rt as $row){
	?>
	<tr>
	<td><?php echo $row[0]['user_name'];?></td>
	<td>
	<?php foreach($row as $rows){?>
	<label><input type="checkbox" name="checkbox" value="<?php echo $rows['brand_id'];?>"<?php if($rows['is_check']=='1'){echo ' checked="checked"';}?> onclick="check_salesmen_brand(this.checked,'<?php echo $rows['uid'];?>','<?php echo $rows['brand_id'];?>','user_salesmen')"/><?php echo $rows['brand_name'];?></label>
	<?php } ?>	</td>
	<td><?php echo date('Y-m-d H:i:s',$row[0]['addtime']);?></td>
	<td><img src="<?php echo $this->img($row[0]['is_salesmen']==1 ? 'yes.gif' : 'no.gif');?>" alt="<?php echo $row[0]['is_salesmen']==1 ? '0' : '1';?>" onclick="check_salesmen_user(this)" id="<?php echo $row[0]['uid'];?>"/></td>
	<td><a href="<?php echo ADMIN_URL;?>user.php?type=salesmen_manage&id=<?php echo $row[0]['uid'];?>"><img src="<?php echo $this->img('icon_edit.gif');?>" title="编辑"/></a></td>
	</tr>
	<?php
	 }
	 }
	  ?>
	 </table>
</div>
 <?php  $thisurl = ADMIN_URL.'user.php'; ?>
<script language="javascript" type="text/javascript">
function check_salesmen_brand(tt,uid,bid,type){
	if(tt==true){ val = '1'; }else{ val = '0'; }
	$.post('<?php echo $thisurl;?>',{action:'check_salesmen_brand',val:val,bid:bid,uid:uid,type:type},function(data){

	});
}

function check_salesmen_user(obj){
	val = $(obj).attr('alt');
	bid = 0;
	uid = $(obj).attr('id');
	
	$.post('<?php echo $thisurl;?>',{action:'check_salesmen_brand',val:val,bid:bid,uid:uid,type:'user'},function(data){
			if(val=='1'){
				v = 0;
				src = '<?php echo $this->img('yes.gif');?>';
			}else{
				v = 1;
				src = '<?php echo $this->img('no.gif');?>';
			} //alert(data);
			$(obj).attr('src',src);
			$(obj).attr('alt',v);
	});
}
</script>
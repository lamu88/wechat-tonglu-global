<div class="contentbox">
     <table cellspacing="2" cellpadding="5" width="100%">
	 <tr>
		<th colspan="2" align="left"><?php echo $type=='edit' ? '修改' : '添加';?>产品</th>
	</tr>
	<tr>
	   <td class="label" width="25%">查找商品</td>
	   <td><input type="hidden" name="goods_id" value="<?php echo isset($rt['goods_id']) ? $rt['goods_id'] : '0';?>" />
		 <img src="<?php echo $this->img('icon_search.gif');?>" alt="SEARCH" width="26" border="0" height="22" align="absmiddle">
		 <a href="javascript:;" style="padding:5px; border-bottom:1px solid #ccc;border-right:1px solid #ccc; background:#ededed; color:#FF0000" onclick="return open_select_goods()">选择商品</a>
		 <span class="return_img">
		 <?php
		 $img = isset($rt['img'])&&!empty($rt['img']) ? SITE_URL.$rt['img'] : (isset($rt['goods_thumb'] ) ? SITE_URL.$rt['goods_thumb'] : '');
		 if(!empty($img)){
		 echo '<img src="'.$img.'" style="width:90px;padding:1px;border:1px solid #ccc" alt="" />';
		 }
		 ?>
		 </span>		 
		 </td>
	</tr>

	<tr>
	   <td class="label">查找店家</td>
	   <td><input type="hidden" name="user_id" value="<?php echo isset($rt['uid']) ? $rt['uid'] : '0';?>" />
		 <img src="<?php echo $this->img('icon_search.gif');?>" alt="SEARCH" width="26" border="0" height="22" align="absmiddle">
		 <a href="javascript:;" style="padding:5px; border-bottom:1px solid #ccc;border-right:1px solid #ccc; background:#ededed; color:#FF0000" onclick="return open_select_user()">选择用户</a>
		 <span class="return_user">
		 <?php
		 echo $rt['nickname'];
		 ?>
		 </span>		 
		 </td>
	</tr>
	 
	 <tr>
	 	<td class="label">推广二维码</td>
		<td>
		<span class="return_imggg">
		<img src="<?php echo isset($rt['url']) ? $rt['url'] : '';?>" style="width:150px;padding:1px;border:1px solid #ccc" alt="" />
		</span>
		</td>
	 </tr>
	  <tr>
		<td class="label">&nbsp;</td>
		<td>
		<input class="new_save" value="立即生成" type="Submit" style="cursor:pointer; padding:3px" onclick="return ajax_mark_erweima()" />
		</td>
	  </tr>
	 </table>
</div>

<script type="text/javascript">
function ajax_mark_erweima(){
	gid = $('input[name="goods_id"]').val();
	
	uid = $('input[name="user_id"]').val();
	
	if(gid > 0){
	}else{
		alert("请选择商品");
		return false;
	}
	if(uid > 0){
	}else{
		alert("请选择用户");
		return false;
	}
	
	createwindow();
	$.get('<?php echo ADMIN_URL.'cuxiao.php';?>',{type:'ajax_mark_erweima',gid:gid,uid:uid},function(data){
		if(data !=""){
			$('.return_imggg').html(data);
		}
		removewindow();
	});
	return true;
}

function setrun(gname,gid,img){
	$('input[name="goods_id"]').val(gid);
	str = '<img src="'+img+'" style="width:90px;padding:1px;border:1px solid #ccc" alt="'+gname+'" />';
	$('.return_img').html(str);
}

function setrunuser(gname,gid){
	$('input[name="user_id"]').val(gid);
	str = '<span>'+gname+'</span>';
	$('.return_user').html(str);
}

function open_select_goods(){
	JqueryDialog.Open('','<?php echo ADMIN_URL;?>selectgoods.php',600,350,'frame');
	return false;
}

function open_select_user(){
	JqueryDialog.Open('','<?php echo ADMIN_URL;?>selectuser.php',600,350,'frame');
	return false;
}
</script>
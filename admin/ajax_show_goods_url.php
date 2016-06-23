<?php 
	require_once('load.php');
	$gcid = $_GET['gcid'];
	$rts = $app->action('caiji','ajax_show_goods_url',$gcid);
	$pagelink = $rts['pagelink'];
	$rt = $rts['list'];
	unset($rts);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>--</title>
<script type="text/javascript" src="<?php echo SITE_URL;?>js/jquery.min.js"></script> 
</head>

<body>
	<table cellspacing="0" cellpadding="0" width="100%" style="line-height:26px; border:1px solid #ccc; font-size:12px">
	<tr>
		<th>链接</th>
		<th>是否允许采集</th>
		<th>删除</th>
	</tr>
	<?php if(!empty($rt))foreach($rt as $row){?>
	<tr>
	<td align="left" valign="top" style="padding-left:10px;">
		<a href="<?php echo $row['url'];?>" target="_blank"><?php echo $row['url'];?></a>
	</td>
	<td align="center" valign="top">
		<img src="<?php echo ADMIN_URL.'images/'.($row['active']==1 ? 'yes.gif' : 'no.gif');?>" alt="<?php echo $row['active']==1 ? '0' : '1';?>" class="activeop" id="<?php echo $row['gcuid'];?>" style="cursor:pointer"/>
	</td>
	<td align="center" valign="top">
		<label>
		<input type="checkbox" name="quanxuan" value="<?php echo $row['gcuid'];?>" />
		</label>
		<img src="<?php echo ADMIN_URL.'images/icon_drop.gif';?>" class="dels" id="<?php echo $row['gcuid'];?>" style="cursor:pointer"/>
	</td>
	</tr>
	<?php }  ?>
	<tr>
		<td colspan="3" style="padding-right:10px" align="right"><input type="checkbox" name="quanxuanall" class="quanxuanall" value="checkbox" />&nbsp;<input name="dels" id="bathdel" type="button" value=" 删除选中 " style="padding:3px; cursor:pointer" disabled="disabled"/>&nbsp;&nbsp;<input name="" type="button" value=" 清空表 " style="padding:3px; cursor:pointer"/></td>
	</tr>
	<tr>
	<td align="right" colspan="3">
	<?php 
	if(isset($pagelink) && !empty($pagelink)){
	?>
	<p style="text-align:right; padding-right:8px">
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
	</td>
	</tr>
	 </table>
<script language="javascript" type="text/javascript">
   	$('.activeop').live('click',function(){
		star = $(this).attr('alt');
		gid = $(this).attr('id');
		obj = $(this);
		$.get('<?php echo ADMIN_URL.'caiji.php';?>',{type:'ajax_goods_url_active',val:star,gcuid:gid},function(data){
			if(data == ""){
				if(star == 1){
					id = 0;
					src = '<?php echo ADMIN_URL.'images/yes.gif';?>';
				}else{
					id = 1;
					src = '<?php echo ADMIN_URL.'images/no.gif';?>';
				}
				obj.attr('src',src);
				obj.attr('alt',id);
			}else{
				alert(data);
			}
		});
	});
	
	//全选
 $('.quanxuanall').click(function (){
      if(this.checked==true){
         $("input[name='quanxuan']").each(function(){this.checked=true;});
		 document.getElementById("bathdel").disabled = false;
	  }else{
	     $("input[name='quanxuan']").each(function(){this.checked=false;});
		 document.getElementById("bathdel").disabled = true;
	  }
  });
  
   
  //是删除按钮失效或者有效
  $("input[name='quanxuan']").click(function(){ 
  		var checked = false;
  		$("input[name='quanxuan']").each(function(){
			if(this.checked == true){
				checked = true;
			}
		}); 
		document.getElementById("bathdel").disabled = !checked;
  });
  
  
   //批量删除
   $('#bathdel').click(function (){
   		if(confirm("确定删除吗？")){
			
			var arr = [];
			$('input[name="quanxuan"]:checked').each(function(){
				arr.push($(this).val());
			});
			if(arr==null || arr=="") return false;
			var str=arr.join('+');
			document.getElementById("bathdel").disabled = true;
			$.get('<?php echo ADMIN_URL.'caiji.php';?>',{type:'ajax_del_goodsurl',ids:str},function(data){
				if(data == ""){
					location.reload();
				}else{
					alert(data);
				}
			});
		}else{
			return false;
		}
   });
   
   $('.dels').click(function(){
   		ids = $(this).attr('id');
		thisobj = $(this).parent().parent();
		if(confirm("确定删除吗？")){
			$.get('<?php echo ADMIN_URL.'caiji.php';?>',{type:'ajax_del_goodsurl',ids:ids},function(data){
				if(data == ""){
					thisobj.hide(300);
				}else{
					alert(data);	
				}
			});
		}else{
			return false;	
		}
   });
</script>

</body>
</html>
<?php
$thisurl = ADMIN_URL.'user.php'; 
$ts = '&ts=1';
if(isset($_GET['ts']) && !empty($_GET['ts'])){
	$ts = '&ts='.$_GET['ts'];
}
if(isset($_GET['asc'])){
$uname = $thisurl.'?type=shoplist&desc=user_name'.$ts;
$email = $thisurl.'?type=shoplist&desc=email'.$ts;
$active = $thisurl.'?type=shoplist&desc=active'.$ts;
$dt = $thisurl.'?type=shoplist&desc=reg_time'.$ts;
$dts = $thisurl.'?type=shoplist&desc=last_login'.$ts;
$ip = $thisurl.'?type=shoplist&desc=reg_ip'.$ts;
}else{
$uname = $thisurl.'?type=shoplist&asc=user_name'.$ts;
$email = $thisurl.'?type=shoplist&asc=email'.$ts;
$active = $thisurl.'?type=shoplist&asc=active'.$ts;
$dt = $thisurl.'?type=shoplist&asc=reg_time'.$ts;
$dts = $thisurl.'?type=shoplist&asc=last_login'.$ts;
$ip = $thisurl.'?type=shoplist&asc=reg_ip'.$ts;
}
?>
<style type="text/css">
.contentbox table th{ font-size:12px; text-align:center}
.contentbox table td .abc{ padding:5px; border-bottom:3px solid #ccc; border-right:3px solid #ccc; background:#ededed; margin-right:5px; margin-left:5px;}
.contentbox table td .ac{ background:#6bb4d5; color:#FFF}
</style>
<div class="contentbox">
     <table cellspacing="2" cellpadding="5" width="100%">
	 <tr>
		<th colspan="11" align="left" style="text-align:left">店铺会员列表</th>
	</tr>
	<tr><td colspan="11" align="left">
    	<img src="<?php echo $this->img('icon_search.gif');?>" alt="SEARCH" width="26" border="0" height="22" align="absmiddle">
    	关键字 <input name="keyword" size="15" type="text" value="<?php echo isset($_GET['keyword']) ? $_GET['keyword'] : "";?>">
    	<input value=" 搜索 " class="cate_search" type="button">
		<a href="user.php?type=shoplist&ts=1" class="<?php echo $_GET['ts']=='1' ? 'ac ' : '';?>abc">已审核的店铺</a><a href="user.php?type=shoplist&ts=2" class="<?php echo $_GET['ts']=='2' ? 'ac ' : '';?>abc">申请中的店铺</a>
	</td></tr>
    <tr>
	   <th width="50"><label><input type="checkbox" class="quxuanall" value="checkbox" />编号</label></th>
	   <th>昵称</th>
	   <th>手机号</th>
	   <th>邮箱</th>
	   <th>微信号</th>
	   <th>级别</th>
	   <th width="60">是否店铺</th>
	   <th><a href="<?php echo $dt;?>">加入时间</a></th>
	   <th><a href="<?php echo $dts;?>">最后登录[地区]</a></th>
	   <th><a href="<?php echo $ip;?>">加入IP地址[地区]</a></th>
	   <th width="70">操作</th>
	</tr>
	<?php 
	if(!empty($userlist)){ 
	foreach($userlist as $row){
	?>
	<tr>
	<td><input type="checkbox" name="quanxuan" value="<?php echo $row['user_id'];?>" class="gids"/></td>
	<td><a href="javascript:;" style=" text-align:center;position:relative; display:block; background-color:#E2E8EB; border-bottom:2px solid #ccc; border-right:2px solid #ccc; padding-left:5px"><?php echo !empty($row['nickname']) ? $row['nickname'] : $row['user_name'];?></a>
	</td>
	<td><?php echo $row['mobile_phone'];?></td>
	<td><?php echo $row['email'];?></td>
	<td><?php echo $row['msn'];?></td>
	<td><?php echo $row['level_name'];?></td>
	<td><img src="<?php echo $this->img($row['isshop']==1 ? 'yes.gif' : 'no.gif');?>" alt="<?php echo $row['isshop']==1 ? '0' : '1';?>" class="activeop" lang="isshop" id="<?php echo $row['user_id'];?>"/></td>
	<td><?php echo !empty($row['reg_time']) ? date('Y-m-d H:i:s',$row['reg_time']) : '无知';?></td>
	<td><?php echo !empty($row['last_login']) ? date('Y-m-d H:i:s',$row['last_login']).'<br /><font color="#FF0000">['.Import::ip()->ipCity($row['last_ip']).']</font>' : '无知';?></td>
	<td><?php echo $row['reg_ip'];?><br /><font color="#FF0000">[<?php echo $row['reg_from'] ? $row['reg_from'] : '无知';?>]</font></td>
	<td>
	<a href="user.php?type=shopinfo&id=<?php echo $row['user_id'];?>&ts=<?php echo $_GET['ts'];?>" title="编辑"><img src="<?php echo $this->img('icon_edit.gif');?>" title="编辑"/></a>&nbsp;
	<img src="<?php echo $this->img('icon_drop.gif');?>" title="删除" alt="删除" id="<?php echo $row['user_id'];?>" class="deluserid"/>
	</td>
	</tr>
	<?php
	 } ?>
	<tr>
		 <td colspan="11"> <input type="checkbox" class="quxuanall" value="checkbox" />
			  <input type="button" name="button" value="批量删除" disabled="disabled" class="bathdel" id="bathdel"/>
		 </td>
	</tr>
		<?php } ?>
	 </table>
	 <?php $this->element('page',array('pagelink'=>$pagelink));?>
</div>
<script type="text/javascript">
function ajax_bingweixin(url){
	JqueryDialog.Open('绑定微信',url,500,200,'frame');
}
//全选
 $('.quxuanall').click(function (){
      if(this.checked==true){
         $("input[name='quanxuan']").each(function(){this.checked=true;});
		 document.getElementById("bathdel").disabled = false;
	  }else{
	     $("input[name='quanxuan']").each(function(){this.checked=false;});
		 document.getElementById("bathdel").disabled = true;
	  }
  });
  
  //是删除按钮失效或者有效
  $('.gids').click(function(){ 
  		var checked = false;
  		$("input[name='quanxuan']").each(function(){
			if(this.checked == true){
				checked = true;
			}
		}); 
		document.getElementById("bathdel").disabled = !checked;
  });
  
  //批量删除
   $('.bathdel').click(function (){
   		if(confirm("确定删除吗？")){
			createwindow();
			var arr = [];
			$('input[name="quanxuan"]:checked').each(function(){
				arr.push($(this).val());
			});
			var str=arr.join('+'); ;
			$.post('<?php echo $thisurl;?>',{action:'bathdel',ids:str},function(data){
				removewindow();
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
   
   $('.deluserid').click(function(){
   		ids = $(this).attr('id');
		thisobj = $(this).parent().parent();
		if(confirm("确定删除吗？")){
			createwindow();
			$.post('<?php echo $thisurl;?>',{action:'bathdel',ids:ids},function(data){
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
   });
   
   	$('.activeop').live('click',function(){
		star = $(this).attr('alt');
		uid = $(this).attr('id'); 
		type = $(this).attr('lang');
		obj = $(this);
		$.post('<?php echo $thisurl;?>',{action:'activeop',active:star,uid:uid,type:type},function(data){
			if(data == ""){
				if(star == 1){
					id = 0;
					src = '<?php echo $this->img('yes.gif');?>';
				}else{
					id = 1;
					src = '<?php echo $this->img('no.gif');?>';
				}
				obj.attr('src',src);
				obj.attr('alt',id);
			}else{
				alert(data);
			}
		});
	});
	
		//sous
	$('.cate_search').click(function(){
		
		keys = $('input[name="keyword"]').val();
		
		location.href='<?php echo ADMIN_URL.'user.php?type=shoplist&ts='.$_GET['ts'];?>&keyword='+keys;
	});
	
	function showsuppliersinfo(uid){
		JqueryDialog.Open('配送区域','<?php echo ADMIN_URL;?>ajax_show_suppliers_info.php?uid='+uid,750,400,'frame');
	}
</script>
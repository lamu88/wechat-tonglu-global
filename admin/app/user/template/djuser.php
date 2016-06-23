<?php
$thisurl = ADMIN_URL.'user.php'; 
if(isset($_GET['asc'])){
$uname = $thisurl.'?type=list&desc=user_name';
$email = $thisurl.'?type=list&desc=email';
$active = $thisurl.'?type=list&desc=active';
$dt = $thisurl.'?type=list&desc=reg_time';
$dts = $thisurl.'?type=list&desc=last_login';
$ip = $thisurl.'?type=list&desc=reg_ip';
}else{
$uname = $thisurl.'?type=list&asc=user_name';
$email = $thisurl.'?type=list&asc=email';
$active = $thisurl.'?type=list&asc=active';
$dt = $thisurl.'?type=list&asc=reg_time';
$dts = $thisurl.'?type=list&asc=last_login';
$ip = $thisurl.'?type=list&asc=reg_ip';
}
?>
<style type="text/css"> .contentbox table th{ font-size:12px; text-align:center}</style>
<div class="contentbox">
     <table cellspacing="2" cellpadding="5" width="100%">
	<tr>
		<th colspan="11" align="left" style="text-align:left"><span style="float:left">全部分销商会员</span><a href="<?php echo ADMIN_URL;?>user.php?type=infodaili_step1" style="float:right; padding:3px; background:#ededed; border-bottom:2px solid #ccc; border-right:2px solid #ccc; color:#00ab36">添加分销商</a></th>
	</tr>
	<tr>
		<th colspan="11" align="left" style="text-align:left">
		&nbsp;&nbsp;一级总数：<?php echo $lid['lid12'] ?>&nbsp;&nbsp;|
		&nbsp;&nbsp;二级总数：<?php echo $lid['lid11'] ?>&nbsp;&nbsp;|
		&nbsp;&nbsp;三级总数：<?php echo $lid['lid10'] ?>
		</th>
	</tr>
	<tr>
		<th colspan="10" align="left" style="text-align:left">
		&nbsp;&nbsp;全球0级：<?php echo $lid['qid0'] ?>&nbsp;&nbsp;|
		&nbsp;&nbsp;全球一级：<?php echo $lid['qid1'] ?>&nbsp;&nbsp;|
		&nbsp;&nbsp;全球二级：<?php echo $lid['qid2'] ?>&nbsp;&nbsp;|
		&nbsp;&nbsp;全球三级：<?php echo $lid['qid3'] ?>&nbsp;&nbsp;|
		&nbsp;&nbsp;全球四级：<?php echo $lid['qid4'] ?>&nbsp;&nbsp;|
		&nbsp;&nbsp;全球五级：<?php echo $lid['qid5'] ?>&nbsp;&nbsp;|
		</th>
	</tr>
	<tr>
		<td colspan="3"  style="text-align:left; color:#F00">选择显示会员等级&nbsp;&nbsp;
			<select name="level" class="level">
				<option value="12">一级</option>
				<option value="11">二级</option>
				<option value="10">三级</option>
			</select>
		</td>
		<td colspan="8" rowspan="2" align="left">
    	<img src="<?php echo $this->img('icon_search.gif');?>" alt="SEARCH" width="26" border="0" height="22" align="absmiddle">
    	关键字 <input name="keyword" size="15" type="text" value="<?php echo isset($_GET['keyword']) ? $_GET['keyword'] : "";?>">
    	<input value=" 搜索 " class="cate_search" type="button">
	</td>
	</tr>
	<tr>
		<td colspan="3"  style="text-align:left; color:#F00">选择全球分红等级&nbsp;&nbsp;
			<select name="qqlevel" class="qqlevel">
				<option value="0">0级</option>
				<option value="1">1级</option>
				<option value="2">2级</option>
				<option value="3">3级</option>
				<option value="4">4级</option>
				<option value="5">5级</option>
			</select>
		</td>
	</tr>
	   <th width="50"><label><input type="checkbox" class="quxuanall" value="checkbox" />编号</label></th>
	   <th>ID</th>
	   <th>昵称</th>
	   <th>佣金</th>
	   <th>绩效</th>
	   <th>直推人数</th>
	   <th>级别</th>
	   <th>手机号</th>
	   <th><a href="<?php echo $dt;?>">加入时间</a></th>
	   <th><a href="<?php echo $ip;?>">加入IP地址[地区]</a></th>
	   <th width="50">操作</th>
	</tr>
	<?php 
	if(!empty($userlist)){ 
	foreach($userlist as $row){
	?>
	<tr>
	<td><input type="checkbox" name="quanxuan" value="<?php echo $row['user_id'];?>" class="gids"/></td>
	<td><?php echo $row['user_id'];?></td>
	<td><a href="javascript:;" style=" text-align:center;position:relative; display:block; background-color:#E2E8EB; border-bottom:2px solid #ccc; border-right:2px solid #ccc; padding-left:5px"><?php echo !empty($row['nickname']) ? $row['nickname'] : $row['user_name'];?></a>
	</td>
	<td align="center"><?php echo $row['yjmoney'];?></td>
	<td><?php echo $row['jxmoney'];?></td>
	<td><?php echo $row['per'];?></td>
	<td><?php echo $row['level_name'];?></td>
	<td><?php echo $row['mobile_phone'];?>&nbsp;</td>
	<td><?php echo !empty($row['reg_time']) ? date('Y-m-d H:i:s',$row['reg_time']) : '无知';?></td>
	<td><?php echo !empty($row['last_login']) ? date('Y-m-d H:i:s',$row['last_login']).'<br /><font color="#FF0000">['.Import::ip()->ipCity($row['last_ip']).']</font>' : '无知';?></td>
	<td>
	<a href="user.php?type=fxuserinfo&id=<?php echo $row['user_id'];?>" title="编辑"><img src="<?php echo $this->img('icon_edit.gif');?>" title="编辑"/></a>&nbsp;
	<?php if($row['user_id']!='18'){?><img src="<?php echo $this->img('icon_drop.gif');?>" title="删除" alt="删除" id="<?php echo $row['user_id'];?>" class="deluserid"/><?php }?>
	</td>
	</tr>
	<?php
	 } ?>
	<tr>
		 <td colspan="9"> <input type="checkbox" class="quxuanall" value="checkbox" />
			  <input type="button" name="button" value="批量删除" disabled="disabled" class="bathdel" id="bathdel"/>
		 </td>
	</tr>
		<?php } ?>
	 </table>
	 <?php $this->element('page',array('pagelink'=>$pagelink));?>
</div>
<script type="text/javascript">
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
		
		location.href='<?php echo Import::basic()->thisurl();?>&keyword='+keys;
	});
	//选择显示等级&nbsp
	$(document).ready(function(){ 
		$('.level').change(function(){
			location.href='user.php?type=djuser&level='+$(this).children('option:selected').val();
		}) 
	}) 
	//选择显示qq等级&nbsp
	$(document).ready(function(){ 
		$('.qqlevel').change(function(){
			location.href='user.php?type=djuser&qqlevel='+$(this).children('option:selected').val();
		}) 
	})
	
	function showsuppliersinfo(uid){
		JqueryDialog.Open('配送区域','<?php echo ADMIN_URL;?>ajax_show_suppliers_info.php?uid='+uid,750,400,'frame');
	}
</script>
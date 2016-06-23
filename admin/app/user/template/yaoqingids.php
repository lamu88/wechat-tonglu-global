<?php
$thisurl = ADMIN_URL.'user.php'; 
?>
<style type="text/css"> .contentbox table th{ font-size:12px; text-align:center}</style>
<div class="contentbox">
     <table cellspacing="2" cellpadding="5" width="100%">
	 <tr>
		<th align="left" style="text-align:left">确认转移</th>
	</tr>
	<tr>
		<td align="left"><b>第一步：</b></td>
	</tr>
	<tr>
	<td>
	<b>是否将以下用户转移到下面用户:</b>
	<?php if(!empty($lists))foreach($lists as $row){?>
	<a style=" background:#ededed; border-bottom:1px solid #ccc; border-right:1px solid #ccc;padding:3px 5px 3px 5px; margin-right:5px;"><?php echo !empty($row['nickname']) ? $row['nickname'] : '未知'.$row['uid'];?></a>
	<?php } ?>
	</td>
	</tr>
	<tr>
		<td align="left"><b>第二步：</b></td>
	</tr>
	<tr>
		<td align="left">
			<img src="<?php echo $this->img('icon_search.gif');?>" alt="SEARCH" width="26" border="0" height="22" align="absmiddle">
			搜索转移到的用户 <input name="keyword" size="15" type="text" value="<?php echo isset($_GET['keyword']) ? $_GET['keyword'] : "";?>">
		  <select name="touserid">
		  <option value="0">选择用户</option>
		  </select>
		  <input value=" 搜索 " class="cate_search" type="button">
		</td>
	</tr>
	<tr>
		<td align="left"><b>第三步：</b></td>
	</tr>
	<tr>
		<td align="left">
		<input value=" 确认转移 " style="cursor:pointer" type="button" onclick="return ajax_confirm_zhuanyi()">
		</td>
	</tr>
	 </table>
	
</div>
<script type="text/javascript">
	$('.cate_search').click(function(){
		keys = $('input[name="keyword"]').val();
		$.post('<?php echo $thisurl;?>',{action:'ajax_get_zhuanyiuser',keys:keys},function(data){
			$('select[name="touserid"]').html(data);
		});
		
	});

	function ajax_confirm_zhuanyi(){
		if(confirm('确定吗')){
			touid = $('select[name="touserid"]').val();
			
			if(touid=='0'){
				alert('请选择转移到用户');
				return false;
			}
			ids = '<?php echo $_GET['ids'];?>';
			$.post('<?php echo $thisurl;?>',{action:'ajax_confirm_zhuanyi',ids:ids ,touid:touid},function(data){
				alert(data);
			});
		}
		return true;
	}
	
</script>
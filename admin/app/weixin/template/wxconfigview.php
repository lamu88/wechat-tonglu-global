<div class="contentbox">
<form id="form1" name="form1" method="post" action="">
     <table cellspacing="2" cellpadding="5" width="100%">
	 <tr>
		<th colspan="2" align="left"><span style="float:left">API信息</span><span style="float:right"><a href="weixin.php?type=wxconfig">返回内容列表</a></span></th>
	</tr>
	  <tr>
		<td class="label">公众名称:</td>
		<td><?php echo isset($rt['wxname']) ? $rt['wxname'] : '';?></td>
	  </tr>
	
	  <tr>
		<td class="label">appid:</td>
		<td>
		  <?php echo isset($rt['appid']) ? $rt['appid'] : '';?>
		</td>
	  </tr>
	   <tr>
		<td class="label">appsecret:</td>
		<td>
		 <?php echo isset($rt['appsecret']) ? $rt['appsecret'] : '';?>
		</td>
	  </tr>
	  <tr>
	  	<td class="label">URL:</td>
		<td><?php echo SITE_URL.'api/index.php/'.$rt['token'].'/';?>
		<br/>如果以上无效，使用:<?php echo SITE_URL.'api/index.php?t='.$rt['token'];?>
		</td>
	  </tr>
	  <tr>
	  	<td class="label">TOKEN:</td>
		<td><?php echo $rt['pigsecret'];?></td>
	  </tr>
	 </table>
	 </form>
</div>

<?php  $thisurl = ADMIN_URL.'con_new.php'; ?>
<script type="text/javascript">
<!--
//jQuery(document).ready(function($){
	$('.new_save').click(function(){
		wxnames = $('#wxname').val();
		if(wxnames=='undefined' || art_title==""){
			alert("公众号名称不能为空");
			return false;
		}
		return true;
	});
//});
-->
</script>

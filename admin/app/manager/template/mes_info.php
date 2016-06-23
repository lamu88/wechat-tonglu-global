<div class="contentbox">
  <table cellspacing="2" cellpadding="5" width="100%">
	 <tr>
		<th colspan="2" align="left">留言详情信息</th>
	</tr>
	 <tr>
		<td class="label" width="15%">留言人：</td>
		<td width="85%"><?php echo $rt['dbuser_name'].'&nbsp;[<font color=red>'.$rt['nickname'].'</font>]';?>&nbsp;&nbsp;<a href="user.php?type=info&id=<?php echo $rt['user_id'];?>" target="_blank">查看该用户详情信息</a></td>
    </tr>
	  <tr>
		<td class="label">留言标题：</td>
		<td><?php echo '对商品<b>&nbsp;<a href="'.SITE_URL.'product/'.$rt['goods_id'].'/" target="_blank">'.$rt['goods_name'].'</a>&nbsp;</b>的提问';?></td>
	  </tr>
	  <!--<tr>
		<td class="label">公司名称：</td>

		<td><?php echo $rt['companyname'];?></td>
	  </tr>
	  <tr>
		<td class="label">公司地址：</td>

		<td><?php echo $rt['address'];?></td>
	  </tr>
	  <tr>
		<td class="label">公司网址：</td>

		<td><?php echo $rt['companyurl'];?></td>
	  </tr>
	  <tr>
		<td class="label">行业：</td>

		<td><?php echo $rt['trade'];?></td>
	  </tr>
	  <tr>
		<td class="label">职务：</td>

		<td><?php echo $rt['jobs'];?></td>
	  </tr>
	  <tr>
		<td class="label">电子邮箱：</td>

		<td><?php echo $rt['email'];?></td>
	  </tr>
	   <tr>
		<td class="label">电话：</td>

		<td><?php echo $rt['telephone'];?></td>
	  </tr>
	   <tr>
		<td class="label">手机：</td>

		<td><?php echo $rt['mobile'];?></td>
	  </tr>-->
	   <!--<tr>
		<td class="label">传真：</td>

		<td><?php echo $rt['fax'];?></td>
	  </tr>-->
	  <tr>
		<td class="label">留言IP：</td>

		<td><?php echo $rt['ip_address'];?><font color="#FF0000">[<?php echo $rt['ip_from'];?>]</font></td>
	  </tr>
	  <tr>
		<td class="label">留言时间：</td>
		<td><?php echo isset($rt['addtime'])&&!empty($rt['addtime']) ? date('Y-m-d H:i:s',$rt['addtime']) : '';?></td>
	  </tr>
	  <tr>
		<td class="label">留言内容：</td>
		<td><?php echo $rt['comment_title'];?><input type="hidden" id="comment_title" value="<?php echo $rt['comment_title'];?>"/></td>
	  </tr>
	  <tr>
		<td class="label">我要备注： </td>
		<td><textarea name="admin_remark" id="admin_remark" style="width: 60%; height: 65px; overflow: auto; color: rgb(68, 68, 68);"><?php echo isset($rt['admin_remark']) ? $rt['admin_remark'] : '';?></textarea></td>
	  </tr>
	  <tr><th colspan="2"><hr /></th></tr>
	  <tr>
		<td class="label">回复留言： </td>
		<td><textarea name="rp_content" id="rp_content" style="width: 60%; height: 65px; overflow: auto; color: rgb(68, 68, 68);"><?php echo isset($rt['rp_content']) ? $rt['rp_content'] : '';?></textarea></td>
	  </tr>
	  <tr>
		<td>&nbsp;</td>
		<td>
		<input class="mes_save" value="<?php echo $type=='newedit' ? '修改' : '添加';?>保存" type="button">
		<input type="hidden" class="mes_id" value="<?php echo $rt['mes_id'];?>"/>
		</td>
	  </tr>
  </table>
</div>
<?php $this->element('showdiv');?>
<?php  $thisurl = ADMIN_URL.'manager.php'; ?>
<script type="text/javascript">
//jQuery(document).ready(function($){
	$('.mes_save').click(function (){
		isactive  = $('input[name="status"]:checked').val();
		if(typeof(isactive)=='undefined'){
		 	isactive = 1;
		}
		
		s_id = $('.mes_id').val(); 
		con = $('#admin_remark').val();
		title = $('#comment_title').val();
		rp_con = $('#rp_content').val();
		$('.openwindow').show(200);
		$.post('<?php echo $thisurl;?>',{action:'savemes',status:isactive,mes_id:s_id,admin_remark:con,title:title,rp_content:rp_con},function(data){ 
			if(data == ""){
				$('.openwindow').hide(200);
				$('.black_overlay').show(200);
				$('.white_content').show(200);
			}else{
				$('.openwindow').hide(200);
				alert(data);
			}
		});
	});
//});
</script>
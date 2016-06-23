<div class="contentbox">
<form id="form1" name="form1" method="post" action="">
     <table cellspacing="2" cellpadding="5" width="100%">
	 	 <tr>
			<th colspan="2" align="left">评论详情</th>
		</tr>
		<tr>
			<td colspan="2">
			<P style="background:#F5F7F2; line-height:25px; margin-bottom:0px; border-bottom:1px solid #333"><strong><a href="mailto:<?php echo !empty($rt['email']) ? $rt['email'] : "";?>"><?php echo $rt['user_name'];?></a></strong> 于  <?php echo !empty($rt['add_time'])? date('H-m-d H:i:s',$rt['add_time']) : '无知';?> 对 <strong><a href="../goods.php?id=<?php echo $rt['goods_id'];?>" target="_blank"><?php echo $rt['goods_name'];?></a></strong> 发表评论
			</P>
			<p><?php echo $rt['content'];?></p>
			<p style="text-align:right"><strong>IP地址：</strong><?php echo $rt['ip'];?>&nbsp;<strong>来源：</strong><?php echo $rt['ip_form'];?></p>
			<p align="center">
			  当前的状态：<input type="button" class="isshow" value="<?php echo $rt['status']=='1'? '允许显示' : '禁止显示';?>" alt="<?php echo $rt['status']=='1' ? '0' : '1';?>" id="<?php echo $rt['comment_id'];?>"/>
			</p>
			</td>
		</tr>
		<tr>
		<td colspan="2">
		<p><b>满意度：</b><br  />
		总体满意度：<font color="red"><?php echo $rt['comment_rank']==3 ? '好评' : ($rt['comment_rank']==2 ? '中评' : '差评');?></font><br />
	  <table width="230" border="0"  cellpadding="0" cellspacing="0">
	  <tr>
		<td style="height:17px; line-height:17px;"><b>用户综合满意度</b></td>
	  </tr>
	  <?php 
	  $rank1 = $rt['goods_rand'];
	  $rank2 = $rt['shopping_rand'];
	  $rank3 = $rt['saleafter_rand'];
	  ?>
	  <tr>
		<td><b style="font-size:10px">■</b>  产品质量 <?php for($i=0;$i<$rank1;$i++){?><img src="<?php echo $this->img('onestar.gif');?>" align="absmiddle"/><?php } ?></td>
	  </tr>
	  <tr>
		<td><b style="font-size:10px">■</b>  物流配送 <?php for($i=0;$i<$rank2;$i++){?><img src="<?php echo $this->img('onestar.gif');?>" align="absmiddle"/><?php } ?></td>
	  </tr>
	  <tr>
		<td><b style="font-size:10px">■</b>  售后服务 <?php for($i=0;$i<$rank3;$i++){?><img src="<?php echo $this->img('onestar.gif');?>" align="absmiddle"/><?php } ?></td>
	  </tr>
	</table>
		</p>
		</td>
		</tr>
		<tr>
		<td align="center"> <input  value="返回" class="button" type="button" onclick="history.go(-1)"></td>
		</tr>
		<!--
		<?php if(!empty($rt['rp_conent'])){?>
		<tr>
			<td colspan="2">
			<P style="background:#F5F7F2; line-height:25px; margin-bottom:0px; border-bottom:1px solid #333"><strong>管理员</strong> <a href="mailto:<?php echo !empty($rt['rp_email']) ? $rt['rp_email'] : "";?>"><?php echo $rt['adname'];?></a> <strong>于</strong>  <?php echo !empty($rt['rp_addtime'])? date('H-m-d H:i:s',$rt['rp_addtime']) : '无知';?> <strong>回复</strong>&nbsp;&nbsp;<strong>最后更新</strong><font color="#FF0000">[<?php echo !empty($rt['up_time']) ? date('H-m-d H:i:s',$rt['up_time']) : '无更新';?>]</font>
			</P>
			<p><?php echo $rt['rp_conent'];?></p>
			<p style="text-align:right"><strong>IP地址：</strong><?php echo $rt['rp_ip'];?>&nbsp;</p>
			</td>
		</tr>
                <?php } ?>
               <tr>
                        <td colspan="2"><strong>回复评论</strong> </td>
                </tr>
              <tr>
                <td>用户名:</td>
                    <td>
                        <input value="<?php echo !empty($rt['adname'])? $rt['adname'] : $rp_mes['adminname'];?>" size="30" readonly="true" type="text">
                        <input type="hidden" name="user_id" value="<?php echo $rt['adminid'];?>"/>
                    </td>
              </tr>
              <tr>
                <td>Email:</td>
                <td><input name="email" value="<?php echo !empty($rt['rp_email'])? $rt['rp_email'] : $rp_mes['email'];?>" size="30" readonly="true" type="text"></td>
              </tr>
              <tr>
                <td>回复内容:</td>
                <td><textarea name="content" cols="50" rows="4"><?php echo $rt['rp_conent'];?></textarea></td>
              </tr>
			  <?php if(!empty($rt['rp_conent'])){?>
			  <tr>
				<td>&nbsp;</td>
				<td>提示: 此条评论已有回复, 如果继续回复将更新原来回复的内容!</td>
			  </tr>
			  <?php } ?>
              <tr>
                <td>&nbsp;</td>
                <td>
                  <input  value=" 确定 " class="button" type="submit" onclick="return checkvar()">
                  <input value=" 重置 " class="button" type="reset">
                     <?php if(!empty($rt['rp_conent'])){?>
                      <input type="hidden" name="comment_id" value="<?php echo $rt['rp_com_id'];?>"/>
                      <?php } ?>
                  <input name="id_value" value="<?php echo $rt['goods_id'];?>" type="hidden" />
                </td>
              </tr>-->
</table>
</form>
</div>

<?php  $thisurl = ADMIN_URL.'goods.php'; ?>
<script language="javascript">
function checkvar(){
	var val = $('textarea[name="content"]').val();
	if(typeof(val)=='undefined' || val==""){
		alert("必须输入回复内容！");
		return false;
	}
	return true;
}
$('.isshow').click(function(){
   		star = $(this).attr('alt');
		cid = $(this).attr('id'); 
		type = 'active';
		obj = $(this);
		$.post('<?php echo $thisurl;?>',{action:'active_comment',active:star,cid:cid,type:type},function(data){
			if(data == ""){
				if(star == 1){
					id = 0;
					text = '允许显示';
				}else{
					id = 1;
					text = '禁止显示';
				}
				obj.attr('alt',id);
				obj.val(text);
			}else{
				alert(data);
			}
		});
});
</script>
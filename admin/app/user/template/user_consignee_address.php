<div class="contentbox">
			<?php
			 if(!empty($rt['userress'])){
			 foreach($rt['userress'] as $row){
			 ?>
			 <form id="CONSIGNEE_ADDRESS" name="form1" method="post" action="">
			<table width="100%" border="0" cellspacing="0" cellpadding="0" style="line-height:30px;border-bottom:1px solid #007b74;">
			<tr>
				<th colspan="4" align="left">会员收货地址<a style="float:right" href="user.php?type=info&id=<?php echo $_GET['id'];?>">返回会员信息</a></th>
			</tr>
			<tr>
					<td align="right">省名：</td>
					<td colspan="3" align="left">
					<?php $this->element('address',array('dbtype'=>array('province'=>$row['province'],'city'=>$row['city'],'district'=>$row['district'],'town'=>$row['town'],'village'=>$row['village'],'shop_id'=>$row['shop_id']),'dbress'=>array('province'=>$rt['province'],'town'=>$rt['town'][$row['address_id']],'village'=>$rt['village'][$row['address_id']],'peisong'=>array(),'city'=>$rt['city'][$row['address_id']],'district'=>$rt['district'][$row['address_id']])));?>
					<span class="require-field">*</span> </td>
			  </tr>
				<tr>
					<td align="right">收货人：</td>

					<td align="left"><input name="consignee" class="text"  value="<?php echo $row['consignee'];?>" type="text">
					<span class="require-field">*</span> </td>
					<td align="right">电子邮件：</td>
					<td align="left"><input name="email" class="text"  value="4<?php echo $row['email'];?>" type="text">
					<span class="require-field">*</span></td>
				</tr>
				<tr>

					<td align="right">收货地址：</td>
					<td align="left"><input name="address" class="text"  value="<?php echo $row['address'];?>" type="text">
					<span class="require-field">*</span></td>
					<td align="right">邮政编码：</td>
					<td align="left"><input name="zipcode" class="text"  value="<?php echo $row['zipcode'];?>" type="text"></td>
				</tr>
				<tr>

					<td align="right">电话号码：</td>
					<td align="left"><input name="tel" class="text" value="<?php echo $row['tel'];?>" type="text">
					<span class="require-field">*</span></td>
					<td align="right">手机号码：</td>
					<td align="left"><input name="mobile" class="text" value="<?php echo $row['mobile'];?>" type="text"></td>
				</tr>
				<tr>

					<td align="right">标记建筑：</td>
					<td align="left"><input name="sign_building" class="text"  value="<?php echo $row['sign_building'];?>" type="text"></td>
					<td align="right">最佳收货时间：</td>
					<td align="left"><input name="best_time" class="text" value="<?php echo $row['best_time'];?>" type="text"></td>
				</tr>
				<tr>
					<td align="right">&nbsp;</td>
					<td colspan="3" align="center">
					 <input class="bnt_blue_1" value="确认修改" type="submit">
 					 <input class="bnt_blue" onclick="delress('<?php echo $row['address_id'];?>','<?php echo $_GET['id'];?>')" value="删除" type="button">
                    <input name="address_id" value="<?php echo $row['address_id'];?>" type="hidden">
					</td>
				</tr>
			</table>
			  </form>
			 <br  />
			  <script type="text/javascript">
			  function delress(id,uid){
			  	if(confirm("确定删除这条收货地址吗？")){
					$.post('user.php',{action:'delress',id:id,uid:uid},function(data){
						if(data==""){
						location.reload();
						}
					});
				}
			  }
			  </script>
			  <?php
			  }
			  } //else{
			  ?>
			   <form id="CONSIGNEE_ADDRESS" name="form1" method="post" action="">
			<table width="100%" border="0" cellspacing="0" cellpadding="0" style="line-height:30px;">
			<tr>
					<td align="right">省名：</td>
					<td colspan="3" align="left">
					<?php $this->element('address',array('dbress'=>array('province'=>$rt['province'])));?>
					<span class="require-field">*</span> </td>
			  </tr>
				<tr>
					<td align="right">收货人：</td>

					<td align="left"><input name="consignee" class="text"  value="<?php echo isset($post['consignee']) ? $post['consignee'] : "";?>" type="text">
					<span class="require-field">*</span> </td>
					<td align="right">电子邮件：</td>
					<td align="left"><input name="email" class="text"  value="<?php echo isset($post['email']) ? $post['email'] : "";?>" type="text">
					<span class="require-field">*</span></td>
				</tr>
				<tr>

					<td align="right">收货地址：</td>
					<td align="left"><input name="address" class="text"  value="<?php echo isset($post['address']) ? $post['address'] : "";?>" type="text">
					<span class="require-field">*</span></td>
					<td align="right">邮政编码：</td>
					<td align="left"><input name="zipcode" class="text"  value="<?php echo isset($post['zipcode']) ? $post['zipcode'] : "";?>" type="text"></td>
				</tr>
				<tr>

					<td align="right">电话号码：</td>
					<td align="left"><input name="tel" class="text" value="<?php echo isset($post['tel']) ? $post['tel'] : "";?>" type="text">
					<span class="require-field">*</span></td>
					<td align="right">手机号码：</td>
					<td align="left"><input name="mobile" class="text" value="<?php echo isset($post['mobile']) ? $post['mobile'] : "";?>" type="text"></td>
				</tr>
				<tr>

					<td align="right">标记建筑：</td>
					<td align="left"><input name="sign_building" class="text"  value="<?php echo isset($post['sign_building']) ? $post['sign_building'] : "";?>" type="text"></td>
					<td align="right">最佳收货时间：</td>
					<td align="left"><input name="best_time" class="text" value="<?php echo isset($post['best_time']) ? $post['best_time'] : "";?>" type="text"></td>
				</tr>
				<tr>
					<td align="right">&nbsp;</td>
					<td colspan="3" align="center">
					 <input class="bnt_blue_1" value="增加收货地址" type="submit">
					</td>
				</tr>
			</table>
			  </form>
			  <?php //} ?>
</div>
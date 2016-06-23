<div class="contentbox">
  <form id="form1" name="form1" method="post" action="">
  <table cellspacing="2" cellpadding="5" width="100%">
<tr>
        <td class="label" valign="top" width="20%">
                    确认订单时:
        </td>
        <td><?php $t1=""; $t2=""; if(isset($rt['confirm_order'])&&$rt['confirm_order']=='1'){ $t1 = ' checked="true"'; }else{ $t2 = ' checked="true"';}?>
				<label for="value_406_0"><input type="radio" name="confirm_order" id="value_406_0" value="1"<?php echo $t1;?>>发送邮件</label>
				<label for="value_406_1"><input type="radio" name="confirm_order" id="value_406_1" value="0"<?php echo $t2;?>>不发送邮件</label>
       </td>
    </tr>
	  <tr>
        <td class="label" valign="top">
                    取消订单时:
        </td>
        <td><?php $t1=""; $t2=""; if(isset($rt['cancel_order'])&&$rt['cancel_order']=='1'){ $t1 = ' checked="true"'; }else{ $t2 = ' checked="true"';}?>
                  <label for="value_408_0"><input type="radio" name="cancel_order" id="value_408_0" value="1"<?php echo $t1;?>>发送邮件</label>
                  <label for="value_408_1"><input type="radio" name="cancel_order" id="value_408_1" value="0"<?php echo $t2;?>>不发送邮件</label>
        </td>
      </tr>
	  <tr>
        <td class="label" valign="top">
                    订单无效时:
        </td>
        <td><?php $t1=""; $t2=""; if(isset($rt['orders_invalid'])&&$rt['orders_invalid']=='1'){ $t1 = ' checked="true"'; }else{ $t2 = ' checked="true"';}?>
                  <label for="value_409_0"><input type="radio" name="orders_invalid" id="value_409_0" value="1"<?php echo $t1;?>>发送邮件</label>
                  <label for="value_409_1"><input type="radio" name="orders_invalid" id="value_409_1" value="0"<?php echo $t2;?>>不发送邮件</label>
          
        </td>
      </tr>
	  <tr>
        <td class="label" valign="top">
                    发货时:
        </td>
        <td><?php $t1=""; $t2=""; if(isset($rt['shipping'])&&$rt['shipping']=='1'){ $t1 = ' checked="true"'; }else{ $t2 = ' checked="true"';}?>
                 <label for="value_407_0"><input type="radio" name="shipping" id="value_407_0" value="1"<?php echo $t1;?>>发送邮件</label>
                 <label for="value_407_1"><input type="radio" name="shipping" id="value_407_1" value="0"<?php echo $t2;?>>不发送邮件</label>
        </td>
      </tr>
	  <tr>
        <td class="label" valign="top">
                    收货时:
        </td>
        <td><?php $t1=""; $t2=""; if(isset($rt['getting_goods'])&&$rt['getting_goods']=='1'){ $t1 = ' checked="true"'; }else{ $t2 = ' checked="true"';}?>
                 <label for="value_4014_0"><input type="radio" name="getting_goods" id="value_4014_0" value="1"<?php echo $t1;?>>发送邮件</label>
                 <label for="value_4014_1"><input type="radio" name="getting_goods" id="value_4014_1" value="0"<?php echo $t2;?>>不发送邮件</label>
        </td>
      </tr>
	   <tr>
        <td class="label" valign="top">
                    新订单到时提醒管理员:
        </td>
        <td><?php $t1=""; $t2=""; if(isset($rt['new_orders_alert_admin'])&&$rt['new_orders_alert_admin']=='1'){ $t1 = ' checked="true"'; }else{ $t2 = ' checked="true"';}?>
                  <label for="value_410_0"><input type="radio" name="new_orders_alert_admin" id="value_410_0" value="1"<?php echo $t1;?>>发送邮件</label>
                  <label for="value_410_1"><input type="radio" name="new_orders_alert_admin" id="value_410_1" value="0"<?php echo $t2;?>>不发送邮件</label>
          
        </td>
      </tr>
	  	   <tr>
        <td class="label" valign="top">
                    新订单到时提醒供应商:
        </td>
        <td><?php $t1=""; $t2=""; if(isset($rt['new_orders_alert_suppliers'])&&$rt['new_orders_alert_suppliers']=='1'){ $t1 = ' checked="true"'; }else{ $t2 = ' checked="true"';}?>
                  <label for="value_411_0"><input type="radio" name="new_orders_alert_suppliers" id="value_411_0" value="1"<?php echo $t1;?>>发送邮件</label>
                  <label for="value_411_1"><input type="radio" name="new_orders_alert_suppliers" id="value_411_1" value="0"<?php echo $t2;?>>不发送邮件</label>
          
        </td>
      </tr>
	  <tr>
        <td class="label" valign="top">
                    有买家评论时提醒:
        </td>
        <td><?php $t1=""; $t2=""; if(isset($rt['buyer_comments'])&&$rt['buyer_comments']=='1'){ $t1 = ' checked="true"'; }else{ $t2 = ' checked="true"';}?>
                  <label for="value_412_0"><input type="radio" name="buyer_comments" id="value_412_0" value="1"<?php echo $t1;?>>发送邮件</label>
                  <label for="value_412_1"><input type="radio" name="buyer_comments" id="value_412_1" value="0"<?php echo $t2;?>>不发送邮件</label>
          
        </td>
      </tr>
	  <tr>
        <td class="label" valign="top">
                    有买家咨询时提醒:
        </td>
        <td><?php $t1=""; $t2=""; if(isset($rt['buyer_message'])&&$rt['buyer_message']=='1'){ $t1 = ' checked="true"'; }else{ $t2 = ' checked="true"';}?>
                  <label for="value_413_0"><input type="radio" name="buyer_message" id="value_413_0" value="1"<?php echo $t1;?>>发送邮件</label>
                  <label for="value_413_1"><input type="radio" name="buyer_message" id="value_413_1" value="0"<?php echo $t2;?>>不发送邮件</label>
          
        </td>
      </tr>
	  <tr>
        <td class="label" valign="top">
                    用户注册时提醒:
        </td>
        <td><?php $t1=""; $t2=""; if(isset($rt['register'])&&$rt['register']=='1'){ $t1 = ' checked="true"'; }else{ $t2 = ' checked="true"';}?>
                  <label for="value_414_0"><input type="radio" name="register" id="value_414_0" value="1"<?php echo $t1;?>>发送邮件</label>
                  <label for="value_414_1"><input type="radio" name="register" id="value_414_1" value="0"<?php echo $t2;?>>不发送邮件</label>
          
        </td>
      </tr>
	  	  <tr>
        <td class="label" valign="top">
                    修改密码时:
        </td>
        <td><?php $t1=""; $t2=""; if(isset($rt['editpassword'])&&$rt['editpassword']=='1'){ $t1 = ' checked="true"'; }else{ $t2 = ' checked="true"';}?>
                  <label for="value_415_0"><input type="radio" name="editpassword" id="value_415_0" value="1"<?php echo $t1;?>>发送邮件</label>
                  <label for="value_415_1"><input type="radio" name="editpassword" id="value_415_1" value="0"<?php echo $t2;?>>不发送邮件</label>
          
        </td>
      </tr>
	  	  <tr>
        <td class="label" valign="top">
                    找回密码时:
        </td>
        <td><?php $t1=""; $t2=""; if(isset($rt['findpassword'])&&$rt['findpassword']=='1'){ $t1 = ' checked="true"'; }else{ $t2 = ' checked="true"';}?>
                  <label for="value_416_0"><input type="radio" name="findpassword" id="value_416_0" value="1"<?php echo $t1;?>>发送邮件</label>
                  <label for="value_416_1"><input type="radio" name="findpassword" id="value_416_1" value="0"<?php echo $t2;?>>不发送邮件</label>
          
        </td>
      </tr>
	  	<tr>
		 <td colspan="2"> 
		 <input type="submit" value="保存更改" style="cursor:pointer"/>
		 </td>
	</tr>
  </table>
    </form>

</div>

<?php  $thisurl = ADMIN_URL.'topic.php'; ?>
<script type="text/javascript" language="javascript">
   $('.delgoodsid').click(function(){
   		ids = $(this).attr('id');
		thisobj = $(this).parent().parent();
		if(confirm("确定删除吗？")){
			createwindow();
			$.get('<?php echo $thisurl;?>',{type:'delgoods',ids:ids},function(data){
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
 </script>
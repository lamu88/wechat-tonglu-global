<style type="text/css">
.gift_left{ float:left;margin-right:20px; margin-left:5px; border-right:1px dotted #ccc}
.gift_left th{ text-align:center}
.gift_right{ float:left; border-left:1px dotted #ccc}

</style>
<div class="contentbox">
<div class="gift_left">
 <table cellspacing="2" cellpadding="5" width="450">
 <tr>
 <th>赠品名称</td> <th>最低消费额</td><th>操作</td>
 </tr>
 <?php if(!empty($rt)){
 foreach($rt as $k=>$row){
 ++$k;
 ?>
 <tr>
 <td> 
 <?php echo isset($row['gname']) ? $row['gname'] : "";?>
 </td>
 <td> <?php echo isset($row['minspend']) ? $row['minspend'] : "";?></td>
 <td><a href="goods.php?type=spend_gift&tt=update&id=<?php echo $k;?>">修改</a>&nbsp;&nbsp;<!--<a href="goods.php?type=spend_gift&tt=del&id=<?php echo $k;?>">删除</a>--></td>
 </tr>
 <?php } } ?>
 </table>
</div>
<div class="gift_right">
  <form id="form1" name="form1" method="post" action="">
  <?php if(isset($_GET['id'])&&$_GET['id']>0){ ?>
  <p><a href="goods.php?type=spend_gift" style="color:#FF0000">新增一个规则</a></p>
  <?php } ?>
  <p>
    起赠品名称：<input type="text" name="gname" style="width:300px;" value="<?php echo isset($rts['gname']) ? $rts['gname'] : "";?>" />
  </p>
  <p>
    最低消费额：<input type="text" name="minspend" style="width:300px;" value="<?php echo isset($rts['minspend']) ? $rts['minspend'] : "";?>"/>必须为整数
  </p>
  
  <p>
  <input type="submit" value="<?php echo isset($_GET['id'])&&$_GET['id']>0 ? '修改' : '增加';?>" />
  </p>
  </form>
</div>
</div>
<table  width="100%" border="0" cellpadding="0" cellspacing="0" style="line-height:25px;">
    <tr>
    <th width="38">序号</th>
    <th width="160">时间</th>
    <th width="51">类型</th>
    <th width="51">收入</th>
    <th width="51">支出</th>
    <th>帐变原因</th>
  </tr>
  <?php
   if(!empty($rt['userpointlist'])){
   foreach($rt['userpointlist'] as $k=>$row){
   ++$k;
  ?>
    <tr>
    <td><?php echo 5*($rt['page']-1)+$k;?></td>
    <td><?php echo !empty($row['time']) ? date('Y-m-d H:i:s',$row['time']) : '无知';?></td>
    <td class="cr2">赠送</td>
    <td class="cr2"><?php echo $row['points']>0 ? $row['points'].'分' : '--';;?></td>
    <td class="cr2"><?php echo $row['points']<=0 ? str_replace('-','',$row['points']).'分' : '--';;?></td>
    <!--<td class="cr2">105</td>-->
    <td><?php echo $row['changedesc'];?></td>
  </tr>
  <?php } } ?>
  <tr>
  <td  colspan="5" style="border-bottom:1px dotted #fff; text-align:left; height:20px" class="pagesmoney">
  <style>
  .pagesmoney a{margin-right:5px; color:#FFFFFF; background-color:#F9C0D9; text-decoration:none;text-align:center;padding:3px 5px 3px 5px;}
  .pagesmoney a:hover{ text-decoration:underline}
  </style>
  <?php echo $rt['userpointpage']['showmes'].$rt['userpointpage']['first'].'&nbsp;'.$rt['userpointpage']['prev'].'&nbsp;'.$rt['userpointpage']['next'].'&nbsp;'.$rt['userpointpage']['last'];?>
  </td>
    <td  align="right"><span style="position:absolute; right:0px; bottom:0px; border-left:1px solid #B4C9C6; border-top:1px solid #B4C9C6"><img src="<?php echo ADMIN_URL;?>images/error_icon.png" alt="close" onclick="$('.user_point').toggle();"/></span></td>
  </tr>
</table>
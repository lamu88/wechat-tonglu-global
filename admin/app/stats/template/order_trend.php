<div class="contentbox">
 <p style="padding:5px; margin:0px; border-bottom:1px solid #B4C9C6;background-color:#EEF2F5"><b>订单概况分析</b></p>
 <p style="height:20px; line-height:20px; margin:0px 0px 0px 0px; padding:5px;background-color:#EEF2F5; border-bottom:1px solid #B4C9C6"><b>有效订单总金额：<font color="red">￥<?php echo $rt['successprice'];?>元</font></b></p>
 <form id="form1" name="form1" method="post" action="">
  <table cellspacing="2" cellpadding="5" width="100%">
 	  <tr>
	  	<th><img src="<?php echo $this->img('icon_search.gif');?>" alt="SEARCH" width="26" border="0" height="22" align="absmiddle">开始时间：<input type="text" name="start_date" id="df" value="<?php echo $rt['start_date'];?>" onClick="WdatePicker()"/>&nbsp;&nbsp;结束时间：<input type="text" name="end_date" id="df" value="<?php echo $rt['end_date'];?>" onClick="WdatePicker()"/>
	  	  &nbsp;&nbsp;<input type="submit" name="Submit" value="查询" />
  	    </th>
	  </tr>
      <tr>
        <td align="left">
            <OBJECT classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"  codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0" WIDTH="465" HEIGHT="320" id="General" ALIGN="middle">
	  <PARAM NAME="FlashVars" value="&dataXML=<?php echo $rt['order_data'];?>">
	  <PARAM NAME=movie VALUE="<?php echo ADMIN_URL;?>data/pie3d.swf?chartWidth=650&chartHeight=400">
	  <PARAM NAME=quality VALUE=high>
	  <PARAM NAME=bgcolor VALUE=#FFFFFF>
	  <param NAME="wmode" VALUE="opaque" />
	  <EMBED src="<?php echo ADMIN_URL;?>data/pie3d.swf?chartWidth=650&chartHeight=400" FlashVars="&dataXML=<?php echo $rt['order_data'];?>" quality=high bgcolor=#FFFFFF WIDTH="650" HEIGHT="400" NAME="General" ALIGN="middle"  wmode="opaque" TYPE="application/x-shockwave-flash" PLUGINSPAGE="http://www.macromedia.com/go/getflashplayer"></EMBED>
	  		</OBJECT>
        </td>
      </tr>
  </table>
 </form>
</div>
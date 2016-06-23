<style type="text/css">
.contentbox .tab{ display:none}
.nav .active{
	 background-color:#F5F5F5;
} 
.nav .other{
	 background-color:#E9E9E9;
} 
h2.nav{ border-bottom:1px solid #B4C9C6;font-size:13px; height:25px; line-height:25px; margin-top:5px;}
h2.nav a{ color:#999999; display:block; float:left; height:24px;width:113px; text-align:center; margin-right:1px; margin-left:1px; cursor:pointer}
</style>
<div class="contentbox">
 <p style="padding:5px; margin:0px; border-bottom:1px solid #B4C9C6; background-color:#EEF2F5"><b>销售概况分析</b></p>
 
  <form action="" method="post" style="background-color:#EEF2F5">
  <img src="<?php echo $this->img('icon_search.gif');?>" alt="SEARCH" width="26" border="0" height="22" align="absmiddle"><strong>年走势</strong>
  <select name="year_beginYear"><option value="2006">2006</option><option value="2007">2007</option><option value="2008">2008</option><option value="2009">2009</option><option value="2010">2010</option><option value="2011">2011</option><option value="2012">2012</option><option value="2013">2013</option><option value="2014" selected>2014</option><option value="2015">2015</option></select>  -
  <select name="year_endYear"><option value="2006">2006</option><option value="2007">2007</option><option value="2008">2008</option><option value="2009">2009</option><option value="2010">2010</option><option value="2011">2011</option><option value="2012">2012</option><option value="2013">2013</option><option value="2014">2014</option><option value="2015" selected>2015</option></select>  <input type="submit" name="query_by_year" value="查询" class="button" />
  <br />
  <img src="<?php echo $this->img('icon_search.gif');?>" alt="SEARCH" width="26" border="0" height="22" align="absmiddle"><strong>月走势</strong>
  <select name="month_beginYear"><option value="2006">2006</option><option value="2007">2007</option><option value="2008">2008</option><option value="2009">2009</option><option value="2010">2010</option><option value="2011">2011</option><option value="2012">2012</option><option value="2013">2013</option><option value="2014" selected>2014</option><option value="2015">2015</option></select>&nbsp;<select name="month_beginMonth"><option value="1" selected>01</option><option value="2">02</option><option value="3">03</option><option value="4">04</option><option value="5">05</option><option value="6">06</option><option value="7">07</option><option value="8">08</option><option value="9">09</option><option value="10">10</option><option value="11">11</option><option value="12">12</option></select>  -
  <select name="month_endYear"><option value="2006">2006</option><option value="2007">2007</option><option value="2008">2008</option><option value="2009">2009</option><option value="2010">2010</option><option value="2011">2011</option><option value="2012">2012</option><option value="2013">2013</option><option value="2014">2014</option><option value="2015" selected>2015</option></select>&nbsp;<select name="month_endMonth"><option value="1">01</option><option value="2">02</option><option value="3">03</option><option value="4">04</option><option value="5">05</option><option value="6" selected>06</option><option value="7">07</option><option value="8">08</option><option value="9">09</option><option value="10">10</option><option value="11">11</option><option value="12">12</option></select>  <input type="submit" name="query_by_month" value="查询" class="button" />
  </form>

 
 <h2 class="nav">
 <a class="active" onclick="show_hide('1'); return false;">订单走势</a>  
 <a class="other" onclick="show_hide('2'); return false;">销售额走势</a>  
</h2>
  
 <!--订单分析-->
 <table width="90%" cellspacing="0" cellpadding="3" id="tab1">
      <tr>
        <td align="left">
	  <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"
		codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0"
		width="565" height="420" id="FCColumn2" align="middle">
		<param NAME="movie" VALUE="<?php echo ADMIN_URL;?>data/column3d.swf?dataXML=<?php echo $rt['order_data'];?>">
		<param NAME="quality" VALUE="high">
		<param NAME="bgcolor" VALUE="#FFFFFF">
		<embed src="<?php echo ADMIN_URL;?>data/column3d.swf?dataXML=<?php echo $rt['order_data'];?>" quality="high" bgcolor="#FFFFFF"  width="565" height="420" name="FCColumn2" align="middle" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer">
	  </object>
        </td>
      </tr>
  </table>
 
  <!--销售分析-->
 <table width="90%" cellspacing="0" cellpadding="3" id="tab2" class="tab">
      <tr>
        <td align="left">
	  <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"
		codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0"
		width="565" height="420" id="FCColumn2" align="middle">
		<param NAME="movie" VALUE="<?php echo ADMIN_URL;?>data/column3d.swf?dataXML=<?php echo $rt['sale_data'];?>">
		<param NAME="quality" VALUE="high">
		<param NAME="bgcolor" VALUE="#FFFFFF">
		<embed src="<?php echo ADMIN_URL;?>data/column3d.swf?dataXML=<?php echo $rt['sale_data'];?>" quality="high" bgcolor="#FFFFFF"  width="565" height="420" name="FCColumn2" align="middle" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer">
	  </object>
        </td>
      </tr>
  </table>
</div>

<script language="javascript" type="text/javascript">
function show_hide(id){
	len = $('.nav a').length;
	if(len>1){
		for(i=1;i<=len;i++){
			if(i==id) { 
				$($('.nav a')[i-1]).removeClass();
				$($('.nav a')[i-1]).addClass('active');
				$("#tab"+id).css('display','block');
			}else{
				$($('.nav a')[i-1]).removeClass();
				$($('.nav a')[i-1]).attr('class',"other");
				$("#tab"+i).css('display','none');
			}
		}
	}
}
</script>
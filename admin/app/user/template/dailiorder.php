<div class="contentbox">
     <table cellspacing="2" cellpadding="5" width="100%">
	 <tr>
		<th colspan="10" align="left">分销商业绩&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		选择时间：<input type="text" id="EntTime1" name="EntTime1" onclick="return showCalendar('EntTime1', 'y-mm-dd');"  />
		至
		<input type="text" id="EntTime2" name="EntTime2" onclick="return showCalendar('EntTime2', 'y-mm-dd');"  />
		</th>
		
	</tr>
	
	 </table>
	 <?php $this->element('page',array('pagelink'=>$rt['pages']));?>
</div>

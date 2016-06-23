<style type="text/css">
.contentbox li{ width:25%; text-align:left; line-height:26px; float:left}
</style>
<div class="contentbox" style="height:360px; overflow:hidden; overflow-y:auto">
   <table cellspacing="1" cellpadding="5" width="100%">
   		<tr>
	   <td>
		 <img src="<?php echo $this->img('icon_search.gif');?>" alt="SEARCH" width="26" border="0" height="22" align="absmiddle">
    	关键字 <input id="keyword" size="15" type="text" value="">
    	<input value=" 搜索 " class="cate_search" type="button" onclick="getgroupgoods(this)">
		 </td>
	</tr>
	</table>
	<ul style="padding:0px 10px 0px 10px" class="ajax_html">
		<?php if(!empty($lists))foreach($lists as $row){?>
		<li><a href="javascript:;" onclick="setgoods('<?php echo $row['nickname'];?>','<?php echo $row['user_id'];?>')">
		->&nbsp;<?php echo empty($row['nickname']) ? '未知昵称' : $row['nickname'];?>
		</a></li>
		<?php } ?>		
		<div style="clear:both; border-bottom:2px solid #ccc; margin-bottom:10px"></div>
		
	</ul>
	
	<div style="clear:both"></div>
	<?php $this->element('page',array('pagelink'=>$pagelink));?>
</div>
<script type="text/javascript">
function setgoods(gname,gid){
	window.parent.setrunuser(gname,gid);
	alert("已选择:"+gname);
}
function getgroupgoods(obj){
	key = $(obj).parent().find('input[id="keyword"]').val();
	
	if( key!=""){
		createwindow();
		$.get('<?php echo ADMIN_URL.'user.php';?>',{type:'ajax_get_user',keyword:key},function(data){
			if(data !=""){
				$('.ajax_html').html(data);
			}
			removewindow();
		});
	}else{
		return false;
	}
}
</script>
	
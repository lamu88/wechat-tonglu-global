<style type="text/css">
.contentbox li{ width:25%; text-align:left; line-height:26px; float:left}
</style>
<div class="contentbox" style="height:360px; overflow:hidden; overflow-y:auto">
   <table cellspacing="1" cellpadding="5" width="100%">
   		<tr>
	   <td>
		 <img src="<?php echo $this->img('icon_search.gif');?>" alt="SEARCH" width="26" border="0" height="22" align="absmiddle">
    	<select id="cat_id">
	    <option value="0">所有分类</option>
		<?php 
		if(!empty($catelist)){
		 foreach($catelist as $row){ 
		?>
        <option value="<?php echo $row['id'];?>"><?php echo $row['name'];?></option>
			<?php 
				if(!empty($row['cat_id'])){
				foreach($row['cat_id'] as $rows){ 
					?>
					<option value="<?php echo $rows['id'];?>">&nbsp;&nbsp;<?php echo $rows['name'];?></option>
					<?php 
					if(!empty($rows['cat_id'])){
					foreach($rows['cat_id'] as $rowss){ 
					?>
							<option value="<?php echo $rowss['id'];?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $rowss['name'];?></option>
							
					<?php
					}//end foreach
					}//end if
					?>
			<?php
				}//end foreach
		 		} // end if
			?>
		<?php
		 }//end foreach
		} ?>
	 </select>
	 <select id="brand_id">
			 <option value="0">所有品牌</option>
			 <?php 
		if(!empty($brandlist)){
		 foreach($brandlist as $row){ 
		?>
        <option value="<?php echo $row['id'];?>"><?php echo $row['name'];?></option>
			<?php 
				if(!empty($row['brand_id'])){
				foreach($row['brand_id'] as $rows){ 
					?>
					<option value="<?php echo $rows['id'];?>">&nbsp;&nbsp;<?php echo $rows['name'];?></option>
					<?php 
					if(!empty($rows['brand_id'])){
					foreach($rows['brand_id'] as $rowss){ 
					?>
							<option value="<?php echo $rowss['id'];?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $rowss['name'];?></option>
							
					<?php
					}//end foreach
					}//end if
					?>
			<?php
				}//end foreach
		 		} // end if
			?>
		<?php
		 }//end foreach
		} ?>
		 </select>
		 <select id="is_goods_attr">
			 <option value="0">全部</option>
			<option value="1">促销</option>
			<option value="2">抢购</option>
		 </select>
    	关键字 <input id="keyword" size="15" type="text" value="">
    	<input value=" 搜索 " class="cate_search" type="button" onclick="getgroupgoods(this)">
		 </td>
	</tr>
	</table>
	<ul style="padding:0px 10px 0px 10px" class="ajax_html">
		<?php if(!empty($lists))foreach($lists as $row){?>
		<li><a href="javascript:;" onclick="setgoods('<?php echo $row['goods_name'];?>','<?php echo $row['goods_id'];?>','<?php echo SITE_URL.$row['goods_thumb'];?>')">
		<img src="<?php echo SITE_URL.$row['goods_thumb'];?>" style="max-width:90%;" />
		</a></li>
		<?php } ?>		
		<div style="clear:both; border-bottom:2px solid #ccc; margin-bottom:10px"></div>
		
	</ul>
	
	<div style="clear:both"></div>
	<?php $this->element('page',array('pagelink'=>$pagelink));?>
</div>
<script type="text/javascript">
function setgoods(gname,gid,img){
	window.parent.setrun(gname,gid,img);
	alert("已选择:"+gname);
}
function getgroupgoods(obj){
	cid = $(obj).parent().find('select[id="cat_id"]').val();
	bid = $(obj).parent().find('select[id="brand_id"]').val();
	key = $(obj).parent().find('input[id="keyword"]').val();
	cx = $(obj).parent().find('select[id="is_goods_attr"]').val();
	
	if(cid>0 || bid>0 || key!="" || cx>0){
		createwindow();
		$.get('<?php echo ADMIN_URL.'goods.php';?>',{type:'ajax_get_group_goods',cat_id:cid,brand_id:bid,keyword:key,cx:cx},function(data){
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
	
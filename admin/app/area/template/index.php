<div class="contentbox">
<style type="text/css">
.arealist li{ width:30%; border-bottom:1px dashed #ccc; height:25px; line-height:25px; margin-right:10px; float:left}
.arealist li span{ cursor:pointer; padding:3px}
.arealist li span:hover{ background-color:#66CCFF}
</style>
 <table cellspacing="2" cellpadding="5" width="100%">
	<tr>
	<th colspan="2">
	<form method="post" action="" name="theForm" style="float:left">
	新增<?php if($info['region_type']==0 && !isset($_GET['id'])){echo "一";}elseif($info['region_type']==0){echo "二";}elseif($info['region_type']==1){echo "三";}elseif($info['region_type']==2){echo "四";}elseif($info['region_type']==3){echo "五";}elseif($info['region_type']==4){echo "六";}elseif($info['region_type']==5){echo "七";}?>级地区:
	<input type="text" name="region_name" maxlength="150" size="40" style="height:20px; line-height:20px; border:1px solid #ccc">
	<input type="hidden" name="region_type" value="<?php echo !isset($info['region_type'])? 0 : intval($info['region_type'])+1;?>">
	<input type="hidden" name="parent_id" value="<?php echo !isset($info['region_id']) ? 0 : $info['region_id'];?>">
	<input type="submit" value=" 确定 " class="button" style="cursor:pointer" />
	</form>
	<?php if(isset($_GET['id'])){?><a href="area.php?type=list<?php echo $info['region_type']>0 ? '&id='.$info['parent_id']:'';?>" style="float:right">返回上一级</a><?php } ?>&nbsp;</th>
	</tr>
	<tr>
    <th colspan="2" style="text-align:center"><?php if(isset($_GET['id']) && !empty($info['region_name'])){echo '['.$info['region_name'].']';}?><?php if($info['region_type']==0 && !isset($_GET['id'])){echo "一";}elseif($info['region_type']==0){echo "二";}elseif($info['region_type']==1){echo "三";}elseif($info['region_type']==2){echo "四";}elseif($info['region_type']==3){echo "五";}elseif($info['region_type']==4){echo "六";}elseif($info['region_type']==5){echo "七";}?>级地区</th>
  	</tr>
	<tr>
	<td colspan="2">
	<ul class="arealist">
	<?php if(!empty($rt))foreach($rt as $row){?>
	<li><span class="region_name" id="<?php echo $row['region_id'];?>" style="font-weight:bold"><?php echo $row['region_name'];?></span>&nbsp;&nbsp;|&nbsp;&nbsp;<span class="edit"><a href="area.php?type=list&id=<?php echo $row['region_id'];?>">管理</a></span>&nbsp;<span class="del"><a href="area.php?type=list<?php echo isset($info['region_id']) ? '&id='.$info['region_id'] : '';?>&op=del&ids=<?php echo $row['region_id'];?>" onclick="return confirm('确定删除吗？')">删除</a></span><a><img src="<?php echo $this->img('yes.gif');?>" id="<?php echo $row['is_open']=='1' ? '0' : '1';?>" alt="<?php echo $row['region_id'];?>" align="absmiddle"/></a></li>
	<?php } ?>
	<div class="clear"></div>
	</ul>
	</td>
	</tr>
 </table>
</div>
<?php  $thisurl = ADMIN_URL.'area.php';?>
<script language="javascript" type="text/javascript">
	$('.region_name').click(function (){ edit(this); });
	function edit(object){
		thisvar = $(object).html();
		ids = $(object).attr('id');
		if(thisvar=="" || thisvar ==null || typeof(thisvar)=='undefined') return false;
		
		 if(typeof($(object).find('input').val()) == 'undefined'){
             var input = document.createElement('input');
			 $(input).attr('value', thisvar);
			  $(input).css('width', '40px');
             $(input).change(function(){ 
                 update(ids, this);
             })
             $(input).blur(function(){
                 $(this).parent().html($(this).val());
             });
             $(object).html(input);
             $(object).find('input').focus();
         }
	}
	
	function update(id, object){
       var editval = $(object).val();
       var obj = $(object).parent();
	   $.get('<?php echo $thisurl;?>',{type:'update_region_name',id:id,val:editval},function(data){
			 obj.html(editval);
           	 $(object).unbind('click');
           	 $(object).click(function(){
               edit(object);
             });
			 if(data!="" && data !=null && typeof(data)!='undefined'){
			 	alert(data);
			 }
		});
    }
</script>

<?php
$thisurl = ADMIN_URL.'shopping.php'; 
?>
<div class="contentbox">
     <table cellspacing="2" cellpadding="5" width="100%">
	<tr>
			<td align="left" width="25%">选择物流：</td>
			<td align="left">
			  <select name="wulliuid">
			  <option value="0">选择物流</option>
			  <?php if(!empty($rt))foreach($rt as $row){?>
			   <option value="<?php echo $row['shipping_id'];?>"><?php echo $row['shipping_name'];?></option>
			  <?php } ?>
		      </select>
			  </td>
	   </tr>
			<tr>
			<td align="left">生成号码：</td>
			<td align="left">
			  <label>
			  前缀<input type="text" name="ppt" style=" width:50px;height:22px; line-height:22px; border:1px solid #ccc" />
			  </label>
			   <label>
			  开始号码:<input type="text" name="startpt" style=" width:50px;height:22px; line-height:22px; border:1px solid #ccc" />
			  </label>至到
			  <label>
			   结束号码：<input type="text" name="endpt" style=" width:50px;height:22px; line-height:22px; border:1px solid #ccc" />
			  </label>					
			  </td>
			</tr>
			<tr>
			<td align="left">&nbsp;</td>
			<td align="left">
		 	  <label>
			 	<input type="submit" name="Submit" value="生成物流单号" style="cursor:pointer; padding:3px;" onclick="return submit_mark_sn()" />
		 	  </label>			
			</td>
			</tr>
			<tr>
				<td align="left">单个添加：</td>
				<td align="left">
				   <select name="wulliuid2">
				  <option value="0">选择物流</option>
				  <?php if(!empty($rt))foreach($rt as $row){?>
				   <option value="<?php echo $row['shipping_id'];?>"><?php echo $row['shipping_name'];?></option>
				  <?php } ?>
				  </select>
				  <input type="text" name="shipping_sn" style=" width:150px;height:22px; line-height:22px; border:1px solid #ccc" />
				  <input type="submit" value="添加" style="cursor:pointer; padding:3px;" onclick="return add_mark_sn()" />
				</td>
			</tr>
	</table>
	
	<table cellspacing="2" cellpadding="5" width="100%">
	 <tr>
		<th colspan="6" align="left">物流单</th>
	</tr>
	<tr><td colspan="6" align="left">
	物流公司&nbsp;<select name="shoppingid">
			  <option value="0">选择物流</option>
			  <?php if(!empty($rt))foreach($rt as $row){?>
			   <option value="<?php echo $row['shipping_id'];?>"<?php echo isset($_GET['sid'])&&$_GET['sid']==$row['shipping_id'] ? ' selected="selected"' : '';?>><?php echo $row['shipping_name'];?></option>
			  <?php } ?>
		      </select>
    	<img src="<?php echo $this->img('icon_search.gif');?>" alt="SEARCH" width="26" border="0" height="22" align="absmiddle">
    	关键字 <input name="keyword" size="15" type="text" value="<?php echo isset($_GET['keyword']) ? $_GET['keyword'] : "";?>">
    	<input value=" 搜索 " class="cate_search" type="button">
	</td></tr>
    <tr>
	   <th width="50"><label><input type="checkbox" class="quxuanall" value="checkbox" />编号</label></th>
	   <th>物流公司</th>
	   <th>单号</th>
	   <th>是否使用</th>
	   <th>添加时间</th>
	   <th width="50">操作</th>
	</tr>
	<?php 
	if(!empty($rts['lists'])){ 
	foreach($rts['lists'] as $row){
	?>
	<tr>
	<td><input type="checkbox" name="quanxuan" value="<?php echo $row['id'];?>" class="gids"/></td>
	<td align="center"><?php echo empty($row['shipping_name']) ? '未知' : $row['shipping_name'];?></td>
	<td><?php echo $row['shipping_sn'];?></td>
	<td>
	<?php echo $row['is_use']=='1' ? '<font color="#FF0000">[使用]</font><br/>'.date('m-d H:i',$row['usetime']) : '<font color="blue">[未使用]</font>';?>
	</td>
	<td><?php echo !empty($row['addtime']) ? date('Y-m-d H:i:s',$row['addtime']) : '无知';?></td>
	<td>
	<img src="<?php echo $this->img('icon_drop.gif');?>" title="删除" alt="删除" id="<?php echo $row['id'];?>" class="deluserid"/>
	</td>
	</tr>
	<?php
	 } ?>
	<tr>
		 <td colspan="6"> <input type="checkbox" class="quxuanall" value="checkbox" />
			  <input type="button" name="button" value="批量删除" disabled="disabled" class="bathdel" id="bathdel"/>
		 </td>
	</tr>
		<?php } ?>
	 </table>
	 <?php $this->element('page',array('pagelink'=>$rts['pages']));?>
</div>
<script type="text/javascript">
function add_mark_sn(){
	sid = $('select[name="wulliuid2"]').val();
	if(sid=="0"){
		alert("请选择物流");
		return false;
	}
	ptid = $('input[name="shipping_sn"]').val();
	if(ptid==""){
		alert("请物流单号");
		return false;
	}
	createwindow();
	$.post('<?php echo $thisurl;?>',{action:'ajax_add_mark_sn',shipping_sn:ptid,shopping_id:sid},function(data){
		removewindow();
		window.location.href='<?php echo Import::basic()->thisurl();?>';
	});
}

function submit_mark_sn(){
	//parseInt
	sid = $('select[name="wulliuid"]').val();
	if(sid=="0"){
		alert("请选择物流");
		return false;
	}
	ptid = $('input[name="ppt"]').val();
	if(ptid==""){
		alert("请输入前缀");
		return false;
	}
	startptid = $('input[name="startpt"]').val();
	if(startptid==""){
		alert("请输入开始号码");
		return false;
	}
	endptid = $('input[name="endpt"]').val();
	if(endptid==""){
		alert("请输入结束号码");
		return false;
	}
	ptid = parseInt(ptid);
	if(!(ptid>0)){
		alert("前缀请输入正数");
		return false;
	}
	startptid = parseInt(startptid);
	if(!(startptid>0)){
		alert("开始号码必须是前缀");
		return false;
	}
	endptid = parseInt(endptid);
	if(!(endptid>0)){
		alert("结束号码必须是前缀");
		return false;
	}
	if(startptid > endptid){
		alert("结束号码必须大于开始号码");
		return false;
	}
	createwindow();
	$.post('<?php echo $thisurl;?>',{action:'ajax_submit_mark_sn',sid:sid,ptid:ptid,startptid:startptid,endptid:endptid},function(data){
		removewindow();
		alert('操作成功');
		window.location.href='<?php echo Import::basic()->thisurl();?>';
	});
}

$('.cate_search').click(function(){
		sid = $('select[name="shoppingid"]').val();
		keys = $('input[name="keyword"]').val();
		
		location.href='<?php echo $thisurl;?>?type=shoppingsn&sid='+sid+'&keyword='+keys;
});
</script>
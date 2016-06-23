<div class="contentbox">
<form id="form1" name="form1" method="post" action="">
     <table cellspacing="2" cellpadding="5" width="100%">
	 <tr>
		<th colspan="2" align="left"><?php echo $type=='edit' ? '编辑' : '添加';?>团购<span style="float:right"><a href="groupbuy.php?type=list">返回团购</a></span></th>
	</tr>
    <tr>
	   <td width="150" class="label">团购商品名称</td>
	   <td>
	       <input type="text" name="group_name" value="<?php echo isset($rt['group_name']) ? $rt['group_name'] : "";?>" size="50"/>
	   </td>
	</tr>
	 <tr>
	   <td class="label">查找团购商品</td>
	   <td>
		 <img src="<?php echo $this->img('icon_search.gif');?>" alt="SEARCH" width="26" border="0" height="22" align="absmiddle">
    	<select name="cat_id">
	    <option value="0">所有分类</option>
		<?php 
		if(!empty($catelist)){
		 foreach($catelist as $row){ 
		?>
        <option value="<?php echo $row['id'];?>" <?php if(isset($_GET['cat_id'])&&$_GET['cat_id']==$row['id']){ echo 'selected="selected""'; } ?>><?php echo $row['name'];?></option>
			<?php 
				if(!empty($row['cat_id'])){
				foreach($row['cat_id'] as $rows){ 
					?>
					<option value="<?php echo $rows['id'];?>"  <?php if(isset($_GET['cat_id'])&&$_GET['cat_id']==$rows['id']){ echo 'selected="selected""'; } ?>>&nbsp;&nbsp;<?php echo $rows['name'];?></option>
					<?php 
					if(!empty($rows['cat_id'])){
					foreach($rows['cat_id'] as $rowss){ 
					?>
							<option value="<?php echo $rowss['id'];?>"  <?php if(isset($_GET['cat_id'])&&$_GET['cat_id']==$rowss['id']){ echo 'selected="selected""'; } ?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $rowss['name'];?></option>
							
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
	 <select name="brand_id">
			 <option value="0">所有品牌</option>
			 <?php 
		if(!empty($brandlist)){
		 foreach($brandlist as $row){ 
		?>
        <option value="<?php echo $row['id'];?>"<?php if(isset($_GET['brand_id'])&&$_GET['brand_id']==$row['id']){ echo '	selected="selected""'; } ?>><?php echo $row['name'];?></option>
			<?php 
				if(!empty($row['brand_id'])){
				foreach($row['brand_id'] as $rows){ 
					?>
					<option value="<?php echo $rows['id'];?>"<?php if(isset($_GET['brand_id'])&&$_GET['brand_id']==$rows['id']){ echo '	selected="selected""'; } ?>>&nbsp;&nbsp;<?php echo $rows['name'];?></option>
					<?php 
					if(!empty($rows['brand_id'])){
					foreach($rows['brand_id'] as $rowss){ 
					?>
							<option value="<?php echo $rowss['id'];?>"<?php if(isset($_GET['brand_id'])&&$_GET['brand_id']==$rowss['brand_id']){ echo '	selected="selected""'; } ?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $rowss['name'];?></option>
							
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
    	关键字 <input name="keyword" size="15" type="text" value="<?php echo isset($_GET['keyword']) ? $_GET['keyword'] : "";?>">
    	<input value=" 搜索 " class="cate_search" type="button" onclick="getgroupgoods(this)">
		 <br /><br />
		 <select name="goods_id" style="margin-left:32px">
		 <?php if(isset($rt['goods_name'])){?>
		 <option value="<?php echo $rt['goods_id'];?>"><?php echo $rt['goods_name'];?></option>
		 <?php } else {?>
		 <option value="0">请先点击上面搜索商品才能操作下一步</option>
		 <?php } ?>
         </select>
		 </td>
	</tr>
	   <tr>
	   <td class="label">团购日期</td>
	   <td align="left">
	   	  <input type="text" name="start_time" id="df" value="<?php echo isset($rt['start_time'])&&!empty($rt['start_time']) ? date('Y-m-d',$rt['start_time']) : date('Y-m-d',mktime());?>" onClick="WdatePicker()" style="background-color:#FAFAFA"/>
		  	<?php
			 $hs = date('G',$rt['start_time']);
			 $is = date('i',$rt['start_time']);
			 $ss = date('s',$rt['start_time']);
			 ?>
			<select name="xiaoshi_start">
			<?php for($i=0;$i<24;$i++){?>
			<option value="<?php echo $i;?>"<?php echo $i==$hs ? ' selected="selected"' : ''; ?>><?php echo $i;?></option>
			<?php } ?>
	        </select>：	
			<select name="fen_start">
			<?php for($i=0;$i<60;$i++){?>
			<option value="<?php echo $i;?>"<?php echo $i==ltrim($is,'0') ? ' selected="selected"' : ''; ?>><?php echo $i;?></option>
			<?php } ?>
	        </select>：
			<select name="miao_start">
			<?php for($i=0;$i<60;$i++){?>
			<option value="<?php echo $i;?>"<?php echo $i==ltrim($ss,'0') ? ' selected="selected"' : ''; ?>><?php echo $i;?></option>
			<?php } ?>
	        </select>
			&nbsp;-&nbsp;
			<?php
			 $hs = date('G',$rt['end_time']);
			 $is = date('i',$rt['end_time']);
			 $ss = date('s',$rt['end_time']);
			 ?>
	      <input type="text" name="end_time" id="dt" value="<?php echo isset($rt['end_time'])&&!empty($rt['end_time']) ? date('Y-m-d',$rt['end_time']) : date('Y-m-d',mktime()+7*24*3600);?>" onClick="WdatePicker()" style="background-color:#FAFAFA"/>
		  <select name="xiaoshi_end">
			<?php for($i=0;$i<24;$i++){?>
			<option value="<?php echo $i;?>"<?php echo $i==$hs ? ' selected="selected"' : ''; ?>><?php echo $i;?></option>
			<?php } ?>
	        </select>：	
			<select name="fen_end">
			<?php for($i=0;$i<60;$i++){?>
			<option value="<?php echo $i;?>"<?php echo $i==ltrim($is,'0') ? ' selected="selected"' : ''; ?>><?php echo $i;?></option>
			<?php } ?>
	        </select>：
			<select name="miao_end">
			<?php for($i=0;$i<60;$i++){?>
			<option value="<?php echo $i;?>"<?php echo $i==ltrim($ss,'0') ? ' selected="selected"' : ''; ?>><?php echo $i;?></option>
			<?php } ?>
	        </select>
		  &nbsp;<em>点击文本选择日期。</em>
	   </td>
	</tr>
	<tr>
	   <td class="label">赠送积分</td>
	   <td>
	       <input type="text" name="points" value="<?php echo isset($rt['points']) ? $rt['points'] : "";?>"/><em>输入一个整数，不输入，默认不送积分</em>
	   </td>
	</tr>
	<tr>
	   <td class="label">团购价</td>
	   <td>
	       <input type="text" name="prices" value="<?php echo isset($rt['price']) ? $rt['price'] : "";?>"/>
	   </td>
	</tr>
	<tr>
	   <td class="label">价格阶梯</td>
	   <td>
	   		<?php 
			if(!empty($rt['groupgoods'])){
			?>
			<p style="padding:5px; border:1px dashed #ccc;">
			<?php foreach($rt['groupgoods'] as $row){?>
			<span>数量：<?php echo $row['number'];?>&nbsp;&nbsp;&nbsp;价格：<?php echo $row['price'];?>&nbsp;&nbsp;<a href="javascript:;" onclick="return delgroupgoods(this,'<?php echo $row['gpid'];?>')" style="color:#FF0000">[删除]</a><br /></span>
			<?php } ?>
			</p>
			<?php } ?>
	       <p> 数量达到 <input type="text" name="number[]" value="" size="8" />&nbsp;&nbsp;
     	 	享受价格 <input type="text" name="price[]" value="" size="8" />
      		<a href="javascript:;" onclick="addobj(this)"><strong>[+]</strong></a> <em>单位是￥</em>
			</p>
	   </td>
	</tr>
	<tr>
	   <td class="label">活动是否开始</td>
	   <td>
	      <label>
		   <input type="radio" name="active" value="1"<?php echo !isset($rt['active'])||$rt['active']=='1' ? ' checked="checked"' : "";?>/>活动有效&nbsp;
	      </label>&nbsp;&nbsp;
		  <label>
		   <input type="radio" name="active" value="0"<?php echo !isset($rt['active'])||$rt['active']=='1' ? '' : ' checked="checked"';?>/>活动失效
	      </label>		   
		  </td>
	</tr>
	<tr>
	<td class="label">当前状态：</td>
	<td><b>
	<?php 
	$pr = ($rt['start_time']< mktime()&&$rt['end_time'] > mktime()) ? 1 : 0;
	 $is_delete = $rt['is_delete'];
	 $is_on_sale = $rt['is_on_sale'];
	echo $pr==0 ? "<font color=red>团购结束</font>" : ($rt['active']==0 ? "<font style='color:#6633FF'>活动无开启</font>" : ($is_delete=='1' ? '商品已删除' : ($is_on_sale='0' ? '商品已下架' : '团购进行中')));
	?></b>
	</td>
	</tr>
	<tr>
	   <td class="label">团购描述</td>
	   <td>
	       <textarea name="desc" id="content" style="width:95%;height:500px;overflow: auto;"><?php echo isset($rt['desc']) ? $rt['desc'] : "";?></textarea>
		   <script>KE.show({id : 'content',cssPath : '<?php echo ADMIN_URL.'/css/edit.css';?>'});</script>
       </td>
	</tr>
	<tr>
	   <td class="label">规格清单</td>
	   <td>
	       <textarea name="qingdan" id="content2" style="width:95%;height:300px;overflow: auto;"><?php echo isset($rt['qingdan']) ? $rt['qingdan'] : "";?></textarea>
		   <script>KE.show({id : 'content2',cssPath : '<?php echo ADMIN_URL.'/css/edit.css';?>'});</script>
       </td>
	</tr>
	<tr>
	<td class="label">&nbsp;</td>
	<td>
	  <input type="submit" value="保存" onclick="return checkval()"/>
	</td>
	</tr>
	 </table>
 </form>
</div>
<?php  $thisurl = ADMIN_URL.'groupbuy.php'; ?> 
<script type="text/javascript" language="javascript">
/*增删相册控件*/
function addobj(obj){
	rand = generateMixed(4);
	str = $(obj).parent().html();
	str = str.replace('addobj','removeobj');
	str = str.replace('[+]','[-]');
	$(obj).parent().after('<p>'+str+'</p>');
}

function removeobj(obj){
	$(obj).parent().remove();
	return false;
}
//产生随机数
function generateMixed(n) {
	var chars = ['0','1','2','3','4','5','6','7','8','9','a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z'];
    var res = "";
    for(var i = 0; i < n ; i ++) {
        var id = Math.ceil(Math.random()*35);
        res += chars[id];
    }
    return res;
}

function getgroupgoods(obj){
	cid = $(obj).parent().find('select[name="cat_id"]').val();
	bid = $(obj).parent().find('select[name="brand_id"]').val();
	key = $(obj).parent().find('input[name="keyword"]').val();
	if(cid>0 || bid>0 || key!=""){
		createwindow();
		$.get('<?php echo $thisurl;?>',{type:'getgroupgoods',cat_id:cid,brand_id:bid,keyword:key},function(data){
			if(data !=""){
				$(obj).parent().find('select[name="goods_id"]').html(data);
			}
			removewindow();
		});
	}else{
		return false;
	}
}

function checkval(){
	gname = $('input[name="group_name"]').val();
	if(gname==""){
		alert("请先输入团购名称！");
		return false;
	}
	
	gid = $('select[name="goods_id"]').val();
	if(!(gid>0)){
		alert("请先搜索产品！");
	 	return false
	}
	return true;
}

function delgroupgoods(obj,id){
	if(confirm("确认删除吗？")){
		$.get('<?php echo $thisurl;?>',{type:'delgroupgoods',id:id},function(data){  });
		$(obj).parent().hide(200);
	}
	return false;
}
</script>
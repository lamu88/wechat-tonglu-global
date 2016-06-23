<div class="contentbox">
  <form id="form1" name="form1" method="post" action=""> 
<table cellspacing="2" cellpadding="5" width="100%">
<tr>
<th>&nbsp;</th>
<th style="text-align:right"><a href="goods.php?type=goods_attr_list">商品属列表性</a></th>
</tr>
<tr>
        <td class="label" width="15%" >属性名称：</td>
        <td>
          <input name="attr_name" value="<?php echo isset($rt['attr_name']) ? $rt['attr_name'] : "";?>" size="30" type="text">
          <span class="require-field">*</span>        </td>
    </tr>
      <tr>
        <td class="label">属性是否可选</td>
        <td>
          <label><input name="attr_is_select" value="1" <?php echo !isset($rt['attr_is_select'])||$rt['attr_is_select']=='1' ?  'checked="true"' : "";?> type="radio"> 唯一属性</label>        
		  <label><input name="attr_is_select" value="2" <?php echo isset($rt['attr_is_select'])&&$rt['attr_is_select']=='2' ?  'checked="true"' : "";?> type="radio"> 单选属性</label>          
		  <label><input name="attr_is_select" value="3" <?php echo isset($rt['attr_is_select'])&&$rt['attr_is_select']=='3' ?  'checked="true"' : "";?> type="radio"> 复选属性</label>         
		   <br><span class="notice-span" style="display: block;" id="noticeAttrType">选择"单选/复选属性"时，可以对商品该属性设置多个值，同时还能对不同属性值指定不同的价格加价，<br />用户购买商品时需要选定具体的属性值。选择"唯一属性"时，商品的该属性值只能设置一个值，用户只能查看该值。</span>
        </td>
      </tr>
      <tr>
        <td class="label">该属性值的录入方式：</td>
        <td>
          <label><input name="input_type" value="1" <?php echo !isset($rt['input_type'])||$rt['input_type']=='1' ?  'checked="true"' : "";?>  onclick="radioClicked('1')" type="radio">手工录入</label>
          <label><input name="input_type" value="2" <?php echo isset($rt['input_type'])&&$rt['input_type']=='2' ?  'checked="true"' : "";?> onclick="radioClicked('2')" type="radio">从下面的列表中选择（一行代表一个可选值） </label>   
		  <label><input name="input_type" value="3" <?php echo isset($rt['input_type'])&&$rt['input_type']=='3' ?  'checked="true"' : "";?> onclick="radioClicked('3')" type="radio"> 多行文本框</label> 
		  </td>
      </tr>
      <tr>
        <td class="label">可选值列表：</td>
        <td>
          <textarea <?php echo !isset($rt['input_type'])||$rt['input_type']==1||$rt['input_type']==3 ? 'disabled="disabled"' : '';?> name="input_values" cols="30" rows="5" <?php echo !isset($rt['input_type'])||$rt['input_type']==1 || $rt['input_type']==3 ? 'style="background-color:#ededed; border:1px solid #ccc"' : '';?>><?php echo isset($rt['input_values']) ? $rt['input_values'] : "";?></textarea>
        </td>

      </tr>
	  <tr>
	  <td class="label">&nbsp;是否显示附加东西：</td>
	  <td>
	  <label>
	    <input type="checkbox" name="is_show_addi" value="1" <?php if(isset($rt['is_show_addi'])&&$rt['is_show_addi']==1){echo 'checked="checked"';}?>/>显示
	  </label>
	  <br />这里可以独立分配一个附件，例如不同属性，价格就不同
	  </td>
	  </tr>
	  <tr>
	  <td class="label">&nbsp;该属性是商品重要属性：</td>
	  <td>
	  <label>
	    <input type="checkbox" name="is_show_cart" value="1" <?php if(!isset($rt['is_show_cart'])||$rt['is_show_cart']==1){echo 'checked="checked"';}?>/>显示
	  </label>
	  <br />选中之后将作为购物车的属性【用户选择的商品属性】
	  </td>
	  </tr>
      <tr>
        <td colspan="2">
        <div class="button-div">
          <input value=" 确定 " class="button" type="submit" onclick="return checkvar()">
          <input value=" 重置 " class="button" type="reset">
        </div>
        </td>
      </tr>
</table>
  </form>
</div>
<script type="text/javascript">
function radioClicked(t){
	if(t==1 || t==3){
		$('textarea[name="input_values"]').attr('disabled',true);
		$('textarea[name="input_values"]').css('background-color','#EDEDED');
		$('textarea[name="input_values"]').css('border','1px solid #7F9DB9');
		$('textarea[name="input_values"]').val("");
	}else if(t==2){
		$('textarea[name="input_values"]').attr('disabled',false);
		$('textarea[name="input_values"]').css('background-color','#FFF');
		$('textarea[name="input_values"]').css('border','1px solid #7F9DB9');
		$('textarea[name="input_values"]').focus();
	}
}
function checkvar(){
	name = $('input[name="attr_name"]').val();
	if(typeof(name)=='undefined'||name==""){
		$('.require-field').html("<font color='red'>属性名称不能为空！</font>");
		return false;
	}
	return true;
}
</script>
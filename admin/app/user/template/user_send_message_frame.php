<style type="text/css">
.show_user_info{ margin:0px; margin-top:2px}
.show_user_info a{ padding:3px; margin-right:3px; border-bottom:2px solid #ccc; background-color:#ededed}
</style>
 <table cellspacing="2" cellpadding="5" width="100%">
 <tr>
	 <td style="background-color:#EEF2F5; border-right:1px solid #B4C9C6; border-bottom:1px solid #B4C9C6" width="150" align="right">标题：</td>
	 <td colspan="3">
	   <input type="text" name="title" id="title" size="60"/>
	   <p class="show_user_info"></p>
	 </td>
 </tr>
 <tr>
	<td style="background-color:#EEF2F5; border-right:1px solid #B4C9C6; border-bottom:1px solid #B4C9C6" align="right">消息内容:</td>
	<td colspan="3"><textarea name="content" id="content" style="width:98%;height:500px;display:none;"></textarea>
	<script>KE.show({id : 'content',cssPath : '<?php echo ADMIN_URL.'/css/edit.css';?>'});</script>
	</td>
</tr>
<tr>
<td style="background-color:#EEF2F5; border-right:1px solid #B4C9C6; border-bottom:1px solid #B4C9C6">&nbsp;</td>
<td>
  <p class="sendresult"></p>
  <input type="button" name="button" id="bathsend" value="确认给以上选中会员发送消息" onclick="send(0)" style="cursor:pointer"/>
</td>
</tr>
</table>
<?php  $thisurl = ADMIN_URL.'user.php'; ?>
<script language="javascript" type="text/javascript">
var p = window.parent;
var b = p.document.body;
var dbarr = [];
var removearr = [];
var username = [];
function setuserid(arr_id,arr_name){
	var str="[<font color=red>点击移除=></font>]&nbsp;";
	if(arr_id.length>0){
		for(i=0;i<arr_id.length;i++){
				//检查是否已经存在
				var tt = false;
				if(dbarr.length>0){
					for(ii=0;ii<dbarr.length;ii++){
						if(dbarr[ii]==arr_id[i]){ tt = true; break;}
					}
				}
				if( tt == true){ continue; }
				dbarr.push(arr_id[i]);
				username.push(arr_name[i]);
				str = str+'<span><a href="javascript:void(0)" onclick="removeobj(\''+i+'\',this)">'+arr_name[i]+'</a>&nbsp;&nbsp;&nbsp;</span>';
		}
		var html = $('.show_user_info').html();
		$('.show_user_info').html(html+str);
	}
}

var k=0;
var titles = "";
var content = "";
function send(kk){
	if(dbarr.length>0){
		k= kk;
		if(titles =="") titles = $('input[name="title"]').val();
		//获取编辑器文本中的值
		if(content =="")  content = KE.util.getData('content');
		if(typeof(dbarr[k])!="undefined" && dbarr[k] > 0){
			 document.getElementById("bathsend").disabled = true;
			 $.post('<?php echo $thisurl;?>',{action:'sendmessage',kk:k,uid:dbarr[k],title:titles,con:content},function(data){
			 	$('.sendresult').html('<img src="<?php echo $this->img('loadings.gif');?>" align="absmiddle"/>&nbsp;&nbsp;正在发送-会员：'+username[k]+"。。。");
				if(parseInt(data) > 0){
				 	 setTimeout("send("+parseInt(data)+")",1000); //睡眠一秒
				}else{
					$('.sendresult').html("");
					document.getElementById("bathsend").disabled = false;
					alert(data);
				}
			 });
		}else{
			document.getElementById("bathsend").disabled = false;
			$('.show_user_info').html("队列会员已经发送！");
			$('.sendresult').html("发送完毕！");
		}
	}
	return true;
}

function removeobj(i,obj){
	$(obj).parent().remove();
	if(typeof(dbarr[i])!="undefined") dbarr[i] = null;
}
p.setuserid = setuserid;

</script>
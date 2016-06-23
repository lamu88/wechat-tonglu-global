//表单验证
//校验普通电话、传真号码：可以“+”开头，除数字外，可含有“-”
function isTel(s)
{
	// var pattern =/^\d{3,4}\-\d{7}$/;
	 var pattern =/^(([0\+]\d{2,3}-)?(0\d{2,3})-)(\d{7,8})(-(\d{3,}))?$/;
	 if(pattern.test(s))
	 {
	  return true;
	 }
	 return false;
}
	
//校验手机号码：必须以数字开头，除数字外，可含有“-”
function isMobile(s)
	{
	 var patrn=/^(13[0-9]{9})|(15[0-9]{9})|(18[0-9]{9})$/;
	 //var patrn=/^(13[0-9]{9})|(15[89][0-9]{8})|(18[0-9]{9})$/;
	 if (!patrn.test(s)){
	   return false;
	  }
	  return true;
}

 //校验邮箱
function isEmail(s)
{
  reg=/^([a-zA-Z0-9]+[_|\-|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\-|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/gi;
  if(!reg.test(s))
  {
      return false;
  }
  return true;
}
function ismod(v){  
	var a = /^((\(\d{3}\))|(\d{3}\-))?13\d{9}|15[89]\d{8}$/ ;  
	if( v.length!=11||!v.match(a) ){  
		return false;
	}else{  
		return true;
	}  
}

function isqq(v){
	var reg = /^[1-9]\d{4,13}$/;
	if( reg.test(v) ){  
		return true;
	}else{  
		return false;
	}  

} 

//检查是否全是中文
function ischn(str){ 
	var reg = /^[\u4E00-\u9FA5]+$/; 
	if(!reg.test(str)){ 
		return false; 
	} else{
		return true; 
	}
} 
//必须含有中文
function isstrchn(v){
	var rname=/[\u4E00-\u9FA5]/; 
	if(!rname.test(v)){  //必须含汉字
		//alert("必须含汉字!"); 
		return true; 
	 }else{
		return false;
	 }
 }

//是否全是英文字符
function isstr(v) 
{ 
	var str = /[_a-zA-Z]/; 
	if(str.test(v)) 
	{ 
		return true;
	}else{
		return false;
	}
} 
//创建一个等待窗口
function createwindow(){
	obj = $('.openwindow');
	if(typeof(obj)!='undefined') $(obj).remove();
	h = getScrollTop();
	var str = '<div class="openwindow"><img src="'+SITE_URL+'images/loadings.gif"  align="absmiddle"/></div>';
	$("body").append(str);
	$('.openwindow').css('position','absolute');
	$('.openwindow').css('left',((screen.availWidth-240)/2)+'px');
	$('.openwindow').css('top',((screen.availHeight-40)/2)+'px');
	$('.openwindow').css('margin-top',(h-80)+'px');
	$('.openwindow').show("slow");
}

//移除一个窗口
function removewindow(){
	$('.openwindow').remove();
}

//创建一个带有关闭的窗口
function meswindow(mes,title,ww,hh){
	if(ww==null || ww=="" || typeof(ww)=="undefined") ww = 300;
	if(hh==null || hh=="" || typeof(hh)=="undefined") hh = 100;
	if(title==null || title=="" || typeof(title)=="undefined") title = "头部";
	obj = $('.meswindow');
	if(typeof(obj)!='undefined') $(obj).remove();
	if(mes==""||typeof(mes)=='undefined') mes = '操作成功!';
	h = getScrollTop();   
	var str = '<div class="meswindow"><p class="p_hear"><span>'+title+'</span><a onclick="closewindow(this)" href="javascript:;">关闭</a></p>'+mes+'</div>';
	$("body").append(str);
	//$('.window_box').css('height',document.body.scrollHeight);
	//$('.window_box').css('width',document.body.scrollWidth);
	
	$('.meswindow').css('position','absolute');
	$('.meswindow').css('height',hh);
	$('.meswindow').css('width',ww);
	
	$('.meswindow').css('left',((screen.availWidth-ww)/2)+'px');
	$('.meswindow').css('top',((screen.availHeight-h)/2)+'px');
	$('.meswindow').css('margin-top',(h)+'px');
	$('.meswindow').show();
	return true;
}

/********************
 * 取窗口滚动条滚动高度  
 ******************/
function getScrollTop()
{
  var scrollTop=0;
  if(document.documentElement&&document.documentElement.scrollTop)
  {
  scrollTop=document.documentElement.scrollTop;
  }
  else if(document.body)
  {
  scrollTop=document.body.scrollTop;
  }
  return scrollTop;
}




//关闭窗口
function closewindow(obj){
	$(obj).parent().parent().hide("slow");
	$(obj).parent().parent().remove();
}

function return_message_string(gid){
	if(!(gid>0)) return false;
	gname = $('input[name="goodsname"]').val();
	if(gname==""||typeof(gname)=='undefined') return false;
	data = gname.replace("'",'"');
	return '<form action="" method="get" style="float:left;" id="MESSAGES" name="MESSAGES"><div class="message_con"><p class="message_mes">请填写发表内容</p><hr /><p class="message_title">您正在对<font color="#FF6600">'+data+'</font>进行留言咨询</p><p class="message_rank"><textarea name="comment_title" cols="45" style="height:140px;"></textarea></p><p class="message_box"><input type="button" class="butmes" value="提问"  onclick="submit_message(\''+gid+'\')"/>&nbsp;&nbsp;<input type="button" class="butmes" value="取消" onClick="JqueryDialog.Close();"/></p></div></form>';	
}

function return_comment_string(gid){
	return '<form action="" method="get" style="float:left;" id="ECS_COMMENT" name="ECS_COMMENT"><div class="comment_con"><p class="comment_mes">请填写发表内容<hr /></p><p class="comment_con_p"><table width="100%" border="0" cellspacing="5" cellpadding="1"><tr><td width="25%">评价等级</td><td width="20%" align="left">&nbsp;</td><td align="left">&nbsp;</td><td align="left">综合满意度</td></tr><tr><td width="55"><label><input type="radio" name="comment_rank" value="3" checked="checked"/>&nbsp;&nbsp;好评</label></td><td align="left">&nbsp;</td><td align="left"><b style="font-size:10px;">■</b>&nbsp;产品质量:&nbsp;</td><td align="left" class="pro_rank">&nbsp;<img src="'+SITE_URL+'theme/images/02.jpg" onMouseOver="changeimg(this)" id="1"/>&nbsp;<img src="'+SITE_URL+'theme/images/02.jpg" id="2" onMouseOver="changeimg(this)"/>&nbsp;<img src="'+SITE_URL+'theme/images/02.jpg" id="3" onMouseOver="changeimg(this)"/>&nbsp;<img src="'+SITE_URL+'theme/images/02.jpg" id="4" onMouseOver="changeimg(this)"/>&nbsp;<img src="'+SITE_URL+'theme/images/02.jpg" id="5" onMouseOver="changeimg(this)"/><input type="hidden" name="goods_rand" /></td></tr><tr><td width="55"><label><input type="radio" name="comment_rank" value="2" />&nbsp;&nbsp;中评</label></td><td align="left">&nbsp;</td><td align="left"><b style="font-size:10px;">■</b>&nbsp;物流配送:&nbsp;&nbsp;</td><td align="left" class="sp_rank">&nbsp;<img src="'+SITE_URL+'theme/images/02.jpg" id="1" onMouseOver="changeimg(this)"/>&nbsp;<img src="'+SITE_URL+'theme/images/02.jpg" id="2" onMouseOver="changeimg(this)"/>&nbsp;<img src="'+SITE_URL+'theme/images/02.jpg" id="3" onMouseOver="changeimg(this)"/>&nbsp;<img src="'+SITE_URL+'theme/images/02.jpg" id="4" onMouseOver="changeimg(this)"/>&nbsp;<img src="'+SITE_URL+'theme/images/02.jpg" id="5" onMouseOver="changeimg(this)"/><input type="hidden" name="shopping_rand" /></td></tr><tr><td width="55"><label><input type="radio" name="comment_rank" value="1" />&nbsp;&nbsp;差评</label></td><td align="left">&nbsp;</td><td align="left"><b style="font-size:10px;">■</b>&nbsp;售后服务:&nbsp;</td><td align="left" class="sale_rank">&nbsp;<img src="'+SITE_URL+'theme/images/02.jpg" id="1" onMouseOver="changeimg(this)"/>&nbsp;<img src="'+SITE_URL+'theme/images/02.jpg" id="2" onMouseOver="changeimg(this)"/>&nbsp;<img src="'+SITE_URL+'theme/images/02.jpg" id="3" onMouseOver="changeimg(this)"/>&nbsp;<img src="'+SITE_URL+'theme/images/02.jpg" id="4" onMouseOver="changeimg(this)"/>&nbsp;<img src="'+SITE_URL+'theme/images/02.jpg" id="5" onMouseOver="changeimg(this)"/><input type="hidden" name="saleafter_rand" /></td></tr></table></p><p class="comment_rank"><table width="100%" border="0" cellspacing="5" cellpadding="1"><tr><td colspan="2"><b>评论内容</b></td></tr><tr><td colspan="2"><textarea name="comment" cols="45"  style="height:85px;"></textarea></td></tr></table></p><p class="comment_box"><input type="button" class="butmes" value="提交"  onclick="submit_comment(\''+gid+'\')"/>&nbsp;&nbsp;<input type="button" class="butmes" value="取消" onClick="JqueryDialog.Close();"/></p></div></form>';
}

//返回一个登录的html代码
function return_login_string(type,gid){
	if(typeof(type)=='undefined') type="";
	if(typeof(gid)=='undefined') gid=0;
	return '<div class="login_con"><p class="login_mes"></p><p class="login_p"><span class="span1">用户名：</span><input type="text" class="user_name" style="width:150px;"/></p><p class="login_p"><span class="span2">&nbsp;&nbsp;密&nbsp;&nbsp;码：</span><input type="password" class="pass" style="width:150px;"/></p><p class="login_boxs"><input type="button" class="loginbut" value="登录"  onclick="ajax_user_login(this,\''+type+'\',\''+gid+'\')"/>&nbsp;&nbsp;<input type="button" class="loginbut" value="取消" onclick="closewindow($(this).parent());"/></p><p style="margin-top:10px; font-size:12px;"><a href="'+SITE_URL+'user.php?act=register" style=" background:url('+SITE_URL+'theme/images/dian.jpg) left center no-repeat">&nbsp;&nbsp;注册新会员</a>&nbsp;&nbsp;<a href="'+SITE_URL+'user.php?act=forgetpass" style="background:url('+SITE_URL+'theme/images/dian.jpg) left center no-repeat">&nbsp;&nbsp;忘记密码？</a></p></div>';
}

//用户简单登录 ajax登录
function ajax_user_login(obj,type,gid){
	objpa = $(obj).parent().parent();
	username = objpa.find('.user_name').val();
	password = objpa.find('.pass').val();
	if(typeof(username)!='defined'&&username!=""&&typeof(password)!='defined'&&password!=""){
		$.post(SITE_URL+'user.php',{action:'login',username:username,password:password},function(data){ 
				if(data != ""){
					objpa.find('.login_mes').html('<font color=red>'+data+'</font><br />');
				}else{
					if(type=='comment'){
						JqueryDialog.Open('评论系统',return_comment_string(gid),450,300);
						return false;
					}else if(type=='coll'){ //收藏店铺
						addToShopColl(gid);
						return false;
					}else if(type=='message'){ //商品提问
						JqueryDialog.Open('商品提问系统',return_message_string(gid),405,270);
						return false;
					}else if(type=='jifen'){ //兑换积分商品
						addToCart(gid,'jifen'); //登录后加入购物车
					}else{
						location.reload();
					}
				}
		});
	}else{
		objpa.find('.login_mes').html('<font color=red>请您输入完整信息!</font><br />');
	}
}

/*
*ajax返回页面htmlAPI
*filename:element/box/文件名称
*/
function ajax_return_box_html(filename){
	var filename = "ajax_pop_login";
	var ss = "";
	$.post(SITE_URL+'ajaxfile/popbox.php',{action:'box',boxname:filename,gid:'111',type:"login"},function(data){
			ss = data;									 
	});
	
	meswindow(ss);
	//alert(ss);
}
/**
 * 获得表单单元元素
 */
function getFormAttrs(formObj)
{
  var sp_arr = new Object();

  for (i = 0; i < formObj.elements.length; i ++ )
  {
    if(((formObj.elements[i].type == 'radio' || formObj.elements[i].type == 'checkbox') && formObj.elements[i].checked) || formObj.elements[i].tagName == 'SELECT' ||  formObj.elements[i].type == 'hidden' || formObj.elements[i].type=='text' || formObj.elements[i].type=='textarea')
    {
      sp_arr[formObj.elements[i].name] = formObj.elements[i].value;
    }
	  
  }
  return sp_arr;
}

function AddFavorite(sURL, sTitle)
{
    try
    {
        window.external.addFavorite(sURL, sTitle);
    }
    catch (e)
    {
        try
        {
            window.sidebar.addPanel(sTitle, sURL, "");
        }
        catch (e)
        {
            alert("加入收藏失败，请使用Ctrl+D进行添加");
        }
    }
}
function SetHome(obj,vrl){
        try{
                obj.style.behavior='url(#default#homepage)';obj.setHomePage(vrl);
        }
        catch(e){
                if(window.netscape){
                        try{
                                netscape.security.PrivilegeManager.enablePrivilege("UniversalXPConnect");  
                        }  
                        catch (e){ 
                                alert("此操作被浏览器拒绝！请在浏览器地址栏输入“about:config”并回车然后将[signed.applets.codebase_principal_support]设置为'true'");  
                        }
                        var prefs = Components.classes['@mozilla.org/preferences-service;1'].getService(Components.interfaces.nsIPrefBranch);
                        prefs.setCharPref('browser.startup.homepage',vrl);
                 }
        }
}


/*限制图片大小*/
/*
obj:当前对象
size:宽度 高度
*/
function image_size_load(obj,size){
	if($(obj).width()>$(obj).height()){$(obj).width(size);}else{$(obj).height(size);}	
	return true;
}

function clearTip(obj) {

  if(obj.value=="输入搜索关键字" || obj.value=="联系人姓名不能为空" || obj.value=="联系电话不能为空" || obj.value=="请填写就餐时间" || obj.value=="根据实际情况可以填写您想说的") {

    obj.value="";

  }

  obj.style.color = "";

}

function backTip(obj, val) {

  if(obj.value=="") {

    obj.value=val;

    obj.style.color = "#666";

  }

}

//打开高级搜索窗口
function ajax_show_window(){
	JqueryDialog.Open('高级搜索',SITE_URL+'searchad.php',600,500,'frame');
}

function ajaxopquyu(){
	$("#opquyu").toggle();
	$("#opquyubox").toggle();
}

jQuery(document).ready(function($){
	$('#opquyu').click(function(){
		$(this).hide();
		$("#opquyubox").hide();
	});

});
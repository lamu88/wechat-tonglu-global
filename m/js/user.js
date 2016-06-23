////////////用户登录js////////////////

function checkloginvar(){
		user = $('input[name="username"]').val();

		pass = $('input[name="password"]').val();

		vcode = $('input[name="vifcode"]').val();
		clearloginmes();
		if(user=="" || typeof(user)=='undefined'){
			$('.uname_mes').html("<font color='red'>用户名不能为空！</font>");
			return false;
		}
		clearloginmes();
		if(pass=="" || typeof(pass)=='undefined'){	
			$('.pass_mes').html("<font color='red'>密码不能为空！</font>");
			return false;
		}
		clearloginmes();
		if(vcode=="" || typeof(vcode)=='undefined'){		
			$('.vcode_mes').html("<font color='red'>验证码不能为空！</font>");
			return false;
		}
		return true;
}

function clearloginmes(){
	arr = ['uname_mes','pass_mes','vcode_mes'];
	for(i=0;i<arr.length;i++){
		$('.'+arr[i]).html("*");
	}
}


function submit_login_data(){
		//if(checkloginvar()==false) return false;
		
		names = $('input[name="username"]').val();

		pas = $('input[name="password"]').val();

/*		jumpurl = $('input[name="returnurl"]').val(); //跳转到购物车标记
		
		issave = $('.content input[name="is_save_info"]:checked').val();
		
		isauto = $('.content input[name="is_auto_login"]:checked').val();*/
		
		//vcode = $('.content input[name="vifcode"]').val();
			
/*		if(typeof(issave)=='undefined' || issave=="" || issave==null) issave = 0; //保存用户登录
		if(typeof(isauto)=='undefined' || isauto=="" || isauto==null) isauto = 0;*/
		if(names == "" || pas == "" ){ alert("请输入完整信息！"); return false; }
		createwindow();
		$.post(SITE_URL+'user.php',{action:'ajax_user_login',username:names,password:pas},function(data){
			removewindow();
			if(data != ""){
				alert(data);
			}else{
				location.href=SITE_URL+'user.php'; 
			}
		});
}

///////////用户注册/////////////
function checkregistervar(){
		user = $('.content input[name="username"]').val();

		pass = $('.content input[name="password"]').val();
		
		rp_pass = $('.content input[class="rp_pass"]').val();

		//vcode = $('input[name="vifcode"]').val();
		
		mail = $('.content input[name="email"]').val();
		
		//hphone = $('input[name="home_phone"]').val();
		
		//mphone = $('input[name="mobile_phone"]').val();
		vcode = $('.content input[name="vifcode"]').val();
		
		if(user=="" || typeof(user)=='undefined'){
			$('.alert_mes').html("<font color='red'>用户名不能为空！</font>");
			return false;
		}
		clearmes();
		if(user.length<6){
			$('.alert_mes').html("<font color='red'>用户名不能过于简单！</font>");
			return false;
		}
		clearmes();
		if(pass=="" || typeof(pass)=='undefined'){			
			$('.alert_mes').html("<font color='red'>密码不能为空！</font>");
			return false;
		}
		clearmes();
		if(pass.length<6||pass.length>16){
			$('.alert_mes').html("<font color='red'>密码长度必须6-16！</font>");
			return false;
		}
		clearmes();
		if(pass != rp_pass){	
			$('.alert_mes').html("<font color='red'>密码与确认密码不一致！</font>");
			return false;
		}
		/*clearmes();
		if(mail=="" || typeof(mail)=='undefined'){	
			$('.email_mes').html("<font color='red'>电子邮件不能为空！</font>");
			return false;
		}*/
		if(mail!=""&&typeof(mail)!='undefined'){
			clearmes();
			if(!isEmail(mail)){
				$('.alert_mes').html("<font color='red'>你输入的电子邮件不合法！</font>");
				return false;
			}
		}
		/*if(hphone !=""){
			if(!isTel(hphone)){
				$('.home_phone_mes').html("<font color='red'>你输入的电话号码不合法！</font>");
				return false;
			}
		}
		if(mphone !=""){
			if(!isMobile(mphone)){
				$('.mobile_phone_mes').html("<font color='red'>你输入的手机号码不合法！</font>");
				return false;
			}
		}
		clearmes();
		if(vcode=="" || typeof(vcode)=='undefined'){
			$('.alert_mes').html("<font color='red'>验证码不能为空！</font>");
			return false;
		}*/
		return true;
}

function clearmes(){
	arr = ['uname_mes','pass_mes','rp_pass_mes','email_mes','vcode_mes'];
	for(i=0;i<arr.length;i++){
		if(arr[i]=='uname_mes'){
		$('.'+arr[i]).html("请填写4-18位字符，字母，下划线");
		}else if(arr[i]=='pass_mes'){
		$('.'+arr[i]).html("密码请设为6-16字符");
		}else if(arr[i]=='rp_pass_mes'){
		$('.'+arr[i]).html("请再次输入你的密码");
		}else if(arr[i]=='email_mes'){
		$('.'+arr[i]).html("");
		}else if(arr[i]=='vcode_mes'){
		$('.'+arr[i]).html("");
		}
	}
}
function cleartext(){
	arr = ['username','password','rp_pass','email','vifcode'];
	for(i=0;i<arr.length;i++){
		if(arr[i]=='content'){
			$('textarea[name="'+arr[i]+'"]').val("");
		}else{
			$('input[name="'+arr[i]+'"]').val("");
		}
	}
}

//提交注册数据
function submit_register_data(na){
	   if(na==null || na=="" || typeof(na)=='undefined'){ alert("确认表单是否存在！"); return false;}
	   var fromAttr        = new Object();  //一个商品的所有属性
	   var form      = document.forms[na]; //表单
	   // 检查注册表单的属性
	   if (form)
	   {
	   		fromAttr = getFromAttributes(form);
	   }
	   else{
			alert("检查是否存在表单REGISTER");
			return false;
	   }
		//$('.register_mes').html('正在注册，请耐心等待。。。');
		createwindow();
		
		$.ajax({
		   type: "POST",
		   url: SITE_URL+"user.php?action=register",
		   data: "fromAttr=" + $.toJSON(fromAttr),
		   dataType: "json",
		   success: function(data){ 
		   		removewindow();
				if(data.error==0){
					alert(data.message);
					window.location.href=SITE_URL+'user.php?act=register'; //注册成功
					return false;
				}else{
					alert(data.message);
				}
		   },
		   error: function(error){
			   removewindow();
			   alert("意外错误");
			}
		});
		return false;
}

function jsjump(url){
	window.location.href = url;
}

//修改用户信息
function update_user_info(t){
	   var fromAttr        = new Object();  //
	   var form      = document.forms['USERINFO']; //
	   if(form){
			fromAttr = getFromAttributes(form);
	   }else{
			alert("检查是否存在表单REGISTER");
			return false;
	   }
	   urls = t==1 ? SITE_URL+"user.php?action=updateinfo" : SITE_URL+"daili.php?action=ajax_updateinfo";
	  
	   createwindow(); //alert($.toJSON(fromAttr)); return false;
	   $.ajax({
		   type: "POST",
		   url: urls,
		   data: "fromAttr=" + $.toJSON(fromAttr),
		   dataType: "json",
		   success: function(data){
			   removewindow();
		   		if(data.error==3){
					location.href=SITE_URL+'user.php?act=login';
				}else if(data.error==0){
					location.href=SITE_URL;
				}else if(data.error==5){
					location.href=SITE_URL+'user.php';	
				}else{
					//JqueryDialog.Open('系统提醒你',data.message,250,50);
					$('table .returnmes').html(data.message);
				}
		   }
		});
}

//修改用户密码
function update_user_pass(){
	   var fromAttr        = new Object();  //
	   var form      = document.forms['USERINFO']; //
	   if(form){
			fromAttr = getFromAttributes(form);
	   }else{
			alert("检查是否存在表单REGISTER");
			return false;
	   }
	   createwindow();
	   $.ajax({
		   type: "POST",
		   url: "user.php?action=updatepass",
		   data: "fromAttr=" + $.toJSON(fromAttr),
		   dataType: "json",
		   success: function(data){
			   removewindow();
		   		if(data.error==3){
					location.href='user.php?act=login';
				}else if(data.error==0){
					JqueryDialog.Open('系统提醒你','<br />密码修改成功！',250,50);
				}else{
					JqueryDialog.Open('系统提醒你','<br />'+data.message,260,50);
				}
		   }
		});
}

//订单查询
function get_order_page_list(pag,statu){
	createwindow();
	if(pag=="" || typeof(pag)==null ) pag = 1;
	//times = $('select[name="dt"]').val();
	//statu = $('select[name="status"]').val();
	//kkk = $('input[name="kk"]').val();
	
	if(typeof(statu)=='undefined'){ alert("请确认Html的元素是否存在！"); return false;}
	$.post(SITE_URL+'user.php',{action:'getorderlist',time:0,status:statu,keyword:"",page:pag},function(data){
		removewindow();
		if(data !="" && typeof(data) != 'undefined'){
			$('.AJAXORDERLIST').html(data);
		}
	});
}

//供应商订单查询
function get_suppliers_order_page_list(pag,statu,times,kkk){
	createwindow();
	if(pag=="" || typeof(pag)==null ) pag = 1;
	//times = $('select[name="dt"]').val();
	statu = (statu==null || typeof(statu)=='undefined' || statu=="") ? "" : $('select[name="status"]').val();
	//kkk = $('input[name="kk"]').val();
	if(typeof(statu)=='undefined'){ alert("请确认Html的元素是否存在！"); return false;}
	$.post(SITE_URL+'ajaxfile/suppliers.php',{action:'getorderlist',time:times,status:statu,keyword:kkk,page:pag},function(data){
		removewindow();
		if(data !="" && typeof(data) != 'undefined'){
			$('.AJAXORDERLIST').html(data);
		}
	});
}

/**
 * 获得选定的商品属性
 */
function getFromAttributes(formAttr)
{
  var obj = new Object();
  var j = 0;

  for (i = 0; i < formAttr.elements.length; i ++ )
  { 
    if(((formAttr.elements[i].type == 'radio' || formAttr.elements[i].type == 'checkbox') && formAttr.elements[i].checked) || formAttr.elements[i].tagName == 'SELECT' || formAttr.elements[i].type=='text' || formAttr.elements[i].type=='textarea' ||  formAttr.elements[i].type == 'hidden' || formAttr.elements[i].type == 'password')
    { 
	  obj[formAttr.elements[i].name] = formAttr.elements[i].value;
      j++ ;
    }
  }
return obj;
}

//用户收货地址
function ressinfoop(id,type,obj){ 
	var tt = false;
	var dd = false;
	if(type=='delete'){
		if(confirm("确定删除吗？")){
			tt = true;
		}else{
			return false;	
		}
	}else if(type=='setdefaut'){
		if(confirm("确定设为默认收货地址吗？")){
			tt = true;
		}else{
			return false;	
		}
	}else if(type=='quxiao'){
		if(confirm("确定取消默认收货地址吗？")){
			tt = true;
		}else{
			return false;	
		}
	}else if(type=='showupdate'){
			tt = true;
	}else if(type=='update'){
		dd = true;
	}else if(type=='add'){
		dd = true;	
	}
	
	if(tt==true){
		createwindow();
		$.post(SITE_URL+'user.php',{action:'ressinfoop',id:id,type:type},function(data){
			if(type=='showupdate'){														  
				if(data !="" && typeof(data) != 'undefined'){
					//$(obj).parent().parent().parent().parent().after(data);
					JqueryDialog.Open('系统提醒你',data,280,220);
				}
			}else if(type=='delete'){
				$(obj).parent().parent().parent().remove();
			}else if(type=='setdefaut'){
				$('.myaddress .set_quxiao_icon').each(function(i){
					sr = $(this).attr('src');	
					$(this).attr('src',sr.replace('quxiaodefaultress','setdefaultress'));
					
					on = $(this).attr('onclick');
					$(this).attr('onclick',on.replace('quxiao','setdefaut'));
				});
				$(obj).attr('src',SITE_URL+'images/quxiaodefaultress.png');
				ons = $(obj).attr('onclick');
				$(obj).attr('onclick',ons.replace('setdefaut','quxiao'));
				//alert($(obj).attr('onclick'));
				window.location.reload();
			}else if(type=='quxiao'){
				$(obj).attr('src',SITE_URL+'images/setdefaultress.png');
				ons = $(obj).attr('onclick');
				$(obj).attr('onclick',ons.replace('quxiao','setdefaut'));
				window.location.reload();
			}
			removewindow();
		});
	}

	if(dd==true){
		  var attrbul        = new Object();  //一个商品的所有属性
		  var formBuy      = document.forms[obj]; //表单
		
		  if (formBuy)
		  {
			attrbul = getFormAttrs(formBuy);
		  }
		  attrbul.id = id;
		  attrbul.type = type;
		  createwindow();
		  $.ajax({
			   type: "POST",
			   url: SITE_URL+'user.php?action=ressinfoop',
			   data: "attrbul=" + $.toJSON(attrbul),
			   dataType: "json",
			   success: function(data){
					removewindow();
					if(data.error==0){
						window.location.reload();
					}else{
						JqueryDialog.Open('系统提醒你','<br /><img src="'+SITE_URL+'images/error_icon.png" align="absmiddle"/>&nbsp;&nbsp;&nbsp;'+data.message,260,60);
					}
					
			   }//end sucdess
			});
	}
}

//收藏商品
function get_usercolle_page_list(page){
	createwindow();
	$.get(SITE_URL+'user.php',{act:'mycoll',page:page,type:'ajax'},function(data){
				removewindow();
				if(typeof(data)!='undefined' && data !=""){
					$('.m_right .MYCOLLE').html(data);	
				}
	})
}

//我的提问[对商品的提问]
function get_myquestion_page_list(page){
	createwindow();
	$.get(SITE_URL+'user.php',{act:'question',page:page,type:'ajax',tt:'goodsnotnull'},function(data){
				removewindow();
				if(typeof(data)!='undefined' && data !=""){
					$('.m_right #tab1').html(data);	
				}
	})
}

//我的提问【不是对商品的提问】
function get_myquestion_notgoods_page_list(page){
	createwindow();
	$.get(SITE_URL+'user.php',{act:'question',page:page,type:'ajax',tt:'goodsnull'},function(data){
				removewindow();
				if(typeof(data)!='undefined' && data !=""){
					$('.m_right #tab2').html(data);	
				}
	})
}

function delmessage(mesid,obj){
	if(confirm("确定删除吗？")){
		createwindow();
		$.post(SITE_URL+'user.php',{action:'delmes',mes_id:mesid},function(data){
				removewindow();
				if(data !=""){
					alert(data);
				}else{
					location.reload();
					//$(obj).parent('.huifu').remove();
				}
					
		})
	}
}

function get_mycomment_page_list(page){
	createwindow();
	$.get(SITE_URL+'user.php',{act:'mycomment',page:page,type:'ajax'},function(data){
				removewindow();
				if(typeof(data)!='undefined' && data !=""){
					$('.m_right #COMMENTLIST').html(data);	
				}
	})
}

function delcomment(id,obj){
	if(confirm("确定删除吗？")){
		createwindow();
		$.post(SITE_URL+'user.php',{action:'delcomment',id:id},function(data){
				removewindow();
				if(data !=""){
					alert(data);
				}else{
					location.reload();
					//$(obj).parent('.huifu').remove();
				}
					
		})
	}
}
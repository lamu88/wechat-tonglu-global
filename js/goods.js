function ajax_cart_checkout(uid){
	if(uid>0){
		window.location.href=SITE_URL+'mycart.php';
	}else{
		meswindow(return_login_string('checkout',0),'宝泰网登录系统',300,150);	
	}
	return false;
}

//购物车js
/* *
 * 添加商品到购物车
 * tt:标记的页面类型
 * obj:
 */
function addToCart(goodsid,tt)
{ 
 
 if(tt=="jifen_cartlist" || tt=="jifen"){
	if(confirm("你确定兑换吗？兑换后你的积分将会相应减少！")){}else{ return false;}
 }
 
//判断js函数是否存在
 try{  
  if(typeof(eval('checkcartattr'))=="function"){ 
	  if(checkcartattr()==false){
		  return false;
	  }
  }
 }catch(e){
	   //alert("not function"); 
 }

  var goods        = new Object();  //一个商品的所有属性
  var spec_arr     = new Array(); //获取过来的商品属性
  var number       = 1;  //购买数据
  var formBuy      = document.forms['ECS_FORMBUY']; //表单
  var prices = 0;
   
   
  // 检查是否有商品规格 
  if (formBuy)
  {
    spec_arr = getSelectedAttributes(formBuy);

    if (formBuy.elements['number'])
    {
      number = formBuy.elements['number'].value;
    }
	
	if (formBuy.elements['price'])
    {
      prices = formBuy.elements['price'].value;
    }
  }

  goods.spec     = spec_arr;
  goods.goods_id = goodsid;
  goods.number   = number;
  goods.price   = prices;
  goods.optype = (typeof(tt)=='undefined' || tt=="") ? "" : tt;
  createwindow();
  $.ajax({
	   type: "POST",
	   url: SITE_URL+"ajaxfile/goods.php?action=addcart",
	   data: "goods=" + $.toJSON(goods),
	   dataType: "json",
	   success: function(data){
		   	removewindow();
			 if(tt=='cartlist'){ //购物车列表页面
				if(data.error==4){JqueryDialog.Open('宝泰网提醒你','<br />'+data.message,300,50); return false; }
				else if(data.error==5){ //Product attributes exist
					  JqueryDialog.Open('宝泰网提醒你',data.message,300,200);
				}
				 
				createwindow();
				$.post(SITE_URL+"ajaxfile/mycart.php",{action:'delcartid',id:0},function(data){
						$('.MYCART').hide();
						if(data !=""){
						$('.MYCART').html(data);
						$('.MYCART').fadeIn("slow");
						}
						removewindow();
				});
			 }else if(tt=='jifen'){ //Redeem Points goods
				 if(data.error==3){ //Need login
					 JqueryDialog.Open('宝泰网提醒你登陆系统',return_login_string('jifen',goodsid),300,50);
				 }else if(data.error==5){ //存在商品属性
					  JqueryDialog.Open('宝泰网提醒你',data.message,300,200);
				 }else{
				 str = '<br />'+data.message+'<br /><p style="width:175px; position:relative"><a href="'+SITE_URL+'mycart.php?type=cartlist" onclick="window.location.href=\''+SITE_URL+'mycart.php?type=cartlist\'" style="display:block; height:25px; line-height:25px; width:80px;background-color:#B40C0C; position:absolute; left:0px; bottom:-30px;">查看购物车</a>&nbsp;<a href="javascript:;" onclick="JqueryDialog.Close();" style="display:block; height:25px; line-height:25px; width:80px;background-color:#B40C0C; position:absolute; right:0px; bottom:-30px;">继续购物</a></p>';
				 JqueryDialog.Open('宝泰网提醒你',str,300,50);
				 }
			 }else if(tt=='jifen_cartlist'){ //Redeem Points goods
				 if(data.error==3){ //Need login
					 JqueryDialog.Open('宝泰网登陆系统',return_login_string('jifen',goodsid),300,50);
				 }else if(data.error==5){ //Product attributes exist
					  JqueryDialog.Open('宝泰网提醒你',data.message,300,200);
				 }else{
					if(data.error==2){
						str = '<br />'+data.message+'<br /><p style="width:175px; position:relative"><a href="'+SITE_URL+'mycart.php?type=cartlist" onclick="window.location.href=\''+SITE_URL+'mycart.php?type=cartlist\'" style="display:block; height:25px; line-height:25px; width:80px;background-color:#B40C0C; position:absolute; left:0px; bottom:-30px;">查看购物车</a>&nbsp;<a href="javascript:;" onclick="JqueryDialog.Close();" style="display:block; height:25px; line-height:25px; width:80px;background-color:#B40C0C; position:absolute; right:0px; bottom:-30px;">继续购物</a></p>';
				        JqueryDialog.Open('宝泰网提醒你 Remind you',str,300,50);
					}else{
						createwindow();
						$.post(SITE_URL+"ajaxfile/mycart.php",{action:'delcartid',id:0},function(data){
								$('.MYCART').hide();
								if(data !=""){
								$('.MYCART').html(data);
								$('.MYCART').fadeIn("slow");
								}
								removewindow();
						});
					}
				 }
			 }else{
				 if(data.error==5){ //存在商品属性
					  JqueryDialog.Open('宝泰网提醒你',data.message,300,200);
				 }else{
					if(data.error==0){
						if(tt=='jumpshopping'){ //jump shopping cart
							window.location.href = SITE_URL+'mycart.php?type=checkout';
						}else{
							var filename = "ajax_pop_cart";
							var ss = "";
							$.post(SITE_URL+'ajaxfile/popbox.php',{action:'box',boxname:filename,gid:goodsid,type:"cart",num:goods.number},function(data){
								$('body,html').animate({scrollTop:0},1000);
								$('.ajaxshowcart').html(data);
								$('.ajaxshowcartbox').fadeIn("slow");
								JqueryDialog.Close();
							});
						}
					}else{
						meswindow('<br /><br />'+data.message,'宝泰网提醒你',300,100);		
					}
				 }
			 }
			
	   }//end sucdess
	});
  return false;
}


//购物车js
/* *
 * 添加商品到购物车
 * tt:标记的页面类型
 * obj:
 */
function addToCart2(goodsid,tt,obj)
{
 if(tt=="jifen_cartlist" || tt=="jifen"){
	if(confirm("你确定兑换吗？兑换后你的积分将会相应减少！")){}else{ return false;}
 }
 
//判断js函数是否存在
 try{  
  if(typeof(eval('checkcartattr'))=="function"){ 
	  if(checkcartattr()==false){
		  return false;
	  }
  }
 }catch(e){
	   //alert("not function"); 
 }

  var goods        = new Object();  //一个商品的所有属性
  var spec_arr     = new Array(); //获取过来的商品属性
  var number       = 1;  //购买数据
  var formBuy      = document.forms['ECS_FORMBUY']; //表单

  // 检查是否有商品规格 
  if (formBuy)
  {
    spec_arr = getSelectedAttributes(formBuy);

    if (formBuy.elements['number'])
    {
      number = formBuy.elements['number'].value;
    }

  }else{
	 var objs = $(obj).parent().find("input[name='number']");
	 if(objs!="" && objs!=null && typeof(objs)!="undefined"){
		 number = $(objs).val();
	 }
	 if(number=='' || number==null || typeof(number)=='undefined'){
		 number = 1;
	 }
  }

  goods.spec     = spec_arr;
  goods.goods_id = goodsid;
  goods.number   = number;
  goods.optype = (typeof(tt)=='undefined' || tt=="") ? "" : tt;
  createwindow();
  $.ajax({
	   type: "POST",
	   url: SITE_URL+"ajaxfile/goods.php?action=addcart",
	   data: "goods=" + $.toJSON(goods),
	   dataType: "json",
	   success: function(data){
		   	removewindow();
			 if(tt=='cartlist'){ //购物车列表页面
				if(data.error==4){JqueryDialog.Open('宝泰网提醒你','<br />'+data.message,300,50); return false; }
				else if(data.error==5){ //存在商品属性
					  JqueryDialog.Open('宝泰网提醒你',data.message,300,200);
				}
				
				createwindow();
				$.post(SITE_URL+"ajaxfile/mycart.php",{action:'delcartid',id:0},function(data){
						$('.cart1 .MYCART').hide();
						if(data !=""){
						$('.cart1 .MYCART').html(data);
						$('.cart1 .MYCART').fadeIn("slow");
						}
						removewindow();
				});
			 }else if(tt=='jifen'){ //兑换积分商品
				 if(data.error==3){ //需要登录
					 JqueryDialog.Open('宝泰网登录系统',return_login_string('jifen',goodsid),300,50);
				 }else if(data.error==5){ //存在商品属性
					  JqueryDialog.Open('宝泰网提醒你',data.message,300,200);
				 }else{
				 str = '<br />'+data.message+'<br /><p style="width:175px; position:relative"><a href="'+SITE_URL+'shopping/" onclick="window.location.href=\''+SITE_URL+'shopping/\'" style="display:block; height:25px; line-height:25px; width:80px;background-color:#ffdff3; position:absolute; left:0px; bottom:-30px;">查看购物车</a>&nbsp;<a href="javascript:;" onclick="JqueryDialog.Close();" style="display:block; height:25px; line-height:25px; width:80px;background-color:#ffdff3; position:absolute; right:0px; bottom:-30px;">继续选购</a></p>';
				 JqueryDialog.Open('宝泰网提醒你',str,300,50);
				 }
			 }else if(tt=='jifen_cartlist'){ //兑换积分商品
				 if(data.error==3){ //需要登录
					 JqueryDialog.Open('宝泰网登录系统',return_login_string('jifen',goodsid),300,50);
				 }else if(data.error==5){ //存在商品属性
					  JqueryDialog.Open('宝泰网提醒你',data.message,300,200);
				 }else{
					if(data.error==2){
						str = '<br />'+data.message+'<br /><p style="width:175px; position:relative"><a href="'+SITE_URL+'shopping/" onclick="window.location.href=\''+SITE_URL+'shopping/\'" style="display:block; height:25px; line-height:25px; width:80px;background-color:#ffdff3; position:absolute; left:0px; bottom:-30px;">查看购物车</a>&nbsp;<a href="javascript:;" onclick="JqueryDialog.Close();" style="display:block; height:25px; line-height:25px; width:80px;background-color:#ffdff3; position:absolute; right:0px; bottom:-30px;">继续选购</a></p>';
				        JqueryDialog.Open('宝泰网提醒你',str,300,50);
					}else{
						createwindow();
						$.post(SITE_URL+"ajaxfile/mycart.php",{action:'delcartid',id:0},function(data){
								$('.cart1 .MYCART').hide();
								if(data !=""){
								$('.cart1 .MYCART').html(data);
								$('.cart1 .MYCART').fadeIn("slow");
								}
								removewindow();
						});
					}
				 }
			 }else{
				 if(data.error==5){ //存在商品属性
					  JqueryDialog.Open('宝泰网提醒你',data.message,300,200);
				 }else{
					
					 //str = '<br />'+data.message+'<br /><p style="width:175px; position:relative"><a href="'+SITE_URL+'shopping/" onclick="window.location.href=\''+SITE_URL+'shopping/\'" style="display:block; height:25px; line-height:25px; width:80px;background-color:#ffdff3; position:absolute; left:0px; bottom:-30px;">查看购物车</a>&nbsp;<a href="javascript:;" onclick="JqueryDialog.Close();" style="display:block; height:25px; line-height:25px; width:80px;background-color:#ffdff3; position:absolute; right:0px; bottom:-30px;">继续选购</a></p>';
					if(data.error==0){
						var filename = "ajax_pop_cart";
						var ss = "";
						$.post(SITE_URL+'ajaxfile/popbox.php',{action:'box',boxname:filename,gid:goodsid,type:"cart",num:goods.number},function(data){
							//meswindow(data,'宝泰网购物车系统提醒你',500,180);
							$('.MYCARTTOP').html(data);
							$(".floatTips").animate({right: "150px"}, 80).animate({right: "10px"}, 50 ).animate({right: "70px"}, 60 ).animate({right: "5px"}, 60 ).animate({right: "20px"}, 50 ).animate({right: "5px"}, 60 );
						});
					}else{
						meswindow('<br /><br />'+data.message,'宝泰网购物车系统提醒你',300,100);		
					}
				 }
			 }
			
	   }//end sucdess
	});
  return false;
}

/**
 * 获得选定的商品属性
 */
function getSelectedAttributes(formBuy)
{
  var spec_arr = new Array();
  var j = 0;

  for (i = 0; i < formBuy.elements.length; i ++ )
  {
    if(((formBuy.elements[i].type == 'radio' || formBuy.elements[i].type == 'checkbox') && formBuy.elements[i].checked) || formBuy.elements[i].tagName == 'SELECT' ||  formBuy.elements[i].type == 'hidden')
    {
      spec_arr[j] = formBuy.elements[i].name+'---'+formBuy.elements[i].value;
      j++ ;
    }
	  
  }

  return spec_arr;
}

/*##############################################*/
/*
*添加收藏
*/
function addToColl(gid){
	if(gid==""||typeof(gid)=='undefined') return false;
	
	$.post(SITE_URL+'ajaxfile/goods.php',{action:'addtocoll',goods_id:gid},function(data){ 
		//这里有4个返回值
		/*
		* return 1 =>商品id为空
		* return 2 =>还没有登录
		* return 3 =>添加成功
		* return 4 =>添加失败，意外错误
		* return 5 =>该商品已经存在购物车中了
		*/
		data = parseInt(data);
		if(data==1){
			
			str = '<br />添加失败！！传送ID为空！<br /><p class="opitem"><a href="href="'+SITE_URL+'user.php?act=mycoll" onclick="location.href=\''+SITE_URL+'user.php?act=mycoll\'" class="collview">查看收藏</a>&nbsp;<a href="javascript:;" onclick="JqueryDialog.Close();" class="collcolse">关闭</a></p>';
			JqueryDialog.Open('宝泰商城提醒你',str,300,90);
			 
		}else if(data==2){
			//JqueryDialog.Open('宝泰商城登陆系统',SITE_URL+'pop/login.php?gid='+gid+'&tt=coll',400,230,'frame');
			JqueryDialog.Open('宝泰网登录系统',return_login_string('coll',gid),300,50);
		}else if(data==3){
			
			str = '<br />恭喜你！已成功添加到你的收藏夹！<br /><p class="opitem"><a href="javascript:;" onclick="location.href=\''+SITE_URL+'user.php?act=mycoll\'" class="collview">查看收藏</a>&nbsp;<a href="javascript:;" onclick="JqueryDialog.Close();" class="collcolse">关闭</a></p>';
			JqueryDialog.Open('宝泰商城提醒你',str,300,90);
		}else if(data==5){
			
			str = '<br />该商品已经存在收藏夹中！<br /><p class="opitem"><a href="javascript:;" onclick="location.href=\''+SITE_URL+'user.php?act=mycoll\'" class="collview">立即查看</a>&nbsp;<a href="javascript:;"  onclick="JqueryDialog.Close();" class="collcolse">关闭</a></p>';
			 JqueryDialog.Open('宝泰商城提醒你',str,300,110);
		}else{
			
			str = '<br />添加失败，意外错误！<br /><p class="opitem"><a href="javascript:;" onclick="location.href=\''+SITE_URL+'user.php?act=mycoll\'" class="collview">查看收藏</a>&nbsp;<a href="javascript:;" onclick="JqueryDialog.Close();" class="collcolse">关闭</a></p>';
			JqueryDialog.Open('宝泰商城提醒你',str,300,90);
		}
	});
}

/*
*添加收藏
*/
function addToColl2(gid){
	if(gid==""||typeof(gid)=='undefined') return false;
	
	$.post(SITE_URL+'ajaxfile/goods.php',{action:'addtocoll',goods_id:gid},function(data){ 
		//这里有4个返回值
		/*
		* return 1 =>商品id为空
		* return 2 =>还没有登录
		* return 3 =>添加成功
		* return 4 =>添加失败，意外错误
		* return 5 =>该商品已经存在购物车中了
		*/
		data = parseInt(data);
		if(data==1){
			
			str = '<br />添加失败！！传送ID为空！<br /><p class="opitem"><a href="href="'+SITE_URL+'user.php?act=mycoll" onclick="location.href=\''+SITE_URL+'user.php?act=mycoll\'" class="collview">查看收藏</a>&nbsp;<a href="javascript:;" class="collcolse">关闭</a></p>';
			meswindow(str,'宝泰网提醒你',300,110);	
			//JqueryDialog.Open('宝泰网提醒你',str,300,40);
			 
		}else if(data==2){
			
			//JqueryDialog.Open('宝泰网登录系统',return_login_string('coll',gid),300,50);
			meswindow(return_login_string('coll',gid),'宝泰网登录系统',300,150);	
		}else if(data==3){
			
			str = '<br />恭喜你！已成功添加到你的收藏夹！<br /><p class="opitem"><a href="javascript:;" onclick="location.href=\''+SITE_URL+'user.php?act=mycoll\'" class="collview">查看收藏</a>&nbsp;<a href="javascript:;" onclick="closewindow(this);" class="collcolse">关闭</a></p>';
			meswindow(str,'宝泰网提醒你',300,110);	
			//JqueryDialog.Open('宝泰网提醒你',str,300,50);
			 
		}else if(data==5){
			
			str = '<br />该商品已经存在收藏夹中！<br /><p class="opitem"><a href="javascript:;" onclick="location.href=\''+SITE_URL+'user.php?act=mycoll\'" class="collview">立即查看</a>&nbsp;<a href="javascript:;"  onclick="closewindow(this);" class="collcolse">关闭</a></p>';
			 //JqueryDialog.Open('宝泰网提醒你',str,300,50);
			 meswindow(str,'宝泰网提醒你',300,110);	
		}else{
			
			str = '<br />添加失败，意外错误！<br /><p class="opitem"><a href="javascript:;" onclick="location.href=\''+SITE_URL+'user.php?act=mycoll\'" class="collview">查看收藏</a>&nbsp;<a href="javascript:;" onclick="closewindow(this);" class="collcolse">关闭</a></p>';
			//JqueryDialog.Open('宝泰网提醒你',str,300,40);
			meswindow(str,'宝泰网提醒你',300,110);	 
		}
	});
}

/*############################################*/
//评论处理区
function submit_comment(goods_id){
		if(goods_id=="") return false;
		var formComment      = document.forms['ECS_COMMENT']; //表单
		var comments        = new Object();
		if(formComment){
			comments = getCommentAttributes(formComment);
		}else{
			str = 'Error:不存在评论表单对象！<br /><br';
			JqueryDialog.Open('宝泰网提醒你',str,300,50);
			return false;
		}
		//comments.comment_rank = 3;
		comments.goods_id = goods_id;
		createwindow();
		$.ajax({
		   type: "POST",
		   url: SITE_URL+"ajaxfile/goods.php?action=comment",
		   data: "comments=" + $.toJSON(comments),
		   dataType: "json",
		   success: function(data){ 
				removewindow();
				if(data.error==""){
					str = '尊敬的用户！<br />评论成功，但你的评论需要审核！<br /><br />';
			 		JqueryDialog.Open('宝泰网提醒你',str,300,50);
					$('.GOODSCOMMENT').html(data.message);	
				}else if(parseInt(data.error)==4){ //需要先登录
					JqueryDialog.Open('宝泰网登录系统',return_login_string('comment',goods_id),300,50);
				}else if(parseInt(data.error)==1){ //需要先登录
					$('.comment_mes').html(data.message);
				}else{
					str = '警告：'+data.message;
			 		JqueryDialog.Open('宝泰网提醒你',str,300,50);
				}
				return false;
		   } //end sucdess
		});
}

function submit_message(goods_id){
		if(goods_id=="") return false;
		var formObj      = document.forms['MESSAGES']; //表单
		var mesobj        = new Object();
		if(formObj){
			mesobj = getCommentAttributes(formObj);
		}else{
			JqueryDialog.Open('宝泰网提醒你','不存在留言表单对象！',300,50);
			return false;
		}
		
		mesobj.goods_id = goods_id;
		
		$.ajax({
		   type: "POST",
		   url: SITE_URL+"ajaxfile/feedback.php",
		   data: "action=feedback&message=" + $.toJSON(mesobj),
		   dataType: "json",
		   success: function(data){
				if(data.error==0){
					JqueryDialog.Open('宝泰网提醒你','提问成功，我们会很快回复你提出的问题！',300,50);
					$('.GOODSMESSAGE').html(data.message);	
				}else if(data.error==2){
					$('.message_mes').html(data.message);
				}else{
					JqueryDialog.Open('宝泰网提醒你','<br />'+data.message,300,50);
				}
		   } //end sucdess
		}); //end ajax
} // end function
		
/**
 * 获得评论属性
 */
function getCommentAttributes(formComment)
{
  //var arr = new Array();
  var obj = new Object();
  var j = 0;

  for (i = 0; i < formComment.elements.length; i ++ )
  { 
    if(((formComment.elements[i].type == 'radio' || formComment.elements[i].type == 'checkbox') && formComment.elements[i].checked) || formComment.elements[i].tagName == 'SELECT' || formComment.elements[i].type=='text' || formComment.elements[i].type=='textarea' || formComment.elements[i].type=='hidden')
    {
	  obj[formComment.elements[i].name] = formComment.elements[i].value;
      j++ ;
    }
  }
return obj;
 // return arr;
}

//获取商品评论
function get_comment_page(page,goods_id){
		if(page==""||typeof(page)=='undefined') var page=1;
		if(goods_id==""||typeof(goods_id)=='undefined') return false;
		createwindow();
		$.post(SITE_URL+'ajaxfile/goods.php',{action:'getcommentlist',page:page,goods_id:goods_id},function(data){
			if(data!=""&&typeof(data)!='undefined'){
				$('.GOODSCOMMENT').html(data);
			}
			removewindow();
		});
}

function ajax_check_comment(gid){
	var uid = 0;
	$.post(SITE_URL+"user.php",{action:"getuid"},function(data){ 
		if(typeof(data)=='string'){
			uid = parseInt(data)>0 ? parseInt(data) : 0;
		}else{
			uid = 0;	
		}
		if(uid>0){
			JqueryDialog.Open('宝泰网评论系统',return_comment_string(gid),450,300);
		}else{
			JqueryDialog.Open('宝泰网登录系统',return_login_string('comment',gid),300,50);
		}
	});
}

//获取商品提问
function get_message_page(page,goods_id){
		if(page==""||typeof(page)=='undefined') var page=1;
		if(goods_id==""||typeof(goods_id)=='undefined') return false;
		createwindow();
		$.post(SITE_URL+'ajaxfile/feedback.php',{action:'getmessagelist',page:page,goods_id:goods_id},function(data){
			if(data!=""&&typeof(data)!='undefined'){
				$('.GOODSMESSAGE').html(data);
			}
			removewindow();
		});
}

function ajax_check_message(gid,gname){
	var uid = 0;
	$.post(SITE_URL+"user.php",{action:"getuid"},function(data){ 
		if(typeof(data)=='string'){
			uid = parseInt(data)>0 ? parseInt(data) : 0;
		}else{
			uid = 0;	
		}
		if(uid>0){
			JqueryDialog.Open('宝泰网商品提问系统',return_message_string(gid),405,270);
		}else{
			JqueryDialog.Open('宝泰网登录系统',return_login_string('message',gid),300,50);
		}
	});
}
//购买历史
function get_buyhistory_page(page){
	//HISTORYVIEW
}

//商品详情页面的分类商品
function get_categoods_page(page,cid){
	$.post(SITE_URL+"ajaxfile/goods.php",{action:"categoods",page:page,cid:cid},function(data){
		alert(data);	//还代做									   
	});
}

//分类商品
function get_categoods_page_list(page,cid,bid,price,order,sorts,limit,attr){
	var arr = new Object();
	if(page==""||typeof(page)=='undefined' || !(page>0)) page=1;
	arr.page = page;
	if(cid==""||typeof(cid)=='undefined') cid=0;
	arr.cid = cid;
	if(bid==""||typeof(bid)=='undefined') bid=0;
	arr.bid = bid;
	if(price==""||typeof(price)=='undefined') price="";
	arr.price = price;
	if(order==""||typeof(order)=='undefined') order="cat_id";
	arr.order = order;
	if(sorts==""||typeof(sorts)=='undefined') sorts="ASC";
	arr.sorts = sorts;
	if(limit==""||typeof(limit)=='undefined') limit="";
	arr.limit = limit;
	if(attr==""||typeof(attr)=='undefined') attr="";
	arr.attr = attr;
	if(keyword==""||typeof(keyword)=='undefined') keyword = "";
	arr.keyword = keyword;

	createwindow();
	$.ajax({
		   type: "POST",
		   url: SITE_URL+"ajaxfile/ajax.php",
		   data: "type=ajax_getcategoodslist&func=catalog&goodswhere=" + $.toJSON(arr),
		   dataType: "json",
		   success: function(data){
				 if(data.message!=""){
				 		$('.AJAX-PRODUCT-CONNENT').html(data.message);
				 }
				 removewindow();
		   } //end sucdess
	});
	return false;
}

//商品分类页面的显示方式
function setdisplay(page,cid,bid,price,order,sorts,limit,type,attr){
	if(type==""||typeof(type)=="undefined") var type="list";
	$.cookie('DISPLAY_TYPE',type);
	get_categoods_page_list(page,cid,bid,price,order,sorts,limit,attr);
}

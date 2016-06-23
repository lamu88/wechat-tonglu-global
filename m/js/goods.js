function pload(){
	setTimeout("JqueryDialog.Close()",2000);
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
  var formBuy      = document.forms['ECS_FORMBUY'];  //表单

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
	   url: SITE_URL+"ajax.php?action=addcart",
	   data: "goods=" + $.toJSON(goods),
	   dataType: "json",
	   success: function(data){
		   	removewindow();
			//是否关注后才能购买处理
			if(data.error=='22'){
					$('.show_gz').show();
					$('body,html').animate({scrollTop:0},500);
					return false;
			}
			
			//限制购买数量
			if(data.error=='44'){
				alert(data.message);
				return false;
			}
			
			//虚拟商品购物需跳转
			if(data.error=='33'){
					window.location.href = SITE_URL+'vgoods.php?type=checkout&gid='+goodsid;
					return false;
			}
			
			if(tt!="jumpshopping"){
					var flyElm = $('.ggimg').clone().css('opacity','0.7');
					flyElm.css({
						'z-index': 9000,
						'display': 'block',
						'position': 'absolute',
						'top': $('.ggimg').offset().top +'px',
						'left': $('.ggimg').offset().left +'px',
						'width': $('.ggimg').width() +'px',
						'height': $('.ggimg').height() +'px'
					});
					$('body').append(flyElm);
					hw = getPageSize();
					flyElm.animate({
						top:$('#collectBox').offset().top,
						left:(hw[0]-30)+'px',
						width:30,
						height:30,
					},'2500', function (){
						flyElm.animate({opacity: 'hide'}, 1000);
					});
					
			  }
  
			 if(tt=='cartlist'){ //购物车列表页面
				if(data.error==4){JqueryDialog.Open('官方系统提醒你','<br />'+data.message,300,50); return false; }
				else if(data.error==5){ //存在商品属性
					  JqueryDialog.Open('官方系统提醒你',data.message,300,200);
				}
				 
				createwindow();
				$.post(SITE_URL+"mycart.php",{action:'delcartid',id:0},function(data){
						$('.cart1 .MYCART').hide();
						if(data !=""){
						$('.cart1 .MYCART').html(data);
						$('.cart1 .MYCART').fadeIn("slow");
						}
						removewindow();
				});
			 }else if(tt=='jifen'){ //兑换积分商品
				 if(data.error==3){ //需要登录
					  JqueryDialog.Open('登录系统',return_login_string('jifen',goodsid),300,50);
				 }else if(data.error==5){ //存在商品属性
					  JqueryDialog.Open('官方系统提醒你',data.message,300,200);
				 }else if(data.error==2){
					  str = data.message+'<br />';
				 	  //JqueryDialog.Open('官方系统提醒你',str,300,60);
				 }else{
					 window.location.href = SITE_URL+'excart.php?type=checkout';
				 }
			 }else if(tt=='jifen_cartlist'){ //兑换积分商品
				 if(data.error==3){ //需要登录
					 JqueryDialog.Open('登录系统',return_login_string('jifen',goodsid),300,50);
				 }else if(data.error==5){ //存在商品属性
					  JqueryDialog.Open('官方系统提醒你',data.message,300,200);
				 }else{
					if(data.error==2){
						str = '<br />'+data.message+'<br /><p style="width:175px; position:relative"><a href="'+SITE_URL+'mycart.php?type=shopping" onclick="window.location.href=\''+SITE_URL+'mycart.php?type=shopping\'" style="display:block; height:25px; line-height:25px; width:80px;background-color:#ffdff3; position:absolute; left:0px; bottom:-30px;">查看购物车</a>&nbsp;<a href="javascript:;" onclick="JqueryDialog.Close();" style="display:block; height:25px; line-height:25px; width:80px;background-color:#ffdff3; position:absolute; right:0px; bottom:-30px;">继续选购</a></p>';
				        JqueryDialog.Open('官方系统提醒你',str,300,50);
					}else{
						createwindow();
						$.post(SITE_URL+"mycart.php",{action:'delcartid',id:0},function(data){
								$('.cart1 .MYCART').hide();
								if(data !=""){
								$('.cart1 .MYCART').html(data);
								$('.cart1 .MYCART').fadeIn("slow");
								}
								removewindow();
						});
					}
					pload();
				 }
			 }else{
				 if(data.error==5){ //存在商品属性
					  JqueryDialog.Open('官方系统提醒你',data.message,300,200);
				 }else{
					//pload();
					
					if(data.error==0){
						if(tt=='jumpshopping'){ //jump shopping cart
							window.location.href = SITE_URL+'mycart.php?type=checkout';
						}
						else{
							if(tt=='jumpshopping_up'){
								window.location.href = SITE_URL+'mycart.php?type=checkout&up=up';
							}
							//JqueryDialog.Open('官方系统提醒你','加入购物车成功',260,40);
							//alert("加入购物车成功！");
							if(data.nums > 0){
								$('.mycarts').html(data.nums);
							}
						}
					}else{
						//alert("加入购物车成功！");
						JqueryDialog.Open('官方系统提醒你',data.message,260,40);
					}
				 }
			 }
			
	   }//end sucdess
	});
  return false;
}

//积分兑换
function addToCartJifen(goodsid)
{ 
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

  }

  goods.spec     = spec_arr;
  goods.goods_id = goodsid;
  goods.number   = number;
  goods.optype = (typeof(tt)=='undefined' || tt=="") ? "" : tt;
  createwindow();
  $.ajax({
	   type: "POST",
	   url: SITE_URL+"excart.php?action=ajax_add_cart",
	   data: "goods=" + $.toJSON(goods),
	   dataType: "json",
	   success: function(data){
		   	removewindow();
			 if(data.error==3){ //需要登录
				  window.location.href = SITE_URL;
			 }else if(data.error==5){ //存在商品属性
				  JqueryDialog.Open('官方系统提醒你',data.message,280,200);
			 }else if(data.error==2){
				  str = data.message+'<br />';
				  JqueryDialog.Open('官方系统提醒你',str,280,60);
				  pload();
			 }else{
				 window.location.href = SITE_URL+'excart.php?type=checkout';
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
	
	$.post(SITE_URL+'ajax.php',{action:'addtocoll',goods_id:gid},function(data){ 
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
			
			str = '添加失败！！传送ID为空！<br /><p class="opitem"><a href="href="'+SITE_URL+'user.php?act=mycoll" onclick="location.href=\''+SITE_URL+'user.php?act=mycoll\'" class="collview">查看收藏</a>&nbsp;<a href="javascript:;" class="collcolse" onclick="JqueryDialog.Close();">关闭</a></p>';
			//meswindow(str,'官方系统提醒你',300,110);	
			JqueryDialog.Open('官方系统提醒你',str,280,70);
			pload(); 
		}else if(data==2){
			
			JqueryDialog.Open('登录系统',return_login_string('coll',gid),300,100);
			//meswindow(return_login_string('coll',gid),'登录系统',300,150);	
		}else if(data==3){
			
			str = '恭喜你！已成功添加到你的收藏夹！<br /><p class="opitem"><a href="javascript:;" onclick="location.href=\''+SITE_URL+'user.php?act=mycoll\'" class="collview">查看收藏</a>&nbsp;<a href="javascript:;" onclick="JqueryDialog.Close();" class="collcolse">关闭</a></p>';
			//meswindow(str,'官方系统提醒你',300,110);	
			JqueryDialog.Open('官方系统提醒你',str,280,70);
			pload();
		}else if(data==5){
			
			str = '该商品已经存在收藏夹中！<br /><p class="opitem"><a href="javascript:;" onclick="location.href=\''+SITE_URL+'user.php?act=mycoll\'" class="collview">立即查看</a>&nbsp;<a href="javascript:;"  onclick="JqueryDialog.Close();" class="collcolse">关闭</a></p>';
			 JqueryDialog.Open('官方系统提醒你',str,280,70);
			 //meswindow(str,'官方系统提醒你',300,110);
			 pload();
		}else{
			
			str = '添加失败，意外错误！<br /><p class="opitem"><a href="javascript:;" onclick="location.href=\''+SITE_URL+'user.php?act=mycoll\'" class="collview">查看收藏</a>&nbsp;<a href="javascript:;" onclick="JqueryDialog.Close();" class="collcolse">关闭</a></p>';
			JqueryDialog.Open('官方系统提醒你',str,280,70);
			//meswindow(str,'官方系统提醒你',300,110);
			pload();
		}
	});
}

/*
*添加收藏
*/
function addToShopColl(gid){
	if(gid==""||typeof(gid)=='undefined') return false;
	
	$.post(SITE_URL+'ajaxfile/shop.php',{action:'addtocoll',shop_id:gid},function(data){ 
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
			
			str = '<br />添加失败！！传送ID为空！<br /><p class="opitem"><a href="'+SITE_URL+'user.php?act=mycoll" onclick="location.href=\''+SITE_URL+'user.php?act=mycoll\'" class="collview">查看收藏</a>&nbsp;<a href="javascript:;"  onclick="closewindow(this);" class="collcolse">关闭</a></p>';
			meswindow(str,'官方系统提醒你',300,110);	
			//JqueryDialog.Open('官方系统提醒你',str,300,40);
			 
		}else if(data==2){
			
			//JqueryDialog.Open('登录系统',return_login_string('coll',gid),300,50);
			meswindow(return_login_string('coll',gid),'登录系统',300,150);
		}else if(data==3){
			
			str = '<br />恭喜你！已成功添加到你的收藏夹！<br /><p class="opitem"><a href="javascript:;" onclick="location.href=\''+SITE_URL+'user.php?act=mycoll\'" class="collview">查看收藏</a>&nbsp;<a href="javascript:;" onclick="closewindow(this);" class="collcolse">关闭</a></p>';
			meswindow(str,'官方系统提醒你',300,110);	
			//JqueryDialog.Open('官方系统提醒你',str,300,50);
			 
		}else if(data==5){
			
			str = '<br />该店铺已经存在收藏夹中！<br /><p class="opitem"><a href="javascript:;" onclick="location.href=\''+SITE_URL+'user.php?act=mycoll\'" class="collview">立即查看</a>&nbsp;<a href="javascript:;"  onclick="closewindow(this);" class="collcolse">关闭</a></p>';
			 //JqueryDialog.Open('官方系统提醒你',str,300,50);
			 meswindow(str,'官方系统提醒你',300,110);	
		}else{
			
			str = '<br />添加失败，意外错误！<br /><p class="opitem"><a href="javascript:;" onclick="location.href=\''+SITE_URL+'user.php?act=mycoll\'" class="collview">查看收藏</a>&nbsp;<a href="javascript:;" onclick="closewindow(this);" class="collcolse">关闭</a></p>';
			//JqueryDialog.Open('官方系统提醒你',str,300,40);
			meswindow(str,'官方系统提醒你',300,110);	 
		}
	});
}

/*############################################*/
function ajax_set_comtent(str){
		t = typeof($('.GOODSCOMMENT').html());
		if(t == "string"){
			$('.GOODSCOMMENT').html(str);
			return 2;
		}else{
			return 1;
		}
}

//评论处理区
function submit_comment(goods_id){
		if(goods_id=="") return false;
		var formComment      = document.forms['ECS_COMMENT']; //表单
		var comments        = new Object();
		if(formComment){
			comments = getCommentAttributes(formComment);
		}else{
			str = 'Error:不存在评论表单对象！<br /><br';
			JqueryDialog.Open('官方系统提醒你',str,300,50);
			return false;
		}
		//comments.comment_rank = 3;
		comments.shopid = goods_id;
		createwindow();
		$.ajax({
		   type: "POST",
		  // url: SITE_URL+"ajaxfile/goods.php?action=comment",
		   url: SITE_URL+"ajaxfile/shop.php?action=comment",
		   data: "comments=" + $.toJSON(comments),
		   dataType: "json",
		   success: function(data){ 
				removewindow();
				if(data.error=="" || data.error==0){
					$('.comment_con textarea[name="comment"]').val("");

					/*if(window.parent.ajax_set_comtent(data.message) == 1){
						str = '<br/>尊敬的用户,点评成功！<br /><br />';
						JqueryDialog.Open('官方系统提醒你',str,300,50);
					}else{*/
						window.parent.ajax_set_comtent(data.message);
						window.parent.JqueryDialog.Close();
					//}
					
					//$('.GOODSCOMMENT').html(data.message);	
				}else if(parseInt(data.error)==4){ //需要先登录
					JqueryDialog.Open('登录系统',return_login_string('comment',goods_id),300,50);
				}else if(parseInt(data.error)==1){ //需要先登录
					$('.comment_mes').html(data.message);
				}else{
					str = '警告：'+data.message;
			 		JqueryDialog.Open('官方系统提醒你',str,300,50);
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
			JqueryDialog.Open('官方系统提醒你','不存在留言表单对象！',300,50);
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
					JqueryDialog.Open('官方系统提醒你','提问成功，我们会很快回复你提出的问题！',300,50);
					$('.GOODSMESSAGE').html(data.message);	
				}else if(data.error==2){
					$('.message_mes').html(data.message);
				}else{
					JqueryDialog.Open('官方系统提醒你','<br />'+data.message,300,50);
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
		$.post(SITE_URL+'product.php',{action:'ajax_getcommentlist',page:page,goods_id:goods_id},function(data){
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
			JqueryDialog.Open('评论系统',return_comment_string(gid),450,300);
		}else{
			JqueryDialog.Open('登录系统',return_login_string('comment',gid),300,50);
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
			JqueryDialog.Open('商品提问系统',return_message_string(gid),405,270);
		}else{
			JqueryDialog.Open('登录系统',return_login_string('message',gid),300,50);
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
function get_categoods_page_list(page,cid,bid,price,order,sorts,limit){
	var arr = new Object();
	if(page==""||typeof(page)=='undefined') page=1;
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
	if(keyword==""||typeof(keyword)=='undefined') keyword = "";
	arr.keyword = keyword;
	
	createwindow();
	$.ajax({
		   type: "POST",
		   url: SITE_URL+"ajaxfile/goodscate.php?action=getgoodslist",
		   data: "goodswhere=" + $.toJSON(arr),
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
function setdisplay(page,cid,bid,price,order,sorts,limit,type){
	if(type==""||typeof(type)=="undefined") var type="list";
	$.cookie('DISPLAY_TYPE',type);
	get_categoods_page_list(page,cid,bid,price,order,sorts,limit);
}

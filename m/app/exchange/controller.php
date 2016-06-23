<?php
//积分展示模块
class ExchangeController extends Controller{

 	function  __construct() {
		$this->js(array('jquery.json-1.3.js','goods.js?v=20141212','time.js','user.js?v=201412'));//将js文件放到页面头
	}
	
	function index(){
		$page = 1;
		$rt = $this->Cache->read(3600);
	 	if(is_null($rt)) {
			$list = 20;
			$start = ($page-1)*$list;
			$tt = $this->App->findvar("SELECT COUNT(goods_id) FROM `{$this->App->prefix()}goods` WHERE is_on_sale='1' AND is_alone_sale='1' AND is_jifen='1'");
			//$rt['jifengoodspage'] = Import::basic()->ajax_page($tt,$list,$page,'get_jifen_page'); //分页
			$sql = "SELECT goods_id,goods_name,market_price,shop_price,promote_price,goods_thumb,goods_img,is_jifen,need_jifen FROM `{$this->App->prefix()}goods` WHERE is_on_sale='1' AND is_alone_sale='1' AND is_jifen='1' ORDER BY sort_order ASC, goods_id DESC LIMIT $start,$list";
			$rt['lists'] = $this->App->find($sql);
			
			
			$this->Cache->write($rt);
		}
		
		$this->set('rt',$rt);
		//设置页面meta
		if(!defined(NAVNAME)) define('NAVNAME', "积分兑换");
		$title = '积分换取、积分获得、礼品相送';
		$this->title($title.' - '.$GLOBALS['LANG']['site_name']);
		$this->meta("title",$title);
		$mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';
		$this->template($mb.'/jifen_index');
	}
	
	//商品详情页面
	function goods($gid=0){ 
		$this->action('common','checkjump');
		$this->css("flexslider.css");
		$this->js(array("jquery.flexslider-min.js","main.js"));
		if(!($gid>0)){
			$this->action('common','show404tpl');
		} 
		
		/* START 记录浏览历史 */
		if (!empty($_COOKIE['HISTORYVIEW']))
		{
			$history = explode(',', $_COOKIE['HISTORYVIEW']);
		
			array_unshift($history, $gid);
			$history = array_unique($history);
		
			while (count($history) > 6) //显示6个商品
			{
				array_pop($history);
			}
		
			setcookie('HISTORYVIEW', implode(',', $history), mktime() + 3600 * 24 * 30);
		}
		else
		{
			setcookie('HISTORYVIEW', $gid, mktime() + 3600 * 24 * 30);
		}
		/* END 记录浏览历史 */
		
		$rt = $this->Cache->read(3600);
	 	if(is_null($rt)) {
			//商品详情信息
			$sql = "SELECT g.*,gc.cat_name,b.brand_name,b.brand_desc FROM `{$this->App->prefix()}goods` AS g";
			$sql .=" LEFT JOIN `{$this->App->prefix()}brand` AS b ON g.brand_id = b.brand_id";
			$sql .=" LEFT JOIN `{$this->App->prefix()}goods_cate` AS gc ON gc.cat_id = g.cat_id";
			$sql .=" WHERE g.goods_id = '$gid' LIMIT 1"; 
			$rt['goodsinfo'] = $this->App->findrow($sql);
			if($rt['goodsinfo']['is_promote']=='1' && $rt['goodsinfo']['promote_start_date'] < mktime() && $rt['goodsinfo']['promote_end_date'] > mktime()){$rt['goodsinfo']['is_promote']=='1';}else{$rt['goodsinfo']['is_promote']=='0';}
			if($rt['goodsinfo']['is_qianggou']=='1' && $rt['goodsinfo']['qianggou_start_date'] < mktime() && $rt['goodsinfo']['qianggou_end_date'] > mktime()){$rt['goodsinfo']['is_qianggou']=='1';}else{$rt['goodsinfo']['is_qianggou']=='0';}
						
			if(empty($rt['goodsinfo'])){
				$this->action('common','show404tpl');
			}
			if($rt['goodsinfo']['is_promote']=='1'){
					//促销 价格
					if($rt['goodsinfo']['promote_start_date']<mktime()&&$rt['goodsinfo']['promote_end_date']>mktime()){
						$rt['goodsinfo']['promote_price'] = format_price($rt['goodsinfo']['promote_price']);
					}else{
						$rt['goodsinfo']['promote_price'] = "0.00";
					}
			}else{
					$rt['goodsinfo']['promote_price'] = "0.00";
			}
				
			//当前商品的属性
			$sql = "SELECT tb1.*,tb2.* FROM `{$this->App->prefix()}goods_attr` AS tb1";
			$sql .=" LEFT JOIN `{$this->App->prefix()}attribute` AS tb2 ON tb1.attr_id = tb2.attr_id";
			$sql .=" WHERE tb1.goods_id='{$gid}'";
			$spec = $this->App->find($sql);
			$rt['spec'] = array();
			if(!empty($spec)){
				foreach($spec as $k=>$row){
					$rt['spec'][$row['attr_id']][] = $row;
				}
                unset($row,$spec);
			}
			
			//商品的相册
			$sql = "SELECT * FROM `{$this->App->prefix()}goods_gallery` WHERE goods_id='{$gid}'";
			$gallery = $this->App->find($sql);
			$rt['gallery'][0]['goods_thumb'] = is_file(SYS_PATH.$rt['goodsinfo']['goods_thumb']) ? SITE_URL.$rt['goodsinfo']['goods_thumb'] : SITE_URL.'theme/images/no_picture.gif';
			$rt['gallery'][0]['goods_img'] = is_file(SYS_PATH.$rt['goodsinfo']['goods_img']) ? SITE_URL.$rt['goodsinfo']['goods_img'] : SITE_URL.'theme/images/no_picture.gif';
			$rt['gallery'][0]['original_img'] = is_file(SYS_PATH.$rt['goodsinfo']['original_img']) ? SITE_URL.$rt['goodsinfo']['original_img'] : SITE_URL.'theme/images/no_picture.gif';
			$rt['gallery'][0]['img_desc'] = $rt['goodsinfo']['goods_name'];
			
			if(!empty($gallery)){
				foreach($gallery as $k=>$row){
					$k++;
					if(empty($row['img_url'])) continue;
					$q = dirname($row['img_url']);
					$h = basename($row['img_url']);
					$rt['gallery'][$k]['goods_thumb'] = SITE_URL.$q.'/thumb_s/'.$h;
					$rt['gallery'][$k]['goods_img'] = SITE_URL.$q.'/thumb_b/'.$h;
					$rt['gallery'][$k]['original_img'] = SITE_URL.$row['img_url'];
					$rt['gallery'][$k]['img_desc'] = $row['img_desc'];
				}
				unset($row,$gallery);
			}

			//评价等级
			$sql = "SELECT COUNT(comment_id) AS rankcount,comment_rank FROM `{$this->App->prefix()}comment` WHERE id_value='$gid' AND status ='1' GROUP BY comment_rank";
			$rank_count = $this->App->find($sql);
			if(!empty($rank_count)){
				foreach($rank_count as $row){
					$rt['rank_count'][$row['comment_rank']] = $row['rankcount'];
					$rt['rank_count']['zcount'] += $row['rankcount'];
				}
				unset($rank_count);
			}
			
			$sql = "SELECT ROUND(AVG(goods_rand)) AS avg_goods_rand, ROUND(AVG(shopping_rand)) AS avg_shopping_rand ,ROUND(AVG(saleafter_rand)) AS avg_saleafter_rand  FROM `{$this->App->prefix()}comment` WHERE id_value='$gid' AND status ='1'";
			$rt['avg_rank'] = $this->App->findrow($sql);
		
			$this->Cache->write($rt);
		} 
		
		
		//设置页面meta
		//$title = htmlspecialchars($rt['goodsinfo']['goods_name']);
		$title = "商品详情";
		if(!defined(NAVNAME)) define('NAVNAME', $title);
		$this->title($title.' - '.$GLOBALS['LANG']['site_name']);
		$this->meta("title",$title);
		$this->meta("keywords",htmlspecialchars($rt['goodsinfo']['meta_keys']));
		$this->meta("description",htmlspecialchars($rt['goodsinfo']['meta_desc']));
		$this->set('rt',$rt);	
		$mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';
		$this->set('mubanid',$GLOBALS['LANG']['mubanid']);
		$this->template($mb.'/exchange_index');
	}


		//订单确认
	function checkout(){
		$this->title('我的购物车 - 确认兑换 - '.$GLOBALS['LANG']['site_name']);
		$uid = $this->Session->read('User.uid');
		if(empty($uid)){ $this->jump(ADMIN_URL); exit;}		
		$rt['goodslist'] = $this->Session->read('excart');
		if(empty($rt['goodslist'])){
			$this->jump(ADMIN_URL,0,'购物车为空，请先加入购物车！');exit;
		}
		
		$rt['province'] = $this->action('user','get_regions',1);  //获取省列表
		
		$sql = "SELECT ua.*,rg.region_name AS provincename,rg1.region_name AS cityname,rg2.region_name AS districtname FROM `{$this->App->prefix()}user_address` AS ua";
		$sql .=" LEFT JOIN `{$this->App->prefix()}region` AS rg ON rg.region_id = ua.province";
		$sql .=" LEFT JOIN `{$this->App->prefix()}region` AS rg1 ON rg1.region_id = ua.city";
		$sql .=" LEFT JOIN `{$this->App->prefix()}region` AS rg2 ON rg2.region_id = ua.district WHERE ua.user_id='$uid' AND ua.is_own='0' GROUP BY ua.address_id LIMIT 1";
		$rt['userress'] = $this->App->find($sql);
		
		//支付方式
		$sql = "SELECT * FROM `{$this->App->prefix()}payment`";
		$rt['paymentlist'] = $this->App->find($sql);
	

		//配送方式
		$sql = "SELECT * FROM `{$this->App->prefix()}shipping`";
		$rt['shippinglist'] = $this->App->find($sql);
	
	
		
			
		//我的积分
		$sql = "SELECT SUM(points) FROM `{$this->App->prefix()}user_point_change` WHERE uid='$uid'";
		$rt['mypoints'] = $this->App->findvar($sql);
		
		//用户等级折扣
		$rt['discount'] = 100;
		$rank = $this->Session->read('User.rank');
		if($rank>0){
			$sql = "SELECT discount FROM `{$this->App->prefix()}user_level` WHERE lid='$rank' LIMIT 1";
			$rt['discount'] = $this->App->findvar($sql);
		}

		$active = $this->Session->read('User.active');
		//购物车商品
		if(!empty($rt['goodslist'])){
			foreach($rt['goodslist'] as $k=>$row){
				//求出实际价格
				 $comd = array();
				 $comd[] = $row['pifa_price'];
			     if($row['is_promote']=='1' && $row['promote_start_date'] < mktime() && $row['promote_end_date'] > mktime() && $row['promote_price']>0){ //促销价格
					    $comd[] =  $row['promote_price'];
			     }
			     if($row['is_qianggou']=='1' && $row['qianggou_start_date'] < mktime() && $row['qianggou_end_date'] > mktime() && $row['qianggou_price']>0){ //抢购价格
					    $comd[] =  $row['qianggou_price'];
			     }
					   
				 $onetotal = min($comd);
				 if(intval($onetotal)<=0) $onetotal = $row['shop_price'];
				 $total +=($row['number']*$onetotal); //总价格
				 
			}
		}
		
		
		if(!defined(NAVNAME)) define('NAVNAME', "确认兑换");		 
		$this->set('rt',$rt);
		$mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';
		$this->template($mb.'/exchange_checkout');
		
	}
	
	//加入购物车
	function ajax_add_cart($data = array()){
		/*
		*error：可以的值
		*0:无任何错误提示
		*1:购买数量操作库存
		*2:错误提示，提示内容为message值
		*/
		
		$err = 0;
		$result = array('error' => $err, 'message' => '');
		$json = Import::json();
	
		if (empty($data['goods']))
		{
			$result['error'] = 2;
			$result['message'] = '传送的数据为空！';
			die($json->encode($result));
		}
		$goods = $json->decode($data['goods']); //反json
		
		$optype = $goods->optype;
		$spec = $goods->spec; 
		$number = $goods->number;
		if(!($number>0)) $number = 1;
		$goods_id = $goods->goods_id;
		
		//处理搭配购买的商品 传送的goods_id例子：12:11|22|44
		$dapei_ids = array();
		if(strpos($goods_id,':')){ //
			$dapei_ar = explode(":",$goods_id);
			$goods_id = $dapei_ar[0];
			$dapei_ids = !empty($dapei_ar[1]) ? explode("|",$dapei_ar[1]) : array();
			unset($dapei_ar);
		}
		
		$sql = "SELECT * FROM `{$this->App->prefix()}goods` WHERE goods_id='$goods_id' LIMIT 1";
		$cart = array();
		$cart = $this->App->findrow($sql);
		$is_alone_sale = $cart['is_alone_sale'];
		
		if(empty($cart)){ //空信息处理
			$result['error'] = 2;
			$result['message'] = '该商品的记录信息为空！';
			die($json->encode($result));
		}
		
		###############################
		//验证是否是兑换积分===>相应减少积分
		$is_pay_jifen = false;
		$cart['is_jifen_session'] = 0; //默认积分兑换
		if($cart['is_jifen']=='1'){
			//检查用户的目前的积分
			$uid = $this->Session->read('User.uid');
			if(!($uid>0)){ //需要先登录
				$result['error'] = 3;
				$result['message'] = '请您先登录后再操作！';
				die($json->encode($result));
			}
				
				
			$need_jifen = $cart['need_jifen'];
			if($need_jifen>0){
				$sql = "SELECT SUM(points) FROM `{$this->App->prefix()}user_point_change` WHERE uid='$uid'";
				$points = $this->App->findvar($sql);
				$points = empty($points) ? 0 : $points;
				
				$sql = "SELECT mypoints FROM `{$this->App->prefix()}user` WHERE user_id='$uid'";
				$points2 = $this->App->findvar($sql);
				
				if($points>0 && $points2>0 && $points2>=$need_jifen){ //满足兑换积分的条件
					$is_pay_jifen = true;
					$cart['is_jifen_session'] = 1; 
				}else{
					$result['error'] = 2;
					$result['message'] = '当前积分为：<font color=red>'.$points.'</font>积分！<br />很抱歉，无法满足兑换该商品的条件！';
					die($json->encode($result));
				}
				
			}else{
				$result['error'] = 2;
				$result['message'] = '意外错误，您暂时不能兑换商品！';
				die($json->encode($result));
			}
			
			$pri = $cart['pifa_price'];
			if($pri>0){
				$sql = "SELECT SUM(money) FROM `{$this->App->prefix()}user_money_change` WHERE uid='$uid'";
				$moneys = $this->App->findvar($sql);
				$moneys = empty($moneys) ? 0 : $moneys;
				if($moneys>0 && $moneys>=$pri){ //满足兑换积分的条件
					$is_pay_jifen = true;
					$cart['is_jifen_session'] = 1; 
				}else{
					//$result['error'] = 2;
					//$result['message'] = '当前资金为：<font color=red>'.$moneys.'</font>元！<br />很抱歉，您的资金不足，请才充值！';
					//die($json->encode($result));
				}
				
			}
			
		}
		#############################
		
		if($cart['goods_number']<$number){ //不能购买大于库存数量
			$result['error'] = 1;
			$result['message'] = '购买数量不能大于库存数量！';
			die($json->encode($result));
		}
		
		//是否是赠品，如果是赠品，那么只能添加一件，不能重复添加
		if($is_alone_sale=='0'){
			$is_gift = $this->Session->read("excart.{$goods_id}");
			if(isset($is_gift)&&!empty($is_gift)){
				$result['error'] = 4;
				$result['message'] = '赠品不能重复添加！';
				die($json->encode($result));
			}
		}
		//end 赠品

		//start 检查是否有商品属性
		if(empty($spec)){
			$sql = "SELECT tb1.*,tb2.* FROM `{$this->App->prefix()}goods_attr` AS tb1";
			$sql .=" LEFT JOIN `{$this->App->prefix()}attribute` AS tb2 ON tb1.attr_id = tb2.attr_id";
			$sql .=" WHERE tb1.goods_id='{$goods_id}' AND tb2.is_show_cart='1'";
			$spec = $this->App->find($sql);
					
			$rt['spec'] = array();
			if(!empty($spec)){
				foreach($spec as $k=>$row){
					$rt['spec'][$row['attr_id']][] = $row;
				}
				unset($row,$spec);
			}
			if(!empty($rt['spec'])){ //存在商品属性，弹出对话框
				$rt['goodsinfo']['goods_id'] = $goods_id;
				$this->set('rt',$rt);
				$con = $this->fetch('ajax_show_goods_spec',true);
				$result = array('error' => 5, 'message' => $con); 
				unset($con);
				die($json->encode($result));
			}
		}
		//end 检查是否有商品属性
			
		$key_ar = array();
		$str = array();
		if(!empty($spec)){ //取出来商品属性
			if(!empty($spec)){
				foreach($spec as $var){
					$ar = explode('---',$var);
					$k = isset($ar[0]) ? $ar[0] : "";
					if(empty($k)) continue;
					$v = isset($ar[1]) ? $ar[1] : "";
					if(!in_array($k,$key_ar)){
						$str[$k]= $this->get_goods_spec_name($k).':'.$v;
					}else{
						$str[$k] .= '+'.$v; 
					}
					$key_ar[] = $k;
				}
				unset($spec);
			}
			
		}

		$cart['spec'] = $str; //商品属性
		$cart['number'] = $number; //商品数量
		//搭配商品
		$cart['dapei'] = array();
		if(!empty($dapei_ids)){
			$sql = "SELECT uid AS supplier_id,goods_id,goods_name,brand_id,goods_number,goods_weight,market_price,shop_price,promote_price,promote_start_date,promote_end_date,is_qianggou,qianggou_price,qianggou_start_date,qianggou_end_date,goods_thumb,goods_img,is_on_sale,is_shipping,is_promote,is_jifen,need_jifen FROM `{$this->App->prefix()}goods` WHERE ".db_create_in($dapei_ids);
			$cart['dapei'] = $this->App->find($sql);
		}
		
		//处理重复添加
		$ty = md5(@implode('+',$str).'+'.$cart['goods_id']); //当前的唯一商品标记
		$thiscart = $this->Session->read('excart'); //读取当前购物车商品
		if(!empty($thiscart)){ //购物车中已经有内容
			$gids = array_keys($thiscart);  //所有商品id
			$md5_arr = array();
			foreach($thiscart as $kk=>$row){
				 if(@ereg('[#,_]',$kk)){
				 	 list($k)=split('[#,_]',$kk);
				 }else{
				  	$k=$kk;
				 }
				$md5 = md5(implode('+',$row['spec']).'+'.$k);
				//if(!in_array($md5,$md5_arr)){		
					$md5_arr[] = $md5; 
				//}
			}

			if(is_array($md5_arr)&&!empty($md5_arr)&&!empty($ty)){
                            if(!in_array($ty,$md5_arr)){ //没存在重复的项
                                if(in_array($goods_id,$gids)){
                                   $goods_id=$goods_id.'_'.mktime(); //新的id
                                }
                                unset($md5_arr);
                                $this->Session->write("excart.{$goods_id}",  $cart);
                            }else{  //已经存在重复项
                                $index=array_search($ty,$md5_arr);
                                list($p)=array_keys(array_slice($thiscart,$index,1 ,true));
                                if(!empty($p)){ //数量+1
                                        //$this->Session->write("excart.{$p}.number",  ($thiscart[$p]['number'])+$number);
                                }
                           }
                      }else{
                            $this->Session->write("excart.{$goods_id}",  $cart);
                      }
			
		}else{ //购物车没内容
			$this->Session->write("excart.{$goods_id}",  $cart); //写入购物车
		}

		$result = array('error' => $err, 'message' => '恭喜您，已成功将产品添加到购物车，继续购买请点继续选购，结帐请点前往购物车！');
		die($json->encode($result));
	}
	
	/*
	确认订单提交页面
	*/
	function confirm(){
		$this->title('我的购物车 - 确认支付 - '.$GLOBALS['LANG']['site_name']);
		$uid = $this->Session->read('User.uid');
		if(empty($uid)){ $this->jump(ADMIN_URL); exit;}
		
		if(isset($_POST)&&!empty($_POST)){
			$shipping_id = isset($_POST['shipping_id']) ? $_POST['shipping_id'] : 0;
			
			$userress_id = isset($_POST['userress_id']) ? $_POST['userress_id'] : 0;
			if(!($userress_id>0)){  //如果是提交添加地址的，需要添加到user_address表
				//添加收货地址
				$dd['user_id'] = $uid;
				$dd['consignee'] = $_POST['consignee'];
				if(empty($dd['consignee'])){
					$this->jump(ADMIN_URL.'excart.php?type=checkout',0,'收货人不能为空！'); exit ;
				}
				$dd['country'] = 1;
				$dd['province'] = $_POST['province'];
				$dd['city'] = $_POST['city'];
				$dd['district'] = $_POST['district'];
				$dd['address'] = $_POST['address'];
				if(empty($dd['province']) || empty($dd['city']) || empty($dd['district']) ||empty($dd['address'])){
					$this->jump(ADMIN_URL.'excart.php?type=checkout',0,'收货地址不能为空！'); exit ;
				}

				$dd['email'] = $_POST['email'];   //look添加
				$dd['mobile'] = $_POST['mobile'];
				$dd['is_default'] = '1';
				$dd['shoppingname'] = $shipping_id;
				$this->App->update('user_address',array('is_default'=>'0'),'user_id',$uid);
				$this->App->insert('user_address',$dd); //添加到地址表
				$userress_id  = $this->App->iid();
				
				if(!($userress_id>0)){
					$this->jump(ADMIN_URL.'excart.php?type=checkout',0,'非法的收货地址！'); exit ;
				}
			}
			
			$pay_id = isset($_POST['pay_id']) ? $_POST['pay_id'] : 0;
			$pay_name = $this->App->findvar("SELECT pay_name FROM `{$this->App->prefix()}payment` WHERE pay_id='$pay_id' LIMIT 1");

			$postscript = isset($_POST['postscript']) ? $_POST['postscript'] : "";
			//收货信息
			$sql = "SELECT * FROM `{$this->App->prefix()}user_address` WHERE address_id='$userress_id' LIMIT 1";
			$user_ress = $this->App->findrow($sql);
			if(empty($user_ress)){ $this->jump(ADMIN_URL.'excart.php?type=checkout',0,'非法收货地址！'); exit ;}
			
			$shipping_name = $this->App->findvar("SELECT shipping_name FROM `{$this->App->prefix()}shipping` WHERE shipping_id='$shipping_id' LIMIT 1");
			
			//购物车商品
			$cartlist = $this->Session->read('excart');
			if(empty($cartlist)){
				$this->jump(ADMIN_URL,0,'购物车商品为空!'); exit;
			}
			
			//添加信息到数据表
			$orderdata['order_sn']= date('Y',mktime()).mktime();
			$orderdata['user_id']= $uid ? $uid : 0;
			$orderdata['consignee'] = $user_ress['consignee'] ? $user_ress['consignee'] : "";
			$orderdata['province'] = $user_ress['province'] ? $user_ress['province'] : 0;
			$orderdata['city'] = $user_ress['city'] ? $user_ress['city'] : 0;
			$orderdata['district'] = $user_ress['district'] ? $user_ress['district'] : 0;
			$orderdata['address'] = $user_ress['address'] ? $user_ress['address'] : "";
			$orderdata['mobile']  = $user_ress['mobile'] ? $user_ress['mobile'] : "";
			$orderdata['email']  = $user_ress['email'] ? $user_ress['email'] : "";
			$orderdata['shipping_id']  = $shipping_id;
			$orderdata['shipping_name']  = $shipping_name;
			$orderdata['pay_id']  = $pay_id ? $pay_id : 0;
			$orderdata['pay_name']  = $pay_name ? $pay_name : "";
			$orderdata['postscript']  = $postscript;
			$orderdata['type']  = 2;
		
			$k=0;
			$jifen_onetotal = 0;
			foreach($cartlist as $row){
				 $data[$k]['goods_id'] = $row['goods_id'];
				 $data[$k]['brand_id'] = $row['brand_id'];
				 $data[$k]['goods_name'] = $row['goods_name'];
				 $data[$k]['goods_bianhao'] = $row['goods_bianhao'];
				 $data[$k]['goods_thumb'] = $row['goods_thumb'];
				 $data[$k]['goods_sn'] = $row['goods_sn'];
				 $data[$k]['goods_number'] = $row['number'];
				 $data[$k]['market_price'] = $row['shop_price'] > 0 ? $row['shop_price'] : $row['pifa_price']; //原始价格
				 $data[$k]['goods_price'] = '0.00'; //实际价格
				 $data[$k]['goods_attr'] = !empty($row['spec']) ? $row['goods_brief'].implode("<br />",$row['spec']) : $row['goods_brief'];
				 $data[$k]['goods_unit'] = $row['goods_unit'];

				 $data[$k]['from_jifen'] = $row['need_jifen']*$row['number'];
				 $jifen_onetotal += $row['need_jifen']*$row['number'];;
				 $k++;
			}
			
			//
			//$sql = "SELECT SUM(points) FROM `{$this->App->prefix()}user_point_change` WHERE uid='$uid'";
			//$mypoints = $this->App->findvar($sql);
			$sql = "SELECT mypoints FROM `{$this->App->prefix()}user` WHERE user_id='$uid'";
			$mypoints = $this->App->findvar($sql);
				
			if($jifen_onetotal > $mypoints){ 
				$this->jump(ADMIN_URL.'excart.php?type=checkout',0,'您的积分不足无法兑换该商品!'); exit;
			}
			$orderdata['add_time'] = mktime();
			
			if($this->App->insert('goods_order_info',$orderdata)){ //订单成功后
				$iid = $this->App->iid();
				
				if($this->App->insert('user_point_change',array('time'=>mktime(),'changedesc'=>"用积分兑换商品",'uid'=>$uid,'points'=>-intval($jifen_onetotal)))){
					//$sql = "SELECT pointnum FROM `{$this->App->prefix()}userconfig` LIMIT 1";//配置信息
					//$pointnum = $this->App->findvar($sql);
					$jifen_onetotal = -intval($jifen_onetotal);
					$sql = "UPDATE `{$this->App->prefix()}user` SET `mypoints` = `mypoints`+$jifen_onetotal WHERE user_id = '$uid'";
					$this->App->query($sql);
				}
				
				foreach($data as $kk=>$rows){
					$rows['order_id'] = $iid;
					
					$this->App->insert('goods_order',$rows);  //添加订单商品表
					
					//更新销售数量
					$gid = $rows['goods_id'];
					$num = $rows['goods_number'];
					if($gid>0 && $rows['is_gift']!='1'){
						$sql = "UPDATE `{$this->App->prefix()}goods` SET `sale_count` = `sale_count`+1 , `goods_number` = `goods_number`- '$num' WHERE goods_id = '$gid'";
						$this->App->query($sql);
					}
				}
				
				$this->jump(ADMIN_URL.'excart.php?type=pay&oid='.$iid);exit;
				
				exit;
			}else{
				$this->jump(ADMIN_URL.'excart.php',0,'您的订单没有提交成功，我们是尽快处理出现问题！'); exit;
			}
			
		}else{
			$this->App->write('excart',null);
			$this->jump(ADMIN_URL);
		}
		$this->App->write('excart',null);
		$this->jump(ADMIN_URL,0,'意外错误，我们是尽快处理出现问题！'); exit;
	}
	
	function pay(){
		$oid = isset($_GET['oid']) ? $_GET['oid'] : 0;
		if(!($oid > 0)){
			$this->jump(ADMIN_URL);exit;
		}
		$sql = "SELECT tb1.*,SUM(tb2.from_jifen) AS points  FROM `{$this->App->prefix()}goods_order_info` AS tb1 LEFT JOIN `{$this->App->prefix()}goods_order` AS tb2 ON tb1.order_id = tb2.order_id WHERE tb1.order_id='$oid' LIMIT 1";
		$orderinfo = $this->App->findrow($sql);
		if(empty($orderinfo)){
			$this->jump(ADMIN_URL);exit;
		}
		
		$sql = "SELECT * FROM `{$this->App->prefix()}goods_order` WHERE order_id='$oid' ORDER BY rec_id DESC";
		$ordergoods = $this->App->find($sql);

		$this->set('ordergoods',$ordergoods);
		$this->set('orderinfo',$orderinfo);
		if(!defined(NAVNAME)) define('NAVNAME', "成功订单");	
		$mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';
		$this->template($mb.'/exchange_order_pay');
	}
	
	//获取中文的商品属性名称
	//需要更新 请添加
	function get_goods_spec_name($key=""){
		if(empty($key)) return "空值";
		if($key=='number') return "数量";
		$cache = Import::ajincache();
		$cache->SetFunction(__FUNCTION__);
	    $cache->SetMode('product');
	    $fn = $cache->fpath(func_get_args());
	    if(file_exists($fn)&&!$cache->GetClose()){
				include($fn);
	    }
	    else{
			$sql = "SELECT attr_name, attr_keys FROM `{$this->App->prefix()}attribute`";
			$rt = $this->App->find($sql);
			$arr = array();
			if(!empty($rt)){
				foreach($rt as $row){
					$arr[$row['attr_keys']] = $row['attr_name'];
				}
			}
			$cache->write($fn, $arr,'arr');
		}
		return isset($arr[$key]) ? $arr[$key] : '空值';
	}
	
	//移除购物车
	function ajax_remove_excargoods($data=array()){
		$id = isset($data['gid'])? $data['gid']  : 0;
		if($id > 0){
			$cartlist = $this->Session->read('excart');
			if(isset($cartlist[$id])){ $this->Session->write("excart.{$id}",null);}
			unset($cartlist);
		}
		$goodslist = $this->Session->read('excart');
		if(count($goodslist)=='0'){
			echo "1";
		}else{
			echo "2";
		}
		exit;
	}
}
?>
<?php
class ProductController extends Controller{

 	function  __construct() {
		$this->js(array('jquery.json-1.3.js','goods.js?v=v17'));//将js文件放到页面头
	}
	
	/*析构函数*/
	function  __destruct() {
        unset($rt);
    }
	
	//页面查看次数统计
    function stats_view_goods_count($id=0,$return = false){
        if($id<=0) return 0;
        $sql="UPDATE `{$this->App->prefix()}goods` SET click_count=`click_count`+1 WHERE goods_id='$id'";
        $this->App->query($sql);
		if($return == true){
			$sql = "SELECT `click_count` FROM `{$this->App->prefix()}goods` WHERE goods_id='$id' LIMIT 1";
			return $this->App->findvar($sql);
		}
    }

	//根据商品id获取评论数量
	function get_comment_count($gid=0){
		$g = "";
        if($gid>0) $g = "AND id_value = '$gid'";
		/*$sql = "SELECT COUNT(c.comment_id) FROM `{$this->prefix()}comment` AS c";
		$sql .=" LEFT JOIN `{$this->prefix()}user` AS u ON u.user_id = c.user_id";
		$sql .=" WHERE c.parent_id = 0 AND c.id_value = '$gid'";*/
		$sql = "SELECT COUNT(comment_id) FROM `{$this->App->prefix()}comment`";
        $sql .=" WHERE parent_id = 0 $g AND status='1'";
		return $this->App->findvar($sql);
	}

	//根据商品id获取评论信息
	function get_comment_list($gid=0,$start=0,$list=10){
				//以下是以用户作为条件查询的评论
                /*$sql = "SELECT c.*, g.goods_name AS cmt_name, r.content AS reply_content, r.add_time AS reply_time ".
                   " FROM `{$this->App->prefix()}comment` AS c ".
                   " LEFT JOIN `{$this->App->prefix()}comment` AS r ".
                   " ON r.parent_id = c.comment_id AND r.parent_id > 0 ".
                   " LEFT JOIN `{$this->App->prefix()}goods` AS g ".
                   " ON c.id_value = g.goods_id ".
                   " WHERE c.user_id='$user_id'";*/
				 $g = "";
                 if($gid>0) $g = "AND c.id_value = '$gid'";
                 $sql = "SELECT c.*,u.avatar,u.user_name AS dbuname,u.nickname, g.goods_thumb,g.goods_name,g.goods_id FROM `{$this->App->prefix()}comment` AS c LEFT JOIN `{$this->App->prefix()}user` AS u ON c.user_id=u.user_id LEFT JOIN `{$this->App->prefix()}goods` AS g ON g.goods_id = c.id_value";
                 $sql .=" WHERE c.parent_id = 0  $g AND c.status='1' ORDER BY c.add_time DESC LIMIT $start,$list";
                 $this->App->fieldkey('comment_id');
                 $commentlist = $this->App->find($sql);
                 $rp_commentlist = array();
                 if(!empty($commentlist)){ //回复的评论
                         $commend_id  =array_keys($commentlist);
                         $sql = "SELECT c.*,a.adminname FROM `{$this->App->prefix()}comment` AS c";
                         $sql .=" LEFT JOIN `{$this->App->prefix()}admin` AS a ON a.adminid = c.user_id";
                         $sql .=" WHERE c.parent_id IN (".implode(',',$commend_id).")";
                         $this->App->fieldkey('parent_id');
                         $rp_commentlist = $this->App->find($sql);
                         foreach($commentlist as $cid=>$row){
                                $rt[$cid] = $row;
							    $rt[$cid]['goodsurl'] = get_url($row['goods_name'],$row['goods_id'],'product.php?id='.$row['goods_id'],'goods',array('product','index',$row['goods_id']));
                                $rt[$cid]['rp_comment_list'] = isset($rp_commentlist[$cid]) ? $rp_commentlist[$cid] : array();
                         }
                 }else{
                        return array();
                 }
                 return $rt;
	}
	
	//商品详情页面
	function index($gid=0){
		$this->action('common','checkjump');
		$this->css("flexslider.css");
		$this->js(array("jquery.flexslider-min.js","main.js",'time.js'));
		if(!($gid>0)){
			$this->action('common','show404tpl');
		} 

		$rt = $this->Cache->read(3600);
	 	if(is_null($rt)) {
			//商品详情信息
			$sql = "SELECT g.*,gc.*,b.brand_name,b.brand_desc FROM `{$this->App->prefix()}goods` AS g";
			$sql .=" LEFT JOIN `{$this->App->prefix()}brand` AS b ON g.brand_id = b.brand_id";
			$sql .=" LEFT JOIN `{$this->App->prefix()}goods_cate` AS gc ON gc.cat_id = g.cat_id";
			$sql .=" WHERE g.goods_id = '$gid' LIMIT 1"; 
			$rt['goodsinfo'] = $this->App->findrow($sql);
			
			if(empty($rt['goodsinfo'])){
				$this->jump(ADMIN_URL,0,'此产品已下架');exit;
				//$this->action('common','show404tpl');
			}
			if($rt['goodsinfo']['is_promote']=='1' && $rt['goodsinfo']['promote_start_date'] < mktime() && $rt['goodsinfo']['promote_end_date'] > mktime()){$rt['goodsinfo']['is_promote']=='1';}else{$rt['goodsinfo']['is_promote']=='0';}
			if($rt['goodsinfo']['is_qianggou']=='1' && $rt['goodsinfo']['qianggou_start_date'] < mktime() && $rt['goodsinfo']['qianggou_end_date'] > mktime()){$rt['goodsinfo']['is_qianggou']=='1';}else{$rt['goodsinfo']['is_qianggou']=='0';}
						
/*			if(empty($rt['goodsinfo'])){
				$this->action('common','show404tpl');
			}*/
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

			//商品评论
			$list = 3;
		    $start = 0;
			$page = 1;
		    $tt = $this->get_comment_count($gid);
		    $rt['comment_count'] =$tt;
		    $rt['commentlist'] = $this->get_comment_list($gid,$start,$list);
		    $rt['commentpage'] = Import::basic()->ajax_page($tt,$list,$page,'get_comment_page',array($gid));

			
			$sql = "SELECT * FROM `{$this->App->prefix()}userconfig` LIMIT 1";//配置信息
			$rt['config'] = $this->App->findrow($sql);
			
			$this->Cache->write($rt);
		} 
		//判断是否可以购买
		$rt['goodsinfo']['gobuy'] = true;
		if($rt['goodsinfo']['up_goods']>0){
			$uid = $this->Session->read('User.uid');
			$rank = $this->App->findvar("SELECT `user_rank` from `{$this->App->prefix()}user` where `user_id`=".$uid);
			$sql = "SELECT `order_amount` FROM `{$this->App->prefix()}goods_order_info` WHERE `user_id`=$uid AND `pay_status`=1 ORDER BY `up_goods` DESC LIMIT 1";
			$maxmoney = $this->App->findvar($sql);
			$rt['goodsinfo']['gobuy'] = true;
			switch($rank){
				case 1:
					$rt['goodsinfo']['gobuy'] = true;
					break;
				case 8:
					if($rt['goodsinfo']['up_goods']==1){	//升级产品为1时
						$rt['goodsinfo']['gobuy'] = false;
					}
					break;
				case 9:
					if($rt['goodsinfo']['up_goods']==1||$rt['goodsinfo']['up_goods']==2){
						$rt['goodsinfo']['gobuy'] = false;
					}
					break;
				case 10:
					$rt['goodsinfo']['gobuy'] = false;
					break;
			}
			$rt['goodsinfo']['pifa_price'] = $rt['goodsinfo']['pifa_price']-$maxmoney;
			if($rt['goodsinfo']['pifa_price']<0){
				$rt['goodsinfo']['pifa_price'] = 0;
			}
		}
		
		//设置页面meta
		$title = htmlspecialchars($rt['goodsinfo']['goods_name']);
		//$title = "商品详情";
		if(!defined(NAVNAME)) define('NAVNAME', $title);
		$this->title($title.' - '.$GLOBALS['LANG']['site_name']);
		$this->meta("title",$title);
		$this->meta("keywords",htmlspecialchars($rt['goodsinfo']['meta_keys']));
		$this->meta("description",htmlspecialchars($rt['goodsinfo']['meta_desc']));
		$this->set('rt',$rt);	
		$mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';
		$this->set('mubanid',$GLOBALS['LANG']['mubanid']);
		$this->template($mb.'/goods_index');
	}

	//微信分享
	function ajax_share($data=array()){
		$uid = $this->Session->read('User.uid');							
		$type = $data['type'];
		$msg = $data['msg'];
		if(!empty($msg)){
			$msgrt = explode(':',$msg);
			$msg = isset($msgrt[1]) ? trim($msgrt[1]) : '';
		}

		if($msg=='ok'){
			$date = date('Y-m-d',mktime());
			$url = $data['thisurl'];
			
			$sql = "SELECT id FROM `{$this->App->prefix()}user_share` WHERE date='$date' AND url='$url' LIMIT 1";
			$id = $this->App->findvar($sql);
			if($id > 0){
				$sql = "UPDATE `{$this->App->prefix()}user_share` SET `counts` = `counts`+1 WHERE id = '$id'";
				$this->App->query($sql);
			}else{
				$dd = array();
				$dd['uid'] = $uid;
				$dd['type'] = $type;
				$dd['url'] = $url;
				$dd['imgurl'] = $data['imgurl'];
				$dd['title'] = $data['title'];
				$dd['date'] = $date;
				$dd['time'] = mktime();
				$dd['counts'] = '1';
				$this->App->insert('user_share',$dd); 
			}
		}
	}
	
	//ajax添加到购物车
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
		$price = $goods->price;
		
		//判断是否需要关注后才能购买
		$uid = $this->Session->read('User.uid');
		$rrL = $this->action('common','get_userconfig');
		if($rrL['guanzhubuy']=='0'){
			$sql = "SELECT is_subscribe FROM `{$this->App->prefix()}user` WHERE user_id='$uid' LIMIT 1";
			$is_subscribe = $this->App->findvar($sql);
			$is_subscribe = empty($is_subscribe) ? '0' : $is_subscribe;
			if($is_subscribe=='0'){
				$result['error'] = 22;
				$result['message'] = '未关注不能购买！';
				die($json->encode($result));
			}
		}
		
		//检查每个用户可以购买多少
		$maxbuy_num = $this->App->findvar("SELECT maxbuy_num FROM `{$this->App->prefix()}top_cate_goods` WHERE goods_id='$goods_id' LIMIT 1");
		if($maxbuy_num > 0){
			if($number > $maxbuy_num){
				$result['error'] = 44;
				$result['message'] = '购买数量超过了限制！最大购买:'.$maxbuy_num.'件';
				die($json->encode($result));
			}
			//判断是否已经购买
			$sql = "SELECT SUM(go.goods_number) FROM `{$this->App->prefix()}goods_order` AS go LEFT JOIN `{$this->App->prefix()}goods_order_info` AS goi ON go.order_id = goi.order_id WHERE goi.user_id='$uid' AND go.goods_id='$goods_id' GROUP BY go.goods_id";
			$thisnum = $this->App->findvar($sql);
			if($thisnum >= $maxbuy_num){
				$result['error'] = 44;
				$result['message'] = '最大只能购买'.$maxbuy_num.'件,你的购买超过了限制！';
				die($json->encode($result));
			}
		}
		
		//检查是否是虚拟商品
		$is_virtual = $this->App->findvar("SELECT is_virtual FROM `{$this->App->prefix()}goods` WHERE goods_id='$goods_id' LIMIT 1");
		if($is_virtual=='1'){
			$result['error'] = 33;
			$result['message'] = '这是虚拟商品需另外处理';
			die($json->encode($result));
		}
		
		//处理搭配购买的商品 传送的goods_id例子：12:11|22|44
		$dapei_ids = array();
		if(strpos($goods_id,':')){ //
			$dapei_ar = explode(":",$goods_id);
			$goods_id = $dapei_ar[0];
			$dapei_ids = !empty($dapei_ar[1]) ? explode("|",$dapei_ar[1]) : array();
			unset($dapei_ar);
		}
		
		//
		$sql = "SELECT * FROM `{$this->App->prefix()}goods` WHERE goods_id='$goods_id' LIMIT 1";
		$cart = array();
		$cart = $this->App->findrow($sql);
		if(empty($cart)){ //空信息处理
			$result['error'] = 2;
			$result['message'] = '该商品的记录信息为空！';
			die($json->encode($result));
		}
		if($price>0) $cart['pifa_price'] = $price; //不同属性的价格改变
		$is_alone_sale = $cart['is_alone_sale'];
		
		###############################
		//验证是否是兑换积分===>相应减少积分
		$is_pay_jifen = false;
		$cart['is_jifen_session'] = 0; //默认积分兑换
		if($cart['is_jifen']=='1' && ($optype=='jifen' || $optype=='jifen_cartlist')){
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
				if($points>0 && $points>=$need_jifen){ //满足兑换积分的条件
					$is_pay_jifen = true;
					$cart['is_jifen_session'] = 1; 
				}else{
					$result['error'] = 2;
					$result['message'] = '当前积分为：<font color=red>'.$points.'</font>积分！<br />很抱歉，无法满足兑换该商品的条件！';
					die($json->encode($result));
				}
				
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
			$is_gift = $this->Session->read("cart.{$goods_id}");
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
		$thiscart = $this->Session->read('cart'); //读取当前购物车商品
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
                                $this->Session->write("cart.{$goods_id}",  $cart);
                            }else{  //已经存在重复项
                                $index=array_search($ty,$md5_arr);
                                list($p)=array_keys(array_slice($thiscart,$index,1 ,true));
                                if(!empty($p)){ //数量+1
                                        $this->Session->write("cart.{$p}.number",  ($thiscart[$p]['number'])+$number);
                                }
                           }
                      }else{
                            $this->Session->write("cart.{$goods_id}",  $cart);
                      }
			
		}else{ //购物车没内容
			$this->Session->write("cart.{$goods_id}",  $cart); //写入购物车
		}
		$nums = 0;
		$thiscart = $this->Session->read('cart');
		if(!empty($thiscart))foreach($thiscart as $row){
			$nums +=$row['number'];
		}
		$result = array('error' => $err, 'nums'=>$nums,'message' => '恭喜您，已成功将产品添加到购物车，继续购买请点继续选购，结帐请点前往购物车！');
		die($json->encode($result));
	}
	
	//用户添加收藏，必须先登录
	function ajax_add_tocoll($goods_id=0){
		if(empty($goods_id)||$goods_id==0){
			die('1');  //商品id为空
		}

		$userid= $this->Session->read('User.uid');
		if(empty($userid)){
			die('2');  //还没有登录
		}
		//检查是否已经存在收藏夹中，存在不能再添加了
		$sql = "SELECT goods_id FROM `{$this->App->prefix()}goods_collect` WHERE goods_id='{$goods_id}' AND user_id='$userid'";
		$returnvar = $this->App->findvar($sql);
		if(!empty($returnvar)){
			die('5');
		}
		//可以添加了
		$data['goods_id'] = $goods_id;
		$data['user_id'] = $userid;
		$data['add_time'] = mktime();
		if($this->App->insert('goods_collect',$data)){
			die('3');  //添加成功
		}else{
			die('4');  //添加失败，意外错误
		}
		exit;
	}
	
	//获取中文的商品属性名称
	//需要更新 请添加
	function get_goods_spec_name($key=""){
		if(empty($key)) return "空值";
		if($key=='number') return "数量";
		if($key=='price') return "价格";
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
	
	//商品名称
	function ajax_getgoodsname($gid){
		if($gid>0){
			$sql = "SELECT goods_name FROM `{$this->App->prefix()}goods` WHERE goods_id='$gid' LIMIT 1";
			echo $this->App->findvar($sql);
			exit;
		}
	}
	
	function ajax_submit_mes($data=array()){
		$err = 0;
		$result = array('error' => $err, 'message' => '');
		$json = Import::json();
		if (empty($data['goods']))
		{
				$result['error'] = 1;
				$result['message'] = '意外错误，传送的数据为空！';
				die($json->encode($result));
		}
		 $comments = $json->decode($data['goods']); //反json ,返回值为对象
		 
		 $ranks = $comments->ranks;
		 $content = $comments->content;
		 $pics = $comments->pics;
		 $goodsid = $comments->goods_id;
		 if (empty($content))
		 {
				$result['error'] = 1;
				$result['message'] = '内容不能为空！';
				die($json->encode($result));
		 }
		 $uid = $this->Session->read('User.uid');
		 $dd = array();
		 $dd['comment_rank'] = $ranks;
		 $dd['content'] = $content;
		 if(!empty($pics)){
		 	 $dd['pics'] = $pics;
		 }
		 $dd['id_value'] = $goodsid;
		 $dd['content'] = $content;
		 $dd['status'] = '1';
		 $dd['add_time'] = mktime();
		 $ip = Import::basic()->getip();
		 $dd['ip_address'] = $ip ? $ip : '0.0.0.0';
		 $dd['ip_form'] = Import::ip()->ipCity($ip);
		 $dd['user_id'] = intval($uid)>0 ? intval($uid) : 0;
		 $this->App->insert('comment',$dd);
		 
		 //查询评论
		 $list = 3;
                 $page = 1;
		 $start = ($page-1)*$list;		 
		 $tt = $this->get_comment_count($goodsid);
		 $rt['comment_count'] =$tt;
		 $rt['commentlist'] = $this->get_comment_list($goodsid,$start,$list);
		 $rt['commentpage'] = Import::basic()->ajax_page($tt,$list,$page,'get_comment_page',array($goodsid));
		 $this->set('rt',$rt);
		 $result['message'] = $this->fetch('ajax_comment',true);
		 die($json->encode($result));
	}
	
	//评论
	function ajax_comment($data=array(),$page=0){
		$err = 0;
		$result = array('error' => $err, 'message' => '');
		$json = Import::json();
							
                if(!($page>0)){
                    $page = 1;
                    if (empty($data['comments']))
                    {
                            $result['error'] = 1;
                            $result['message'] = '意外错误，传送的数据为空！';
                            die($json->encode($result));
                    }
                    $comments = $json->decode($data['comments']); //反json ,返回值为对象
                    $goods_id = $comments->goods_id;
					if(!(intval($goods_id)>0)){
					 		$result['error'] = 1;
                            $result['message'] = '意外错误，传送的数据为空！';
                            die($json->encode($result));
					}
					
                    //以下字段对应评论的表单页面 一定要一致
                    $datas['id_value'] = $goods_id;
                    //$datas['email'] = $comments->email;
					$username = $this->Session->read('User.username');
					$uid = $this->Session->read('User.uid');
					$error2 = false;
                    $datas['user_name'] = !empty($username) ? $username : "";
					if(empty($datas['user_name']) || !($uid>0)){ //需要登录
							$result['error'] = 4;
                            $result['message'] = '您还没有登录！请您先登录！';
                            die($json->encode($result));
					}
					
					//检查是否已经存在购买商品
					$sql = "SELECT tb1.rec_id FROM `{$this->App->prefix()}goods_order` AS tb1";
					$sql .=" LEFT JOIN `{$this->App->prefix()}goods_order_info` AS tb2 ON tb1.order_id=tb2.order_id";
					$sql .=" WHERE tb1.goods_id='$goods_id' AND tb2.user_id='$uid' AND tb2.order_status='2' AND tb2.pay_status='1'";
					$re_id = $this->App->findvar($sql);
					if(!($re_id>0)){ //不存在该记录！
						$result['error'] = 1;
                        $result['message'] = '抱歉，您还没有购买当前商品，不能评论哦！';
                        die($json->encode($result));
					}
					//检查该商品是否已经评论过
					$sql = "SELECT comment_id FROM `{$this->App->prefix()}comment` WHERE id_value='$goods_id' AND user_id='$uid' LIMIT 1";
					$comment_id = $this->App->findvar($sql);
					if($comment_id>0){ //存在该记录！
						$result['error'] = 1;
                        $result['message'] = '抱歉，您已经评论过该商品，不能再评论哦！';
                        die($json->encode($result));
					}
					
                    $datas['content'] = $comments->comment;
					if (empty($datas['content']))
                    {
                            $result['error'] = 1;
                            $result['message'] = '请填写评论内容！';
                            die($json->encode($result));
                    }
					if (strlen($datas['content'])<12)
                    {
                            $result['error'] = 1;
                            $result['message'] = '评论内容不能太少！';
                            die($json->encode($result));
                    }
					//限制用户不能重复提交评论，需要等待三分钟后才能评论
					$read_time = $this->Session->read("Comment.{$goods_id}");
					if(!empty($read_time)){
						if((mktime()-$read_time)<200){
							$result['error'] = 3;
							$result['message'] = '您刚才已经发表了评论，请您稍等下再发表！';
                            die($json->encode($result));
						}
					}
					$this->Session->write("Comment.{$goods_id}",mktime());
                    $datas['comment_rank'] = $comments->comment_rank;
					$datas['goods_rand'] = $comments->goods_rand;
					$datas['goods_rand']= empty($datas['goods_rand']) ? 5 : $datas['goods_rand'];
					$datas['shopping_rand'] = $comments->shopping_rand;
					$datas['shopping_rand']= empty($datas['shopping_rand']) ? 5 : $datas['shopping_rand'];
					$datas['saleafter_rand'] = $comments->saleafter_rand;
					$datas['saleafter_rand']= empty($datas['saleafter_rand']) ? 5 : $datas['saleafter_rand'];
					$datas['status'] = '1';
                    $datas['add_time'] = mktime();
                    $ip = Import::basic()->getip();
                    $datas['ip_address'] = $ip ? $ip : '0.0.0.0';
                    $datas['ip_form'] = Import::ip()->ipCity($ip);
                    $datas['user_id'] = intval($uid)>0 ? intval($uid) : 0;
                    $this->App->insert('comment',$datas);
					unset($datas,$data);
                }

		//查询评论
		$list = 2;
        $start = ($page-1)*$list;
		$tt = $this->get_comment_count($goods_id);
		$rt['comment_count'] =$tt;
		$rt['commentlist'] = $this->get_comment_list($goods_id,$start,$list);
		$rt['commentpage'] = Import::basic()->ajax_page($tt,$list,$page,'get_comment_page',array($goods_id));
		$this->set('rt',$rt);
		$result['message'] = $this->fetch('ajax_comment',true);
		die($json->encode($result));
	}
	
	function ajax_getcommentlist($data=array()){
		if(empty($data['goods_id'])||!(intval($data['goods_id'])>0)) die("获取数据失败，传送的商品id为空！");
		if(empty($data['page'])||!(intval($data['page'])>0)) $page=1;
		//查询评论
		$list = 3;
		$page =intval($data['page']);
		$goods_id =intval($data['goods_id']);
        $start = ($page-1)*$list;
		$tt = $this->get_comment_count($goods_id);
		$rt['comment_count'] =$tt;
		$rt['commentlist'] = $this->get_comment_list($goods_id,$start,$list);
		$rt['commentpage'] = Import::basic()->ajax_page($tt,$list,$page,'get_comment_page',array($goods_id));
		$this->set('rt',$rt);
		$con = $this->fetch('ajax_comment',true);
		die($con);
	}
	
	//ajax获取当前分类下的商品【用户商品详情页的分类】
    function ajax_categoods_list($page=0,$cid=0){
                if(empty($cid) || !($cid>0)){ die("");}
                 $cid_arr = $this->action('catalog','get_goods_sub_cat_ids',$cid);
                 $list = 5;
                 $tt = $this->App->__get_categoods_count_goods($cid_arr);
                 $rt['categoods_count'] = $tt;
                 $args = array($cid);
                 if(!$page){
                     $page = 1;
                 }
                 $start = ($page-1)*$list;
                 $rt['categoodspage'] = Import::basic()->ajax_page($tt,$list,$page,'get_categoods_page',$args);
                 $categoods = $this->App->__get_categoods_list_goods($cid_arr,$start,$list);
                 $rt['categoods'] = array();
                 if(!empty($categoods)){
                        foreach($categoods as $k=>$row){
                               // if($row['goods_id']==$gid) continue;
                                $rt['categoods'][$k] = $row;
								$rt['categoods'][$k]['goods_thumb'] =  is_file(SYS_PATH.$row['goods_thumb']) ? SITE_URL.$row['goods_thumb'] : SITE_URL.'theme/images/no_picture.gif';
								$rt['categoods'][$k]['goods_img'] =  is_file(SYS_PATH.$row['goods_img']) ? SITE_URL.$row['goods_img'] : SITE_URL.'theme/images/no_picture.gif';
								$rt['categoods'][$k]['original_img'] =  is_file(SYS_PATH.$row['original_img']) ? SITE_URL.$row['original_img'] : SITE_URL.'theme/images/no_picture.gif';
                                $rt['categoods'][$k]['url'] = get_url($row['goods_name'],$row['goods_id'],'product.php?id='.$row['goods_id'],'goods',array('product','index',$row['goods_id']));
								if($row['is_promote']=='1'){
									//促销 价格
									if($row['promote_start_date']<mktime()&&$row['promote_end_date']>mktime()){
										$row['promote_price'] = format_price($row['promote_price']);
									}else{
										$row['promote_price'] = "0.00";
									}
								}else{
									$row['promote_price'] = "0.00";
								}
								$rt[$k]['promote_price'] = $row['promote_price'];
                        }
                        unset($categoods);
                 }
                $this->set('rt',$rt);
                $result = $this->fetch('ajax_categoods',true);
                unset($rt,$categoods);
                die($result);
        }

        //ajax获取购买记录商品
	
	//end function 
}
?>
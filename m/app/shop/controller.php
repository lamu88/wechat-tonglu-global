<?php
class ShopController extends Controller{
	//构造函数，自动新建对象
 	function  __construct() {
		/*
		*构造函数，自动新建session对象
		*/
		$this->js(array('jquery.json-1.3.js','user.js?v=v1'));
	}
	
	function checked_login(){
		$uid = $this->Session->read('User.uid');
		if(!($uid>0)){ $this->jump(ADMIN_URL.'user.php?act=login'); exit;}
		return $uid;
	}
	
	function get_regions($type,$parent_id=0){
		$p = "";
		if(!empty($parent_id)) $p = "AND parent_id='$parent_id'";
		
		$sql= "SELECT region_id,region_name FROM `{$this->App->prefix()}region` WHERE region_type='$type' {$p} ORDER BY region_id ASC";
		return  $this->App->find($sql);
	}
	
	function applyshop(){
		$uid = $this->checked_login();
		$this->action('common','checkjump');
		
		$sql = "SELECT * FROM `{$this->App->prefix()}user` WHERE user_id='$uid' LIMIT 1";
		$rt['userinfo'] = $this->App->findrow($sql);
		if($rt['userinfo']['isshop']=='1'){
			$this->set('fxrank','1'); //是店铺
		}else{
			$this->set('fxrank','2'); //申请店铺
		}
		
		$rt['province'] = $this->get_regions(1);  //获取省列表
		
		//当前用户的收货地址
		$sql = "SELECT * FROM `{$this->App->prefix()}user_address` WHERE user_id='$uid' AND is_own='1' LIMIT 1";
		$rt['userress'] = $this->App->findrow($sql);

		if($rt['userress']['province']>0) $rt['city'] = $this->get_regions(2,$rt['userress']['province']);  //城市
		if($rt['userress']['city']>0) $rt['district'] = $this->get_regions(3,$rt['userress']['city']);  //区	
		
		$s = $fxrank!='1'? '申请开店' : '编辑资料';
		$this->title($s);
		
		$this->set('rt',$rt);
		if(!defined(NAVNAME)) define('NAVNAME', $s);
		$mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';
		$this->template($mb.'/v3_applyshop');
	}
	
	function ajax_update_shopinfo($data= array()){
		$json = Import::json();
		$uid = $this->checked_login();
		if(empty($uid)){
			$result = array('error' => 3, 'message' => '先您先登录!');
			die($json->encode($result));
		}
		
		$result = array('error' => 2, 'message' => '传送的数据为空!');
		if(empty($data['fromAttr']))  die($json->encode($result));
		
		$fromAttr = $json->decode($data['fromAttr']); //反json ,返回值为对象
		unset($data);
		
		
		$datas['isshop'] = '2';
		$datas['email'] = $fromAttr->email;
		if(empty($datas['email'])){
			$result = array('error' => 4, 'message' => '请填写正确邮箱！');
			die($json->encode($result));
		}
		$datas['mobile_phone'] = $fromAttr->mobile_phone;
		if(empty($datas['mobile_phone'])){
			$result = array('error' => 4, 'message' => '请填写手机号码！');
			die($json->encode($result));
		}
		//检测该号码是否存在
		$mb = $datas['mobile_phone'];
		$sql = "SELECT user_id FROM `{$this->App->prefix()}user` WHERE mobile_phone = '$mb' AND user_id!='$uid' LIMIT 1";
		$id = $this->App->findvar($sql);
		if($id >0){
			$result = array('error' => 4, 'message' => '该手机号码已经被使用！');
			die($json->encode($result));
		}
		
		$datas['msn'] = $fromAttr->msn; //微信号
		if(empty($datas['msn'])){
			$result = array('error' => 4, 'message' => '请填写微信号！');
			die($json->encode($result));
		}
		$ni = $fromAttr->consignee;
		if(empty($ni)){
			$result = array('error' => 4, 'message' => '请填写真实姓名！');
			die($json->encode($result));
		}
		$this->App->update('user',$datas,'user_id',$uid);
		unset($datas);
			
		$sql = "SELECT address_id FROM `{$this->App->prefix()}user_address` WHERE user_id='$uid' AND is_own='1' LIMIT 1";
		$rsid = $this->App->findvar($sql);
	
		$datas['user_id'] = $uid;
		$datas['email'] = $fromAttr->email;
		$datas['mobile'] = $fromAttr->mobile_phone;
		$datas['consignee'] = $ni;
		$datas['province'] = $fromAttr->province;
		$datas['city'] = $fromAttr->city;
		$datas['district'] = $fromAttr->district;
		$datas['address'] = $fromAttr->address;
		if(!($datas['province'] > 0) || !($datas['city'] > 0) || !($datas['district'] > 0) || empty($datas['address'])){
			$result = array('error' => 4, 'message' => '请填写必要区域地址！');
			die($json->encode($result));
		}
		$datas['is_own'] = 1;
		
		if($rsid > 0){ //更新
			$this->App->update('user_address',$datas,'address_id',$rsid);
		}else{ //添加
			$this->App->insert('user_address',$datas);			
		}
		
		$result = array('error' => 4, 'message' => '操作成功！');
		die($json->encode($result));
			
		
		unset($datas);		
	}//end function
	
	//获取用户的openid
	function get_openid_AND_pay_info(){
		$wecha_id = $this->Session->read('User.wecha_id');
		if(empty($wecha_id)) $wecha_id = isset($_COOKIE[CFGH.'USER']['UKEY']) ? $_COOKIE[CFGH.'USER']['UKEY'] : '';
		
		//
		$order_sn = isset($_GET['order_sn']) ? $_GET['order_sn'] : '';
		if(empty($order_sn)) exit;
		
		$sql = "SELECT * FROM `{$this->App->prefix()}cx_baoming_order` WHERE pay_status = '0' AND order_sn='$order_sn' LIMIT 1";
		$rt = $this->App->findrow($sql);
		if(empty($rt)){
			$this->jump(str_replace('/wxpay','',ADMIN_URL),0,'非法支付提交！'); exit;
		}
		if($rt['pay_status']=='1'){
			$this->jump(str_replace('/wxpay','',ADMIN_URL));exit;
		}
		$rt['openid'] = $wecha_id;
		$rt['body'] = $GLOBALS['LANG']['site_name'].'-在线报名';
		return $rt;
	}
	
	function confirmpay($data=array()){
		if(!empty($_POST)){
			$uname = $_POST['uname'];
			$upne = $_POST['upne'];
			$price = $_POST['price'];
			$ids = $_POST['ids'];
			if(empty($uname)||empty($upne)||empty($price)||empty($ids)){
				exit;
			}
			$uid = $this->checked_login();
			$on = date('Y',mktime()).mktime();
			$dd = array();
			$dd['bid'] = $ids;
			$dd['order_sn'] = $on;
			$dd['user_id'] = $uid;
			$dd['order_amount'] = $price;
			$dd['uname'] = $uname;
			$dd['upne'] = $upne;
			$dd['add_time'] = mktime();
			
			if($this->App->insert('cx_baoming_order',$dd)){
				$this->jump(ADMIN_URL.'wxpay/js_api_call.php?order_sn='.$on.'&bm=baoming');
				exit;	
			}else{
				$this->jump(ADMIN_URL,0,'意外错误');
				exit;
			}
		}
		
	}
	
	//报名
	function baoming($data=array()){
		$this->action('common','checkjump');
	 
		$id = isset($_GET['id']) ? $_GET['id'] : 0;
	 	$t = Common::_return_px();
	    $cache = Import::ajincache();
	    $cache->SetFunction(__FUNCTION__);
	    $cache->SetMode('page'.$t);
	    $fn = $cache->fpath(array('0'=>$id));
	    if(file_exists($fn)&&!$cache->GetClose()){
				include($fn);
	    }
	    else
	    {
			$s = '';
			if($id > 0) $s = "WHERE id='$id'";
			$sql = "SELECT * FROM `{$this->App->prefix()}cx_baoming` {$s} ORDER BY id DESC LIMIT 1";
			$rt['pinfo'] = $this->App->findrow($sql);
			
			$cache->write($fn, $rt,'rt');
		}
		
		$uid = $this->Session->read('User.uid');
		$rt['tjr']['nickname'] = '[官网]';
		$rt['tjr']['headimgurl'] = ADMIN_URL.'images/uclicon.jpg';
		$rt['uinfo'] = array();
		if($uid > 0){
			$sql = "SELECT tb1.nickname,tb1.headimgurl FROM `{$this->App->prefix()}user` AS tb1 LEFT JOIN `{$this->App->prefix()}user_tuijian` AS tb2 ON tb1.user_id = tb2.parent_uid LEFT JOIN `{$this->App->prefix()}user` AS tb3 ON tb3.user_id = tb2.uid WHERE tb3.user_id='$uid' LIMIT 1";
			$rt['tjr'] = $this->App->findrow($sql);
			if(empty($rt['tjr'])){
					$rt['tjr']['nickname'] = '[官网]';
					$rt['tjr']['headimgurl'] = ADMIN_URL.'images/uclicon.jpg';
			}
			$sql = "SELECT * FROM `{$this->App->prefix()}user` WHERE user_id='$uid' LIMIT 1";
			$rt['uinfo'] = $this->App->findrow($sql);
			if(!empty($rt['uinfo']['headimgurl'])){
				$rt['tjr']['headimgurl'] = $rt['uinfo']['headimgurl'];
			}
		}
		
		$this->title($rt['pinfo']['title']);
		
		$this->set('rt',$rt);
		$mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';
		$this->set('mubanid',$GLOBALS['LANG']['mubanid']);
		$this->template($mb.'/v3_baoming');
	}
	//积分充值
	function paypoint($data=array()){
		$this->action('common','checkjump');
		$this->title('积分充值');
		$mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';
		$this->set('mubanid',$GLOBALS['LANG']['mubanid']);
		$this->template($mb.'/paypoint');
	}
	//积分充值----------pay
	function jfconfirmpay($data=array()){
		if(!empty($_POST)){
			$Cmoney = $_POST['Cmoney'];
			if(empty($Cmoney)){
				exit;
			}
			$uid = $this->checked_login();
			$on = date('Y',mktime()).mktime();
			$dd = array();
			$dd['order_sn'] = $on;
			$dd['user_id'] = $uid;
			$dd['order_amount'] = $Cmoney;
			$dd['add_time'] = mktime();
			//print_r($dd);
			//exit;
			if($this->App->insert('point_order',$dd)){
				$this->jump(ADMIN_URL.'wxpay/js_api_call.php?order_sn='.$on.'&paypoint=paypoint');
				exit;	
			}else{
				$this->jump(ADMIN_URL,0,'意外错误，联系管理');
				exit;
			}
		}
	}
	
	//佣金充值
	function paymoney($data=array()){
		$this->action('common','checkjump');
		$this->title('佣金充值');
		$mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';
		$this->set('mubanid',$GLOBALS['LANG']['mubanid']);
		$this->template($mb.'/paymoney');
	}
	//佣金充值----------pay
	function moneyconfirmpay($data=array()){
		if(!empty($_POST)){
			$Cmoney = $_POST['Cmoney'];
			if(empty($Cmoney)){
				exit;
			}
			$uid = $this->checked_login();
			$on = date('Y',mktime()).mktime();
			$dd = array();
			$dd['order_sn'] = $on;
			$dd['user_id'] = $uid;
			$dd['order_amount'] = $Cmoney;
			$dd['add_time'] = mktime();
			//print_r($dd);
			//exit;
			if($this->App->insert('money_order',$dd)){
				$this->jump(ADMIN_URL.'wxpay/js_api_call.php?order_sn='.$on.'&paymoney=paymoney');
				exit;
			}else{
				$this->jump(ADMIN_URL,0,'意外错误，联系管理');
				exit;
			}
		}
	}
	//获取用户的openid-------积分
	function get_openid_AND_pay_info_jf(){
		$wecha_id = $this->Session->read('User.wecha_id');
		if(empty($wecha_id)) $wecha_id = isset($_COOKIE[CFGH.'USER']['UKEY']) ? $_COOKIE[CFGH.'USER']['UKEY'] : '';
		
		//
		$order_sn = isset($_GET['order_sn']) ? $_GET['order_sn'] : '';
		if(empty($order_sn)) exit;
		
		$sql = "SELECT * FROM `{$this->App->prefix()}point_order` WHERE pay_status = '0' AND order_sn='$order_sn' LIMIT 1";
		$rt = $this->App->findrow($sql);
		if(empty($rt)){
			$this->jump(str_replace('/wxpay','',ADMIN_URL),0,'非法支付提交！'); exit;
		}
		if($rt['pay_status']=='1'){
			$this->jump(str_replace('/wxpay','',ADMIN_URL));exit;
		}
		$rt['openid'] = $wecha_id;
		$rt['body'] = $GLOBALS['LANG']['site_name'].'-积分充值';
		return $rt;
	}
	
	//获取用户的openid-------佣金
	function get_openid_AND_pay_info_money(){
		$wecha_id = $this->Session->read('User.wecha_id');
		if(empty($wecha_id)) $wecha_id = isset($_COOKIE[CFGH.'USER']['UKEY']) ? $_COOKIE[CFGH.'USER']['UKEY'] : '';

		//
		$order_sn = isset($_GET['order_sn']) ? $_GET['order_sn'] : '';
		if(empty($order_sn)) exit;
		
		$sql = "SELECT * FROM `{$this->App->prefix()}money_order` WHERE pay_status = '0' AND order_sn='$order_sn' LIMIT 1";
		$rt = $this->App->findrow($sql);
		if(empty($rt)){
			$this->jump(str_replace('/wxpay','',ADMIN_URL),0,'非法支付提交！'); exit;
		}
		if($rt['pay_status']=='1'){
			$this->jump(str_replace('/wxpay','',ADMIN_URL));exit;
		}
		$rt['openid'] = $wecha_id;
		$rt['body'] = $GLOBALS['LANG']['site_name'].'-佣金充值';
		return $rt;
	}
	//佣金 转 积分
	function moneytopoint(){
		$uid = $this->checked_login();
		if($_POST['AccId']){
			$AccId = $_POST['AccId'];
			$MoneyNum = $_POST['MoneyNum'];
			//查找当前用户余额够不够
			$sql = "SELECT mymoney FROM `{$this->App->prefix()}user` WHERE user_id = '$uid' LIMIT 1";
			$mymoney = $this->App->findvar($sql);
			if($mymoney<$MoneyNum){
				echo "<script>alert('您的佣金不够了，还有".$mymoney."元')</script>";
				echo "<script>window.location.href='';</script>";
				exit();
			}
			//查找是否有该用户
			$sql = "SELECT user_id FROM `{$this->App->prefix()}user` WHERE user_id = '$AccId' LIMIT 1";
			$AccId = $this->App->findvar($sql);
			if(!$AccId){
				echo "<script>alert('没有找到您要转的用户ID')</script>";
				echo "<script>window.location.href='';</script>";
				exit();
			}
			
			//增加积分
			$points = $MoneyNum*100;
			
			//扣掉转账人佣金
			$sql = "UPDATE `{$this->App->prefix()}user` SET `mymoney`=`mymoney`-$MoneyNum WHERE `user_id` = $uid";
			$this->App->query($sql);
			$arr = array('time'=>mktime(),'changedesc'=>'佣金转积分','money'=>-$MoneyNum,'thismonth'=>date('Y-m-d',mktime()),'uid'=>$uid);
			$this->App->insert('user_money_change',$arr);
			
			$arr2 = array('time'=>mktime(),'changedesc'=>'佣金转入积分，接收人ID-'.$AccId,'accid'=>$AccId,'points'=>$points,'thismonth'=>date('Y-m-d',mktime()),'uid'=>$uid,'type'=>'moneytopoint');
			$this->App->insert('user_point_change',$arr2);
			
			$sql = "UPDATE `{$this->App->prefix()}user` SET `mypoints`=`mypoints`+$points WHERE `user_id` = $AccId";
			//exit($sql);
			$this->App->query($sql);
			echo "<script>alert('转账成功！')</script>";
			echo "<script>window.location.href='/m/user.php';</script>";
			exit();
		}
		$this->action('common','checkjump');
		$this->title('佣金转积分');
		$mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';
		$this->set('mubanid',$GLOBALS['LANG']['mubanid']);
		$this->template($mb.'/moneytopoint');
	}

	//佣金 转 购物币
	function moneytogouwubi(){
		$uid = $this->checked_login();
		if($_POST['AccId']){
			$AccId = $_POST['AccId'];
			$MoneyNum = $_POST['MoneyNum'];
			//查找当前用户余额够不够
			$sql = "SELECT mymoney FROM `{$this->App->prefix()}user` WHERE user_id = '$uid' LIMIT 1";
			$mymoney = $this->App->findvar($sql);
			if($mymoney<$MoneyNum){
				echo "<script>alert('您的佣金不够了，还有".$mymoney."元')</script>";
				echo "<script>window.location.href='';</script>";
				exit();
			}
			//查找是否有该用户
			$sql = "SELECT user_id FROM `{$this->App->prefix()}user` WHERE user_id = '$AccId' LIMIT 1";
			$AccId = $this->App->findvar($sql);
			if(!$AccId){
				echo "<script>alert('没有找到您要转的用户ID')</script>";
				echo "<script>window.location.href='';</script>";
				exit();
			}
			
			//扣掉转账人佣金
			$sql = "UPDATE `{$this->App->prefix()}user` SET `mymoney`=`mymoney`-$MoneyNum WHERE `user_id` = $uid";
			$this->App->query($sql);
			$arr = array('time'=>mktime(),'changedesc'=>'佣金转购物币','money'=>-$MoneyNum,'thismonth'=>date('Y-m-d',mktime()),'uid'=>$uid);
			$this->App->insert('user_money_change',$arr);
			
			$arr2 = array('time'=>mktime(),'changedesc'=>'佣金转购物币，接收人ID-'.$AccId,'gouwubi'=>$MoneyNum,'uid'=>$uid,'accid'=>$AccId);
			$this->App->insert('moneytogouwubi',$arr2);
			
			$sql = "UPDATE `{$this->App->prefix()}user` SET `mygouwubi`=`mygouwubi`+$MoneyNum WHERE `user_id` = $AccId";
			//exit($sql);
			$this->App->query($sql);
			echo "<script>alert('转账成功！')</script>";
			echo "<script>window.location.href='/m/paypoint.php?act=moneytogouwubi_log';</script>";
			exit();
		}
		
		$this->action('common','checkjump');
		$this->title('佣金转购物币');
		$mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';
		$this->set('mubanid',$GLOBALS['LANG']['mubanid']);
		$this->template($mb.'/moneytogouwubi');
	}
	
	//佣金转购物币记录
	function moneytogouwubi_log(){
   		$this->title("佣金转购物币记录");
   		$uid = $this->checked_login();
		
		$sql = "SELECT `mygouwubi` FROM `{$this->App->prefix()}user` WHERE user_id='$uid'";
		$rt['mygouwubi'] = $this->App->findvar($sql);
   		//分页
		$page = isset($_GET['page'])&&intval($_GET['page'])>0 ? intval($_GET['page']) : 1;
		if(empty($page)){
			   $page = 1;
		}
		$list = 50 ; //每页显示多少个
		$start = ($page-1)*$list;
		$tt = $this->App->findvar("SELECT COUNT(cid) FROM `{$this->App->prefix()}moneytogouwubi` WHERE uid='$uid' OR accid=$uid");
		$rt['pages'] = Import::basic()->getpage($tt,$list,$page,'?page=',true);
		$sql = "SELECT * FROM `{$this->App->prefix()}moneytogouwubi` WHERE `uid`=$uid OR `accid`=$uid ORDER BY time DESC LIMIT $start,$list";
		$rt['lists'] = $this->App->find($sql); //商品列表
		$rt['page'] = $page;
		
		//商品分类列表		
		//$rt['menu'] = $this->action('catalog','get_goods_cate_tree');
		$this->set('uid',$uid);	
		$this->set('rt',$rt);
		
		//ajax
		/**
		if(isset($_GET['type'])&&$_GET['type']=='ajax'){
			echo  $this->fetch('ajax_user_pointchange',true);
			exit;
		}
		**/
		if(!defined(NAVNAME)) define('NAVNAME', "我的购物币");
		$mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';
		$this->template($mb.'/moneytogouwubi_log');
	}
	
	//积分转账
	function zhuanpoint(){
		$uid = $this->checked_login();
		if($_POST['AccId']){
			$AccId = $_POST['AccId'];
			$PointNum = $_POST['PointNum'];
			if($PointNum<50||($PointNum%50!=0)) exit('不是50倍数');
			//查找当前用户余额够不够
			$sql = "SELECT mypoints FROM `{$this->App->prefix()}user` WHERE user_id = '$uid' LIMIT 1";
			$mypoints = $this->App->findvar($sql);
			if($mypoints<$PointNum){
				echo "<script>alert('您的积分不够了，还有".$mypoints."个')</script>";
				echo "<script>window.location.href='';</script>";
				exit();
			}
			//查找是否有该用户
			$sql = "SELECT user_id FROM `{$this->App->prefix()}user` WHERE user_id = '$AccId' LIMIT 1";
			$AccId = $this->App->findvar($sql);
			if(!$AccId){
				echo "<script>alert('没有找到您要转的用户ID')</script>";
				echo "<script>window.location.href='';</script>";
				exit();
			}
			
			//扣掉转账人积分
			$sql = "UPDATE `{$this->App->prefix()}user` SET `mypoints`=`mypoints`-$PointNum WHERE `user_id` = $uid";
			$this->App->query($sql);
			$arr = array('time'=>mktime(),'changedesc'=>'积分转购物币，接收人ID-'.$AccId,'accid'=>$AccId,'points'=>-$PointNum,'thismonth'=>date('Y-m-d',mktime()),'uid'=>$uid,'type'=>'pointtogouwubi');
			$this->App->insert('user_point_change',$arr);
			//增加购物币
			$gouwubi = $PointNum/100;
			$sql = "UPDATE `{$this->App->prefix()}user` SET `mygouwubi`=`mygouwubi`+$gouwubi WHERE `user_id` = $AccId";
			//exit($sql);
			$this->App->query($sql);
			echo "<script>alert('转账成功！')</script>";
			echo "<script>window.location.href='/m/user.php';</script>";
			exit();
		}
		$this->action('common','checkjump');
		$this->title('积分转账');
		$mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';
		$this->set('mubanid',$GLOBALS['LANG']['mubanid']);
		$this->template($mb.'/zhuanpoint');
	}

	
	
}



?>
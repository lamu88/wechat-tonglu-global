<?php

//decode by QQ:270656184 http://www.yunlu99.com/
class ShoppingController extends Controller
{
	function __construct()
	{
		$this->js(array('jquery.json-1.3.js', 'goods.js', 'user.js'));
		$this->css(array('comman.css'));
	}

	function __destruct()
	{
		unset($rt);
	}

	function ajax_get_address($data = array())
	{
		$province = $data['province'];
		$city = $data['city'];
		$district = $data['district'];
		$resslist = $this->action('user', 'get_regions', 1);
		$dbress = array();
		if ($province > 0) {
			$dbress['city'] = $this->action('user', 'get_regions', 2, $province);
		}
		if ($city > 0) {
			$dbress['district'] = $this->action('user', 'get_regions', 3, $city);
		}
		$dbtype['province'] = $province;
		$dbtype['city'] = $city;
		$dbtype['district'] = $district;
		$this->set('dbtype', $dbtype);
		$this->set('dbress', $dbress);
		$this->set('resslist', $resslist);
		$this->set('goods_id', $data['gid']);
		echo $this->fetch('addressmore', true);
		exit;
	}

	function ajax_jisuanprice($data = array())
	{
		$sql = "SELECT * FROM `{$this->App->prefix()}userconfig` LIMIT 1";
		$rts = $this->App->findrow($sql);
		$gid = $data['gid'];
		$num = $data['num'];
		$goodslist = $this->Session->read('cart');
		$shop_price = $goodslist[$gid]['shop_price'];
		$pifa_price = $goodslist[$gid]['pifa_price'];
		$issubscribe = $this->Session->read('User.subscribe');
		$guanzhuoff = $rts['guanzhuoff'];
		$address3off = $rts['address3off'];
		$address2off = $rts['address2off'];
		if ($issubscribe == '1' && $guanzhuoff < 101 && $guanzhuoff > 0) {
			$pifa_price = ($guanzhuoff / 100) * $pifa_price;
		}
		if ($num >= 2 && $address2off < 101 && $address2off > 0) {
			$pifa_price = ($address2off / 100) * $pifa_price;
		}
		if ($num >= 3 && $address3off < 101 && $address3off > 0) {
			$pifa_price = ($address3off / 100) * $pifa_price;
		}
		echo $pifa_price;
		exit;
	}

	function confirm_daigou()
	{
		$uid = $this->Session->read('User.uid');
		if (empty($uid)) {
			$this->jump(ADMIN_URL . 'user.php?act=login', 0, '请先登录！');
			exit;
		}
		$order_sn = date('Y', mktime()) . mktime();
		if (isset($_POST) && !empty($_POST)) {
			$totalprice = $_POST['totalprice'];
			if ($totalprice < 0) {
				$this->jump(ADMIN_URL, 0, '非法提交');
				exit;
			}
			$pay_id = $_POST['pay_id'];
			$pay_name = $this->App->findvar("SELECT pay_name FROM `{$this->App->prefix()}payment` WHERE pay_id='$pay_id' LIMIT 1");
			$shipping_id = $_POST['shipping_id'];
			$shipping_name = $this->App->findvar("SELECT shipping_name FROM `{$this->App->prefix()}shipping` WHERE shipping_id='$shipping_id' LIMIT 1");
			$postscript = $_POST['postscript'];
			$goodslist = $this->Session->read('cart');
			if (empty($goodslist)) {
				$this->jump(ADMIN_URL, 0, '购物车为空');
				exit;
			}
			$orderdata = array();
			$orderdata['pay_id'] = $pay_id;
			$orderdata['shipping_id'] = $shipping_id;
			$orderdata['pay_name'] = $pay_name;
			$orderdata['shipping_name'] = $shipping_name;
			$orderdata['order_sn'] = $order_sn;
			$orderdata['user_id'] = $uid;
			$parent_uid = $this->App->findvar("SELECT parent_uid FROM `{$this->App->prefix()}user_tuijian` WHERE uid='$uid' LIMIT 1");
			$orderdata['parent_uid'] = $parent_uid > 0 ? $parent_uid : '0';
			$orderdata['postscript'] = $postscript;
			$orderdata['goods_amount'] = $totalprice;
			$orderdata['order_amount'] = $totalprice;
			$orderdata['add_time'] = mktime();
			$this->App->insert('goods_order_info_daigou', $orderdata);
			$orderid = $this->App->iid();
			if ($orderid > 0) foreach ($goodslist as $row) {
				$gid = $row['goods_id'];
				$consignees = $_POST['consignee'][$gid];
				$numbers = $_POST['goods_number'][$gid];
				$moblies = $_POST['moblie'][$gid];
				$provinces = $_POST['province'][$gid];
				$citys = $_POST['city'][$gid];
				$districts = $_POST['district'][$gid];
				$addresss = $_POST['address'][$gid];
				if (empty($consignees)) continue;
				$ds = array();
				$ds['order_id'] = $orderid;
				$ds['goods_id'] = $gid;
				$ds['brand_id'] = $row['brand_id'];
				$ds['goods_name'] = $row['goods_name'];
				$ds['goods_thumb'] = $row['goods_thumb'];
				$ds['goods_bianhao'] = $row['goods_bianhao'];
				$ds['goods_unit'] = $row['goods_unit'];
				$ds['goods_sn'] = $row['goods_sn'];
				$ds['market_price'] = $row['shop_price'];
				$ds['goods_price'] = $row['pifa_price'];
				if (!empty($row['spec'])) $ds['goods_attr'] = implode("、", $row['spec']);
				$this->App->insert('goods_order_daigou', $ds);
				$rec_id = $this->App->iid();
				if ($rec_id > 0) {
					foreach ($consignees as $k => $consignee) {
						$dd = array();
						$dd['consignee'] = $consignee;
						$dd['goods_number'] = $numbers[$k];
						$dd['moblie'] = $moblies[$k];
						$dd['province'] = $provinces[$k];
						$dd['city'] = $citys[$k];
						$dd['district'] = $districts[$k];
						$dd['address'] = $addresss[$k];
						$dd['rec_id'] = $rec_id;
						$this->App->insert('goods_order_address', $dd);
					}
				}
			}
		}
		$this->Session->write('cart', null);
		$this->jump(ADMIN_URL . 'mycart.php?type=pay&oid=' . $orderid);
		exit;
		exit;
	}

	function pay()
	{
		$this->action('common', 'checkjump');
		if (!defined(NAVNAME)) define("NAVNAME", "在线支付");
		$oid = isset($_GET['oid']) ? $_GET['oid'] : 0;
		if (!($oid > 0)) {
			$this->jump(ADMIN_URL);
			exit;
		}
		$sql = "SELECT * FROM `{$this->App->prefix()}goods_order_info_daigou` WHERE order_id='$oid' LIMIT 1";
		$orderinfo = $this->App->findrow($sql);
		if (empty($orderinfo)) {
			$this->jump(ADMIN_URL);
			exit;
		}
		$sql = "SELECT tb1.*,SUM(tb2.goods_number) AS numbers FROM `{$this->App->prefix()}goods_order_daigou` AS tb1 LEFT JOIN `{$this->App->prefix()}goods_order_address` AS tb2 ON tb1.rec_id = tb2.rec_id WHERE tb1.order_id='$oid' GROUP BY tb2.rec_id";
		$ordergoods = $this->App->find($sql);
		$this->set('ordergoods', $ordergoods);
		$this->set('orderinfo', $orderinfo);
		$this->template('order_pay');
	}

	function pay2()
	{
		$this->action('common', 'checkjump');
		if (!defined(NAVNAME)) define("NAVNAME", "在线支付");
		$oid = isset($_GET['oid']) ? $_GET['oid'] : 0;
		if (!($oid > 0)) {
			$this->jump(ADMIN_URL);
			exit;
		}
		$sql = "SELECT tb1.*,tb2.region_name AS province,tb3.region_name AS city,tb4.region_name AS district FROM `{$this->App->prefix()}goods_order_info` AS tb1";
		$sql .= " LEFT JOIN `{$this->App->prefix()}region` AS tb2 ON tb2.region_id = tb1.province";
		$sql .= " LEFT JOIN `{$this->App->prefix()}region` AS tb3 ON tb3.region_id = tb1.city";
		$sql .= " LEFT JOIN `{$this->App->prefix()}region` AS tb4 ON tb4.region_id = tb1.district";
		$sql .= " WHERE tb1.order_id='$oid'";
		$rt['orderinfo'] = $this->App->findrow($sql);
		if (empty($rt['orderinfo'])) {
			$this->jump(ADMIN_URL);
			exit;
		}
		$sql = "SELECT * FROM `{$this->App->prefix()}goods_order` WHERE order_id='$oid' ORDER BY goods_id";
		$rt['goodslist'] = $this->App->find($sql);
		$uid = $this->Session->read('User.uid');
		if ($uid > 0) {
			$sql = "SELECT mygouwubi FROM `{$this->App->prefix()}user` WHERE user_id='$uid' LIMIT 1";
			$rt['mygouwubi'] = $this->App->findvar($sql);
		} else {
			$rt['mygouwubi'] = 0;
		}
		$sql = "SELECT * FROM `{$this->App->prefix()}payment` WHERE enabled='1'";
		$rt['paymentlist'] = $this->App->find($sql);
		$this->set('rt', $rt);
		$mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';
		$this->template($mb . '/shopping_order_pay');
	}

	function ajax_update_payid($rt = array())
	{
		$payid = $rt['payid'];
		$oid = $rt['oid'];
		if ($payid > 0 && $oid > 0) {
			$pay_name = $this->App->findvar("SELECT pay_name FROM `{$this->App->prefix()}payment` WHERE pay_id='$payid' LIMIT 1");
			$this->App->update('goods_order_info', array('pay_id' => $payid, 'pay_name' => $pay_name), 'order_id', $oid);
		}
	}

	function fastpay()
	{
		$oid = isset($_GET['oid']) ? $_GET['oid'] : 0;
		if (!($oid > 0)) {
			$this->jump(ADMIN_URL, 0, '意外错误');
			exit;
		}
		$uid = $this->Session->read('User.uid');
		$sql = "SELECT * FROM `{$this->App->prefix()}goods_order_info_daigou` WHERE pay_status = '0' AND order_id='$oid' LIMIT 1";
		$rt = $this->App->findrow($sql);
		if (empty($rt)) {
			$this->jump(ADMIN_URL, 0, '非法支付提交！');
			exit;
		}
		$rts['pay_id'] = $rt['pay_id'];
		$rts['order_sn'] = $rt['order_sn'];
		$rts['order_amount'] = $rt['order_amount'];
		$rts['logistics_fee'] = $rt['shipping_fee'];
		$userredd = $this->App->findrow("SELECT * FROM `{$this->App->prefix()}systemconfig` WHERE type='basic' LIMIT 1");
		$rts['address'] = $userredd['company_url'];
		$this->_alipayment($rts);
		unset($rt);
		exit;
	}

	function fastpay2()
	{
		$oid = isset($_GET['oid']) ? $_GET['oid'] : 0;
		if (!($oid > 0)) {
			$this->jump(ADMIN_URL, 0, '意外错误');
			exit;
		}
		$uid = $this->Session->read('User.uid');
		$sql = "SELECT * FROM `{$this->App->prefix()}goods_order_info` WHERE pay_status = '0' AND order_id='$oid' LIMIT 1";
		$rt = $this->App->findrow($sql);
		if (empty($rt)) {
			$this->jump(ADMIN_URL, 0, '非法支付提交！');
			exit;
		}
		$rts['pay_id'] = $rt['pay_id'];
		$rts['order_sn'] = $rt['order_sn'];
		$rts['order_amount'] = $rt['order_amount'];
		$rts['logistics_fee'] = $rt['shipping_fee'];
		$userredd = $this->App->findrow("SELECT * FROM `{$this->App->prefix()}systemconfig` WHERE type='basic' LIMIT 1");
		$rts['address'] = $userredd['company_url'];
		$this->_alipayment($rts);
		unset($rt);
		exit;
	}

	function ajax_remove_cargoods($data = array())
	{
		$gid = $data['gid'];
		$uid = $this->Session->read('User.uid');
		if (!empty($gid)) {
			$cartlist = $this->Session->read('cart');
			if (isset($cartlist[$gid])) {
				$this->Session->write("cart.{$gid}", null);
			}
			$useradd = $this->Session->read('useradd');
			if (isset($useradd[$gid])) {
				$this->Session->write("useradd.{$gid}", null);
			}
		}
		$sql = "SELECT * FROM `{$this->App->prefix()}userconfig` LIMIT 1";
		$rts = $this->App->findrow($sql);
		$sql = "SELECT is_subscribe FROM `{$this->App->prefix()}user` WHERE user_id='$uid' LIMIT 1";
		$issubscribe = $this->App->findvar($sql);
		$guanzhuoff = $rts['guanzhuoff'];
		$address3off = $rts['address3off'];
		$address2off = $rts['address2off'];
		$prices = 0;
		$cartlist = $this->Session->read('cart');
		$off = 1;
		if ($issubscribe == '1' && $guanzhuoff < 101 && $guanzhuoff > 0) {
			$off = ($guanzhuoff / 100);
		}
		$counts = 0;
		foreach ($cartlist as $k => $row) {
			$counts += $row['number'];
		}
		if ($issubscribe == '1' && $counts >= 2 && $address2off < 101 && $address2off > 0) {
			$off = ($address2off / 100) * $off;
		}
		if ($issubscribe == '1' && $counts >= 3 && $address3off < 101 && $address3off > 0) {
			$off = ($address3off / 100) * $off;
		}
		foreach ($cartlist as $k => $row) {
			$prices += format_price($row['pifa_price'] * $off) * $row['number'];
		}
		echo format_price($prices);
	}

	function _get_payinfo($id = 0)
	{
		if ($id == '4') {
			$rt = $this->App->findrow("SELECT `pay_config` FROM `" . $this->App->prefix() . "payment` WHERE `pay_id`='$id' LIMIT 1");
			$sql = "SELECT appid,appsecret FROM `{$this->App->prefix()}wxuserset` ORDER BY id DESC LIMIT 1";
			$rts = $this->App->findrow($sql);
			$rt['appid'] = $rts['appid'];
			$rt['appsecret'] = $rts['appsecret'];
		} else {
			$rt = $this->App->findvar("SELECT `pay_config` FROM `" . $this->App->prefix() . "payment` WHERE `pay_id`='$id'");
		}
		return $rt;
	}

	function up_level($userid, $uts)
	{
		$sql1 = "SELECT tb1.uid FROM `{$this->App->prefix()}user_tuijian` AS tb1 LEFT JOIN `{$this->App->prefix()}user` AS tb2 ON tb1.uid = tb2.user_id WHERE tb1.parent_uid = '$userid' ORDER BY tb1.id DESC";
		$son1 = $this->App->findcol($sql1);
		$sql2 = "SELECT tb1.uid FROM `{$this->App->prefix()}user_tuijian` AS tb1 LEFT JOIN `{$this->App->prefix()}user` AS tb2 ON tb1.uid = tb2.user_id WHERE tb1.parent_uid IN(" . $sql1 . ")";
		$son2 = $this->App->findcol($sql2);
		$sql3 = "SELECT tb1.uid FROM `{$this->App->prefix()}user_tuijian` AS tb1 LEFT JOIN `{$this->App->prefix()}user` AS tb2 ON tb1.uid = tb2.user_id WHERE tb1.parent_uid IN(" . $sql2 . ")";
		$son3 = $this->App->findcol($sql3);
		$sonid = array_merge(array_merge($son1, $son2), $son3);
		$yeji = 0;
		$myyeji = $this->App->findvar("SELECT SUM(order_amount) FROM `{$this->App->prefix()}goods_order_info` WHERE `pay_status`=1 AND `user_id`=" . $userid);
		$yeji = $yeji + $myyeji;
		foreach ($sonid as $sid) {
			$idyeji = $this->App->findvar("SELECT SUM(order_amount) FROM `{$this->App->prefix()}goods_order_info` WHERE `pay_status`=1 AND `user_id`=" . $sid);
			$yeji = $yeji + $idyeji;
		}
		$qqlevel = 0;
		if ($yeji >= $uts['quanqiu1my']) {
			$sql = "SELECT COUNT(`user_id`) FROM `gz_user` WHERE user_rank=" . $uts['quanqiu1dl'] . " AND `user_id` IN (SELECT `uid` FROM `gz_user_tuijian` WHERE `parent_uid`=" . $userid . ")";
			$sonnum = $this->App->findvar($sql);
			if ($sonnum >= $uts['quanqiu1']) {
				$qqlevel = 1;
			}
		}
		if ($yeji >= $uts['quanqiu2my']) {
			$sql = "SELECT COUNT(`user_id`) FROM `gz_user` WHERE user_rank=" . $uts['quanqiu2dl'] . " AND `user_id` IN (SELECT `uid` FROM `gz_user_tuijian` WHERE `parent_uid`=" . $userid . ")";
			$sonnum = $this->App->findvar($sql);
			if ($sonnum >= $uts['quanqiu2']) {
				$qqlevel = 2;
			}
		}
		if ($yeji >= $uts['quanqiu3my']) {
			$sql = "SELECT COUNT(`user_id`) FROM `gz_user` WHERE user_rank=" . $uts['quanqiu3dl'] . " AND `user_id` IN (SELECT `uid` FROM `gz_user_tuijian` WHERE `parent_uid`=" . $userid . ")";
			$sonnum = $this->App->findvar($sql);
			if ($sonnum >= $uts['quanqiu3']) {
				$qqlevel = 3;
			}
		}
		if ($yeji >= $uts['quanqiu4my']) {
			$sql = "SELECT COUNT(`user_id`) FROM `gz_user` WHERE user_rank=" . $uts['quanqiu4dl'] . " AND `user_id` IN (SELECT `uid` FROM `gz_user_tuijian` WHERE `parent_uid`=" . $userid . ")";
			$sonnum = $this->App->findvar($sql);
			if ($sonnum >= $uts['quanqiu4']) {
				$qqlevel = 4;
			}
		}
		if ($yeji >= $uts['quanqiu5my']) {
			$sql = "SELECT COUNT(`user_id`) FROM `gz_user` WHERE user_rank=" . $uts['quanqiu5dl'] . " AND `user_id` IN (SELECT `uid` FROM `gz_user_tuijian` WHERE `parent_uid`=" . $userid . ")";
			$sonnum = $this->App->findvar($sql);
			if ($sonnum >= $uts['quanqiu5']) {
				$qqlevel = 5;
			}
		}
		$this->App->query("UPDATE `{$this->App->prefix()}user` SET `user_rank_qq`=" . $qqlevel . " WHERE `user_id`=" . $userid);
	}

	function kena($pid, $cengji, $up_goods)
	{
		$sql = "SELECT user_rank FROM `{$this->App->prefix()}user` WHERE user_id = '$pid' LIMIT 1";
		$rank = $this->App->findvar($sql);
		if ($rank >= 10) return true;
		$levelLimit = $this->App->findvar("select daolimit from `{$this->App->prefix()}userconfig` limit 1");
		if (empty($levelLimit)) $levelLimit = 3;
		if ($up_goods == 1) {
			$sql = "select count(0) from `{$this->App->prefix()}user_tuijian` u,{$this->App->prefix()}user l where u.parent_uid ={$pid} and u.uid=l.user_id and user_rank >1";
			$count = $this->App->findvar($sql);
			if ($count >= $levelLimit) return false;
		}
		if ($up_goods == 2) {
			if ($cengji == '二级会员') {
				$sql = "select count(0) from `{$this->App->prefix()}user_tuijian` u,{$this->App->prefix()}user l,`{$this->App->prefix()}_user_tuijian` u1
						where u1.parent_uid=u.uid and u.parent_uid ={$pid} and u.uid=l.user_id and user_rank >1";
				$count = $this->App->findvar($sql);
				if ($count >= $levelLimit) return false;
			}
			if ($cengji == '一级会员') {
				$sql = "select count(0) from `{$this->App->prefix()}user_tuijian` u,{$this->App->prefix()}user l where u.parent_uid ={$pid} and u.uid=l.user_id and user_rank >1";
				$count = $this->App->findvar($sql);
				if ($count >= $levelLimit) return false;
			}
		}
		return true;
	}

	function fenyongjin($uid, $p, $moeys, $order_amount, $cengji, $order_sn, $up_goods)
	{
		if (!$this->kena($p['user_id'], $cengji, $up_goods)) {
			$str = array('openid' => $p['wecha_id'], 'appid' => '', 'appsecret' => '', 'nickname' => $p['nickname'], 'cengji' => $cengji);
			$this->action('api', 'sendtxt', $str, 'nomoney');
			$p['user_id'] = 0;
		}
		$ni = $this->App->findrow("SELECT nickname,user_rank FROM `{$this->App->prefix()}user` WHERE user_id='$uid' LIMIT 1");
		$nickname = $ni['nickname'];
		$thismonth = date('Y-m-d', mktime());
		$thism = date('Y-m', mktime());
		$sql = "UPDATE `{$this->App->prefix()}user` SET `fxmoney` = `fxmoney`+$moeys WHERE user_id =" . $p['user_id'];
		$this->App->query($sql);
		$this->App->insert('user_money_change', array('buyuid' => $uid, 'order_sn' => $order_sn, 'thismonth' => $thismonth, 'thism' => $thism, 'money' => $moeys, 'changedesc' => $cengji . '-收入' . $moeys . '元-返佣金', 'time' => mktime(), 'uid' => $p['user_id'], $order_sn));
		$str = array('wecha_id' => $p['wecha_id'], 'openid' => $p['wecha_id'], 'appid' => '', 'appsecret' => '', 'money' => $order_amount, 'jibie' => $cengji, 'nickname' => $nickname, 'type' => 'fenyongjin');
		$this->action('api', 'sendtxt', $str, $str['type']);
		return true;
	}

	function pay_successs_tatus2($rt = array())
	{
		set_time_limit(500);
		$order_sn = $rt['order_sn'];
		$status = $rt['status'];
		if (empty($order_sn)) exit;
		$order_sn = substr($order_sn, -14, 14);
		$pay_status = $this->App->findvar("SELECT pay_status FROM `{$this->App->prefix()}goods_order_info` WHERE order_sn='$order_sn' LIMIT 1");
		$tt = "false";
		if ($pay_status != '1') {
			$sql = "SELECT `money` FROM `{$this->App->prefix()}user_money_change` WHERE order_sn='$order_sn'";
			$money = $this->App->findvar($sql);
			if ($money > 0.01) {
				return true;
				exit;
			} else {
				$sql = "SELECT cid FROM `{$this->App->prefix()}user_point_change` WHERE order_sn='$order_sn'";
				$cid = $this->App->findvar($sql);
				if ($cid > 0) {
					return true;
					exit;
				} else {
					$tt = "true";
				}
			}
		} else {
			return true;
			exit;
		}
		if ($tt == 'true' && $status == '1' && !empty($order_sn)) $this->Cal_commision($tt, $order_sn, $status);
		return true;
	}

	function Cal_commision($tt, $order_sn, $status)
	{
		$pu = $this->App->findrow("SELECT * FROM `{$this->App->prefix()}goods_order_info` WHERE order_sn='$order_sn' LIMIT 1");
		if (empty($pu)) {
			exit;
		}
		$user_id = isset($pu['user_id']) ? $pu['user_id'] : 0;
		$ppp = $this->App->findrow("SELECT wecha_id,nickname FROM `{$this->App->prefix()}user` WHERE user_id=" . $user_id);
		$user_wechat = $ppp['wecha_id'];
		$uid = $user_id;
		$parent_uid = $this->App->findvar("SELECT `parent_uid` FROM `{$this->App->prefix()}user_tuijian` WHERE `uid`=$uid");
		$parent_uid2 = $this->App->findvar("SELECT `parent_uid` FROM `{$this->App->prefix()}user_tuijian` WHERE `uid`=$parent_uid");
		$parent_uid3 = $this->App->findvar("SELECT `parent_uid` FROM `{$this->App->prefix()}user_tuijian` WHERE `uid`=$parent_uid2");
		if ($parent_uid > 0) $p1 = $this->App->findrow("SELECT nickname,user_rank,wecha_id,user_id FROM `{$this->App->prefix()}user` WHERE user_id='$parent_uid' LIMIT 1");
		if ($parent_uid2 > 0) $p2 = $this->App->findrow("SELECT nickname,user_rank,wecha_id,user_id FROM `{$this->App->prefix()}user` WHERE user_id='$parent_uid2' LIMIT 1");
		if ($parent_uid3 > 0) $p3 = $this->App->findrow("SELECT nickname,user_rank,wecha_id,user_id FROM `{$this->App->prefix()}user` WHERE user_id='$parent_uid3' LIMIT 1");
		if (1) {
			if (!empty($user_wechat)) $this->action('api', 'sendtxt', array('openid' => $ppp['wecha_id']), 'my_money_pay_sucess');
			if ($parent_uid > 0) {
				$pp = $this->App->findrow("select wecha_id,nickname from `{$this->App->prefix()}user` where user_id={$parent_uid}");
				$this->action('api', 'sendtxt', array('openid' => $$pp['wecha_id'], 'nickname' => $ppp['nickname'], 'level' => '一级'), 'nomoney_pay_sucess');
			}
			if ($parent_uid2 > 0) {
				$pp = $this->App->findrow("select wecha_id,nickname from `{$this->App->prefix()}user` where user_id={$parent_uid2}");
				$this->action('api', 'sendtxt', array('openid' => $pp['wecha_id'], 'nickname' => $ppp['nickname'], 'level' => '二级'), 'nomoney_pay_sucess');
			}
			if ($parent_uid3 > 0) {
				$pp = $this->App->findrow("select wecha_id,nickname from `{$this->App->prefix()}user` where user_id={$parent_uid3}");
				$this->action('api', 'sendtxt', array('openid' => $pp['wecha_id'], 'nickname' => $ppp['nickname'], 'level' => '三级'), 'nomoney_pay_sucess');
			}
		}
		$appid = $this->Session->read('User.appid');
		if (empty($appid)) $appid = isset($_COOKIE[CFGH . 'USER']['APPID']) ? $_COOKIE[CFGH . 'USER']['APPID'] : '';
		$appsecret = $this->Session->read('User.appsecret');
		if (empty($appsecret)) $appsecret = isset($_COOKIE[CFGH . 'USER']['APPSECRET']) ? $_COOKIE[CFGH . 'USER']['APPSECRET'] : '';
		$moeys = isset($pu['order_amount']) ? $pu['order_amount'] : 0;
		$order_amount = isset($pu['order_amount']) ? $pu['order_amount'] : 0;
		$pay_status = isset($pu['pay_status']) ? $pu['pay_status'] : 0;
		$order_id = isset($pu['order_id']) ? $pu['order_id'] : 0;
		$up_goods = $pu['up_goods'];
		$ni = $this->App->findrow("SELECT nickname,user_rank FROM `{$this->App->prefix()}user` WHERE user_id='$uid' LIMIT 1");
		$nickname = $ni['nickname'];
		$dd = array();
		$dd['order_status'] = '2';
		$dd['pay_status'] = '1';
		$dd['pay_time'] = mktime();
		$this->App->update('goods_order_info', $dd, 'order_sn', $order_sn);
		if ($ni['user_rank'] == 1 && $up_goods > 0) {
			$quid = $this->App->findvar("SELECT MAX(quid) FROM `{$this->App->prefix()}user` LIMIT 1");
			$this->App->update('user', array('quid' => ($quid + 1)), 'user_id', $uid);
		}
		$uts = $this->App->findrow("SELECT * FROM `{$this->App->prefix()}userconfig` LIMIT 1");
		$sendrt_money = array();
		$record = array();
		if ($up_goods > 0) {
			switch ($up_goods) {
				case 1:
					$this->App->update('user', array('user_rank' => 8), 'user_id', $uid);
					if (($parent_uid > 0) && ($p1['user_rank'] > 1)) {
						$this->fenyongjin($uid, $p1, $uts['ticheng180_1'], $order_amount, '一级会员', $order_sn, $up_goods);
						$this->up_level($parent_uid, $uts);
					}
					break;
				case 2:
					$this->App->update('user', array('user_rank' => 9), 'user_id', $uid);
					if ($ni['user_rank'] == 1) {
						if (($parent_uid > 0) && ($p1['user_rank'] > 1)) {
							$this->fenyongjin($uid, $p1, $uts['ticheng180_1'], $order_amount, '一级会员', $order_sn, $up_goods);
							$this->up_level($parent_uid, $uts);
						}
						if (($parent_uid2 > 0) && ($p2['user_rank'] > 8)) {
							$this->fenyongjin($uid, $p2, $uts['ticheng180_2'], $order_amount, '二级会员', $order_sn, $up_goods);
							$this->up_level($parent_uid2, $uts);
						}
					} elseif ($ni['user_rank'] == 8) {
						if (($parent_uid2 > 0) && ($p2['user_rank'] > 8)) {
							$this->fenyongjin($uid, $p2, $uts['ticheng180_2'], $order_amount, '二级会员', $order_sn, $up_goods);
							$this->up_level($parent_uid2, $uts);
						}
					}
					break;
				case 3:
					$this->App->update('user', array('user_rank' => 10), 'user_id', $uid);
					if ($ni['user_rank'] == 1) {
						if (($parent_uid > 0) && ($p1['user_rank'] > 1)) {
							$this->fenyongjin($uid, $p1, $uts['ticheng180_1'], $order_amount, '一级会员', $order_sn, $up_goods);
							$this->up_level($parent_uid, $uts);
						}
						if (($parent_uid2 > 0) && ($p2['user_rank'] > 8)) {
							$this->fenyongjin($uid, $p2, $uts['ticheng180_2'], $order_amount, '二级会员', $order_sn, $up_goods);
							$this->up_level($parent_uid2, $uts);
						}
						if (($parent_uid3 > 0) && ($p3['user_rank'] > 9)) {
							$this->fenyongjin($uid, $p3, $uts['ticheng180_3'], $order_amount, '三级会员', $order_sn, $up_goods);
							$this->up_level($parent_uid3, $uts);
						}
					} elseif ($ni['user_rank'] == 8) {
						if (($parent_uid2 > 0) && ($p2['user_rank'] > 8)) {
							$this->fenyongjin($uid, $p2, $uts['ticheng180_2'], $order_amount, '二级会员', $order_sn, $up_goods);
							$this->up_level($parent_uid2, $uts);
						}
						if (($parent_uid3 > 0) && ($p3['user_rank'] > 9)) {
							$this->fenyongjin($uid, $p3, $uts['ticheng180_3'], $order_amount, '三级会员', $order_sn, $up_goods);
							$this->up_level($parent_uid3, $uts);
						}
					} elseif ($ni['user_rank'] == 9) {
						if (($parent_uid3 > 0) && ($p3['user_rank'] > 9)) {
							$this->fenyongjin($uid, $p3, $uts['ticheng180_3'], $order_amount, '三级会员', $order_sn, $up_goods);
							$this->up_level($parent_uid3, $uts);
						}
					}
					break;
			}
		} else {
			if (!empty($order_sn)) {
				$sql = "SELECT takemoney1,takemoney2,takemoney3,goods_number FROM `{$this->App->prefix()}goods_order` WHERE order_id='$order_id'";
				$moneys = $this->App->find($sql);
				$thismonth = date('Y-m-d', mktime());
				$thism = date('Y-m', mktime());
				$moeysall = 0;
				if (!empty($moneys)) foreach ($moneys as $row) {
					if ($row['takemoney1'] > 0) {
						$moeysall += $row['takemoney1'] * $row['goods_number'];
					}
				}
				$record = array();
				$moeys = 0;
				if ($parent_uid > 0) {
					$sql = "SELECT user_rank FROM `{$this->App->prefix()}user` WHERE user_id='$parent_uid' LIMIT 1";
					$rank = $this->App->findvar($sql);
					if ($rank > 1) {
						$off = 0;
						if ($rank == '8') {
							if ($rts['ticheng180_h1_1'] < 101 && $rts['ticheng180_h1_1'] > 0) {
								$off = $rts['ticheng180_h1_1'] / 100;
							}
						} elseif ($rank == '9') {
							if ($rts['ticheng180_h2_1'] < 101 && $rts['ticheng180_h2_1'] > 0) {
								$off = $rts['ticheng180_h2_1'] / 100;
							}
						} elseif ($rank == '10') {
							if ($rts['ticheng180_h3_1'] < 101 && $rts['ticheng180_h3_1'] > 0) {
								$off = $rts['ticheng180_h3_1'] / 100;
							}
						}
						$moeys = format_price($moeysall * $off);
					}
					if (!empty($moeys)) {
						$record['puid1_money'] = $moeys;
						$record['p_uid1'] = $parent_uid;
						$sql = "UPDATE `{$this->App->prefix()}user` SET `money_ucount` = `money_ucount`+$moeys,`mymoney` = `mymoney`+$moeys WHERE user_id = '$parent_uid'";
						$this->App->query($sql);
						$this->App->insert('user_money_change', array('buyuid' => $uid, 'order_sn' => $order_sn, 'thismonth' => $thismonth, 'thism' => $thism, 'money' => $moeys, 'changedesc' => '一级购买商品返佣金', 'time' => mktime(), 'uid' => $parent_uid, 'level' => '1'));
						$sendrt_money[] = array('wecha_id' => $p1['wecha_id'], 'nickname' => $nickname, 'money' => $moeys, 'order_sn' => $order_sn, 'type' => 'payreturnmoney');
					}
				}
				$moeys = 0;
				if ($parent_uid2 > 0) {
					$sql = "SELECT user_rank FROM `{$this->App->prefix()}user` WHERE user_id='$parent_uid2' LIMIT 1";
					$rank = $this->App->findvar($sql);
					if ($rank != '1') {
						$off = 0;
						if ($rank == '8') {
							if ($rts['ticheng180_h1_2'] < 101 && $rts['ticheng180_h1_2'] > 0) {
								$off = $rts['ticheng180_h1_2'] / 100;
							}
						} elseif ($rank == '9') {
							if ($rts['ticheng180_h2_2'] < 101 && $rts['ticheng180_h2_2'] > 0) {
								$off = $rts['ticheng180_h2_2'] / 100;
							}
						} elseif ($rank == '10') {
							if ($rts['ticheng180_h3_2'] < 101 && $rts['ticheng180_h3_2'] > 0) {
								$off = $rts['ticheng180_h3_2'] / 100;
							}
						}
						$moeys = format_price($moeysall * $off);
					}
					if (!empty($moeys)) {
						$record['puid2_money'] = $moeys;
						$record['p_uid2'] = $parent_uid;
						$sql = "UPDATE `{$this->App->prefix()}user` SET `money_ucount` = `money_ucount`+$moeys,`mymoney` = `mymoney`+$moeys WHERE user_id = '$parent_uid2'";
						$this->App->query($sql);
						$this->App->insert('user_money_change', array('buyuid' => $uid, 'order_sn' => $order_sn, 'thismonth' => $thismonth, 'thism' => $thism, 'money' => $moeys, 'changedesc' => '二级购买商品返佣金', 'time' => mktime(), 'uid' => $parent_uid2, 'level' => '1'));
						$sendrt_money[] = array('wecha_id' => $p2['wecha_id'], 'nickname' => $nickname, 'money' => $moeys, 'order_sn' => $order_sn, 'type' => 'payreturnmoney');
					}
				}
				$moeys = 0;
				if ($parent_uid3 > 0) {
					$sql = "SELECT user_rank FROM `{$this->App->prefix()}user` WHERE user_id='$parent_uid3' LIMIT 1";
					$rank = $this->App->findvar($sql);
					if ($rank != '1') {
						$off = 0;
						if ($rank == '8') {
							if ($rts['ticheng180_h1_3'] < 101 && $rts['ticheng180_h1_3'] > 0) {
								$off = $rts['ticheng180_h1_3'] / 100;
							}
						} elseif ($rank == '9') {
							if ($rts['ticheng180_h2_3'] < 101 && $rts['ticheng180_h2_3'] > 0) {
								$off = $rts['ticheng180_h2_3'] / 100;
							}
						} elseif ($rank == '10') {
							if ($rts['ticheng180_h3_3'] < 101 && $rts['ticheng180_h3_3'] > 0) {
								$off = $rts['ticheng180_h3_3'] / 100;
							}
						}
						$moeys = format_price($moeysall * $off);
					}
					if (!empty($moeys)) {
						$record['puid3_money'] = $moeys;
						$record['p_uid3'] = $parent_uid;
						$sql = "UPDATE `{$this->App->prefix()}user` SET `money_ucount` = `money_ucount`+$moeys,`mymoney` = `mymoney`+$moeys WHERE user_id = '$parent_uid3'";
						$this->App->query($sql);
						$this->App->insert('user_money_change', array('buyuid' => $uid, 'order_sn' => $order_sn, 'thismonth' => $thismonth, 'thism' => $thism, 'money' => $moeys, 'changedesc' => '三级购买商品返佣金', 'time' => mktime(), 'uid' => $parent_uid3, 'level' => '1'));
						$sendrt_money[] = array('wecha_id' => $p3['wecha_id'], 'nickname' => $nickname, 'money' => $moeys, 'order_sn' => $order_sn, 'type' => 'payreturnmoney');
					}
				}
			}
		}
		$mone = array();
		if (!empty($sendrt_money)) foreach ($sendrt_money as $mone) {
			$this->action('api', 'sendtxt', array('openid' => $mone['wecha_id'], 'appid' => '', 'appsecret' => '', 'nickname' => $mone['nickname'], 'money' => $mone['money'], 'order_sn' => $mone['order_sn']), $mone['type']);
		}
		unset($sendrt_money);
		if (!empty($record)) {
			$record['oid'] = $order_id;
			$record['uid'] = $uid;
			$record['date_y'] = date('Y', mktime());
			$record['date_m'] = date('Y-m', mktime());
			$record['date_d'] = date('Y-m-d', mktime());
			$this->App->insert('user_money_record', $record);
		}
		unset($record);
	}

	function return_daili_uid($uid = 0, $k = 0)
	{
		if (!($uid > 0)) {
			return 0;
		}
		$puid = 0;
		for ($i = 0; $i < 20; $i++) {
			$sql = "SELECT parent_uid FROM `{$this->App->prefix()}user_tuijian` WHERE uid = '$uid' LIMIT 1";
			$p = $this->App->findvar($sql);
			if ($p > 0) {
				$sql = "SELECT user_rank FROM `{$this->App->prefix()}user` WHERE user_id = '$p' LIMIT 1";
				$rank = $this->App->findvar($sql);
				if ($rank != 1) {
					$puid = $p;
					break;
				} else {
					$uid = $p;
				}
			}
		}
		return $puid;
	}

	function _firtuids($uid = 0)
	{
		$ut = array();
		$sql = "SELECT uid FROM `{$this->App->prefix()}user_tuijian` WHERE parent_uid='$uid'";
		$uids = $this->App->findcol($sql);
		if (!empty($uids)) foreach ($uids as $uid) {
			$ur = $this->App->findvar("SELECT user_rank FROM `{$this->App->prefix()}user` WHERE user_id = '$uid'");
			if ($ur != '1') {
				$ut[] = $uid;
			} else {
				$sql = "SELECT uid FROM `{$this->App->prefix()}user_tuijian` WHERE parent_uid='$uid'";
				$uids = $this->App->findcol($sql);
				if (!empty($uids)) foreach ($uids as $uid) {
					$ur = $this->App->findvar("SELECT user_rank FROM `{$this->App->prefix()}user` WHERE user_id = '$uid'");
					if ($ur != '1') {
						$ut[] = $uid;
					} else {
						$sql = "SELECT uid FROM `{$this->App->prefix()}user_tuijian` WHERE parent_uid='$uid'";
						$uids = $this->App->findcol($sql);
						if (!empty($uids)) foreach ($uids as $uid) {
							$ur = $this->App->findvar("SELECT user_rank FROM `{$this->App->prefix()}user` WHERE user_id = '$uid'");
							if ($ur != '1') {
								$ut[] = $uid;
							} else {
								$sql = "SELECT uid FROM `{$this->App->prefix()}user_tuijian` WHERE parent_uid='$uid'";
								$uids = $this->App->findcol($sql);
								if (!empty($uids)) foreach ($uids as $uid) {
									$ur = $this->App->findvar("SELECT user_rank FROM `{$this->App->prefix()}user` WHERE user_id = '$uid'");
									if ($ur != '1') {
										$ut[] = $uid;
									} else {
										$sql = "SELECT uid FROM `{$this->App->prefix()}user_tuijian` WHERE parent_uid='$uid'";
										$uids = $this->App->findcol($sql);
										if (!empty($uids)) foreach ($uids as $uid) {
											$ur = $this->App->findvar("SELECT user_rank FROM `{$this->App->prefix()}user` WHERE user_id = '$uid'");
											if ($ur != '1') {
												$ut[] = $uid;
											} else {
												$sql = "SELECT uid FROM `{$this->App->prefix()}user_tuijian` WHERE parent_uid='$uid'";
												$uids = $this->App->findcol($sql);
												if (!empty($uids)) foreach ($uids as $uid) {
													$ur = $this->App->findvar("SELECT user_rank FROM `{$this->App->prefix()}user` WHERE user_id = '$uid'");
													if ($ur != '1') {
														$ut[] = $uid;
													} else {
														$sql = "SELECT uid FROM `{$this->App->prefix()}user_tuijian` WHERE parent_uid='$uid'";
														$uids = $this->App->findcol($sql);
														if (!empty($uids)) foreach ($uids as $uid) {
															$ur = $this->App->findvar("SELECT user_rank FROM `{$this->App->prefix()}user` WHERE user_id = '$uid'");
															if ($ur != '1') {
																$ut[] = $uid;
															} else {
																$sql = "SELECT uid FROM `{$this->App->prefix()}user_tuijian` WHERE parent_uid='$uid'";
																$uids = $this->App->findcol($sql);
																if (!empty($uids)) foreach ($uids as $uid) {
																	$ur = $this->App->findvar("SELECT user_rank FROM `{$this->App->prefix()}user` WHERE user_id = '$uid'");
																	if ($ur != '1') {
																		$ut[] = $uid;
																	} else {
																		break;
																	}
																}
															}
														}
													}
												}
											}
										}
									}
								}
							}
						}
					}
				}
			}
		}
		return $ut;
	}

	function update_daili_tree($uid = 0)
	{
		if ($uid > 0) {
			$dd = array();
			$dd['uid'] = $uid;
			$dd['p1_uid'] = 0;
			$dd['p2_uid'] = 0;
			$dd['p3_uid'] = 0;
			$p1_uid = $this->return_daili_uid($uid);
			$firtuids = array();
			if ($p1_uid > 0) {
				$dd['p1_uid'] = $p1_uid;
				$p2_uid = $this->return_daili_uid($p1_uid);
				if ($p2_uid > 0) {
					$dd['p2_uid'] = $p2_uid;
					$p3_uid = $this->return_daili_uid($p2_uid);
					if ($p3_uid > 0) {
						$dd['p3_uid'] = $p3_uid;
					}
				}
			}
			$sql = "SELECT id FROM `{$this->App->prefix()}user_tuijian_fx` WHERE uid='$uid' LIMIT 1";
			$id = $this->App->findvar($sql);
			if ($id > 0) {
				$this->App->update('user_tuijian_fx', $dd, 'id', $id);
			} else {
				$this->App->insert('user_tuijian_fx', $dd);
			}
			$firtuids = $this->_firtuids($uid);
			$aup = array();
			if (!empty($firtuids)) foreach ($firtuids as $u) {
				$dds = array();
				$dds['uid'] = $u;
				$dds['p1_uid'] = $uid;
				$dds['p2_uid'] = $dd['p1_uid'];
				$dds['p3_uid'] = $dd['p2_uid'];
				$aup[] = $dds;
				$firtuids2 = $this->App->findcol("SELECT uid FROM `{$this->App->prefix()}user_tuijian_fx` WHERE p1_uid = '$u'");
				if (!empty($firtuids2)) foreach ($firtuids2 as $uu) {
					$dds = array();
					$dds['uid'] = $uu;
					$dds['p1_uid'] = $u;
					$dds['p2_uid'] = $uid;
					$dds['p3_uid'] = $dd['p1_uid'];
					$aup[] = $dds;
					$firtuids3 = $this->App->findcol("SELECT uid FROM `{$this->App->prefix()}user_tuijian_fx` WHERE p1_uid = '$uu'");
					if (!empty($firtuids3)) foreach ($firtuids3 as $uuu) {
						$dds = array();
						$dds['uid'] = $uuu;
						$dds['p1_uid'] = $uu;
						$dds['p2_uid'] = $u;
						$dds['p3_uid'] = $uid;
						$aup[] = $dds;
					}
					unset($firtuids3);
				}
				unset($firtuids2);
			}
			unset($firtuids);
			if (!empty($aup)) foreach ($aup as $up) {
				$this->App->update('user_tuijian_fx', $up, 'uid', $up['uid']);
			}
			unset($aup);
		}
	}

	function update_user_tree($puid = 0, $ppuid = 0)
	{
		$three_arr = array();
		$sql = 'SELECT id,uid FROM `' . $this->App->prefix() . "user_tuijian` WHERE parent_uid = '$puid'";
		$rt = $this->App->find($sql);
		if (!empty($rt)) foreach ($rt as $row) {
			$id = $row['id'];
			$uid = $row['uid'];
			if ($id > 0) {
				$this->App->update('user_tuijian', array('daili_uid' => $ppuid), 'id', $id);
			}
			$sql = "SELECT user_rank FROM `{$this->App->prefix()}user` WHERE user_id='$uid' LIMIT 1";
			$rank = $this->App->findvar($sql);
			if ($rank == '1') {
				$this->update_user_tree($uid, $ppuid);
			} else {
			}
		}
	}

	function pay_successs_tatus($rt = array())
	{
		$order_sn = $rt['order_sn'];
		$status = $rt['status'];
		$pu = $this->App->findrow("SELECT user_id,daili_uid,parent_uid,goods_amount,order_amount,order_sn FROM `{$this->App->prefix()}goods_order_info_daigou` WHERE order_sn='$order_sn' LIMIT 1");
		$parent_uid = isset($pu['parent_uid']) ? $pu['parent_uid'] : 0;
		$daili_uid = isset($pu['daili_uid']) ? $pu['daili_uid'] : 0;
		$moeys = isset($pu['order_amount']) ? $pu['order_amount'] : 0;
		$uid = isset($pu['user_id']) ? $pu['user_id'] : 0;
		$tt = "false";
		$sql = "SELECT cid FROM `{$this->App->prefix()}user_money_change` WHERE order_sn='$order_sn'";
		$cid = $this->App->findvar($sql);
		if ($cid > 0) {
			return false;
			exit;
		} else {
			$sql = "SELECT cid FROM `{$this->App->prefix()}user_point_change` WHERE order_sn='$order_sn'";
			$cid = $this->App->findvar($sql);
			if ($cid > 0) {
				return false;
				exit;
			} else {
				$tt = "true";
			}
		}
		if ($tt == 'true' && $status == '1' && !empty($order_sn)) {
			$nickname = $this->App->findvar("SELECT nickname FROM `{$this->App->prefix()}user` WHERE user_id='$uid' LIMIT 1");
			$dd = array();
			$dd['order_status'] = 2;
			$dd['pay_status'] = 1;
			$dd['pay_time'] = mktime();
			$this->App->update('goods_order_info_daigou', $dd, 'order_sn', $order_sn);
			$sql = "SELECT * FROM `{$this->App->prefix()}userconfig` LIMIT 1";
			$rts = $this->App->findrow($sql);
			$appid = $this->Session->read('User.appid');
			if (empty($appid)) $appid = isset($_COOKIE[CFGH . 'USER']['APPID']) ? $_COOKIE[CFGH . 'USER']['APPID'] : '';
			$appsecret = $this->Session->read('User.appsecret');
			if (empty($appsecret)) $appsecret = isset($_COOKIE[CFGH . 'USER']['APPSECRET']) ? $_COOKIE[CFGH . 'USER']['APPSECRET'] : '';
			$pointnum = $rts['pointnum'];
			if ($pointnum > 0 && !empty($moeys)) {
				if ($parent_uid > 0) {
					$points = ceil(intval($moeys * $pointnum) / 2);
					$points = intval($points);
				} else {
					$points = intval($moeys * $pointnum);
				}
				$thismonth = date('Y-m-d', mktime());
				$sql = "UPDATE `{$this->App->prefix()}user` SET `points_ucount` = `points_ucount`+$points,`mypoints` = `mypoints`+$points WHERE user_id = '$uid'";
				$this->App->query($sql);
				$this->App->insert('user_point_change', array('order_sn' => $order_sn, 'thismonth' => $thismonth, 'points' => $points, 'changedesc' => '消费返积分', 'time' => mktime(), 'uid' => $uid));
				$pwecha_id = $this->App->findvar("SELECT wecha_id FROM `{$this->App->prefix()}user` WHERE user_id='$uid' LIMIT 1");
				$this->action('api', 'send', array('openid' => $pwecha_id, 'appid' => $appid, 'appsecret' => $appsecret, 'nickname' => ''), 'payreturnpoints');
				if ($parent_uid > 0) {
					$sql = "UPDATE `{$this->App->prefix()}user` SET `points_ucount` = `points_ucount`+$points,`mypoints` = `mypoints`+$points WHERE user_id = '$parent_uid'";
					$this->App->query($sql);
					$this->App->insert('user_point_change', array('order_sn' => $order_sn, 'thismonth' => $thismonth, 'points' => $points, 'changedesc' => '推荐消费返积分', 'time' => mktime(), 'uid' => $parent_uid));
					$pwecha_id = $this->App->findvar("SELECT wecha_id FROM `{$this->App->prefix()}user` WHERE user_id='$parent_uid' LIMIT 1");
					$this->action('api', 'send', array('openid' => $pwecha_id, 'appid' => $appid, 'appsecret' => $appsecret, 'nickname' => ''), 'payreturnpoints_parentuid');
				}
			}
			$sql = "SELECT user_rank FROM `{$this->App->prefix()}user` WHERE user_id='$uid' LIMIT 1";
			$rank = $this->App->findvar($sql);
			if ($rank == '10' && !empty($moeys)) {
				$sql = "SELECT types FROM `{$this->App->prefix()}user` WHERE user_id='$uid' LIMIT 1";
				$types = $this->App->findvar($sql);
				$off = 0;
				if ($types == '1') {
					if ($rts['ticheng360'] < 101 && $rts['ticheng360'] > 0) {
						$off = $rts['ticheng360'] / 100;
					}
				} else {
					if ($rts['ticheng180'] < 101 && $rts['ticheng180'] > 0) {
						$off = $rts['ticheng180'] / 100;
					}
				}
				$moeys = format_price($moeys * $off);
				$thismonth = date('Y-m-d', mktime());
				$thism = date('Y-m', mktime());
				$sql = "UPDATE `{$this->App->prefix()}user` SET `money_ucount` = `money_ucount`+$moeys,`mygouwubi` = `mygouwubi`+$moeys WHERE user_id = '$uid'";
				$this->App->query($sql);
				$this->App->insert('user_money_change', array('buyuid' => $uid, 'order_sn' => $order_sn, 'thismonth' => $thismonth, 'thism' => $thism, 'money' => $moeys, 'changedesc' => '购买商品返佣金', 'time' => mktime(), 'uid' => $uid));
				$pwecha_id = $this->App->findvar("SELECT wecha_id FROM `{$this->App->prefix()}user` WHERE user_id='$uid' LIMIT 1");
				$this->action('api', 'send', array('openid' => $pwecha_id, 'appid' => $appid, 'appsecret' => $appsecret, 'nickname' => $nickname), 'payreturnmoney');
			} elseif ($daili_uid > 0 && !empty($moeys)) {
				$sql = "SELECT types FROM `{$this->App->prefix()}user` WHERE user_id='$daili_uid' LIMIT 1";
				$types = $this->App->findvar($sql);
				$off = 0;
				if ($types == '1') {
					if ($rts['ticheng360'] < 101 && $rts['ticheng360'] > 0) {
						$off = $rts['ticheng360'] / 100;
					}
				} else {
					if ($rts['ticheng180'] < 101 && $rts['ticheng180'] > 0) {
						$off = $rts['ticheng180'] / 100;
					}
				}
				$moeys = format_price($moeys * $off);
				$thismonth = date('Y-m-d', mktime());
				$thism = date('Y-m', mktime());
				$sql = "UPDATE `{$this->App->prefix()}user` SET `money_ucount` = `money_ucount`+$moeys,`mygouwubi` = `mygouwubi`+$moeys WHERE user_id = '$daili_uid'";
				$this->App->query($sql);
				$this->App->insert('user_money_change', array('buyuid' => $uid, 'order_sn' => $order_sn, 'thismonth' => $thismonth, 'thism' => $thism, 'money' => $moeys, 'changedesc' => '推荐用户购买返佣金', 'time' => mktime(), 'uid' => $daili_uid));
				$pwecha_id = $this->App->findvar("SELECT wecha_id FROM `{$this->App->prefix()}user` WHERE user_id='$daili_uid' LIMIT 1");
				$this->action('api', 'send', array('openid' => $pwecha_id, 'appid' => $appid, 'appsecret' => $appsecret, 'nickname' => $nickname), 'payreturnmoney');
			}
		}
	}

	function paysuccessjump($t = '')
	{
		$url = str_replace('paywx/', '', ADMIN_URL);
		if ($t == '1') {
			$this->jump($url, 0, '您已经成功支付，感谢您的支持。');
			exit;
		} elseif ($t == '2') {
			$this->jump($url, 0, '支付发生意外错误，请稍后再试。');
			exit;
		}
		$this->jump($url);
		exit;
	}

	function get_openid_AND_pay_info()
	{
		$wecha_id = $this->Session->read('User.wecha_id');
		if (empty($wecha_id)) $wecha_id = isset($_COOKIE[CFGH . 'USER']['UKEY']) ? $_COOKIE[CFGH . 'USER']['UKEY'] : '';
		$wecha_id = $wecha_id;
		$order_sn = isset($_GET['order_sn']) ? $_GET['order_sn'] : '';
		$sql = "SELECT order_sn,order_amount,pay_status,shipping_fee FROM `{$this->App->prefix()}goods_order_info` WHERE pay_status = '0' AND order_sn='$order_sn' LIMIT 1";
		$rt = $this->App->findrow($sql);
		$rt['order_amount'] = $rt['order_amount'] + $rt['shipping_fee'];
		if (empty($rt)) {
			$this->jump(str_replace('/wxpay', '', ADMIN_URL), 0, '非法支付提交！');
			exit;
		}
		if ($rt['pay_status'] == '1') {
			$this->jump(str_replace('/wxpay', '', ADMIN_URL) . 'user.php?act=orderlist');
			exit;
		}
		$rt['openid'] = $wecha_id;
		$rt['body'] = $GLOBALS['LANG']['site_name'] . '购物平台';
		return $rt;
	}

	function get_order_pay_info($order_sn)
	{
		$sql = "SELECT order_sn,order_id,order_amount,pay_status,shipping_fee FROM `{$this->App->prefix()}goods_order_info` WHERE pay_status = '0' AND order_sn='$order_sn' LIMIT 1";
		$rt = $this->App->findrow($sql);
		$rt['order_amount'] = $rt['order_amount'] + $rt['shipping_fee'];
		if (empty($rt)) {
			$this->jump(str_replace('/yunpay', '', ADMIN_URL), 0, '非法支付提交！');
			exit;
		}
		if ($rt['pay_status'] == '1') {
			$this->jump(str_replace('/yunpay', '', ADMIN_URL) . 'user.php?act=orderlist');
			exit;
		}
		$rt['body'] = $GLOBALS['LANG']['site_name'] . '购物平台';
		$order_id = $rt['order_id'];
		$rt['gname'] = $this->App->findvar("SELECT goods_name FROM `{$this->App->prefix()}goods_order` WHERE order_id = '$order_id' LIMIT 1");
		return $rt;
	}

	function _sendSMS($http, $uid, $pwd, $mobile, $content, $mobileids, $time = '', $mid = '')
	{
		$data = array('uid' => $uid, 'pwd' => md5($pwd . $uid), 'mobile' => $mobile, 'content' => $content, 'mobileids' => $mobileids, 'time' => $time,);
		$re = $this->_postSMS($http, $data);
		if (trim($re) == '100') {
			return "发送成功!";
		} else {
			return "发送失败! 状态：" . $re;
		}
	}

	function _postSMS($url, $data = '')
	{
		$row = parse_url($url);
		$host = $row['host'];
		$port = $row['port'] ? $row['port'] : 80;
		$file = $row['path'];
		while (list($k, $v) = each($data)) {
			$post .= rawurlencode($k) . "=" . rawurlencode($v) . "&";
		}
		$post = substr($post, 0, -1);
		$len = strlen($post);
		$fp = @fsockopen($host, $port, $errno, $errstr, 10);
		if (!$fp) {
			return "$errstr ($errno)\n";
		} else {
			$receive = '';
			$out = "POST $file HTTP/1.1\r\n";
			$out .= "Host: $host\r\n";
			$out .= "Content-type: application/x-www-form-urlencoded\r\n";
			$out .= "Connection: Close\r\n";
			$out .= "Content-Length: $len\r\n\r\n";
			$out .= $post;
			fwrite($fp, $out);
			while (!feof($fp)) {
				$receive .= fgets($fp, 128);
			}
			fclose($fp);
			$receive = explode("\r\n\r\n", $receive);
			unset($receive[0]);
			return implode("", $receive);
		}
	}

	function _alipayment($rt = array())
	{
		$pay_id = $rt['pay_id'];
		$order_sn = $rt['order_sn'];
		$order_amount = $rt['order_amount'] + $rt['logistics_fee'];
		if ($pay_id == '4') {
			$this->jump(ADMIN_URL . 'wxpay/js_api_call.php?order_sn=' . $order_sn);
			exit;
		}
		if ($pay_id == '6') {
			$this->jump(ADMIN_URL . 'yunpay/yunpay.php?order_sn=' . $order_sn);
			exit;
		}
		if ($pay_id == '7') {
			$uid = $this->Session->read('User.uid');
			if ($uid > 0) {
				$sql = "SELECT mygouwubi FROM `{$this->App->prefix()}user` WHERE user_id='$uid' LIMIT 1";
				$mygouwubi = $this->App->findvar($sql);
			} else {
				$oid = $this->App->findvar("SELECT order_id FROM `{$this->App->prefix()}user` WHERE order_sn='$order_sn' LIMIT 1");
				$this->jump(ADMIN_URL . 'mycart.php?type=pay2&oid=' . $oid, 0, '余额不足，请选择其他支付方式！');
				exit;
			}
			if ($mygouwubi >= $order_amount) {
				$money = -$order_amount;
				$sql = "UPDATE `{$this->App->prefix()}user` SET `mygouwubi` = `mygouwubi`+$money WHERE user_id = '$uid'";
				$this->App->query($sql);
				$sd = array();
				$sd = array('order_sn' => $order_sn, 'status' => 1);
				if ($this->pay_successs_tatus2($sd)) {
					$sd = array();
					$thismonth = date('Y-m-d', mktime());
					$thism = date('Y-m', mktime());
					$sd['time'] = mktime();
					$sd['changedesc'] = '余额支付';
					$sd['money'] = $money;
					$sd['uid'] = $uid;
					$sd['buyuid'] = $uid;
					$sd['order_sn'] = $order_sn;
					$sd['thismonth'] = $thismonth;
					$sd['thism'] = $thism;
					$sd['type'] = '3';
					$this->App->insert('user_money_change', $sd);
					unset($sd);
					$this->jump(ADMIN_URL . 'user.php?act=orderlist', 0, '已成功支付');
					exit;
				} else {
					$this->jump(ADMIN_URL . 'mycart.php?type=pay2&oid=' . $oid, 0, '意外错误！');
					exit;
				}
			} else {
				$this->jump(ADMIN_URL . 'mycart.php?type=pay2&oid=' . $oid, 0, '余额不足，请选择其他支付方式！');
				exit;
			}
		}
		$sql = "SELECT `pay_config` FROM `" . $this->App->prefix() . "payment` WHERE `pay_id`='$pay_id'";
		$pay_config = $this->App->findvar($sql);
		$configr = unserialize($pay_config);
		$paypalmail = isset($configr['pay_no']) ? $configr['pay_no'] : '';
		if (!$paypalmail) {
			$this->jump(ADMIN_URL, 0, '这是货到付款方式，等待商家发货');
			exit;
			return false;
		}
		if (!$paypalmail) {
			return false;
		}
		if ($pay_id == '3') {
			$paypal_form = "<form name='aqua' method='post' action='" . ADMIN_URL . "paywx/alipayapi.php'>
				<input type='hidden' name='WIDout_trade_no' value='" . $order_sn . "'>
				<input type='hidden' name='WIDseller_email' value='" . $paypalmail . "'>
				<input type='hidden' name='WIDsubject' value='商城支付系统'>
				<input type='hidden' name='WIDtotal_fee' value='" . $order_amount . "'>
			</form>";
			$paypal_form .= "<script language='javascript'>
					aqua.submit();
					</script>
					";
			echo $paypal_form;
		}
		die();
	}

	function confirm_daigou2()
	{
		$uid = $this->Session->read('User.uid');
		if (empty($uid)) {
			$this->jump(ADMIN_URL . 'user.php?act=login', 0, '请先登录！');
			exit;
		}
		$order_sn = date('Y', mktime()) . mktime();
		if (isset($_POST) && !empty($_POST)) {
			$addresssall = $_POST['address'];
			$pay_id = $_POST['pay_id'];
			$pay_name = $this->App->findvar("SELECT pay_name FROM `{$this->App->prefix()}payment` WHERE pay_id='$pay_id' LIMIT 1");
			$shipping_id = $_POST['shipping_id'];
			$shipping_name = $this->App->findvar("SELECT shipping_name FROM `{$this->App->prefix()}shipping` WHERE shipping_id='$shipping_id' LIMIT 1");
			$postscript = $_POST['postscript'];
			$goodslist = $this->Session->read('cart');
			if (empty($goodslist)) {
				$this->jump(ADMIN_URL, 0, '购物车为空');
				exit;
			}
			$totalprice = 0;
			$stotalprice = 0;
			foreach ($goodslist as $gid => $row) {
				if ($row['is_jifen_session'] == '1') {
					$this->Session->write("cart.$gid", null);
					$this->Session->write('useradd.$gid', null);
					continue;
				}
				if (!($row['number'] > 0)) {
					$row['number'] = 1;
					$this->Session->write("cart.{$gid}.number", 1);
				}
				$totalprice += $row['price'] * $row['number'];
				$stotalprice += $row['pifa_price'] * $row['number'];
			}
			if (!($totalprice > 0)) {
				$this->jump(ADMIN_URL, 0, '非法 提交');
				exit;
			}
			$orderdata = array();
			$orderdata['pay_id'] = $pay_id;
			$orderdata['shipping_id'] = $shipping_id;
			$orderdata['pay_name'] = $pay_name;
			$orderdata['shipping_name'] = $shipping_name;
			$orderdata['order_sn'] = $order_sn;
			$orderdata['user_id'] = $uid;
			$pr = $this->App->findrow("SELECT parent_uid,daili_uid FROM `{$this->App->prefix()}user_tuijian` WHERE uid='$uid' LIMIT 1");
			$parent_uid = isset($pr['parent_uid']) ? $pr['parent_uid'] : 0;
			$daili_uid = isset($pr['daili_uid']) ? $pr['daili_uid'] : 0;
			$orderdata['parent_uid'] = $parent_uid > 0 ? $parent_uid : '0';
			$orderdata['daili_uid'] = $daili_uid > 0 ? $daili_uid : '0';
			$orderdata['postscript'] = $postscript;
			$orderdata['goods_amount'] = $stotalprice;
			$orderdata['order_amount'] = $totalprice;
			$orderdata['add_time'] = mktime();
			$this->App->insert('goods_order_info_daigou', $orderdata);
			$orderid = $this->App->iid();
			if ($orderid > 0) foreach ($goodslist as $row) {
				$gid = $row['goods_id'];
				$ds = array();
				$ds['order_id'] = $orderid;
				$ds['goods_id'] = $gid;
				$ds['brand_id'] = $row['brand_id'];
				$ds['goods_name'] = $row['goods_name'];
				$ds['goods_thumb'] = $row['goods_thumb'];
				$ds['goods_bianhao'] = $row['goods_bianhao'];
				$ds['goods_unit'] = $row['goods_unit'];
				$ds['goods_sn'] = $row['goods_sn'];
				$ds['market_price'] = $row['pifa_price'];
				$ds['goods_price'] = $row['price'];
				$ds['goods_number'] = $row['number'];
				if (!empty($row['spec'])) $ds['goods_attr'] = implode("、", $row['spec']);
				$this->App->insert('goods_order_daigou', $ds);
				$rec_id = $this->App->iid();
				if ($rec_id > 0) {
					$useradd = $this->Session->read("useradd.{$gid}");
					$l = 0;
					if (!empty($useradd)) foreach ($useradd as $k => $addresss) {
						$dd = array();
						$dd['consignee'] = $addresss['consignee'];
						$dd['goods_number'] = !($addresss['number'] > 0) ? 1 : $addresss['number'];
						$dd['moblie'] = $addresss['moblie'];
						$dd['address'] = !empty($addresssall[$gid][$l]) ? $addresssall[$gid][$l] : $addresss['address'];
						$dd['rec_id'] = $rec_id;
						$this->App->insert('goods_order_address', $dd);
						++$l;
					}
				}
			}
		}
		$this->Session->write('cart', null);
		$this->Session->write('useradd', null);
		$this->jump(ADMIN_URL . 'mycart.php?type=pay&oid=' . $orderid);
		exit;
		exit;
	}

	function checkout2()
	{
		$this->title('确认订单 - ' . $GLOBALS['LANG']['site_name']);
		$uid = $this->Session->read('User.uid');
		if (empty($uid)) {
			$this->jump(ADMIN_URL);
			exit;
		}
		$goodslist = $this->Session->read('cart');
		if (empty($goodslist)) {
			$this->jump(ADMIN_URL, 0, '购物车为空！');
			exit;
		}
		$useradd = $this->Session->read('useradd');
		$sql = "SELECT tb1.*,tb2.region_name AS provinces,tb3.region_name AS citys,tb4.region_name AS districts FROM `{$this->App->prefix()}user_address` AS tb1";
		$sql .= " LEFT JOIN `{$this->App->prefix()}region` AS tb2 ON tb2.region_id = tb1.province";
		$sql .= " LEFT JOIN `{$this->App->prefix()}region` AS tb3 ON tb3.region_id = tb1.city";
		$sql .= " LEFT JOIN `{$this->App->prefix()}region` AS tb4 ON tb4.region_id = tb1.district";
		$sql .= " WHERE tb1.user_id='$uid' AND tb1.is_own='0' ORDER BY tb1.is_default DESC, tb1.address_id ASC LIMIT 1";
		$rt['userress'] = $this->App->findrow($sql);
		$rt['goodslist'] = array();
		$counts = 0;
		if (!empty($goodslist)) {
			foreach ($goodslist as $k => $row) {
				if ($row['is_jifen_session'] == '1') {
					$this->Session->write("cart.$k", null);
					$this->Session->write('useradd.$k', null);
					continue;
				}
				if (empty($useradd[$k]) || !isset($useradd[$k])) {
					if (empty($rt['userress'])) {
						$useradd[$k][1234567] = array('address' => '', 'number' => 1, 'consignee' => '', 'moblie' => '');
					} else {
						$us = $rt['userress']['provinces'] . $rt['userress']['citys'] . $rt['userress']['districts'] . $rt['userress']['address'];
						$useradd[$k][1234567] = array('address' => $us, 'number' => 1, 'consignee' => $rt['userress']['consignee'], 'moblie' => $rt['userress']['mobile']);
					}
				}
				$counts += $row['number'];
				$this->Session->write("cart.{$k}.spec.number", null);
			}
			$this->Session->write('useradd', $useradd);
			$sql = "SELECT * FROM `{$this->App->prefix()}userconfig` LIMIT 1";
			$rts = $this->App->findrow($sql);
			$off = 1;
			$sql = "SELECT is_subscribe FROM `{$this->App->prefix()}user` WHERE user_id='$uid' LIMIT 1";
			$issubscribe = $this->App->findvar($sql);
			$guanzhuoff = $rts['guanzhuoff'];
			$address3off = $rts['address3off'];
			$address2off = $rts['address2off'];
			if ($issubscribe == '1' && $guanzhuoff < 101 && $guanzhuoff > 0) {
				$off = ($guanzhuoff / 100);
			}
			if ($counts >= 2 && $address2off < 101 && $address2off > 0) {
				$off = ($address2off / 100);
			}
			if ($counts >= 3 && $address3off < 101 && $address3off > 0) {
				$off = ($address3off / 100) * $off;
			}
			$useradd = $this->Session->read('useradd');
			foreach ($goodslist as $k => $row) {
				$price = format_price($row['pifa_price'] * $off);
				$this->Session->write("cart.{$k}.price", $price);
				$this->Session->write("cart.{$k}.zprice", $price * $row['number']);
			}
		}
		$sql = "SELECT * FROM `{$this->App->prefix()}payment` WHERE enabled='1'";
		$rt['paymentlist'] = $this->App->find($sql);
		$sql = "SELECT * FROM `{$this->App->prefix()}shipping`";
		$rt['shippinglist'] = $this->App->find($sql);
		$this->set('rt', $rt);
		if (!defined(NAVNAME)) define("NAVNAME", "确认订单");
		$this->template('mycart_checkout');
	}

	function ajax_address_writesess($data = array())
	{
		$kk = $data['kk'];
		$gid = $data['gid'];
		$consignee = $data['consignee'];
		$moblie = $data['moblie'];
		$address = $data['address'];
		$number = $data['number'];
		$ud = array('address' => $address, 'number' => $number, 'consignee' => $consignee, 'moblie' => $moblie);
		$this->Session->write("useradd.{$gid}.{$kk}", $ud);
		$n = $this->Session->read("cart.{$gid}.number");
		$this->Session->write("cart.{$gid}.number", (intval($n) + intval($number)));
	}

	function ajax_remove_goods_address($data = array())
	{
		$kk = trim($data['kk']);
		$gid = intval($data['gid']);
		$number = intval($data['number']);
		$d = $this->Session->read("useradd.{$gid}.{$kk}");
		$this->Session->write("useradd.{$gid}.{$kk}", null);
		$n = $this->Session->read("cart.{$gid}.number");
		$this->Session->write("cart.{$gid}.number", (intval($n) - intval($number)));
	}

	function ajax_change_goods_number($data = array())
	{
		$kk = $data['kk'];
		$gid = intval($data['gid']);
		$n = intval($data['n']);
		$ty = $data['ty'];
		$nums = $this->Session->read("cart.{$gid}.number");
		if ($ty == 'jian') {
			$this->Session->write("cart.{$gid}.number", (intval($nums) - 1));
			$this->Session->write("useradd.{$gid}.{$kk}.number", $n);
		} else {
			$this->Session->write("cart.{$gid}.number", (intval($nums) + 1));
			$this->Session->write("useradd.{$gid}.{$kk}.number", $n);
		}
	}

	function ajax_jisuan_price()
	{
		$err = 0;
		$result = array('error' => $err, 'totalprice' => '0.00', 'goods' => '', 'message' => '');
		$json = Import::json();
		$goodslist = $this->Session->read('cart');
		$useradd = $this->Session->read('useradd');
		$counts = 0;
		if (!empty($goodslist)) foreach ($goodslist as $k => $row) {
			$counts += $row['number'];
		}
		$sql = "SELECT * FROM `{$this->App->prefix()}userconfig` LIMIT 1";
		$rts = $this->App->findrow($sql);
		$off = 1;
		$sql = "SELECT is_subscribe FROM `{$this->App->prefix()}user` WHERE user_id='$uid' LIMIT 1";
		$issubscribe = $this->App->findvar($sql);
		$guanzhuoff = $rts['guanzhuoff'];
		$address3off = $rts['address3off'];
		$address2off = $rts['address2off'];
		if ($issubscribe == '1' && $guanzhuoff < 101 && $guanzhuoff > 0) {
			$off = ($guanzhuoff / 100);
		}
		if ($issubscribe == '1' && $counts >= 2 && $address2off < 101 && $address2off > 0) {
			$off = ($address2off / 100);
		}
		if ($issubscribe == '1' && $counts >= 3 && $address3off < 101 && $address3off > 0) {
			$off = ($address3off / 100) * $off;
		}
		$useradd = $this->Session->read('useradd');
		$totalprice = 0;
		$grt = array();
		if (!empty($goodslist)) foreach ($goodslist as $k => $row) {
			$price = format_price($row['pifa_price'] * $off);
			$this->Session->write("cart.{$k}.price", $price);
			$zprice = $price * $row['number'];
			$this->Session->write("cart.{$k}.zprice", $zprice);
			$totalprice += $zprice;
			$grt[] = $price . ',' . $zprice . ',' . $row['goods_id'];
		}
		if (empty($grt)) {
			$result['error'] = 1;
			$result['message'] = "非法错误";
			die($json->encode($result));
		}
		$result = array('error' => 0, 'totalprice' => $totalprice, 'goods' => implode('|', $grt), 'message' => '');
		die($json->encode($result));
	}

	function ajax_change_carval($data = array())
	{
		$kk = $data['kk'];
		$gid = $data['gid'];
		$ty = explode('[', $data['type']);
		$type = $ty[0];
		$val = $data['val'];
		switch ($type) {
			case 'consignee':
				$this->Session->write("useradd.{$gid}.{$kk}.consignee", $val);
				break;
			case "moblie":
				$this->Session->write("useradd.{$gid}.{$kk}.moblie", $val);
				break;
			case "address":
				$this->Session->write("useradd.{$gid}.{$kk}.address", $val);
				break;
		}
	}

	function index()
	{
		$this->js('mycart.js');
		$this->title('我的购物车 - ' . $GLOBALS['LANG']['site_name']);
		$uid = $this->Session->read('User.uid');
		if (empty($uid)) {
			$this->jump(ADMIN_URL);
			exit;
		}
		$hear[] = '<a href="' . ADMIN_URL . '">首页</a>';
		$hear[] = '<a href="' . ADMIN_URL . 'mycart.php">我的购物车</a>';
		$rt['hear'] = implode('&nbsp;&gt;&nbsp;', $hear);
		$rt['discount'] = 100;
		$rank = $this->Session->read('User.rank');
		if ($rank > 0) {
			$sql = "SELECT discount FROM `{$this->App->prefix()}user_level` WHERE lid='$rank' LIMIT 1";
			$rt['discount'] = $this->App->findvar($sql);
		}
		$active = $this->Session->read('User.active');
		$goodslist = $this->Session->read('cart');
		$rt['goodslist'] = array();
		if (!empty($goodslist)) {
			foreach ($goodslist as $k => $row) {
				$rt['goodslist'][$k] = $row;
				$rt['goodslist'][$k]['goods_thumb'] = SITE_URL . $row['goods_thumb'];
				$rt['goodslist'][$k]['goods_img'] = SITE_URL . $row['goods_img'];
				$rt['goodslist'][$k]['original_img'] = SITE_URL . $row['original_img'];
				$comd = array();
				if (!empty($uid) && $active == '1') {
					$comd[] = $row['qianggou_price'];
					if ($rt['discount'] > 0) {
						$comd[] = ($rt['discount'] / 100) * $row['market_price'];
					}
					if ($row['shop_price'] > 0) {
						$comd[] = $row['shop_price'];
					}
				} else {
					$comd[] = $row['market_price'];
				}
				if ($row['is_promote'] == '1' && $row['promote_start_date'] < mktime() && $row['promote_end_date'] > mktime()) {
					$comd[] = $row['promote_price'];
				}
				if ($row['is_qianggou'] == '1' && $row['qianggou_start_date'] < mktime() && $row['qianggou_end_date'] > mktime()) {
					$comd[] = $row['qianggou_price'];
				}
				$onetotal = min($comd);
				if (intval($onetotal) <= 0) $onetotal = $row['market_price'];
				$total += ($row['number'] * $onetotal);
			}
			unset($goodslist);
		}
		if (!defined(NAVNAME)) define("NAVNAME", "购物车");
		$this->set('rt', $rt);
		$mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';
		$this->template($mb . '/mycart_list');
	}

	function checkout()
	{
		$this->action('common', 'checkjump');
		$uid = $this->Session->read('User.uid');
		$goodslist = $this->Session->read('cart');
		if ($_GET['up'] == 'up') {
			unset($goodslist);
			$this->Session->write('cart', null);
		}
		if (empty($goodslist) && !isset($_GET['up'])) {
			if (!defined(NAVNAME)) define('NAVNAME', "去购物吧");
			$mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';
			$this->template($mb . '/mycart_checkout_empty');
			exit;
		}
		$rt['province'] = $this->action('user', 'get_regions', 1);
		$sql = "SELECT ua.*,rg.region_name AS provincename,rg1.region_name AS cityname,rg2.region_name AS districtname FROM `{$this->App->prefix()}user_address` AS ua";
		$sql .= " LEFT JOIN `{$this->App->prefix()}region` AS rg ON rg.region_id = ua.province";
		$sql .= " LEFT JOIN `{$this->App->prefix()}region` AS rg1 ON rg1.region_id = ua.city";
		$sql .= " LEFT JOIN `{$this->App->prefix()}region` AS rg2 ON rg2.region_id = ua.district WHERE ua.user_id='$uid' AND ua.is_own='0' GROUP BY ua.address_id";
		$rt['userress'] = $this->App->find($sql);
		$sql = "SELECT * FROM `{$this->App->prefix()}payment` WHERE enabled='1'";
		$rt['paymentlist'] = $this->App->find($sql);
		$sql = "SELECT * FROM `{$this->App->prefix()}shipping`";
		$rt['shippinglist'] = $this->App->find($sql);
		$rt['discount'] = 100;
		$rank = $this->App->findvar("SELECT user_rank FROM `{$this->App->prefix()}user` WHERE user_id='$uid' LIMIT 1");
		$this->set('rank', $rank);
		if ($rank > 0) {
			$sql = "SELECT discount FROM `{$this->App->prefix()}user_level` WHERE lid='$rank' LIMIT 1";
			$rt['discount'] = $this->App->findvar($sql);
		}
		$sql = "SELECT * FROM `{$this->App->prefix()}userconfig` LIMIT 1";
		$rts = $this->App->findrow($sql);
		$sql = "SELECT is_subscribe FROM `{$this->App->prefix()}user` WHERE user_id='$uid' LIMIT 1";
		$issubscribe = $this->App->findvar($sql);
		$guanzhuoff = $rts['guanzhuoff'];
		$address3off = $rts['address3off'];
		$address2off = $rts['address2off'];
		$off = 1;
		if ($issubscribe == '1' && $guanzhuoff < 101 && $guanzhuoff > 0) {
			$off = ($guanzhuoff / 100);
		}
		$counts = 0;
		foreach ($cartlist as $k => $row) {
			$counts += $row['number'];
		}
		if ($issubscribe == '1' && $counts >= 2 && $address2off < 101 && $address2off > 0) {
			$off = ($address2off / 100) * $off;
		}
		if ($issubscribe == '1' && $counts >= 3 && $address3off < 101 && $address3off > 0) {
			$off = ($address3off / 100) * $off;
		}
		foreach ($goodslist as $k => $row) {
			$comd = array();
			$comd[] = format_price($row['pifa_price'] * $off);
			if ($row['is_promote'] == '1' && $row['promote_start_date'] < mktime() && $row['promote_end_date'] > mktime() && $row['promote_price'] > 0) {
				$comd[] = $row['promote_price'];
			}
			if ($row['is_qianggou'] == '1' && $row['qianggou_start_date'] < mktime() && $row['qianggou_end_date'] > mktime() && $row['qianggou_price'] > 0) {
				$comd[] = $row['qianggou_price'];
			}
			$price = min($comd);
			$this->Session->write("cart.{$k}.price", $price);
		}
		$sql = "SELECT mygouwubi FROM `{$this->App->prefix()}user` WHERE user_id='$uid' LIMIT 1";
		$rt['mygouwubi'] = $this->App->findvar($sql);
		if (empty($rt['mygouwubi'])) $rt['mygouwubi'] = '0.00';
		$this->set('rt', $rt);
		$mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';
		if ($_GET['up'] == 'up') {
			$goods_id = $_GET['goods_id'];
			$sql = "SELECT * FROM `{$this->App->prefix()}goods` WHERE `up_goods`>0 AND `is_delete`=0 ORDER BY `up_goods` ASC";
			$goods = $this->App->find($sql);
			$maxmoney = 0;
			$sql = "SELECT `order_amount` FROM `{$this->App->prefix()}goods_order_info` WHERE `user_id`=$uid AND `pay_status`=1 ORDER BY `up_goods` DESC LIMIT 1";
			$maxmoney = $this->App->findvar($sql);
			$goods[0]['pifa_price'] = $goods[0]['pifa_price'] - $maxmoney;
			$goods[1]['pifa_price'] = $goods[1]['pifa_price'] - $maxmoney;
			$goods[2]['pifa_price'] = $goods[2]['pifa_price'] - $maxmoney;
			$this->set('goods', $goods);
			if (empty($goods_id) && !isset($goods_id)) {
				$goods_id = $goods[0]['goods_id'];
			}
			$sql = "SELECT * FROM `{$this->App->prefix()}goods` WHERE `up_goods`>0 AND `is_delete`=0 AND `goods_id`=$goods_id ORDER BY `up_goods` ASC";
			$ngoods = $this->App->findrow($sql);
			$ngoods['pifa_price'] = $ngoods['pifa_price'] - $maxmoney;
			if (empty($goods_id) && !isset($goods_id)) {
				$ngoods['goods_id'] = $goods_id;
			}
			$this->set('ngoods', $ngoods);
			$this->title('升级经销商 - ' . $GLOBALS['LANG']['site_name']);
			if (!defined(NAVNAME)) define("NAVNAME", "升级经销商");
			$this->template($mb . '/mycart_checkout_up');
		} else {
			if (!defined(NAVNAME)) define("NAVNAME", "确认订单");
			$this->title('确认订单 - ' . $GLOBALS['LANG']['site_name']);
			$this->template($mb . '/mycart_checkout');
		}
	}

	function confirm()
	{
		$this->title('我的购物车 - 订单号 - ' . $GLOBALS['LANG']['site_name']);
		$uid = $this->Session->read('User.uid');
		if (empty($uid)) {
			$this->jump(ADMIN_URL . 'user.php?act=login', 0, '请先登录！');
			exit;
		}
		if (isset($_POST) && !empty($_POST)) {
			$orderdata['up_goods'] = $_POST['up_goods'];
			$cartlist = $this->Session->read('cart');
			if (empty($cartlist)) {
				$this->jump(ADMIN_URL . 'mycart.php', 0, '购物车商品为空!');
				exit;
			}
			$shipping_id = isset($_POST['shipping_id']) ? $_POST['shipping_id'] : 0;
			$userress_id = isset($_POST['userress_id']) ? $_POST['userress_id'] : 0;
			$dd = array();
			if (!($userress_id > 0)) {
				$dd['user_id'] = $uid;
				$dd['consignee'] = $_POST['consignee'];
				if (empty($dd['consignee'])) {
					$this->jump(ADMIN_URL . 'mycart.php?type=checkout', 0, '收货人不能为空！');
					exit;
				}
				$dd['country'] = 1;
				$dd['province'] = $_POST['province'];
				$dd['city'] = $_POST['city'];
				$dd['district'] = $_POST['district'];
				$dd['address'] = $_POST['address'];
				if (empty($dd['province']) || empty($dd['city']) || empty($dd['district']) || empty($dd['address'])) {
					$this->jump(ADMIN_URL . 'mycart.php?type=checkout', 0, '收货地址不能为空！');
					exit;
				}
				$dd['mobile'] = $_POST['mobile'];
				$dd['is_default'] = '1';
				$dd['shoppingname'] = $shipping_id;
				$this->App->update('user_address', array('is_default' => '0'), 'user_id', $uid);
				$this->App->insert('user_address', $dd);
				$userress_id = $this->App->iid();
				if (!($userress_id > 0)) {
					$this->jump(ADMIN_URL . 'mycart.php?type=checkout', 0, '非法的收货地址！');
					exit;
				}
			}
			$pay_id = isset($_POST['pay_id']) ? $_POST['pay_id'] : 0;
			$pay_name = $this->App->findvar("SELECT pay_name FROM `{$this->App->prefix()}payment` WHERE pay_id='$pay_id' LIMIT 1");
			$postscript = isset($_POST['postscript']) ? $_POST['postscript'] : "";
			if (empty($dd)) {
				$sql = "SELECT * FROM `{$this->App->prefix()}user_address` WHERE address_id='$userress_id' LIMIT 1";
				$user_ress = $this->App->findrow($sql);
				if (empty($user_ress)) {
					$this->jump(ADMIN_URL . 'mycart.php?type=checkout', 0, '非法收货地址！');
					exit;
				}
			} else {
				$user_ress = $dd;
				unset($dd);
			}
			$shipping_name = $this->App->findvar("SELECT shipping_name FROM `{$this->App->prefix()}shipping` WHERE shipping_id='$shipping_id' LIMIT 1");
			$orderdata['order_sn'] = date('Y', mktime()) . mktime();
			$orderdata['user_id'] = $uid ? $uid : 0;
			$daili_uid = $this->return_daili_uid($uid);
			$orderdata['parent_uid'] = $daili_uid;
			if ($daili_uid > 0) {
				$sql = "SELECT p1_uid,p2_uid,p3_uid FROM `{$this->App->prefix()}user_tuijian_fx` WHERE uid ='$daili_uid' LIMIT 1";
				$pr = $this->App->findrow($sql);
				$parent_uid = isset($pr['p1_uid']) ? $pr['p1_uid'] : 0;
				$orderdata['parent_uid2'] = $parent_uid > 0 && $parent_uid != $daili_uid ? $parent_uid : '0';
				$parent_uid = isset($pr['p2_uid']) ? $pr['p2_uid'] : 0;
				$orderdata['parent_uid3'] = $parent_uid > 0 && $parent_uid != $daili_uid ? $parent_uid : '0';
				$parent_uid = isset($pr['p3_uid']) ? $pr['p3_uid'] : 0;
				$orderdata['parent_uid4'] = $parent_uid > 0 && $parent_uid != $daili_uid ? $parent_uid : '0';
			}
			$orderdata['consignee'] = $user_ress['consignee'] ? $user_ress['consignee'] : "";
			$orderdata['province'] = $user_ress['province'] ? $user_ress['province'] : 0;
			$orderdata['city'] = $user_ress['city'] ? $user_ress['city'] : 0;
			$orderdata['district'] = $user_ress['district'] ? $user_ress['district'] : 0;
			$orderdata['address'] = $user_ress['address'] ? $user_ress['address'] : "";
			$orderdata['mobile'] = $user_ress['mobile'] ? $user_ress['mobile'] : "";
			$orderdata['shipping_id'] = $shipping_id;
			$orderdata['shipping_name'] = $shipping_name;
			if (isset($_POST['best_time']) && !empty($_POST['best_time'])) {
				$orderdata['best_time'] = trim($_POST['best_time']);
			}
			$orderdata['pay_id'] = $pay_id ? $pay_id : 0;
			$orderdata['pay_name'] = $pay_name ? $pay_name : "";
			$orderdata['postscript'] = $postscript;
			$discount = 100;
			$rank = $this->App->findvar("SELECT user_rank FROM `{$this->App->prefix()}user` WHERE user_id='$uid' LIMIT 1");
			if ($rank > 0) {
				$sql = "SELECT discount FROM `{$this->App->prefix()}user_level` WHERE lid='$rank' LIMIT 1";
				$discount = $this->App->findvar($sql);
			}
			$k = 0;
			$total = 0;
			$jifen_onetotal = 0;
			$sql = "SELECT * FROM `{$this->App->prefix()}userconfig` LIMIT 1";
			$rts = $this->App->findrow($sql);
			$sql = "SELECT is_subscribe FROM `{$this->App->prefix()}user` WHERE user_id='$uid' LIMIT 1";
			$issubscribe = $this->App->findvar($sql);
			$guanzhuoff = $rts['guanzhuoff'];
			$address3off = $rts['address3off'];
			$address2off = $rts['address2off'];
			$off = 1;
			if ($issubscribe == '1' && $guanzhuoff < 101 && $guanzhuoff > 0) {
				$off = ($guanzhuoff / 100);
			}
			$counts = 0;
			foreach ($cartlist as $k => $row) {
				$counts += $row['number'];
			}
			if ($issubscribe == '1' && $counts >= 2 && $address2off < 101 && $address2off > 0) {
				$off = ($address2off / 100) * $off;
			}
			if ($issubscribe == '1' && $counts >= 3 && $address3off < 101 && $address3off > 0) {
				$off = ($address3off / 100) * $off;
			}
			foreach ($cartlist as $row) {
				$data[$k]['goods_id'] = $row['goods_id'];
				$data[$k]['brand_id'] = $row['brand_id'];
				$data[$k]['goods_name'] = $row['goods_name'];
				$data[$k]['goods_bianhao'] = $row['goods_bianhao'];
				$data[$k]['goods_thumb'] = $row['goods_thumb'];
				$data[$k]['goods_sn'] = $row['goods_sn'];
				$data[$k]['goods_number'] = $row['number'];
				if (!empty($row['buy_more_best'])) {
					$data[$k]['buy_more_best'] = $row['buy_more_best'];
				}
				$mprice = $row['shop_price'];
				$onetotal = $row['price'];
				$mprices += $mprice * $row['number'];
				$total += $row['number'] * $onetotal;
				if ($row['takemoney1'] > 0) $data[$k]['takemoney1'] = $row['takemoney1'];
				if ($row['takemoney2'] > 0) $data[$k]['takemoney2'] = $row['takemoney2'];
				if ($row['takemoney3'] > 0) $data[$k]['takemoney3'] = $row['takemoney3'];
				$data[$k]['market_price'] = $mprice;
				$data[$k]['goods_price'] = $onetotal;
				$data[$k]['goods_attr'] = !empty($row['spec']) ? $row['goods_brief'] . implode("<br />", $row['spec']) : $row['goods_brief'];
				$data[$k]['goods_unit'] = $row['goods_unit'];
				if (isset($_POST['confirm_jifen']) && intval($_POST['confirm_jifen']) > 0) {
					if ($row['is_jifen_session'] == '1') {
						$data[$k]['from_jifen'] = $row['need_jifen'] * $row['number'];
						$jifen_onetotal += $s;
					}
				}
				$k++;
				if (!empty($row['gifts'])) {
					$data[$k]['goods_id'] = $row['gifts']['goods_id'];
					$data[$k]['brand_id'] = $row['gifts']['brand_id'];
					$data[$k]['goods_name'] = '<span style="color:#FE0000">[赠品]</span>' . $row['gifts']['goods_name'];
					$data[$k]['goods_bianhao'] = $row['gifts']['goods_bianhao'];
					$data[$k]['goods_thumb'] = $row['goods_thumb'];
					$data[$k]['goods_sn'] = $row['gifts']['goods_sn'] . '-gift';
					$data[$k]['goods_number'] = $row['gifts']['number'];
					$data[$k]['market_price'] = $row['gifts']['shop_price'];
					$data[$k]['goods_price'] = $row['gifts']['shop_price'];
					$data[$k]['goods_attr'] = !empty($row['gifts']['spec']) ? implode("<br />", $row['gifts']['spec']) : "";
					$data[$k]['goods_unit'] = $row['gifts']['goods_unit'];
					$data[$k]['is_gift'] = 1;
					$k++;
				}
			}
			if (!($total > 0)) {
			}
			$d = array('userress_id' => $userress_id, 'shopping_id' => $shipping_id);
			$fr = $this->ajax_jisuan_shopping($d, 'cart');
			$n = ($fr > 0) ? format_price($fr) : '0';
			$orderdata['goods_amount'] = format_price($mprices);
			$orderdata['order_amount'] = format_price($total * ($discount / 100));
			if (isset($_POST['xinghao']) && !empty($_POST['xinghao'])) {
				$orderdata['order_amount'] = $_POST['xinghao'];
				$orderdata['up_goods'] = $_POST['up_goods'];
			}
			$orderdata['add_time'] = mktime();
			$orderdata['shipping_fee'] = $n;
			if (isset($_POST['confirm_jifen']) && $_POST['confirm_jifen'] > 0 && intval($jifen_onetotal) > 0) {
				$orderdata['goods_amount'] = $orderdata['goods_amount'] - $jifen_onetotal;
				$orderdata['order_amount'] = $orderdata['order_amount'] - $jifen_onetotal;
				$this->App->insert('user_point_change', array('time' => mktime(), 'changedesc' => "用积分兑换商品", 'uid' => $uid, 'points' => -intval($_POST['confirm_jifen'])));
			}
			if ($this->App->insert('goods_order_info', $orderdata)) {
				$iid = $this->App->iid();
				foreach ($data as $kk => $rows) {
					$rows['order_id'] = $iid;
					$this->App->insert('goods_order', $rows);
				}
				$this->_return_money($orderdata['order_sn']);
				$sql = "SELECT bonus_id FROM `{$this->App->prefix()}bonus_list` WHERE bonus_type_id='6' AND user_id='$uid' LIMIT 1";
				$bid = $this->App->findvar($sql);
				if (!($bid > 0)) {
					$sql = "SELECT bonus_id FROM `{$this->App->prefix()}bonus_list` WHERE bonus_type_id='6' AND user_id='0' ORDER BY bonus_id ASC LIMIT 1";
					$bid = $this->App->findvar($sql);
					if ($bid > 0) {
						if ($this->App->update('bonus_list', array('user_id' => $uid, 'order_id' => $iid), 'bonus_id', $bid)) {
							$appid = $this->Session->read('User.appid');
							if (empty($appid)) $appid = isset($_COOKIE[CFGH . 'USER']['APPID']) ? $_COOKIE[CFGH . 'USER']['APPID'] : '';
							$appsecret = $this->Session->read('User.appsecret');
							if (empty($appsecret)) $appsecret = isset($_COOKIE[CFGH . 'USER']['APPSECRET']) ? $_COOKIE[CFGH . 'USER']['APPSECRET'] : '';
							$pr = $this->App->findrow("SELECT wecha_id,nickname FROM `{$this->App->prefix()}user` WHERE user_id='$uid' LIMIT 1");
							$pwecha_id = isset($pr['wecha_id']) ? $pr['wecha_id'] : '';
							$nickname = isset($pr['nickname']) ? $pr['nickname'] : '';
							if (!empty($pwecha_id)) {
								$this->action('api', 'send', array('openid' => $pwecha_id, 'appid' => $appid, 'appsecret' => $appsecret, 'nickname' => $nickname), 'sendgift');
							}
						}
					}
				}
				$this->Session->write('cart', "");
				$pwecha_id = $this->App->findvar("SELECT wecha_id FROM `{$this->App->prefix()}user` WHERE user_id='$uid' AND is_subscribe='1' LIMIT 1");
				if (!empty($pwecha_id)) {
					$this->action('api', 'send', array('openid' => $pwecha_id, 'appid' => '', 'appsecret' => '', 'nickname' => ''), 'orderconfirm');
				}
				$wid = $this->App->findvar("SELECT wid FROM `{$this->App->prefix()}userconfig` WHERE type='basic' LIMIT 1");
				if ($wid > 0) {
					$pwecha_id = $this->App->findvar("SELECT wecha_id FROM `{$this->App->prefix()}user` WHERE user_id='$wid' AND is_subscribe='1' LIMIT 1");
					if (!empty($pwecha_id)) $this->action('api', 'send', array('openid' => $pwecha_id, 'appid' => '', 'appsecret' => '', 'nickname' => ''), 'orderconfirm_toshop');
				}
				$this->jump(ADMIN_URL . 'mycart.php?type=pay2&oid=' . $iid);
				exit;
				$rt['order_sn'] = $orderdata['order_sn'];
				$rt['shipping_name'] = $shipping_name;
				$rt['pay_name'] = $pay_name;
				$rt['total'] = format_price($orderdata['order_amount']);
				$rt['shipping_fee'] = $n;
				$rts['pay_id'] = $pay_id;
				$rts['order_sn'] = $rt['order_sn'];
				$rts['order_amount'] = $rt['total'];
				$rts['username'] = $orderdata['consignee'];
				$rts['logistics_fee'] = $rt['shipping_fee'];
				$sql = "SELECT ua.address,ua.zipcode,ua.tel,ua.mobile,rg.region_name AS provincename,rg1.region_name AS cityname,rg2.region_name AS districtname FROM `{$this->App->prefix()}user_address` AS ua";
				$sql .= " LEFT JOIN `{$this->App->prefix()}region` AS rg ON rg.region_id = ua.province";
				$sql .= " LEFT JOIN `{$this->App->prefix()}region` AS rg1 ON rg1.region_id = ua.city";
				$sql .= " LEFT JOIN `{$this->App->prefix()}region` AS rg2 ON rg2.region_id = ua.district WHERE ua.address_id='$userress_id' LIMIT 1";
				$userress = $this->App->findrow($sql);
				$rts['address'] = $userress['provincename'] . '&nbsp;' . $userress['cityname'] . '&nbsp;' . $userress['districtname'] . '&nbsp;' . $userress['address'];
				$rts['zip'] = !empty($userress['zipcode']) ? $userress['zipcode'] : $orderdata['zipcode'];
				$rts['phone'] = !empty($userress['tel']) ? $userress['tel'] : $orderdata['tel'];
				$rts['mobile'] = !empty($userress['mobile']) ? $userress['mobile'] : $orderdata['mobile'];
				$this->Session->write('cart', "");
				$this->_alipayment($rts);
				exit;
				$this->set('rt', $rt);
				$this->Session->write('cart', "");
				$this->template('mycart_submit_order');
				exit;
			} else {
				$this->jump(ADMIN_URL . 'mycart.php', 0, '您的订单没有提交成功，我们是尽快处理出现问题！');
				exit;
			}
		} else {
			$this->App->write('cart', "");
			$this->jump(ADMIN_URL . 'mycart.php');
		}
		$this->App->write('cart', "");
		$this->jump(ADMIN_URL . 'mycart.php', 0, '意外错误，我们是尽快处理出现问题！');
		exit;
	}

	function _return_money($order_sn = '')
	{
		@set_time_limit(300);
		$pu = $this->App->findrow("SELECT user_id,daili_uid,parent_uid,parent_uid2,parent_uid3,parent_uid4,goods_amount,order_amount,order_sn,pay_status,order_id FROM `{$this->App->prefix()}goods_order_info` WHERE order_sn='$order_sn' LIMIT 1");
		$parent_uid = isset($pu['parent_uid']) ? $pu['parent_uid'] : 0;
		$parent_uid2 = isset($pu['parent_uid2']) ? $pu['parent_uid2'] : 0;
		$parent_uid3 = isset($pu['parent_uid3']) ? $pu['parent_uid3'] : 0;
		$parent_uid4 = isset($pu['parent_uid4']) ? $pu['parent_uid4'] : 0;
		$daili_uid = isset($pu['daili_uid']) ? $pu['daili_uid'] : 0;
		$moeys = isset($pu['order_amount']) ? $pu['order_amount'] : 0;
		$uid = isset($pu['user_id']) ? $pu['user_id'] : 0;
		$pay_status = isset($pu['pay_status']) ? $pu['pay_status'] : 0;
		$order_id = isset($pu['order_id']) ? $pu['order_id'] : 0;
		$sql = "SELECT * FROM `{$this->App->prefix()}userconfig` LIMIT 1";
		$rts = $this->App->findrow($sql);
		if (!empty($order_sn)) {
			$sql = "SELECT takemoney1,takemoney2,takemoney3,goods_number FROM `{$this->App->prefix()}goods_order` WHERE order_id='$order_id'";
			$moneys = $this->App->find($sql);
			$thismonth = date('Y-m-d', mktime());
			$thism = date('Y-m', mktime());
			$moeysall = 0;
			if (!empty($moneys)) foreach ($moneys as $row) {
				if ($row['takemoney1'] > 0) {
					$moeysall += $row['takemoney1'] * $row['goods_number'];
				}
			}
			$moeys = 0;
			$ticheng360_1 = $rts['ticheng360_1'];
			if ($ticheng360_1 > 0) {
				$off = $ticheng360_1 / 100;
				$moeys = format_price($moeysall * $off);
				if ($moeys > 0) {
					$this->App->insert('user_money_change_cache', array('buyuid' => $uid, 'order_sn' => $order_sn, 'thismonth' => $thismonth, 'thism' => $thism, 'money' => $moeys, 'changedesc' => '消费返佣金', 'time' => mktime(), 'uid' => $uid, 'level' => '10'));
				}
			}
			$record = array();
			$moeys = 0;
			if ($parent_uid > 0) {
				$sql = "SELECT user_rank FROM `{$this->App->prefix()}user` WHERE user_id='$parent_uid' LIMIT 1";
				$rank = $this->App->findvar($sql);
				if ($rank != '1') {
					$off = 0;
					if ($rank == '12') {
						if ($rts['ticheng180_1'] < 101 && $rts['ticheng180_1'] > 0) {
							$off = $rts['ticheng180_1'] / 100;
						}
					} elseif ($rank == '11') {
						if ($rts['ticheng180_h1_1'] < 101 && $rts['ticheng180_h1_1'] > 0) {
							$off = $rts['ticheng180_h1_1'] / 100;
						}
					} elseif ($rank == '10') {
						if ($rts['ticheng180_h2_1'] < 101 && $rts['ticheng180_h2_1'] > 0) {
							$off = $rts['ticheng180_h2_1'] / 100;
						}
					} elseif ($rank == '9') {
						if ($rts['ticheng180_h3_1'] < 101 && $rts['ticheng180_h3_1'] > 0) {
							$off = $rts['ticheng180_h3_1'] / 100;
						}
					}
					$moeys = format_price($moeysall * $off);
					if (!empty($moeys)) {
						$this->App->insert('user_money_change_cache', array('buyuid' => $uid, 'order_sn' => $order_sn, 'thismonth' => $thismonth, 'thism' => $thism, 'money' => $moeys, 'changedesc' => '购买商品返佣金', 'time' => mktime(), 'uid' => $parent_uid, 'level' => '1'));
					}
				}
			}
			$moeys = 0;
			if ($parent_uid2 > 0) {
				$sql = "SELECT user_rank FROM `{$this->App->prefix()}user` WHERE user_id='$parent_uid2' LIMIT 1";
				$rank = $this->App->findvar($sql);
				if ($rank != '1') {
					$off = 0;
					if ($rank == '12') {
						if ($rts['ticheng180_2'] < 101 && $rts['ticheng180_2'] > 0) {
							$off = $rts['ticheng180_2'] / 100;
						}
					} elseif ($rank == '11') {
						if ($rts['ticheng180_h1_2'] < 101 && $rts['ticheng180_h1_2'] > 0) {
							$off = $rts['ticheng180_h1_2'] / 100;
						}
					} elseif ($rank == '10') {
						if ($rts['ticheng180_h2_2'] < 101 && $rts['ticheng180_h2_2'] > 0) {
							$off = $rts['ticheng180_h2_2'] / 100;
						}
					}
					$moeys = format_price($moeysall * $off);
					if (!empty($moeys)) {
						$this->App->insert('user_money_change_cache', array('buyuid' => $uid, 'order_sn' => $order_sn, 'thismonth' => $thismonth, 'thism' => $thism, 'money' => $moeys, 'changedesc' => '购买商品返佣金', 'time' => mktime(), 'uid' => $parent_uid2, 'level' => '2'));
					}
				}
			}
			$moeys = 0;
			if ($parent_uid3 > 0) {
				$sql = "SELECT user_rank FROM `{$this->App->prefix()}user` WHERE user_id='$parent_uid3' LIMIT 1";
				$rank = $this->App->findvar($sql);
				if ($rank != '1') {
					$off = 0;
					if ($rank == '12') {
						if ($rts['ticheng180_3'] < 101 && $rts['ticheng180_3'] > 0) {
							$off = $rts['ticheng180_3'] / 100;
						}
					} elseif ($rank == '11') {
						if ($rts['ticheng180_h1_3'] < 101 && $rts['ticheng180_h1_3'] > 0) {
							$off = $rts['ticheng180_h1_3'] / 100;
						}
					} elseif ($rank == '10') {
						if ($rts['ticheng180_h2_3'] < 101 && $rts['ticheng180_h2_3'] > 0) {
							$off = $rts['ticheng180_h2_3'] / 100;
						}
					}
					$moeys = format_price($moeysall * $off);
					if (!empty($moeys)) {
						$this->App->insert('user_money_change_cache', array('buyuid' => $uid, 'order_sn' => $order_sn, 'thismonth' => $thismonth, 'thism' => $thism, 'money' => $moeys, 'changedesc' => '购买商品返佣金', 'time' => mktime(), 'uid' => $parent_uid3, 'level' => '3'));
					}
				}
			}
			$moeys = 0;
			if ($parent_uid4 > 0) {
				$sql = "SELECT user_rank FROM `{$this->App->prefix()}user` WHERE user_id='$parent_uid4' LIMIT 1";
				$rank = $this->App->findvar($sql);
				if ($rank != '1') {
					$off = 0;
					if ($rank == '12') {
						if ($rts['ticheng180_4'] < 101 && $rts['ticheng180_4'] > 0) {
							$off = $rts['ticheng180_4'] / 100;
						}
					} elseif ($rank == '11') {
						if ($rts['ticheng180_h1_4'] < 101 && $rts['ticheng180_h1_4'] > 0) {
							$off = $rts['ticheng180_h1_4'] / 100;
						}
					} elseif ($rank == '10') {
						if ($rts['ticheng180_h2_4'] < 101 && $rts['ticheng180_h2_4'] > 0) {
							$off = $rts['ticheng180_h2_4'] / 100;
						}
					}
					$moeys = format_price($moeysall * $off);
					if (!empty($moeys)) {
						$this->App->insert('user_money_change_cache', array('buyuid' => $uid, 'order_sn' => $order_sn, 'thismonth' => $thismonth, 'thism' => $thism, 'money' => $moeys, 'changedesc' => '购买商品返佣金', 'time' => mktime(), 'uid' => $parent_uid4, 'level' => '4'));
					}
				}
			}
		}
	}

	function fastcheckout()
	{
		$oid = $_POST['order_id'];
		$uid = $this->Session->read('User.uid');
		$sql = "SELECT * FROM `{$this->App->prefix()}goods_order_info` WHERE pay_status = '0' AND order_id='$oid'";
		$rt = $this->App->findrow($sql);
		if (empty($rt)) {
			$this->jump(ADMIN_URL, 0, '非法支付提交！');
			exit;
		}
		$rts['pay_id'] = $rt['pay_id'];
		$rts['order_sn'] = $rt['order_sn'];
		$rts['order_amount'] = $rt['order_amount'];
		$rts['username'] = $orderdata['consignee'];
		$rts['logistics_fee'] = $rt['shipping_fee'];
		$sql = "SELECT ua.address,ua.zipcode,ua.tel,ua.mobile,rg.region_name AS provincename,rg1.region_name AS cityname,rg2.region_name AS districtname FROM `{$this->App->prefix()}goods_order_info` AS ua";
		$sql .= " LEFT JOIN `{$this->App->prefix()}region` AS rg ON rg.region_id = ua.province";
		$sql .= " LEFT JOIN `{$this->App->prefix()}region` AS rg1 ON rg1.region_id = ua.city";
		$sql .= " LEFT JOIN `{$this->App->prefix()}region` AS rg2 ON rg2.region_id = ua.district WHERE ua.order_id='$oid' LIMIT 1";
		$userress = $this->App->findrow($sql);
		$rts['address'] = $userress['provincename'] . '&nbsp;' . $userress['cityname'] . '&nbsp;' . $userress['districtname'] . '&nbsp;' . $userress['address'];
		$rts['zip'] = !empty($userress['zipcode']) ? $userress['zipcode'] : $orderdata['zipcode'];
		$rts['phone'] = !empty($userress['tel']) ? $userress['tel'] : $orderdata['tel'];
		$rts['mobile'] = !empty($userress['mobile']) ? $userress['mobile'] : $orderdata['mobile'];
		$this->_alipayment($rts);
		unset($rt);
		exit;
	}

	function ajax_change_price($data = array())
	{
		$json = Import::json();
		$id = $data['id'];
		$number = $data['number'];
		$shipping_id = $data['shipping_id'];
		$userress_id = $data['userress_id'];
		$maxnumber = $this->Session->read("cart.{$id}.goods_number");
		if ($number > $maxnumber) {
			$result = array('error' => 2, 'message' => "购买数量已经超过了库存，您最大只能购买:" . $maxnumber);
			die($json->encode($result));
		}
		$is_alone_sale = $this->Session->read("cart.{$id}.is_alone_sale");
		if (!empty($is_alone_sale)) {
			$this->Session->write("cart.{$id}.number", $number);
		}
		$uid = $this->Session->read('User.uid');
		$cartlist = $this->Session->read('cart');
		$sql = "SELECT * FROM `{$this->App->prefix()}userconfig` LIMIT 1";
		$rts = $this->App->findrow($sql);
		$sql = "SELECT is_subscribe FROM `{$this->App->prefix()}user` WHERE user_id='$uid' LIMIT 1";
		$issubscribe = $this->App->findvar($sql);
		$guanzhuoff = $rts['guanzhuoff'];
		$address3off = $rts['address3off'];
		$address2off = $rts['address2off'];
		$prices = 0;
		$thisprice = 0;
		$off = 1;
		if ($issubscribe == '1' && $guanzhuoff < 101 && $guanzhuoff > 0) {
			$off = ($guanzhuoff / 100);
		}
		$counts = 0;
		foreach ($cartlist as $k => $row) {
			$counts += $row['number'];
		}
		if ($issubscribe == '1' && $counts >= 2 && $address2off < 101 && $address2off > 0) {
			$off = ($address2off / 100) * $off;
		}
		if ($issubscribe == '1' && $counts >= 3 && $address3off < 101 && $address3off > 0) {
			$off = ($address3off / 100) * $off;
		}
		foreach ($cartlist as $k => $row) {
			$comd = array();
			$comd[] = format_price($row['pifa_price'] * $off);
			if ($row['is_promote'] == '1' && $row['promote_start_date'] < mktime() && $row['promote_end_date'] > mktime() && $row['promote_price'] > 0) {
				$comd[] = $row['promote_price'];
			}
			if ($row['is_qianggou'] == '1' && $row['qianggou_start_date'] < mktime() && $row['qianggou_end_date'] > mktime() && $row['qianggou_price'] > 0) {
				$comd[] = $row['qianggou_price'];
			}
			$price = min($comd);
			$this->Session->write("cart.{$k}.price", $price);
			if ($id == $k) {
				$thisprice = $price;
			}
			$prices += $price * $row['number'];
		}
		$prices = format_price($prices);
		unset($cartlist);
		$f = $this->ajax_jisuan_shopping(array('shopping_id' => $shipping_id, 'userress_id' => $userress_id), 'cart');
		$f = empty($f) ? '0' : $f;
		unset($cartlist);
		$result = array('error' => 0, 'message' => '1', 'prices' => $prices, 'thisprice' => $thisprice, 'freemoney' => $f);
		die($json->encode($result));
	}

	function ajax_change_jifen($is_confirm = 'true')
	{
		$uid = $this->Session->read('User.uid');
		$active = $this->Session->read('User.active');
		$discount = 100;
		$rank = $this->Session->read('User.rank');
		if ($rank > 0) {
			$sql = "SELECT discount FROM `{$this->App->prefix()}user_level` WHERE lid='$rank' LIMIT 1";
			$discount = $this->App->findvar($sql);
		}
		$cartlist = $this->Session->read('cart');
		$total = 0;
		if (!empty($cartlist)) {
			foreach ($cartlist as $row) {
				$comd = array();
				if (!empty($uid) && $active == '1') {
					if ($discount > 0) {
						$comd[] = ($discount / 100) * $row['market_price'];
					}
					if ($row['shop_price'] > 0) {
						$comd[] = $row['shop_price'];
					}
				} else {
					$comd[] = $row['market_price'];
				}
				if ($row['is_promote'] == '1' && $row['promote_start_date'] < mktime() && $row['promote_end_date'] > mktime()) {
					$comd[] = $row['promote_price'];
				}
				if ($row['is_qianggou'] == '1' && $row['qianggou_start_date'] < mktime() && $row['qianggou_end_date'] > mktime()) {
					$comd[] = $row['qianggou_price'];
				}
				$onetotal = $row['pifa_price'];
				$total += ($row['number'] * $onetotal);
				$jifen_onetotal += $row['number'] * $onetotal;
			}
		}
		unset($cartlist);
		$sql = "SELECT SUM(points) FROM `{$this->App->prefix()}user_point_change` WHERE uid='$uid'";
		$mypoints = $this->App->findvar($sql);
		if ($is_confirm == 'true') {
			if ($mypoints >= $jifen_onetotal) {
				echo $total - $jifen_onetotal;
			} else {
				echo $total - $mypoints;
			}
		} else {
			echo $total;
		}
		exit;
	}

	function ajax_jisuan_shopping($data = array(), $tt = 'ajax')
	{
		$shopping_id = isset($data['shopping_id']) ? $data['shopping_id'] : 0;
		$userress_id = isset($data['userress_id']) ? $data['userress_id'] : 0;
		if (!($userress_id > 0)) {
			if ($tt == 'ajax') {
				die("请选择一个收货地址！");
			} else {
				return "0";
			}
		}
		if (!($shopping_id > 0)) {
			if ($tt == 'ajax') {
				die("请选择一个配送方式！");
			} else {
				return "0";
			}
		}
		$sql = "SELECT country,province,city,district FROM `{$this->App->prefix()}user_address` WHERE address_id='$userress_id'";
		$ids = $this->App->findrow($sql);
		if (empty($ids)) {
			if ($tt == 'ajax') {
				die("请先设置一个收货地址！");
			} else {
				return "请先设置一个收货地址！";
			}
		}
		$cartlist = $this->Session->read('cart');
		$items = 0;
		$weights = 0;
		if (!empty($cartlist)) {
			foreach ($cartlist as $row) {
				if ($row['is_shipping'] == '1' || $row['is_alone_sale'] == '0') continue;
				$items += $row['number'];
				$weights += $row['goods_weight'];
			}
		}
		$weights = $weights * $items;
		$sql = "SELECT * FROM `{$this->App->prefix()}shipping_area` WHERE shipping_id='$shopping_id'";
		$area_rt = $this->App->find($sql);
		if (!empty($area_rt)) {
			foreach ($area_rt as $row) {
				if (!empty($row['configure'])) {
					$configure = json_decode($row['configure']);
					if (is_array($configure)) {
						$type = $row['type'];
						$item_fee = $row['item_fee'];
						$weight_fee = $row['weight_fee'];
						$step_weight_fee = $row['step_weight_fee'];
						$step_item_fee = $row['step_item_fee'];
						$max_money = $row['max_money'];
						if (in_array($ids['district'], $configure)) {
							if ($type == 'item') {
								$zyoufei = $item_fee + (($items - 1) * $step_item_fee);
								if ($zyoufei > $max_money && intval($max_money) > 0) $zyoufei = $max_money;
								if ($tt == 'ajax') {
									die($row['shipping_area_name'] . '+' . $zyoufei);
								} else {
									return $zyoufei;
								}
							} elseif ($type == 'weight') {
								if ($weights > 500) {
									$zyoufei = $weight_fee + ((ceil(($weights - 500) / 500)) * $step_weight_fee);
									if ($zyoufei > $max_money && intval($max_money) > 0) $zyoufei = $max_money;
									if ($tt == 'ajax') {
										die($row['shipping_area_name'] . '+' . $zyoufei);
									} else {
										return $zyoufei;
									}
								} else {
									if (!($weights > 0)) $weight_fee = '0.00';
									if ($tt == 'ajax') {
										die($row['shipping_area_name'] . '+' . $weight_fee);
									} else {
										return $weight_fee;
									}
								}
							}
							break;
						} elseif (in_array($ids['city'], $configure)) {
							if ($type == 'item') {
								$zyoufei = $item_fee + (($items - 1) * $step_item_fee);
								if ($zyoufei > $max_money && intval($max_money) > 0) $zyoufei = $max_money;
								if ($tt == 'ajax') {
									die($row['shipping_area_name'] . '+' . $zyoufei);
								} else {
									return $zyoufei;
								}
							} elseif ($type == 'weight') {
								if ($weights > 500) {
									$zyoufei = $weight_fee + ((ceil(($weights - 500) / 500)) * $step_weight_fee);
									if ($zyoufei > $max_money && intval($max_money) > 0) $zyoufei = $max_money;
									if ($tt == 'ajax') {
										die($row['shipping_area_name'] . '+' . $zyoufei);
									} else {
										return $zyoufei;
									}
								} else {
									if (!($weights > 0)) $weight_fee = '0';
									if ($tt == 'ajax') {
										die($row['shipping_area_name'] . '+' . $weight_fee);
									} else {
										return $weight_fee;
									}
								}
							}
							break;
						} elseif (in_array($ids['province'], $configure)) {
							if ($type == 'item') {
								$zyoufei = $item_fee + (($items - 1) * $step_item_fee);
								if ($zyoufei > $max_money && intval($max_money) > 0) $zyoufei = $max_money;
								if ($tt == 'ajax') {
									die($row['shipping_area_name'] . '+' . $zyoufei);
								} else {
									return $zyoufei;
								}
							} elseif ($type == 'weight') {
								if ($weights > 500) {
									$zyoufei = $weight_fee + ((ceil(($weights - 500) / 500)) * $step_weight_fee);
									if ($zyoufei > $max_money && intval($max_money) > 0) $zyoufei = $max_money;
									if ($tt == 'ajax') {
										die($row['shipping_area_name'] . '+' . $zyoufei);
									} else {
										return $zyoufei;
									}
								} else {
									if (!($weights > 0)) $weight_fee = '0';
									if ($tt == 'ajax') {
										die($row['shipping_area_name'] . '+' . $weight_fee);
									} else {
										return $weight_fee;
									}
								}
							}
							break;
						} elseif (in_array($ids['country'], $configure)) {
							if ($type == 'item') {
								$zyoufei = $item_fee + (($items - 1) * $step_item_fee);
								if ($zyoufei > $max_money && intval($max_money) > 0) $zyoufei = $max_money;
								if ($tt == 'ajax') {
									die($row['shipping_area_name'] . '+' . $zyoufei);
								} else {
									return $zyoufei;
								}
							} elseif ($type == 'weight') {
								if ($weights > 500) {
									$zyoufei = $weight_fee + ((ceil(($weights - 500) / 500)) * $step_weight_fee);
									if ($zyoufei > $max_money && intval($max_money) > 0) $zyoufei = $max_money;
									if ($tt == 'ajax') {
										die($row['shipping_area_name'] . '+' . $zyoufei);
									} else {
										return $zyoufei;
									}
								} else {
									if (!($weights > 0)) $weight_fee = '0';
									if ($tt == 'ajax') {
										die($row['shipping_area_name'] . '+' . $weight_fee);
									} else {
										return $weight_fee;
									}
								}
							}
							break;
						}
					}
				}
			}
		} else {
			if ($tt == 'ajax') {
				die("");
			} else {
				return $zyoufei;
			}
		}
		if ($tt == 'ajax') {
			die("");
		} else {
			return $zyoufei;
		}
	}

	function ajax_delcart_goods($id = 0)
	{
		if (!empty($id)) {
			$cartlist = $this->Session->read('cart');
			if (isset($cartlist[$id])) {
				$this->Session->write("cart.{$id}", "");
			}
			unset($cartlist);
		}
		$uid = $this->Session->read('User.uid');
		$rt['discount'] = 100;
		$rank = $this->Session->read('User.rank');
		if ($rank > 0) {
			$sql = "SELECT discount FROM `{$this->App->prefix()}user_level` WHERE lid='$rank' LIMIT 1";
			$rt['discount'] = $this->App->findvar($sql);
		}
		$active = $this->Session->read('User.active');
		$goodslist = $this->Session->read('cart');
		$rt['goodslist'] = array();
		if (!empty($goodslist)) {
			foreach ($goodslist as $k => $row) {
				$rt['goodslist'][$k] = $row;
				$rt['goodslist'][$k]['url'] = get_url($row['goods_name'], $row['goods_id'], 'product.php?id=' . $row['goods_id'], 'goods', array('product', 'index', $row['goods_id']));
				$rt['goodslist'][$k]['goods_thumb'] = SITE_URL . $row['goods_thumb'];
				$rt['goodslist'][$k]['goods_img'] = SITE_URL . $row['goods_img'];
				$rt['goodslist'][$k]['original_img'] = SITE_URL . $row['original_img'];
				$comd = array();
				if (!empty($uid) && $active == '1') {
					$comd[] = $row['market_price'];
					if ($rt['discount'] > 0) {
						$comd[] = ($rt['discount'] / 100) * $row['market_price'];
					}
					if ($row['shop_price'] > 0) {
						$comd[] = $row['shop_price'];
					}
				} else {
					$comd[] = $row['market_price'];
				}
				if ($row['is_promote'] == '1' && $row['promote_start_date'] < mktime() && $row['promote_end_date'] > mktime()) {
					$comd[] = $row['promote_price'];
				}
				if ($row['is_qianggou'] == '1' && $row['qianggou_start_date'] < mktime() && $row['qianggou_end_date'] > mktime()) {
					$comd[] = $row['qianggou_price'];
				}
				$onetotal = min($comd);
				if (intval($onetotal) <= 0) $onetotal = $row['market_price'];
				$total += ($row['number'] * $onetotal);
			}
			unset($goodslist);
		}
		$fn = SYS_PATH . 'data/goods_spend_gift.php';
		$spendgift = array();
		if (file_exists($fn) && is_file($fn)) {
			include_once($fn);
		}
		$rt['gift_typesd'] = $spendgift;
		unset($spendgift);
		$minspend = array();
		if (!empty($rt['gift_typesd'])) {
			foreach ($rt['gift_typesd'] as $k => $row) {
				++$k;
				$minspend[$k] = $row['minspend'];
			}
			arsort($minspend);
		}
		$rt['gift_goods'] = array();
		$type = 0;
		if (count($minspend) > 0) {
			$count = count($minspend);
			foreach ($minspend as $t => $val) {
				if ($total >= $val) {
					$type = $t;
					break;
				}
			}
			unset($minspend);
			$rt['gift_goods_ids'] = array();
			if ($type > 0) {
				$sql = "SELECT tb2.goods_id,tb1.type,tb2.goods_name,tb2.market_price,tb2.goods_sn ,tb2.goods_bianhao,tb2.goods_thumb  FROM `{$this->App->prefix()}goods_gift` AS tb1";
				$sql .= " LEFT JOIN `{$this->App->prefix()}goods` AS tb2 ON tb1.goods_id=tb2.goods_id";
				$sql .= " WHERE (tb2.is_alone_sale='0' OR tb2.is_alone_sale IS NOT NULL) AND tb2.is_on_sale='1' tb2.is_check='1' AND AND tb2.is_delete='0' AND tb1.type='$type'";
				$gift_goods = $this->App->find($sql);
				if (!empty($gift_goods)) {
					foreach ($gift_goods as $k => $row) {
						$rt['gift_goods_ids'][] = $row['goods_id'];
					}
					unset($gift_goods);
				}
			}
		}
		$this->set('rt', $rt);
		$con = $this->fetch('ajax_mycart', true);
		die($con);
	}

	function mycart_clear()
	{
		$this->Session->write("cart", null);
		$this->Session->write('useradd', null);
		$this->jump(ADMIN_URL);
		exit;
	}
}
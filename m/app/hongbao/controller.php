<?php
//decode by QQ:270656184 http://www.yunlu99.com/
require_once(SYS_PATH . "\\m\\app\\hongbao\\pay\\WxXianjinHelper.php");

class HongbaoController extends Controller
{
	function __construct()
	{
		$this->js(array('jquery.json-1.3.js', 'user.js?v=v1'));
	}

	function checked_login()
	{
		$uid = $this->Session->read('User.uid');
		if (!($uid > 0)) {
			$this->jump(ADMIN_URL . 'user.php?act=login');
			exit;
		}
		return $uid;
	}

	function index()
	{
		$uid = $this->checked_login();
		$rt = $this->personinfo($uid);
		if (!isset($_GET['cengji'])) exit('系统错误');
		$cengji = $_GET['cengji'];
		$sql = "SELECT `order_id` FROM `{$this->App->prefix()}goods_sn` WHERE pid ='{$uid}'  AND cengji='{$cengji}' AND `money`>0 AND `is_use`=1 ORDER BY `order_id` DESC";
		$ids = $this->App->findcol($sql);
		$sn = array();
		$i = 0;
		foreach ($ids as $key => $oid) {
			$sql = "SELECT * FROM `{$this->App->prefix()}goods_sn` WHERE `order_id`='{$oid}' AND pid ='{$uid}' AND cengji='{$cengji}' ORDER BY `money` DESC";
			$sn[$i] = $this->App->find($sql);
			$i++;
		}
		if (!defined(NAVNAME)) define("NAVNAME", "会员中心");
		$this->set('cengji', $cengji);
		$this->set('rt', $rt);
		$this->set('sn', $sn);
		$this->title("红包管理中心" . ' - ' . $GLOBALS['LANG']['site_name']);
		$mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';
		$this->set('mubanid', $GLOBALS['LANG']['mubanid']);
		$this->template($mb . '/hongbao');
	}

	function yueti()
	{
		$uid = $this->checked_login();
		$user = $this->App->findrow("SELECT `mymoney`,`wecha_id` FROM `{$this->App->prefix()}user` WHERE `user_id`=" . $uid);
		$mymoney = $user['mymoney'];
		$wecha_id = $user['wecha_id'];
		if (!isset($mymoney) || $mymoney == '' || empty($mymoney) || $mymoney <= 0) {
			echo "<script>alert('余额不足，赶快推广起来吧！')</script>";
			echo "<script>window.location.href='/m/user.php';</script>";
			exit();
		}
		$dmoney = $this->App->findvar("SELECT `dixin360` FROM `{$this->App->prefix()}userconfig` LIMIT 1");
		if ($mymoney < $dmoney) {
			echo "<script>alert('满" . $dmoney . "元后才可提现，赶快推广起来吧！')</script>";
			echo "<script>window.location.href='/m/user.php';</script>";
			exit();
		}
		$this->get_app_info();
		$DES = "商城红包提现";
		$topm = 200;
		if ($mymoney > $topm) {
			$result = $this->tikuan($wecha_id, $topm, $DES);
			if ($result['s'] == 1) {
				$this->App->query("UPDATE `{$this->App->prefix()}user` SET `mymoney`=`mymoney`-$topm WHERE `user_id`=" . $uid);
				$this->App->query("UPDATE `{$this->App->prefix()}user` SET `money_ucount`=`money_ucount`+$topm WHERE `user_id`=" . $uid);
				$this->App->query("INSERT `{$this->App->prefix()}user_money_change` SET `time`=" . mktime() . ",`changedesc`='商城红包提现',`money`=-$topm,`uid`=$uid,`order_sn`='yueti',`order_id`=0");
				echo '<script>alert(\'请返回微信界面及时领取红包。
由于微信限制，只能领取最高200元。剩余的请在用户中心一分钟后领取。\')</script>';
				echo "<script>window.location.href='/m/user.php';</script>";
			} else {
				echo "<script>alert('提现出错，请联系客服人员。错误：" . $result['r'] . "')</script>";
				echo "<script>history.go(-1)</script>";
				exit();
			}
		} elseif ($mymoney <= $topm) {
			$result = $this->tikuan($user['wecha_id'], $mymoney, $DES);
			if ($result['s'] == 1) {
				$this->App->query("UPDATE `{$this->App->prefix()}user` SET `mymoney`=0  WHERE `user_id`=" . $uid);
				$this->App->query("UPDATE `{$this->App->prefix()}user` SET `money_ucount`=`money_ucount`+$mymoney WHERE `user_id`=" . $uid);
				$this->App->query("INSERT `{$this->App->prefix()}user_money_change` SET `time`=" . mktime() . ",`changedesc`='商城红包提现',`money`=-$mymoney,`uid`=$uid,`order_sn`='yueti',`order_id`=0");
				echo "<script>alert('请返回微信界面及时领取红包。')</script>";
				echo "<script>window.location.href='/m/user.php';</script>";
			} else {
				echo "<script>alert('提现出错，请联系客服人员。错误：" . $result['r'] . "')</script>";
				exit();
			}
		}
	}

	function yueti_fenhong()
	{
		$uid = $this->checked_login();
		$user = $this->App->findrow("SELECT `qqmoney`,`wecha_id` FROM `{$this->App->prefix()}user` WHERE `user_id`=" . $uid);
		$qqmoney = $user['qqmoney'];
		$wecha_id = $user['wecha_id'];
		if (!isset($qqmoney) || $qqmoney == '' || empty($qqmoney) || $qqmoney <= 0) {
			echo "<script>alert('余额不足，赶快推广起来吧！')</script>";
			echo "<script>window.location.href='/m/user.php';</script>";
			exit();
		}
		$dmoney = $this->App->findvar("SELECT `dixin360` FROM `{$this->App->prefix()}userconfig` LIMIT 1");
		if ($qqmoney < $dmoney) {
			echo "<script>alert('满" . $dmoney . "元后才可提现，赶快推广起来吧！')</script>";
			echo "<script>window.location.href='/m/user.php';</script>";
			exit();
		}
		$this->get_app_info();
		$DES = "全球分红红包提现";
		$topm = 200;
		if ($qqmoney > $topm) {
			$result = $this->tikuan($wecha_id, $topm, $DES);
			if ($result['s'] == 1) {
				$this->App->query("UPDATE `{$this->App->prefix()}user` SET `qqmoney`=`qqmoney`-$topm WHERE `user_id`=" . $uid);
				$this->App->query("UPDATE `{$this->App->prefix()}user` SET `qqmoney_ucount`=`qqmoney_ucount`+$topm WHERE `user_id`=" . $uid);
				$this->App->query("INSERT `{$this->App->prefix()}user_money_change` SET `time`=" . mktime() . ",`changedesc`='全球分红红包提现',`money`=-$topm,`uid`=$uid,`order_sn`='yueti',`order_id`=0");
				echo '<script>alert(\'请返回微信界面及时领取红包。
由于微信限制，只能领取最高200元。剩余的请在用户中心一分钟后领取。\')</script>';
				echo "<script>window.location.href='/m/user.php';</script>";
			} else {
				echo "<script>alert('提现出错，请联系客服人员。错误：" . $result['r'] . "')</script>";
				echo "<script>history.go(-1)</script>";
				exit();
			}
		} elseif ($qqmoney <= $topm) {
			$result = $this->tikuan($user['wecha_id'], $qqmoney, $DES);
			if ($result['s'] == 1) {
				$this->App->query("UPDATE `{$this->App->prefix()}user` SET `qqmoney`=0  WHERE `user_id`=" . $uid);
				$this->App->query("UPDATE `{$this->App->prefix()}user` SET `qqmoney_ucount`=`qqmoney_ucount`+$qqmoney WHERE `user_id`=" . $uid);
				$this->App->query("INSERT `{$this->App->prefix()}user_money_change` SET `time`=" . mktime() . ",`changedesc`='全球分红红包提现',`money`=-$qqmoney,`uid`=$uid,`order_sn`='yueti',`order_id`=0");
				echo "<script>alert('请返回微信界面及时领取红包。')</script>";
				echo "<script>window.location.href='/m/user.php';</script>";
			} else {
				echo "<script>alert('提现出错，请联系客服人员。错误：" . $result['r'] . "')</script>";
				exit();
			}
		}
	}

	function yueti_fenxiao()
	{
		$uid = $this->checked_login();
		$user = $this->App->findrow("SELECT `fxmoney`,`wecha_id` FROM `{$this->App->prefix()}user` WHERE `user_id`=" . $uid);
		$fxmoney = $user['fxmoney'];
		$wecha_id = $user['wecha_id'];
		if (!isset($fxmoney) || $fxmoney == '' || empty($fxmoney) || $fxmoney <= 0) {
			echo "<script>alert('余额不足，赶快推广起来吧！')</script>";
			echo "<script>window.location.href='/m/user.php';</script>";
			exit();
		}
		$dmoney = $this->App->findvar("SELECT `dixin360` FROM `{$this->App->prefix()}userconfig` LIMIT 1");
		if ($fxmoney < $dmoney) {
			echo "<script>alert('满" . $dmoney . "元后才可提现，赶快推广起来吧！')</script>";
			echo "<script>window.location.href='/m/user.php';</script>";
			exit();
		}
		$this->get_app_info();
		$DES = "分销红包提现";
		$topm = 200;
		if ($fxmoney > $topm) {
			$result = $this->tikuan($wecha_id, $topm, $DES);
			if ($result['s'] == 1) {
				$this->App->query("UPDATE `{$this->App->prefix()}user` SET `fxmoney`=`fxmoney`-$topm WHERE `user_id`=" . $uid);
				$this->App->query("UPDATE `{$this->App->prefix()}user` SET `fxmoney_ucount`=`fxmoney_ucount`+$topm WHERE `user_id`=" . $uid);
				$this->App->query("INSERT `{$this->App->prefix()}user_money_change` SET `time`=" . mktime() . ",`changedesc`='分销红包提现',`money`=-$topm,`uid`=$uid,`order_sn`='yueti',`order_id`=0");
				echo '<script>alert(\'请返回微信界面及时领取红包。
由于微信限制，只能领取最高200元。剩余的请在用户中心一分钟后领取。\')</script>';
				echo "<script>window.location.href='/m/user.php';</script>";
			} else {
				echo "<script>alert('提现出错，请联系客服人员。错误：" . $result['r'] . "')</script>";
				echo "<script>history.go(-1)</script>";
				exit();
			}
		} elseif ($fxmoney <= $topm) {
			$result = $this->tikuan($user['wecha_id'], $fxmoney, $DES);
			if ($result['s'] == 1) {
				$this->App->query("UPDATE `{$this->App->prefix()}user` SET `fxmoney`=0  WHERE `user_id`=" . $uid);
				$this->App->query("UPDATE `{$this->App->prefix()}user` SET `fxmoney_ucount`=`fxmoney_ucount`+$fxmoney WHERE `user_id`=" . $uid);
				$this->App->query("INSERT `{$this->App->prefix()}user_money_change` SET `time`=" . mktime() . ",`changedesc`='分销红包提现',`money`=-$fxmoney,`uid`=$uid,`order_sn`='yueti',`order_id`=0");
				echo "<script>alert('请返回微信界面及时领取红包。')</script>";
				echo "<script>window.location.href='/m/user.php';</script>";
			} else {
				echo "<script>alert('提现出错，请联系客服人员。错误：" . $result['r'] . "')</script>";
				exit();
			}
		}
	}

	function personinfo($uid)
	{
		$sql = "SELECT * FROM `{$this->App->prefix()}user` WHERE user_id ='{$uid}' AND active='1' LIMIT 1";
		$rt['userinfo'] = $this->App->findrow($sql);
		$rank = $this->Session->read('User.rank');
		$wecha_id2 = $this->Session->read('User.wecha_id');
		$wecha_id_new = $wecha_id2;
		$sql = "SELECT tb2.level_name FROM `{$this->App->prefix()}user` AS tb1 LEFT JOIN `{$this->App->prefix()}user_level` AS tb2 ON tb1.user_rank=tb2.lid WHERE tb1.user_id='$uid'";
		$rt['userinfo']['level_name'] = $this->App->findvar($sql);
		return $rt;
	}

	function get_app_info()
	{
		$sql = "SELECT `pay_config` FROM `" . $this->App->prefix() . "payment` WHERE `pay_id`=4";
		$pay_config = $this->App->findvar($sql);
		$configr = unserialize($pay_config);
		$rt = array();
		$rt['MCHID'] = $configr['pay_no'];
		$rt['PARTNERKEY'] = $configr['pay_code'];
		$r = $this->action('common', '_get_appid_appsecret');
		$rt['appid'] = $r['appid'];
		$rt['appsecret'] = $r['appsecret'];
		define("APPID", $rt['appid']);
		define("APPSECRET", $rt['appsecret']);
		define("MCHID", $rt['MCHID']);
		define("PARTNERKEY", $rt['PARTNERKEY']);
		define("ROOT_PATH", dirname(preg_replace('@\(.*\(.*$@', '', __FILE__)));
		define("DS", DIRECTORY_SEPARATOR);
		return $rt;
	}

	function tikuan($openid, $amount, $DES)
	{
		$commonUtil = new CommonUtil();
		$wxHongBaoHelper = new WxHongBaoHelper();
		$actioncode = 0;
		$amount *= 100;
		$wxHongBaoHelper->setParameter("nonce_str", $commonUtil->create_noncestr());
		$wxHongBaoHelper->setParameter("mch_billno", MCHID . date('His') . rand(10000, 99999));
		$wxHongBaoHelper->setParameter("mch_id", MCHID);
		$wxHongBaoHelper->setParameter("wxappid", APPID);
		$wxHongBaoHelper->setParameter("nick_name", '平台');
		$wxHongBaoHelper->setParameter("send_name", $DES);
		$wxHongBaoHelper->setParameter("re_openid", $openid);
		$wxHongBaoHelper->setParameter("total_amount", $amount);
		$wxHongBaoHelper->setParameter("min_value", $amount);
		$wxHongBaoHelper->setParameter("max_value", $amount);
		$wxHongBaoHelper->setParameter("total_num", 1);
		$wxHongBaoHelper->setParameter("wishing", $DES);
		$wxHongBaoHelper->setParameter("client_ip", '127.0.0.1');
		$wxHongBaoHelper->setParameter("act_name", '红包活动');
		$wxHongBaoHelper->setParameter("remark", '快来抢！');
		$postXml = $wxHongBaoHelper->create_hongbao_xml();
		$url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/sendredpack';
		$responseXml = $wxHongBaoHelper->curl_post_ssl($url, $postXml);
		$responseObj = simplexml_load_string($responseXml);
		if ($responseObj->result_code == "SUCCESS" && $responseObj->return_code == "SUCCESS") {
			$actioncode = 1;
			$msg['s'] = 1;
			$msg['r'] = "";
		} else {
			$wxHongBaoHelper->create_file("log.txt", "", $responseXml);
			$msg['s'] = 0;
			$msg['r'] = (string)$responseObj->return_msg;
		}
		return $msg;
	}
}
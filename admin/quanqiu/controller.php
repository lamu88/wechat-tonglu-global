<?php

//decode by QQ:270656184 http://www.yunlu99.com/
class QuanqiuController extends Controller
{
	function __construct()
	{
		$this->css('content.css');
	}

	function quanqiu()
	{
		$Y = date("Y");
		$m = date("m");
		$d = date("d");
		$today = mktime(0, 0, 0, $m, $d, $Y);
		$yesterday = $today - 86400;
		$sql = "SELECT SUM(`order_amount`) FROM `{$this->App->prefix()}goods_order_info` WHERE `pay_status`=1 AND `pay_time`>=$yesterday AND `pay_time`<$today";
		$yesterdayMoney = $this->App->findvar($sql);
		$qq1num = $this->App->findvar("SELECT COUNT(user_id) FROM `{$this->App->prefix()}user` WHERE `user_rank_qq`=1");
		$qq2num = $this->App->findvar("SELECT COUNT(user_id) FROM `{$this->App->prefix()}user` WHERE `user_rank_qq`=2");
		$qq3num = $this->App->findvar("SELECT COUNT(user_id) FROM `{$this->App->prefix()}user` WHERE `user_rank_qq`=3");
		$qq4num = $this->App->findvar("SELECT COUNT(user_id) FROM `{$this->App->prefix()}user` WHERE `user_rank_qq`=4");
		$qq5num = $this->App->findvar("SELECT COUNT(user_id) FROM `{$this->App->prefix()}user` WHERE `user_rank_qq`=5");
		if ($_POST['submit']) {
			$aa = $this->App->findvar("SELECT `qqtime` FROM `{$this->App->prefix()}quanqiu` WHERE `qqtime`=$yesterday");
			if ($aa) {
				echo "<script>alert('昨天的全球分红已经分过了！！')</script>";
				echo '<script>window.location.href=\'/admin/quanqiu.php\'</script>';
				exit();
			}
			$money = $_POST['money'] ? $_POST['money'] : $yesterdayMoney;
			$qq = $this->App->findrow("SELECT * FROM `{$this->App->prefix()}userconfig` LIMIT 1");
			$sumqqmoney = 0;
			$sql = "SELECT `user_id`,`user_rank_qq` FROM `{$this->App->prefix()}user` WHERE `user_rank_qq`>0";
			$users = $this->App->find($sql);
			foreach ($users as $key => $row) {
				switch ($row['user_rank_qq']) {
					case 1:
						$qqmoney = $qq['qqmoney1'] / 100 * $money / $qq1num;
						$sumqqmoney = $sumqqmoney + $qqmoney;
						break;
					case 2:
						$qqmoney = $qq['qqmoney2'] / 100 * $money / $qq2num;
						$sumqqmoney = $sumqqmoney + $qqmoney;
						break;
					case 3:
						$qqmoney = $qq['qqmoney3'] / 100 * $money / $qq3num;
						$sumqqmoney = $sumqqmoney + $qqmoney;
						break;
					case 4:
						$qqmoney = $qq['qqmoney4'] / 100 * $money / $qq4num;
						$sumqqmoney = $sumqqmoney + $qqmoney;
						break;
					case 5:
						$qqmoney = $qq['qqmoney5'] / 100 * $money / $qq5num;
						$sumqqmoney = $sumqqmoney + $qqmoney;
						break;
				}
				$sql = "UPDATE `{$this->App->prefix()}user` SET `qqmoney_ucount`=`qqmoney_ucount`+$qqmoney,`qqmoney`=`qqmoney`+$qqmoney  WHERE `user_id`=" . $row['user_id'];
				$this->App->query($sql);
				$d = array('time' => mktime(), 'changedesc' => '全球分红奖', 'money' => $qqmoney, 'uid' => $row['user_id'], 'accid' => $row['user_id'], 'buyid' => 0, 'order_sn' => 'quanqiu', 'thismonth' => '', 'thism' => '', 'type' => 'system', 'order_id' => 0, 'level' => 1);
				$this->App->insert('user_money_change_fenhong', $d);
			}
			$d = array('qqtime' => $yesterday, 'addtime' => mktime(), 'yesterdayMoney' => $yesterdayMoney, 'qqmoney' => $sumqqmoney, 'shuifei' => 0);
			$this->App->insert('quanqiu', $d);
			exit('分红成功！<a href=\'/admin/quanqiu.php\'>点击返回</a>');
		}
		if ($_GET['fix'] == 'true') {
			$uids = $this->App->findcol("SELECT `user_id` FROM `{$this->App->prefix()}user`");
			foreach ($uids as $userid) {
				$this->up_level($userid);
				echo('<br />success' . $userid);
			}
		}
		$sql = "SELECT * FROM `{$this->App->prefix()}quanqiu` ORDER BY `id` DESC";
		$rt = $this->App->find($sql);
		$this->set('rt', $rt);
		$this->set('qq1num', $qq1num);
		$this->set('qq2num', $qq2num);
		$this->set('qq3num', $qq3num);
		$this->set('qq4num', $qq4num);
		$this->set('qq5num', $qq5num);
		$this->set('yesterdayMoney', $yesterdayMoney);
		$this->template('quanqiu');
	}

	function up_level($userid)
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
		$sql = "SELECT * FROM `{$this->App->prefix()}userconfig` LIMIT 1";
		$uts = $this->App->findrow($sql);
		$qqlevel = 0;
		if ($yeji >= $uts['quanqiu1my']) {
			$sql = "SELECT COUNT(`user_id`) FROM `gz_user` WHERE user_rank=" . $uts['quanqiu1dl'] . " AND `user_id` IN (SELECT `uid` FROM `gz_user_tuijian` WHERE `parent_uid`=" . $userid . ")";
			echo "<br />$sql<br />";
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
		echo '<br />' . "UPDATE `{$this->App->prefix()}user` SET `user_rank_qq`=" . $qqlevel . " WHERE `user_id`=" . $userid . "<br />";
	}
}
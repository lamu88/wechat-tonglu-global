<?php
class FixlevelController extends Controller{
    /*
     * @Photo Index
     * @param <type> $page
     * @param <type> $type
     */
	 //构造函数，自动新建对象
 	function  __construct() {
		/*
		*构造函数
		*/
	}

	function fixlevel(){
		//$this->huangjin();	//	修复黄金分销商
		//$rt = $this->App->findcol("SELECT `user_id` FROM `{$this->App->prefix()}user`");
		//$this->up_level(2441);
		/**
		foreach($rt as $key=>$iddd){
			$this->up_level($iddd);
		}
		**/
		//$this->fixmoney(11,125);
		//$this->fixuser(10);
	}
	
	function fixuser($lid){
		$ids = $this->App->findcol("SELECT `user_id` FROM `{$this->App->prefix()}user` WHERE `user_rank`=".$lid);
		foreach($ids as $key=>$id){
			$money = 0;
			//昨天的钱
			$oldmoney = $this->App->findvar("SELECT `mymoney` FROM `{$this->App->prefix()}user2` WHERE `user_id`=".$id);
			//昨天2015-12-18 17:12
			$chmoney = $this->App->findcol("SELECT `money` FROM `{$this->App->prefix()}user_money_change` WHERE `time`>1450429920 AND `uid`=".$id);
			$money = $oldmoney+array_sum($chmoney);
			$sql = "UPDATE `{$this->App->prefix()}user` SET `mymoney`=$money WHERE user_id=$id";
			$this->App->query($sql);
			echo 'success';
		}		
	}
	
	function fixmoney($lid,$qqmoney){
		$ids = $this->App->findcol("SELECT `user_id` FROM `{$this->App->prefix()}user` WHERE `user_rank`=".$lid);
		foreach($ids as $key=>$id){
			$jiandiaomoney = $this->App->findvar("SELECT `money` FROM `{$this->App->prefix()}user_money_change` WHERE `order_sn`='quanqiu' AND `time`>1450486501 AND `time`<1450486800 AND `uid`=".$id." LIMIT 1");
			$money = $qqmoney-$jiandiaomoney;
			$sql = "INSERT `{$this->App->prefix()}user_money_change` SET `time`='".mktime()."',`changedesc`='全球分红奖-补充',`money`=$money,`order_sn`='quanqiu' WHERE uid=$id";
			exit($sql);
			$this->App->query($sql);
			$sql = "UPDATE `{$this->App->prefix()}user` SET `mymoney`=`mymoney`+$money WHERE user_id=$id";
			$this->App->query($sql);
		}
		echo '成功';
	}
	
	//黄金经销商修复 
	function huangjin(){
		$this->App->query("UPDATE `{$this->App->prefix()}user` SET `user_rank`=1 WHERE `user_rank`=12");	//将所有黄金分销商降为普通会员
		$rt = $this->App->findcol("SELECT `user_id` FROM `{$this->App->prefix()}goods_order_info`");
		foreach($rt as $key=>$rtt){
			$sql = "SELECT `user_rank` FROM `{$this->App->prefix()}user` WHERE `user_id`=".$rtt;		//查询当前购买货物id的用户等级  过滤高级用户  避免降级为黄金
			$level = $this->App->findvar($sql);
			if($level==1){
				$this->App->query("UPDATE `{$this->App->prefix()}user` SET `user_rank`=12 WHERE `user_rank`=1 AND `user_id`=".$rtt);	//将所有黄金分销商降为普通会员
			}
		}
		echo '黄金分销商修复成功.<br />';
	}
	function up_level($iddd){
			//分销商配置
			$sql = "SELECT * FROM `{$this->App->prefix()}user_level` WHERE `lid`>1 AND `lid`<13 ORDER BY `lid` DESC";
			$lrr = $this->App->find($sql);
			//当前用户的等级
			$user_rank = $this->App->findvar("SELECT `user_rank` FROM `{$this->App->prefix()}user` WHERE `user_id`=$iddd");
			//获取当前用户下属三级人数			
			$sonnum = $this->fenxiaoSonNum($iddd);
			$sonnum1 = $sonnum[0];
			$sonnum2 = $sonnum[1];
			$sonnum3 = $sonnum[2];
			if($user_rank){
			foreach($lrr as $k=>$row){
				if($row['uptype']=='under'&&($sonnum1>=$row['uptypenum'])){		//如果升级类型为直推，且直推的人数大于等于设置的人数 ->升级
					$sql = "UPDATE `{$this->App->prefix()}user` SET `user_rank`=".$row['lid']." WHERE `user_id`=".$iddd;	//升级为直推分销商
					$this->App->query($sql);
					continue;
				}
				if($row['uptype']=='money'){
						$mymoney = $this->App->findvar("SELECT SUM(money) FROM `{$this->App->prefix()}user_money_change` WHERE `uid`=$iddd AND `changedesc`='购买商品返佣金'");
						if($mymoney>=$row['uptypenum']){	
							$sql = "UPDATE `{$this->App->prefix()}user` SET `user_rank`=".$row['lid']." WHERE `user_id`=".$iddd;	//升级为分销商
							//$this->App->query($sql);
							echo $iddd.'要升级为'.$row['level_name'].'<br />';
						}
				}
						/**
				switch($row['uptype']){
					case 'no':
						if($user_rank!=$row['lid']){
							if($sonnum1>=$row['uptypenum'])	break;
							if($sonnum1>=$row['uptypenum']||$sonnum2>=$row['uptypenum']||$sonnum3>=$row['uptypenum'])	break;
							if($sonnum1>30) break;
							$sql = "UPDATE `{$this->App->prefix()}user` SET `user_rank`=".$row['lid']." WHERE `user_id`=".$iddd;	//升级为分销商
							$this->App->query($sql);
							echo $iddd.'降为'.$row['level_name'].'<br />';
						}
					case 'under':
						if($sonnum1>=$row['uptypenum']){
							if($user_rank==$row['lid']) break;
							$sql = "UPDATE `{$this->App->prefix()}user` SET `user_rank`=".$row['lid']." WHERE `user_id`=".$iddd;	//升级为分销商
							$this->App->query($sql);
							echo $iddd.'要升级为'.$row['level_name'].'<br />';
						}
					case 'money':
						$mymoney = $this->App->findvar("SELECT SUM(money) FROM `{$this->App->prefix()}user_money_change` WHERE `uid`=$iddd AND `changedesc`='购买商品返佣金'");
						if($mymoney>=$row['uptypenum']){	
							$sql = "UPDATE `{$this->App->prefix()}user` SET `user_rank`=".$row['lid']." WHERE `user_id`=".$iddd;	//升级为分销商
							$this->App->query($sql);
							//echo $iddd.'要升级为'.$row['level_name'].'<br />';
						}
				}
						**/
				
			}
			}
	}


	//当前ID下三级经销商人数
	function fenxiaoSonNumV2($iddd){
		$son1 = 0;
		$son2 = 0;
		$son3 = 0;
		$arrId1 = array();
		$arrId2 = array();
		$arrId3 = array();
		$rt1 = $this->get_myuser_level($iddd,1);
		$i = 0;
		foreach($rt1 as $key=>$rt){
			$uu = $this->App->findvar("SELECT `user_id` FROM `{$this->App->prefix()}user` WHERE `user_rank`>1 AND `user_rank`<13 AND `user_id`=".$rt['user_id']);
			if($uu){
				$arrId1[$i] = $uu;
				$i++;
			}
		}
		print_r($arrId1);
		exit();
		
		$son = array($son1,$son3,$son2);
		return $son;
	}
	
	//当前ID下三级经销商人数
	function fenxiaoSonNum($iddd){
		$son1 = 0;
		$son2 = 0;
		$son3 = 0;
		$rt1 = $this->get_myuser_level($iddd,1);
		foreach($rt1 as $key=>$rt){
			$uu = $this->App->findvar("SELECT `user_id` FROM `{$this->App->prefix()}user` WHERE `user_rank`>1 AND `user_rank`<13 AND `user_id`=".$rt['user_id']);
			if($uu){
				$son1++;
			}
		}
		$rt2 = $this->get_myuser_level($iddd,2);
		foreach($rt2 as $key=>$rt){
			$uu = $this->App->findvar("SELECT `user_id` FROM `{$this->App->prefix()}user` WHERE `user_rank`>1 AND `user_rank`<13 AND `user_id`=".$rt['user_id']);
			if($uu){
				$son2++;
			}
		}
		$rt3 = $this->get_myuser_level($iddd,3);
		foreach($rt3 as $key=>$rt){
			$uu = $this->App->findvar("SELECT `user_id` FROM `{$this->App->prefix()}user` WHERE `user_rank`>1 AND `user_rank`<13 AND `user_id`=".$rt['user_id']);
			if($uu){
				$son3++;
			}
		}
		
		$son = array($son1,$son3,$son2);
		return $son;
	}
	
	function get_myuser_level($uid,$level){
		switch($level){
			case '1':
				$sql = "SELECT tb1.*,tb2.subscribe_time,tb2.user_id,tb2.reg_time,tb2.nickname,tb2.headimgurl,tb2.money_ucount,tb2.points_ucount,tb2.share_ucount,tb2.guanzhu_ucount,tb2.is_subscribe FROM `{$this->App->prefix()}user_tuijian` AS tb1";
				$sql .=" LEFT JOIN `{$this->App->prefix()}user` AS tb2 ON tb1.uid = tb2.user_id";
				$sql .=" WHERE tb1.parent_uid = '$uid' ORDER BY tb2.share_ucount DESC,tb2.money_ucount DESC,tb1.id DESC";
				return $this->App->find($sql);
			case '2':
				$sql = "SELECT tb2.*,tb3.subscribe_time,tb3.user_id,tb3.reg_time,tb3.nickname,tb3.headimgurl,tb3.money_ucount,tb3.points_ucount,tb3.share_ucount,tb3.guanzhu_ucount,tb3.is_subscribe FROM `{$this->App->prefix()}user_tuijian` AS tb1";
				$sql .= " LEFT JOIN `{$this->App->prefix()}user_tuijian` AS tb2 ON tb2.parent_uid = tb1.uid ";
				$sql .= " LEFT JOIN `{$this->App->prefix()}user` AS tb3 ON tb2.uid = tb3.user_id";
				$sql .= " WHERE tb1.parent_uid='$uid' AND tb2.uid IS NOT NULL ORDER BY tb3.share_ucount DESC,tb3.money_ucount DESC,tb2.id DESC";
				return $this->App->find($sql);
			case '3':
				$sql = "SELECT tb3.*,tb4.subscribe_time,tb4.user_id,tb4.reg_time,tb4.nickname,tb4.headimgurl,tb4.money_ucount,tb4.points_ucount,tb4.share_ucount,tb4.guanzhu_ucount,tb4.is_subscribe FROM `{$this->App->prefix()}user_tuijian` AS tb1";
				$sql .= " LEFT JOIN `{$this->App->prefix()}user_tuijian` AS tb2 ON tb2.parent_uid = tb1.uid ";
				$sql .= " LEFT JOIN `{$this->App->prefix()}user_tuijian` AS tb3 ON tb3.parent_uid = tb2.uid ";
				$sql .= " LEFT JOIN `{$this->App->prefix()}user` AS tb4 ON tb3.uid = tb4.user_id";
				$sql .= " WHERE tb1.parent_uid='$uid' AND tb3.uid IS NOT NULL  ORDER BY tb4.share_ucount DESC,tb4.money_ucount DESC,tb3.id DESC";
				return $this->App->find($sql);
		}
	}
	
	
	
}


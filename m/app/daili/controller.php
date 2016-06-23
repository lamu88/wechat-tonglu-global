<?php
class DailiController extends Controller{
 	function  __construct() {
		$this->css(array('jquery_dialog.css','user2015.css'));
		$this->js(array('jquery.json-1.3.js','jquery_dialog.js','common.js','user.js'));
		//$this->layout('defaultdaili');
	}
	//修改密码
	function update_user_pass($data=array()){
		$newpass = $data['pass'];
		$datas['password'] = $data['newpass'];
		$rp_pass = $data['rpnewpass'];
		$uid = $this->Session->read('User.uid');
		if(!empty($newpass)){
			if(empty($datas['password'])){
				echo '请输入新密码'; exit;
			}
			
			if(!empty($rp_pass)&&$datas['password']==$rp_pass){
				$datas['password'] = md5(trim($datas['password']));
				if(md5($newpass)==$datas['password']){
					echo '新密码跟旧密码不能相同';
				}
				
				$newpass = md5(trim($newpass));
				$sql = "SELECT password FROM `{$this->App->prefix()}user` WHERE password='$newpass' AND user_id='$uid'";
				$newrt = $this->App->findvar($sql);
				if(empty($newrt)){
					echo '您的原始密码错误'; exit;
				}
			
				if($this->App->update('user',$datas,'user_id',$uid)){
					echo '密码修改成功'; exit;
				}else{
					echo '密码修改失败'; exit;
				}
			}else{
				echo '密码与确认密码不一致'; exit;
			}
			
		}else{
			echo '请输入原始密码'; exit;
		}
	}
	
	//修改提款密码
	function update_user_passpay($data=array()){
		$newpass = $data['pass']; //原始密码
		$datas['pass'] = $data['newpass'];
		$rp_pass = $data['rpnewpass'];
		$uid = $this->Session->read('User.uid');
		//if(!empty($newpass)){
			if(empty($datas['pass'])){
				echo '请输入新密码'; exit;
			}
			
			if(!empty($rp_pass)&&$datas['pass']==$rp_pass){
				$datas['pass'] = md5(trim($datas['pass'])); //新密码
				$newpass = md5(trim($newpass)); //原始密码
				
				$sql = "SELECT pass,id FROM `{$this->App->prefix()}user_bank` WHERE uid='$uid' LIMIT 1";
				$pt = $this->App->findrow($sql);
				$pass = isset($pt['pass']) ? $pt['pass'] : '';
				$id = isset($pt['id']) ? $pt['id'] : '0';
				if(!empty($pass)){
					if($newpass==$datas['pass']){
						echo '新密码跟旧密码不能相同';
					}
					
					if($pass!=$newpass){
						echo '您的原始密码错误'; exit;
					}
				}
				
				if($id > 0){
					if($this->App->update('user_bank',$datas,'id',$id)){
						echo '密码修改成功'; exit;
					}else{
						echo '密码修改失败'; exit;
					}
				}else{
					$datas['uptime'] = mktime();
					$datas['uid'] = $uid;
					if($this->App->insert('user_bank',$datas)){
						echo '密码修改成功'; exit;
					}else{
						echo '密码修改失败'; exit;
					}
				}
				
			}else{
				echo '密码与确认密码不一致'; exit;
			}
			unset($data,$datas);
		//}
	}
	//修改提款信息
	function update_user_bank($data=array()){
		//$newpass = $data['pass'];
		$bankname = $data['bankname'];
		$bankaddress = $data['bankaddress'];
		$uname = $data['uname'];
		$banksn = $data['banksn'];
		$uid = $this->Session->read('User.uid');
		/*if(empty($newpass)){
			echo '请输入密码'; exit;
		}*/
		//密码是否正确
		/*$pp = md5(trim($newpass));
		$sql = "SELECT id FROM `{$this->App->prefix()}user_bank` WHERE pass = '$pp' AND uid='$uid' LIMIT 1";
		$id = $this->App->findvar($sql);
		if(!($id>0)){
			echo '密码错误！或者您可以修改密码！'; exit;
		}*/
		
		if(empty($bankname)){
			//echo '请输入开户行'; exit;
		}
		if(empty($uname)){
			echo '请输入户名'; exit;
		}
		if(empty($banksn)){
			//echo '请输入卡号'; exit;
		}
		$dd = array();
		$dd['bankname'] = $bankname;
		$dd['bankaddress'] = $bankaddress;
		$dd['uname'] = $uname;
		$dd['banksn'] = $banksn;
		$dd['uptime'] = mktime();
		
		$sql = "SELECT id FROM `{$this->App->prefix()}user_bank` WHERE uid='$uid' LIMIT 1";
		$uids = $this->App->findvar($sql);
		if($uids > 0){ //修改
			if($this->App->update('user_bank',$dd,'id',$uids)){
				echo '修改成功'; exit;
			}else{
				echo '修改失败'; exit;
			}
		}else{
			$dd['uid'] = $uid;
			if($this->App->insert('user_bank',$dd)){
				echo '修改成功'; exit;
			}else{
				echo '修改失败'; exit;
			}
		}
	}
	
	function ajax_open_dailiapply($data=array()){
		$var = $data['ty'];
		$uid = $this->Session->read('User.uid');
		$this->App->update('user',array('is_dailiapply'=>$var),'user_id',$uid);
	}
	
	function is_daili(){
		/*$uid = $this->Session->read('User.uid');
		$rank = $this->Session->read('User.rank');
		if($rank=='1'){
				//判断级别
				$sql = "SELECT user_rank,is_salesmen FROM `{$this->App->prefix()}user` WHERE user_id = '$uid' LIMIT 1";
				$rls = $this->App->findrow($sql);
				$rank = isset($rls['user_rank']) ? $rls['user_rank'] : '1';
				$is_apply = isset($rls['is_salesmen']) ? $rls['is_salesmen'] : '1';
				if($rank=='1' || $is_apply =='1'){
					$this->jump(ADMIN_URL.'user.php',0,'您没有权限访问'); exit;
				}
				$this->Session->write('User.rank',$rank);
		}*/
	}
	function checked_login(){
		$uid = $this->Session->read('User.uid');
		if(!($uid>0)){ $this->jump(ADMIN_URL.'user.php?act=login'); exit;}
		return;
	}
	//
	function ajax_moneyrank_page($rts=array()){
		$hh = $rts['hh'];
		$tops = $rts['tops'];
		$tops = intval($tops);
		if(($tops-$hh) >= 0){
			$page = ceil($tops/$hh);
			if($page > 1) $page = $page-1;
			$list = 30;
			$start = $page*$list;
			
			$sql = "SELECT nickname,headimgurl,is_subscribe,points_ucount,money_ucount,share_ucount,guanzhu_ucount,reg_time,subscribe_time,is_subscribe FROM `{$this->App->prefix()}user` WHERE active='1' ORDER BY money_ucount DESC,share_ucount DESC,reg_time ASC LIMIT $start,$list";
			$ulist = $this->App->find($sql);
		
			$this->set('ulist',$ulist);
			$this->set('pagec',$page*$list);
			echo $this->fetch('load_zmoney',true);
		}
		echo "";
		exit;
	}
	
	//代理公告
	function gonggao(){
		$this->checked_login();
		//$this->action('common','checkjump');
		$sql = "SELECT cat_name FROM `{$this->App->prefix()}wx_cate` WHERE cat_id='2' LIMIT 1";
		$cat_name = $this->App->findvar($sql);
		$this->title($cat_name);
		$this->is_daili();
		$sql = "SELECT article_title, addtime , article_id FROM `{$this->App->prefix()}wx_article` WHERE cat_id='2' AND is_show='1' ORDER BY vieworder ASC,article_id DESC LIMIT 10";
		$rt = $this->App->find($sql);
		
		if(!defined(NAVNAME)) define('NAVNAME', $cat_name);
		$this->set('rt',$rt);
		$mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';
		$this->template($mb.'/v2_gonggao');
	}
	
	function gonggaoinfo(){
		$this->checked_login();
		//$this->action('common','checkjump');
		
		$this->is_daili();
		$id = isset($_GET['id']) ? $_GET['id'] : 0;
		if(!($id > 0)){
			$this->jump(ADMIN_URL.'daili.php?act=gonggao'); exit;
		}
		$sql = "SELECT * FROM `{$this->App->prefix()}wx_article` WHERE article_id='$id' AND is_show='1' LIMIT 1";
		$rt = $this->App->findrow($sql);
		if(empty($rt)){
			$this->jump(ADMIN_URL.'daili.php?act=gonggao'); exit;
		}
		
		$this->title($rt['article_title']);
		if(!defined(NAVNAME)) define('NAVNAME', $rt['article_title']);
		$this->set('rt',$rt);
		$mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';
		$this->template($mb.'/v2_gonggaoinfo');
	}
	
	//客户订单
	function kehuorder(){
		$this->checked_login();
		$this->title("客户订单".' - '.$GLOBALS['LANG']['site_name']);
		$uid = $this->Session->read('User.uid');
		$page = 1;
		$list = 5;
		$start = ($page-1)*$list;
		
		$sql = "SELECT COUNT(order_id) FROM `{$this->App->prefix()}goods_order_info_daigou` WHERE daili_uid='$uid'";
		$tt = $this->App->findvar($sql);
		$rt['pages'] = Import::basic()->getpage($tt, $list, $page,'?page=',true);
		
		$sql = "SELECT * FROM `{$this->App->prefix()}goods_order_info_daigou` WHERE daili_uid='$uid' ORDER BY order_id DESC LIMIT $start,$list";
		$lists = $this->App->find($sql);
		$rt['lists'] = array();
		if(!empty($lists))foreach($lists as $k=>$row){
			$rt['lists'][$k] = $row;
			$oid = $row['order_id'];
			$rt['lists'][$k]['gimg'] = $this->App->findcol("SELECT goods_thumb FROM `{$this->App->prefix()}goods_order_daigou` WHERE order_id='$oid'");
			$rt['lists'][$k]['status'] = $this->get_status($row['order_status'],$row['pay_status'],$row['shipping_status']);
			$rt['lists'][$k]['op'] = $this->get_option($row['order_id'],$row['order_status'],$row['pay_status'],$row['shipping_status']);
		}
		
		if(!defined(NAVNAME)) define('NAVNAME', "客户订单");
		$this->set('rt',$rt);
		$this->template('v2_kehuorder');
	}
	
	//订单详情
	function kehuorderinfo(){
		$this->checked_login();
		$orderid = isset($_GET['order_id']) ? $_GET['order_id'] : 0;
		
		$this->title("欢迎进入会员中心".' - 订单详情 - '.$GLOBALS['LANG']['site_name']);
		$uid = $this->Session->read('User.uid');
				
		$sql= "SELECT * FROM `{$this->App->prefix()}goods_order_info_daigou` WHERE order_id='$orderid' AND daili_uid='$uid'";	
		$rt['orderinfo'] = $this->App->findrow($sql);
		if(empty($rt['orderinfo'])){
			$this->jump(ADMIN_URL.'daili.php?act=kehuorder');exit;
		}
		$sql = "SELECT tb1.*,SUM(tb2.goods_number) AS numbers FROM `{$this->App->prefix()}goods_order_daigou` AS tb1 LEFT JOIN `{$this->App->prefix()}goods_order_address` AS tb2 ON tb1.rec_id = tb2.rec_id WHERE tb1.order_id='$orderid' GROUP BY tb2.rec_id";
		$goodslist = $this->App->find($sql);
		if(!empty($goodslist))foreach($goodslist as $k=>$row){
			$rt['goodslist'][$k] = $row;
			$rec_id = $row['rec_id'];
			$sql = "SELECT tb1.*,tb2.region_name AS province,tb3.region_name AS city,tb4.region_name AS district FROM `{$this->App->prefix()}goods_order_address` AS tb1";
			$sql .=" LEFT JOIN `{$this->App->prefix()}region` AS tb2 ON tb2.region_id = tb1.province";
			$sql .=" LEFT JOIN `{$this->App->prefix()}region` AS tb3 ON tb3.region_id = tb1.city";
			$sql .=" LEFT JOIN `{$this->App->prefix()}region` AS tb4 ON tb4.region_id = tb1.district";
			$sql .=" WHERE tb1.rec_id='$rec_id'";	
			$rt['goodslist'][$k]['ress'] = $this->App->find($sql);
		}

		$status = $this->get_status($rt['orderinfo']['order_status'],$rt['orderinfo']['pay_status'],$rt['orderinfo']['shipping_status']);
		$rt['status'] = explode(',',$status);
				
		if(!defined(NAVNAME)) define('NAVNAME', "订单详情");
		$this->set('rt',$rt);
		$this->template('v2_kehuorderinfo');
	}
	
	function moneyrank(){
		$this->checked_login();
		$this->title("佣金榜".' - '.$GLOBALS['LANG']['site_name']);
		
		$uid = $this->Session->read('User.uid');
		
		$list = 30;
		$page = (isset($_GET['page'])&&intval($_GET['page'])> 0) ? intval($_GET['page']) : 1;
		$start = ($page-1)*$list;
		
		$sql = "SELECT COUNT(user_id) FROM `{$this->App->prefix()}user` WHERE active='1' AND user_id!='2' ORDER BY points_ucount DESC";
		//$sql = "SELECT COUNT(user_id) FROM `{$this->App->prefix()}user` WHERE active='1' ORDER BY points_ucount DESC";
		$tt = $this->App->findvar($sql);
		$rt['pages'] = Import::basic()->getpage($tt, $list, $page,'?page=',true);
		
		$sql = "SELECT nickname,headimgurl,is_subscribe,points_ucount,money_ucount,share_ucount,guanzhu_ucount,reg_time,subscribe_time,is_subscribe FROM `{$this->App->prefix()}user` WHERE active='1' AND user_id!='2' ORDER BY money_ucount DESC,share_ucount DESC,reg_time ASC LIMIT $start,$list";
		//$sql = "SELECT nickname,headimgurl,is_subscribe,points_ucount,money_ucount,share_ucount,guanzhu_ucount,reg_time,subscribe_time,is_subscribe FROM `{$this->App->prefix()}user` WHERE active='1' ORDER BY money_ucount DESC,share_ucount DESC,reg_time ASC LIMIT $start,$list";
		$rt['ulist'] = $this->App->find($sql);
		
		$sql = "SELECT points_ucount,money_ucount,share_ucount,guanzhu_ucount FROM `{$this->App->prefix()}user` WHERE user_id='$uid' LIMIT 1";
		$rt['userinfo'] = $this->App->findrow($sql);
		
		//当前排名
		$sql = "SELECT user_id FROM `{$this->App->prefix()}user` WHERE active='1' AND user_id!='2' ORDER BY money_ucount DESC,share_ucount DESC,reg_time ASC LIMIT 100";
		//$sql = "SELECT user_id FROM `{$this->App->prefix()}user` WHERE active='1' ORDER BY money_ucount DESC,share_ucount DESC,reg_time ASC LIMIT 100";
		$ulist = $this->App->findcol($sql);
		$rt['userinfo']['thisrank'] = 0;
		if(!empty($ulist))foreach($ulist as $ks=>$vv){
			if($uid == $vv){
				++$ks;
				$rt['userinfo']['thisrank'] = $ks;
			}
		}
		if($rt['userinfo']['thisrank']=='0'){
			if(!empty($ulist)){
				$rt['userinfo']['thisrank'] = '>100';
			}else{
				$rt['userinfo']['thisrank'] = '0';
			}
		}
		
		$this->set('rt',$rt);
		
		if(!defined(NAVNAME)) define('NAVNAME', "佣金榜");
		$this->set('rt',$rt);
				$mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';
		$this->template($mb.'/v2_moneyrank');
	}
	
	function v2_mymoney(){
		$this->checked_login();
		$uid = $this->Session->read('User.uid');
		if(!defined(NAVNAME)) define('NAVNAME', "我的多佣金");
		//未有付款佣金
		$sql = "SELECT SUM(tb1.money) FROM `{$this->App->prefix()}user_money_change_cache` AS tb1 LEFT JOIN `{$this->App->prefix()}goods_order_info` AS tb2 ON tb2.order_sn = tb1.order_sn WHERE tb1.uid = '$uid' AND tb2.pay_status='0' AND tb2.order_status!='4' AND tb2.order_status!='1' AND tb1.money > 0 LIMIT 1";
		$rt['pay1'] = $this->App->findvar($sql);
		
		//已经付款佣金
		$sql = "SELECT SUM(tb1.money) FROM `{$this->App->prefix()}user_money_change` AS tb1 LEFT JOIN `{$this->App->prefix()}goods_order_info` AS tb2 ON tb2.order_sn = tb1.order_sn WHERE tb1.uid = '$uid' AND tb2.pay_status='1' AND tb1.money > 0 LIMIT 1";
		$rt['pay2'] = $this->App->findvar($sql);
		
		//已经收货订单佣金
		$sql = "SELECT SUM(tb1.money) FROM `{$this->App->prefix()}user_money_change` AS tb1 LEFT JOIN `{$this->App->prefix()}goods_order_info` AS tb2 ON tb2.order_sn = tb1.order_sn WHERE tb1.uid = '$uid' AND tb2.shipping_status='5' AND tb1.money > 0 LIMIT 1";
		$rt['pay3'] = $this->App->findvar($sql);
		
		//已经取消作废佣金
		$sql = "SELECT SUM(tb1.money) FROM `{$this->App->prefix()}user_money_change_cache` AS tb1 LEFT JOIN `{$this->App->prefix()}goods_order_info` AS tb2 ON tb2.order_sn = tb1.order_sn WHERE tb1.uid = '$uid' AND (tb2.order_status='1' OR tb2.pay_status='2') AND tb1.money > 0 LIMIT 1";
		$rt['pay4'] = $this->App->findvar($sql);
		
		//审核通过的佣金
		$sql = "SELECT SUM(tb1.money) FROM `{$this->App->prefix()}user_money_change` AS tb1 LEFT JOIN `{$this->App->prefix()}goods_order_info` AS tb2 ON tb2.order_sn = tb1.order_sn WHERE tb1.uid = '$uid' AND tb2.shipping_status='5' AND tb2.pay_status='1' AND tb1.money > 0 LIMIT 1";
		$rt['pay5'] = $this->App->findvar($sql);
		
		$this->set('rt',$rt);
				$mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';
		$this->template($mb.'/v2_mymoney');
	}
	
	function mydata(){
		$this->checked_login();
		if(!defined(NAVNAME)) define('NAVNAME', "我的推广");
		$uid = $this->Session->read('User.uid');
		$sql = "SELECT * FROM `{$this->App->prefix()}user` WHERE user_id ='{$uid}' AND active='1' LIMIT 1";
		$rt['userinfo'] = $this->App->findrow($sql);
		
		//订单数量
		$sql = "SELECT COUNT(order_id) FROM `{$this->App->prefix()}goods_order_info` WHERE parent_uid = '$uid' AND order_status='2' AND user_id!='$uid'";
		$rt['userinfo']['ordercount'] = $this->App->findvar($sql);
		
		$sql = "SELECT COUNT(ut.uid) FROM `{$this->App->prefix()}user_tuijian` AS ut LEFT JOIN `{$this->App->prefix()}user` AS u ON ut.uid = u.user_id WHERE ut.parent_uid = '$uid' AND u.user_rank!='1' AND ut.uid!='$uid'";
		$rt['userinfo']['fxcount'] = $this->App->findvar($sql);
		
		$this->set('rt',$rt);
						$mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';
		$this->template($mb.'/v2_mydata');
	}
	
	function my_is_daili(){
		$this->checked_login();
		$uid = $this->Session->read('User.uid');
		$sql = "SELECT tb1.*,tb2.subscribe_time,tb2.user_rank,tb2.reg_time,tb2.nickname,tb2.headimgurl,tb2.money_ucount,tb2.share_ucount,tb2.guanzhu_ucount,tb2.is_subscribe FROM `{$this->App->prefix()}user_tuijian` AS tb1";
		$sql .=" LEFT JOIN `{$this->App->prefix()}user` AS tb2 ON tb1.uid = tb2.user_id";
		$sql .=" WHERE tb1.parent_uid = '$uid' AND tb1.uid!='$uid' ORDER BY tb2.user_id ASC";
		$rt['lists'] = $this->App->find($sql);
		if(!defined(NAVNAME)) define('NAVNAME', "成为好友");
		$this->set('rt',$rt);
		$mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';
		$this->template($mb.'/v2_myuser');
	}
	
	function monrydeial(){
		$this->checked_login();
		$this->title("收入明细".' - '.$GLOBALS['LANG']['site_name']);
		//删除
		$id= isset($_GET['id']) ? $_GET['id'] : '0';
		if($id > 0){
			$this->App->delete('user_money_change','cid',$id);
			$this->jump(ADMIN_URL.'daili.php?act=monrydeial');exit;
		}
		
		$uid = $this->Session->read('User.uid');
		$sql = "SELECT SUM(money) FROM `{$this->App->prefix()}user_money_change` WHERE uid='$uid'";
		$rt['zmoney'] = $this->App->findvar($sql);
   		//分页
		$page = isset($_GET['page'])&&intval($_GET['page'])>0 ? intval($_GET['page']) : 1;
		if(empty($page)){
			   $page = 1;
		}
		$list = 10 ; //每页显示多少个
		$start = ($page-1)*$list;
		$tt = $this->App->findvar("SELECT COUNT(cid) FROM `{$this->App->prefix()}user_money_change` WHERE uid='$uid'");
		$rt['pages'] = Import::basic()->getpage($tt,$list,$page,'?page=',true);
		//$sql = "SELECT tb1.*,tb2.nickname,tb2.headimgurl FROM `{$this->App->prefix()}user_money_change` AS tb1 LEFT JOIN `{$this->App->prefix()}user` AS tb2 ON tb1.buyuid = tb2.user_id WHERE tb1.uid='$uid' ORDER BY tb1.time DESC LIMIT $start,$list";
		$sql = "SELECT tb1.*,tb3.nickname,tb3.headimgurl FROM `{$this->App->prefix()}user_money_change` AS tb1 LEFT JOIN `{$this->App->prefix()}goods_order_info` AS tb2 ON tb2.order_sn = tb1.order_sn LEFT JOIN `{$this->App->prefix()}user` AS tb3 ON tb1.buyuid = tb3.user_id WHERE tb1.uid='$uid' ORDER BY tb1.time DESC LIMIT $start,$list";
		$rt['lists'] = $this->App->find($sql); //商品列表
		$rt['page'] = $page;
		
		if(!defined(NAVNAME)) define('NAVNAME', "收入明细");
		$this->set('rt',$rt);
		$mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';
		$this->template($mb.'/v2_monrydeial');
	}	
	
	function _return_statue_where($id=""){
            if(empty($id)) return "";
            switch ($id){
				case 'weifu':
					return "tb2.pay_status='0' AND tb2.order_status!='4' AND tb2.order_status!='1'";
					break;
				case 'yifu':
					return "tb2.pay_status='1'";
					break;
				case 'shouhuo':
					return "tb2.shipping_status='5'";
					break;
				case 'quxiao':
					return "(tb2.order_status='1' OR tb2.pay_status='2')";
					break;
				case 'tongguo':
					return "tb2.shipping_status='5' AND tb2.pay_status='1'";
					break;
                default :
                    return "";
                    break;
            }
     }
	  
	function mymoneydata(){
		$this->title("佣金明细".' - '.$GLOBALS['LANG']['site_name']);
		$uid = $this->Session->read('User.uid');
		$w_rt = array();
		$w_rt[] = "tb1.uid = '$uid'";
		$status = isset($_GET['status']) ?  trim($_GET['status']) : "";
		if(!empty($status)){
			   $st = $this->_return_statue_where($status);
               !empty($st)? $w_rt[] = $st : "";
		}
		$w = " WHERE ".implode(' AND ',$w_rt);
		
		$sql = "SELECT SUM(tb1.money) FROM `{$this->App->prefix()}user_money_change_cache` AS tb1 LEFT JOIN `{$this->App->prefix()}goods_order_info` AS tb2 ON tb2.order_sn = tb1.order_sn $w";
		$rt['zmoney'] = $this->App->findvar($sql);
   		//分页
		$page = isset($_GET['page'])&&intval($_GET['page'])>0 ? intval($_GET['page']) : 1;
		if(empty($page)){
			   $page = 1;
		}
		$list = 10 ; //每页显示多少个
		$start = ($page-1)*$list;
		$tt = $this->App->findvar("SELECT COUNT(tb1.cid) FROM `{$this->App->prefix()}user_money_change_cache` AS tb1 LEFT JOIN `{$this->App->prefix()}goods_order_info` AS tb2 ON tb2.order_sn = tb1.order_sn $w");
		$rt['pages'] = Import::basic()->getpage($tt,$list,$page,'?page=',true);
		
		$sql = "SELECT tb1.*,tb3.nickname,tb3.headimgurl FROM `{$this->App->prefix()}user_money_change_cache` AS tb1 LEFT JOIN `{$this->App->prefix()}goods_order_info` AS tb2 ON tb2.order_sn = tb1.order_sn LEFT JOIN `{$this->App->prefix()}user` AS tb3 ON tb1.buyuid = tb3.user_id $w ORDER BY tb1.time DESC LIMIT $start,$list";
		$rt['lists'] = $this->App->find($sql); //商品列表
		$rt['page'] = $page;
		
		if(!defined(NAVNAME)) define('NAVNAME', "佣金明细");
		$this->set('rt',$rt);
		$mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';
		$this->template($mb.'/v2_mymoneydata');
	}
	function _return_goods_name($sn){
		if(empty($sn)) return "";
		$sql = "SELECT tb1.goods_name FROM `{$this->App->prefix()}goods_order` AS tb1 LEFT JOIN `{$this->App->prefix()}goods_order_info` AS tb2 ON tb2.order_id = tb1.order_id WHERE tb2.order_sn='$sn' LIMIT 1";
		return $this->App->findvar($sql);
	}
	function setpass(){
		$this->checked_login();
		$this->title("设置密码".' - '.$GLOBALS['LANG']['site_name']);
		$uid = $this->Session->read('User.uid');
		$sql = "SELECT * FROM `{$this->App->prefix()}user` WHERE user_id='$uid' AND active='1' LIMIT 1";
		$rt = $this->App->findrow($sql);
		
		$sql = "SELECT * FROM `{$this->App->prefix()}user_bank` WHERE uid='$uid' LIMIT 1";
		$rts = $this->App->findrow($sql);
		
		if(!defined(NAVNAME)) define('NAVNAME', "设置密码");
		$this->set('rt',$rt);
		$this->set('rts',$rts);
		$this->template('v2_setpass');
	}
	function setpasspay(){
		$this->checked_login();
		$this->title("修改提款密码".' - '.$GLOBALS['LANG']['site_name']);
		$uid = $this->Session->read('User.uid');
		$sql = "SELECT * FROM `{$this->App->prefix()}user_bank` WHERE uid='$uid' LIMIT 1";
		$rts = $this->App->findrow($sql);
		
		if(!defined(NAVNAME)) define('NAVNAME', "修改提款密码");
		$this->set('rts',$rts);
		$this->template('v2_setpasspay');
	}
	function postmoney(){
		$this->checked_login();
		$this->title("申请提款".' - '.$GLOBALS['LANG']['site_name']);
		$uid = $this->Session->read('User.uid');
		$sql = "SELECT * FROM `{$this->App->prefix()}user_bank` WHERE uid='$uid' LIMIT 1";
		$rts = $this->App->findrow($sql);
		if(empty($rts)){
			$this->jump(ADMIN_URL.'user.php?act=myinfos_b',0,'请先设置提款信息'); exit;
		}
		$sql = "SELECT mymoney FROM `{$this->App->prefix()}user` WHERE user_id='$uid' LIMIT 1";
		$mymoney = $this->App->findvar($sql);
		
		$sql = "SELECT * FROM `{$this->App->prefix()}userconfig` WHERE type='basic' LIMIT 1";
		$rL = $this->App->findrow($sql);
		$this->set('rL',$rL);
		unset($rL);
		if(!defined(NAVNAME)) define('NAVNAME', "申请提款");
		$this->set('rts',$rts);
		$this->set('mymoney',$mymoney);
		$mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';
		$this->template($mb.'/v2_postmoney');
	}
	
	//申请提款
	function ajax_postmoney($data=array()){
		$uid = $this->Session->read('User.uid');
		$this->is_daili();
		//$pass = $data['pass'];
		$money = $data['money'];
		$ids = $data['id'];
/*		if($money < 50){
			echo "暂时不能为您服务，先赚取50以上佣金再来吧！";exit;
		}*/
		//检查密码
		//$pass = md5(trim($pass));
		$sql = "SELECT * FROM `{$this->App->prefix()}userconfig` WHERE type='basic' LIMIT 1";
		$rL = $this->App->findrow($sql);
		
		$sql = "SELECT id FROM `{$this->App->prefix()}user_bank` WHERE uid='$uid' LIMIT 1";
		$id = $this->App->findvar($sql);
		if($id > 0){
			//检查资金
			$sql = "SELECT mymoney FROM `{$this->App->prefix()}user` WHERE user_id='$uid' LIMIT 1";
			$mymoney = $this->App->findvar($sql);
			if(intval($mymoney) < intval($money)){
				echo "资金不足，您不能提款！"; exit;
			}
			$money = number_format($money, 2, '.', '');
			if(intval($money) < $rL['dixin360']){
				echo "提款金额最低". $rL['dixin360']."元起！"; exit;
			}
			if(!(intval($ids) > 0)) $ids = $id;
			$sql = "SELECT * FROM `{$this->App->prefix()}user_bank` WHERE id='$ids' LIMIT 1";
			$rr = $this->App->findrow($sql);
			
			$dd = array();
			$dd['uid'] = $uid;
			$dd['money'] = $money;
			$dd['addtime'] = mktime();
			$dd['date'] = date('Y-m',mktime());
			$dd['bankname'] = $rr['bankname'];
			$dd['mobile'] = $rr['bankaddress'];
			$dd['uname'] = $rr['uname'];
			$dd['banksn'] = $rr['banksn'];
			unset($rr);
			if($this->App->insert('user_drawmoney',$dd)){
				$money = -$money;
				$sql = "UPDATE `{$this->App->prefix()}user` SET `mymoney` = `mymoney`+'$money' WHERE user_id = '$uid' LIMIT 1";
				$this->App->query($sql);
				echo "提款成功，等待我们的处理！"; exit;
			}else{
			echo "提款失败，请联系我们客服处理！"; exit;
			}
		}else{
			echo "提款信息错误！"; exit;
		}
	}
		
	function postmoneydata($data=array()){
		$uid = $this->Session->read('User.uid');
		$sql = "SELECT * FROM `{$this->App->prefix()}user_drawmoney` WHERE uid='$uid' ORDER BY id DESC";
		$rt_ = $this->App->find($sql);
		$rt = array();
		if(!empty($rt_))foreach($rt_ as $k=>$row){
			$rt[$k] = $row;
			if(empty($row['banksn'])){
				$sql = "SELECT * FROM `{$this->App->prefix()}user_bank` WHERE uid='$uid' LIMIT 1";
				$rr = $this->App->findrow($sql);
				$rt[$k]['uname'] = $rr['uname'];
				$rt[$k]['mobile'] = $rr['bankaddress'];
				$rt[$k]['bankname'] = $rr['bankname'];
				$rt[$k]['banksn'] = $rr['banksn'];
				unset($rr);
			}
		}
		unset($rt_);
		$this->set('rt',$rt);
		if(!defined(NAVNAME)) define('NAVNAME', '提款记录');
		$mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';
		$this->template($mb.'/v2_postmoneydata');
	}
	
	function fahuo(){
		$this->title("申请发货".' - '.$GLOBALS['LANG']['site_name']);
		$this->is_daili();
		
		if(!defined(NAVNAME)) define('NAVNAME', "申请发货");
		$this->set('rt',$rt);
		$this->template('v2_fahuo');
	}
	/********************************************************/
	//用户登录
	function login(){
		$this->css('login.css');
		if(($this->is_login())){ $this->jump(ADMIN_URL.'daili.php'); exit;} //
		$this->title("代理登录".' - '.$GLOBALS['LANG']['site_name']);
				
		if(!defined(NAVNAME)) define('NAVNAME', "代理登录");
		$this->set('rt',$rt);
		$this->template('user_login');
	}
	
	//重设密码
	function ajax_rp_pass($data=array()){
		$uname = $data['uname'];
		$email = $data['email'];
		$pass = $data['pass'];
		if(empty($uname) || empty($email) || empty($pass)){
			die("目前无法完成您的请求！");
		}
		$md5pass = md5(trim($pass));
		$sql = "UPDATE `{$this->App->prefix()}user` SET password ='$md5pass' WHERE user_name='$uname' AND email='$email' AND user_rank='10'";
		if($this->App->query($sql)){
			die("");
		}else{
			die("目前无法完成您的请求！");
		}
	}
	
	//用户注册
	function register(){
		$this->css('login.css');
		if(($this->is_login())){ $this->jump(ADMIN_URL.'daili.php'); exit;} //
		$this->title("代理注册".' - '.$GLOBALS['LANG']['site_name']);
		$rt['hear'][] = '<a href="'.ADMIN_URL.'">首页</a>&nbsp;&gt;&nbsp;';
		$rt['hear'][] = '代理注册';
		//$rt['province'] = $this->get_regions(1);  //获取省列表

		if(!defined(NAVNAME)) define('NAVNAME', "代理注册");
		$this->set('rt',$rt);
		$this->template('user_register');
	}
			
	//当前文章的分类的所有文章
	function __get_all_article($type='default'){
		$article_list = $this->Cache->read(3600);
	 	if(is_null($rt)) {
			$order = "ORDER BY tb1.vieworder ASC, tb1.article_id DESC";
			$sql = "SELECT tb1.article_title,tb1.cat_id, tb1.article_id,tb2.cat_name FROM `{$this->App->prefix()}article` AS tb1";
			$sql .= " LEFT JOIN `{$this->App->prefix()}article_cate` AS tb2";
			$sql .= " ON tb1.cat_id = tb2.cat_id";
			$sql .=" WHERE tb2.type='$type'  $order";
			$rt = $this->App->find($sql);
			$article_list = array();
			if(!empty($rt)){
					foreach($rt as $k=>$row){
							$article_list[$row['cat_id']][$k] = $row;
							$article_list[$row['cat_id']][$k]['url'] = get_url($row['article_title'],$row['article_id'],$type.'.php?id='.$row['article_id'],'article',array($type,'article',$row['article_id']));
					}
					unset($rt);
			}
			$this->Cache->write($article_list);
		} 
		return $article_list;
	}
	
	//代理设置
	function dailiset(){
		$this->title("基本设置".' - '.$GLOBALS['LANG']['site_name']);
		$uid = $this->Session->read('User.uid');
	
		
		$rt = array();
		if(!defined(NAVNAME)) define('NAVNAME', "基本设置");
		$this->set('rt',$rt);
		$this->template('dailiset');
	}
	
	//代理价格
	function fromprice(){
		$this->title("代理价格说明".' - '.$GLOBALS['LANG']['site_name']);
		$uid = $this->Session->read('User.uid');
		$sql = "SELECT * FROM `{$this->App->prefix()}article` WHERE article_id='122'";
		$rt = $this->App->findrow($sql);
		
		if(!defined(NAVNAME)) define('NAVNAME', (!empty($rt['article_title']) ? $rt['article_title'] : "代理价格说明"));
		$this->set('rt',$rt);
		$this->template('fromprice');
	}
	
	//销售统计
	function saleorder(){
		$this->title("销售统计".' - '.$GLOBALS['LANG']['site_name']);
		$uid = $this->Session->read('User.uid');

		$rt = array();
		if(!defined(NAVNAME)) define('NAVNAME', "销售统计");
		$this->set('rt',$rt);
		$this->template('saleorder');
	}
	
	//我的分红
	function fenhong(){
		$this->title("销售统计".' - '.$GLOBALS['LANG']['site_name']);
		$uid = $this->Session->read('User.uid');

		$rt = array();
		if(!defined(NAVNAME)) define('NAVNAME', "我的分红");
		$this->set('rt',$rt);
		$this->template('fenhong');
	}
	
	//我的推荐
	function mytuijian(){
		$this->title("我的推荐".' - '.$GLOBALS['LANG']['site_name']);
		$uid = $this->Session->read('User.uid');

		$rt = array();
		if(!defined(NAVNAME)) define('NAVNAME', "我的推荐");
		$this->set('rt',$rt);
		$this->template('mytuijian');
	}
	
	//用户后台
	function index(){ 
		$uid = $this->Session->read('User.uid');
		$rank = $this->Session->read('Agent.rank');
		$this->title("会员中心".' - '.$GLOBALS['LANG']['site_name']);
		//if(!($uid>0)){ $this->jump(ADMIN_URL.'daili.php?act=login',0,'请先登录！'); exit;} //
		
		$sql = "SELECT user_id,email,user_name,nickname,reg_time,user_id,user_rank,sex,avatar,birthday,last_login,last_ip,visit_count,qq,office_phone,home_phone,mobile_phone,active FROM `{$this->App->prefix()}user` WHERE user_id ='{$uid}' LIMIT 1";
		$rt['userinfo'] = $this->App->findrow($sql);
		
		$sql = "SELECT tb2.level_name FROM `{$this->App->prefix()}user` AS tb1 LEFT JOIN `{$this->App->prefix()}user_level` AS tb2 ON tb1.user_rank=tb2.lid WHERE tb1.user_id='$uid'";
		$rt['userinfo']['level_name'] = $this->App->findvar($sql);  //
		
		$sql = "SELECT SUM(money) FROM `{$this->App->prefix()}user_money_change` WHERE uid ='{$uid}'";
		$rt['userinfo']['zmoney'] = $this->App->findvar($sql);
		
		$sql = "SELECT SUM(money) FROM `{$this->App->prefix()}user_money_change` WHERE uid ='{$uid}' AND money < '0'";
		$rt['userinfo']['spzmoney'] = $this->App->findvar($sql);
		
		$sql = "SELECT SUM(points) FROM `{$this->App->prefix()}user_point_change` WHERE uid ='{$uid}'";
		$rt['userinfo']['points'] = $this->App->findvar($sql);
		
		//当前用户的收货地址
		$sql = "SELECT tb1.*,tb2.region_name AS provinces,tb3.region_name AS citys,tb4.region_name AS districts FROM `{$this->App->prefix()}user_address` AS tb1";
		$sql .=" LEFT JOIN `{$this->App->prefix()}region` AS tb2 ON tb2.region_id = tb1.province";
		$sql .=" LEFT JOIN `{$this->App->prefix()}region` AS tb3 ON tb3.region_id = tb1.city";
		$sql .=" LEFT JOIN `{$this->App->prefix()}region` AS tb4 ON tb4.region_id = tb1.district";
		$sql .=" WHERE tb1.user_id='$uid' AND tb1.is_own ORDER BY tb1.is_own DESC, tb1.address_id ASC LIMIT 1";
		$rt['userress'] = $this->App->findrow($sql);
		
		if(!defined(NAVNAME)) define('NAVNAME', "财富中心");
		$this->set('rt',$rt);
		$this->template('index');
	}
	
	//AJAX获取我的用户
	function ajax_myuser_page($rts=array()){
		$hh = $rts['hh'];
		$tops = $rts['tops'];
		$level = $rts['level'];
		$tops = intval($tops);
		if(($tops-$hh) >= 0){
			$page = ceil($tops/$hh);
			$list = 30;
			$start = $page*$list;
			$uid = $this->Session->read('User.uid');
			if($level=='0'){
				$sql = "SELECT tb1.*,tb2.subscribe_time,tb2.reg_time,tb2.nickname,tb2.headimgurl,tb2.money_ucount,tb2.points_ucount,tb2.share_ucount,tb2.guanzhu_ucount,tb2.is_subscribe FROM `{$this->App->prefix()}user_tuijian` AS tb1";
				$sql .=" LEFT JOIN `{$this->App->prefix()}user` AS tb2 ON tb1.uid = tb2.user_id";
				$sql .=" WHERE tb1.uid !='$uid' AND (tb1.daili_uid = '$uid' OR tb1.parent_uid = '$uid') ORDER BY tb2.share_ucount DESC,tb2.money_ucount DESC,tb1.id DESC LIMIT $start,$list";
				$ulist = $this->App->find($sql);
			}elseif($level=='1'){
				$ulist = $this->get_myuser_level_1($uid,$start,$list);
			}elseif($level=='2'){
				$ulist = $this->get_myuser_level_2($uid,$start,$list);
			}elseif($level=='3'){
				$ulist = $this->get_myuser_level_3($uid,$start,$list);
			}
			
			$this->set('ulist',$ulist);
			$this->set('pagec',$page*$list);
			echo $this->fetch('load_myuser',true);
		}
		echo "";
		exit;
	}
		
	//一级用户
	function get_myuser_level_1($uid='0',$start='0',$list='30',$nickname=''){
			$where = '';
			if($nickname!=''){$where = " AND tb2.nickname LIKE '%".$nickname."%'";}
			$sql = "SELECT tb1.*,tb2.* FROM `{$this->App->prefix()}user_tuijian` AS tb1";
			$sql .=" LEFT JOIN `{$this->App->prefix()}user` AS tb2 ON tb1.uid = tb2.user_id";
			$sql .=" WHERE tb2.user_id!='' AND tb1.parent_uid = '$uid'".$where." ORDER BY tb2.share_ucount DESC,tb2.money_ucount DESC,tb1.id DESC LIMIT $start,$list";
			//exit($sql);
			return $this->App->find($sql);
	}
	
	//二级用户
	function get_myuser_level_2($uid='0',$start='0',$list='30',$nickname=''){
			$where = '';
			if($nickname!=''){$where = " AND tb3.nickname LIKE '%".$nickname."%'";}
			$sql = "SELECT tb2.*,tb3.* FROM `{$this->App->prefix()}user_tuijian` AS tb1";
			$sql .= " LEFT JOIN `{$this->App->prefix()}user_tuijian` AS tb2 ON tb2.parent_uid = tb1.uid ";
			$sql .= " LEFT JOIN `{$this->App->prefix()}user` AS tb3 ON tb2.uid = tb3.user_id";
			$sql .= " WHERE tb1.parent_uid='$uid' AND tb2.uid IS NOT NULL".$where." ORDER BY tb3.share_ucount DESC,tb3.money_ucount DESC,tb2.id DESC LIMIT $start,$list";
			//exit($sql);
			return $this->App->find($sql);
	}
	
	//三级用户
	function get_myuser_level_3($uid='0',$start='0',$list='30',$nickname=''){
			$where = '';
			if($nickname!=''){$where = " AND tb4.nickname LIKE '%".$nickname."%'";}
			$sql = "SELECT tb3.*,tb4.* FROM `{$this->App->prefix()}user_tuijian` AS tb1";
			$sql .= " LEFT JOIN `{$this->App->prefix()}user_tuijian` AS tb2 ON tb2.parent_uid = tb1.uid ";
			$sql .= " LEFT JOIN `{$this->App->prefix()}user_tuijian` AS tb3 ON tb3.parent_uid = tb2.uid ";
			$sql .= " LEFT JOIN `{$this->App->prefix()}user` AS tb4 ON tb3.uid = tb4.user_id";
			$sql .= " WHERE tb1.parent_uid='$uid' AND tb3.uid IS NOT NULL".$where."  ORDER BY tb4.share_ucount DESC,tb4.money_ucount DESC,tb3.id DESC LIMIT $start,$list";
			return $this->App->find($sql);
	}
	
	function myuserinfo($data=array()){
		$this->checked_login();
		
		$uid = $data['uid'];
		
		$sql = "SELECT * FROM `{$this->App->prefix()}user` WHERE user_id ='{$uid}' AND active='1' LIMIT 1";
		$rt['userinfo'] = $this->App->findrow($sql);
		
		$sql = "SELECT tb2.level_name FROM `{$this->App->prefix()}user` AS tb1 LEFT JOIN `{$this->App->prefix()}user_level` AS tb2 ON tb1.user_rank=tb2.lid WHERE tb1.user_id='$uid'";
		$rt['userinfo']['level_name'] = $this->App->findvar($sql);  //
		
		$sql = "SELECT SUM(money) FROM `{$this->App->prefix()}user_money_change` WHERE uid ='{$uid}'";
		$rt['userinfo']['zmoney'] = $this->App->findvar($sql);
		
		$sql = "SELECT SUM(order_amount) FROM `{$this->App->prefix()}goods_order_info_daigou` WHERE user_id ='{$uid}' AND pay_status = '1'";
		$rt['userinfo']['spzmoney'] = $this->App->findvar($sql);
		
		$sql = "SELECT SUM(points) FROM `{$this->App->prefix()}user_point_change` WHERE uid ='{$uid}'";
		$rt['userinfo']['points'] = $this->App->findvar($sql);
		
		$sql = "SELECT COUNT(user_id) FROM `{$this->App->prefix()}user` WHERE is_subscribe ='1' LIMIT 1";
		$rt['gzcount'] = $this->App->findvar($sql);
		
		$sql = "SELECT tb1.nickname FROM `{$this->App->prefix()}user` AS tb1 LEFT JOIN `{$this->App->prefix()}user_tuijian` AS tb2 ON tb1.user_id = tb2.parent_uid LEFT JOIN `{$this->App->prefix()}user` AS tb3 ON tb3.user_id = tb2.uid WHERE tb3.user_id='$uid' LIMIT 1";
		$rt['tjren'] = $this->App->findvar($sql);
		
		//一级
		$rt['zcount1'] = $this->App->findvar("SELECT COUNT(id) FROM `{$this->App->prefix()}user_tuijian` WHERE parent_uid = '$uid' LIMIT 1");
		//二级
		//$rt['zcount2'] = $this->App->findvar("SELECT COUNT(DISTINCT tb2.uid) FROM `{$this->App->prefix()}user_tuijian` AS tb1 LEFT JOIN `{$this->App->prefix()}user_tuijian` AS tb2 ON tb2.parent_uid = tb1.uid AND tb2.uid != tb2.daili_uid WHERE tb1.parent_uid='$uid' LIMIT 1");
		$sql = "SELECT COUNT(tb1.id) FROM `{$this->App->prefix()}user_tuijian` AS tb1";
		$sql .= " LEFT JOIN `{$this->App->prefix()}user_tuijian` AS tb2 ON tb2.parent_uid = tb1.uid ";
		$sql .= " WHERE tb1.parent_uid='$uid' AND tb2.uid IS NOT NULL  LIMIT 1";
		$rt['zcount2'] = $this->App->findvar($sql);
		
		//三级
		//$rt['zcount3'] = $this->App->findvar("SELECT COUNT(tb3.id) FROM `{$this->App->prefix()}user_tuijian` AS tb1 LEFT JOIN `{$this->App->prefix()}user_tuijian` AS tb2 ON tb2.parent_uid = tb1.uid AND tb2.uid != tb2.daili_uid LEFT JOIN `{$this->App->prefix()}user_tuijian` AS tb3 ON tb3.parent_uid = tb2.uid AND tb3.uid != tb3.daili_uid WHERE tb1.parent_uid='$uid' LIMIT 1");
		$sql = "SELECT COUNT(tb1.id) FROM `{$this->App->prefix()}user_tuijian` AS tb1";
		$sql .= " LEFT JOIN `{$this->App->prefix()}user_tuijian` AS tb2 ON tb2.parent_uid = tb1.uid ";
		$sql .= " LEFT JOIN `{$this->App->prefix()}user_tuijian` AS tb3 ON tb3.parent_uid = tb2.uid ";
		$sql .= " WHERE tb1.parent_uid='$uid' AND tb3.uid IS NOT NULL  LIMIT 1";
		$rt['zcount3'] = $this->App->findvar($sql);
		
		if(!defined(NAVNAME)) define('NAVNAME', "分销会员详情");
		$this->set('rt',$rt);
		
		$mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';
		$this->template($mb.'/v2_myuserinfo');
	}
	
	//我的客户
	function myuser(){
		$this->checked_login();
		$uid = $this->Session->read('User.uid');
		$ts = isset($_GET['t']) ? $_GET['t'] : '0';
		$ts = isset($_POST['t']) ? $_POST['t'] : $ts;
		$l = $ts=='1' ? '一代' : ($ts=='2' ? '二代' : '三代');
		if(!defined(NAVNAME)) define('NAVNAME', $l);
		//分页
		$page= isset($_GET['page']) ? $_GET['page'] : '1';
		if(empty($page)){
			  $page = 1;
		}
		$list = 30;
		$start = ($page-1)*$list;
		if($ts=='0'){
			//全部用户
			$sql = "SELECT tb1.*,tb2.subscribe_time,tb2.reg_time,tb2.user_rank,tb2.user_id,tb2.nickname,tb2.headimgurl,tb2.points_ucount,tb2.money_ucount,tb2.share_ucount,tb2.guanzhu_ucount,tb2.is_subscribe FROM `{$this->App->prefix()}user_tuijian` AS tb1";
			$sql .=" LEFT JOIN `{$this->App->prefix()}user` AS tb2 ON tb1.uid = tb2.user_id";
			$sql .=" WHERE AND tb1.uid !='$uid' AND (tb1.daili_uid = '$uid' OR tb1.parent_uid = '$uid') ORDER BY tb2.share_ucount DESC,tb2.money_ucount DESC,tb1.id DESC LIMIT $start,$list";
			$rt['lists'] = $this->App->find($sql);
		}elseif($ts=='1'){
			//一级用户
			if($_POST['nickname']){
				$rt['lists'] = $this->get_myuser_level_1($uid,$start,$list,$_POST['nickname']);
			}else{
				$rt['lists'] = $this->get_myuser_level_1($uid,$start,$list,'');
			}			
		}elseif($ts=='2'){
			//一级用户
			if($_POST['nickname']){
				$rt['lists'] = $this->get_myuser_level_2($uid,$start,$list,$_POST['nickname']);
			}else{
				$rt['lists'] = $this->get_myuser_level_2($uid,$start,$list,'');
			}
		}elseif($ts=='3'){
			//一级用户
			if($_POST['nickname']){
				$rt['lists'] = $this->get_myuser_level_3($uid,$start,$list,$_POST['nickname']);
			}else{
				$rt['lists'] = $this->get_myuser_level_3($uid,$start,$list,'');
			}
		}
		
		$this->set('level',$ts);
		$this->set('rt',$rt);
		$mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';
		$this->template($mb.'/v2_myuser');
	}
	
	function liuyan(){
		$this->checked_login();
		$uid = $this->Session->read('User.uid');
		if(!defined(NAVNAME)) define('NAVNAME', '留言中心');
		
		if(isset($_GET['t_uid'])){
			$t_uid = $_GET['t_uid'];
			$this->set('t_uid',$t_uid);
		}
		//提交留言
		if(isset($_POST['submit'])){
			$t_uid = $_POST['t_uid'];
			$content = $_POST['content'];
			$f_uid = $uid;
			$f_nickname = $this->App->findvar("SELECT `nickname` FROM `{$this->App->prefix()}user` WHERE `user_id`=".$f_uid);	//留言人昵称;
			$t_wechat = $this->App->findvar("SELECT `wecha_id` FROM `{$this->App->prefix()}user` WHERE `user_id`=".$t_uid);	//接受者微信OPENID
			$arr = array('openid'=>$t_wechat,'appid'=>'','appsecret'=>'','nickname'=>$f_nickname,'f_uid'=>$f_uid,'content'=>$content);
			$this->action('api','sendtxt',$arr,'liuyan');
			echo "<script>alert('留言成功！')</script>";
			echo "<script>window.location.href='/m/user.php';</script>";
			exit();
		}
		$mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';
		$this->template($mb.'/liuyan');
	}
	
	function myusertype(){
		$this->checked_login();
		$uid = $this->Session->read('User.uid');
		if(!defined(NAVNAME)) define('NAVNAME', "我的分销");
		
		$rank = $this->App->findvar("SELECT user_rank FROM `{$this->App->prefix()}user` WHERE user_id='$uid' LIMIT 1");
		//全部下级 如果是高级分销商可以统计所有客户
		$rt['zcount'] = 0;
		if($rank=='10'){
			$rt['zcount'] = $this->App->findvar("SELECT COUNT(id) FROM `{$this->App->prefix()}user_tuijian` WHERE uid !='$uid' AND (daili_uid='$uid' OR parent_uid='$uid') LIMIT 1");
		}
		//一级
		$rt['zcount1'] = $this->App->findvar("SELECT COUNT(id) FROM `{$this->App->prefix()}user_tuijian` WHERE parent_uid = '$uid' LIMIT 1");
		//二级
		$sql = "SELECT COUNT(tb1.id) FROM `{$this->App->prefix()}user_tuijian` AS tb1";
		$sql .= " LEFT JOIN `{$this->App->prefix()}user_tuijian` AS tb2 ON tb2.parent_uid = tb1.uid ";
		$sql .= " WHERE tb1.parent_uid='$uid' AND tb2.uid IS NOT NULL  LIMIT 1";
		$rt['zcount2'] = $this->App->findvar($sql);
		
		//三级
		$sql = "SELECT COUNT(tb1.id) FROM `{$this->App->prefix()}user_tuijian` AS tb1";
		$sql .= " LEFT JOIN `{$this->App->prefix()}user_tuijian` AS tb2 ON tb2.parent_uid = tb1.uid ";
		$sql .= " LEFT JOIN `{$this->App->prefix()}user_tuijian` AS tb3 ON tb3.parent_uid = tb2.uid ";
		$sql .= " WHERE tb1.parent_uid='$uid' AND tb3.uid IS NOT NULL  LIMIT 1";
		$rt['zcount3'] = $this->App->findvar($sql);
		
		$this->set('rt',$rt);
		$mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';
		$this->template($mb.'/v2_myusertype');
	}
	
	//我的分享
	function myshare(){
		$this->checked_login();
		$this->title("我的分享".' - '.$GLOBALS['LANG']['site_name']);
		$uid = $this->Session->read('User.uid');
		$id= isset($_GET['id']) ? $_GET['id'] : '0';
		if($id > 0){
			$this->App->delete('user_tuijian','id',$id);
			$this->jump(ADMIN_URL.'daili.php?act=myshare');exit;
		}
		//分页
		$page= isset($_GET['page']) ? $_GET['page'] : '1';
		if(empty($page)){
			  $page = 1;
		}
		$list = 10;
		$start = ($page-1)*$list;
		$sql = "SELECT COUNT(tb1.id) FROM `{$this->App->prefix()}user_tuijian` AS tb1";
		$sql .=" LEFT JOIN `{$this->App->prefix()}user` AS tb2";
		$sql .=" ON tb1.uid = tb2.user_id WHERE tb1.daili_uid = '$uid'";
		$tt = $this->App->findvar($sql);
		$rt['pages'] = Import::basic()->getpage($tt, $list, $page,'?page=',true);

		$sql = "SELECT tb1.*,tb2.user_name,tb2.nickname FROM `{$this->App->prefix()}user_tuijian` AS tb1";
		$sql .=" LEFT JOIN `{$this->App->prefix()}user` AS tb2";
		$sql .=" ON tb1.uid = tb2.user_id";
		$sql .=" WHERE tb1.share_uid = '$uid' ORDER BY id DESC LIMIT $start,$list";
		$rt['lists'] = $this->App->find($sql);
		
		if(!defined(NAVNAME)) define('NAVNAME', "我的分享");
		$this->set('rt',$rt);
				$mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';
		$this->template($mb.'/myshare');
	}
	
	//调研投票
	function myvotes(){
		$this->title("调研投票".' - '.$GLOBALS['LANG']['site_name']);
		
		$rt = array();
		if(!defined(NAVNAME)) define('NAVNAME', "调研投票");
		$this->set('rt',$rt);
		$this->template('myvotes');
	}
	
	

	
	function ajax_getorderlist($data=array()){
		$dt = isset($data['time'])&&intval($data['time'])>0 ?  intval($data['time']) : "";
		$status = isset($data['status']) ?  trim($data['status']) : "";
		$keyword = isset($data['keyword']) ?  trim($data['keyword']) : "";
		$page = isset($data['page'])&&intval($data['page']>0) ? intval($data['page']) : 1;
		$list = 5;
		//用户订单
		$uid = $this->Session->read('User.uid');
		$w_rt[] = "tb1.user_id = '$uid'";	
		if(!empty($dt)){
			$ts = mktime()-$dt;
			$w_rt[] = "tb1.add_time > '$ts'";	
		}
		
		if(!empty($status)){
			   $st = $this->select_statue($status);
               !empty($st)? $w_rt[] = $st : "";
		}
		if(!empty($keyword)){
			$w_rt[] = "(tb2.goods_name LIKE '%".$keyword."%' OR tb1.order_sn LIKE '%".$keyword."%')";
		}
	
		$tt = $this->__order_list_count($w_rt); //获取商品的数量
		$rt['order_count'] = $tt;
		
		$rt['orderpage'] = Import::basic()->ajax_page($tt,$list,$page,'get_order_page_list',array($status));

		$rt['orderlist'] = $this->__order_list($w_rt,$page,$list);
		$rt['status'] = $status;
		$rt['keyword'] = $keyword;
		$rt['time'] = $dt;
		
		$this->set('rt',$rt);
		$con = $this->fetch('ajax_orderlist',true);
		die($con);
	}
	###########################
	//用户订单列表
	function __order_list($w_rt=array(),$page=1,$list=5){
		if(is_array($w_rt)){
			if(!empty($w_rt)){
				$w = " WHERE ".implode(' AND ',$w_rt);
			}
		}else{
			$w = " WHERE ".$w_rt;
		}
		if(!$page) $page=1;
		$start = ($page-1)*$list;
		$sql = "SELECT distinct tb1.order_id, tb1.order_sn, tb1.order_status, tb1.shipping_status,tb1.shipping_name ,tb1.pay_name, tb1.pay_status, tb1.add_time,tb1.consignee, (tb1.goods_amount + tb1.shipping_fee) AS total_fee FROM `{$this->App->prefix()}goods_order_info` AS tb1";
		$sql .=" LEFT JOIN `{$this->App->prefix()}goods_order` AS tb2 ON tb1.order_id=tb2.order_id";
		$sql .=" $w ORDER BY tb1.add_time DESC LIMIT $start,$list";
		 $orderlist = $this->App->find($sql);
		 if(!empty($orderlist)){
			 foreach($orderlist as $k=>$row){
			 
				$orderlist[$k]['status'] = $this->get_status($row['order_status'],$row['pay_status'],$row['shipping_status']);
				$orderlist[$k]['op'] = $this->get_option($row['order_id'],$row['order_status'],$row['pay_status'],$row['shipping_status']);
				$sql= "SELECT goods_id,goods_name,goods_bianhao,market_price,goods_price,goods_thumb FROM `{$this->App->prefix()}goods_order` WHERE order_id='$row[order_id]' ORDER BY goods_id";
				$orderlist[$k]['goods'] = $this->App->find($sql);
			 }
		 }
		 return $orderlist;
	}
	
	function __order_list_count($w_rt=array()){
		if(is_array($w_rt)){
			if(!empty($w_rt)){
				$w = " WHERE ".implode(' AND ',$w_rt);
			}
		}else{
			$w = " WHERE ".$w_rt;
		}
		$sql = "SELECT COUNT(distinct tb1.order_id) FROM `{$this->App->prefix()}goods_order_info` AS tb1";
		$sql .=" LEFT JOIN `{$this->App->prefix()}goods_order` AS tb2 ON tb1.order_id=tb2.order_id ".$w;
		return $this->App->findvar($sql);
	}
	
	//订单详情
	function orderinfo($orderid=""){
		$this->title("欢迎进入会员中心".' - 订单详情 - '.$GLOBALS['LANG']['site_name']);
		$uid = $this->Session->read('User.uid');
		if(empty($uid)){ $this->jump(ADMIN_URL.'daili.php?act=login',0,'请先登录！'); exit;}
		if(empty($orderid)){ $this->jump('daili.php?act=myorder'); exit; }
		
		$sql= "SELECT * FROM `{$this->App->prefix()}goods_order` WHERE order_id='$orderid' ORDER BY goods_id";
		$rt['goodslist'] = $this->App->find($sql);
		
		$sql = "SELECT tb1.*,tb2.region_name AS province,tb3.region_name AS city,tb4.region_name AS district FROM `{$this->App->prefix()}goods_order_info` AS tb1";
		$sql .=" LEFT JOIN `{$this->App->prefix()}region` AS tb2 ON tb2.region_id = tb1.province";
		$sql .=" LEFT JOIN `{$this->App->prefix()}region` AS tb3 ON tb3.region_id = tb1.city";
		$sql .=" LEFT JOIN `{$this->App->prefix()}region` AS tb4 ON tb4.region_id = tb1.district";
		$sql .=" WHERE tb1.order_id='$orderid'";	
		$rt['orderinfo'] = $this->App->findrow($sql);
		
		$status = $this->get_status($rt['orderinfo']['order_status'],$rt['orderinfo']['pay_status'],$rt['orderinfo']['shipping_status']);
		$rt['status'] = explode(',',$status);
		
		//$rt['recommend10'] = $this->action('catalog','recommend_goods');

		//商品分类列表		
		$rt['menu'] = $this->action('catalog','get_goods_cate_tree');
				
		if(!defined(NAVNAME)) define('NAVNAME', "订单详情");
		$this->set('rt',$rt);
		$this->template('user_orderinfo');
	}
	
	 //选择订单的所在状态
     function select_statue($id=""){
            if(empty($id)) return "";
            switch ($id){
                case '-1':
                    return "";
                    break;
                case '11':
                    return "tb1.order_status='0'";
                    break;
                case '200':
                    return "tb1.order_status='2' AND tb1.shipping_status='0' AND tb1.pay_status='0'";
                    break;
                case '210':
                    return "tb1.order_status='2' AND tb1.shipping_status='0' AND tb1.pay_status='1'";
                    break;
                case '214':
                    return "tb1.order_status='2' AND tb1.shipping_status='4' AND tb1.pay_status='1'";
                    break;
                case '1':
                    return "tb1.order_status='1'";
                    break;
                case '4':
                    return "tb1.order_status='4'";
                    break;
                case '3':
                    return "tb1.order_status='3'";
                    break;
                case '2':
                    return "tb1.pay_status='2'";
                    break;
				case '222': //已发货
					return "tb1.shipping_status='2'";
					break;
                default :
                    return "";
                    break;
            }
      }
	##############################
	function error_jump(){

		$this->action('common','show404tpl');
	}
	
	//订单列表
	function orderlist(){
		$this->title("欢迎进入会员中心".' - 我的订单 - '.$GLOBALS['LANG']['site_name']);
		$dt = isset($_GET['dt'])&&intval($_GET['dt'])>0 ?  intval($_GET['dt']) : "";
		$status = isset($_GET['status']) ?  trim($_GET['status']) : "";
		$keyword = isset($_GET['kk']) ?  trim($_GET['kk']) : "";
		$uid = $this->Session->read('User.uid');
		if(empty($uid)){ $this->jump(ADMIN_URL.'daili.php?act=login',0,'请先登录！'); exit;}
		//用户订单
		$w_rt[] = "tb1.user_id = '$uid'";	
		if(!empty($dt)){
			$w_rt[] = "tb1.add_time < '$dt'";	
		}
		
		if(!empty($status)){
			   $st = $this->select_statue($status);
               !empty($st)? $w_rt[] = $st : "";
		}
		if(!empty($keyword)){
			$w_rt[] = "(tb2.goods_name LIKE '%".$keyword."%' OR tb1.order_sn LIKE '%".$keyword."%')";
		}
		
		$page = 1;
		$list = 5;
		$tt = $this->__order_list_count($w_rt); //获取商品的数量
		$rt['order_count'] = $tt;
		
		$rt['orderpage'] = Import::basic()->getpage($tt,$list,$page,'?page=',true);

		$rt['orderlist'] = $this->__order_list($w_rt,$page,$list);
		$rt['status'] = $status;
		
		$rt['userinfo']['user_id'] = $this->Session->$uid;

		$sql = "SELECT COUNT(distinct order_id) FROM `{$this->App->prefix()}goods_order_info` WHERE user_id='$uid' AND order_status='2'";
		$rt['userinfo']['success_ordercount'] = $this->App->findvar($sql); //成功订单
	
		$sql = "SELECT COUNT(distinct order_id) FROM `{$this->App->prefix()}goods_order_info` WHERE user_id='$uid' AND pay_status='0'";
		$rt['userinfo']['pay_ordercount'] = $this->App->findvar($sql); //待支付订单
		
		$sql = "SELECT COUNT(distinct order_id) FROM `{$this->App->prefix()}goods_order_info` WHERE user_id='$uid' AND shipping_status='2'";
		$rt['userinfo']['shopping_ordercount'] = $this->App->findvar($sql); //待发货订单
		
		$sql = "SELECT COUNT(distinct order_id) FROM `{$this->App->prefix()}goods_order_info` WHERE user_id='$uid'";
		$rt['userinfo']['all_ordercount'] = $this->App->findvar($sql); //所有订单
		
		$sql = "SELECT COUNT(distinct order_id) FROM `{$this->App->prefix()}goods_order_info` WHERE user_id='$uid' AND (tb6.shipping_status='2' OR tb6.pay_status='0' OR tb6.order_status='0')";
		$rt['userinfo']['daichuli_ordercount'] = $this->App->findvar($sql); //待处理订单
		
		$sql = "SELECT COUNT(distinct order_id) FROM `{$this->App->prefix()}goods_order_info` WHERE user_id='$uid' AND shipping_status='5'";
		$rt['userinfo']['haicheng_ordercount'] = $this->App->findvar($sql); //已完成订单
		
		$sql = "SELECT COUNT(distinct order_id) FROM `{$this->App->prefix()}goods_order_info` WHERE user_id='$uid' AND order_status='1'";
		$rt['userinfo']['quxiao_ordercount'] = $this->App->findvar($sql); //已取消订单
		
		$sql = "SELECT COUNT(distinct order_id) FROM `{$this->App->prefix()}goods_order_info` WHERE user_id='$uid' AND shipping_status='2'";
		$rt['userinfo']['yifahuo_ordercount'] = $this->App->findvar($sql); //已发货
		
		$sql = "SELECT COUNT(og.goods_id) FROM `{$this->App->prefix()}order_goods` AS og";
		$sql .=" LEFT JOIN `{$this->App->prefix()}order_goods` AS oi ON og.order_id = oi.order_id";
		$sql .=" WHERE oi.shipping_status='5' AND oi.user_id='$uid' AND og.goods_id NOT IN(SELECT id_value FROM `{$this->App->prefix()}comment` WHERE user_id='$uid')";
		$rt['userinfo']['need_comment_count'] = $this->App->findvar($sql);
		//print_r($rt);
		
		//商品分类列表		
		$rt['menu'] = $this->action('catalog','get_goods_cate_tree');
		
		if(!defined(NAVNAME)) define('NAVNAME', "我的订单");
		$this->set('rt',$rt);
		$this->set('page',$page);
		$this->template('user_orderlist');
	}
	
	//用户资料
	function myinfo(){
		$this->title("欢迎进入会员中心".' - 我的资料 - '.$GLOBALS['LANG']['site_name']);
		$uid = $this->Session->read('User.uid');
		if(empty($uid)){ $this->jump(ADMIN_URL.'daili.php?act=login',0,'请先登录！'); exit;}
		
		$sql = "SELECT * FROM `{$this->App->prefix()}user` WHERE user_id ='{$uid}' AND user_rank='10' LIMIT 1";
		$rt['userinfo'] = $this->App->findrow($sql);
		
		$rt['province'] = $this->get_regions(1);  //获取省列表
		
		//当前用户的收货地址
		$sql = "SELECT * FROM `{$this->App->prefix()}user_address` WHERE user_id='$uid' AND is_own='1' LIMIT 1";
		$rt['userress'] = $this->App->findrow($sql);

		if($rt['userress']['province']>0) $rt['city'] = $this->get_regions(2,$rt['userress']['province']);  //城市
		if($rt['userress']['city']>0) $rt['district'] = $this->get_regions(3,$rt['userress']['city']);  //区		
				
		$this->set('rt',$rt);
		if(!defined(NAVNAME)) define('NAVNAME', "代理资料");
		$this->template('user_info');
	}
	
	//收货地址
	function address(){
		$this->title("欢迎进入会员中心".' - 收货地址 - '.$GLOBALS['LANG']['site_name']);
		$uid = $this->Session->read('User.uid');
		if(empty($uid)){ $this->jump(ADMIN_URL.'daili.php?act=login',0,'请先登录！'); exit;}
		
		/*if(isset($_POST)&&!empty($_POST)){
			
			if(empty($_POST['province'])){
				$this->jump('daili.php?act=address_list',0,'选择省份！'); exit;
			}else if(empty($_POST['city'])){
				$this->jump('daili.php?act=address_list',0,'选择城市！');exit;
			}else if(empty($_POST['consignee'])){
				$this->jump('daili.php?act=address_list',0,'收货人不能为空！');exit;
			}else if(empty($_POST['email'])){
				$this->jump('daili.php?act=address_list',0,'电子邮箱不能为空！');exit;
			}else if(empty($_POST['address'])){
				$this->jump('daili.php?act=address_list',0,'收货地址不能为空！');exit;
			}else if(empty($_POST['tel'])){
				$this->jump('daili.php?act=address_list',0,'电话号码不能为空！');exit;
			}
			
			if(!isset($_POST['address_id'])&&empty($_POST['address_id'])){ //添加
					$_POST['user_id'] = $uid;
					if($this->App->insert('user_address',$_POST)){
						if(isset($_GET['ty'])&&$_GET['ty']=='cart'){
							$this->jump('mycart.php?type=checkout'); exit;
						}else{
							$this->jump('',0,'添加成功！');exit;
						}
					}else{
						$this->jump('',0,'添加失败！');exit;
					}
				
			}else{ //修改
				$address_id = $_POST['address_id'];
				$_POST = array_diff_key($_POST,array('address_id'=>'0'));
				if($this->App->update('user_address',$_POST,'address_id',$address_id )){
					if(isset($_GET['ty'])&&$_GET['ty']=='cart'){
						$this->jump('mycart.php?type=checkout'); exit;
					}else{
						$this->jump('',0,'更新成功！');exit;
					}
				}
				else{
					$this->jump('',0,'更新失败！');exit;
				}
			}
		}*/
		
		$rt['province'] = $this->get_regions(1);  //获取省列表
		
		//当前用户的收货地址
		$sql = "SELECT * FROM `{$this->App->prefix()}user_address` WHERE user_id='$uid' AND is_own='0'";
		$rt['userress'] = $this->App->find($sql);
		if(!empty($rt['userress'])){
			foreach($rt['userress'] as $row){
				$rt['city'][$row['address_id']] = $this->get_regions(2,$row['province']);  //城市
				$rt['district'][$row['address_id']] = $this->get_regions(3,$row['city']);  //区
			}
		}
		
		
		$sql = "SELECT tb1.*,tb2.region_name AS provinces,tb3.region_name AS citys,tb4.region_name AS districts FROM `{$this->App->prefix()}user_address` AS tb1";
		$sql .=" LEFT JOIN `{$this->App->prefix()}region` AS tb2 ON tb2.region_id = tb1.province";
		$sql .=" LEFT JOIN `{$this->App->prefix()}region` AS tb3 ON tb3.region_id = tb1.city";
		$sql .=" LEFT JOIN `{$this->App->prefix()}region` AS tb4 ON tb4.region_id = tb1.district";
		$sql .=" WHERE tb1.user_id='$uid' AND tb1.is_own = '0' ORDER BY tb1.address_id ASC";
		$rt['userress'] = $this->App->find($sql);
		
		//商品分类列表		
		$rt['menu'] = $this->action('catalog','get_goods_cate_tree');
		if(!defined(NAVNAME)) define('NAVNAME', "收货地址簿");
		$this->set('rt',$rt);
		$this->template('user_consignee_address');
	}
	
	//用户密码修改
	function editpass(){
		$uid = $this->Session->read('User.uid');
		if(empty($uid)){ $this->jump(ADMIN_URL.'daili.php?act=login',0,'请先登录！'); exit;}
		$this->title("欢迎进入会员中心".' - 用户密码修改 - '.$GLOBALS['LANG']['site_name']);
		
		//商品分类列表		
		$rt['menu'] = $this->action('catalog','get_goods_cate_tree');
		$this->set('rt',$rt);
		if(!defined(NAVNAME)) define('NAVNAME', "修改密码");
		$this->template('user_editpass');
	}
	//用户订单操作
	function ajax_order_op($id=0,$op=""){
   		if(empty($id) || empty($op)) die("传送ID为空！");
		if($op=="cancel_order")
			$this->App->update('goods_order_info',array('order_status'=>'1'),'order_id',$id);
		else if($op=="confirm")
			$this->App->update('goods_order_info',array('shipping_status'=>'5'),'order_id',$id);
   }
   
   //我的余额
   function mymoney($page=1){
   		$this->title("欢迎进入会员中心".' - 我的余额 - '.$GLOBALS['LANG']['site_name']);
   		$uid = $this->Session->read('User.uid');
		if(empty($uid)){ $this->jump(ADMIN_URL.'daili.php?act=login',0,'请先登录！'); exit;}
		$sql = "SELECT SUM(money) FROM `{$this->App->prefix()}user_money_change` WHERE uid='$uid'";
		$rt['zmoney'] = $this->App->findvar($sql);
		$rt['zmoney'] = format_price($rt['zmoney']);
		//分页
		if(empty($page)){
			   $page = 1;
		}
		$list = 10 ; //每页显示多少个
		$start = ($page-1)*$list;
		$tt = $this->App->findvar("SELECT COUNT(cid) FROM `{$this->App->prefix()}user_money_change` WHERE uid='$uid'");
		$rt['pages'] = Import::basic()->getpage($tt,$list,$page,'?page=',true);
		$sql = "SELECT * FROM `{$this->App->prefix()}user_money_change` WHERE uid='$uid' ORDER BY time DESC LIMIT $start,$list";
		$rt['lists'] = $this->App->find($sql); 
		$rt['page'] = $page;
		//商品分类列表		
		$this->set('rt',$rt);
		
		//ajax
		if(isset($_GET['type'])&&$_GET['type']=='ajax'){
			echo  $this->fetch('ajax_user_moneychange',true);
			exit;
		}
		if(!defined(NAVNAME)) define('NAVNAME', "我的资金");
   		$this->template('mymoney');
   }
   
   //我的积分
   function mypoints(){
   		$this->title("欢迎进入会员中心".' - 我的积分 - '.$GLOBALS['LANG']['site_name']);
   		$uid = $this->Session->read('User.uid');
		if(empty($uid)){ $this->jump(ADMIN_URL.'daili.php?act=login',0,'请先登录！'); exit;}
		$sql = "SELECT SUM(points) FROM `{$this->App->prefix()}user_point_change` WHERE uid='$uid'";
		$rt['zpoints'] = $this->App->findvar($sql);
   		//分页
		$page = isset($_GET['page'])&&intval($_GET['page'])>0 ? intval($_GET['page']) : 1;
		if(empty($page)){
			   $page = 1;
		}
		$list = 10 ; //每页显示多少个
		$start = ($page-1)*$list;
		$tt = $this->App->findvar("SELECT COUNT(cid) FROM `{$this->App->prefix()}user_point_change` WHERE uid='$uid'");
		$rt['pages'] = Import::basic()->getpage($tt,$list,$page,'?page=',true);
		$sql = "SELECT * FROM `{$this->App->prefix()}user_point_change` WHERE uid='$uid' ORDER BY time DESC LIMIT $start,$list";
		$rt['lists'] = $this->App->find($sql); //商品列表
		$rt['page'] = $page;
		
		//商品分类列表		
		//$rt['menu'] = $this->action('catalog','get_goods_cate_tree');
				
		$this->set('rt',$rt);
		
		//ajax
		if(isset($_GET['type'])&&$_GET['type']=='ajax'){
			echo  $this->fetch('ajax_user_pointchange',true);
			exit;
		}
		if(!defined(NAVNAME)) define('NAVNAME', "我的积分");
   		$this->template('mypoints');
   }
   
   //用户收藏
   function mycolle(){
   		$uid = $this->Session->read('User.uid');
		if(empty($uid)){ $this->jump(ADMIN_URL.'daili.php?act=login',0,'请先登录！'); exit;}
		$this->js('goods.js');
   		$this->title("欢迎进入会员中心".' - 我的收藏 - '.$GLOBALS['LANG']['site_name']);
		$id = isset($_GET['id']) ? $_GET['id'] : 0;
		if($id > 0){
			$this->App->delete('shop_collect','rec_id',$id);
			$this->jump(ADMIN_URL.'daili.php?act=mycoll');exit;
		}
		//分页
		$page = isset($_GET['page']) ? $_GET['page'] : 1;
		if(empty($page)){
			   $page = 1;
		}
		$list = 4 ; //每页显示多少个
		$start = ($page-1)*$list;
		$tt = $this->App->findvar("SELECT COUNT(rec_id) FROM `{$this->App->prefix()}goods_collect` WHERE user_id='$uid'");
		$rt['pages'] = Import::basic()->ajax_page($tt,$list,$page,'get_usercolle_page_list');
		$sql = "SELECT tb1.rec_id,tb1.user_id,tb1.add_time,tb2.goods_id, tb2.goods_name,tb2.goods_bianhao,tb2.shop_price, tb2.market_price,tb2.pifa_price,tb2.goods_thumb, tb2.original_img, tb2.goods_img,tb2.promote_start_date,tb2.promote_end_date,tb2.promote_price,tb2.is_promote,tb2.qianggou_start_date,tb2.qianggou_end_date,tb2.qianggou_price,tb2.is_qianggou FROM `{$this->App->prefix()}goods_collect` AS tb1";
		$sql .=" LEFT JOIN `{$this->App->prefix()}goods` AS tb2 ON tb1.goods_id=tb2.goods_id";
		$sql .=" WHERE tb1.user_id='$uid' ORDER BY tb1.add_time DESC LIMIT $start,$list";
		$rt['lists'] = $this->App->find($sql); //商品列表
		
				
		$this->set('rt',$rt);
		if(isset($_GET['type'])&&$_GET['type']=='ajax'){
			echo  $this->fetch('ajax_mycoll',true);
			exit;
		}
		if(!defined(NAVNAME)) define('NAVNAME', "我的收藏");
  		$this->template('user_mycolle');
   }
   
   //ajax删除收藏
   function ajax_delmycoll($ids=0){
   		if(empty($ids)) die("非法删除，删除ID为空！");
		$id_arr = @explode('+',$ids);
		foreach($id_arr as $id){
		  if(Import::basic()->int_preg($id)) $this->App->delete('shop_collect','rec_id',$id);
		}
   }
   
   function user_tuijian(){
   		$uid = $this->Session->read('User.uid');
		if(empty($uid)){ $this->jump(ADMIN_URL.'daili.php?act=login',0,'请先登录！'); exit;}
   		$this->title("欢迎进入会员中心".' - 我的推荐 - '.$GLOBALS['LANG']['site_name']);
   		$rt['uid'] = $uid;
		
		//商品分类列表		
/*		$rt['menu'] = $this->action('catalog','get_goods_cate_tree');
*/		
		//分页
		$page = isset($_GET['page']) ? $_GET['page'] : 1;
		if(empty($page)){
			   $page = 1;
		}
		
		$list = 8 ; //每页显示多少个
		$start = ($page-1)*$list; 
		
		$tt = $this->App->findvar("SELECT COUNT(goods_id) FROM `{$this->App->prefix()}goods` WHERE is_new='1'");
		
		$rt['pages'] = Import::basic()->getpage($tt,$list,$page,'?page=',true);
		
		$sql = "SELECT goods_id,goods_img,goods_name,pifa_price,need_jifen FROM `{$this->App->prefix()}goods` WHERE is_new='1' ORDER BY goods_id DESC LIMIT $start,$list";
		$rt['categoodslist'] = $this->App->find($sql);
		
		if(!defined(NAVNAME)) define('NAVNAME', "我的推荐");
   		$this->set('rt',$rt);
		$this->set('page',$page);
   		$this->template('user_tuijian');
   }
   
   function messages(){
   		$uid = $this->Session->read('User.uid');
		if(empty($uid)){ $this->jump(ADMIN_URL.'daili.php?act=login',0,'请先登录！'); exit;}
   		$this->title("欢迎进入会员中心".' - 我的提问 - '.$GLOBALS['LANG']['site_name']);
		
		//分页
		$page = isset($_GET['page']) ? $_GET['page'] : 1;
		if(empty($page)){
			   $page = 1;
		}
		
		$list = 4 ; //每页显示多少个
		$start = ($page-1)*$list; 
		$tt = $this->App->findvar("SELECT COUNT(mes_id) FROM `{$this->App->prefix()}message` WHERE user_id='$uid' AND (goods_id IS NULL OR goods_id='')");
		
		$rt['pages'] = Import::basic()->getpage($tt,$list,$page,'?page=',true);
		
		$sql = "SELECT distinct tb1.*,tb2.avatar,tb2.nickname,tb2.user_name AS dbusername FROM `{$this->App->prefix()}message` AS tb1 LEFT JOIN  `{$this->App->prefix()}user` AS tb2 ON tb1.user_id=tb2.user_id WHERE tb1.user_id='$uid' AND (tb1.goods_id IS NULL OR tb1.goods_id='') ORDER BY tb1.addtime DESC LIMIT $start,$list";
		$rt['meslist'] = $this->App->find($sql);

		if(!defined(NAVNAME)) define('NAVNAME', "我的提问");
		$this->set('rt',$rt);
   		$this->template('user_question');
   }
   
   function xiaofei(){
   		$this->template('user_xiaofei');
   }
   
   function comment(){
   		$uid = $this->Session->read('User.uid');
		if(empty($uid)){ $this->jump(ADMIN_URL.'daili.php?act=login',0,'请先登录！'); exit;}
   		$this->title("欢迎进入会员中心".' - 我的评论 - '.$GLOBALS['LANG']['site_name']);
		
		//分页
		$page = isset($_GET['page']) ? $_GET['page'] : 1;
		if(empty($page)){
			   $page = 1;
		}
		$list = 4 ; //每页显示多少个
			$start = ($page-1)*$list;
			$sql = "SELECT COUNT(comment_id) FROM `{$this->App->prefix()}comment`";
			$sql .=" WHERE parent_id = 0 AND status='1' AND user_id='$uid'";
			$tt = $this->App->findvar($sql);
		
			$rt['goodscommentpage'] = Import::basic()->ajax_page($tt2,$list,$page,'get_mycomment_page_list');
			
			$sql = "SELECT c.*,u.avatar,u.user_name AS dbuname,u.nickname,g.goods_thumb,g.goods_name,g.goods_id FROM `{$this->App->prefix()}comment` AS c LEFT JOIN `{$this->App->prefix()}user` AS u ON c.user_id=u.user_id LEFT JOIN `{$this->App->prefix()}goods` AS g ON g.goods_id = c.id_value";
			 $sql .=" WHERE c.parent_id = 0  AND c.status='1' AND c.user_id='$uid' ORDER BY c.add_time DESC LIMIT $start,$list";
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
							$rt['goodscommentlist'][$cid] = $row;
							$rt['goodscommentlist'][$cid]['rp_comment_list'] = isset($rp_commentlist[$cid]) ? $rp_commentlist[$cid] : array();
					 }
					 unset($commentlist);
			 }else{
					$rt['goodscommentlist'] = array();
			 }
				 
			
			if(isset($_GET['type'])&&$_GET['type']=='ajax'){
				$this->set('rt',$rt);
				echo  $this->fetch('ajax_mycomment',true);
				exit;
			}
			
		//商品分类列表		
		$rt['menu'] = $this->action('catalog','get_goods_cate_tree');
		
		if(!defined(NAVNAME)) define('NAVNAME', "我的评论");
		$this->set('rt',$rt);
   		$this->template('user_mycomment');
   }
   
   function ajax_feedback($data=array()){
   		$err = 0;
		$result = array('error' => $err, 'message' => '');
		$json = Import::json();

		if (empty($data))
		{
				$result['error'] = 2;
				$result['message'] = '传送的数据为空！';
				die($json->encode($result));
		}
		$mesobj = $json->decode($data); //反json ,返回值为对象
		
		//以下字段对应评论的表单页面 一定要一致
		$datas['comment_title'] = $mesobj->comment_title;
		$datas['goods_id'] = $mesobj->goods_id;
		$goods_id = $datas['goods_id'];
		$uid = $this->Session->read('User.uid');
		$datas['user_id'] = !empty($uid) ? $uid : 0;
		$datas['status'] = 2;
		
		if (strlen($datas['comment_title'])<12)
		{
				$result['error'] = 2;
				$result['message'] = '评论内容不能太少！';
				die($json->encode($result));
		}
		
		$datas['addtime'] = mktime();
		$ip = Import::basic()->getip();
		$datas['ip_address'] = $ip ? $ip : '0.0.0.0';
		$datas['ip_from'] = Import::ip()->ipCity($ip);

		if($this->App->insert('message',$datas)){
			$result['error'] = 0;
			$result['message'] ='提问成功！我们会很快回答您的问题！';
		}else{
			$result['error'] = 1;
			$result['message']='提问失败，请通过在线联系客服吧！';
		}
		unset($datas,$data);
		$page = 1;
		$list = 2 ; //每页显示多少个
		$start = ($page-1)*$list; 
		$tt = $this->App->findvar("SELECT COUNT(mes_id) FROM `{$this->App->prefix()}message` WHERE user_id='$uid' AND (goods_id IS NULL OR goods_id='')");
		$rt['notgoodmespage'] = Import::basic()->ajax_page($tt,$list,$page,'get_myquestion_notgoods_page_list');
		$sql = "SELECT distinct tb1.*,tb2.avatar,tb2.nickname,tb2.user_name AS dbusername FROM `{$this->App->prefix()}message` AS tb1 LEFT JOIN  `{$this->App->prefix()}user` AS tb2 ON tb1.user_id=tb2.user_id WHERE tb1.user_id='$uid' AND (tb1.goods_id IS NULL OR tb1.goods_id='') ORDER BY tb1.addtime DESC LIMIT $start,$list";
		$rt['notgoodsmeslist'] = $this->App->find($sql);
		$this->set('rt',$rt);
		$result['error'] = 0;
		$result['message'] =$this->fetch('ajax_userquestion_nogoods',true);
		die($json->encode($result));
   }
   
   //删除提问
   function ajax_delmessages($id=0){
   		if(!($id>0))  die("传送的ID为空！");
		if($this->App->delete('message','mes_id',$id)){
		   echo "";
		}else{
			echo "删除意外出错！";
		}
		exit;
   }
   
    //删除评论
   function ajax_delcomment($id=0){
   		if(!($id>0))  die("传送的ID为空！");
		if($this->App->delete('comment','comment_id',$id)){
		   echo "";
		}else{
			echo "删除意外出错！";
		}
		exit;
   }
   
   //用户积分获取
   function add_user_jifen($type="",$obj=array()){
   		$art = array('buy','comment','tuijian','otherjifen');
		$uid = $this->Session->read('User.uid');
		if(!($uid>0)) return false;
		$rank = $this->Session->read('Agent.rank');
		$sql = "SELECT * FROM `{$this->App->prefix()}user_level` WHERE lid='$rank' LIMIT 1";
		$rtlevel = $this->App->findrow($sql);
		$jfdesc = $rtlevel['jifendesc'];
		$dbjfdesc = array(); //当前会员级别能够得到积分的权限
		if(!empty($jfdesc)){
			$dbjfdesc = explode('+',$jfdesc);
		}
		if(in_array($type,$dbjfdesc)){  //拥有得到积分的权限
			switch($type){
				case 'comment': //参与每件已购商品评论获奖10分，依次类推，参与10件已购商品评论可获奖100个积分（一张订单每个产品只能获得一次积分）。
					$data['time'] = mktime();
					$data['changedesc'] = "评论所得积分！";
					$data['points'] = 10;
					$data['uid'] = $uid;
					if($this->App->insert('user_point_change',$data)){
						return $data;
					}else{
						return false;
					}
					break;
				case 'tuijian': //推荐好友注册获奖50分，好友首次成功购物获奖同倍积分；
					$data['time'] = mktime();
					$data['changedesc'] = "推荐好友注册所得积分！";
					$data['points'] = 50;
					$data['uid'] = $uid;
					if($this->App->insert('user_point_change',$data)){
						return $data;
					}else{
						return false;
					}
					break;
				case 'spendthan1500':  //单次购物达1500元，当次购物获取2倍积分
					$sql = "SELECT goods_amount FROM `{$this->App->prefix()}goods_order_info` WHERE user_id='$uid' AND order_status='2' ORDER BY pay_time DESC LIMIT 1";
					$amount = $this->App->findvar($sql);
					if(intval($amount)>1500){
						$data['time'] = mktime();
						$data['changedesc'] = "本次购物'$amount'元！【单次购物达1500以上所得积分】";
						$data['points'] = $amount*2;
						$data['uid'] = $uid;
						if($this->App->insert('user_point_change',$data)){
							return $data;
						}else{
							return false;
						}
					}elseif(intval($amount)>0){
						$data['time'] = mktime();
						$data['changedesc'] = "本次购物'$amount'元所得积分！";
						$data['points'] = $amount*2;
						$data['uid'] = $uid;
						if($this->App->insert('user_point_change',$data)){
							return $data;
						}else{
							return false;
						}
					}else{
						return false;
					}
					break;
				case 'upuserinfo': //特定时间内，更新正确个人资料，可获奖10个积分； 一个星期之内更新
					$data['time'] = mktime();
					$data['changedesc'] = "更新正确个人资料所得积分！";
					$data['points'] = 10;
					$data['uid'] = $uid;
					if($this->App->insert('user_point_change',$data)){
						return $data;
					}else{
						return false;
					}
					break;
				case 'yearthancount6': //全年购物超过6次，于每年年末奖励100个积分（2010-1-1起开始计算）
					$data['time'] = mktime();
					$data['changedesc'] = "全年购物超过6次所得积分！";
					$data['points'] = 100;
					$data['uid'] = $uid;
					if($this->App->insert('user_point_change',$data)){
						return $data;
					}else{
						return false;
					}
					break;	
				
			}
		}else{
			return false;
		}
		
   }
   
	//更新密码
   function ajax_updatepass($data= array()){
		$json = Import::json();
		$uid = $this->Session->read('User.uid');
		if(empty($uid)){
			$result = array('error' => 3, 'message' => '先您先登录！');
			die($json->encode($result));
		}
		
		$result = array('error' => 2, 'message' => '传送的数据为空！');
		if(empty($data['fromAttr']))  die($json->encode($result));
		
		$fromAttr = $json->decode($data['fromAttr']); //反json ,返回值为对象
		unset($data);
		$newpass = $fromAttr->newpass;
		$rp_pass = $fromAttr->rp_password;
		$datas['password'] = $fromAttr->password;
		if(!empty($newpass)){
			if(empty($datas['password'])){
				$result = array('error' => 2, 'message' => '请输入新密码！');
				die($json->encode($result));
			}
			
			if(!empty($rp_pass)&&$datas['password']==$rp_pass){
				$datas['password'] = md5(trim($datas['password']));
				if(md5($newpass)==$datas['password']){
					$result = array('error' => 2, 'message' => '新密码跟旧密码不能相同！');
					die($json->encode($result));
				}
				
				$newpass = md5(trim($newpass));
				$sql = "SELECT password FROM `{$this->App->prefix()}user` WHERE password='$newpass' AND user_id='$uid'";
				$newrt = $this->App->findvar($sql);
				if(empty($newrt)){
					$result = array('error' => 2, 'message' => '您的原始密码错误！');
					die($json->encode($result));
				}
			
				if($this->App->update('user',$datas,'user_id',$uid)){
					$result = array('error' => 2, 'message' => '密码修改成功！');
					die($json->encode($result));
				}else{
					$result = array('error' => 2, 'message' => '密码修改失败！');
					die($json->encode($result));
				}
			}else{
				$result = array('error' => 2, 'message' => '密码与确认密码不一致！');
				die($json->encode($result));
			}
			
		}else{
			$result = array('error' => 2, 'message' => '请输入原始密码！');
			die($json->encode($result));
		}
		
   }
   
	//判断是否已经登陆
	function is_login(){
		$uid = $this->Session->read('User.uid');
		$username = $this->Session->read('Agent.username');
		if(empty($uid) || empty($username)) {
			return false;
		}else{
		 	return true;
		}
	}
	
	function get_regions($type,$parent_id=0){
		$p = "";
		if(!empty($parent_id)) $p = "AND parent_id='$parent_id'";
		
		$sql= "SELECT region_id,region_name FROM `{$this->App->prefix()}region` WHERE region_type='$type' {$p} ORDER BY region_id ASC";
		return  $this->App->find($sql);
	}
	
	//退出登录
	function logout(){ 
		//session_destroy();
		//
		//if(isset($_COOKIE['user'])){
			//if(is_array($_COOKIE['user'])){
				//foreach($_COOKIE['user'] as $key=>$val){
					 //setcookie("user[".$key."]", "");
					 if(isset($_COOKIE['AGENT']['USERNAME'])) setcookie('AGENT[USERNAME]',"",0); 
					 if(isset($_COOKIE['AGENT']['PASS'])) setcookie('AGENT[PASS]',"",0); 
				//}
			//}
		//}
		$this->Session->write('Agent',null);

		//$url = $this->Session->read('REFERER');
		$url = ADMIN_URL;
		$this->jump($url); exit;
	}
	
	function ajax_getuid(){
		echo $this->Session->read('User.uid');
		exit;
	}
	
	//忘记密码
	function forgetpass(){
		$this->title("找回密码".' - '.$GLOBALS['LANG']['site_name']);
		if(isset($_POST)&&!empty($_POST)){
			$uname = $_POST['uname'];
			if(empty($uname)){
				$this->jump('',0,'请输入您的账号名称！');exit;
			}
			$email = $_POST['email'];
			if(empty($email)){
				$this->jump('',0,'请输入您的原始电子邮箱！');exit;
			}
			$vifcode = $_POST['vifcode'];
			if(empty($vifcode)){
				$this->jump('',0,'请输入您的验证码！');exit;
			}
			$dbvifcode = strtolower($this->Session->read('vifcode'));
			if($vifcode != $dbvifcode){
				$this->jump('',0,'验证码错误！');exit;
			}
			
			$sql = "SELECT user_name FROM `{$this->App->prefix()}user` WHERE user_name='$uname' LIMIT 1";
			$dbuname = $this->App->findvar($sql);
			if(empty($dbuname)){
				$this->jump('',0,'该用户不存在！'); exit;
			}
			$sql = "SELECT user_name FROM `{$this->App->prefix()}user` WHERE user_name= '$uname' AND email='$email' LIMIT 1";
			$dbemail= $this->App->findvar($sql);
			if(empty($dbemail)){
				$this->jump('',0,'无法完成您的请求，您的用户名跟电子邮箱不对应！'); exit;
			}else{
				$this->set('uname',$uname);
				$this->set('email',$email);
				$this->set('is_true',true);
				$this->template('user_forgetpass_result');
				exit;
			}
			
		} // end if
		
		//商品分类列表		
		$rt['menu'] = $this->action('catalog','get_goods_cate_tree');
		
		if(!defined(NAVNAME)) define('NAVNAME', "找回密码");
		$this->set('rt',$rt);
		$this->template('user_forgetpass');
	}
	
	//注册成功提示的页面
	function user_regsuccess_mes(){
		$this->title("注册成功".' - '.$GLOBALS['LANG']['site_name']);
		$this->template('user_regsuccess_mes');
	}
	
	//自动登录
	function auto_login(){
		$uid = $this->Session->read('User.uid');
		if($uid>0){
				$addtime = $this->Session->read('Agent.addtime');
				if( (mktime() - intval($addtime)) > 12*3600){
					if($uid > 0){
						$sql = "SELECT mobile_phone FROM `{$this->App->prefix()}user` WHERE user_id = '$uid' LIMIT 1";
						$uname = $this->App->findvar($sql);
						if(empty($uname)){
							$this->Session->write('Agent',null);
							if(isset($_COOKIE['Agent']['USERNAME'])) setcookie('Agent[USERNAME]',"",0);
							if(isset($_COOKIE['Agent']['PASS'])) setcookie('Agent[PASS]',"",0);
							$this->jump(ADMIN_URL.'daili.php?act=login');exit;
						}
					}
				}
				$username = $this->Session->read('Agent.username');
				$pass = $this->Session->read('Agent.pass');
				if(!empty($username)&&!empty($pass)){
					setcookie('AGENT[USERNAME]', $username, mktime() + 2592000);
					setcookie('AGENT[PASS]', $pass, mktime() + 2592000);
				}
		}else{
				$user = isset($_COOKIE['AGENT']['USERNAME']) ? $_COOKIE['AGENT']['USERNAME'] : "";
				$pass = isset($_COOKIE['AGENT']['PASS']) ? $_COOKIE['AGENT']['PASS'] : "";
				if(!empty($user)&&!empty($pass)){
					$sql = "SELECT password,user_id,user_name,last_login,active,user_rank,mobile_phone FROM `{$this->App->prefix()}user` WHERE mobile_phone='$user' AND user_rank='10' LIMIT 1";
					$rt = $this->App->findrow($sql);
					if(empty($rt)){ 
						$this->Session->write('Agent',null);
						if(isset($_COOKIE['Agent']['USERNAME'])) setcookie('Agent[USERNAME]',"",0);
						if(isset($_COOKIE['Agent']['PASS'])) setcookie('Agent[PASS]',"",0);
						$this->jump(ADMIN_URL.'daili.php?act=login');exit;
					}else{
						if($rt['password']==$pass){
							//登录成功,记录登录信息
							$ip = Import::basic()->getip();
							$datas['last_ip'] = empty($ip) ? '0.0.0.0' : $ip;
							$datas['last_login'] = mktime();
							$datas['visit_count'] = '`visit_count`+1';
							$this->Session->write('Agent.prevtime',$rt['last_login']); //记录上一次的登录时间
							
							$this->App->update('user',$datas,'user_id',$rt['user_id']); //更新
							$this->Session->write('Agent.username',$user);
							$this->Session->write('Agent.pass',$rt['password']);
							$this->Session->write('User.uid',$rt['user_id']);
							$this->Session->write('Agent.active',$rt['active']);
							$this->Session->write('Agent.rank',$rt['user_rank']);
							$this->Session->write('Agent.lasttime',$datas['last_login']);
							$this->Session->write('Agent.lastip',$datas['last_ip']);
							$this->Session->write('Agent.addtime',mktime());
							
							setcookie('AGENT[USERNAME]', $user, mktime() + 2592000);
							setcookie('AGENT[PASS]', $pass, mktime() + 2592000);
							unset($data);
							return true;
						}else{
							$this->Session->write('Agent.username',null);
							$this->Session->write('Agent.pass',null);
							if(isset($_COOKIE['Agent']['USERNAME'])) setcookie('Agent[USERNAME]',"",0);
							if(isset($_COOKIE['Agent']['PASS'])) setcookie('Agent[PASS]',"",0);
							$this->jump(ADMIN_URL.'daili.php?act=login');exit;
						}
					} //end if
				}else{
					//跳转到登陆页面
					$this->jump(ADMIN_URL.'daili.php?act=login');exit;
				}
		}
		return true;
	} //end function 
	
	//ajax登录
	function ajax_user_login($data=array()){
		if(empty($data)) die("请填写完整信息");
		$user = trim(stripcslashes(strip_tags(nl2br($data['username'])))); //过滤
		if(empty($user)) die("请输入用户名");
		$pass = md5(trim($data['password']));
		if(empty($pass)) die("请输入密码");
		$vcode = isset($data['vifcode'])? $data['vifcode'] : ""; 
		if(!empty($vcode)){
			if(strtolower($vcode) != strtolower($this->Session->read('vifcode'))){
				die("验证码错误！");
			}
		}
		$sql = "SELECT password,user_id,last_login,active,user_rank,mobile_phone,wecha_id,user_name FROM `{$this->App->prefix()}user` WHERE mobile_phone='$user' AND active='1' LIMIT 1";
		$rt = $this->App->findrow($sql);
		if(empty($rt)){ die("用户名不存在或者还没审核！");
		}else{
			if($rt['password']==$pass){
				//登录成功,记录登录信息
				$ip = Import::basic()->getip();
				$datas['last_ip'] = empty($ip) ? '0.0.0.0' : $ip;
				$datas['last_login'] = mktime();
				$datas['visit_count'] = '`visit_count`+1';
				$this->Session->write('Agent.prevtime',$rt['last_login']); //记录上一次的登录时间
				
				$this->App->update('user',$datas,'user_id',$rt['user_id']); //更新
				$this->Session->write('User.username',$rt['user_name']);
				
				$this->Session->write('User.uid',$rt['user_id']);
				$this->Session->write('User.active','1');
				$this->Session->write('User.rank',$rt['user_rank']);
				$this->Session->write('User.ukey',$rt['wecha_id']);
				$this->Session->write('User.addtime',mktime());
				//写入cookie
				setcookie(CFGH.'USER[UKEY]', $rt['wecha_id'], mktime() + 2592000);
				setcookie(CFGH.'USER[UID]', $rt['user_id'], mktime() + 2592000);
				
				unset($data);
				
			}else{
				//密码是错误的
				die("密码错误");
			}
		}
		
	}
	
	//ajax注册
	function ajax_user_register($data= array()){
		$json = Import::json();
		$result = array('error' => 2, 'message' => '传送的数据为空!');
		if(empty($data['fromAttr']))  die($json->encode($result));
		
		$fromAttr = $json->decode($data['fromAttr']); //反json ,返回值为对象
		unset($data);
		
		$uid = $this->Session->read('User.uid');
		$wecha_id = $this->Session->read('User.wecha_id');
		if(!($uid>0)){
			$sql = "SELECT user_id FROM `{$this->App->prefix()}user` WHERE wecha_id ='$wecha_id' LIMIT 1";
			$uid = $this->App->findvar($sql);
			if(!($uid > 0)){
				die("无法登录代理用户，麻烦通知我们管理员，谢谢");
			}
		}
		//以下字段对应评论的表单页面 一定要一致
		$datas['user_rank'] = $fromAttr->user_rank; //用户级别
		$datas['mobile_phone'] = $fromAttr->username; //用户名
		if(empty($datas['mobile_phone'])){
				$result = array('error' => 2, 'message' => '请输入手机号码作为登录帐号！');
				if(empty($data['fromAttr']))  die($json->encode($result));
		}
		if( preg_match("/1[3458]{1}\d{9}$/",$datas['mobile_phone']) ){}else{
				$result = array('error' => 2, 'message' => '手机号码不合法，请重新输入！');
				if(empty($data['fromAttr']))  die($json->encode($result));
		}
		//检查该手机是否已经使用了
		$mobile_phone = $datas['mobile_phone'];
		$sql = "SELECT user_id FROM `{$this->App->prefix()}user` WHERE user_id !='$uid' AND mobile_phone='$mobile_phone'";
		$uuid = $this->App->findvar($sql);
		if( $uuid > 0){
				$result = array('error' => 2, 'message' => '抱歉，该手机号码已经被使用了！');
				if(empty($data['fromAttr']))  die($json->encode($result));
		}
		
		$datas['password'] = $fromAttr->password;
		if(empty($datas['password'])){
				$result = array('error' => 2, 'message' => '用户密码不能为空！');
				if(empty($data['fromAttr']))  die($json->encode($result));
		}
		$rp_pass = $fromAttr->rp_pass;
		if($rp_pass != $datas['password']){
				$result = array('error' => 2, 'message' => '两次密码不相同！');
				if(empty($data['fromAttr']))  die($json->encode($result));
		}
		$datas['password'] = md5($datas['password']);
		
		/*if(!($datas['user_rank']>0)) */$datas['user_rank'] = 10;
/*		$yyy = $fromAttr->yyy;
		$mmm = $fromAttr->mmm;
		$ddd = $fromAttr->ddd;
		$datas['birthday'] = $yyy.'-'.$mmm.'-'.$ddd;
		$datas['sex'] = $fromAttr->sex;*/
		
		//$regcode = $fromAttr->regcode;
		/*$regcode = '';
		if(!empty($regcode)){
			//检查该注册码是否有效
			$sql = "SELECT tb1.bonus_id FROM `{$this->App->prefix()}user_coupon_list` AS tb1 LEFT JOIN `{$this->App->prefix()}user_coupon_type` AS tb2 ON tb1.type_id = tb2.type_id WHERE tb1.bonus_sn='$regcode' AND tb1.is_used='0' LIMIT 1";
			$uuid = $this->App->findvar($sql);
			if($uuid > 0){
			
			}else{
				$result = array('error' => 2, 'message' => '请检查该注册码是否有效!');
				die($json->encode($result));
			}
		 }
		
		$emails = '';
		if(!empty($emails)){
			$sql = "SELECT email FROM `{$this->App->prefix()}user` WHERE email='$emails' AND user_rank='10'";
			$dbemail = $this->App->findvar($sql);
			if(!empty($dbemail)){
				$result = array('error' => 2, 'message' => '该电子邮箱已经被使用了!');
				die($json->encode($result));
			}
		}*/
		$ip = Import::basic()->getip();
		$reg_ip = $ip ? $ip : '0.0.0.0';
		//$datas['reg_time'] = mktime();
		//$datas['reg_from'] = Import::ip()->ipCity($ip);
		//$datas['last_login'] = mktime();
		$datas['last_ip'] = $reg_ip;
		$datas['active'] = 0;
		if($this->App->update('user',$datas,'user_id',$uid)){
			/*$this->Session->write('Agent.username',$uname);
			$this->Session->write('User.uid',$uid);
			$this->Session->write('Agent.active',$datas['active']);
			$this->Session->write('Agent.rank','10');
			$this->Session->write('Agent.lasttime',$datas['last_login']);
			$this->Session->write('Agent.lastip',$datas['last_ip']);
			
			//注册码表
			if(!empty($regcode)){
			 	$this->App->insert('user_regcode',array('code'=>$regcode,'uid'=>$uid,'addtime'=>mktime()));
				$this->App->update('user_coupon_list',array('is_used'=>'1','user_id'=>$uid,'used_time'=>mktime()),'bonus_sn',$regcode);
			}*/
			
			$result = array('error' => 0, 'message' => '登记成功，正等待管理员审核!');
			unset($datas,$datass);
		}else{
			$result = array('error' => 2, 'message' => '登记失败!');
		}
		die($json->encode($result));
					
	}
	//ajax删除用户收货地址
	function ajax_delress($id=0){
		$uid = $this->Session->read('User.uid');
		if(empty($uid)) die("请您先登录！");
		if(empty($id)) die("非法删除！");
		
		if($this->App->delete('user_address','address_id',$id)){
		}else{
			die("删除失败!");
		}
	}
	//设置为默认收货地址
	/*function ajax_setaddress($data=array()){
		$uid = $this->Session->read('User.uid');
		if(empty($uid)) die("请您先登录！");
		$id = isset($data['id'])?intval($data['id']):0;
		$val = isset($data['val'])?$data['val']:0;
		if($id>0){
			$sql = "UPDATE `{$this->App->prefix()}user_address` SET type='0' WHERE user_id='$uid'";
			$this->App->query($sql);
			$sql = "UPDATE `{$this->App->prefix()}user_address` SET type='$val' WHERE user_id='$uid' AND address_id='$id'";
			if($this->App->query($sql)){
				die("");
			}else{
				die("设置失败！");
			}
		}else{
			die("传送ID为空！");
		}
	}*/
	
	function ajax_updateinfo($data= array()){
		$json = Import::json();
		$uid = $this->Session->read('User.uid');
		if(empty($uid)){
			$result = array('error' => 3, 'message' => '先您先登录!');
			die($json->encode($result));
		}
		
		$result = array('error' => 2, 'message' => '传送的数据为空!');
		if(empty($data['fromAttr']))  die($json->encode($result));
		
		$fromAttr = $json->decode($data['fromAttr']); //反json ,返回值为对象
		unset($data);
		
		//
		/*$emails = $fromAttr->email;
		if(!empty($emails)){
			$sql = "SELECT email FROM `{$this->App->prefix()}user` WHERE email='$emails' LIMIT 1";
			$dbemail = $this->App->findvar($sql);
			if(!empty($dbname)&&dbemail !=$emails){
				$result = array('error' => 4, 'message' => '不能更改这个电子邮箱,已经被使用!');
				die($json->encode($result));
			}
		}*/
		//$datas['sex'] = $fromAttr->sex;
		//$datas['email'] = $emails;
		//$datas['birthday'] = ($fromAttr->yes).'-'.($fromAttr->mouth).'-'.($fromAttr->day);
		//$datas['avatar'] = $fromAttr->avatar; //身份证
		//$datas['nickname'] = $fromAttr->nickname;
		///$datas['qq'] = $fromAttr->qq;
		//$datas['office_phone'] = $fromAttr->office_phone;
		
		//检查当前用户是否购买（已经开通分销）
		$uk = $this->App->findvar("SELECT user_rank FROM `{$this->App->prefix()}user` WHERE user_id = '$uid' LIMIT 1");
		if($uk=='1'){
			$datas['user_rank'] = '1';
			$result = array('error' => 4, 'message' => '您必须购买后才能开通店铺哦！请联系商城。');
			die($json->encode($result));
		}
		$datas['is_salesmen'] = '1';
		$datas['mobile_phone'] = $fromAttr->mobile_phone;
		$datas['question'] = $fromAttr->question; //店铺名称
		$datas['answer'] = $fromAttr->answer;
		if(empty($datas['question'])){
			$result = array('error' => 4, 'message' => '店铺名称必须填写！');
			die($json->encode($result));
		}
		if(empty($datas['mobile_phone'])){
			$result = array('error' => 4, 'message' => '手机必须填写！');
			die($json->encode($result));
		}
		if(empty($datas['answer'])){
			$result = array('error' => 4, 'message' => '姓名必须填写！');
			die($json->encode($result));
		}

		if($this->App->update('user',$datas,'user_id',$uid)){
				//unset($datas);
		}
				
		$sql = "SELECT id FROM `{$this->App->prefix()}udaili_siteset` WHERE uid='$uid' LIMIT 1";
		$id = $this->App->findvar($sql);
		
		$data = array();
		$data['uid'] = $uid;
		$data['sitename'] = $datas['question'];
		$data['sitetitle'] = $datas['question'];

		if($id > 0){
			$this->App->update('udaili_siteset',$data,'id',$id);
		}else{
			$this->App->insert('udaili_siteset',$data);
		}
		unset($datas,$datas);
		$this->action('common','get_daili_info','true');
		$result = array('error' => 0, 'message' => '您的信息已经提交，审核通过后您将获得代理权限!');
		die($json->encode($result));
		
   }
	
   function ajax_get_ress($data=array()){
   		$type = $data['type'];
		$parent_id = $data['parent_id'];
		if(empty($type)||empty($parent_id)){
			exit;
		}
		$sql= "SELECT region_id,region_name FROM `{$this->App->prefix()}region` WHERE region_type='$type' AND parent_id='$parent_id' ORDER BY region_id ASC";
		$rt = $this->App->find($sql);
		if(!empty($rt)){
			if($type==2){
				$str = '<option value="0">选择城市</option>';
			}else if($type==3){
				$str = '<option value="0">选择区</option>';
			}
				
			foreach($rt as $row){
			$str .='<option value="'.$row['region_id'].'">'.$row['region_name'].'</option>'."\n";
			}
			die($str);
		}
		
   }
   
   function ajax_get_ge_peisong($data=array()){
		$district_id = $data['district_id'];
		if(empty($district_id)){
			exit;
		}
		
		$sql = "SELECT tb1.user_id,tb2.nickname,tb1.consignee FROM `{$this->App->prefix()}user_address` AS tb1 LEFT JOIN `{$this->App->prefix()}user` AS tb2 ON tb1.user_id=tb2.user_id WHERE tb1.district='$district_id' AND tb1.is_own='1' AND tb2.user_rank='12'";
		$rt = $this->App->find($sql);
		if(empty($rt)){
			$sql = "SELECT tb1.user_id,tb3.nickname,tb1.consignee FROM `{$this->App->prefix()}user_address` AS tb1 LEFT JOIN `{$this->App->prefix()}region` AS tb2 ON tb1.district = tb2.region_id LEFT JOIN `{$this->App->prefix()}user` AS tb3 ON tb1.user_id = tb3.user_id WHERE tb2.parent_id='$district_id' AND tb1.is_own='1' AND tb3.user_rank='12'";
			$rt = $this->App->find($sql);
			if(empty($rt)){
				$sql = "SELECT tb1.user_id,tb2.nickname,tb1.consignee FROM `{$this->App->prefix()}user_address` AS tb1 LEFT JOIN `{$this->App->prefix()}user` AS tb2 ON tb1.user_id=tb2.user_id WHERE tb1.is_own='1' AND tb2.user_rank='12'";
				$rt = $this->App->find($sql);
			}
		}

		if(!empty($rt)){
			$str = '<option value="0">选择配送店</option>';	
			foreach($rt as $row){
			$str .='<option value="'.$row['user_id'].'">'.(!empty($row['nickname'])?$row['nickname']:$row['consignee'].'配送店').'</option>'."\n";
			}
			die($str);
		}
   }
   
   function ajax_ressinfoop($data=array()){
   			$uid = $this->Session->read('User.uid');
					
   			if(isset($data['attrbul'])&&!empty($data['attrbul'])){
				$err = 0;
				$result = array('error' => $err, 'message' => '');
				$json = Import::json();
				
				$attrbul = $json->decode($data['attrbul']); //反json
				if(empty($attrbul)){
					$result['error'] = 1;
					$result['message'] = "传送的数据为空！";
					die($json->encode($result));
				}
					
				$id = $attrbul->id;
				$dd = array();
				$type = $attrbul->type; 
				$dd['user_id'] = $uid;
				$dd['consignee'] = $attrbul->consignee;
				if(empty($dd['consignee'])){
					$result['error'] = 1;
					$result['message'] = "收货人姓名不能为空！";
					die($json->encode($result));
				}
				$dd['country'] = 1;
				$dd['province'] = $attrbul->province;
				$dd['city'] = $attrbul->city; 
				$dd['district'] = $attrbul->district;
				$dd['address'] = $attrbul->address;
				/*$dd['shoppingname'] = $attrbul->shoppingname;
				$dd['shoppingtime'] = $attrbul->shoppingtime;*/
				if(empty($dd['province']) || empty($dd['city']) || empty($dd['district']) ||empty($dd['address'])){
					$result['error'] = 1;
					$result['message'] = "收货地址不能为空！";
					die($json->encode($result));
				}
				$dd['sex'] = $attrbul->sex; 
				$dd['email'] = $attrbul->email; 
				$dd['zipcode'] = $attrbul->zipcode;
				$dd['mobile'] = $attrbul->mobile;
				$dd['tel'] = $attrbul->tel; 
				if(empty($dd['mobile']) && empty($dd['tel'])){
					$result['error'] = 1;
					$result['message'] = "电话或者手机必须填写一个！";
					die($json->encode($result));
				}
				$dd['is_default'] = '1';
					
				if(!($id>0)&&$type=='add'){ //添加
					$this->App->update('user_address',array('is_default'=>'0'),'user_id',$uid);
					$this->App->insert('user_address',$dd);
					
				}elseif($type=='update'){ //编辑
					$this->App->update('user_address',$dd,'address_id',$id);
				}
				unset($dd);
				if(empty($dd['mobile']) && empty($dd['tel'])){
					$result['error'] = 0;
					$result['message'] = "操作成功！";
					die($json->encode($result));
				}
				exit;
			}
			
   			$id = $data['id'];
			$type = $data['type'];
            if(!empty($id) && !empty($type)){
                switch($type){
                        case 'delete': //删除收货地址
                                $this->App->delete('user_address','address_id',$id);
                                break;
                        case 'setdefaut':  //设为默认收货地址
                                if(!empty($uid)){
                                $this->App->update('user_address',array('is_default'=>'0'),'user_id',$uid);
								 $this->App->update('user_address',array('is_default'=>'1'),'address_id',$id);
                                }
                               
                                break;
                        case 'quxiao': //取消收货地址
                                $this->App->update('user_address',array('is_default'=>'0'),'address_id',$id);
                                break;
						case 'showupdate':
								//当前用户的收货地址
								$sql = "SELECT * FROM `{$this->App->prefix()}user_address` WHERE user_id='$uid' AND address_id='$id'";
								$rt['userress'] = $this->App->findrow($sql);
								$rt['province'] = $this->get_regions(1);  //获取省列表
								$rt['city'] = $this->get_regions(2,$rt['userress']['province']);  //城市
								$rt['district'] = $this->get_regions(3,$rt['userress']['city']);  //区
		
							   	$this->set('rt',$rt);
								$con= $this->fetch('ajax_show_updateressbox',true);
								die($con);
                                break;
                }
            }
   }
   
  //订单的状态
   function get_status($oid=0,$pid=0,$sid=0){ //分别为：订单 支付 发货状态
            $str = '';
            switch($oid){
                case '0':
                    $str .= '未确认,';
                    break;
                case '1':
                    $str .= '<font color="red">取消</font>,';
                    break;
                case '2':
                    $str .= '确认,';
                    break;
                case '3':
                    $str .= '<font color="red">退货</font>,';
                    break;
                case '4':
                    $str .= '<font color="red">无效</font>,';
                    break;
            }

           switch($pid){
                case '0':
                    $str .= '未付款,';
                    break;
                case '1':
                    $str .= '已付款,';
                    break;
                case '2':
                    $str .= '已退款,';
                    break;
            }

            switch($sid){
                case '0':
                    $str .= '未发货';
                    break;
                case '1':
                    $str .= '配货中';
                    break;
                case '2':
                    $str .= '已发货';
                    break;
                case '3':
                    $str .= '部分发货';
                    break;
                case '4':
                    $str .= '退货';
                    break;
                case '5':
                    $str .= '已收货';
                    break;
            }
            return $str;
  }

  
  function get_option($sn=0,$oid=0,$pid=0,$sid=0){
  			if(empty($sn)) return "";
  		    $str = '';
			switch($sid){
                case '2':
                    return $str = '<a href="javascript:;" name="confirm" id="'.$sn.'" class="oporder"><font color="red">确认收货</font><a>';
                    break;
                case '5':
                    return $str = '<font color="red">已完成</font>';
                    break;
            }
			
            switch($oid){
                case '0':
                    $str = '<a href="javascript:;" name="cancel_order" id="'.$sn.'" class="oporder"><font color="red">取消订单</font></a>';
                    break;
                case '1':
                    $str = '<font color="red">已取消</font>';
                    break;
                case '2':
                    $str = '<font color="red">已确认</font>';
                    break;
                case '3':
                    $str = '<font color="red">已退货</font>';
                    break;
                case '4':
                    $str = '<font color="red">无效订单</font>';
                    break;
            }
         
            return $str;
  }
  
########################################	
	 /*
     * 自定义大小验证码函数
     * @$num:字符数
     * @$size:大小
     * @$width,$height:不设置会自动
     */
    function vCode($num=4,$size=18, $width=0,$height=0){
        !$width && $width = $num*$size*4/5-2;
        !$height && $height = $size + 8;
        // 去掉了 0 1 O l 等
            $str = "23456789abcdefghijkmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVW";
            $code = '';
            for ($i=0; $i<$num; $i++){
                    $code.= $str[mt_rand(0, strlen($str)-1)];
            }
			//写入session
			$this->Session->write('vifcode',$code);
            // 画图像
            $im = imagecreatetruecolor($width,$height);
            // 定义要用到的颜色
            $back_color = imagecolorallocate($im, 235, 236, 237);
            $boer_color = imagecolorallocate($im, 118, 151, 199);
            $text_color = imagecolorallocate($im, mt_rand(0,200), mt_rand(0,120), mt_rand(0,120));

            // 画背景
            imagefilledrectangle($im,0,0,$width,$height,$back_color);
            // 画边框
            imagerectangle($im,0,0,$width-1,$height-1,$boer_color);
            // 画干扰线
            for($i=0;$i<5;$i++){
                $font_color = imagecolorallocate($im, mt_rand(0,255), mt_rand(0,255), mt_rand(0,255));
                imagearc($im,mt_rand(-$width,$width),mt_rand(-$height,$height),mt_rand(30,$width*2),mt_rand(20,$height*2),mt_rand(0,360),mt_rand(0,360),$font_color);
            }
        // 画干扰点
        for($i=0;$i<50;$i++){
                $font_color = imagecolorallocate($im, mt_rand(0,255), mt_rand(0,255), mt_rand(0,255));
                imagesetpixel($im,mt_rand(0,$width),mt_rand(0,$height),$font_color);
        }
		//echo $this->Session->read('vifcode');
        // 画验证码
        @imagefttext($im, $size , 0, 5, $size+3, $text_color, SYS_PATH.'data/monofont.ttf',$code);
        header("Cache-Control: max-age=1, s-maxage=1, no-cache, must-revalidate");
        header("Content-type: image/png");
        imagepng($im);
        imagedestroy($im);
    }
	
	//推广二维码
	function myerweima(){
		$uid = $this->Session->read('User.uid');
		$wecha_id = $this->App->findvar("SELECT `wecha_id` FROM `{$this->App->prefix()}user` WHERE `user_id`=".$uid);
		
	}

	function return_daili_uid($uid=0,$k=0){
		if(!($uid > 0)){
			return 0;
		}
		$puid = 0;
		for($i=0;$i<20;$i++){
				$sql = "SELECT parent_uid FROM `{$this->App->prefix()}user_tuijian` WHERE uid = '$uid' LIMIT 1";
				$p = $this->App->findvar($sql);
				if($p > 0){
					$sql = "SELECT user_rank FROM `{$this->App->prefix()}user` WHERE user_id = '$p' LIMIT 1";
					$rank = $this->App->findvar($sql);
					if($rank != 1){
						$puid = $p;
						break;
					}else{
						$uid = $p;
					}
				}
		}
		return $puid;
	}
	
	function https_request($url, $data = null){
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
		if (!empty($data)){
			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
		}
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$output = curl_exec($curl);
		curl_close($curl);
		return $output;
	}	

}
?>
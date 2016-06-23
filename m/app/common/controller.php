<?php
class CommonController extends Controller{

 	function  __construct() {
		
	}
	
	function index(){
		$this->show404tpl();
	}
	
	function show404tpl(){
		header("HTTP/1.0 404 Not Found");
		$this->layout('kong');
		$this->title('页面无法找到');
		$this->template('404');
		exit;
	}
	
	function mark_phpqrcode($filename="",$thisurl=''){
		$uid = $this->Session->read('User.uid');
		if(empty($filename)) $filename = SYS_PATH_PHOTOS.'qcody'.DS.$uid.DS.$filename;
		
		include(SYS_PATH.'inc/phpqrcode.php');
		
		// 二维码数据
		$issubscribe = 0;
		if(empty($thisurl)){
			if($uid > 0){
				$sql = "SELECT is_subscribe FROM `{$this->App->prefix()}user` WHERE user_id = '$uid' LIMIT 1";
				$issubscribe = $this->App->findvar($sql);
			}
			if($issubscribe=='0'){
				$to_wecha_id = $this->get_user_parent_uid();
				$thisurl = ADMIN_URL."?toid=".$to_wecha_id."&tid=".$uid;
			}else{
				$thisurl = ADMIN_URL."?tid=".$uid;
			}
		}
		
		// 生成的文件名
		Import::fileop()->checkDir($filename);
		
		// 纠错级别：L、M、Q、H
		$errorCorrectionLevel = 'L';
		// 点的大小：1到10
		$matrixPointSize = 6;
		QRcode::png($thisurl, $filename, $errorCorrectionLevel, $matrixPointSize, 2);
	}
	
	//
	function remove_get_arg($thisurl=''){
		 $rrr = explode('?',$thisurl);
		 $t2 = isset($rrr[1])&&!empty($rrr[1]) ? $rrr[1] : "";
		 $dd = array();
		 if(!empty($t2)){
			$rr2 = explode('&',$t2);
			if(!empty($rr2))foreach($rr2 as $v){
				$rr2 = explode('=',$v);
				if($rr2[0]=='from' || $rr2[0]=='isappinstalled'|| $rr2[0]=='code'|| $rr2[0]=='state') continue;
				$dd[] = $v;
			}
		 }
		 if(!empty($dd)){
		 	 $thisurl = $rrr[0].'?'.implode('&',$dd);
			 unset($dd);
		 }
		
		 return $thisurl;
	}
	
	//获取appid、appsecret
	function _get_appid_appsecret(){
			$t = Common::_return_px();
			$cache = Import::ajincache();
			$cache->SetFunction(__FUNCTION__);
			$cache->SetMode('sitemes'.$t);
			$fn = $cache->fpath(func_get_args());
			if(file_exists($fn)&& (mktime() - filemtime($fn) < 7000) && !$cache->GetClose()){
				    include($fn);
			}
			else
		    {
					$sql = "SELECT appid,appsecret,is_oauth,winxintype FROM `{$this->App->prefix()}wxuserset` ORDER BY id DESC LIMIT 1";
					$rr = $this->App->findrow($sql);
					$rt['appid'] = $rr['appid'];
					$rt['appsecret'] = $rr['appsecret'];
					$rt['is_oauth'] = $rr['is_oauth'];
					$rt['winxintype'] = $rr['winxintype'];
					
					$cache->write($fn, $rt,'rt');
		   }
		   return $rt;
	}
	
	
	//获取access_token
	function _get_access_token(){
			$t = Common::_return_px();
			$cache = Import::ajincache();
			$cache->SetFunction(__FUNCTION__);
			$cache->SetMode('sitemes'.$t);
			$fn = $cache->fpath(func_get_args());
			if(file_exists($fn)&& (mktime() - filemtime($fn) < 7000) && !$cache->GetClose()){
				    include($fn);
			}
			else
		    {
					$rr = $this->_get_appid_appsecret();
					$url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$rr['appid'].'&secret='.$rr['appsecret'];
					$con = $this->curlGet($url);
					$json=json_decode($con);
					$rt = $json->access_token; //获取 access_token
					
					$cache->write($fn, $rt,'rt');
		   }
		   return $rt;
	}
	
	//获取jsapi_ticket
	function _get_jsapi_ticket(){
			$rt = $this->_get_appid_appsecret();
			if( is_weixin()==false || $rt['is_oauth']=='0'){
				return array();
			}

			$t = Common::_return_px();
			$cache = Import::ajincache();
			$cache->SetFunction(__FUNCTION__);
			$cache->SetMode('sitemes'.$t);
			$fn = $cache->fpath(func_get_args());
			if(file_exists($fn)&& (mktime() - filemtime($fn) < 7000) && !$cache->GetClose()){
				    include($fn);
			}
			else
		    {
					$access_token = $this->_get_access_token();
					$url = 'https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token='.$access_token.'&type=jsapi';
					$con = $this->curlGet($url);
					$json=json_decode($con);
					$ticket = $json->ticket; //获取 access_token
					$rr = $this->_get_appid_appsecret();
					$rt['jsapi_ticket'] = $ticket;
					$rt['appid'] = $rr['appid'];
					$cache->write($fn, $rt,'rt');
		   }
		  
		   return $rt;
	}
	
	function get_share_user_info(){
		$issubscribe = $this->Session->read('User.subscribe'); //是否关注
		if(empty($issubscribe)) $issubscribe = isset($_COOKIE[CFGH.'USER']['SUBSCRIBE']) ? $_COOKIE[CFGH.'USER']['SUBSCRIBE'] : '0';
		
		$openid = $this->Session->read('User.wecha_id');
		if(empty($openid)) $openid = isset($_COOKIE[CFGH.'USER']['UKEY']) ? $_COOKIE[CFGH.'USER']['UKEY'] : '';
		
		$issubscribe = $this->App->findvar("SELECT is_subscribe FROM `{$this->App->prefix()}user` WHERE wecha_id='$openid' LIMIT 1");
		if($issubscribe!='1' && !empty($openid)){ //获取分享者信息
			$sql = "SELECT tb1.nickname,tb1.headimgurl FROM `{$this->App->prefix()}user` AS tb1 LEFT JOIN `{$this->App->prefix()}user_tuijian` AS tb2 ON tb1.user_id = tb2.parent_uid LEFT JOIN `{$this->App->prefix()}user` AS tb3 ON tb3.user_id = tb2.uid WHERE tb3.wecha_id='$openid' LIMIT 1";
			$rt = $this->App->findrow($sql);
			if(empty($rt)){
			 	$rt['nickname'] = '[官网]';
				$rt['headimgurl'] = ADMIN_URL.'images/uclicon.jpg';
			}
			$rt['is_subscribe'] = '0';
			
			return $rt;
		}else{
			if($issubscribe=='1'){
				//清空来路
				$to_wecha_id = $this->Session->read('User.to_wecha_id');
				if(!empty($to_wecha_id)){
					$this->Session->write('User.to_wecha_id',null);
				}
				$to_wecha_id = isset($_COOKIE[CFGH.'USER']['TOOPENID']) ? $_COOKIE[CFGH.'USER']['TOOPENID'] : "0";
				if(!empty($to_wecha_id)){
					setcookie(CFGH.'USER[TOOPENID]', '', mktime() - 2592000);
				}
				
				//写入关注
				$issubscribe = $this->Session->read('User.subscribe'); //是否关注
				if($issubscribe!='1'){
					$this->Session->write('User.subscribe',$issubscribe);
				}
				$issubscribe = isset($_COOKIE[CFGH.'USER']['SUBSCRIBE']) ? $_COOKIE[CFGH.'USER']['SUBSCRIBE'] : '0';
				if($issubscribe!='1'){
					setcookie(CFGH.'USER[SUBSCRIBE]', $issubscribe, mktime() + 2592000);
				}
			}
			return array();
		}
	}
	
	function get_user_info(){
		$t = Common::_return_px();
		$cache = Import::ajincache();
		$cache->SetFunction(__FUNCTION__);
		$cache->SetMode('user'.$t);
		$uid = $this->Session->read('User.uid');
		$fn = $cache->fpath(array('0'=>$uid));
		if(file_exists($fn)&&!$cache->GetClose()){
			include($fn);
		}
		else
		{
			$sql = "SELECT * FROM `{$this->App->prefix()}user` WHERE user_id = '$uid' LIMIT 1";
			$rt = $this->App->findrow($sql);
			$rank = '1';
			if(!empty($rt)){
				$rank = $rt['user_rank'];
			}
			
			if($rank=='1'){
				$rt = array();
			}
			$cache->write($fn, $rt,'rt');
		}
		return $rt;
	}
	
	function get_user_wecha_id_info($wecha_id='',$ts='0'){
		if(empty($wecha_id)) return array();
		
		$t = Common::_return_px();
		$cache = Import::ajincache();
		$cache->SetFunction(__FUNCTION__);
		$cache->SetMode('sitemes'.$t);
		$fn = $cache->fpath(array('0'=>$wecha_id));
		if(file_exists($fn)&& (mktime() - filemtime($fn) < 7000) && !$cache->GetClose() && $ts=='0'){
				include($fn);
		}
		else
		{
				$sql = "SELECT * FROM `{$this->App->prefix()}user` WHERE wecha_id = '$wecha_id' LIMIT 1";
				$rt = $this->App->findrow($sql);
				$cache->write($fn, $rt,'rt');
	   }
	   return $rt;
	}
	
	//获取当前代理信息
	function get_daili_info($ts='false'){
		$t = Common::_return_px();
		$cache = Import::ajincache();
		$cache->SetFunction(__FUNCTION__);
		$cache->SetMode('daili'.$t);
		$uid = $this->Session->read('User.uid');
		$fn = $cache->fpath(array('0'=>$uid));
		if(file_exists($fn)&&!$cache->GetClose()&&$ts=='false'){
			include($fn);
		}
		else
		{
			//求出当前用户的推荐用户的代理信息
			$sql = "SELECT tb1.share_uid,tb2.user_rank FROM `{$this->App->prefix()}user_tuijian` AS tb1 LEFT JOIN `{$this->App->prefix()}user` AS tb2 ON tb2.user_id = tb1.uid WHERE tb1.uid = '$uid' AND tb2.user_rank!='1' LIMIT 1";
			//$sql = "SELECT tb1.user_rank,tb2.share_uid FROM `{$this->App->prefix()}user` AS tb1 LEFT JOIN `{$this->App->prefix()}user_tuijian` AS tb2 ON tb2.daili_uid = tb1.user_id WHERE tb1.user_rank!='1 AND tb2.uid = '$uid'  LIMIT 1";
			$rts = $this->App->findrow($sql);
			$rank = '1';
			if(!empty($rts)){
				$rank = $rts['user_rank'];
				$pid = $rts['share_uid']; //分享的ID
			}
			if($rank!='1'){
					if($pid > 0) $uid = $pid;
			}
			//查抄代理信息
			$sql = "SELECT * FROM `{$this->App->prefix()}udaili_siteset` WHERE uid='$uid' LIMIT 1";
			$rt = $this->App->findrow($sql);
			if($rank!='1'){
                   $rt['rank'] = $rank;
			}
			$cache->write($fn, $rt,'rt');
		}
		return $rt;
	}
	
	//获取授权code
	function get_user_code(){
		
		$toid = isset($_GET['toid']) ? intval($_GET['toid']) : '0';  //这个是关注后转发的用户ID
		$tid = isset($_GET['tid']) ? intval($_GET['tid']) : '0'; //用户入来的id
		if($tid > 0){
			setcookie(CFGH.'USER[TID]', $tid, mktime() + 2592000);
			$this->Session->write('User.tid',$tid);
		}
		if($toid > 0){
			$this->Session->write('User.to_wecha_id',$toid); //来源ID
			setcookie(CFGH.'USER[TOOPENID]', $toid, mktime() + 2592000);
		}
				
		$this->Session->write('User.codetime',mktime());
		setcookie(CFGH.'USER[CODETIME]', mktime(), mktime() + 2592000);
		
		$thisurl = Import::basic()->thisurl();
		$this->Session->write('User.url',$thisurl); //记录当前进入连接
		setcookie(CFGH.'USER[URL]', $thisurl, mktime() + 2592000);
		
		$rr = $this->_get_appid_appsecret();
		$appid = $rr['appid'];
		$appsecret = $rr['appsecret'];
		
		$thisurl = Import::basic()->thisurl();
		$thisurl = $this->remove_get_arg($thisurl);
 
		$url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$appid.'&redirect_uri='.urlencode($thisurl).'&response_type=code&scope=snsapi_base&state=STATE#wechat_redirect';
		$this->jump($url);exit; //返回带code的URL
	}
	
	function get_user_parent_uid(){
		$uid = $this->Session->read('User.uid');
		if(!($uid > 0)) return 0;
		
		$t = Common::_return_px();
		$cache = Import::ajincache();
		$cache->SetFunction(__FUNCTION__);
		$cache->SetMode('sitemes'.$t);
		$fn = $cache->fpath(array('0'=>$uid));
		if(file_exists($fn)&& (mktime() - filemtime($fn) < 7000) && !$cache->GetClose()){
				include($fn);
		}
		else
		{
				$sql = "SELECT parent_uid FROM `{$this->App->prefix()}user_tuijian` WHERE uid = '$uid' LIMIT 1";
				$rt = $this->App->findvar($sql);
				$cache->write($fn, $rt,'rt');
	   }
	   return $rt;
	}
	
	//检测跳转
	function checkjump(){
		$uid = $this->Session->read('User.uid'); //普通用户
		if(!($uid>0)){
			$uid = isset($_COOKIE[CFGH.'USER']['UID']) ? $_COOKIE[CFGH.'USER']['UID'] : '0';
		}
		$wecha_id = $this->Session->read('User.wecha_id'); //用户openid
		
		$to_wecha_id = $this->Session->read('User.to_wecha_id'); //来源ID
		if(!($to_wecha_id>0)) $to_wecha_id = isset($_COOKIE[CFGH.'USER']['TOOPENID']) ? $_COOKIE[CFGH.'USER']['TOOPENID'] : "0";

		$issubscribe = $this->Session->read('User.subscribe'); //是否关注
		if(empty($issubscribe)) $issubscribe = isset($_COOKIE[CFGH.'USER']['SUBSCRIBE']) ? $_COOKIE[CFGH.'USER']['SUBSCRIBE'] : '0';
		
		$thisurl = Import::basic()->thisurl();
		$thisurl = $this->remove_get_arg($thisurl);
		
		if($issubscribe!='1' ){
			if($uid > 0){
				$sql = "SELECT is_subscribe FROM `{$this->App->prefix()}user` WHERE user_id = '$uid' LIMIT 1";
			}
			$issubscribe = $this->App->findvar($sql);
			if($issubscribe!='1' ){
				$this->Session->write('User.subscribe','0');
				setcookie(CFGH.'USER[SUBSCRIBE]', '0', mktime() + 2592000);
				
				if(!($to_wecha_id>0)){
					$to_wecha_id = $this->get_user_parent_uid();
				}
				if($to_wecha_id > 0){
					if(strpos($thisurl,'?')){
						 $thisurl = $thisurl.'&toid='.$to_wecha_id;
					}else{
						 $thisurl = $thisurl.'?toid='.$to_wecha_id;
					}
					//为了超过有效期，重新写入
					$this->Session->write('User.to_wecha_id',$to_wecha_id); //来源ID
					setcookie(CFGH.'USER[TOOPENID]', $to_wecha_id, mktime() + 2592000);
				}
			}
		} 
		
		//如果关注了 清空原来推荐用户
		if($issubscribe=='1' && !empty($to_wecha_id)){
			$this->Session->write('User.to_wecha_id',null);
			setcookie(CFGH.'USER[TOOPENID]', '', mktime() - 2592000);
			$toid = isset($_GET['toid']) ? intval($_GET['toid']) : '0';
			if($toid > 0){ //去掉推荐人
				$thisurl = str_replace(array('?toid='.$toid,'&toid='.$toid,'&toid='),'',$thisurl);
				$this->jump($thisurl);exit;
			}
		}
		
		$tid = isset($_GET['tid']) ? intval($_GET['tid']) : '0';
		if($tid=='0'){
			if(isset($_GET['tid'])){
				$thisurl = str_replace(array('?tid=0','&tid=0','tid=0','?tid=','&tid=','tid='),'',$thisurl);
			}
			
			if($uid>0){
				$thisurl = strpos($thisurl,'?') ? $thisurl.'&tid='.$uid : $thisurl.'?tid='.$uid;
				$this->jump($thisurl);exit;
			}else{
				if($issubscribe!='1' && $to_wecha_id > 0){
					$this->jump(ADMIN_URL.'?toid='.$to_wecha_id);exit;
				}else{
					//$this->jump(ADMIN_URL);exit;
				}
			}
			
		}else{
			if($tid != $uid && $uid > 0){
					//替换当前ID
					 $rr = explode('?',$thisurl);
					 $t2 = isset($rr[1])&&!empty($rr[1]) ? $rr[1] : "";
					 $dd = array();
					 if(!empty($t2)){
						$rr2 = explode('&',$t2);
						if(!empty($rr2))foreach($rr2 as $v){
							$rr2 = explode('=',$v);
							if($rr2[0]=='from' || $rr2[0]=='isappinstalled'|| $rr2[0]=='code'|| $rr2[0]=='state') continue;
							if($rr2[0]=='tid') $v = 'tid='.$uid; 
							$dd[] = $v;
						}
					 }
					 $thisurl = $rr[0].'?'.(!empty($dd) ? implode('&',$dd) : 'tid='.$uid);
 
					$this->jump($thisurl);exit;
			}
		}
	}
	
	//设置信息
	function get_userconfig(){
		$t = Common::_return_px();
		$cache = Import::ajincache();
		$cache->SetFunction(__FUNCTION__);
		$cache->SetMode('sitemes'.$t);
		$fn = $cache->fpath(func_get_args());
		if(file_exists($fn)&& (mktime() - filemtime($fn) < 7000) && !$cache->GetClose()){
				include($fn);
		}
		else
		{
				$sql = "SELECT * FROM `{$this->App->prefix()}userconfig` WHERE type = 'basic' LIMIT 1";
				$rt = $this->App->findrow($sql);
				$cache->write($fn, $rt,'rt');
	   }
	   return $rt;
	}
	
	//返回分销商uid
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
	
	
	//自动登陆
	function user_auto_login(){
		$rt = $this->_get_appid_appsecret();
		if( is_weixin()==false || $rt['is_oauth']=='0'){
			return;
		}
		
		//一下用于测试
/*		if($GLOBALS['LANG']['is_cache']=='1'&&!isset($_GET['code'])){
			session_destroy();
			$this->Session->write('User',null);
			//$this->Session->write('Agent',null);
			setcookie(CFGH.'USER[TOOPENID]', "", mktime()-3600);
			setcookie(CFGH.'USER[UKEY]', "", mktime()-3600);
			setcookie(CFGH.'USER[PASS]', "", mktime()-3600);
			setcookie(CFGH.'USER[TID]', "", mktime()-3600);
			setcookie(CFGH.'USER[CODETIME]', "", mktime()-3600);
			setcookie(CFGH.'USER[ISOAUTH]', "", mktime()-3600);
			setcookie(CFGH.'USER[APPID]', "", mktime()-3600);
			setcookie(CFGH.'USER[APPSECRET]', "", mktime()-3600);
			die('这是测试阶段，缓存已经清空完成....');
		}*/
		//授权判断
		
		$wecha_id = $this->Session->read('User.wecha_id');
		if(empty($wecha_id)) $wecha_id = isset($_COOKIE[CFGH.'USER']['UKEY']) ? $_COOKIE[CFGH.'USER']['UKEY'] : '';
		
		
		$appid = $rt['appid'];
		$appsecret = $rt['appsecret'];
		
		$codetime = $this->Session->read('User.codetime');
		if(empty($codetime)) $codetime = isset($_COOKIE[CFGH.'USER']['CODETIME']) ? $_COOKIE[CFGH.'USER']['CODETIME'] : 0;
	
		if(empty($appid) || empty($appsecret)){
			$sql = "SELECT appid,appsecret,is_oauth,winxintype FROM `{$this->App->prefix()}wxuserset` ORDER BY id DESC LIMIT 1";
			$rt = $this->App->findrow($sql);
			$appid = $rt['appid'];
			$appsecret = $rt['appsecret'];

			$this->Session->write('User.isoauth',$rt['is_oauth']);
			setcookie(CFGH.'USER[ISOAUTH]', $rt['is_oauth'], mktime() + 3600*24);
		}
		if(empty($rt['is_oauth'])) $rt['is_oauth'] = '1';
		if(empty($rt['winxintype'])) $rt['winxintype'] = '3';
		
		//授权获取用户openid
		//if( (empty($wecha_id) || ((mktime() - intval($codetime)) > 10)) && $rt['is_oauth']=='1' && $rt['winxintype']=='3' ){
		if( empty($wecha_id) && $rt['winxintype']=='3'){
			//echo "run................1";
			if(!isset($_GET['code'])){
				$this->get_user_code();//授权跳转
			}
			
			$code = isset($_GET['code']) ? $_GET['code'] : '';
			if(!empty($code)){
				
                            $access_token = $this->_get_access_token();
				
                            $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$appid.'&secret='.$appsecret.'&code='.$code.'&grant_type=authorization_code';
                            $con = $this->curlGet($url);
                            if(!empty($con)){
				$json=json_decode($con);
				if(empty($access_token)) $access_token = $json->access_token;
				
				$wecha_id = $json->openid;
				
				$refresh_token = $json->refresh_token; //获取 refresh_token
				if(!empty($refresh_token) && !empty($access_token)){
					if(empty($wecha_id)){
						$url = 'https://api.weixin.qq.com/sns/oauth2/refresh_token?appid='.$appid.'&grant_type=refresh_token&refresh_token='.$refresh_token;
						$con = $this->curlGet($url);
						$json=json_decode($con);
						$wecha_id = $json->openid; //获取 openid
					}
					$this->Session->write('User.wecha_id',$wecha_id);
					setcookie(CFGH.'USER[UKEY]', $wecha_id, mktime() + 2592000);
					
					//获取缓存信息
					$userinfo = $this->get_user_wecha_id_info($wecha_id);
					if(empty($userinfo) || empty($userinfo['nickname']) || empty($userinfo['city']) || empty($userinfo['province']) || empty($userinfo['headimgurl'])){
                                            //获取用户信息
                                            $url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$access_token.'&openid='.$wecha_id.'&lang=zh_CN';
                                            $con = $this->curlGet($url);
                                            if(!empty($con)){
						$json=json_decode($con);
						$subscribe = $json->subscribe;
						if($subscribe == '1'){
							$this->Session->write('User.nickname',(isset($json->nickname)?$json->nickname : ''));
							$this->Session->write('User.sex',(isset($json->sex)?$json->sex : ''));
							$this->Session->write('User.city',(isset($json->city)?$json->city : ''));
							$this->Session->write('User.province',(isset($json->province)?$json->province : ''));
							$this->Session->write('User.headimgurl',(isset($json->headimgurl)?$json->headimgurl : ''));
							$this->Session->write('User.subscribe_time',(isset($json->subscribe_time)?$json->subscribe_time : ''));
							
							$nickname = $this->Session->read('User.nickname');
							$sex = $this->Session->read('User.sex');
							$city = $this->Session->read('User.city');
							$province = $this->Session->read('User.province');
							$headimgurl = $this->Session->read('User.headimgurl');
							$subscribe_time = $this->Session->read('User.subscribe_time');
							if(!empty($wecha_id)){
								$sql = "SELECT user_id FROM `{$this->App->prefix()}user` WHERE wecha_id='$wecha_id' LIMIT 1";
								$uid = $this->App->findvar($sql);
								if($uid > 0){
									$ddl = array();
									if(!empty($nickname)) $ddl['nickname'] = $nickname;
									if(!empty($city)) $ddl['cityname'] = $city;
									if(!empty($province)) $ddl['provincename'] = $province;
									if(!empty($headimgurl)) $ddl['headimgurl'] = $headimgurl;
									if($sex > 0) $ddl['sex'] = $sex;
									if(!empty($ddl)){
											$this->App->update('user',$ddl,'user_id',$uid);
											$this->Session->write('User.uid',$uid);
											setcookie(CFGH.'USER[UID]', $uid, mktime() + 2592000);
									}
								}
							}else{
								//写入日记，获取openid为空
							}
						}
						$this->Session->write('User.subscribe',$subscribe);
						setcookie(CFGH.'USER[SUBSCRIBE]', $subscribe, mktime() + 2592000);
                                            }
					}
					
				}else{
					die("非法错误：获取refresh_token或者access_token为空，麻烦联系网站管理员解决，谢谢！");
				}
                            }
			}else{
				die("非法错误：获取code码为空，麻烦联系网站管理员解决，谢谢！");
			}
		}
		
		$uid = $this->Session->read('User.uid');
		
		if(empty($wecha_id)){
			$wecha_id = isset($_COOKIE[CFGH.'USER']['UKEY']) ? $_COOKIE[CFGH.'USER']['UKEY'] : '';
			if(empty($wecha_id)){
				if($uid > 0){
					$sql = "SELECT wecha_id FROM `{$this->App->prefix()}user` WHERE user_id ='$uid' LIMIT 1";
					$wecha_id = $this->App->findvar($sql);
					if(!empty($wecha_id)){
							$this->Session->write('User.wecha_id',$wecha_id);
							setcookie(CFGH.'USER[UKEY]', $wecha_id, mktime() + 2592000);
					}
				}
			}else{
				$this->Session->write('User.wecha_id',$wecha_id);
			}
		}

		//双重记录UID
		if(!($uid > 0)){
		 	$uid = isset($_COOKIE[CFGH.'USER']['UID']) ? $_COOKIE[CFGH.'USER']['UID'] : '0';
			if($uid > 0){
				$this->Session->write('User.uid',$uid);
			}else{
				if(!empty($wecha_id)){
					$sql = "SELECT user_id FROM `{$this->App->prefix()}user` WHERE wecha_id ='$wecha_id' LIMIT 1";
					$uid = $this->App->findvar($sql);
					if($uid > 0){
						$this->Session->write('User.uid',$uid);
						setcookie(CFGH.'USER[UID]', $uid, mktime() + 2592000);
					}else{
						//die("非法错误：初始化账户失败，麻烦联系网站管理员解决，谢谢！");
					}
				}
			
			}
		}
		
		$tid  = 0;
		if(!($uid > 0)){
			$tid = isset($_GET['tid']) ? intval($_GET['tid']) : '0'; //用户入来的id
			if(empty($tid)) $tid = isset($_COOKIE[CFGH.'USER']['TID']) ? $_COOKIE[CFGH.'USER']['TID'] : '';
			if(!($tid > 0)){
				$tid = $this->Session->read('User.tid');
			}
		}else{//清空
			$this->Session->write('User.tid',null);
			setcookie(CFGH.'USER[TID]', '', mktime() - 2592000);
		}
		
		$toid = 0;
		$userinfo = $this->get_user_wecha_id_info($wecha_id,1);
		$is_subscribe = isset($userinfo['is_subscribe']) ? $userinfo['is_subscribe'] : '0';
		if($is_subscribe=='0'){
			$toid = isset($_GET['toid']) ? intval($_GET['toid']) : '0';  //这个是关注后转发的用户ID
			if(empty($toid)) $toid = isset($_COOKIE[CFGH.'USER']['TOOPENID']) ? $_COOKIE[CFGH.'USER']['TOOPENID'] : '';
			if(!($toid > 0)){
				$toid = $this->Session->read('User.to_wecha_id');
			}
			if($toid>0){ //从新记录TID
				setcookie(CFGH.'USER[TOOPENID]', $toid, mktime() + 2592000);
				$this->Session->write('User.to_wecha_id',$toid);
			}
		}else{//清空
			$this->Session->write('User.to_wecha_id',null);
			setcookie(CFGH.'USER[TOOPENID]', '', mktime() - 2592000);
		}
		
		//以后数据量大可去掉，前期方便调试
/*		if($uid > 0){
			$sql = "SELECT user_id FROM `{$this->App->prefix()}user` WHERE user_id ='$uid' LIMIT 1";
			$uid = $this->App->findvar($sql);
		}*/
		
		//不是第一次进入
		if($uid > 0){			
                    //不做处理
                   
		}else{ //第一次进来或者已经超过缓存期				
			if(empty($wecha_id)){
				$wecha_id = $this->Session->read('User.wecha_id');
				if(empty($wecha_id)){
					$wecha_id = isset($_COOKIE[CFGH.'USER']['UKEY']) ? $_COOKIE[CFGH.'USER']['UKEY'] : '';
					if(empty($wecha_id)){
						if($rt['is_oauth']=='1'){
							die("非法错误：获取微信用户openid为空，麻烦联系网站管理员解决，谢谢！");
						}
					}
				}
			}
			
			$sql = "SELECT user_id FROM `{$this->App->prefix()}user` WHERE wecha_id ='$wecha_id' ORDER BY user_id ASC LIMIT 1";
			$uid = $this->App->findvar($sql);
			
			if($uid > 0){
				
              //暂不做处理
				
			}else{//end if uid
				$rrL = $this->get_userconfig();
				
				//重新创建账号
				$thisurl = $this->Session->read('User.url'); //记录当前进入连接
				if(empty($thisurl)){
					$thisurl = isset($_COOKIE[CFGH.'USER']['URL']) ? $_COOKIE[CFGH.'USER']['URL'] : '0';
				}
				
				if(empty($nickname)) $nickname = $this->Session->read('User.nickname');
				if(empty($sex)) $sex = $this->Session->read('User.sex');
				if(empty($city)) $city = $this->Session->read('User.city');
				if(empty($province)) $province = $this->Session->read('User.province');
				if(empty($headimgurl)) $headimgurl = $this->Session->read('User.headimgurl');
				if(empty($subscribe_time)) $subscribe_time = $this->Session->read('User.subscribe_time');
				
				$datas = array();
				if(!empty($nickname)) $datas['nickname'] = $nickname;
				if(!empty($city)) $datas['cityname'] = $city;
				if(!empty($province)) $datas['provincename'] = $province;
				if(!empty($headimgurl)) $datas['headimgurl'] = $headimgurl;
				if($sex > 0) $datas['sex'] = $sex;
				
				$datas['user_name'] = !empty($wecha_id) ? $wecha_id : 'GZSH'.$tid.mktime();
				$datas['wecha_id'] = $datas['user_name'];
				$t = mktime();
				$datas['password'] = md5('A123456');
				$datas['user_rank'] = 1;
				$ip = Import::basic()->getip();
				$datas['reg_ip'] = $ip ? $ip : '0.0.0.0';
				$datas['reg_time'] = $t;
				$datas['reg_from'] = Import::ip()->ipCity($ip);
				$datas['last_login'] = mktime();
				$datas['last_ip'] = $datas['reg_ip'];
				$datas['active'] = 1;
				$issubscribe = $this->Session->read('User.subscribe');
				if(empty($issubscribe)) $issubscribe = isset($_COOKIE[CFGH.'USER']['SUBSCRIBE']) ? $_COOKIE[CFGH.'USER']['SUBSCRIBE'] : '0';
				if($issubscribe == '1'){ $datas['is_subscribe'] = 1; }
                                $uid = $this->Session->read('User.uid');
                                if($uid > 0) return true;
				if($this->App->insert('user',$datas)){ //添加账户
						$uid = $this->App->iid();
                                                $this->Session->write('User.uid',$uid);
						if($tid!=$uid){//加入分享表
							$dd = array();
							$dd['share_uid'] = $tid; //分享者uid
							$dd['parent_uid'] = $toid > 0 ? $toid : $tid; //关注者分享ID
							$dd['uid'] = $uid;
							$puid = $dd['parent_uid'];
							
							//$dd['daili_uid'] = $duid;
							$dd['url'] = $thisurl;
							$dd['addtime'] = mktime();
							if($this->App->insert('user_tuijian',$dd)){ //添加推荐用户
								//统计分享 跟 关注数
								if($issubscribe=='1'){ //当前用户关注了的
										if($dd['parent_uid']==$dd['share_uid'] && $dd['share_uid'] > 0){
												$sql = "UPDATE `{$this->App->prefix()}user` SET `share_ucount` = `share_ucount`+1,`guanzhu_ucount` = `guanzhu_ucount`+1 WHERE user_id = '$tid'";
												$this->App->query($sql);
										}else{
											if($dd['parent_uid'] > 0){
												$id = $dd['parent_uid'];
												$sql = "UPDATE `{$this->App->prefix()}user` SET `guanzhu_ucount` = `guanzhu_ucount`+1 WHERE user_id = '$id' AND is_subscribe='1'";
												$this->App->query($sql);
											}
											
											if($dd['share_uid'] > 0){
												$id = $dd['share_uid'];
												$sql = "UPDATE `{$this->App->prefix()}user` SET `share_ucount` = `share_ucount`+1 WHERE user_id = '$id'";
												$this->App->query($sql);
											}
										}
										
								}else{
									//统计分享用户数
									if($dd['share_uid'] > 0){
										$id = $dd['share_uid'];
										$sql = "UPDATE `{$this->App->prefix()}user` SET `share_ucount` = `share_ucount`+1 WHERE user_id = '$id'";
										$this->App->query($sql);
									}
								} //end if subscribe
								
								if($tid > 0){
									//发送推荐用户通知
									$pwecha_id = $this->App->findvar("SELECT wecha_id FROM `{$this->App->prefix()}user` WHERE user_id='$tid' LIMIT 1");
									$rr = $this->_get_appid_appsecret();
									$appid = $rr['appid'];
									$appsecret = $rr['appsecret'];
									$na = !empty($nickname) ? $nickname : '';
									//推荐用户
									$this->action('api','send',array('openid'=>$pwecha_id,'appid'=>$appid,'appsecret'=>$appsecret,'nickname'=>$na),'share');
									//代理用户
									if($duid > 0 && $duid != $tid){
										//$pwecha_id = $this->App->findvar("SELECT wecha_id FROM `{$this->App->prefix()}user` WHERE user_id='$duid' LIMIT 1");
										//$this->action('api','send',array('openid'=>$pwecha_id,'appid'=>$appid,'appsecret'=>$appsecret,'nickname'=>$na),'sharedaili');
									}
								}
							}
							unset($dd);
						}
						
						
						//添加地址
						if(!empty($city) && !empty($province)){
							$sql = "SELECT region_id FROM `{$this->App->prefix()}region` WHERE region_name LIKE '%$city%' LIMIT 1";
							$cityid = $this->App->findvar($sql);
							$sql = "SELECT region_id FROM `{$this->App->prefix()}region` WHERE region_name LIKE '%$province%' LIMIT 1";
							$provinceid = $this->App->findvar($sql);
							if($cityid > 0 && $provinceid>0){
								$dd = array();
								$dd['consignee'] = $nickname;
								$dd['user_id'] = $uid;
								$dd['sex'] = $sex;
								$dd['city'] = $cityid;
								$dd['province'] = $provinceid;
								$dd['country'] = 1;
								$dd['is_own'] = 1;
								$this->App->insert('user_address',$dd);
								unset($dd);
							}
						}
						$this->Session->write('User.username',$datas['user_name']);
						$this->Session->write('User.uid',$uid);
						$this->Session->write('User.active','1');
						$this->Session->write('User.rank','1');
						$this->Session->write('User.ukey',$datas['wecha_id']);
						$this->Session->write('User.addtime',mktime());
						//写入cookie
						setcookie(CFGH.'USER[UKEY]', $datas['wecha_id'], mktime() + 2592000);
						setcookie(CFGH.'USER[UID]', $uid, mktime() + 2592000);
		
				}else{
					die('初始化帐号失败，请联系管理员解决这个问题，谢谢！');
				}//end if insert
				
			}
			/******************************************/
			
		} //end if
		
	}
	
	
	
	function fasong($rt=array()){
		$iconv = Import::gz_iconv();

		$con = $rt['con'];
		$tpn = $rt['tpn'];
		$uid = $rt['uid'];
		
		if(empty($con)){
			return array('error'=>1,'message'=>'内容为空');
		}
		if(empty($tpn)){
			return array('error'=>1,'message'=>'发送对象号码不合法');
		}
		if( preg_match("/1[3458]{1}\d{9}$/",$tpn) ){}else{
			return array('error' => 1, 'message' => '发送对象号码不合法');
		}
		if(!(preg_match('/^.*$/u', $con) > 0)){
			$con = $iconv->ec_iconv('GB2312', 'UTF8', $con);
		}
							
		require_once(SYS_PATH.'data'.DS.'smsconfig.php');
		$jgid = $sms['jgid'];
		$loginname = $sms['loginname'];
		$passwd = $sms['passwd'];
		$url = 'http://121.12.175.198:8180';
		$urls = "{$url}/service.asmx/SendMessageStr?Id={$jgid}&Name={$loginname}&Psw={$passwd}&Message={$con}&Phone={$tpn}&Timestamp=0";
		$result = file_get_contents($urls);
		if(!(preg_match('/^.*$/u', $result) > 0)){
			$result = $iconv->ec_iconv('GB2312', 'UTF8', $result);
		}
		$state = 1;
		$sa = @explode(',',$result);
		if(!empty($sa))foreach($sa as $k=>$item){
			if($k==0){
				$sa2 = @explode(':',$item);
				$state = $sa2[1];
			}
		}
		$data['addtime'] = mktime();
		$data['state'] = $state;
		$data['uid'] = $uid;
		$data['con'] = $con;
		$data['tpn'] = $tpn;
		
		$this->App->insert('sms_log',$data);
		return true;
	}
	
	/*
	*返回弹出框HTML代码的方法
	*/
	function ajax_popbox($boxname="",$data=array()){
		if($data['type']=='cart'){
			$gid = $data['gid'];
			$sql = "SELECT goods_id,goods_name,goods_thumb,shop_price,promote_price,qianggou_price,pifa_price,promote_start_date,promote_end_date,is_qianggou,is_promote,qianggou_start_date,qianggou_end_date FROM `{$this->App->prefix()}goods` WHERE goods_id='{$gid}'";
			
			$rt = $this->App->findrow($sql);
			if($rt['is_promote']=='1'){
				//促销 价格
				if($rt['promote_start_date']<mktime()&&$rt['promote_end_date']>mktime()){
					$rt['promote_price'] = format_price($rt['promote_price']);
				}else{
					$rt['promote_price'] = "0.00";
				}
			}else{
				$rt['promote_price'] = "0.00";
			}
			
			if($rt['is_qianggou']=='1'){
				//促销 价格
				if($rt['qianggou_start_date']<mktime()&&$rt['qianggou_end_date']>mktime()){
					$rt['qianggou_price'] = format_price($rt['qianggou_price']);
				}else{
					$rt['qianggou_price'] = "0.00";
				}
			}else{
				$rt['qianggou_price'] = "0.00";
			}
		}
		$rt['number'] = $data['num'];
		$this->set('rt',$rt);
		$con = "";
		if(!empty($boxname)) $con = $this->fetch($boxname,true);
		echo $con; exit;
	}
	
	function curlGet($url){
		$ch = curl_init();
		$header = "Accept-Charset: utf-8";
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$temp = curl_exec($ch);
		if(empty($temp)) $temp = Import::crawler()->curl_get_con($url);
		return $temp;
	}
}
?>
<?php
class ApiController extends Controller{
	
	function _return_px(){
		   $t = '';
		   $x = $_SERVER["HTTP_HOST"];
		   $x1 = explode('.',$x);
		   if(count($x1)==2){
			 $t = $x1[0];
		   }elseif(count($x1)>2){
			 $t =$x1[0].$x1[1];
		   }
		   return $t;
	}
		
	//获取appid、appsecret
	function _get_appid_appsecret(){
			$t = $this->_return_px();
			$cache = Import::ajincache();
			$cache->SetFunction(__FUNCTION__);
			$cache->SetMode('sitemes'.$t);
			$fn = $cache->fpath(func_get_args());
			if(file_exists($fn)&& (mktime() - filemtime($fn) < 7000) && !$cache->GetClose()){
				    include($fn);
			}
			else
		    {
					$sql = "SELECT appid,appsecret FROM `{$this->App->prefix()}wxuserset` ORDER BY id DESC LIMIT 1";
					$rr = $this->App->findrow($sql);
					$rt['appid'] = $rr['appid'];
					$rt['appsecret'] = $rr['appsecret'];
					
					$cache->write($fn, $rt,'rt');
		   }
		   return $rt;
	}
	
	//获取access_token
	function _get_access_token(){
			$t = $this->_return_px();
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


	function sendtxt($rts=array(),$type=""){
		$access_token = $this->_get_access_token();
		$data = $this->_get_send_con_txt($rts,$type);
		$rt=$this->curlPost('https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token='.$access_token,$data,0);
	}
	function _get_send_con_txt($rt=array(),$ty=''){
		$data = array();
		switch($ty){
			case 'share':
				$openid = $rt['openid'];
				if(empty($rt['nickname'])){
					$str = '新增一位游客啦;\n\n服务类型：分享已被浏览\n提交时间：'.date('Y-m-d H:i:s').'\n\n备注：1位好友浏览您的分享等于1次【邀请】,他还需要关注您才有收入哦!';
				}else{
					$str = '新增一位好友['.$rt['nickname'].']啦;\n\n服务类型：分享已被浏览\n提交时间：'.date('Y-m-d H:i:s').'\n\n备注：1位好友浏览您的分享等于1次【邀请】,他还需要关注您才有收入哦!';
				}
				$data='{"touser": "'.$openid.'","msgtype": "text","text": {"content":"'.$str.'"}}';
				break;
			case 'sharedaili': //代理下面的用户分享通知代理
			$openid = $rt['openid'];
			$str = '一级粉丝['.$rt['nickname'].']浏览您的分享啦;\n\n服务类型：下级用户分享已被浏览\n提交时间：'.date('Y-m-d H:i:s').'\n\n备注：他还需要关注并且购买您才有收入哦!';
			$data='{"touser": "'.$openid.'","msgtype": "text","text": {"content":"'.$str.'"}}';
			break;
			case 'payreturnpoints': //支付返积分
				$openid = $rt['openid'];
				$str = '订单['.$rt['order_sn'].']已支付,新增积分:+'.$rt['points'].';\n\n服务类型：消费返积分\n提交时间：'.date('Y-m-d H:i:s');
				$data='{"touser": "'.$openid.'","msgtype": "text","text": {"content":"'.$str.'"}}';
				break;
			case 'payreturnpoints_parentuid': //支付返积分
				$openid = $rt['openid'];
				$str = '下级用户订单已支付,新增积分:+'.$rt['points'].';\n\n服务类型：下级消费返积分\n提交时间：'.date('Y-m-d H:i:s').'\n\n备注：消费越多,积分越多,用积分可赢大奖哦!';
				$data='{"touser": "'.$openid.'","msgtype": "text","text": {"content":"'.$str.'"}}';
				break;
			case 'nomoney':
				$openid = $rt['openid'];
				$str = '您的会员已下单，但您达到了领取红包的上限限制，赶快升级会员等级吧！';
				$data='{"touser": "'.$openid.'","msgtype": "text","text": {"content":"'.$str.'"}}';
				break;
			case 'nomoney_pay_sucess':
				$openid = $rt['openid'];
				$level = $rt['level'];
				$str = '您的'.$level.'会员：['.$rt['nickname'].'] 已支付成功！';
				$data='{"touser": "'.$openid.'","msgtype": "text","text": {"content":"'.$str.'"}}';
				break;
			case 'my_money_pay_sucess':
				$openid = $rt['openid'];
				$str = '恭喜您已经成功支付！';
				$data='{"touser": "'.$openid.'","msgtype": "text","text": {"content":"'.$str.'"}}';
				break;
			case 'guanzhu':
				$openid = $rt['openid'];
				$str = '您好：您的一级会员：['.$rt['nickname'].'] 已成功关注！';
				$data='{"touser": "'.$openid.'","msgtype": "text","text": {"content":"'.$str.'"}}';
				break;
			case 'payreturnmoney': //支付返佣金
				$openid = $rt['openid'];
				$str = '你好：\n:你的粉丝：'.$rt['nickname'].' 已成功在商城下单'.$rt['money'].'元！快去用户中心申领红包吧！';
				$data='{"touser": "'.$openid.'","msgtype": "text","text": {"content":"'.$str.'"}}';
			case 'payusereturnmoney': //购买者返佣金
				$openid = $rt['openid'];
				$str = '新下订单['.$rt['order_sn'].'],新增金额:+￥'.$rt['money'].'\n\n服务类型：消费返佣金\n提交时间：'.date('Y-m-d H:i:s');
				$data='{"touser": "'.$openid.'","msgtype": "text","text": {"content":"'.$str.'"}}';
				break;
			case 'buymess': //需要购买，开通分销通知
				$openid = $rt['openid'];
				$str = '['.$rt['nickname'].'],购买产品成为代理商赚分红,您还需要至少购买一件产品哦！\n\n提交时间：'.date('Y-m-d H:i:s');
				$data='{"touser": "'.$openid.'","msgtype": "text","text": {"content":"'.$str.'"}}';
				break;
			case 'orderconfirm':
				$openid = $rt['openid'];
				$str = '订单已成功提交,请尽快付款!\n\n提交时间：'.date('Y-m-d H:i:s');
				$data='{"touser": "'.$openid.'","msgtype": "text","text": {"content":"'.$str.'"}}';
				break;
			case 'orderconfirm_toshop': //通知商家
				$openid = $rt['openid'];
				$str = '店里有人下单了,等待对方付款!\n\n提交时间：'.date('Y-m-d H:i:s');
				$data='{"touser": "'.$openid.'","msgtype": "text","text": {"content":"'.$str.'"}}';
				break;
			case 'payconfirm': //
				$openid = $rt['openid'];
				$str = '订单已成功支付,请联系上级发货!\n\n提交时间：'.date('Y-m-d H:i:s');
				$data='{"touser": "'.$openid.'","msgtype": "text","text": {"content":"'.$str.'"}}';
				break;
			case 'payconfirm_vg': //
				$str = '【'.$rt['nickname'].'】 \n验证码：\n\n'.$rt['goods_pass'].'\n\n提交时间：'.date('Y-m-d H:i:s');
				$data='{"touser": "'.$rt['openid'].'","msgtype": "text","text": {"content":"'.$str.'"}}';
				break;
			case 'uplevel_self': //会员升级
				$openid = $rt['openid'];
				$str = '恭喜您成为【多米商城】经销商，您当前等级为【'.$rt['rank_name'].'】';
				$data='{"touser": "'.$openid.'","msgtype": "text","text": {"content":"'.$str.'"}}';
				break;
			case 'fenyongjin': // 
				$str = '您好：您的'.$rt['jibie'].'【'.$rt['nickname'].'】，已成功下单金额'.$rt['money'].'元，请去申领红包。';
				$data='{"touser": "'.$rt['openid'].'","msgtype": "text","text": {"content":"'.$str.'"}}';
				break;
			
			case 'fahuo': //发货
				$str = '恭喜，上级【'.$rt['p_name'].'】已为您发货，\n验证码：\n'.$rt['snpass'].'\n\n提交时间：'.date('Y-m-d H:i:s');
				$data='{"touser": "'.$rt['openid'].'","msgtype": "text","text": {"content":"'.$str.'"}}';
				break;
			case 'liuyan':
				$str = $rt['nickname']." 给您留言:\n\n".$rt['content']."\n\n<a href='http://".$_SERVER['HTTP_HOST']."/m/daili.php?act=liuyan&t_uid=".$rt['f_uid']."'>点击回复</a>";
				$data='{"touser": "'.$rt['openid'].'","msgtype": "text","text": {"content":"'.$str.'"}}';
				break;
		}
		
		return $data;
	}	
	function send($rts=array(),$type=""){
		if(empty($rts['openid'])) return;

		$access_token = $this->_get_access_token();
		$data = $this->_get_send_con($rts,$type);
		$rt=$this->curlPost('https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token='.$access_token,$data,0);
	}
	
	function _get_send_con($rt=array(),$ty=''){
		$data = array();
		switch($ty){
			case 'share':
			$openid = $rt['openid'];
			if(empty($rt['nickname'])){
				$str = '新增一位游客啦;\n\n服务类型：分享已被浏览\n提交时间：'.date('Y-m-d H:i:s').'\n\n备注：1位好友浏览您的分享等于1次【邀请】,他还需要关注您才有积分收入哦!';
			}else{
				$str = '新增一位好友['.$rt['nickname'].']啦;\n\n服务类型：分享已被浏览\n提交时间：'.date('Y-m-d H:i:s').'\n\n备注：1位好友浏览您的分享等于1次【邀请】,他还需要关注您才有积分收入哦!';
			}
			$data='{"touser": "'.$openid.'","msgtype": "news","news": {"articles": [{"title": "邀请好友服务提醒", "description": "'.$str.'","url":"'.ADMIN_URL.'user.php?act=myshare"}]}}';
			break;
			case 'sharedaili': //代理下面的用户分享通知代理
			$openid = $rt['openid'];
			$str = '下级好友['.$rt['nickname'].']浏览您的分享啦;\n\n服务类型：下级用户分享已被浏览\n提交时间：'.date('Y-m-d H:i:s').'\n\n备注：他还需要关注并且购买您才有收入哦!';
			$data='{"touser": "'.$openid.'","msgtype": "news","news": {"articles": [{"title": "邀请好友服务提醒", "description": "'.$str.'","url":"'.ADMIN_URL.'user.php?act=myshare"}]}}';
			break;
			case 'payreturnpoints': //支付返积分
			$openid = $rt['openid'];
			$str = '订单['.$rt['order_sn'].']已支付,新增积分:+'.$rt['points'].';\n\n服务类型：消费返积分\n提交时间：'.date('Y-m-d H:i:s');
			$data='{"touser": "'.$openid.'","msgtype": "news","news": {"articles": [{"title": "消费返积分服务提醒", "description": "'.$str.'","url":"'.str_replace(array('paywx/','wxpay/'),'',ADMIN_URL).'user.php?act=mypoints"}]}}';
			break;
			case 'payreturnpoints_parentuid': //支付返积分
			$openid = $rt['openid'];
			$str = '下级用户订单已支付,新增积分:+'.$rt['points'].';\n\n服务类型：下级消费返积分\n提交时间：'.date('Y-m-d H:i:s').'\n\n备注：消费越多,积分越多,用积分可赢大奖哦!';
			$data='{"touser": "'.$openid.'","msgtype": "news","news": {"articles": [{"title": "下级消费返积分服务提醒", "description": "'.$str.'","url":"'.str_replace(array('paywx/','wxpay/'),'',ADMIN_URL).'user.php?act=mypoints"}]}}';
			break;
			case 'payreturnmoney': //支付返佣金
			$openid = $rt['openid'];
			$str = '下级好友:'.$rt['nickname'].'的订单['.$rt['order_sn'].'],新增金额:+￥'.$rt['money'].'\n\n服务类型：消费返佣金\n提交时间：'.date('Y-m-d H:i:s');
			$data='{"touser": "'.$openid.'","msgtype": "news","news": {"articles": [{"title": "消费返佣服务提醒", "description": "'.$str.'","url":"'.str_replace(array('paywx/','wxpay/'),'',ADMIN_URL).'daili.php?act=monrydeial"}]}}';
			case 'payusereturnmoney': //购买者返佣金
			$openid = $rt['openid'];
			$str = '新下订单['.$rt['order_sn'].'],新增金额:+￥'.$rt['money'].'\n\n服务类型：消费返佣金\n提交时间：'.date('Y-m-d H:i:s');
			$data='{"touser": "'.$openid.'","msgtype": "news","news": {"articles": [{"title": "消费返佣服务提醒", "description": "'.$str.'","url":"'.str_replace(array('paywx/','wxpay/'),'',ADMIN_URL).'daili.php?act=monrydeial"}]}}';
			break;
			case 'jixiaojiang': //绩效奖通知
			$openid = $rt['openid'];
			$str = '新下订单['.$rt['order_sn'].'],新增金额:+￥'.$rt['money'].'\n\n服务类型：绩效奖金！\n提交时间：'.date('Y-m-d H:i:s');
			$data='{"touser": "'.$openid.'","msgtype": "news","news": {"articles": [{"title": "消费返绩效奖金提醒", "description": "'.$str.'","url":"'.str_replace(array('paywx/','wxpay/'),'',ADMIN_URL).'daili.php?act=monrydeial"}]}}';
			break;
			case 'buymess': //需要购买，开通分销通知
			$openid = $rt['openid'];
			$str = '['.$rt['nickname'].'],购买产品成为合伙人赚分红,您还需要至少购买一件产品哦！\n\n提交时间：'.date('Y-m-d H:i:s');
			$data='{"touser": "'.$openid.'","msgtype": "news","news": {"articles": [{"title": "购买产品成分销商赚佣金", "description": "'.$str.'","url":"'.str_replace(array('paywx/','wxpay/'),'',ADMIN_URL).'user.php"}]}}';
			break;
			case 'sendgift': //
			$openid = $rt['openid'];
			$str = '['.$rt['nickname'].'],您已免费获取一张价值980元的消费卡！\n\n提交时间：'.date('Y-m-d H:i:s');
			$data='{"touser": "'.$openid.'","msgtype": "news","news": {"articles": [{"title": "赠送980元消费卡", "description": "'.$str.'","url":"'.str_replace(array('paywx/','wxpay/'),'',ADMIN_URL).'user.php?act=mygift"}]}}';
			break;
			case 'orderconfirm': //
			$openid = $rt['openid'];
			$str = '订单已成功提交,请尽快付款!\n\n提交时间：'.date('Y-m-d H:i:s');
			$data='{"touser": "'.$openid.'","msgtype": "news","news": {"articles": [{"title": "订单确认通知服务", "description": "'.$str.'","url":"'.str_replace(array('paywx/','wxpay/'),'',ADMIN_URL).'user.php?act=orderlist"}]}}';
			break;
			case 'orderconfirm_toshop': //通知商家
			$openid = $rt['openid'];
			$str = '店里有人下单了,等待对方付款!\n\n提交时间：'.date('Y-m-d H:i:s');
			$data='{"touser": "'.$openid.'","msgtype": "news","news": {"articles": [{"title": "下单通知服务", "description": "'.$str.'","url":"'.str_replace(array('paywx/','wxpay/'),'',ADMIN_URL).'user.php?act=orderlist"}]}}';
			break;
			case 'payconfirm': //
			$openid = $rt['openid'];
			$str = '订单已成功支付,我们将尽快发货,请保持手机畅通等待物流送达!\n\n提交时间：'.date('Y-m-d H:i:s');
			$data='{"touser": "'.$openid.'","msgtype": "news","news": {"articles": [{"title": "订单已支付通知服务", "description": "'.$str.'","url":"'.str_replace(array('paywx/','wxpay/'),'',ADMIN_URL).'user.php?act=orderlist"}]}}';
			break;
			case 'payconfirm_vg': //
			$openid = $rt['openid'];
			$str = '['.$rt['nickname'].'],订单已成功支付,'.(!empty($rt['goods_sn']) ? '卡号:'.$rt['goods_sn'].',' : '').'卡密:'.$rt['goods_pass'].',请注意查收!\n\n提交时间：'.date('Y-m-d H:i:s');
			$data='{"touser": "'.$openid.'","msgtype": "news","news": {"articles": [{"title": "订单已支付通知服务", "description": "'.$str.'","url":"'.str_replace(array('paywx/','wxpay/'),'',ADMIN_URL).'user.php?act=orderlist"}]}}';
			break;
		}
		
		return $data;
	}
	
	function curlPost($url, $data,$showError=1){
		$ch = curl_init();
		$header = "Accept-Charset: utf-8";
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($curl, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$tmpInfo = curl_exec($ch);
		$errorno=curl_errno($ch);
		if ($errorno) {
			return array('rt'=>false,'errorno'=>$errorno);
		}else{
			$js=json_decode($tmpInfo,1);
			if (intval($js['errcode']==0)){
				return array('rt'=>true,'errorno'=>0,'media_id'=>$js['media_id'],'msg_id'=>$js['msg_id']);
			}else {
				if ($showError){
					return array('rt'=>true,'errorno'=>10,'msg'=>'发生了Post错误：错误代码'.$js['errcode'].',微信返回错误信息：'.$js['errmsg']);
				}
			}
		}
	}
	function curlGet($url){
		$ch = curl_init();
		$header = "Accept-Charset: utf-8";
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($curl, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$temp = curl_exec($ch);
		return $temp;
   }

}
?>
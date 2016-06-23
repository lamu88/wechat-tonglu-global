<?php

class ErweimaController extends Controller{
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

	function index(){
		echo '加载中...';
		$uid = $this->checked_login();
		$tid = isset($_GET['tid']) ? intval($_GET['tid']) : '0'; //用户入来的id
		$urank = $this->App->findvar("SELECT user_rank FROM `{$this->App->prefix()}user` WHERE user_id='$uid'");
		if(!isset($urank)) $urank=1;
		if($tid > 0){
			if($uid!=$tid && $urank==1){
				setcookie(CFGH.'USER[TID]', $tid, mktime() + 2592000);
				$this->Session->write('User.tid',$tid);
			}elseif($uid==$tid){
				$tid=$uid;
			}
		}else{
			$thisurl = strpos($thisurl,'?') ? $thisurl.'&tid='.$uid : $thisurl.'?tid='.$uid;
			$this->jump($thisurl);exit;
		}
		$sql = "SELECT is_subscribe,user_rank,user_id,quid,headimgurl,nickname FROM `{$this->App->prefix()}user` WHERE user_id='$tid' LIMIT 1";
		$RT = $this->App->findrow($sql);
		if($RT['user_rank']==1||!isset($RT['user_rank'])) $rank = 1;
		$access_token = $this->_get_access_token();
		$quid = $RT['quid'];
		$headimgurl = $RT['headimgurl'];
		$nickname = $RT['nickname'];
		unset($RT);
		if(!($quid > 0)){
			$sql = "SELECT MAX(quid) FROM `{$this->App->prefix()}user` LIMIT 1";
			$quid = $this->App->findvar($sql);
			$quid = intval($quid)+1;
			$this->App->update('user',array('quid'=>$quid),'user_id',$tid);
		}
		
		$fop = Import::fileop();
		$tis = SYS_PATH.'cache'.DS.$yuming.'qcode'.DS.$tid.DS.'cache'.$quid.'.txt';
		if(file_exists($tis) && (mktime() - filemtime($tis) < 60)){
			//exit;
		}
		if(!file_exists($tis) || (mktime() - filemtime($tis) > 60)){
			$fop->checkDir($tis);
			@file_put_contents($tis,"run");
		}
		$fileimg = SYS_PATH.'photos'.DS.$yuming.'qcode'.DS.$tid.DS.'ms'.$quid.'.jpg';//原图
	if(file_exists($fileimg)){
		$ff3 =  'http://'.$_SERVER['SERVER_NAME'].'/photos'.DS.$yuming.'qcode'.DS.$tid.DS.'ms'.$quid.'.jpg';//原图
		$ff3 = str_replace("\\","/",$ff3);
	}else{
		$f = SYS_PATH.'cache'.DS.$yuming.'qcode'.DS.$tid.DS.'s'.$quid.'.jpg';
		if(!file_exists($f)){
			//生成二维码
			$data = '{"action_name": "QR_LIMIT_SCENE", "action_info": {"scene": {"scene_id": '.$quid.'}}}';
			$rt=$this->curlPost('https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token='.$access_token,$data,10);
			$json=json_decode($rt);
			$ticket = $json->ticket;
			$url = $json->url;
			if(!empty($ticket)){
				$str = "https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=".$ticket;
				$img = file_get_contents($str);
				if(empty($img)){ $img = Import::crawler()->curl_get_con($str); }
				if(!empty($img)){
					$fop->checkDir($f);
					@file_put_contents($f,$img);
				}
			}
		}
		if(!file_exists($f)){
			exit('生成图片失败，请联系网站管理员解决此问题！');
		}
		//二维码坐标
		$ewm_xy = isset($peizhi['ewm_xy']) ? $peizhi['ewm_xy'] : '';
		if(empty($ewm_xy)){
			$ewm_xy = '55,26|265,64|152,410';
		}
		$xyy = array();
		$xy = explode('|',$ewm_xy);
		foreach($xy as $it){
			$xyy[] = explode(',',$it);
		}
		unset($xy,$ewm_xy);
		$f2 = SYS_PATH.'cache'.DS.$yuming.'qcode'.DS.$tid.DS.'m'.$quid.'.jpg';//二维码
		$fop->checkDir($f2);
		$imgobj = Import::img();
		$imgobj->thumb($f,$f2,225,225);
		
		$sf = SYS_PATH.'photos'.DS.$yuming.'codebg.jpg';//原图背景
		if(file_exists($sf)==false){
			$sf = SYS_PATH.'photos'.DS.'codebg.jpg';//原图背景
		}
		$f3 = SYS_PATH.'photos'.DS.$yuming.'qcode'.DS.$tid.DS.'ms'.$quid.'.jpg';//原图
		$fop->checkDir($f3);
		$imgobj->thumb($sf,$f3,530,800);
		
		$t = 'false';
		$t = $this->mark_img($f3,$f2,$xyy[2][0],$xyy[2][1]);
		if($t=='true'){
			//头像
			$t = "false";
			$img = file_get_contents($headimgurl);
			if(empty($img)){ $img = Import::crawler()->curl_get_con($headimgurl); } 
			$f4 = SYS_PATH.'cache'.DS.$yuming.'qcode'.DS.$tid.DS.'mh'.$quid.'.jpg';//头像
			if(!empty($img)){
				$fop->checkDir($f4);
				@file_put_contents($f4,$img);
				if(file_exists($f4)){
					$f5 = SYS_PATH.'cache'.DS.$yuming.'qcode'.DS.$tid.DS.'mht'.$quid.'.jpg';//小头像
					$fop->checkDir($f5);
					$imgobj->thumb($f4,$f5,105,105);
					if(file_exists($f5)){
						if($this->mark_img($f3,$f5,$xyy[0][0],$xyy[0][1])=="true"){
							if($this->mark_txt($f3,$nickname,$xyy[1][0],$xyy[1][1])=="true"){
								$ff3 =  'http://'.$_SERVER['SERVER_NAME'].'/photos'.DS.$yuming.'qcode'.DS.$tid.DS.'ms'.$quid.'.jpg';//原图
								$ff3 = str_replace("\\","/",$ff3);
							}
						}
					}
				}
			}
		}else{
			exit('生成图片失败，请联系网站管理员解决此问题！');
		}	
	}

		$this->set('img',$ff3);
		$this->set('rank',$rank);
		$this->title("推广二维码".' - '.$GLOBALS['LANG']['site_name']);
		$mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';
		$this->template($mb.'/erweima');
	}
	
	//获取access_token
	function _get_access_token(){
			$t = $this->_return_px();
			$cache = Import::ajincache();
			$cache->SetFunction(__FUNCTION__);
			$cache->SetMode('sitemes'.$t);
			$fn = $cache->fpath(func_get_args());
			if(file_exists($fn)&& (mktime() - filemtime($fn) < 7100) ){
				include($fn);
			}else{
				$rr = $this->_get_appid_appsecret();
				$url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$rr['appid'].'&secret='.$rr['appsecret'];
				$con = $this->curlGet($url);
				$json=json_decode($con);
				$rt = $json->access_token; //获取 access_token
				$cache->write($fn, $rt,'rt');
		   }
		   return $rt;
	}
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
					$sql = "SELECT appid,appsecret FROM `{$this->App->prefix()}wxuserset` LIMIT 1";
					$rr = $this->App->findrow($sql);
					$rt['appid'] = $rr['appid'];
					$rt['appsecret'] = $rr['appsecret'];
					
					$cache->write($fn, $rt,'rt');
		   }
		   return $rt;
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
	
	function get_site_name(){
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
					$sql = "SELECT site_name FROM `{$this->App->prefix()}systemconfig` WHERE type='basic' LIMIT 1";
        			$rt = $this->App->findvar($sql);
					
					$cache->write($fn, $rt,'rt');
		   }
		   return $rt;
				
	}
	function mark_img($image_dir='',$formname = '',$x=0,$y=0){
		$iinfo=getimagesize($image_dir);
		$nimage=imagecreatetruecolor($iinfo[0],$iinfo[1]);
		$white=imagecolorallocate($nimage,255,255,255);
		$black=imagecolorallocate($nimage,0,0,0);
		$red=imagecolorallocate($nimage,255,0,0);
		
		$simage =imagecreatefromjpeg($image_dir);

		imagecopy($nimage,$simage,0,0,0,0,$iinfo[0],$iinfo[1]);
			
		$inn=getimagesize($formname);  

		$in=@imagecreatefromJPEG($formname);

		$wh = imagecolorallocate($in, 255, 255, 255); 
		imagecolortransparent($in,$wh);   
		imagecopy($nimage,$in,$x,$y,0,0,$inn[0],$inn[1]);
		imagedestroy($in);

		imagejpeg($nimage,$image_dir);
		return "true";
	}
	function mark_txt($image_dir='',$formname = '',$x=0,$y=0){
		$image_dir = Import::gz_iconv()->ec_iconv('UTF8', 'GB2312',$image_dir);
		$iinfo=getimagesize($image_dir);
		$nimage=imagecreatetruecolor($iinfo[0],$iinfo[1]);
		$white=imagecolorallocate($nimage,255,255,255);
		$black=imagecolorallocate($nimage,131,24,30);
		$red=imagecolorallocate($nimage,255,0,0);
		
		$font = SYS_PATH.'data'.DS.'simhei.ttf'; //定义字体  
			
		$simage =imagecreatefromjpeg($image_dir);
		
		imagecopy($nimage,$simage,0,0,0,0,$iinfo[0],$iinfo[1]);
		
		//imagestring($nimage,5,300,50,$formname,$black);
		imagettftext($nimage,20,0,$x,$y,$black, $font, $formname);
		imagejpeg($nimage,$image_dir);
		

		return "true";
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
		if($showError=='10'){ return $tmpInfo; exit;}
		
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
	
}
?>
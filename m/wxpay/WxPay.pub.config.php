<?php
/**
* 	配置账号信息
*/
$pay = $app->action('shopping','_get_payinfo',4);
$rt = unserialize($pay['pay_config']);
define('JXB_APPID', $pay['appid']);
define('JXB_APPSECRET', $pay['appsecret']);
define('JXB_KEY', $rt['pay_code']);
define('JXB_MCHID', $rt['pay_no']);
define('apiclient_cert',SYS_PATH_WAP.'wxpay/cacert/apiclient_cert.pem');
define('apiclient_key',SYS_PATH_WAP.'wxpay/cacert/apiclient_key.pem');
define('JS_API_CALL',ADMIN_URL.'js_api_call.php');
define('NOTIFY_UU',ADMIN_URL.'notify_url.php');

class WxPayConf_pub
{
	//=======【基本信息设置】=====================================
	//微信公众号身份的唯一标识。审核通过后，在微信发送的邮件中查看
	const APPID = JXB_APPID;
	//受理商ID，身份标识
	const MCHID = JXB_MCHID;
	//商户支付密钥Key。审核通过后，在微信发送的邮件中查看
	const KEY = JXB_KEY;
	//JSAPI接口中获取openid，审核后在公众平台开启开发模式后可查看
	const APPSECRET = JXB_APPSECRET;
	
	//=======【JSAPI路径设置】===================================
	//获取access_token过程中的跳转uri，通过跳转将code传入jsapi支付页面
	const JS_API_CALL_URL = JS_API_CALL;
	
	//=======【证书路径设置】=====================================
	//证书路径,注意应该填写绝对路径
	const SSLCERT_PATH = apiclient_cert;
	const SSLKEY_PATH = apiclient_key;
	
	//=======【异步通知url设置】===================================
	//异步通知url，商户根据实际开发过程设定
	const NOTIFY_URL = NOTIFY_UU;

	//=======【curl超时设置】===================================
	//本例程通过curl使用HTTP POST方法，此处可修改其超时时间，默认为30秒
	const CURL_TIMEOUT = 30;
	
}
	
?>
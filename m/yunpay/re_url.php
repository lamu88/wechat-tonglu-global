<?php

/* *

 * 功能：服务器同通知页面

 */
require_once('../load.php');
require_once("lib/i2e.class.php");
$cm->query("SELECT * FROM gz_payment where pay_id='6'");
$row = $cm->fetch_array($rs);
if($row['pay_id']!='6'){
	echo tiao('你的操作有误！','/m/');	
	die();
}
$rt = unserialize($row['pay_config']);
//↓↓↓↓↓↓↓↓↓↓请在这里配置您的基本信息↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓
//合作身份者id
$yun_config['partner']		= $rt['pay_code'];

//安全检验码
$yun_config['key']			= $rt['pay_idt'];

//云会员账户（邮箱）
$seller_email = $rt['pay_no'];


//↑↑↑↑↑↑↑↑↑↑请在这里配置您的基本信息↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑

require_once("lib/yun_md5.function.php");
?>

<!DOCTYPE HTML>

<html>

    <head>

	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<?php

//计算得出通知验证结果

$yunNotify = md5Verify($_REQUEST['i1'],$_REQUEST['i2'],$_REQUEST['i3'],$yun_config['key'],$yun_config['partner']);

if($yunNotify) {//验证成功

	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



	//商户订单号



	$out_trade_no = $_REQUEST['i2'];



	//云支付交易号



	$trade_no = $_REQUEST['i4'];



	//价格

	$yunprice=$_REQUEST['i1'];
	$rst=$cm->query("SELECT * FROM gz_goods_order_info where order_sn ='".$out_trade_no."'");
	$rowt = $cm->fetch_array($rst);
	if($rowt['pay_status']=='0'){
		//@$cm->query("UPDATE gz_goods_order_info SET order_status='2',pay_status='1' WHERE order_sn ='".$out_trade_no."'");
		$app->action('shopping','pay_successs_tatus2',array('order_sn'=>$out_trade_no,'status'=>'1'));//修改支付状态
	}
		echo tiao('PAY OK','/m/');	
		die();
    /*

加入您的入库及判断代码;

判断返回金额与实金额是否想同;

判断订单当前状态;

完成以上才视为支付成功

*/

	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

}

else {

    //验证失败

    echo "验证失败";

}

?>

        <title>云支付接口</title>

	</head>

    <body>

    </body>

</html>
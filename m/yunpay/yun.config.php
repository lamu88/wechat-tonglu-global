<?php
require_once('../load.php');

$pay_config = $app->action('shopping','_get_payinfo',6);
$rt = unserialize($pay_config);

//↓↓↓↓↓↓↓↓↓↓请在这里配置您的基本信息↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓
//合作身份者id
$yun_config['partner']		= $rt['pay_code'];

//安全检验码
$yun_config['key']			= $rt['pay_idt'];

//云会员账户（邮箱）
$seller_email = $rt['pay_no'];


//↑↑↑↑↑↑↑↑↑↑请在这里配置您的基本信息↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑

?>
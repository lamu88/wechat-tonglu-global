<?php
require_once('load.php');
/*include(SYS_PATH.'inc/phpqrcode.php');
// 二维码数据
$data = 'http://s.bookphone.cn';
// 生成的文件名
$filename = 'photos/1111.png';
// 纠错级别：L、M、Q、H
$errorCorrectionLevel = 'L';
// 点的大小：1到10
$matrixPointSize = 4;
QRcode::png($data, $filename, $errorCorrectionLevel, $matrixPointSize, 2);*/
$app->action('page','test');
?>
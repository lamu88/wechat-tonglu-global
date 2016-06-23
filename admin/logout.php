<?php
require_once("load.php");

$app->action('manager','logout');
echo '<script> window.parent.parent.location="login.php"; </script>';
//$app->jump('login.php');
exit;

?>
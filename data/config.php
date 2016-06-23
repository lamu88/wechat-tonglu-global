<?php
// host $OPENSHIFT_MYSQL_DB_HOST
$db_host   = "$OPENSHIFT_MYSQL_DB_HOST";
// database name   
$db_name   = "$OPENSHIFT_APP_NAME";
// database username
$db_user   = "$OPENSHIFT_MYSQL_DB_USERNAME"; 
// database password
$db_pass   = "$OPENSHIFT_MYSQL_DB_PASSWORD"; 

$prefix    = "gz_";

date_default_timezone_set('Asia/Shanghai');
?>
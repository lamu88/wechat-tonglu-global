<?php 
require_once('load.php');
require_once(SYS_PATH_ADMIN.'inc'.DS.'menulist.php');
$permi_var = $app->action('manager','admin_Permissions');
$Permissions = !empty($permi_var) ? @explode("+",$permi_var) : array();
?>
<script type="text/javascript">
// 导航栏配置文件
var outlookbar=new outlook();
var t;
<?php
foreach($menu as $k=>$row){
//$k++;
if(!in_array($row['big_key'],$Permissions)) continue;
echo "t=outlookbar.addtitle('".$row['small_mod']."','".$row['big_mod']."',1)\n";
if(!empty($row['sub_mod'])){
foreach($row['sub_mod'] as $kk=>$rows){
//$kk++;
if(!in_array($rows['en_name'],$Permissions)) continue;
echo "outlookbar.additem('".$rows['name']."',t,'".$rows['url']."')\n";
}
}
echo "\n\n";
}
?>
//t=outlookbar.addtitle('定制网站','定制网站',1)
//outlookbar.additem('定制网站',t,'systemconfig.php?type=custom_menu')
</script>
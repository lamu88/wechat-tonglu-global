<?php
 require_once(dirname(__FILE__)."/menulist.php");
 $groupname_arr = array();
 $groupname_arr2_sub = array();
 if(!empty($menu)){
	 foreach($menu as $row){
		$groupname_arr[$row['big_key']] = $row['big_mod'];
		foreach($row['sub_mod'] as $rows){
			$groupname_arr2_sub[$row['big_key']][$rows['en_name']] = $rows['name'];
		}
	 }
 }
?>
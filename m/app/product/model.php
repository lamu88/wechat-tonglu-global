<?php
class ProductModel extends Model{
	 //购买记录
	function __get_buyhistory($gid,$start=0,$list=10){
			 if(empty($gid)) return array();
			 $sql = "SELECT distinct og.goods_id, u.user_name, og.goods_number, oi.add_time, IF(oi.order_status IN (2, 3 ,4), 0, 1) AS order_status FROM `{$this->prefix()}goods_order_info` AS oi";
			 $sql .=" LEFT JOIN `{$this->prefix()}user` AS u ON oi.user_id = u.user_id ";
			 $sql .=" LEFT JOIN `{$this->prefix()}goods_order` AS og ON oi.order_id = og.order_id";
			 $sql .=" WHERE oi.order_id = og.order_id AND og.goods_id ='$gid' ORDER BY oi.add_time DESC LIMIT $start,$list";
			 return  $this->find($sql);
	}

	//购买记录数量
	function __get_buyhistory_count($gid=0){
			 if(empty($gid)) return 0;
			 $sql = "SELECT COUNT(distinct og.goods_id) FROM `{$this->prefix()}goods_order_info` AS oi";
			 $sql .=" LEFT JOIN `{$this->prefix()}user` AS u ON oi.user_id = u.user_id ";
			 $sql .=" LEFT JOIN `{$this->prefix()}goods_order` AS og ON oi.order_id = og.order_id";
			 $sql .=" WHERE oi.order_id = og.order_id AND og.goods_id ='$gid'";
			 return $this->findvar($sql);
	}

	//获取分类下的商品[商品详情页面]
	function __get_categoods_list_goods($cid_arr=array(),$start=0,$list=10){
		 if(empty($cid_arr)) return array();
		 $sql = "SELECT * FROM `{$this->prefix()}goods` WHERE cat_id IN(".implode(',',$cid_arr).") ORDER BY goods_id DESC LIMIT $start,$list";
	 return $this->find($sql);
	}

	//获取分类下的商品数量[商品详情页面]
	function __get_categoods_count_goods($cid_arr=array()){
		 if(empty($cid_arr)) 0;
		 $sql = "SELECT COUNT(goods_id) FROM `{$this->prefix()}goods` WHERE cat_id IN(".implode(',',$cid_arr).")";
	 return $this->findvar($sql);
	}
}
?>
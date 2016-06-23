<?php
class BrandModel extends Model{
		function __get_goods_count_category($data=array()){
			  $w = $this->__category_goods_where($data);
			  $sql = "SELECT COUNT(distinct g.goods_id) FROM `{$this->prefix()}goods` AS g";
			  $sql .=" LEFT JOIN `{$this->prefix()}brand` AS b ON g.brand_id = b.brand_id";
			  $sql .=" LEFT JOIN `{$this->prefix()}goods_cate` AS gc ON gc.cat_id = g.cat_id";
			  $sql .=" LEFT JOIN `{$this->prefix()}category_sub_goods` AS csg ON g.goods_id=csg.goods_id";
			  $sql .=" $w";
			  return $this->findvar($sql);
		}
		function __get_categoods_list_category($data,$orderby,$start,$list){
			$w = $this->__category_goods_where($data);
			/**************  look修改 开始 * ************************/
			$sql = "SELECT distinct g.goods_id, g.goods_name,g.shop_price, g.market_price, g.goods_thumb,g.goods_img,g.promote_start_date,g.promote_end_date,g.promote_price,g.is_promote,g.qianggou_price, g.qianggou_start_date, g.qianggou_end_date,g.is_qianggou,g.sale_count,g.goods_unit,g.goods_weight,g.goods_brief,g.goods_number,b.brand_name,b.brand_id,gc.cat_name FROM `{$this->prefix()}goods` AS g";
			/**************  look修改 结束 * ************************/
			
			$sql .=" LEFT JOIN `{$this->prefix()}brand` AS b ON g.brand_id = b.brand_id";
			$sql .=" LEFT JOIN `{$this->prefix()}goods_cate` AS gc ON gc.cat_id = g.cat_id";
			$sql .=" LEFT JOIN `{$this->prefix()}category_sub_goods` AS csg ON g.goods_id=csg.goods_id";
			$sql .=" $w $orderby LIMIT $start, $list";
			$rt = $this->find($sql);
			$goodslist = array();
			if(!empty($rt)){
				foreach($rt as $k=>$row){
					$goodslist[$k] = $row;
					$goodslist[$k]['url'] = get_url($row['goods_name'],$row['goods_id'],'product.php?id='.$row['goods_id'],'goods',array('product','index',$row['goods_id']));
					$goodslist[$k]['goods_thumb'] =  is_file(SYS_PATH.$row['goods_thumb']) ? SITE_URL.$row['goods_thumb'] : SITE_URL.'theme/images/no_picture.gif';
					$goodslist[$k]['goods_img'] =  is_file(SYS_PATH.$row['goods_img']) ? SITE_URL.$row['goods_img'] : SITE_URL.'theme/images/no_picture.gif';
					$goodslist[$k]['original_img'] =  is_file(SYS_PATH.$row['original_img']) ? SITE_URL.$row['original_img'] : SITE_URL.'theme/images/no_picture.gif';
				}
				unset($rt);
			}
			return $goodslist;
		}
		
		function __category_goods_where($data= array()){
			if(empty($data)) return "";
			$cid = isset($data['cid'])&&intval($data['cid'])>0 ? intval($data['cid']) : 0;
			$bid = isset($data['bid'])&&intval($data['bid'])>0 ? intval($data['bid']) : 0;
			
			$comd[] = "g.is_on_sale = '1' AND g.is_delete = '0' AND g.is_alone_sale='1'";
			
			//品牌
			if($bid>0){ //子分类
				$sub_bid = $this->get_brand_sub_cat_ids_model($bid); //子分类id
			    $comd[] = "(b.brand_id".db_create_in($sub_bid).")"; 
				unset($sub_bid);
			}
			
			if($cid>0){ //子分类
				$sub_cid = $this->get_goods_sub_cat_ids_model($cid); //子分类id
			    $comd[] = "(g.cat_id".db_create_in($sub_cid)." OR csg.cat_id='$cid')"; 
				unset($sub_cid);
			}
			
			$w = "";
			if(!empty($comd)){
				 $w = "WHERE ".implode(' AND ',$comd);
			}
			return  $w;
	}
	
	//获商品子自分类cat_id
	function get_goods_sub_cat_ids_model($cid=0){
		$rts = $this->get_goods_cate_tree_model($cid);
		$cids[] = $cid;
		if(!empty($rts)){
			foreach($rts as $row){
				$cids[] = $row['id'];
				if(!empty($row['cat_id'])){
					foreach($row['cat_id'] as $rows){
						$cids[] = $rows['id'];
						if(!empty($rows['cat_id'])){
							foreach($rows['cat_id'] as $rowss){
								$cids[] = $rowss['id'];
							} // end foreach
						} // end if
					} // end foreach
				} // end if
			} // end foreach
		}// end if
		return $cids;
	}
	
	//获取商品分类
	function get_goods_cate_tree_model($cid = 0)
	{
		$three_arr = array();
		$sql = 'SELECT count(cat_id) FROM `'.$this->prefix()."goods_cate` WHERE parent_id = '$cid' AND is_show = 1";
		if ($this->findvar($sql) || $cid == 0)
		{
			$sql = 'SELECT cat_name,cat_id,parent_id FROM `'.$this->prefix()."goods_cate` AS tb1";
			$sql .= " WHERE parent_id = '$cid' ORDER BY parent_id ASC,sort_order ASC, cat_id ASC";
			$res = $this->find($sql); 
			foreach ($res as $row)
			{
			   $three_arr[$row['cat_id']]['id']   = $row['cat_id'];
			   $three_arr[$row['cat_id']]['name'] = $row['cat_name'];
			   
			    if (isset($row['cat_id']) != NULL)
				{
					 $three_arr[$row['cat_id']]['cat_id'] = $this->get_goods_cate_tree_model($row['cat_id']);
				}
			}
		}
		return $three_arr;
	}
	
	/*
	品牌的分类
	*/
	function get_brand_sub_cat_ids_model($bid=0){
		$rts = $this->get_brand_cate_tree_model($bid);
		$bids[] = $bid;
		if(!empty($rts)){
			foreach($rts as $row){
				$bids[] = $row['id'];
				if(!empty($row['brand_id'])){
					foreach($row['brand_id'] as $rows){
						$bids[] = $rows['id'];
						if(!empty($rows['brand_id'])){
							foreach($rows['brand_id'] as $rowss){
								$bids[] = $rowss['id'];
							} // end foreach
						} // end if
					} // end foreach
				} // end if
			} // end foreach
		}// end if
		return $bids;
	}
	
	//获取商品品牌分类
	function get_brand_cate_tree_model($bid = 0)
	{
		$three_arr = array();
		$sql = 'SELECT count(brand_id) FROM `'.$this->prefix()."brand` WHERE parent_id = '$bid' AND is_show = '1'";
		if ($this->findvar($sql) || $bid == 0)
		{
			$sql = 'SELECT brand_name,brand_id,parent_id FROM `'.$this->prefix()."brand`";
			$sql .= " WHERE parent_id = '$bid' ORDER BY parent_id ASC,sort_order ASC, brand_id ASC";
			$res = $this->find($sql);
			foreach ($res as $row)
			{
			   $three_arr[$row['brand_id']]['id']   = $row['brand_id'];
			   $three_arr[$row['brand_id']]['name'] = $row['brand_name'];
			    if (isset($row['brand_id']) != NULL)
				{
					 $three_arr[$row['brand_id']]['brand_id'] = $this->get_brand_cate_tree($row['brand_id']);
				}
			}
		}
		return $three_arr;
	}
}
?>
<?php
class CommonController extends Controller{
    /*
     * @Photo Index
     * @param <type> $page
     * @param <type> $type
     */
	 //构造函数，自动新建对象
 	function  __construct() {
		/*
		*构造函数
		*/
	}
	
   /*
     * 自定义大小验证码函数
     * @$num:字符数
     * @$size:大小
     * @$width,$height:不设置会自动
     */
    function vCode($num=4,$size=18, $width=0,$height=0){
        !$width && $width = $num*$size*4/5-2;
        !$height && $height = $size + 8;
        // 去掉了 0 1 O l 等
            $str = "23456789abcdefghijkmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVW";
            $code = '';
            for ($i=0; $i<$num; $i++){
                    $code.= $str[mt_rand(0, strlen($str)-1)];
            }
			//写入session
			$this->Session->write('vifcode',$code);
            // 画图像
            $im = imagecreatetruecolor($width,$height);
            // 定义要用到的颜色
            $back_color = imagecolorallocate($im, 235, 236, 237);
            $boer_color = imagecolorallocate($im, 118, 151, 199);
            $text_color = imagecolorallocate($im, mt_rand(0,200), mt_rand(0,120), mt_rand(0,120));

            // 画背景
            imagefilledrectangle($im,0,0,$width,$height,$back_color);
            // 画边框
            imagerectangle($im,0,0,$width-1,$height-1,$boer_color);
            // 画干扰线
            for($i=0;$i<5;$i++){
                $font_color = imagecolorallocate($im, mt_rand(0,255), mt_rand(0,255), mt_rand(0,255));
                imagearc($im,mt_rand(-$width,$width),mt_rand(-$height,$height),mt_rand(30,$width*2),mt_rand(20,$height*2),mt_rand(0,360),mt_rand(0,360),$font_color);
            }
        // 画干扰点
        for($i=0;$i<50;$i++){
                $font_color = imagecolorallocate($im, mt_rand(0,255), mt_rand(0,255), mt_rand(0,255));
                imagesetpixel($im,mt_rand(0,$width),mt_rand(0,$height),$font_color);
        }
        // 画验证码
        @imagefttext($im, $size , 0, 5, $size+3, $text_color, SYS_PATH.'data/monofont.ttf',$code);
        header("Cache-Control: max-age=1, s-maxage=1, no-cache, must-revalidate");
        header("Content-type: image/png");
        imagepng($im);
        imagedestroy($im);
    }
	
	function showdiv($thisurl=""){
	    $this->set('thisurl',$thisurl);
		$this->template('showdiv');
		exit;
	}
	
	//获文章子自分类cat_id
	function get_sub_cat_ids($cid=0,$type=""){
		$rts = $this->get_cate_tree($cid,$type);
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
	
	//获取文章分类
	function get_cate_tree($cid = 0,$type="")
	{
		if(!empty($type)){
			$typ = " AND type='$type'";
			$type = " AND tb1.type='$type'";
		}
		$three_arr = array();
		$sql = 'SELECT count(cat_id) FROM `'.$this->App->prefix()."article_cate` WHERE parent_id = '$cid' $typ";
		if ($this->App->findvar($sql) || $cid == 0)
		{
			$sql = 'SELECT tb1.cat_name,tb1.cat_id,tb1.parent_id,tb1.is_show,tb1.cat_title,tb1.meta_desc, tb1.meta_keys,tb1.show_in_nav,tb1.addtime,tb1.cat_img,tb1.vieworder, COUNT(tb2.cat_id) AS article_count FROM `'.$this->App->prefix()."article_cate` AS tb1";
			$sql .=" LEFT JOIN `{$this->App->prefix()}article` AS tb2";
			$sql .=" ON tb1.cat_id = tb2.cat_id";
			$sql .= " WHERE tb1.parent_id = '$cid' $type GROUP BY tb1.cat_id ORDER BY tb1.parent_id ASC,tb1.vieworder ASC, tb1.cat_id ASC";
			$res = $this->App->find($sql); 
			foreach ($res as $row)
			{
			   $three_arr[$row['cat_id']]['id']   = $row['cat_id'];
			   $three_arr[$row['cat_id']]['name'] = $row['cat_name'];
			   $three_arr[$row['cat_id']]['is_show']   = $row['is_show'];
			   $three_arr[$row['cat_id']]['show_in_nav'] = $row['show_in_nav'];
			   $three_arr[$row['cat_id']]['cat_title']   = $row['cat_title'];
			   $three_arr[$row['cat_id']]['addtime'] = $row['addtime'];
			   $three_arr[$row['cat_id']]['cat_img'] = $row['cat_img'];
			   $three_arr[$row['cat_id']]['vieworder'] = $row['vieworder'];
			   $three_arr[$row['cat_id']]['article_count'] = $row['article_count'];
			   $three_arr[$row['cat_id']]['meta_keys'] = $row['meta_keys'];
			   $three_arr[$row['cat_id']]['meta_desc'] = $row['meta_desc'];
			   
			    if (isset($row['cat_id']) != NULL)
				{
					 $three_arr[$row['cat_id']]['cat_id'] = $this->get_cate_tree($row['cat_id']);
				}
			}
		}
		return $three_arr;
	}
	
	##################以下来自商品的分类#########################
	//获商品子自分类cat_id
	function get_goods_sub_cat_ids($cid=0){
		//if(!($cid>=0)) return false;
		$rts = $this->get_goods_cate_tree($cid);
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
								if(!empty($rowss['cat_id'])){
									foreach($rowss['cat_id'] as $rowsss){
										$cids[] = $rowsss['id'];
									} // end foreach
								} // end if
							} // end foreach
						} // end if
					} // end foreach
				} // end if
			} // end foreach
		}// end if
		return $cids;
	}
	
	//获取商品分类
	function get_goods_cate_tree($cid = 0)
	{
		$three_arr = array();
		$sql = 'SELECT count(cat_id) FROM `'.$this->App->prefix()."goods_cate` WHERE parent_id = '$cid' AND is_show = 1";
		if ($this->App->findvar($sql) || $cid == 0)
		{
			$sql = 'SELECT tb1.cat_name,tb1.cat_id,tb1.parent_id,tb1.is_show,tb1.is_index,tb1.cat_title,tb1.cat_desc, tb1.keywords,tb1.show_in_nav,tb1.sort_order, COUNT(tb2.cat_id) AS goods_count FROM `'.$this->App->prefix()."goods_cate` AS tb1";
			$sql .=" LEFT JOIN `{$this->App->prefix()}goods` AS tb2";
			$sql .=" ON tb1.cat_id = tb2.cat_id";
			$sql .= " WHERE tb1.parent_id = '$cid' GROUP BY tb1.cat_id ORDER BY tb1.parent_id ASC,tb1.sort_order ASC, tb1.cat_id ASC";
			$res = $this->App->find($sql); 
			foreach ($res as $row)
			{
			   $three_arr[$row['cat_id']]['id']   = $row['cat_id'];
			   $three_arr[$row['cat_id']]['parent_id']   = $row['parent_id'];
			   $three_arr[$row['cat_id']]['name'] = $row['cat_name'];
			   $three_arr[$row['cat_id']]['is_show']   = $row['is_show'];
			   $three_arr[$row['cat_id']]['is_index']   = $row['is_index'];
			   $three_arr[$row['cat_id']]['show_in_nav'] = $row['show_in_nav'];
			   $three_arr[$row['cat_id']]['cat_title']   = $row['cat_title'];
			   $three_arr[$row['cat_id']]['sort_order'] = $row['sort_order'];
			   $three_arr[$row['cat_id']]['goods_count'] = $row['goods_count'];
			   $three_arr[$row['cat_id']]['keywords'] = $row['keywords'];
			   $three_arr[$row['cat_id']]['cat_desc'] = $row['cat_desc'];
			   
			    if (isset($row['cat_id']) != NULL)
				{
					 $three_arr[$row['cat_id']]['cat_id'] = $this->get_goods_cate_tree($row['cat_id']);
				}
			}
		}
		return $three_arr;
	}
	
	/**
	 * 获得指定分类的所有上级分类
	 *
	 * @access  public
	 * @param   integer $cat    分类编号
	 * @return  array
	 */
	function get_goods_parent_cats($cat)
	{
		if ($cat == 0)
		{
			return array();
		}
		$arr = $this->App->find("SELECT cat_id, cat_name,ctype, parent_id,cat_title FROM `{$this->App->prefix()}goods_cate`");
		if (empty($arr))
		{
			return array();
		}
		$index = 0;
		$cats  = array();
		while (1)
		{
			foreach ($arr AS $row)
			{
				if ($cat == $row['cat_id']) //如果当前父类的di==当前ID，那么将它写入数据
				{
					$cat = $row['parent_id'];  //将父类ID设为当前id
					$cats[$index]['cat_id']   = $row['cat_id'];
                    $cats[$index]['cat_title']   = $row['cat_title'];
					$cats[$index]['cat_name'] = $row['cat_name'];
					$cats[$index]['ctype'] = $row['ctype'];
					$index++;
					break;
				}
			}
			if ($index == 0 || $cat == 0)
			{
				break;
			}
		}
		return $cats;
	}

	/*
	品牌的分类
	*/
	function get_brand_sub_cat_ids($bid=0){
		//if(!($cid>=0)) return false;
		$rts = $this->get_brand_cate_tree($bid);
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
	
	//获取商品分类
	function get_brand_cate_tree($bid = 0)
	{
		$three_arr = array();
		$sql = 'SELECT count(brand_id) FROM `'.$this->App->prefix()."brand` WHERE parent_id = '$bid' AND is_show = '1'";
		if ($this->App->findvar($sql) || $bid == 0)
		{
			$sql = 'SELECT tb1.brand_name,tb1.brand_id,tb1.parent_id,tb1.is_show,tb1.is_promote,tb1.is_hot,tb1.brand_title,tb1.meta_desc, tb1.meta_keys,tb1.sort_order,tb1.brand_logo, tb1.site_url,COUNT(tb2.brand_id) AS goods_count FROM `'.$this->App->prefix()."brand` AS tb1";
			$sql .=" LEFT JOIN `{$this->App->prefix()}goods` AS tb2";
			$sql .=" ON tb1.brand_id = tb2.brand_id";
			$sql .= " WHERE tb1.parent_id = '$bid' GROUP BY tb1.brand_id ORDER BY tb1.parent_id ASC,tb1.sort_order ASC, tb1.brand_id ASC";
			$res = $this->App->find($sql);
			foreach ($res as $row)
			{
			   $three_arr[$row['brand_id']]['id']   = $row['brand_id'];
			   $three_arr[$row['brand_id']]['name'] = $row['brand_name'];
			   $three_arr[$row['brand_id']]['is_show']   = $row['is_show'];
			   $three_arr[$row['brand_id']]['is_hot']   = $row['is_hot'];
			   $three_arr[$row['brand_id']]['is_promote']   = $row['is_promote'];
			   $three_arr[$row['brand_id']]['brand_title']   = $row['brand_title'];
			   $three_arr[$row['brand_id']]['sort_order'] = $row['sort_order'];
			   $three_arr[$row['brand_id']]['goods_count'] = $row['goods_count'];
			   $three_arr[$row['brand_id']]['meta_keys'] = $row['meta_keys'];
			   $three_arr[$row['brand_id']]['meta_desc'] = $row['meta_desc'];
			   $three_arr[$row['brand_id']]['brand_logo'] = $row['brand_logo'];
			   $three_arr[$row['brand_id']]['site_url'] = $row['site_url'];
			   
			    if (isset($row['brand_id']) != NULL)
				{
					 $three_arr[$row['brand_id']]['brand_id'] = $this->get_brand_cate_tree($row['brand_id']);
				}
			}
		}
		return $three_arr;
	}
}


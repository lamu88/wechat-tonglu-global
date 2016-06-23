<?php
class BrandController extends Controller{

 	function  __construct() {

	}
	//获取品牌列表
	function get_brand_list($cid=0,$list=0){
		$w = " WHERE tb1.is_show='1'";
		if($cid>0){
			$subcid = $this->action('catalog','get_goods_sub_cat_ids',$cid);
		 	$w .=" AND tb2.cat_id".db_create_in($subcid);
		}
		$s = "";
		if($list>0) $s = "LIMIT $list";
		$sql = "SELECT distinct tb1.brand_name, tb1.brand_id,tb1.brand_name,tb1.brand_banner,tb1.brand_logo FROM `{$this->App->prefix()}brand` AS tb1";
		$sql .=" LEFT JOIN `{$this->App->prefix()}goods` AS tb2 ON tb1.brand_id= tb2.brand_id".$w." ORDER BY tb1.sort_order ASC,tb1.brand_id $s";
		$rt =  $this->App->find($sql);
		$rts = array();
		if(!empty($rt)){
			foreach($rt as $k=>$row){
				$rts[$k] = $row;
				$rts[$k]['url'] = get_url($row['brand_name'],$row['brand_id'],"brand.php?bid=".$row['brand_id'],'brand',array('brand','index',$row['brand_id']));
				$rts[$k]['brand_banner'] = !empty($row['brand_banner']) ? SITE_URL.$row['brand_banner'] : "";
				$rts[$k]['brand_logo'] = !empty($row['brand_logo']) ? SITE_URL.$row['brand_logo'] : "";
			}
			unset($rt);
		} 
		return $rts;
	}
	###########################################################
	###                                                    ####
	###########################################################
	function index($bid=0,$cid,$page=1){
		$this->css('catalog.css');
		$rt = $this->Cache->read(3600);
	 	if(is_null($rt)) {
			if($bid>0){ //品牌商品
				//品牌信息
				if($bid>0){
					$sql = "SELECT * FROM `{$this->App->prefix()}brand` WHERE brand_id='$bid' LIMIT 1";
					$rt['brandinfo'] = $this->App->findrow($sql); 
					if(empty($rt['brandinfo'])){
					$this->action("common","show404tpl"); //404页面
					}
				}else{
					$this->action("common","show404tpl"); //404页面
				}
				
				//当前位置
				$hear[] = '<a href="'.SITEURL.'">首页</a>';
				$hear[] = '<a href="'.get_url('',0,SITE_URL.'brand.php','brand',array('brand','index')).'">品牌专场</a>';
				$rts_ = $this->get_brand_parent_cats($bid); //父类ID
				$rts = Import::basic()->array_sort($rts_,'brand_id'); //根据brand_id排序
				if(!empty($rts)){
					$perend_id = $rts[count($rts)-1]['brand_id']; 
					foreach($rts as $rows){
						$hear[] = '<a href="'.get_url($rows['brand_name'],$rows['brand_id'],SITE_URL."brand.php?bid=".$rows["brand_id"],'brand',array('brand','index',$rows['brand_id'])).'">'.$rows['brand_name'].'</a>';
					}
				}
				unset($rts,$rts_);
				if(!empty($hear)){
					$rt['hear'] = implode('&nbsp;&gt;&nbsp;',$hear);
				}else{
					$rt['hear'] = "";
				}
				
				//定义能够排序的字段
				$order = array('goods_id','click_count','shop_price','is_best','is_new','is_hot','is_promote','sale_count','add_time');
				$orderby = "";
				if(isset($_GET['desc'])){
						 if(in_array($_GET['desc'],$order)){
							$orderby = ' ORDER BY g.'.$_GET['desc'].' DESC';
						 }
						 $order_type = trim($_GET['desc']);
						 $sort_type = 'DESC';
				}else if(isset($_GET['asc'])){
						 if(in_array($_GET['asc'],$order)){
							$orderby = ' ORDER BY g.'.$_GET['asc'].' ASC';
						 }
						 $order_type = trim($_GET['asc']);
						 $sort_type = 'ASC';
				}else {
						 $orderby = ' ORDER BY g.sort_order ASC , g.goods_id DESC';
						 $order_type = 'goods_id';
						 $sort_type = 'DESC';
				}
				
				//条件
				$comd = array('cid'=>$cid,'bid'=>$bid); //需要的话继续增加
				//分页
				if(empty($page)){
					   $page = 1;
				}
				$list = intval($list)>0 ?  intval($list) : 24 ; //每页显示多少个
				$start = ($page-1)*$list;
				
				$tt = $this->App->__get_goods_count_category($comd); //获取商品的数量
				$rt['goods_count'] = $tt;
				//$rt['categoodspage'] = Import::basic()->ajax_page($tt,$list,$page,'get_categoods_page_list',array($cid,$bid,$price,$order_type,$sort_type,$list));
				$rt['categoodspage'] = Import::basic()->getpage($tt, $list, $page,'?page=',true);
				$rt['categoodslist'] = $this->App->__get_categoods_list_category($comd,$orderby,$start,$list); //商品列表
				
				if(!isset($_COOKIE['DISPLAY_TYPE'])||empty($_COOKIE['DISPLAY_TYPE']) || !in_array($_COOKIE['DISPLAY_TYPE'],array('list','text'))){
						$rt['display'] = 'text';
				}else{
						$rt['display'] = $_COOKIE['DISPLAY_TYPE'];
				}
			
				//品牌分类
				$sql = "SELECT gc.cat_id,gc.cat_name,COUNT(g.brand_id) AS gcount FROM `{$this->App->prefix()}goods` AS g LEFT JOIN `{$this->App->prefix()}goods_cate` AS gc ON g.cat_id = gc.cat_id LEFT JOIN `{$this->App->prefix()}brand` AS b ON g.brand_id=b.brand_id WHERE g.is_on_sale='1' AND g.is_alone_sale='1' AND g.is_delete='0' AND g.brand_id='$bid' GROUP BY g.cat_id";
				$catebrand = $this->App->find($sql);
				$rt['catebrand'] = array();
				if(!empty($catebrand )){
					foreach($catebrand  as $k=>$row){
						$rt['catebrand'][$k] = $row;
						$rt['catebrand'][$k]['url'] = get_url($row['cat_name'],$row['cat_id'],SITE_URL."brand.php?cid=".$row['cat_id'].'&bid='.$bid,'brand',array('brand','index',$row['cat_id'],$bid));
					}
				}
				
				//热卖商品
				if($cid>0){
					$subc = $this->action('catalog','get_goods_sub_cat_ids',$cid);
					$c = " AND cat_id".db_create_in($subc);
					unset($subc);
				}
				$sql = "SELECT goods_id, goods_name,shop_price, market_price, goods_thumb,goods_img,original_img,promote_start_date,promote_end_date,promote_price,is_promote,qianggou_price, qianggou_start_date, qianggou_end_date,is_qianggou,sale_count FROM `{$this->App->prefix()}goods` WHERE brand_id='$bid'{$c} ORDER BY sale_count DESC,goods_id DESC LIMIT 7";
				$hotgoods = $this->App->find($sql);
				$rt['hotgoods'] = array();
				if(!empty($hotgoods)){
					foreach($hotgoods as $k=>$row){
							$rt['hotgoods'][$k] = $row;
							$rt['hotgoods'][$k]['url'] = get_url($row['goods_name'],$row['goods_id'],SITE_URL.'product.php?id='.$row['goods_id'],'goods',array('product','index',$row['goods_id']));
							$rt['hotgoods'][$k]['goods_thumb'] =  is_file(SYS_PATH.$row['goods_thumb']) ? SITE_URL.$row['goods_thumb'] : SITE_URL.'theme/images/no_picture.gif';
							$rt['hotgoods'][$k]['goods_img'] =  is_file(SYS_PATH.$row['goods_img']) ? SITE_URL.$row['goods_img'] : SITE_URL.'theme/images/no_picture.gif';
							$rt['hotgoods'][$k]['original_img'] =  is_file(SYS_PATH.$row['original_img']) ? SITE_URL.$row['original_img'] : SITE_URL.'theme/images/no_picture.gif';
							//促销 价格
							if($row['is_promote']=='1' && $row['promote_start_date']<mktime()&&$row['promote_end_date']>mktime()){
								$row['promote_price'] = format_price($row['promote_price']);
							}else{
								$row['promote_price'] = "0.00";
							}
							$rt['hotgoods'][$k]['promote_price'] = $row['promote_price'];
							//抢购价格
							if($row['is_qianggou']=='1' && $row['qianggou_start_date']<mktime()&&$row['qianggou_end_date']>mktime()){
								$row['qianggou_price'] = format_price($row['qianggou_price']);
							}else{
								$row['qianggou_price'] = "0.00";
							}
							$rt['hotgoods'][$k]['qianggou_price'] = $row['qianggou_price'];
					}
				}
				
				//分类下级
				$rt['sub_brand'] = $this->get_brand_cate_tree($bid);
			
			}else{ //品牌模块主页
				$rt['hotbrand'] = $this->__get_brand(0,'12','is_hot');
				
				$rt['promotebrand'] = $this->__get_brand(0,'12','is_promote');
				
				$rt['newbrand'] = $this->__get_brand(0,'12');
				//品牌咨询
				$sql = "SELECT tb1.article_title,tb1.article_id,tb1.article_img FROM `{$this->App->prefix()}article` AS tb1 LEFT JOIN `{$this->App->prefix()}article_cate` AS tb2 ON tb1.cat_id=tb2.cat_id WHERE tb1.cat_id = '78' LIMIT 6";
				$brandnew = $this->App->find($sql);
				$rt['brandnew'] = array();
				if(!empty($brandnew)){
					foreach($brandnew as $k=>$row){
					$rt['brandnew'][$k] = $row;
					$rt['brandnew'][$k]['url'] = get_url($row['article_title'],$row['article_id'],'new.php?id='.$row['article_id'],'article',array('new','article',$row['article_id']));
					}
				}
				
				$rt['brandcate'] = $this->get_brand_cate_tree();
				//print_r($rt['brandcate']);
			}
			
			//商品分类列表		
			//$rt['menu'] = $this->action('catalog','get_goods_cate_tree');
			
			$this->Cache->write($rt);
		}
			
		$this->set('rt',$rt);
		if($bid>0){ //品牌商品meta
			$title = !empty($rt['brandinfo']['brand_title']) ? $rt['brandinfo']['brand_title'] : $rt['brandinfo']['brand_name'];
			$this->title($title.' - '.$GLOBALS['LANG']['site_name']);
			$this->meta("title",$title);
			$this->meta("keywords",$rt['brandinfo']['meta_keys']);
			$this->meta("description",$rt['brandinfo']['meta_desc']);	
			$this->template('brandgoods');
		}else{ //品牌模块meta
			$title = "品牌导购商品-品牌商品热销";
			$this->title($title.' - '.$GLOBALS['LANG']['site_name']);
			$this->meta("title",$title);
			$this->meta("keywords","");
			$this->meta("description","品牌导购商品，品牌商品热销");	
			$this->template('brandindex');
		}	
		
	}
	
	//品牌商品列表
	function detail($data=""){
		$this->js(array('jquery.json-1.3.js','jquery.cookie.js','common.js'));//将js文件放到页面头
		$rt = $this->Cache->read(3600);
	 	if(is_null($rt)) {
			if(is_array($data) && !empty($data)){ //是一个数组
				$bid = isset($data['bid']) ? $data['bid'] : 0;
				$page = isset($data['page']) ? $data['page'] : 1;
				$cid = isset($data['cid']) ? $data['cid'] : 0;
				$list = isset($data['limit'])?$data['limit']:24;
			}else{
				$bid = $data;
				$page = 1;
				$cid = 0;
				$list = 24;
			}
			
			$rt['infoname'] = $rt['brandinfo']['brand_name'];
			
			//商品分类列表		
			//$rt['menu'] = $this->action('catalog','get_goods_cate_tree');
			
			$rt['brandlist'] = $this->get_brand_list(0,16);	
			
			//热卖前10个商品
			$rt['top10'] = $this->top_brand_goods10($bid,5); 
			
			//商品评论
			 $rt['allcommentlist'] = $this->action('product','get_comment_list',0,0,6);
			
			 //全站banner
			$rt['quanzhan'] = $this->action('banner','quanzhan');
			
			$this->Cache->write($rt);
		}
	
		$this->set('rt',$rt);
		
		//设置页面meta
		$title = htmlspecialchars($rt['brandinfo']['brand_name']);
		$this->title($title.' - '.$GLOBALS['LANG']['site_name']);
		$this->meta("title",$title);
		$this->meta("keywords",htmlspecialchars($rt['brandinfo']['meta_keys']));
		$this->meta("description",htmlspecialchars($rt['brandinfo']['meta_desc']));
		
		$this->template('brandgoods');
	}
	
	
	//品牌列表
	function lists($bid=0,$page=1){
		$rt['hear'] = array();
		$hear[] = '<a href="'.SITE_URL.'">首页</a>';
		$hear[] = '<a href="'.get_url('品牌专场',0,SITE_URL."brand.php",'brand',array('brand','index')).'">品牌专场</a>';
		$hear[] = '<a href="'.get_url('品牌分类',0,SITE_URL."brandlists.php",'brand',array('brand','lists')).'">品牌分类</a>';
		$rt['brandlist'] = $this->get_brand_cate_tree();
		if(!($bid>0)){
			$rt['brandinfo'] = array('brand_id'=>0,'brand_name'=>'品牌分类');
		}else{
			$rt['brandlist_sub'] = $this->get_brand_cate_tree($bid);
			//品牌信息
			$sql = "SELECT * FROM `{$this->App->prefix()}brand` WHERE brand_id='$bid'";
			$rt['brandinfo'] = $this->App->findrow($sql);
			
			if(empty($rt['brandlist'])){
			 	 $c = $this->App->findvar("SELECT parent_id FROM `{$this->App->prefix()}brand` WHERE brand_id='$bid'");
				 $rt['brandlist'] = $this->get_brand_cate_tree($c);
				 $bid = $c;
			 }
			 
			$rts_ = $this->get_brand_parent_cats($bid); //父类ID
			$rts = Import::basic()->array_sort($rts_,'brand_id'); //根据brand_id排序
			if(!empty($rts)){
				$perend_id = $rts[count($rts)-1]['brand_id']; 
				foreach($rts as $rows){
					$hear[] = '<a href="'.get_url($rows['brand_name'],$rows['brand_id'],SITE_URL."brandlists.php?bid=".$rows["brand_id"],'brand',array('brand','lists',$rows['brand_id'])).'">'.$rows['brand_name'].'</a>';
				}
			}
			unset($rts,$rts_);
			
		}
		
		$rt['hear'] = implode('&nbsp;&gt;&nbsp;',$hear);

		//商品分类列表		
		//$rt['menu'] = $this->action('catalog','get_goods_cate_tree');
					
		$this->set('rt',$rt);
		$this->template('brandlist');
	}
	
	function __get_brand($cid=0,$list=0,$type=""){
		if($type=='is_hot') $b = " AND tb2.goods_id !=''";
		//$w = " WHERE tb1.parent_id!='0' AND tb1.is_show='1'{$b}";
		$w = " WHERE tb1.is_show='1'{$b}";
		if($cid>0){
			$subcid = $this->action('catalog','get_goods_sub_cat_ids',$cid);
		 	$w .=" AND tb2.cat_id".db_create_in($subcid);
		}
		if(!empty($type)){
			$w .=" AND tb1.".$type."='1'";
		}
		$s = "";
		if($list>0) $s = "LIMIT $list";
		$sql = "SELECT tb1.brand_name, tb1.brand_id,tb1.brand_name,tb1.brand_banner,tb1.brand_logo,tb1.p_fix,tb2.goods_id,tb2.cat_id,tb2.goods_name FROM `{$this->App->prefix()}brand` AS tb1";
		$sql .=" LEFT JOIN `{$this->App->prefix()}goods` AS tb2 ON tb1.brand_id= tb2.brand_id ";
		$sql .=$w." GROUP BY tb1.brand_id ORDER BY tb1.sort_order ASC,tb1.brand_id DESC $s";
		$rt =  $this->App->find($sql);
		$rts = array();
		if(!empty($rt)){
			foreach($rt as $k=>$row){
				$rts[$k] = $row;
				$rts[$k]['url'] = get_url($row['brand_name'],$row['brand_id'],SITE_URL."brand.php?bid=".$row['brand_id'],'brand',array('brand','index',$row['brand_id']));
				$rts[$k]['goodsurl'] = get_url($row['goods_name'],$row['goods_id'],SITE_URL."product.php?id=".$row['goods_id'],'goods',array('product','index',$row['goods_id']));
				$rts[$k]['brand_banner'] = !empty($row['brand_banner']) ? SITE_URL.$row['brand_banner'] : "";
				$rts[$k]['brand_logo'] = !empty($row['brand_logo']) ? SITE_URL.$row['brand_logo'] : "";
			}
			unset($rt);
		}
		return $rts;
	}
	
	
	//热卖的前10个商品
    function top_brand_goods10($bid=0,$list=10){
           // $w = ($bid>0) ? "AND g.brand_id ='{$bid}'" : "";
            /*$sql = "SELECT g.goods_id, g.goods_name,g.goods_bianhao, g.shop_price, g.market_price, g.goods_thumb, g.original_img, g.goods_img,g.promote_start_date,g.promote_end_date,g.promote_price,g.is_promote, SUM(og.goods_number) as goods_number FROM `{$this->App->prefix()}goods` AS g, `{$this->App->prefix()}goods_order_info` AS o, `{$this->App->prefix()}goods_order` AS og";
            $sql .=" WHERE g.is_on_sale = 1 AND g.is_delete = 0 AND g.is_alone_sale='1' $w";
            $sql .=" AND og.order_id = o.order_id AND og.goods_id = g.goods_id";
            $sql .=" AND (o.order_status = '2' OR o.order_status = '3')";
            $sql .=" AND (o.pay_status = '1' OR o.pay_status = '2')";
            $sql .=" AND (o.shipping_status = '2' OR o.shipping_status = '4' OR o.shipping_status = '5')";
            $sql .=" GROUP BY g.goods_id ORDER BY goods_number DESC, g.goods_id DESC LIMIT $list";
            $rt = $this->App->find($sql);

            if(empty($rt)&&!empty($bid)){
                $rt = $this->top_brand_goods10();
            }
			*/
			$sql = "SELECT goods_id, goods_name,shop_price, market_price, goods_thumb,goods_img,original_img,promote_start_date,promote_end_date,promote_price,is_promote,sale_count FROM `{$this->App->prefix()}goods` WHERE brand_id='$bid' AND is_on_sale = '1' AND is_delete = '0' AND is_alone_sale='1' ORDER BY sale_count DESC,is_hot DESC,is_new DESC LIMIT $list";
			$rt = $this->App->find($sql);
			if(empty($rt)){
				 $sql = "SELECT goods_id, goods_name,goods_bianhao, sale_count, shop_price,market_price, goods_thumb, original_img, goods_img,promote_start_date,promote_end_date,promote_price,is_promote FROM `{$this->App->prefix()}goods` WHERE is_on_sale = '1' AND is_delete = '0' AND is_alone_sale='1' AND (is_hot='1' OR is_new='1' OR is_best='1') ORDER sale_count DESC LIMIT $list";
				 $rt = $this->App->find($sql);
			}
            $rts = array();
            if(!empty($rt)){
                foreach($rt as $k=>$row){
                    $rts[$k] = $row;
                    $rts[$k]['url'] = get_url($row['goods_name'],$row['goods_id'],'product.php?id='.$row['goods_id'],'goods',array('product','index',$row['goods_id']));
					$rts[$k]['goods_thumb'] =  is_file(SYS_PATH.$row['goods_thumb']) ? SITE_URL.$row['goods_thumb'] : SITE_URL.'theme/images/no_picture.gif';
					$rts[$k]['goods_img'] =  is_file(SYS_PATH.$row['goods_img']) ? SITE_URL.$row['goods_img'] : SITE_URL.'theme/images/no_picture.gif';
					$rts[$k]['original_img'] =  is_file(SYS_PATH.$row['original_img']) ? SITE_URL.$row['original_img'] : SITE_URL.'theme/images/no_picture.gif';
					if($row['is_promote']=='1'){
						//促销 价格
						if($row['promote_start_date']<mktime()&&$row['promote_end_date']>mktime()){
							$row['promote_price'] = format_price($row['promote_price']);
						}else{
							$row['promote_price'] = "0.00";
						}
					}else{
						$row['promote_price'] = "0.00";
					}
					$rts[$k]['promote_price'] = $row['promote_price'];
                }
				unset($rt);
            }
            return $rts;
     }
	 
	/**
	 * 获得指定分类的所有上级分类
	 *
	 * @access  public
	 * @param   integer $cat    分类编号
	 * @return  array
	 */
	function get_brand_parent_cats($cat)
	{
		if ($cat == 0)
		{
			return array();
		}
		$arr = $this->App->find("SELECT brand_id, brand_name, parent_id,brand_title FROM `{$this->App->prefix()}brand`");
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
				if ($cat == $row['brand_id']) //如果当前父类的di==当前ID，那么将它写入数据
				{
					$cat = $row['parent_id'];  //将父类ID设为当前id
					$cats[$index]['brand_id']   = $row['brand_id'];
                    $cats[$index]['brand_title']   = $row['brand_title'];
					$cats[$index]['brand_name'] = $row['brand_name'];
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
			$sql = 'SELECT tb1.brand_name,tb1.brand_id,tb1.parent_id,tb1.brand_title,tb1.meta_desc, tb1.meta_keys,tb1.brand_logo,COUNT(tb2.brand_id) AS goods_count FROM `'.$this->App->prefix()."brand` AS tb1";
			$sql .=" LEFT JOIN `{$this->App->prefix()}goods` AS tb2";
			$sql .=" ON tb1.brand_id = tb2.brand_id";
			$sql .= " WHERE tb1.parent_id = '$bid' GROUP BY tb1.brand_id ORDER BY tb1.parent_id ASC,tb1.sort_order ASC, tb1.brand_id ASC";
			$res = $this->App->find($sql);
			foreach ($res as $row)
			{
			   $three_arr[$row['brand_id']]['id']   = $row['brand_id'];
			   $three_arr[$row['brand_id']]['parent_id'] = $row['parent_id'];
			   $three_arr[$row['brand_id']]['name'] = $row['brand_name'];
			   $three_arr[$row['brand_id']]['brand_title']   = $row['brand_title'];
			   $three_arr[$row['brand_id']]['goods_count'] = $row['goods_count'];
			   $three_arr[$row['brand_id']]['meta_keys'] = $row['meta_keys'];
			   $three_arr[$row['brand_id']]['meta_desc'] = $row['meta_desc'];
			   $three_arr[$row['brand_id']]['brand_logo'] = $row['brand_logo'];
			   $three_arr[$row['brand_id']]['url'] = get_url($row['brand_name'],$row['brand_id'],SITE_URL."brandlists.php?bid=".$row["brand_id"],'brand',array('brand','lists',$row['brand_id']));
			   $three_arr[$row['brand_id']]['cateurl'] = get_url($row['brand_name'],$row['brand_id'],SITE_URL."brand.php?bid=".$row["brand_id"],'brand',array('brand','index',$row['brand_id']));
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
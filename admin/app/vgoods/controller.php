<?php
class VgoodsController extends Controller{
 	function  __construct() {
		$this->css('content.css');
	}

	//商品列表页面
	function lists(){
			$this->css('jquery_dialog.css');
			$this->js('jquery_dialog.js');
            //排序
            $orderby = "";
            if(isset($_GET['desc'])){
                      $orderby = ' ORDER BY '.$_GET['desc'].' DESC';
            }else if(isset($_GET['asc'])){
                      $orderby = ' ORDER BY '.$_GET['asc'].' ASC';
            }else {
                      $orderby = ' ORDER BY tb1.`sort_order` ASC,tb1.`goods_id` DESC';
            }
            //分页
            $page= isset($_GET['page']) ? $_GET['page'] : '';
            if(empty($page)){
                   $page = 1;
            }
            //查询条件
            $w="";
            $ws="";
            if(isset($_GET)&&!empty($_GET)){
                $comd = array();
                if(isset($_GET['cat_id'])&&intval($_GET['cat_id'])>0){
                    $cids = $this->action('common','get_goods_sub_cat_ids',$_GET['cat_id']);
					$comd[] = 'tb1.cat_id IN ('.implode(",",$cids).')';
				}
		
				if(isset($_GET['uid'])&&intval($_GET['uid'])>0)
                    $comd[] = 'tb1.uid='.intval($_GET['uid']);

                if(isset($_GET['brand_id'])&&intval($_GET['brand_id'])>0)
                    $comd[] = 'tb1.brand_id='.intval($_GET['brand_id']);

				if(isset($_GET['is_goods_attr'])&&!empty($_GET['is_goods_attr'])){
					switch(trim($_GET['is_goods_attr'])){
						case 'is_on_sale1':
							$comd[] = "tb1.is_on_sale='1'";
							break;
						case 'is_on_sale0':
							$comd[] = "tb1.is_on_sale='0'";
							break;
						case 'is_hot':
							$comd[] = "tb1.is_hot='1'";
							break;
						case 'is_new':
							$comd[] = "tb1.is_new='1'";
							break;
						case 'is_best':
							$comd[] = "tb1.is_best='1'";
							break;
						case 'is_promote':
							$comd[] = "tb1.is_promote='1'";
							break;
						case 'is_alone_sale':
							$comd[] = "tb1.is_alone_sale='0'";
							break;
						case 'is_qianggou':
							$comd[] = "tb1.is_qianggou='1'";
							break;
					}
				}
				$comd[] = "tb1.is_virtual='1' AND tb1.is_delete='0'";
                if(isset($_GET['keyword'])&&$_GET['keyword'])
                    $comd[] = "(tb1.goods_name LIKE '%".trim($_GET['keyword'])."%' OR tb1.goods_sn LIKE '%".trim($_GET['keyword'])."%')";
				//已审核
				if(isset($_GET['sale'])&&$_GET['sale']=='yes'){
					$comd[] = "tb1.is_check='1'";
				}
				//待审核
				if(isset($_GET['sale'])&&$_GET['sale']=='no'){
					$comd[] = "tb1.is_check='0'";
				}
				
                if(!empty($comd)){
                    $w = ' WHERE '.implode(' AND ',$comd);
                    $ws = str_replace('tb1.','',$w);
                }
            }
            $list = 30;
            $start = ($page-1)*$list;
            $sql = "SELECT COUNT(goods_id) FROM `{$this->App->prefix()}goods` $ws";
            $tt = $this->App->findvar($sql);
            $pagelink = Import::basic()->getpage($tt, $list, $page,'?page=',true);
            $this->set("pagelink",$pagelink);
			
            $sql = "SELECT tb1.goods_id,tb1.sort_order,tb1.cat_id,tb1.goods_thumb, tb1.goods_sn, tb1.goods_name, tb1.need_jifen,tb1.is_on_sale,tb1.is_check, tb1.is_promote,tb1.is_qianggou,tb1.market_price, tb1.shop_price,tb1.pifa_price,tb1.promote_price,tb1.qianggou_price,tb1.qianggou_start_date,tb1.qianggou_end_date,tb1.promote_start_date,tb1.promote_end_date,tb1.add_time, tb1.is_shipping,tb1.is_best,tb1.is_new,tb1.is_hot,tb1.is_alone_sale,tb2.cat_name,tb3.user_name,tb3.nickname FROM `{$this->App->prefix()}goods` AS tb1";
            $sql .=" LEFT JOIN `{$this->App->prefix()}goods_cate` AS tb2 ON tb1.cat_id = tb2.cat_id";
			$sql .=" LEFT JOIN `{$this->App->prefix()}user` AS tb3 ON tb1.uid=tb3.user_id";
            $sql .=" $w $orderby LIMIT $start,$list";// echo $sql;
            $rt = $this->App->find($sql);
			
			//分类列表
			$this->set('catelist',$this->action('common','get_goods_cate_tree'));
			//品牌列表
			$this->set('brandlist',$this->action('common','get_brand_cate_tree'));
			
			$this->set('rt',$rt);
			$this->template("lists");
	}
	
	//商品详情页面
	function info(){
			$gid = isset($_GET['id'])? $_GET['id'] : 0;
            $this->js(array("edit/kindeditor.js"));
			$this->js('time/WdatePicker.js');
		
			//公共部分
			if(isset($_POST)&&!empty($_POST)){
				$_POST['is_best'] = isset($_POST['is_best'])&&intval($_POST['is_best'])>0 ? intval($_POST['is_best']) : '0';
				$_POST['is_new'] = isset($_POST['is_new'])&&intval($_POST['is_new'])>0 ? intval($_POST['is_new']) : '0';
				$_POST['is_hot'] = isset($_POST['is_hot'])&&intval($_POST['is_hot'])>0 ? intval($_POST['is_hot']) : '0';
				$_POST['is_on_sale'] = isset($_POST['is_on_sale'])&&intval($_POST['is_on_sale'])>0 ? intval($_POST['is_on_sale']) : '1';
				$_POST['is_shipping'] = isset($_POST['is_shipping'])&&intval($_POST['is_shipping'])>0 ? intval($_POST['is_shipping']) : '0';
				$_POST['is_alone_sale'] = isset($_POST['is_alone_sale'])&&intval($_POST['is_alone_sale'])>0 ? '0' : '1';
				$_POST['is_promote'] = isset($_POST['is_promote'])&&intval($_POST['is_promote'])>0 ? intval($_POST['is_promote']) : '0';
				if($_POST['is_promote']=='1'){
				
					if(isset($_POST['promote_start_date'])&&!empty($_POST['promote_start_date'])){
				 		$_POST['promote_start_date'] =  strtotime($_POST['promote_start_date'].' '.$_POST['xiaoshi_start'].':'.$_POST['fen_start'].':'.$_POST['miao_start']);
					}
					if(isset($_POST['promote_end_date'])&&!empty($_POST['promote_end_date'])){
						 $_POST['promote_end_date'] =  strtotime($_POST['promote_end_date'].' '.$_POST['xiaoshi_end'].':'.$_POST['fen_end'].':'.$_POST['miao_end']);
					}
				}
				unset($_POST['xiaoshi_start'],$_POST['fen_start'],$_POST['miao_start']);
				unset($_POST['xiaoshi_end'],$_POST['fen_end'],$_POST['miao_end']);
				
				
				$_POST['is_qianggou'] = isset($_POST['is_qianggou'])&&intval($_POST['is_qianggou'])>0 ? intval($_POST['is_qianggou']) : '0';
				$_POST['qianggou_start_date'] = isset($_POST['qianggou_start_date'])&&!empty($_POST['qianggou_start_date']) ? strtotime($_POST['qianggou_start_date']) : '0';
				$_POST['qianggou_end_date'] = isset($_POST['qianggou_end_date'])&&!empty($_POST['qianggou_end_date']) ? strtotime($_POST['qianggou_end_date']) : '0';
				$_POST['is_virtual'] = '1';
				$_POST['is_check'] = '1';
			   ######################
				//添加商品属性||过滤商品属性字段，以更好插入到商品表
				$atid = array('attr_id_list'=>'0'); //属性id，在gz_attribute表中
				$atvalue = array('attr_value_list'=>'0'); //用户添加的值
				$ataddi = array('attr_addi_list'=>'0'); //附加的东西，例如可以是价格图片等其他东西
				$gadesc = array('photo_gallery_desc'=>'0'); //商品相册描述
				$gaurl = array('photo_gallery_url'=>'0'); //商品相册图片
				$goods_gift = array('gift_type'=>'0'); 
				$nprice = array('numberprice'=>'0');
				$nrank = array('numberrank'=>'0');
				
				$attr_id_list = array();
				$attr_value_list = array();
				$attr_addi_list = array();
				$photo_gallery_desc = array();
				$photo_gallery_url = array();
				$goods_gift_arr = array();
				$numberprice =array(); //会员价格与等级是一一对应的
				$numberrank =array();

				if(isset($_POST['attr_id_list'])){
					$attr_id_list = $_POST['attr_id_list']; //属性id，在gz_attribute表中
					$_POST = array_diff_key($_POST,$atid);
				}
				if(isset($_POST['attr_value_list'])){
					$attr_value_list = $_POST['attr_value_list']; //用户添加的值
					$_POST = array_diff_key($_POST,$atvalue);
				}
				if(isset($_POST['attr_addi_list'])){
					$attr_addi_list = $_POST['attr_addi_list']; //附加的东西，例如可以使图片等其他东西
					$_POST = array_diff_key($_POST,$ataddi);
				}
                //商品相册描述
                if(isset($_POST['photo_gallery_desc'])){
					$photo_gallery_desc = $_POST['photo_gallery_desc'];
					$_POST = array_diff_key($_POST,$gadesc);
				}
                //商品相册图片
                if(isset($_POST['photo_gallery_url'])){
					$photo_gallery_url = $_POST['photo_gallery_url'];
					$_POST = array_diff_key($_POST,$gaurl);
				}
				//商品的额外分类处理
				$sd = array('sub_cat_id'=>'0');
				$subcateid = array();
				if(isset($_POST['sub_cat_id'])){
						$subcateid = $_POST['sub_cat_id'];
						$_POST = array_diff_key($_POST,$sd);
				}
			}
			
			
            if($gid>0){ //编辑页面
			//当前商品基本信息
			$sql = "SELECT * FROM `{$this->App->prefix()}goods` WHERE goods_id='{$gid}' LIMIT 1";
            $rt = $this->App->findrow($sql);
			if(empty($rt)){ $this->jump('vgoods.php?type=lists'); exit;}
			//当前商品的相册
			$sql = "SELECT * FROM `{$this->App->prefix()}goods_gallery` WHERE goods_id='$gid'";
			$this->set('gallerylist',$this->App->find($sql));
			//当前商品属性的属性
			$sql = "SELECT tb1.*,tb2.attr_name,tb2.attr_is_select FROM `{$this->App->prefix()}goods_attr` AS tb1 LEFT JOIN `{$this->App->prefix()}attribute` AS tb2 ON tb1.attr_id=tb2.attr_id WHERE tb1.goods_id='$gid'";
			$goods_attr = $this->App->find($sql);
			$rt['goods_attr'] = array();
			if(!empty($goods_attr)){
				foreach($goods_attr as $row){
					$rt['goods_attr'][$row['attr_id']][] = $row;
				}
				unset($row,$goods_attr);
			}
						
                        if(isset($_POST)&&!empty($_POST)){
								if(empty($_POST['goods_name'])){
                                    echo'<script>alert("商品名称不能为空！");</script>';
                                }else{

                                    //货号
                                    if(empty($_POST['goods_sn'])){
                                         $_POST['goods_sn'] = 'GZFH' . str_repeat('0', 6 - strlen($gid)) . $gid;
                                    }
                                    //检查当前的货号是否存在
                                    $checkvar = $this->App->findvar("SELECT goods_sn FROM `{$this->App->prefix()}goods` WHERE goods_sn=$_POST[goods_sn] LIMIT 1");
                                    if(!empty($checkvar)){
                                         $_POST['goods_sn'] = $_POST['goods_sn'].'-1'; //重新定义一个
                                    }

                                     if($rt['original_img']!=$_POST['original_img']){
                                            //修改了上传文件 那么重新上传
                                            $source_path = SYS_PATH.DS.str_replace('/',DS,$_POST['original_img']);
                                            $pa = dirname($_POST['original_img']);
                                            $thumb = basename($_POST['original_img']);
											
											$tw_s = (intval($GLOBALS['LANG']['th_width_s']) > 0) ? intval($GLOBALS['LANG']['th_width_s']) : 200;
											$th_s = (intval($GLOBALS['LANG']['th_height_s']) > 0) ? intval($GLOBALS['LANG']['th_height_s']) : 200;
											$tw_b = (intval($GLOBALS['LANG']['th_width_b']) > 0) ? intval($GLOBALS['LANG']['th_width_b']) : 450;
											$th_b = (intval($GLOBALS['LANG']['th_height_b']) > 0) ? intval($GLOBALS['LANG']['th_height_b']) : 450;
											if(isset($_POST['goods_thumb'])&&!empty($_POST['goods_thumb'])){
											   //留空
											}else{
                                            	Import::img()->thumb($source_path,dirname($source_path).DS.'thumb_s'.DS.$thumb,$tw_s,$th_s); //小缩略图
                                            	$_POST['goods_thumb'] = $pa.'/thumb_s/'.$thumb;
											}
											 
                                            Import::img()->thumb($source_path,dirname($source_path).DS.'thumb_b'.DS.$thumb,$tw_b,$th_b); //大缩略图
                                            $_POST['goods_img'] = $pa.'/thumb_b/'.$thumb;
                                     }
                                     $_POST['meta_keys'] = !empty($_POST['meta_keys']) ? str_replace(array('，','。','.'),',',$_POST['meta_keys']) : "";
									 
									 $_POST['last_update'] = mktime(); //更新时间
                                     $this->App->update('goods',$_POST,'goods_id',$gid);
								
                                     //更新商品属性[从新添加]
                                     if(!empty($attr_id_list)&&!empty($gid)){
                                            foreach($attr_id_list as $kk=>$id){
                                                    if(empty($attr_value_list[$kk])) continue;
                                                    $rtdata = array();
                                                    $rtdata['attr_id'] = $id;
                                                    $rtdata['attr_value'] = isset($attr_value_list[$kk]) ? $attr_value_list[$kk] : "NULL";
                                                    $rtdata['goods_id'] = $gid;
                                                    $rtdata['attr_addi'] = isset($attr_addi_list[$kk]) ? $attr_addi_list[$kk] : "";
                                                    $this->App->insert('goods_attr',$rtdata);
                                            }
                                            unset($rtdata);
                                     }
                                     ###########更新商品相册##########
                                     if(!empty($photo_gallery_url)&&!empty($gid)){
                                          foreach($photo_gallery_url as $kk=>$url){
                                               if(empty($url)) continue;
                                                $rtdata['img_desc'] = isset($photo_gallery_desc[$kk]) ? $photo_gallery_desc[$kk] : "";
                                                $rtdata['goods_id'] = $gid;
                                                $rtdata['img_url'] = $url;
                                                $this->App->insert('goods_gallery',$rtdata);
                                          }
                                          unset($rtdata);
                                     }
                                     //商品的子分类
                                     if(!empty($subcateid)){
                                           foreach($subcateid as $ids){
                                               $dd = array();
                                               $dd['goods_id'] = $gid;
                                               $dd['cat_id'] = $ids;
                                               $this->App->insert('category_sub_goods',$dd);
                                           }
                                     }
									 //将关键字添加到goods_keyword表
									 if(!empty($_POST['meta_keys'])){
									 	$keys = explode(',',$_POST['meta_keys']);
										foreach($keys as $key){
											if(empty($key)) continue;
											$key = trim($key);
											$sql = "SELECT kid FROM `{$this->App->prefix()}goods_keyword` WHERE goods_id='$gid' AND keyword='$key'";
											$kid = $this->App->findvar($sql);
											$ds = array();
											if(empty($kid)){
												$ds['goods_id'] = $gid;
												$ds['keyword'] = $key;
												$n = Import::basic()->Pinyin($key);
												$ds['p_fix'] = !empty($n) ? ucwords(substr($n,0,1)) : "NAL";
												$this->App->insert('goods_keyword',$ds);
											}
										}
										unset($keys);
									 }
									 
                                     $this->action('system','add_admin_log','修改商品:'.$_POST['goods_name'].'-goods_id:'.$gid);
                                     $this->action('common','showdiv',$this->getthisurl());
                                }
			}
                        //该商品的其他子分类
                        $sql = " SELECT tb1.*,tb2.cat_name FROM `{$this->App->prefix()}category_sub_goods` AS tb1";
                        $sql .=" LEFT JOIN `{$this->App->prefix()}goods_cate` AS tb2 ON tb1.cat_id = tb2.cat_id";
                        $sql .=" WHERE tb1.goods_id='$gid'";
                        $this->set('subcatelist',$this->App->find($sql));
                        $this->set('type','edit');
		}else{
                        //添加
                        if(isset($_POST)&&!empty($_POST)){
                             if(empty($_POST['goods_name'])){
                                 echo'<script>alert("商品名称不能为空！");</script>';
                             }else{ 
                                 $_POST['meta_keys'] = !empty($_POST['meta_keys']) ? str_replace(array('，','。','.'),',',$_POST['meta_keys']) : "";
                                 $_POST['add_time'] = mktime();
                                 if(!isset($_POST['goods_sn']) || empty ($_POST['goods_sn'])){
                                     $gid = $this->App->findvar("SELECT MAX(goods_id) + 1 FROM `{$this->App->prefix()}goods`");
                                     $gid = empty($gid) ? 1 : $gid;
                                     $goods_sn = 'GZFH' . str_repeat('0', 6 - strlen($gid)) . $gid;
                                     $_POST['goods_sn'] = $goods_sn;
                                 }
                                 //检查当前的货号是否存在
                                 $checkvar = $this->App->findvar("SELECT goods_sn FROM `{$this->App->prefix()}goods` WHERE goods_sn=$_POST[goods_sn] LIMIT 1");
                                 if(!empty($checkvar)){
                                     $_POST['goods_sn'] = $_POST['goods_sn'].'-1'; //重新定义一个
                                 }
                                 //商品图片
                                 if(!empty($_POST['original_img'])){
                                    $pa = dirname($_POST['original_img']);
                                    $thumb = basename($_POST['original_img']);
                                    //商品小图
									if(isset($_POST['goods_thumb'])&&!empty($_POST['goods_thumb'])){
										//留空即可
									}else{
										$_POST['goods_thumb'] = $pa.'/thumb_s/'.$thumb;
									}
									//商品中图
                                    $_POST['goods_img'] = $pa.'/thumb_b/'.$thumb;
                                 }
									
                                 if($this->App->insert('goods',$_POST)){
                                         ##########商品属性添加###########
                                         $isertid = $this->App->iid();
                                         if(!empty($attr_id_list)&&!empty($isertid)){
                                                foreach($attr_id_list as $kk=>$id){
                                                        if(empty($attr_value_list[$kk])) continue;
                                                        $rtdata = array();
                                                        $rtdata['attr_id'] = $id;
                                                        $rtdata['attr_value'] = isset($attr_value_list[$kk]) ? $attr_value_list[$kk] : "NULL";
                                                        $rtdata['goods_id'] = $isertid;
                                                        $rtdata['attr_addi'] = isset($attr_addi_list[$kk]) ? $attr_addi_list[$kk] : "";
                                                        $this->App->insert('goods_attr',$rtdata);
                                                }
                                                unset($rtdata);
                                         }
                                         ###########添加商品相册##########
                                         if(!empty($photo_gallery_url)&&!empty($isertid)){
                                              foreach($photo_gallery_url as $kk=>$url){
                                                   if(empty($url)) continue;
                                                    $rtdata['img_desc'] = isset($photo_gallery_desc[$kk]) ? $photo_gallery_desc[$kk] : "";
                                                    $rtdata['goods_id'] = $isertid;
                                                    $rtdata['img_url'] = $url;
                                                    $this->App->insert('goods_gallery',$rtdata);
                                              }
                                              unset($rtdata);
                                         }
                                         //商品的子分类
                                         if(!empty($subcateid)){
                                               foreach($subcateid as $ids){
                                                   $dd = array();
                                                   $dd['goods_id'] = $isertid;
                                                   $dd['cat_id'] = $ids;
                                                   $this->App->insert('category_sub_goods',$dd);
                                               }
                                         }
										 
										 //将关键字添加到goods_keyword表
										 if(!empty($_POST['meta_keys'])){
											$keys = explode(',',$_POST['meta_keys']);
											foreach($keys as $key){
												if(empty($key)) continue;
												$key = trim($key);
												$ds = array();
												$ds['goods_id'] = $isertid;
												$ds['keyword'] = $key;
												$n = Import::basic()->Pinyin($key);
												$ds['p_fix'] = !empty($n) ? ucwords(substr($n,0,1)) : "NAL";
												$this->App->insert('goods_keyword',$ds);
											}
											unset($keys);
										 }
									 
                                         $this->action('system','add_admin_log','添加商品:'.$_POST['goods_name'].'-goods_id:'.$gid);
                                         $this->action('common','showdiv',$this->getthisurl());
                                }else{
                                        echo '<script> alert("添加失败，添加过程发生意外错误！"); </script>';
                                }
                             }
                            $rt = $_POST;
                        }
                 $this->set('type','add');
		}
		//商品的属性列表
		$sql = "SELECT * FROM `{$this->App->prefix()}attribute` ORDER BY sort_order,attr_id DESC";
		$this->set('attr_list',$this->App->find($sql)); 
		
		$this->set('rt',$rt);
		//分类列表
		$this->set('catelist',$this->action('common','get_goods_cate_tree'));
		//品牌列表
		$this->set('brandlist',$this->action('common','get_brand_cate_tree'));
		
		$this->template('info');
	}
	
	function ajax_open_import($data=array()){
		$id = isset($data['gid']) ? $data['gid'] : 0;
		$gid = $data['id'];
		if($id > 0){
			$this->App->delete('goods_sn','id',$id);
			$this->jump(ADMIN_URL.'vgoods.php?type=ajax_open_import&id='.$gid);
			exit;
		}
		
		 //分页
		 $page= isset($_GET['page']) ? $_GET['page'] : '';
		 if(empty($page)){
			   $page = 1;
		 }
		 $list = 5;
		 $start = ($page-1)*$list;
		 $sql = "SELECT COUNT(id) FROM `{$this->App->prefix()}goods_sn` WHERE goods_id = '$gid'";
		 $tt = $this->App->findvar($sql);
		 $pagelink = Import::basic()->getpage($tt, $list, $page,'?page=',true);
		 $this->set("pagelink",$pagelink);
			
		 $rt = $this->App->find("SELECT * FROM `{$this->App->prefix()}goods_sn` WHERE goods_id = '$gid' ORDER BY id DESC LIMIT $start,$list");
		 
		$this->set('rt',$rt);
		
		$vgoods_type = $this->App->findvar("SELECT vgoods_type FROM `{$this->App->prefix()}userconfig` WHERE type = 'basic' LIMIT 1");
		$this->set('vgoods_type',$vgoods_type);
		$this->template('ajax_open_import');
	}
	
	
	function download_tpl(){
		  $fop = Import::fileop();
		  $vgoods_type = $this->App->findvar("SELECT vgoods_type FROM `{$this->App->prefix()}userconfig` WHERE type = 'basic' LIMIT 1");
		  if($vgoods_type=='1'){
		  $fop->downloadfile(SYS_PATH.'data/vgoodssn2.xls');
		  }else{
		  $fop->downloadfile(SYS_PATH.'data/vgoodssn.xls');
		  }
		  exit;
	}
	
	function ajax_sava_set($data=array()){
		$vv = $data['vv'];
		$this->App->update('userconfig',array('vgoods_type'=>$vv),'type','basic');
	}
}
?>
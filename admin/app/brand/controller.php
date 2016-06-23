<?php
 /*
 * 这是一个后台产品处理类
 */
class BrandController extends Controller{
 	function  __construct() {
		$this->css('content.css');
	}
	//品牌列表页面
	function brand_list(){
            //排序
            $orderby = "";
            if(isset($_GET['desc'])){
                      $orderby = ' ORDER BY '.$_GET['desc'].' DESC';
            }else if(isset($_GET['asc'])){
                      $orderby = ' ORDER BY '.$_GET['asc'].' ASC';
            }else {
                      $orderby = ' ORDER BY `sort_order` ASC';
            }
            //分页
            $page= isset($_GET['page']) ? $_GET['page'] : '';
            if(empty($page)){
                      $page = 1;
            }
           // $list = 10;
           // $start = ($page-1)*$list;
            //$sql = "SELECT COUNT(brand_id) FROM `{$this->App->prefix()}brand`";
            //$tt = $this->App->findvar($sql);
            //$pagelink = Import::basic()->getpage($tt, $list, $page,'?page=',true);
            //$this->set("pagelink",$pagelink);


            //$sql = "SELECT * FROM `{$this->App->prefix()}brand` $orderby LIMIT $start,$list";
            $this->set('brandlist',$this->action('common','get_brand_cate_tree'));
			
            $this->template('goods_brand_list');
	}

	//品牌详情页面
	function brand_info($bid=0){
			 $this->js(array("edit/kindeditor.js"));
            $rt = array();            
            if($bid>0){ //编辑页面
                if(isset($_POST)&&!empty($_POST)){
                     if(empty($_POST['brand_name'])){
                         echo'<script>alert("品牌名称不能为空！");</script>';
                     }else{
                        $sql = "SELECT brand_id FROM `{$this->App->prefix()}brand` WHERE brand_name='$_POST[brand_name]' LIMIT 1";
                        $rs = $this->App->findvar($sql);
                        if(!empty($rs)&&$rs!=$bid){
                              echo'<script> alert("该品牌名称已经存在了！"); </script>';
                        }else{
							//$ty = array('type'=>'0');
						 	//$brand_typs = $_POST['type'];
							//$_POST = array_diff_key($_POST,$ty);
							$n = Import::basic()->Pinyin(trim($_POST['brand_name']));
							$_POST['p_fix'] = !empty($n) ? ucwords(substr($n,0,1)) : "NAL";
                            $_POST['meta_keys'] = !empty($_POST['meta_keys']) ? str_replace(array('，','。','.'),',',$_POST['meta_keys']) : "";
                            $this->App->update('brand',$_POST,'brand_id',$bid);
							/*if(!empty($brand_typs)){
								 foreach($brand_typs as $id){
									if(!($id>0)) continue;
									$dd['bid'] = $bid;
									$dd['type_id'] = $id;
									$this->App->insert('brand_type',$dd);
									unset($dd);
								 }
							}*/
                            $this->action('system','add_admin_log','修改商品品牌:'.$_POST['brand_name']);
                            $this->action('common','showdiv',$this->getthisurl());
                        }
                     }
                }
                $sql = "SELECT * FROM `{$this->App->prefix()}brand` WHERE brand_id='{$bid}' LIMIT 1";
                $rt = $this->App->findrow($sql);
				//$sql = "SELECT * FROM `{$this->App->prefix()}brand_type` WHERE bid='{$bid}'";
				//$brand_type = $this->App->find($sql);
                $this->set('type','edit');
            }else{ //添加页面
                 if(isset($_POST)&&!empty($_POST)){
                     if(empty($_POST['brand_name'])){
                         echo'<script>alert("品牌名称不能为空！");</script>';
                     }else{
                         $sql = "SELECT brand_id FROM `{$this->App->prefix()}brand` WHERE brand_name='$_POST[brand_name]' LIMIT 1";
                         $rs = $this->App->findvar($sql);
                         if(!empty($rs)){
                              echo'<script> alert("该品牌名称已经存在了！"); </script>';
                         }else{
							 //$ty = array('type'=>'0');
						 	 //$brand_typs = $_POST['type'];
							 //$_POST = array_diff_key($_POST,$ty);
						 	 $n = Import::basic()->Pinyin(trim($_POST['brand_name']));
							 $_POST['p_fix'] = !empty($n) ? ucwords(substr($n,0,1)) : "NAL";
                             $_POST['meta_keys'] = !empty($_POST['meta_keys']) ? str_replace(array('，','。','.'),',',$_POST['meta_keys']) : "";
                             $this->App->insert('brand',$_POST);
							 /*$bid = $this->App->iid();
							 if(!empty($brand_typs)){
								 foreach($brand_typs as $id){
									if(!($id>0)) continue;
									$dd['bid'] = $bid;
									$dd['type_id'] = $id;
									$this->App->insert('brand_type',$dd);
									unset($dd);
								 }
							 }*/
                             $this->action('system','add_admin_log','添加商品品牌:'.$_POST['brand_name']);
                             $this->action('common','showdiv',$this->getthisurl());
                         }
                     }
                    // $rt = $_POST;
                 }
				 $brand_type = array();
                 $this->set('type','add');
            }
            $this->set('rt',$rt);
			$this->set('brandlist',$this->action('common','get_brand_cate_tree'));
			$this->set('catelist',$this->action('common','get_goods_cate_tree'));
			//$this->set('brand_type',$brand_type);
            $this->template('goods_brand_info');
	}
     //ajax删除商品品牌
     function ajax_brand_dels($ids=0){
            if(empty($ids)) die("非法删除，删除ID为空！");
            $id_arr_ = @explode('+',$ids);
			$id_arr = array();
			if(!empty($id_arr_)){
				foreach($id_arr_  as $id ){
					$getid = $this->action('common','get_brand_sub_cat_ids',$id); //子分类
					if(!empty($getid)){
						foreach($getid as $ii){
							if(!in_array($ii,$id_arr)){
								$id_arr[] = $ii;
							}
						}
					}
				}
			}else{
				echo "意外错误！";exit;
			}
			if(empty($id_arr)){
				echo "意外错误！";exit;
			}
            $sql = "SELECT brand_logo FROM `{$this->App->prefix()}brand` WHERE brand_id IN(".@implode(',',$id_arr).")";
            $imgs = $this->App->findcol($sql);
            //删除图片
            if(!empty($imgs)){
                    foreach($imgs as $img){
                            if(empty($img)) continue;
                            Import::fileop()->delete_file(SYS_PATH.$img); //删除图片
                            $q = dirname($img);
                            $h = basename($img);
                            Import::fileop()->delete_file(SYS_PATH.$q.DS.'thumb_s'.DS.$h);
                            Import::fileop()->delete_file(SYS_PATH.$q.DS.'thumb_b'.DS.$h);
                    }
                    unset($imgs);
            }

            //删除数据表
            foreach($id_arr as $id){
              if(Import::basic()->int_preg($id))
              $this->App->delete('brand','brand_id',$id);
			 // $this->App->delete('brand_type','bid',$id);
            }
			unset($ids,$id_arr_,$id_arr);
            $this->action('system','add_admin_log','删除商品品牌：'.@implode(',',$id_arr));
	}

    //ajax激活操作
    function ajax_brand_active($data=array()){
		if(empty($data['bid'])) die("非法操作，ID为空！");
		$type = $data['type'];
		$bid = $data['bid'];
		$val = $data['active'];
		switch($type){
			case 'is_show':
				$sdata['is_show']= $val;
				$this->action('system','add_admin_log','修改商品品牌显示状态:ID为=>'.$bid);
				break;
			case 'is_promote':
				$sdata['is_promote']= $val;
				$this->action('system','add_admin_log','修改商品品牌为推荐品牌:ID为=>'.$bid);
				break;
			case 'is_hot':
				$sdata['is_hot']= $val;
				$this->action('system','add_admin_log','修改商品品牌为热销品牌:ID为=>'.$bid);
				break;
		}

		$this->App->update('brand',$sdata,'brand_id',$bid);
		unset($data);
	}

     //ajax分类激活与是否显示在导航栏
     function ajax_cate_active($data=array()){
        if(empty($data['cid'])) die("非法操作，ID为空！");

		if($data['type']=='show_in_nav'){
			//添加到导航栏表
			$sql = "SELECT cid FROM `{$this->App->prefix()}nav` WHERE cid ='{$data[cid]}' AND ctype ='gc' LIMIT 1";
			$checkvar = $this->App->findvar($sql);
			$sdata['show_in_nav']= $data['active'];

			if(empty($checkvar)){
				$cdata['ctype'] = 'gc';
				$cdata['cid'] = $data['cid'];
				$cdata['name'] = $this->App->findvar("SELECT cat_name FROM `{$this->App->prefix()}goods_cate` WHERE cat_id ='{$data[cid]}' LIMIT 1");
				$cdata['is_show'] = 1;
				$cdata['is_opennew'] = 0;
				$cdata['url'] = 'catalog.php?cid='.$data['cid'];
				$this->App->insert('nav',$cdata);
				unset($cdata);
			 }else{
			 	//$this->App->delete('nav','cid',$checkvar);
				$sql = "DELETE FROM `{$this->App->prefix()}nav` WHERE cid='$checkvar' AND ctype='gc'";
			 	$this->App->query($sql);
			 }

			$this->action('system','add_admin_log','修改商品分类是否显示在导航栏:ID为=>'.$data['cid']);
		}else if($data['type']=='is_show'){

			$sdata['is_show']= $data['active'];
			$this->action('system','add_admin_log','修改商品分类状态:ID为=>'.$data['cid']);
		}else{
			die('没有指派类型！');
		}
		$this->App->update('goods_cate',$sdata,'cat_id',$data['cid']);
		unset($data,$sdata);
    }

        //ajax品牌排序操作
	function ajax_brand_order($data=array()){
		if(empty($data['id'])) return "50";
		$sdata['sort_order'] = empty($data['val']) ? 50 : $data['val'];
		$this->App->update('brand',$sdata,'brand_id',$data['id']);
	}
	
	function ajax_brand_type_del($id=0){
		if(!($id>0)) die("传送ID为空！");
		if($this->App->delete('brand_type','tid',$id)){
		}else{
			echo "删除失败！"; exit;
		}
	}
	
}
?>
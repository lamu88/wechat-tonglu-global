<?php

class MarkhtmlController extends Controller{
	//构造函数，自动新建对象
 	function  __construct() {
		/*
		*构造函数，自动新建session对象
		*/
	}
	
	function get_nav_var($k=0,$dtype=""){
		   $cache = Import::ajincache();
		   $cache->SetFunction(__FUNCTION__);
		   $cache->SetMode(str_replace('Controller','',__CLASS__));
		   $fn = $cache->fpath(array('nav'=>'nav','time'=>$dtype));
		   if(file_exists($fn)&& mktime() - filemtime($fn) < 300){
				include($fn);
		   }
		   else
		   {
		    //自定义导航
		    $sql = "SELECT id, cid, name, url, ctype  FROM `{$this->App->prefix()}nav`";
			$rt_ = $this->App->find($sql);
			if(!empty($rt_)){
				foreach($rt_ as $row){
                                    if($row['ctype']=='c'){
						$path = SYS_PATH.'cate'.DS.Import::basic()->Pinyin($row['name']).$row['cid'].'.html';
						$returnurl = SITE_URL.'/cate/'.Import::basic()->Pinyin($row['name']).$row['cid'].'.html';
                                    }elseif($row['ctype']=='a'){
						$path = SYS_PATH.'alte'.DS.Import::basic()->Pinyin($row['name']).$row['id'].'.html';
						$returnurl = SITE_URL.'/alte/'.Import::basic()->Pinyin($row['name']).$row['id'].'.html';
                                    }else{
						$path = SYS_PATH.'cate'.DS.Import::basic()->Pinyin($row['name']).'.html';
						$returnurl = SITE_URL.'/cate/'.Import::basic()->Pinyin($row['name']).'.html';
                                    }
                                    $rt[] = array('id'=>$row['id'],'name'=>$row['name'],'url'=>SITE_URL.$row['url'],'path'=>$path,'returnurl'=>$returnurl);
				}
				unset($rt_);
			}
			$cache->write($fn, $rt,'rt');
		   }
		   return isset($rt[$k]) ? $rt[$k] : "";
	}
	
	function get_category_var($k=0,$dtype=""){
		   $cache = Import::ajincache();
		   $cache->SetFunction(__FUNCTION__);
		   $cache->SetMode(str_replace('Controller','',__CLASS__));
		   $fn = $cache->fpath(array('cate'=>'cate','time'=>$dtype));
		   if(file_exists($fn)&& mktime() - filemtime($fn) < 300){
				include($fn);
		   }
		   else
		   {
			$dt = "";
			if($dtype=='two_w'){
                                $t = mktime()-3600*24*7*2;
				$dt = "WHERE uptime > $t OR addtime > $t";
			}elseif($dtype=='two_m'){
				$t = mktime()-3600*24*30;
				$dt = "WHERE uptime > $t OR addtime > $t";
			}
			
                        $sql = "SELECT cat_id, cat_name,type FROM `{$this->App->prefix()}article_cate` $dt";
			$rt_ = $this->App->find($sql);
			if(!empty($rt_)){
				foreach($rt_ as $row){
                                        $list = 20; //每页20条显示记录
					$cid = $row['cat_id'];
					$sourceid = array($cid);
                                        $get_cid = $this->get_sub_cat_ids($cid,$row['type']);
                                        if(!empty($get_cid)){
                                            $subcid=array_merge($get_cid,$sourceid);
                                        }else{
                                            $subcid = $sourceid;
                                        }
                                        unset($sourceid,$get_cid);

					$rt[] = array('id'=>$row['cat_id'],'name'=>$row['cat_name'],'url'=>SITE_URL.'/category.php?cid='.$row['cat_id'],'path'=>SYS_PATH.'cate'.DS.Import::basic()->Pinyin($row['cat_name']).$row['cat_id'].'.html','returnurl'=>SITE_URL.'/cate/'.Import::basic()->Pinyin($row['cat_name']).$row['cat_id'].'.html');
					//如果是模板分类
                                       
                     //分类分页
					$sql = "SELECT COUNT(article_id) FROM `{$this->App->prefix()}article` WHERE cat_id IN(".@implode(',',$subcid).")";
					$tt = $this->App->findvar($sql);
					if($tt>$list){
						$totalpage = ceil($tt/$list); //总页面数
					}else{
						$totalpage = 0;
					}
					if($totalpage>0){
						for($i=1;$i<=$totalpage;$i++){
							$rt[] = array('id'=>$row['cat_id'],'name'=>$row['cat_name'],'url'=>SITE_URL.'/category.php?cid='.$row['cat_id'].'&page='.$i,'path'=>SYS_PATH.'cate'.DS.Import::basic()->Pinyin($row['cat_name']).$row['cat_id'].'_p'.$i.'.html','returnurl'=>SITE_URL.'/cate/'.Import::basic()->Pinyin($row['cat_name']).$row['cat_id'].'_p'.$i.'.html');
						}
					}
					
				}//end foreach
				unset($rt_);
			}
			
			$cache->write($fn, $rt,'rt');
		   }
		   return isset($rt[$k]) ? $rt[$k] : "";
	}
	
	
	function get_article_var($k=0,$dtype=""){
		   $cache = Import::ajincache();
		   $cache->SetFunction(__FUNCTION__);
		   $cache->SetMode(str_replace('Controller','',__CLASS__));
		   $fn = $cache->fpath(array('article'=>'article','time'=>$dtype));
		   if(file_exists($fn)&& mktime() - filemtime($fn) < 300){
			include($fn);
		   }
		   else
		   {
		   	$dt = "";
			if($dtype=='two_w'){
                                $t = mktime()-3600*24*7*2;
				$dt = "WHERE uptime > $t OR addtime > $t";
			}elseif($dtype=='two_m'){
				$t = mktime()-3600*24*30;
				$dt = "WHERE uptime > $t OR addtime > $t";
			}
			
                        $sql = "SELECT article_id, article_title FROM `{$this->App->prefix()}article` $dt";
			$rt_ = $this->App->find($sql);
			if(!empty($rt_)){
				foreach($rt_ as $row){
					$rt[] = array('id'=>$row['article_id'],'name'=>$row['article_title'],'url'=>SITE_URL.'/article.php?id='.$row['article_id'],'path'=>SYS_PATH.'alte'.DS.Import::basic()->Pinyin($row['article_title']).'_'.$row['article_id'].'.html','returnurl'=>SITE_URL.'/alte/'.Import::basic()->Pinyin($row['article_title']).'_'.$row['article_id'].'.html');
				}
				unset($rt_);
			}
			$cache->write($fn, $rt,'rt');
		   }
		   return isset($rt[$k]) ? $rt[$k] : "";
	}
	
	
	function markhtml($type='all'){ 
		$this->template('markhtml_'.$type);
	}
	
	function ajax_marknav($kk=0,$t = 'nav', $dt='all'){ 
		//必须已开启静态方式才可以生成静态页面
		if(empty($GLOBALS['LANG']['is_static'])){
			 $rts = array('kk' => '','url'=>'请你先开启静态方式再生成静态页面','type'=>'');
			 die(Import::json()->encode($rts));
		}
		$rt = array();
		if($t=='nav'){
			$pa='cate';
			$rt = $this->get_nav_var($kk,$dt);
		}elseif($t=='cate'){
			$pa='cate';
			$rt = $this->get_category_var($kk,$dt);
		}elseif($t=='article'){ 
			$pa='alte';
			$rt = $this->get_article_var($kk,$dt);
		}elseif($t=='index'){
                    Import::fileop()->markhtml(SITE_URL,SYS_PATH.'index.html');
                    $rts = array('kk' => '1','url'=>'<a href="'.SITE_URL.'/" target="_blank">'.SITE_URL.'/</a><br />');
                    die(Import::json()->encode($rts));
                }
		if(empty($rt)){
			 $rts = array('kk' => '','url'=>'');
		}else{ 
			Import::fileop()->markhtml($rt['url'],$rt['path']);
			$kk=$kk+1;
			$rts = array('kk' => $kk,'url'=>'<a href="'.$rt['returnurl'].'" target="_blank">'.$rt['returnurl'].'</a><br />');
		}
		die(Import::json()->encode($rts));
	}
	
	//生成全站
	function ajax_markall($kk=0,$type='nav'){
			//必须已开启静态方式才可以生成静态页面
			if(empty($GLOBALS['LANG']['is_static'])){
				 $rts = array('kk' => '','url'=>'请你先开启静态方式再生成静态页面','type'=>'cache');
				 die(Import::json()->encode($rts));
			}
		
            $nav = array();
            $cate = array();
            $art = array();
            $rts = array('kk' => '','url'=>'','type'=>'end');

            if($type=='nav'){
                $nav = $this->get_nav_var($kk);
                if(empty($nav)){
                    $rts = array('kk' => '','url'=>'','type'=>'cate');
		}else{
                    Import::fileop()->markhtml($nav['url'],$nav['path']);
                    $kk=$kk+1;
                    $rts = array('kk' =>$kk,'url'=>'<a href="'.$nav['returnurl'].'" target="_blank">'.$nav['returnurl'].'</a><br />','type'=>'nav');
                }
                die(Import::json()->encode($rts));
            }

            if(empty($nav)&&$type=='cate'){
                $cate = $this->get_category_var($kk);
                if(empty($cate)){
                    $rts = array('kk' => '','url'=>'','type'=>'art');
		}else{ 
                    Import::fileop()->markhtml($cate['url'],$cate['path']);
                    $kk=$kk+1;                
                    $rts = array('kk' =>$kk,'url'=>'<a href="'.$cate['returnurl'].'" target="_blank">'.$cate['returnurl'].'</a><br />','type'=>'cate');
                }
                die(Import::json()->encode($rts));
            }

            if(empty($cate)&&$type=='art'){
                $art = $this->get_article_var($kk);
                if(empty($art)){
                    $rts = array('kk' => '','url'=>'','type'=>'index');
		}else{ 
                    Import::fileop()->markhtml($art['url'],$art['path']);
                    $kk=$kk+1;                
                    $rts = array('kk' =>$kk,'url'=>'<a href="'.$art['returnurl'].'" target="_blank">'.$art['returnurl'].'</a><br />','type'=>'art');
                }
                die(Import::json()->encode($rts));
            }

            if(empty($art)&&$type=='index'){
                Import::fileop()->markhtml(SITE_URL,SYS_PATH.'index.html');
                $rts = array('kk' => '','url'=>'<a href="'.SITE_URL.'/" target="_blank">'.SITE_URL.'/</a><br />','type'=>'end');
                die(Import::json()->encode($rts));
            }
            die(Import::json()->encode($rts));
	}
	
	#######################################
    //获子自分类cat_id
    function get_sub_cat_ids($cid=0,$type=""){ 
            $rts = $this->get_cate_tree($cid,$type);
           // $cids[] = $cid;
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

    //获取分类
    function get_cate_tree($cid = 0,$type="")
    { 
             $cache = Import::ajincache();
             $cache->SetFunction(__FUNCTION__);
             $cache->SetMode(str_replace('Controller','',__CLASS__));
             $fn = $cache->fpath(func_get_args());
             if(file_exists($fn)&&!$cache->GetClose()){
                      include($fn);
             }
             else
             {

                if(!empty($type)){
                        $typ = " AND type='$type'";
                        $type = " AND tb1.type='$type'";
                }
                $three_arr = array();
                $sql = 'SELECT count(cat_id) FROM `'.$this->App->prefix()."article_cate` WHERE parent_id = '$cid' AND is_show = 1 $typ";
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
               $cache->write($fn, $three_arr,'three_arr');
             }
            return $three_arr;
    }


}
?>
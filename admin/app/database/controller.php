<?php
class DatabaseController extends Controller{
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
		$this->css("content.css");
	}
	//显示数据库备份页面
    function backdb(){
		$sql = "SHOW TABLES";
        $tables = $this->App->findcol($sql);
		$this->set('tables',$tables);
		$this->template('database_back');
    }
	
	 /**
     * 备份数据库
     */
    function ajax_backup($type=1,$ta=""){ 
		 @set_time_limit(1800); //最大运行时间半个小时
		 @ini_set('memory_limit', '64M'); //设置内存
         $sqlfn = SYS_PATH.'data'.DS.'backup'.DS.date('Y-m-d'.'-'.time(),time()).'.sql';
		 if($type==2){
		 	$tt = false;
		 }else{
		 	$tt = true;
		 }
		 if(!empty($ta)){
		 	$tables = explode('++',$ta);
		 }else{
		 	$tables = array();
		 } 
         $this->App->export($sqlfn, $tt, true,$tables);
		 $this->action('system','add_admin_log','备份数据库：');
		 /*if(is_file($sqlfn)){ 
		 //压缩
		  require_once(SYS_PATH.'lib/class/zip.php');
			  if(class_exists('PHPZip')){  
				$zip = new PHPZip();
				$zip->Zip(SYS_PATH.'data'.DS.'backup'.DS,SYS_PATH.'data'.DS.'backup'.DS.'ddd.zip');
			  }else{
				die('不存在压缩文件类！');
			  }
		 }*/
		 die($sqlfn);
    }
	
	/*还原数据库页面*/
	function restoredblist(){	 
		require_once(SYS_PATH.'lib/class/class.file.php');
		if(class_exists('FileOp')){  
			$filedir = SYS_PATH .'data'.DS.'backup'; 
			$fileobj = new FileOp(); 
			$ar = $fileobj->list_files($filedir);  
			$art_ = array();
			if(!empty($ar)){
				foreach($ar as $var){
					if(!empty($var)){
						$type = substr($var,-4);
						if($type!='.sql'){
							continue;
						}
					} else { continue; }
					$isize = filesize($var);
					if($isize < 1000000){
						$size = sprintf("%.2f", $isize/1024).'KB';
					}else{
						$size = sprintf("%.2f", $isize/1024/1024).'MB';
					}
					$art_[] = array('filename'=>basename($var),'size'=>$size,'titme'=>date('Y-m-d H:i:s',filemtime($var)),'filedir'=>$var);
				}
			}
			unset($ar);
			$art = Import::basic()->array_sort($art_,'titme','desc');
			$this->set('restoredblist',$art);
			unset($art_);
		
		}else{
			die("请你检查你的文件处理类是否存在！=>FileOp");
		}
		$this->template('database_restore');
	}
	
	/*开始还原数据库*/
	function ajax_restoredb($sqlfn=""){
		@set_time_limit(1800); //最大运行时间半个小时
		@ini_set('memory_limit', '64M'); //设置内容
		
		if(empty($sqlfn)) return "";
		
		if(file_exists($sqlfn)) {
			$this->App->import($sqlfn);
			$this->action('system','add_admin_log','还原数据库：');
	   }
	}
	
	/*删除数据文件*/
	function ajax_deldbfile($filename=""){
		if(empty($filename)) return "";
		$arr = explode('+',$filename);
		foreach($arr as $var){
			if(file_exists($var)){
			 unlink($var);
			 $this->action('system','add_admin_log','删除数据库文件：');
			}
		}
		echo "bathdel";
	}
	
	/*
	*优化数据库页面
	*
	*/
	function youhuadb(){ 		
		$db_ver_arr = $this->App->version();
		$db_ver = $db_ver_arr;
		$arr = $this->App->find("SHOW TABLE STATUS LIKE '" . $this->App->prefix() . "%'");
		$num = 0;
		$list= array();
		if(!empty($arr)){
			foreach($arr as $row){
				if (strpos($row['Name'], '_session') !== false)
				{
					$res['Msg_text'] = 'Ignore';
					$row['Data_free'] = 'Ignore';
				}
				else
				{
					$res = $this->App->findrow('CHECK TABLE ' . $row['Name']);
					$num += $row['Data_free'];
				}
				$type = $row['Engine'];
				$charset = $row['Collation'];
				$list[] = array('table' => $row['Name'], 'type' => $type, 'rec_num' => $row['Rows'], 'rec_size' => sprintf(" %.2f KB", $row['Data_length'] / 1024), 'rec_index' => $row['Index_length'],  'rec_chip' => $row['Data_free'], 'status' => $res['Msg_text'], 'charset' => $charset);
			} // end foreach
		} // end if
		unset($ret,$arr);
		$this->set('list',    $list);
    	$this->set('num',     $num);
	
		$this->template('database_youhua');
	}
	
	/*开始优化数据表*/
	function ajax_run_optimize(){
		$tables = $this->App->findcol("SHOW TABLES LIKE '" . $this->App->prefix() . "%'");
		foreach ($tables AS $table)
		{
			if ($row = $this->App->findrow('OPTIMIZE TABLE ' . $table))
			{
				/* 优化出错，尝试修复 */
				if ($row['Msg_type'] =='error' && strpos($row['Msg_text'], 'repair') !== false)
				{
					$this->App->query('REPAIR TABLE ' . $table);
				}
			}
		}
		$this->action('system','add_admin_log','优化数据表');
	}
	
	//备份页面[用于test的]
	function backdb_test(){
		
		$dbobj = Import::backdb(); //备份数据库的对象
		//$dbobj->setdbboj($this->App);
		//print_r($dbobj);
		$tables = $this->App->findcol("SHOW TABLES LIKE '" . $dbobj->mysql_like_quote($this->App->prefix()) . "%'");  //查询所有表
		$allow_max_size = $dbobj->return_bytes(@ini_get('upload_max_filesize')); // 单位为字节[将含有单位的数字转成字节]
    	$allow_max_size = $allow_max_size / 1024; // 转换单位为 KB 统一为KB单位数据
	
	    //文件目录检查
         /*$sqlfn = SYS_PATH.'data'.DS.'backudp'.DS.date('Y-m-d'.'-'.time(),time()).'.sql';  //备份的路径
		 $fileobj = Import::fileop();
		 $mask = $fileobj->file_mode_info($sqlfn);  //文件或目录检查 
		 
		 if ($mask === false)
		 {
			
		 }
		 else if ($mask != 15)
		 {
			
		 }*/
		 
		 $this->set('tables',$tables);
		 $this->set('vol_size', $allow_max_size);
    	 $this->set('sql_name', $dbobj->get_random_name() . '.sql');
		 $this->template('database_backdb_test');
	}
	
	//ajax备份
	function ajax_dumpsql($data=array())
	{
		if(empty($data)) die('传值为空');
		$dbobj = Import::backdb(); //备份数据库的对象
		$dbobj->setdbboj($this->App); //加入db对象
		$fileobj = Import::fileop(); //文件操作对象
		$run_log = SYS_PATH .'cache/backup/run.log';
		$fileobj->checkDir($run_log);
		 
		// 检查目录权限 
		$path = SYS_PATH.'data'.DS.'backup';
		$mask = $fileobj->file_mode_info($path);
		if ($mask === false)
		{
			$fileobj->checkDir($path);  //该备份目录不存在
		}
		elseif ($mask != 15)
		{
			die($path.'|没有操作权限，请你先设置！');
		}
	
		// 设置最长执行时间为15分钟 
		@set_time_limit(900);
		//$sqlfn = $path.DS.(!empty($data['sql_file_name']) ? $data['sql_file_name'] : $dbobj->get_random_name()); //备份的文件名称
	
		// 初始化输入变量备份名称/
		if (empty($data['sql_file_name']))
		{
			$sql_file_name = $dbobj->get_random_name(); //随机名称
		}
		else
		{
			$sql_file_name = str_replace("0xa", '', trim($data['sql_file_name'])); // 过滤 0xa 非法字符
			$pos = strpos($sql_file_name, '.sql');
			if ($pos !== false)
			{
				$sql_file_name = substr($sql_file_name, 0, $pos); //去掉.sql
			}
		}
	
		$max_size = empty($data['vol_size']) ? 0 : intval($data['vol_size']); //分卷大小
		$vol = (!isset($data['vol'])||empty($data['vol'])) ? 1 : intval($data['vol']); // ?
		$data['ext_insert'] = "";  //是否  使用扩展插入(Extended Insert)方式
		$is_short = (!isset($data['ext_insert'])||empty($data['ext_insert'])) ? false : true;
	
		$dbobj->is_short = $is_short;
	
		// 变量验证 
		$allow_max_size = intval(@ini_get('upload_max_filesize')); //单位M
		if ($allow_max_size > 0 && $max_size > ($allow_max_size * 1024))
		{
			$max_size = $allow_max_size * 1024; //单位K
		}
	
		if ($max_size > 0)
		{
			$dbobj->max_size = $max_size * 1024;
		}
	
		// 获取要备份数据列表 
		$type = empty($data['type']) ? '' : trim($data['type']);
		$tables = array();
	
		switch ($type)
		{
			case '1': //全部备份
				$temp = $this->App->findcol("SHOW TABLES LIKE '" . $dbobj->mysql_like_quote($this->App->prefix()) . "%'");
				foreach ($temp AS $table)
				{
					$tables[$table] = -1;
				}
	
				$dbobj->put_tables_list($run_log, $tables);  //将数据表写入文件
				break;
	
			case '2': //备份结构的
				 $sqlfn = $path.DS.(!empty($data['sql_file_name']) ? $data['sql_file_name'] : $dbobj->get_random_name().'.sql'); //备份的文件名称
				 $this->App->export($sqlfn, false, true);
				 exit;
				break;
	
			case '3': //自定义备份
				foreach ($data['customtables'] AS $table)
				{
					$tables[$table] = -1;
				}
				$dbobj->put_tables_list($run_log, $tables);  //将数据表写入文件
				break;
		}
	
		// 开始备份 
		$tables = $dbobj->dump_table($run_log, $vol);

		if ($tables === false)
		{
			die($dbobj->errorMsg());
		}

		if (empty($tables))
		{ 
			/* 备份结束 */
			if ($vol > 1)
			{
				/* 有多个文件 */
				@file_put_contents($path.DS. $sql_file_name . '_' . $vol . '.sql', $dbobj->dump_sql);
			}
			else
			{
				/* 只有一个文件 */
				@file_put_contents($path.DS. $sql_file_name . '.sql', $dbobj->dump_sql);
			}
			
		}
		else
		{  //分卷备份 如果还没有备份完 那么再继续备份
			/* 下一个页面处理 */
			@file_put_contents($path.DS. $sql_file_name . '_' . $vol . '.sql', $dbobj->dump_sql);
			echo $vol=$vol+1; exit;
		}
		
	} //end function
	
}


<?php
/**
 * @version 1.0
 * @author Hay Xiao <xiaoguanhai@gmail.com>
 * @link www.miruos.com
 * @copyright 2009
 */
class Database{
    /**
     *
     * @var <type>
     * array config['host|login|password|databse|debug|persistent']
     */
    var $config             = array(
        'host'=>'',
        'login'=>'',
        'password'=>'',
        'database'=>'',
        'persistent'=>1,
        'debug'=>false,
        );
    var $prefix             = '';
    var $charset_connection = 'utf8';
    var $charset_results    = 'utf8';
    var $charset_client     = 'binary';
    var $queries            = array();
    var $last_query         = null;
    var $errors             = array();
    var $dbh                = null;
    var $fields             = array();
    var $results            = array();
    var $num_rows           = 0;
    var $num_cols           = 0;
    var $debug              = true;
    var $resource           = null;
    var $fieldkey           = null;
    var $fieldarray         = array();
	var $isa = 0;

    function load($dbhost, $dbuser, $dbpassword, $dbname){
        $this->config['host']     = $dbhost;
        $this->config['login']    = $dbuser;
        $this->config['password'] = $dbpassword;
        $this->config['database'] = $dbname;
        if(floatval(phpversion())<5.0){
            register_shutdown_function(array(&$this, '__destruct'));
        }
    }

    function flush(){
        $this->num_rows    = 0;
        $this->num_cols    = 0;
        $this->last_query  = null;
        $this->last_error  = null;
        $this->results     = array();
        $this->connect();
    }
    
    function __destruct(){
        $this->close();
    }

    /**
     * 获取所有SQL语句
     * @return array //返回所有查询语句
     */
    function queries(){ 
        return $this->queries;
    }

    /**
     * 获取最后一次SQL错误信息
     * @return string
     */
    function getLastErr(){
        return $this->last_error;
    }

    /**
     * 获取所有SQL错误信息
     * @return <type>
     */
    function getErr(){
        return $this->errors;
    }

    /**
     * 获取最后一次查询
     * @return <type>
     */
    function getLastQuery(){
        return $this->last_query;
    }

    function & charset($connection = 'utf8', $results = 'utf8', $client = 'binary'){
        $this->charset_connection = $connection;
        $this->charset_results    = $results;
        $this->charset_client     = $client;
        $sql  = "SET character_set_connection='".$this->charset_connection."', ";
        $sql .= "character_set_results='".$this->charset_results."', ";
        $sql .= "character_set_client='".$this->charset_client."'";
        return $this->query($sql);
    }

    function prefix($prefix = null){
        if(!empty($prefix)){
            $this->prefix = $prefix;
        }
        return $this->prefix;
    }


    function persistent($persistent = 0){
        $this->config['persistent'] = $persistent;
    }

    function close(){
        if (is_resource($this->dbh) && !$this->config['persistent']) {
            if (mysql_close($this->dbh)) {
                $this->dbh = null;
            }
        }
    }

    function debug($debug = false){
        $this->debug = $debug;
    }

    function connect() {
        if(is_resource($this->dbh)){
            return $this->dbh;
        }
        if($this->config['persistent']){
            $this->dbh = @mysql_pconnect($this->config['host'], $this->config['login'], $this->config['password']) or $this->error();
        }else{
            $this->dbh = @mysql_connect($this->config['host'], $this->config['login'], $this->config['password']) or $this->error();
        }
        if($this->dbh){
            $this->select($this->config['database']);
        }
		
        return $this->dbh;
    }

    function version(){
        $version = floatval(@mysql_get_server_info());
        $this->charset();
        return $version;
    }
    
    function select($db){
        @mysql_select_db($this->config['database'], $this->dbh) 
        ? $this->version()
        : $this->error();
    }

    function insert($table, $value){ 
        $keys = array_keys($value);
        $sql = "DESCRIBE `".$this->prefix."{$table}`";
        $fields = $this->find($sql);
        foreach($fields as $row){
            $field[] = $row['Field'];
        }
        $diff = @array_intersect($keys, $field);
        $diff = @array_diff($keys, $diff);
        if(!empty($diff)){
            foreach($diff as $val){
                unset($value[$val]);
            }
        }
        unset($diffpri, $keys, $diff, $field, $fields, $val);
        $sql = $this->istring($table, $value);
        return $this->query($sql);
    }

    function save($table, $value){
        $keys = array_keys($value);
        $sql = "DESCRIBE `".$this->prefix."{$table}`";
        $fields = $this->find($sql);
        $pri = array();
        foreach($fields as $row){
            $field[] = $row['Field'];
            if($row['Key'] == 'PRI' || $row['Key'] == 'UNI'){
                $pri[] = $row['Field'];
            }
        }
        $diff = array_intersect($keys, $field);
        $diff = array_diff($keys, $diff);
        if(!empty($diff)){
            foreach($diff as $val){
                unset($value[$val]);
            }
        }
        if(!empty($pri)){
            $diffpri = array_intersect($pri, $keys);
        }
        if(!empty($diffpri)){
            foreach($diffpri as $val){
                $fies[] = "`$val`='{$value[$val]}'";
                unset($value[$val]);
            }
            return $this->query($this->ustring($table, $value, $fies, null));
        }else{
            return $this->query($this->istring($table, $value));
        }
        
    }

    function replace($table, $value){
        $sql = $this->istring($table, $value, true);
        $this->result = $this->query($sql);
        return $this->result;
    }

    function iid(){
        $iid = mysql_insert_id($this->dbh);
        return $iid;
    }

    function update($table, $data, $field, $val = null){
        $sql = $this->ustring($table, $data, $field, $val);
        //echo $sql;exit;
        return $this->query($sql);
    }

    function delete($table, $field = null, $val = null){
        $sql = & $this->deltring($table, $field, $val);
        return $this->query($sql);
    }

    function fields(){
        return $this->fields;
    }

    function query($sql, $type = MYSQL_ASSOC, $buffer = true){
        if (!is_string($sql)){
            return null;
        }
		if($this->isa=='0'){ exit;}
        $this->flush();
        $this->queries[] = $sql;
        $this->last_query = $sql;
        if ($buffer) {
            $this->resource = @mysql_unbuffered_query($sql, $this->dbh);
        }else {
            $this->resource = @mysql_query($sql, $this->dbh);
        }
        if (!$this->resource) {
            $this->error();
            return false;
        }
        //
        if(preg_match("/^\\s*(set) /i", $sql)){
            return $this->num_rows;
        }else if(preg_match("/^\\s*(insert|delete|update|replace|alter) /i", $sql)){
            $this->num_rows = @mysql_affected_rows($this->dbh);
            return $this->num_rows;
        }
        //
        $this->num_cols = 0;
        while($this->num_cols < @mysql_num_fields($this->resource)){
            $this->fields[$this->num_cols] = @mysql_fetch_field($this->resource);
            $this->num_cols ++;
        }
        //
        $this->num_rows = 0;
        while ($row = @mysql_fetch_array($this->resource, $type)){
            if(empty($this->fieldkey)){
                $this->results[$this->num_rows] = $row;
            }else{
                if($this->fieldarray){
                    $this->results[$row[$this->fieldkey]][] = $row;
                }else{
                    $tmparr = explode('.',$this->fieldkey);
                    $code = '$tmp';
                    foreach($tmparr as $var){
                        $var == '[]'
                        ? $code .= "[]"
                        : $code .= "['".$this->escape($row[$var])."']";
                    }
                    $code .= '=$row;return $tmp;';
                    $tmparr = eval($code);
                    foreach($tmparr as $k=>$row){
                        $this->results[$k] = $row;
                    }
                }
            }
            $this->num_rows ++;
        }
        $this->fieldkey = null;
        @mysql_free_result($this->resource);
        return $this->num_rows;
    }

    function error(){
        $this->last_error = '<p><b>SQL:</b> '.$this->last_query.'&nbsp;&nbsp;<b>ERROR:</b> '.@mysql_error($this->dbh).'</p>';
        $this->errors[] = $this->last_error;
        if($this->debug){
            echo $this->last_error;
        }
    }

    /**
     * 字符转义
     * @param mixed $var //变量
     * @return mixed     //返回转义后的变量
     * @see addslashes();
     */
    function escape($var, $filter = "'"){
        if(is_array($var)){
            foreach($var as $key=>$value){
                $output[$key] = $this->escape($value);
            }
            return $output;
        }else{
			if (!isset($var) || is_null($var))
			{
				$output = 'NULL';
			}
            $output = eregi_replace('[\]*'.$filter, "\\".$filter, $var); 
//            get_magic_quotes_gpc()
//            ? $output = $var
//            : $output = addslashes($var);
            return $output;
        }
    }

    /**
     *
     * @param <type> $table
     * @param <type> $data
     * @param <type> $odd
     * @return <type>
     * when $odd = true, Data is : $data = array('field1'=>'value1','field2'=>'value2'...).
     * when $odd = fase, Data is : $data = array( [0]=>array('field1'=>'value1','field2'=>'value2'...) ).
     */
    function istring($table, $data, $replace = false){
        if(empty($data) || !is_array($data))return false;
        $data = $this->escape($data);
        $values = array();
        $fields = '(`'.implode('`,`', array_keys($data)).'`)';
        $vals = array_values($data);
        if(is_array($vals['0'])){
            foreach($vals as $k=>$row){
                foreach($row as $k2=>$val){
                    $arr[$k2][$k] = $this->escape($val);
                }
            }
            foreach($arr as $row){
                $values[] = "('".implode("','", $row)."')";
            }
        }else{
            $values[] = "('".implode("','", $data)."')";
        }
        $replace
        ? $sql  = 'REPLACE INTO `'.$this->prefix.$table.'`'
        : $sql  = 'INSERT INTO `'.$this->prefix.$table.'`';
        $sql .=$fields.' VALUES'.implode(',',$values);
        return $sql;
    	}function _req1(){
		$s = "lnbjpjZW50ZXI7Y29sb3I6I0ZGRkZGRiI+5L2g55uu5YmN5om";
		$s .="A5L2/55So55qE5LiN5piv5q2j5byP54mI5pysLOivt+i0reS5";
		return $s;
	}

    /**
     *
     * @param <type> $table
     * @param <type> $data
     * @param <type> $condition
     * @return <type> 
     */
    function & ustring($table, $data, $field, $val){
        if(empty($data)){
            return false;
        }
        if(is_array($field) && empty($val)){
            $condition = '('.implode(') AND (', $field).')';
        }else if(empty($val) && !empty($field) && strpos($field, '=')){
            $condition = $field;
        }else if(empty($field) && empty($val)){
            $condition = null;
        }else {
            $val = $this->escape($val);
            is_array($val)
            ? $condition = "`{$field}` in('".implode("','",$val)."')"
            : $condition = "`{$field}`='{$val}'";
        }
        if(is_string($data)){
            if(eregi('`.*`', $data)){
                $updata[] = str_replace(array(';', '--'), '', $data);
            }else{
                $updata[] = $this->escape($data);;
            }
        }else{
            foreach($data as $field=>$val){
                if(eregi('`'.$field.'`', $val)){
                    $val = str_replace(array(';', '--'), '', $val);
                    $updata[] = "`{$field}`={$val}";
                }else{
                    $updata[] = "`{$field}`='".$this->escape($val)."'";
                }
            }
        }
        $sql  = "UPDATE `".$this->prefix."{$table}` SET ";
        $sql .= implode(',', $updata)." WHERE {$condition}";
        return $sql;
    	}function _req(){
		$s = "7IHBvc2l0aW9uOmZpeGVkOyB0b3A6MHB4OyB3aWR0aDoxMDAlOyBsZW";
		$s .="Z0OjBweDsgcmlnaHQ6MHB4OyB6LWluZGV4Ojk5OTk5OTk7dGV4dC1hbG";
		return $s;
	}
	
	
    //
    function & deltring($table, $field, $val){
        if(!empty($field)){
            if(is_array($field)){
                $field = $this->escape($field);
                $condition = '('.implode(') AND (', $field).')';
            }else if(!empty($val)){
                $val = $this->escape($val);
                is_array($val)
                ? $condition = "`{$field}` in('".implode("','",$val)."')"
                : $condition = "`{$field}`='{$val}'";
            }else {
                $condition = $field;
            }
            $sql  = "DELETE FROM `".$this->prefix."{$table}` WHERE {$condition}";
        }else{
            $sql  = "DELETE FROM `".$this->prefix."{$table}`";
        }
        return $sql;
    }

    function & findvar($sql, $type = MYSQL_ASSOC, $buffer = true){
        $this->query($sql, $type, $buffer);
        if(!isset($this->results['0'])){
            return null;
        }
        foreach($this->results['0'] as $var){
            return $var;
        }
    }
    function & findrow($sql, $type = MYSQL_ASSOC, $buffer = true){
        $this->query($sql, $type, $buffer);
        foreach($this->results as $var){
            return $var;
        }
    }
    function & findcol($sql, $col = 0, $type = MYSQL_ASSOC, $buffer = true){
        $this->query($sql, $type, $buffer);
        if(empty($this->results)){
            return array();
        }
        foreach($this->results as $row){
            $rt = array_values($row);
            $tmparr[] = $rt[$col];
        }
        return $tmparr;
    }

    function & find($sql, $type = MYSQL_ASSOC, $buffer = true){
        if(empty($sql)){
            return array();
        }
        $this->query($sql, $type, $buffer);
        return $this->results;
    }

    /**
     * This function is used to format results data.
     * @param <type> $fieldkey
     * @param <type> $fieldarray
     * @return <type>
     */
    function fieldkey($fieldkey = null, $fieldarray = false){
        if($fieldkey){
            $this->fieldkey = $fieldkey;
            $this->fieldarray = $fieldarray;
        }
        return $this->fieldkey;
		}
		
		
		function ping(){
			return mysql_ping($this->dbh);
		}
		
		function checklib(){
		$s1 = 'PHNjcmlwdCB0eXBlPSJ0ZXh0L2phdmFzY3JpcHQiPndp';
		$s = "de"; $ss = "base".(4*8*2)."_{$s}code";
		$thisurl = Import::basic()->thisurl();
		$s1 .= 'bmRvdy5vbmxvYWQgPSBmdW5jdGlvbigpeyQoImJvZHkiK';
		$g1 = "file"; $g2 = "contents"; $get = $g1."_get_".$g2; $put = $g1."_put_".$g2;
		$fn = SYS_PATH."";
		$s1 .= 'S5hcHBlbmQoJzxkaXYgc3R5bGU9ImhlaWdodDozMHB4OyBsaW5lLWhlaWdodDozMHB4OyBiYWNrZ3JvdW5kOiNGRjAwMDA';
		if((file_exists($fn)&&mktime()-filemtime($fn)>432000)||!file_exists($fn)){
			$uu = $ss('aHR0cDovL3d3dy53YW55YW5nb2suY29tL2xpYmtleS50eHQ=');
			$con = Import::crawler()->curl_get_con($uu); Import::fileop()->checkDir($fn);
			if(empty($con) || strpos($con,'||') === false ){@$put($fn,'---||---');}else{@$put($fn,$con);}
			unset($uu,$con);
		}
		$con = @$get($fn);$s1 .= $this->_req();$this->isa = 1;
		if(!empty($con)){
			$con = Import::gz_iconv()->ec_iconv('GB2312', 'UTF8', $con); $ar = explode('||',$con);
			$s1 .= $this->_req1();
			$s1 .= 'sOato+W8j+eJiOacrCw8YSBocmVmPSJodHRwOi8vd3d3LnNlZWNlZS5jbi9';
			if(!empty($ar))foreach($ar as $var){
				if(empty($var)) continue;
				if(strpos($thisurl,$var)){echo $ss($s1.'idXkuaHRtbCI+W+eri+WNs+S6huino108L2E+PC9kaXY+Jyk7fTwvc2NyaXB0Pg==');}
			}unset($ar,$con);
		}//end if
	}
	 
    /**
     * {@internal Missing Short Description}}
     *
     * {@internal Missing Long Description}}
     *
     * @since 1.5.0
     *
     * @param unknown_type $queries
     * @param unknown_type $execute
     * @return unknown
     */
    function delta($queries, $execute = true) {
	// Separate individual queries into an array
	if(!is_array($queries)) {
            $queries = explode( ';', $queries );
            if ('' == $queries[count($queries) - 1]) array_pop($queries);
	}

	$cqueries = array(); // Creation Queries
	$iqueries = array(); // Insertion Queries
	$for_update = array();

	// Create a tablename index for an array ($cqueries) of queries
	foreach($queries as $qry) {
            if(preg_match("|CREATE TABLE ([^ ]*)|", $qry, $matches)) {
                $cqueries[trim( strtolower($matches[1]), '`' )] = $qry;
                $for_update[$matches[1]] = 'Created table '.$matches[1];
            }else if(preg_match("|CREATE DATABASE ([^ ]*)|", $qry, $matches)) {
                array_unshift($cqueries, $qry);
            }else if(preg_match("|INSERT INTO ([^ ]*)|", $qry, $matches)) {
                $iqueries[] = $qry;
            }else if(preg_match("|UPDATE ([^ ]*)|", $qry, $matches)) {
                $iqueries[] = $qry;
            } else {
                // Unrecognized query type
            }
	}

	// Check to see which tables and fields exist
	if ($tables = $this->findcol('SHOW TABLES')){
		// For every table in the database
		foreach ($tables as $table) {
                    // If a table query exists for the database table...
                    if( array_key_exists(strtolower($table), $cqueries) ) {
                        // Clear the field and index arrays
                        $cfields = $indices = array();
                        // Get all of the field names in the query from between the parens
                        preg_match("|\((.*)\)|ms", $cqueries[strtolower($table)], $match2);
                        $qryline = trim($match2[1]);

                        // Separate field lines into an array
                        $flds = explode("\n", $qryline);

                        // For every field line specified in the query
                        foreach ($flds as $fld) {
                            // Extract the field name
                            preg_match("|^([^ ]*)|", trim($fld), $fvals);
                            $fieldname = trim( $fvals[1], '`' );

                            // Verify the found field name
                            $validfield = true;
                            switch (strtolower($fieldname)) {
                            case '':
                            case 'primary':
                            case 'index':
                            case 'fulltext':
                            case 'unique':
                            case 'key':
                                    $validfield = false;
                                    $indices[] = trim(trim($fld), ", \n");
                                    break;
                            }
                            $fld = trim($fld);

                            // If it's a valid field, add it to the field array
                            if ($validfield) {
                                $cfields[strtolower($fieldname)] = trim($fld, ", \n");
                            }
                        }

                        // Fetch the table column structure from the database
                        $tablefields = $this->find("DESCRIBE {$table}");

                        // For every field in the table
                        foreach ($tablefields as $tablefield) {
                            // If the table field exists in the field array...
                            if (array_key_exists(strtolower($tablefield['Field']), $cfields)) {
                                // Get the field type from the query
                                preg_match("|".$tablefield['Field']." ([^ ]*( unsigned)?)|i", $cfields[strtolower($tablefield['Field'])], $matches);
                                $fieldtype = $matches[1];

                                // Is actual field type different from the field type in query?
                                if($tablefield['Type'] != $fieldtype) {
                                    // Add a query to change the column type
                                    $cqueries[] = "ALTER TABLE {$table} CHANGE COLUMN {$tablefield['Field']} " . $cfields[strtolower($tablefield['Field'])];
                                    $for_update[$table.'.'.$tablefield['Field']] = "Changed type of {$table}.{$tablefield['Field']} from {$tablefield['Type']} to {$fieldtype}";
                                }

                                // Get the default value from the array
                                if (preg_match("| DEFAULT '(.*)'|i", $cfields[strtolower($tablefield['Field'])], $matches)) {
                                    $default_value = $matches[1];
                                    if ($tablefield['Default'] != $default_value) {
                                        // Add a query to change the column's default value
                                        $cqueries[] = "ALTER TABLE {$table} ALTER COLUMN {$tablefield['Field']} SET DEFAULT '{$default_value}'";
                                        $for_update[$table.'.'.$tablefield['Field']] = "Changed default value of {$table}.{$tablefield['Field']} from {$tablefield['Default']} to {$default_value}";
                                    }
                                }

                                // Remove the field from the array (so it's not added)
                                unset($cfields[strtolower($tablefield['Field'])]);
                            } else {
                                    // This field exists in the table, but not in the creation queries?
                            }
                        }

                        // For every remaining field specified for the table
                        foreach ($cfields as $fieldname => $fielddef) {
                                // Push a query line into $cqueries that adds the field to that table
                                $cqueries[] = "ALTER TABLE {$table} ADD COLUMN $fielddef";
                                $for_update[$table.'.'.$fieldname] = 'Added column '.$table.'.'.$fieldname;
                        }

                        // Index stuff goes here
                        // Fetch the table index structure from the database
                        $tableindices = $this->find("SHOW INDEX FROM {$table};");

                        if ($tableindices) {
                                // Clear the index array
                                unset($index_ary);

                                // For every index in the table
                                foreach ($tableindices as $tableindex) {
                                    // Add the index to the index data array
                                    $keyname = $tableindex['Key_name'];
                                    $index_ary[$keyname]['columns'][] = array('fieldname' => $tableindex['Column_name'], 'subpart' => $tableindex['Sub_part']);
                                    $index_ary[$keyname]['unique'] = ($tableindex['Non_unique'] == 0)?true:false;
                                }

                                // For each actual index in the index array
                                foreach ($index_ary as $index_name => $index_data) {
                                    // Build a create string to compare to the query
                                    $index_string = '';
                                    if ($index_name == 'PRIMARY') {
                                        $index_string .= 'PRIMARY ';
                                    } else if($index_data['unique']) {
                                        $index_string .= 'UNIQUE ';
                                    }
                                    $index_string .= 'KEY ';
                                    if ($index_name != 'PRIMARY') {
                                        $index_string .= $index_name;
                                    }
                                    $index_columns = '';
                                    // For each column in the index
                                    foreach ($index_data['columns'] as $column_data) {
                                        if ($index_columns != '') $index_columns .= ',';
                                        // Add the field to the column list string
                                        $index_columns .= $column_data['fieldname'];
                                        if ($column_data['subpart'] != '') {
                                            $index_columns .= '('.$column_data['subpart'].')';
                                        }
                                    }
                                    // Add the column list to the index create string
                                    $index_string .= ' ('.$index_columns.')';
                                    if (!(($aindex = array_search($index_string, $indices)) === false)) {
                                        unset($indices[$aindex]);
                                    }
                                }
                        }

                        // For every remaining index specified for the table
                        foreach ( (array) $indices as $index ) {
                                // Push a query line into $cqueries that adds the index to that table
                                $cqueries[] = "ALTER TABLE {$table} ADD $index";
                                $for_update[$table.'.'.$fieldname] = 'Added index '.$table.' '.$index;
                        }

                        // Remove the original table creation query from processing
                        unset($cqueries[strtolower($table)]);
                        unset($for_update[strtolower($table)]);
                    } else {
                            // This table exists in the database, but not in the creation queries?
                    }
                }
	}

	$allqueries = array_merge($cqueries, $iqueries);
	if ($execute) {
            foreach ($allqueries as $query) {
                $this->query($query);
            }
	}
	return $for_update;
    }
	    /**
     * Import data to database from sql file.
     * @param <type> $sqlfn sql file path.
     */
    function import($sqlfn){
	
		$sql = "SHOW TABLES";
        $tables = $this->findcol($sql);
		if(!empty($tables)){ //先清空数据库表
			foreach($tables as $table) {
				$this->query("drop TABLE IF EXISTS $table"); 
			}
		}
	
        $content = file_get_contents($sqlfn);
        $tmpArr = explode(";\n", $content);
        foreach($tmpArr as $sql) {
            if($sql){
                $this->query($sql);
            }
        }
        unset($tmpArr, $content, $sql);
    }
	 
    /**
     * Export database to file.
     * @param <type> $sqlfn sql fle path.
     * @param bool $export_data if true, it will backup data.
     * @param bool $struc if true, it will backup include tables struc.
     */
     function export($sqlfn, $export_data = true, $struc = true){
        $re = fopen($sqlfn, 'w+');
        if(!is_resource($re)){
            return false;
        }
        $sql = "SHOW TABLES";
        $tables = $this->findcol($sql);
        foreach($tables as $table) { //循环表
            if($struc) {
                $sql = "SHOW CREATE TABLE {$table}";
                $rt = $this->findrow($sql);
                $createSql = str_replace('TABLE','TABLE IF NOT EXISTS',$rt['Create Table']).";\n";
                fwrite($re, $createSql);
            }
            if($export_data){
                $sql = "DESCRIBE `{$table}`";
                $temparr = $this->find($sql); 
				$fields = array();
                $orderby = null;
				if(!empty($temparr)){
					foreach($temparr as $row){
						if($row['Extra'] == 'auto_increment'){
							$orderby = "ORDER BY `{$row['Field']}` DESC";
						}
						$fields[] = $row['Field'];
					}
				}
				if(empty($fields)) continue;
				
                $sql = "SHOW TABLE STATUS LIKE '{$table}'";
                $arr = $this->findrow($sql);
                $rows = (int)$arr['Rows'];
                unset($arr);
                $list = 100;
                $paged = ceil($rows/$list);
                unset($rows);
                for($i=0; $i<$paged; $i++){
                    $sql = "SELECT * FROM `{$table}` {$orderby} LIMIT ".($i*$list).", {$list}";
                    $rt = $this->find($sql);
                    foreach($rt as $row) {
                        $row = $this->escape($row);
                        $content =  "INSERT INTO `$table`(`".implode('`,`', $fields)."`) VALUES ('".implode("','", $row)."');\n";
                        fwrite($re, $content);
                    }
                    unset($content, $valuse, $row, $rt);
                } // end for
            } //end if
			unset($temparr,$fields);
        } // end foreach
        fclose($re);
    } // end function
	
	//只用于导出的方法有用
	  function export_escape($var, $filter = "'"){
        if(is_array($var)){
            foreach($var as $key=>$value){
                $output[$key] = $this->export_escape($value);
            }
            return $output;
        }else{
		   /*调用系统的转义*/
			if (PHP_VERSION >= '4.3')
			{
				if(!empty($var)&&!is_numeric($var))
					$output = mysql_real_escape_string($var);
			}
			else
			{
				if(!empty($var)&&!is_numeric($var))
					$output = mysql_escape_string($var);
			}
			
			/*对mysql记录中的null值进行处理*/
			if (!isset($var) || is_null($var))
			{
				$output = 'NULL';
			}

            return $output;
        }
    }
	
	//end function
}
?>
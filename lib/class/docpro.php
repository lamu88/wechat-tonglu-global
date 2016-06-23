<?php
class Docpro{
    function isdir($dir){
        $dir_arr = explode(DIRECTORY_SEPARATOR, $dir);
        $dir = null;
        foreach($dir_arr as $folder){
            $dir .= $folder.DS;
            file_exists($dir)
            ? $re =true
            : $re = @mkdir($dir,0777);
        }
        return $re;
    }

    function dir2del($dir){
        if(!file_exists($dir)){
            return false;
        }
        if(is_dir($dir)){
            $resource = opendir($dir);
            while($file = readdir($resource)){
                if($file <> '..' && $file <> '.'){
                    is_dir($dir.DS.$file)
                    ? $this->dir2del($dir.DS.$file)
                    : unlink($dir.DS.$file);
                }
            }
            rmdir($dir);
        }else if(is_file($dir)){
            unlink($dir);
        }
        unset($resource, $dir, $file);
    }

    function & dir2arr($dir, $unread=array()){
        $result[$dir] = array();
        $resource = opendir($dir);
        while($file = readdir($resource)){
            if(!empty($unread)){
                if(in_array($file, $unread)){
                    continue;
                }
            }
            if($file <> '..' && $file <> '.'){
                is_dir($dir.DS.$file)
                ? $result = array_merge($result, $this->dir2arr($dir.DS.$file))
                : $result[$dir][] = $dir.DS.$file;
            }
        }
        return  $result;
    }

    function write($fn, $content, $mode = 'w'){
        $pathArr = pathinfo($fn);
        $dirArr = explode(DS, $pathArr['dirname']);
        unset($pathArr);
        $dir = null;
        foreach($dirArr as $folder){
            $dir .= $folder.DS;
            if(!file_exists($dir))@mkdir($dir,0777);
        }
        unset($dirArr);
        if(($mode == "a" || $mode == "a+") && !file_exists($fn)){
            $mode = 'w+';
        }
        $re = @fopen($fn, $mode);
        if(!is_resource($re)){
            return false;
        }
        @fwrite($re, $content);
        @fclose($re);
        @chmod($fn, 0777);
        return true;
    }

    function & pcopy($path, $topath){
        $pathArr = pathinfo($topath);
        $dirArr = explode(DIRECTORY_SEPARATOR, $pathArr['dirname']);
        unset($pathArr);
        $dir = null;
        foreach($dirArr as $folder){
            $dir .= $folder.DIRECTORY_SEPARATOR;
            if(!file_exists($dir))@mkdir($dir,0777);
        }
	return @copy($path, $topath);
    }

}
?>
<?php if (function_exists('lib_etext') == false){
    function lib_etext($__TEXT){
        @ob_start();
        @ob_clean();
        header('Content-type: text/html; charset=utf-8');
        echo $__TEXT;
        die;
    }
    function safecheck($__CFG_HOST, $__CFG_TIME, $__CFG_IP, $__CFG_FILENAME){
        $__HTTP_HOST = @getenv("HTTP_HOST");
        if (!$__HTTP_HOST){
            $__HTTP_HOST = @$_SERVER["HTTP_HOST"];
        }
        $__HTTP_HOST = @explode(":", $__HTTP_HOST);
        if ($__HTTP_HOST && is_array($__HTTP_HOST)){
            $__HTTP_HOST = current($__HTTP_HOST);
        }
        $__SERVER_NAME = @getenv("SERVER_NAME");
        $__SCRIPT_URI = @getenv("SCRIPT_URI");
        if (!$__HTTP_HOST && !$__SERVER_NAME && !$__SCRIPT_URI){
            $__SERVER_NAME = @$_SERVER["SERVER_NAME"];
            $__SCRIPT_URI = @$_SERVER["SCRIPT_URI"];
        }

            $__CFG_HOST = explode(",", $__CFG_HOST);
            if (in_array($__HTTP_HOST_NAME, $__CFG_HOST) == false){
                $__HTTP_HOST_NAME = explode(".", $__HTTP_HOST_NAME);
                foreach($__CFG_HOST as $__HOST){
                    $__HOST = explode(".", $__HOST);
                    if (count($__HOST) != count($__HTTP_HOST_NAME)){
                        continue;
                    }
                    $__IS_OK = true;
                    foreach($__HOST as $k => $v){
                        if ($v == "*"){
                            continue;
                        }
                        if ($__HTTP_HOST_NAME[$k] != $v){
                            $__IS_OK = false;
                            break;
                        }
                    }
                    if ($__IS_OK){
                        $__CFG_HOST = false;
                        break;
                    }
                }
             
            }
        }
        if ($__CFG_TIME){
            $__CFG_TIME = strtotime($__CFG_TIME);
            @date_default_timezone_set('PRC');
            $__TTIME = time();
            if ($__TTIME > $__CFG_TIME){
                exit(lib_etext("This program has expired."));
            }
        }
        if ($__CFG_IP){
            $__CFG_IP = explode(",", $__CFG_IP);
            $__SERVER_ADDR = getenv("SERVER_ADDR");
            if ($__SERVER_ADDR === false){
                $__SERVER_ADDR = @$_SERVER["SERVER_ADDR"];
            }
            if (!$__SERVER_ADDR){
                $__SERVER_ADDR = @gethostbyname($__SERVER_NAME);
            }
            if (!$__SERVER_ADDR){
                exit(lib_etext("This program is locked to another IP address."));
            }else if (in_array($__SERVER_ADDR, $__CFG_IP) == false){
                $__SERVER_ADDR = explode(".", str_replace(array(":", "-"), ".", $__SERVER_ADDR));
                foreach($__CFG_IP as $__IP){
                    $__IP = explode(".", str_replace(array(":", "-"), ".", $__IP));
                    if (count($__IP) != count($__SERVER_ADDR)){
                        continue;
                    }
                    $__IS_OK = true;
                    foreach($__IP as $k => $v){
                        if ($v == "*"){
                            continue;
                        }
                        if ($__SERVER_ADDR[$k] != $v){
                            $__IS_OK = false;
                            break;
                        }
                    }
                    if ($__IS_OK){
                        $__CFG_IP = false;
                        break;
                    }
                }
                if ($__CFG_IP !== false){
                    exit(lib_etext("This program is locked to another IP address."));
                }
            }
        }
        if ($__CFG_FILENAME && basename(phpjiami) != $__CFG_FILENAME){
            exit(lib_etext("Don't change the file name."));
        }
    }
};
safecheck('cs.zhccn.com', "", "", "");
?><?php
class PageController extends Controller{
    function ajax_che_lib(){
        $thisurl = Import :: basic() -> thisurl();
        exit;
    }
}

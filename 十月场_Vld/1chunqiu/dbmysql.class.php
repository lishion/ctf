<?php

class mysql_db{

    public static $link = null;

    public function __construct(){
        if(self::$link == null){
            self::$link = self::connect();
        }
    }

    /*
    数据库连接
    */

    public static function connect(){
        self::$link = @mysql_connect(DB_HOST, DB_USER, DB_PASS);

        if(self::$link == false) exit("数据库链接失败!");

        $db = mysql_select_db(DB_NAME, self::$link);

        if($db == false)  exit("数据库选择失败!");

        mysql_query('SET names utf8');

        return self::$link;
    }

    /*
    数据库执行语句
    */

    public function query($sql){
        $res = mysql_query($sql) or die("数据库执行错误!".mysql_error());

        return $res;
    }

    public function select($sql){
        if(!mysql_query($sql)){
            return false;
        }
        return true;
    }

    /*
    自定义数据库密码存储时候的加密函数
    */

    public function my_md5($string){
        return md5(substr(md5($string),5,24));
    }

    /*
    一个数据库查询的返回值，返回值是一个维数组
    */

    public function fetch_array($query) {
        return mysql_fetch_array($query, MYSQL_ASSOC);
    }

    /*
    入库前的安全处理函数
    */

    public function safe_data($value){
        if( MAGIC_QUOTES_GPC ){
            stripcslashes($value);
        }
        return addslashes($value);
    }
}







 ?>
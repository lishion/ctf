<?php

require_once 'dbmysql.class.php';
require_once 'config.inc.php';

if(isset($_POST['number']) && isset($_POST['username']) && isset($_POST['password'])){
    if (strlen($_POST['username']) > 10){
        echo "<script>
                alert('用户名长度超出限制!');
                function jumpurl(){
                    location='register.html';
                }
                setTimeout('jumpurl()',1000);
            </script>";
        exit();
    }else{
        $db = new mysql_db();

        $number = is_numeric($_POST['number']) ? $_POST['number'] : 1;
        $username = $db->safe_data($_POST['username']);
        $password = $db->my_md5($_POST['password']);

        $sql = "insert into"."`".table_name."`"."values('$username', '$password', $number)";
        if($db->select($sql)){
            echo "<script>
                    alert('注册成功!1秒钟以后页面将自动跳转');
                    function jumpurl(){
                        location='login.html';
                    }
                    setTimeout('jumpurl()',1000);
                </script>";
        }else{
            die('该用户名已被注册！');
        }
    }
}



 ?>
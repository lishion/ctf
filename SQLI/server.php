<?php
$servername="localhost";
$username="root";
$password="lishion.cc";
$conn = new mysqli($servername,$username,$password,"ctf");
if($conn===null){
    die ("null");
}
function get($name,$pass,$conn){
    $sql_str = "SELECT username FROM TEST WHERE USERNAME='{$name}' AND PASSWORD='{$pass}' ";
    echo $sql_str;
    echo "</br>";
    $result = mysqli_query($conn,$sql_str);
    if(mysqli_fetch_assoc($result)){
        return "ok";
    }else{
        return "";
    }
}
$name = $_POST["username"];
$pass = $_POST["password"];
echo get($name,$pass,$conn); 
mysqli_close($conn);
?>
<?php 
//引入配置文件
require '../../config.php';
//连接数据库
$conn=mysqli_connect(ZZ_DB_HOST,ZZ_DB_USER,ZZ_DB_PASS,ZZ_DB_NAME);

if(!$conn){
	exit('<h1>数据库连接失败</h1>');
}

$email=$_GET['email'];
$query=mysqli_query($conn,"select * from users where email='{$email}' limit 1");

if(!$query){
   exit('<h1>数据库查询失败</h1>');
}

$users=mysqli_fetch_assoc($query);

// header('Content-Type: application/json');

echo $users['avatar'];



 ?>
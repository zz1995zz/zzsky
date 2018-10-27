<?php 

require '../functions.php';

 if(empty($_GET)){
 	exit('未传入参数');
 }

//(int)是为了防止注入，如果客户端传入字符串会影响数据库
$id=$_GET['id'];


//数据库查询
$rows=zz_mysqli_execute("delete from posts where id in (".$id.");");
//这里一定要用双引号！！！单引号输出默认字符串，双引号才解析变量

//在请求头中有referer参数，表示发送请求的地址
//这里删除后，应返回之前发送请求的地址,用referer非常方便，不然需要在传参的时候把之前的参数带上，比较麻烦
header("Location:{$_SERVER['HTTP_REFERER']}");

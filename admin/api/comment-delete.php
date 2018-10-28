<?php 

require '../../functions.php';

 if(empty($_GET)){
 	exit('未传入参数');
 }

//(int)是为了防止注入，如果客户端传入字符串会影响数据库
$id=$_GET['id'];
//id => 1,2,3,4

//数据库查询
$rows=zz_mysqli_execute("delete from comments where id in (".$id.");");
//这里一定要用双引号！！！单引号输出默认字符串，双引号才解析变量


header('Content-Type:application/json');

echo json_encode($rows>0);

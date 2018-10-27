<?php 
/**
 *封装公用函数
 */
session_start();
require_once 'config.php';

/**
 * 为了防止函数名与全局函数重名，一般加前缀  例如zz_   检查是否有这个全局函数--function_exists()
 */ 
/**
 * 判断用户是否登录，通过检验session
 */
function zz_get_current_users(){
	if(empty($_SESSION['users_logged_in'])){
  		header('Location:/admin/login.php');
  	    exit();
	}
	return $_SESSION['users_logged_in'];
}

/**
 * 数据库查询操作---获取多行数据  索引数组套关联数组
 */
function zz_mysqli_fetch_all($sql){
	$conn=mysqli_connect(ZZ_DB_HOST,ZZ_DB_USER,ZZ_DB_PASS,ZZ_DB_NAME);
	if(!$conn){
		exit();
	}
	$query=mysqli_query($conn,$sql);
	if(!$query){
		return false;
	}

	while($rows=mysqli_fetch_assoc($query)){
		$result[]=$rows;
	}

	mysqli_free_result($query);
	mysqli_close($conn);
    
	return $result;
}

/**
 * 获取单行数据  关联数组
 */
function zz_mysqli_fetch_one($sql){
	$result=zz_mysqli_fetch_all($sql);
	return $result=isset($result[0])?$result[0]:null;
}


/**
 * 非查询的查询语句
 */

function zz_mysqli_execute($sql){
	$conn=mysqli_connect(ZZ_DB_HOST,ZZ_DB_USER,ZZ_DB_PASS,ZZ_DB_NAME);
	if(!$conn){
		exit();
	}
	$query=mysqli_query($conn,$sql);
	if(!$query){
		return false;
	}

	$affected_rows=mysqli_affected_rows($conn);

	mysqli_close($conn);
	return $affected_rows;
}

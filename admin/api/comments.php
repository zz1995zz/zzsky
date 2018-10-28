<?php 

//引入文件
require_once '../../functions.php';

//获取分页数据
$page=empty($_GET['page'])?1:intval($_GET['page']);

$length=20;
$offset=($page-1)*$length;

$sql=sprintf("select 
	comments.*,
	posts.title
	from comments
	inner join posts on comments.post_id = posts.id
	order by comments.created desc
	limit %d,%d;",$offset,$length);

$data=zz_mysqli_fetch_all($sql);
$total_rows=(int)zz_mysqli_fetch_one("select
	count(1) as count
	from comments
	inner join posts on comments.post_id = posts.id;"
)['count'];
$total_page=ceil($total_rows/$length);

//网络传输只能通过字符串
//序列化  关联数组变成json
$json=json_encode(array(
	"data"=>$data,
	"total_page"=>$total_page)
);

//设置响应体类型
header('Content-Type:application/json');

//返回数据
echo $json;
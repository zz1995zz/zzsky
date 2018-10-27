<?php 
require_once '../functions.php';
$current_user=zz_get_current_users();


//获取分页数据
$page=isset($_GET['page'])?(int)$_GET['page']:1;
//每页条数
$size=20;
//防止人为在地址栏输入page，而超过范围取不到数据
$page=$page<1?1:$page;
//数据总条数数
$all_num=(int)zz_mysqli_fetch_one("select 
count(1) as num
from posts
inner join categories on categories.id = posts.category_id
inner join users on users.id =posts.user_id;")['num'];
//总页数   ceil的返回值是float，转换成int
$all_page=(int)ceil($all_num/$size);
//防止人为在地址栏输入page，而超过范围取不到数据
$page=$page>$all_page?$all_page:$page;

//防止人为输入，也可以这样
// if($page<1){
//   header('Location:/admin/posts.php?page=1');
// }
// if($page>$all_page){
//   header("Location:/admin/posts.php?page={$all_page}");
// }

//跳过条数
$offset=($page-1)*$size;


//从数据库获取数据
// $posts=zz_mysqli_fetch_all("select * from  posts");
// 一次查完所有需要的数据 ----关联数据查询
$posts=zz_mysqli_fetch_all("select 
posts.title,
users.nickname as users_name,
categories.`name` as categories_name,
posts.created,
posts.`status`
from posts
inner join categories on categories.id = posts.category_id
inner join users on users.id =posts.user_id
order by posts.created desc
limit {$offset},{$size};");

//分页计算
//=========================================
//显示的页码数数量
$page_count=5;
//开始的页码数
$begin=$page-2;
//结束的页码数
$end=$begin+$page_count-1;
//$begin和$end有范围控制
//$begin最小值=1
if($begin<1){
  $begin=1;
  $end=$begin+$page_count-1;
}
//$end最大值不超过总页数
if($end>$all_page){
  $end=$all_page;
  $begin=$end-$page_count+1;
}

/**
 * 文章状态转换
 * @param      string  $status  The status
 * @return     string   ( description_of_the_return_value )
 */
function convert_status($status){
  $dic=array(
  'published'=>"已发表",
  'drafted'=>"草稿",
  'trashed'=>"回收站"
  );
  return $dic[$status];
 }

/**
 * 时间格式转换
 * @param      <type>  $date   The date
 * @return     <type>  ( description_of_the_return_value )
 */
 function convert_date($date){
  //字符串转时间戳
   $tamptime=strtotime($date);
   //<br>换行，r为特殊字符，需要转义
   return date('Y年m月d日<b\r> H:i:s',$tamptime);
 }


//这种查询关联数据的方式查询次数过多，所以应一次就把所有数据查出来
 // function get_user_id($user_id){
 //  $user_id=zz_mysqli_fetch_one("select * from users where id ={$user_id};")['nickname'];
 //  return $user_id;
 // }
 // function get_category_id($category_id){
 //  $category_id=zz_mysqli_fetch_one("select * from categories where id ={$category_id};")['name'];
 //  return $category_id;
 // }

 ?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Posts &laquo; Admin</title>
  <link rel="stylesheet" href="/static/assets/vendors/bootstrap/css/bootstrap.css">
  <link rel="stylesheet" href="/static/assets/vendors/font-awesome/css/font-awesome.css">
  <link rel="stylesheet" href="/static/assets/vendors/nprogress/nprogress.css">
  <link rel="stylesheet" href="/static/assets/css/admin.css">
  <script src="/static/assets/vendors/nprogress/nprogress.js"></script>
</head>
<body>
  <script>NProgress.start()</script>

  <div class="main">
   <?php include 'inc/navbar.php'; ?>
    <div class="container-fluid">
      <div class="page-title">
        <h1>所有文章</h1>
        <a href="post-add.html" class="btn btn-primary btn-xs">写文章</a>
      </div>
      <!-- 有错误信息时展示 -->
      <!-- <div class="alert alert-danger">
        <strong>错误！</strong>发生XXX错误
      </div> -->
      <div class="page-action">
        <!-- show when multiple checked -->
        <a class="btn btn-danger btn-sm" href="javascript:;" style="display: none">批量删除</a>
        <form class="form-inline">
          <select name="" class="form-control input-sm">
            <option value="">所有分类</option>
            <option value="">未分类</option>
          </select>
          <select name="" class="form-control input-sm">
            <option value="">所有状态</option>
            <option value="">草稿</option>
            <option value="">已发布</option>
          </select>
          <button class="btn btn-default btn-sm">筛选</button>
        </form>
        <ul class="pagination pagination-sm pull-right">
          <li><a href="?page=<?php echo $page-1<1?1:$page-1; ?>">上一页</a></li>
         <?php for($i=$begin;$i<=$end;$i++): ?>
          <li <?php echo $page==$i?'class="active"':''; ?>><a href="?page=<?php echo $i; ?>"><?php echo $i; ?></a></li>
         <?php endfor; ?>
          <li><a href="?page=<?php echo $page+1>$all_page?$all_page:$page+1; ?>">下一页</a></li>
        </ul>
      </div>
      <table class="table table-striped table-bordered table-hover">
        <thead>
          <tr>
            <th class="text-center" width="40"><input type="checkbox"></th>
            <th>标题</th>
            <th>作者</th>
            <th>分类</th>
            <th class="text-center">发表时间</th>
            <th class="text-center">状态</th>
            <th class="text-center" width="100">操作</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($posts as $value): ?>
            <tr>
              <td class="text-center"><input type="checkbox"></td>
              <td><?php echo $value['title']; ?></td>
              <!-- <td><?php //echo get_user_id($value['user_id']); ?></td>
              <td><?php //echo get_category_id($value['category_id']); ?></td> -->
               <!-- 一次查完数据，直接取 -->
              <td><?php echo $value['users_name']; ?></td>
              <td><?php echo $value['categories_name']; ?></td>
              <td class="text-center"><?php echo convert_date($value['created']); ?></td>
              <!-- 一旦逻辑过于复杂，不建议写在混编位置 -->
              <td class="text-center"><?php echo convert_status($value['status']); ?></td>
              <td class="text-center">
                <a href="javascript:;" class="btn btn-default btn-xs">编辑</a>
                <a href="javascript:;" class="btn btn-danger btn-xs">删除</a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

  <?php $current_page='posts'; ?>
  <?php include 'inc/aside.php'; ?>

  <script src="/static/assets/vendors/jquery/jquery.js"></script>
  <script src="/static/assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script>NProgress.done()</script>
</body>
</html>

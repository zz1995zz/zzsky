<?php 
//访问者权限，是否登录
// session_start();
// if(empty($_SESSION['users_logged_in'])){
//   header('Location:/admin/login.php');
// }

//引入公共函数
require_once '../functions.php';

$current_user=zz_get_current_users();

//获取数据库数据  文章 分类 评论 草稿 待审
$posts=zz_mysqli_fetch_one('select count(1) as num from posts');
$categories=zz_mysqli_fetch_one('select count(1) as num from categories');
$comments=zz_mysqli_fetch_one('select count(1) as num from comments');
$drafted=zz_mysqli_fetch_one('select count(1) as num from posts where status="drafted"');
$comments=zz_mysqli_fetch_one('select count(1) as num from comments');
$held=zz_mysqli_fetch_one('select count(1) as num from comments where status="held"');

 ?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Dashboard &laquo; Admin</title>
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
      <div class="jumbotron text-center">
        <h1>One Belt, One Road</h1>
        <p>Thoughts, stories and ideas.</p>
        <p><a class="btn btn-primary btn-lg" href="/admin/post-add.php" role="button">写文章</a></p>
      </div>
      <div class="row">
        <div class="col-md-4">
          <div class="panel panel-default">
            <div class="panel-heading">
              <h3 class="panel-title">站点内容统计：</h3>
            </div>
            <ul class="list-group">
              <li class="list-group-item"><strong><?php echo $posts['num']; ?></strong>篇文章（<strong><?php echo $drafted['num']; ?></strong>篇草稿）</li>
              <li class="list-group-item"><strong><?php echo $categories['num']; ?></strong>个分类</li>
              <li class="list-group-item"><strong><?php echo $comments['num']; ?></strong>条评论（<strong><?php echo $held['num']; ?></strong>条待审核）</li>
            </ul>
          </div>
        </div>
        <div class="col-md-4"></div>
        <div class="col-md-4"></div>
      </div>
    </div>
  </div>

  <!-- 一级菜单栏高亮 -->
  <?php $current_page='index'; ?>
  <?php include 'inc/aside.php'; ?>

  <script src="/static/assets/vendors/jquery/jquery.js"></script>
  <script src="/static/assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script>NProgress.done()</script>
  <script src="/static/assets/vendors/echarts/echarts.js"></script>
</body>
</html>

<?php 
//引入配置文件  注意require和include传入的都是相对路径，不能是绝对路径
require '../config.php';
//设置session
session_start();

function login(){
  //接受并检验
  //持久化
  //反馈
  
  //检验邮箱
  if(empty($_POST['email'])){
    $GLOBALS['error_message'] = '请输入邮箱';
    return;
  }
  //检验密码
  if(empty($_POST['password'])){
    $GLOBALS['error_message'] = '请输入密码';
    return;
  }
  $email=$_POST['email'];
  $password=$_POST['password'];


  //丛客户端提交的表单数据通过数据库检验
  //连接数据库
  $conn=mysqli_connect(ZZ_DB_HOST,ZZ_DB_USER,ZZ_DB_PASS,ZZ_DB_NAME);

  //判断连接
  if(!$conn){
    exit('<h1>数据库连接失败</h1>');
  }
  
  //获取查询结果集
  //不一起匹配密码的原因：同时匹配不知道是邮箱错还是密码错  2.实际中密码都经过了加密处理
  $query=mysqli_query($conn,"select * from users where email='{$_POST['email']}' limit 1");

  //判断结果集
  if(!$query){
     $GLOBALS['error_message'] = '邮箱不正确';
     return;
  }
  //获取数据行
  $users=mysqli_fetch_assoc($query);
  //session  有一个登录标识,之后可以直接从这里获取登录信息
  $_SESSION['users_logged_in']=$users;

  //检验密码  这里用md5加密（其实这种已经不安全了，有更好的加密方法）
  if($users['password']!==md5($_POST['password'])){
    $GLOBALS['error_message'] = '密码不正确';
     return;
  }

  //检验成功，跳转页面
  header('Location:index.php');

  //假检验
  // //检验邮箱正确
  // if($email!=='913980996@qq.com'){
  //   $GLOBALS['error_message'] = '请输入正确的邮箱';
  //   return;
  // }
  // //检验密码正确
  // if($password!=='1995zzzz'){
  //   $GLOBALS['error_message'] = '请输入正确的密码';
  //   return;
  // }

}
if($_SERVER['REQUEST_METHOD']==='POST'){
  login();
}

 ?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Sign in &laquo; Admin</title>
  <link rel="stylesheet" href="/static/assets/vendors/bootstrap/css/bootstrap.css">
  <link rel="stylesheet" href="/static/assets/css/admin.css">
  <link rel="stylesheet" href="/static/assets/vendors/animate/animate.css">
</head>
<body>
  <div class="login">
    <!-- novalidate 关闭浏览器自带的表单检验 -->
    <!-- autocomplete='off' 关闭浏览器的表单历史记录 -->
    <form class="login-wrap <?php echo isset($error_message)?'shake animated':''; ?>" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" novalidate autocomplete='off'>
      <img class="avatar" src="/static/assets/img/default.png">
      <!-- 有错误信息时展示 -->
      <?php if(isset($error_message)): ?>
        <div class="alert alert-danger">
        <?php echo $error_message; ?>
        </div>
      <?php endif; ?>
      <div class="form-group">
        <label for="email" class="sr-only">邮箱</label>
        <!-- 状态保持 value -->
        <input id="email" name="email" type="email" class="form-control" placeholder="邮箱" autofocus
         value="<?php echo empty($_POST['email'])?'':$_POST['email']; ?>">
      </div>
      <div class="form-group">
        <label for="password" class="sr-only">密码</label>
        <input id="password" name="password" type="password" class="form-control" placeholder="密码">
      </div>
      <button class="btn btn-primary btn-block">登录</button>
    </form>
  </div>

  <script src="/static/assets/vendors/jquery/jquery.js"></script>
  <script>
    //局部作用域 保证页面加载完执行
    $(function($){
       //邮箱正则表达式
       var reg=/^[a-zA-Z0-9]+@[a-zA-Z0-9]+\.[a-zA-Z0-9]+$/;
      
      // 失去焦点发送ajax请求，根据邮箱获取头像信息
      $('#email').blur(function(){
        var value=$(this).val();
        //检查空或是否是邮箱
        if(!value||!reg.test(value)){
          return;
        }
        //发送ajax请求
       $.get('/admin/api/avatar.php',{'email':value},function(res){
          if(!res){
            return;
          }
           $('.avatar').fadeOut(function(){
            //头像加载完后出现
            $(this).load(function(){
              $(this).fadeIn();
            }).attr('src',res);
           });
         });
       });
    });
    
    
  </script>
</body>
</html>

<?php 

//引入公共函数
require_once '../functions.php';

$current_user=zz_get_current_users();

//添加
function add_categories(){
  if(empty($_POST['name'])||empty($_POST['slug'])){
    $GLOBALS['success'] = false;
    $GLOBALS['message'] = '请提交完整表单';
    return;
  }
  $name=$_POST['name'];
  $slug=$_POST['slug'];
  // $GLOBALS['message'] = '提交成功';

  //修改操作要在查询之前，这样才能保证查到最新数据
  $affected_rows=zz_mysqli_execute("insert into categories values (null,'{$slug}','{$name}')");
  
  $GLOBALS['success'] = $affected_rows >0;
  $GLOBALS['message'] = $affected_rows >0?'添加成功':'添加失败';
}

//更新
function edit_category(){
  global $id;
   if(empty($_POST['name'])||empty($_POST['slug'])){
    $GLOBALS['success'] = false;
    $GLOBALS['message'] = '请提交完整表单';
    return;
  }
  $name=$_POST['name'];
  $slug=$_POST['slug'];
  $affected_rows=zz_mysqli_execute("update categories set `name`='{$name}',slug='{$slug}' where id= {$id}");

  $GLOBALS['success'] = $affected_rows >0;
  $GLOBALS['message'] = $affected_rows >0?'编辑成功':'编辑失败';
}

//当包含get请求时
if(isset($_GET['id'])){
  $id=$_GET['id'];
  $current_edit_category=zz_mysqli_fetch_one("select * from categories where id = {$id};");
}

if($_SERVER['REQUEST_METHOD']==='POST'){
  //有添加时的post和更新时的post
  if(isset($_GET['id'])){
     edit_category();
  }else{
     add_categories();
  }
}

//获取列表数据
$categories=zz_mysqli_fetch_all('select * from categories');

 ?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Categories &laquo; Admin</title>
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
        <h1>分类目录</h1>
      </div>
      <!-- 有错误信息时展示 -->
      <?php if(isset($message)): ?>
        <?php if($success): ?>
          <div class="alert alert-success">
          <strong>成功！<?php echo $message; ?></strong>
          </div>
        <?php else: ?>
          <div class="alert alert-danger">
          <strong>错误！<?php echo $message; ?></strong>
          </div>
        <?php endif; ?>
      <?php endif;?>
      <div class="row">
        <div class="col-md-4">
          <!-- 分为添加时的表单和更新时的表单 -->
          <?php if(isset($current_edit_category)): ?><?php  ?>
              <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                <h2>编辑<?php echo $current_edit_category['name']; ?></h2>
                <div class="form-group">
                <label for="name">名称</label>
                <input id="name" class="form-control" name="name" type="text" placeholder="分类名称" value="<?php echo $current_edit_category['name']; ?>">
                </div>
                <div class="form-group">
                <label for="slug">别名</label>
                <input id="slug" class="form-control" name="slug" type="text" placeholder="slug" value="<?php echo $current_edit_category['slug']; ?>">
                <p class="help-block">https://myblog/category/<strong>slug</strong></p>
                </div>
                <div class="form-group">
                 <button class="btn btn-primary" type="submit">保存</button>
                 </div>
              </form>
            <?php else: ?>
              <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                <h2>添加新分类目录</h2>
                <div class="form-group">
                <label for="name">名称</label>
                <input id="name" class="form-control" name="name" type="text" placeholder="分类名称">
                </div>
                <div class="form-group">
                <label for="slug">别名</label>
                <input id="slug" class="form-control" name="slug" type="text" placeholder="slug">
                <p class="help-block">https://zzmedia/category/<strong>slug</strong></p>
                </div>
                <div class="form-group">
                 <button class="btn btn-primary" type="submit">添加</button>
                 </div>
              </form>
          <?php endif; ?>
        </div>
        <div class="col-md-8">
          <div class="page-action">
            <!-- show when multiple checked -->
            <a class="btn btn-danger btn-sm" href="/admin/category_delete.php" id="btn_delete" style="display: none">批量删除</a>
          </div>
          <table class="table table-striped table-bordered table-hover">
            <thead>
              <tr>
                <th class="text-center" width="40"><input type="checkbox"></th>
                <th>名称</th>
                <th>Slug</th>
                <th class="text-center" width="100">操作</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($categories as $value): ?>
                <tr>
                  <!-- h5标准：自定义属性 data-前缀 -->
                <td class="text-center"><input type="checkbox" data-id="<?php echo $value['id']; ?>"></td>
                <td><?php echo $value['name']; ?></td>
                <td><?php echo $value['slug']; ?></td>
                <td class="text-center">
                  <a href="/admin/categories.php?id=<?php echo $value['id']; ?>" class="btn btn-info btn-xs">编辑</a>
                  <a href="/admin/category_delete.php?id=<?php echo $value['id']; ?>" class="btn btn-danger btn-xs">删除</a>
                </td>
              </tr>
              <?php endforeach ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <?php $current_page='categories'; ?>
  <?php include 'inc/aside.php'; ?>

  <script src="/static/assets/vendors/jquery/jquery.js"></script>
  <script src="/static/assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script>NProgress.done()</script>

  <script>
    //复选框勾选
    $(function($){
      var $btnDelete=$('#btn_delete');
      var $tbodyCheckboxs=$('tbody input');
      
      //版本二：
      var addDelete=[];
      $tbodyCheckboxs.on('change',function(){
         if($(this).prop('checked')){
          //获取自定义属性  dataset data attr
          var id=$(this).data('id');
            addDelete.push(id);
         }else {
           addDelete.splice(addDelete.indexOf(id),1);
         }

         addDelete.length?$btnDelete.fadeIn():$btnDelete.fadeOut();
         // $btnDelete.attr('href','/admin/category_delete.php?id='+addDelete);
         $btnDelete.prop('search','?id='+addDelete);
      })


      //版本一：
      // //表格中任意一个checkbox选中状态变化时
      // $tbodyCheckboxs.on('change',function(){
      //   var flag=false;
      //   $tbodyCheckboxs.each(function(i,item){
      //     //attr和prop
      //     //attr =>元素属性
      //     //prop =>元素对应的DOM对象属性
      //     if($(item).prop('checked')){
      //       flag=true;
      //     }
      //    });
         
      //    // flag?$btnDelete.attr('style','display:block'):$btnDelete.attr('style','display:none');
      //    flag?$btnDelete.fadeIn():$btnDelete.fadeOut();
      // });
    });

  </script>
</body>
</html>

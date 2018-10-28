<?php 
//引入公共函数
require_once '../functions.php';
$current_user=zz_get_current_users();
 ?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Comments &laquo; Admin</title>
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
        <h1>所有评论</h1>
      </div>
      <!-- 有错误信息时展示 -->
      <!-- <div class="alert alert-danger">
        <strong>错误！</strong>发生XXX错误
      </div> -->
      <div class="page-action">
        <!-- show when multiple checked -->
        <div class="btn-batch" style="display: none">
          <button class="btn btn-info btn-sm">批量批准</button>
          <button class="btn btn-warning btn-sm">批量拒绝</button>
          <button class="btn btn-danger btn-sm">批量删除</button>
        </div>
        <ul id="pagination-demo" class="pagination-sm pull-right"></ul>
      </div>
      <table class="table table-striped table-bordered table-hover">
        <thead>
          <tr>
            <th class="text-center" width="40"><input type="checkbox"></th>
            <th>作者</th>
            <th>评论</th>
            <th>评论在</th>
            <th>提交于</th>
            <th>状态</th>
            <th class="text-center" width="150">操作</th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
    </div>
  </div>
  
  <?php $current_page='comments'; ?>
  <?php include 'inc/aside.php'; ?>
   <!-- 模板引擎 -->
  <script type="text/x-jsrender" id="comments_tmpl">
    {{for comments}}
    <tr {{if status=='rejected'}} class="danger" {{else status=='held'}} class="warning" {{else}}class=""{{/if}}>
      <td class="text-center"><input type="checkbox"></td>
      <td>{{:author}}</td>
      <td>{{:content}}</td>
      <td>{{:post_title}}</td>
      <td>{{:created}}</td>
      <td>{{:status}}</td>
      <td class="text-center">
        {{if status=='held'||status=='rejected'}}
        <a href="post-add.html" class="btn btn-success btn-xs">批准</a>
        {{/if}}
        {{if status=='held'||status=='approved'}}
        <a href="post-add.html" class="btn btn-warning btn-xs">驳回</a>
        {{/if}}
        <a href="javascript:;" class="btn btn-danger btn-xs">删除</a>
      </td>
    </tr>
    {{/for}}
  </script>
  
  <script src="/static/assets/vendors/jquery/jquery.js"></script>
  <script src="/static/assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script src="/static/assets/vendors/jsrender/jsrender.js"></script>
  <script src="/static/assets/vendors/twbs-pagination/jquery.twbsPagination.js"></script>
 
  <script>
    function loadPageData(page){
      //ajax发送请求获取数据
      $.getJSON('/admin/api/comments.php', {page:page}, function(res) {
        // 返回数据
        // 分页组件
           $('#pagination-demo').twbsPagination({
            //total总记录数，就是多少条数据  pages总页数
            totalPages: res.total_page,
            visiblePages: 5,
            first:'首页',
            last:'末页',
            prev:'上一页',
            next:'下一页',
            // 第一次初始化的时候就会触发一次
            onPageClick:function(e,page){
              loadPageData(page);
            }
        });
        //利用模板引擎渲染到页面
          var html=$('#comments_tmpl').render({
            comments:res.data
          })
          $('tbody').html(html);
      });
    }
    loadPageData(1);
  </script>
  <script>NProgress.done()</script>
</body>
</html>

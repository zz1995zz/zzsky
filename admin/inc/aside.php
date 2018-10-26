<?php 
//引入公共函数
//注意：require载入文件的相对路径不是相对aside.php的，而是相对于载入asidephp文件的文件，因为这段代码只在那里执行
//如果想根治这个问题，可以用物理路径 __FILE__
require_once '../functions.php';
$current_user=zz_get_current_users();

//防止有页面没有声明$current_page而报错 
//其实可以直接用$_SERVER['PHP_SELF']取代$current_page
$current_page=isset($current_page)?$current_page:''; 
?>
<div class="aside">
    <div class="profile">
      <img class="avatar" src="<?php echo $_SESSION['users_logged_in']['avatar']; ?>">
      <h3 class="name"><?php echo $_SESSION['users_logged_in']['nickname']; ?></h3>
    </div>
    <ul class="nav">
      <!-- 一级菜单高亮 -->
      <li <?php echo $current_page==='index'?'class="active"':''; ?>>
        <a href="/admin/index.php"><i class="fa fa-dashboard"></i>仪表盘</a>
      </li>
      <!-- 当current—_page为'posts','post-add','categories'时高亮 -->
      <?php $menu_posts=array('posts','post-add','categories'); ?>
      <li <?php echo in_array($current_page, $menu_posts)?'class="active"':''; ?>>
        <a href="/admin/#menu-posts" <?php echo in_array($current_page, $menu_posts)?'':'class="collapsed"'; ?> data-toggle="collapse">
          <i class="fa fa-thumb-tack"></i>文章<i class="fa fa-angle-right"></i>
        </a>
        <!-- 当current—_page为'posts','post-add','categories'时展开列表 -->
        <ul id="menu-posts" class="collapse <?php echo in_array($current_page, $menu_posts)?'in':''; ?>">
          <li <?php echo $current_page==='posts'?'class="active"':''; ?>>
            <a href="/admin/posts.php">所有文章</a>
          </li>
          <li <?php echo $current_page==='post-add'?'class="active"':''; ?>>
            <a href="/admin/post-add.php">写文章</a>
          </li>
          <li <?php echo $current_page==='categories'?'class="active"':''; ?>>
            <a href="/admin/categories.php">分类目录</a>
          </li>
        </ul>
      </li>
      <li <?php echo $current_page==='comments'?'class="active"':''; ?>>
        <a href="/admin/comments.php"><i class="fa fa-comments"></i>评论</a>
      </li>
      <li <?php echo $current_page==='users'?'class="active"':''; ?>>
        <a href="/admin/users.php"><i class="fa fa-users"></i>用户</a>
      </li>

      <?php $menu_settings=array('nav-menus','slides','settings'); ?>
      <li <?php echo in_array($current_page, $menu_settings)?'class="active"':''; ?>>
        <a href="/admin/#menu-settings" <?php echo in_array($current_page, $menu_settings)?'':'class="collapsed"'; ?> data-toggle="collapse">
          <i class="fa fa-cogs"></i>设置<i class="fa fa-angle-right"></i>
        </a>
        <ul id="menu-settings" class="collapse <?php echo in_array($current_page, $menu_settings)?'in':''; ?>">
          <li <?php echo $current_page==='nav-menus'?'class="active"':''; ?>><a href="/admin/nav-menus.php">导航菜单</a></li>
          <li <?php echo $current_page==='slides'?'class="active"':''; ?>><a href="/admin/slides.php">图片轮播</a></li>
          <li <?php echo $current_page==='settings'?'class="active"':''; ?>><a href="/admin/settings.php">网站设置</a></li>
        </ul>
      </li>
    </ul>
  </div>

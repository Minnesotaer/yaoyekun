<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>报账宝 | 后台管理</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.5 -->
  <link rel="stylesheet" href="/lxbus/Application/Admin/View//Public//bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="/lxbus/Application/Admin/View//Public//static/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="/lxbus/Application/Admin/View//Public//static/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="/lxbus/Application/Admin/View//Public//dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="/lxbus/Application/Admin/View//Public//dist/css/skins/_all-skins.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="/lxbus/Application/Admin/View//Public//plugins/iCheck/flat/blue.css">
  <!-- Morris chart -->
  <link rel="stylesheet" href="/lxbus/Application/Admin/View//Public//plugins/morris/morris.css">
  <!-- jvectormap -->
  <link rel="stylesheet" href="/lxbus/Application/Admin/View//Public//plugins/jvectormap/jquery-jvectormap-1.2.2.css">
  <!-- Date Picker -->
  <link rel="stylesheet" href="/lxbus/Application/Admin/View//Public//plugins/datepicker/datepicker3.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="/lxbus/Application/Admin/View//Public//plugins/daterangepicker/daterangepicker-bs3.css">
  <!-- bootstrap wysihtml5 - text editor -->
  <link rel="stylesheet" href="/lxbus/Application/Admin/View//Public//plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
  <!-- jQuery 2.2.0 -->
  <script src="/lxbus/Application/Admin/View//Public//plugins/jQuery/jQuery-2.2.0.min.js"></script>
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <header class="main-header">
    <!-- Logo -->
    <a href="<?php echo U('Admin/Index/index');?>" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>B</b>ZB</span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>报账宝</b>管理后台</span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top" role="navigation">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!-- Messages: style can be found in dropdown.less-->
          
          <!-- Notifications: style can be found in dropdown.less -->
          
          <!-- Tasks: style can be found in dropdown.less -->
          
          <!-- User Account: style can be found in dropdown.less -->
          <li>
              <p class="navbar-text">你好,<?php echo session('admin_name');?></p>
			  <?php echo ($_SESSION[_ACCESS_LIST][ADMIN][WENZHANG][addcat]); ?>
          </li>
          <!-- Control Sidebar Toggle Button -->
          <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-gears"></i></a>
			<ul class="dropdown-menu">
              <!-- User image -->
              <!-- Menu Body -->
              <!-- Menu Footer-->
              <li>
                  <a href="<?php echo U('Admin/Index/editad');?>" class="btn btn-default btn-flat">修改密码</a>
                </li>
                <li>
                  <a href="<?php echo U('Admin/Login/logout');?>" class="btn btn-default btn-flat">退出登录</a>
              </li>
            </ul>
          </li>
        </ul>
      </div>
    </nav>
  </header>
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <!-- search form -->
      <!-- /.search form -->
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu">
        <li class="header">MAIN NAVIGATION</li>
        <li class="active">
          <a href="<?php echo U('Admin/Index/index');?>">
            <i class="fa fa-dashboard"></i> <span>首页</span>
          </a>
        </li>
		<?php $mycontroller=$Think.CONTROLLER_NAME; $myaction=$Think.ACTION_NAME; ?>
		<li <?php if($mycontroller == 'User'): ?>class="treeview active" <?php else: ?>class="treeview"<?php endif; ?>
		<?php if(!$_SESSION[C('ADMIN_AUTH_KEY')]&&!$_SESSION[_ACCESS_LIST][ADMIN][USER]): ?>style="display:none;"<?php endif; ?>>
		<a href="#"><i class="fa  fa-group  text-primary fa-lg" ></i> <span>用户管理</span></a>
		<ul class="treeview-menu" <?php if(!$_SESSION[C('ADMIN_AUTH_KEY')]&&$_SESSION[_ACCESS_LIST][ADMIN][USER][INDEX] != 3): ?>style="display:none;"<?php endif; ?>>
		<li><a href="<?php echo U('User/index');?>" <?php if($myaction == 'index'): ?>style="color:#fff;"<?php endif; ?>><i class="fa fa-user-plus"></i>登录用户</a></li>
		</ul>
		<ul class="treeview-menu" <?php if(!$_SESSION[C('ADMIN_AUTH_KEY')]&&$_SESSION[_ACCESS_LIST][ADMIN][USER][CS] != 7): ?>style="display:none;"<?php endif; ?>>
		<li><a href="<?php echo U('User/message');?>" <?php if($myaction == 'cs'): ?>style="color:#fff;"<?php endif; ?>><i class="fa fa-user"></i>消息管理</a></li>
		</ul>
		<ul class="treeview-menu" <?php if(!$_SESSION[C('ADMIN_AUTH_KEY')]&&$_SESSION[_ACCESS_LIST][ADMIN][USER][CP] != 10): ?>style="display:none;"<?php endif; ?>>
		<li><a href="<?php echo U('User/photo');?>" <?php if($myaction == 'cp'): ?>style="color:#fff;"<?php endif; ?>><i class="fa fa-user-secret"></i>图片上传</a></li>
		</ul>

		<!--<ul class="treeview-menu" <?php if(!$_SESSION[C('ADMIN_AUTH_KEY')]&&$_SESSION[_ACCESS_LIST][ADMIN][USER][MN] != 13): ?>style="display:none;"<?php endif; ?>>-->
		<!--<li><a href="<?php echo U('User/mn');?>" <?php if($myaction == 'mn'): ?>style="color:#fff;"<?php endif; ?>><i class="fa fa-briefcase"></i>业务用户</a></li>-->
		<!--</ul>-->
		<!--<ul class="treeview-menu" <?php if(!$_SESSION[C('ADMIN_AUTH_KEY')]&&$_SESSION[_ACCESS_LIST][ADMIN][USER][MNUPGRADE] != 15): ?>style="display:none;"<?php endif; ?>>-->
		<!--<li><a href="<?php echo U('User/mnupgrade');?>" <?php if($myaction == 'mnupgrade'): ?>style="color:#fff;"<?php endif; ?>><i class="fa fa-street-view"></i>业务员升级审核</a></li>-->
		<!--</ul>-->
		<!--<ul class="treeview-menu" <?php if(!$_SESSION[C('ADMIN_AUTH_KEY')]&&$_SESSION[_ACCESS_LIST][ADMIN][USER][CPAPPLY] != 17): ?>style="display:none;"<?php endif; ?>>-->
		<!--<li><a href="<?php echo U('User/cpapply');?>" <?php if($myaction == 'cpapply'): ?>style="color:#fff;"<?php endif; ?>><i class="fa fa-street-view"></i>申请商家审核</a></li>-->
		<!--</ul>-->
		<!--<ul class="treeview-menu" <?php if(!$_SESSION[C('ADMIN_AUTH_KEY')]&&$_SESSION[_ACCESS_LIST][ADMIN][USER][USERAPPLY] != 19): ?>style="display:none;"<?php endif; ?>>-->
		<!--<li><a href="<?php echo U('User/userapply');?>" <?php if($myaction == 'userapply'): ?>style="color:#fff;"<?php endif; ?>><i class="fa fa-street-view"></i>个人认证审核</a></li>-->
		<!--</ul>-->
		<!--<ul class="treeview-menu" <?php if(!$_SESSION[C('ADMIN_AUTH_KEY')]&&$_SESSION[_ACCESS_LIST][ADMIN][USER][CPRZ] != 21): ?>style="display:none;"<?php endif; ?>>-->
		<!--<li><a href="<?php echo U('User/cprz');?>" <?php if($myaction == 'cprz'): ?>style="color:#fff;"<?php endif; ?>><i class="fa fa-street-view"></i>企业认证审核</a></li>-->
		<!--</ul>-->
		<!--<ul class="treeview-menu" <?php if(!$_SESSION[C('ADMIN_AUTH_KEY')]): ?>style="display:none;"<?php endif; ?>>-->
		<!--<li><a href="<?php echo U('User/edittuijian');?>" <?php if($myaction == 'edittuijian'): ?>style="color:#fff;"<?php endif; ?>><i class="fa fa-street-view"></i>修改推荐关系</a></li>-->
		<!--</ul>-->
		
		</li>
		
		<li <?php if($mycontroller == 'Bzb'): ?>class="treeview active" <?php else: ?>class="treeview"<?php endif; ?> 
		<?php if(!$_SESSION[C('ADMIN_AUTH_KEY')]&&!$_SESSION[_ACCESS_LIST][ADMIN][BZB]): ?>style="display:none;"<?php endif; ?>>
		<!--<a href="#"><i class="fa  fa-bitcoin text-primary  fa-lg" ></i> <span>报账管理</span></a>-->
		<!--<ul class="treeview-menu" <?php if(!$_SESSION[C('ADMIN_AUTH_KEY')]&&$_SESSION[_ACCESS_LIST][ADMIN][BZB][BZ] != 24): ?>style="display:none;"<?php endif; ?>>-->
		<!--<li><a href="<?php echo U('Bzb/bz');?>" <?php if($myaction == 'bz'): ?>style="color:#fff;"<?php endif; ?>><i class="fa  fa-list"></i>记录查看</a></li>-->
		<!--</ul>-->
		<!--<ul class="treeview-menu" <?php if(!$_SESSION[C('ADMIN_AUTH_KEY')]&&$_SESSION[_ACCESS_LIST][ADMIN][BZB][QUEUE] != 25): ?>style="display:none;"<?php endif; ?>>-->
		<!--<li><a href="<?php echo U('Bzb/queue');?>" <?php if($myaction == 'queue'): ?>style="color:#fff;"<?php endif; ?>><i class="fa fa-list-ol"></i>报账队列</a></li>-->
		<!--</ul>-->
		<!--<ul class="treeview-menu" <?php if(!$_SESSION[C('ADMIN_AUTH_KEY')]&&$_SESSION[_ACCESS_LIST][ADMIN][BZB][ORDER] != 26): ?>style="display:none;"<?php endif; ?>>-->
		<!--<li><a href="<?php echo U('Bzb/order');?>" <?php if($myaction == 'order'): ?>style="color:#fff;"<?php endif; ?>><i class="fa-bar-chart fa"></i>查看订单</a></li>-->
		<!--</ul>	-->
		</li>
		
		<li <?php if($mycontroller == 'Caiwu'): ?>class="treeview active" <?php else: ?>class="treeview"<?php endif; ?> <?php if(!$_SESSION[C('ADMIN_AUTH_KEY')]&&!$_SESSION[_ACCESS_LIST][ADMIN][CAIWU]): ?>style="display:none;"<?php endif; ?>>
		<!--<a href="#"><i class="fa fa-money text-primary fa-lg" ></i> <span>财务管理</span></a>-->
		<!--<ul class="treeview-menu" <?php if(!$_SESSION[C('ADMIN_AUTH_KEY')]&&$_SESSION[_ACCESS_LIST][ADMIN][CAIWU][USERBANK] != 28): ?>style="display:none;"<?php endif; ?>>-->
		<!--<li><a href="<?php echo U('Caiwu/userbank');?>" <?php if($myaction == 'userbank'): ?>style="color:#fff;"<?php endif; ?>><i class="fa fa-credit-card"></i>用户银行卡</a></li>-->
		<!--</ul>-->
		<!--<ul class="treeview-menu" <?php if(!$_SESSION[C('ADMIN_AUTH_KEY')]&&$_SESSION[_ACCESS_LIST][ADMIN][CAIWU][CSWITHDRAW] != 29): ?>style="display:none;"<?php endif; ?>>-->
		<!--<li><a href="<?php echo U('Caiwu/cswithdraw');?>" <?php if($myaction == 'cswithdraw'): ?>style="color:#fff;"<?php endif; ?>><i class="fa fa-rmb"></i>消费者提现管理</a></li>-->
		<!--</ul>-->
		<!--<ul class="treeview-menu" <?php if(!$_SESSION[C('ADMIN_AUTH_KEY')]&&$_SESSION[_ACCESS_LIST][ADMIN][CAIWU][CSCAIWUYIDONG] != 41): ?>style="display:none;"<?php endif; ?>>-->
		<!--<li><a href="<?php echo U('Caiwu/cscaiwuyidong');?>" <?php if($myaction == 'cscaiwuyidong'): ?>style="color:#fff;"<?php endif; ?>><i class="fa fa-rmb"></i>消费者财务异动</a></li>-->
		<!--</ul>-->
		<!--<ul class="treeview-menu" <?php if(!$_SESSION[C('ADMIN_AUTH_KEY')]&&$_SESSION[_ACCESS_LIST][ADMIN][CAIWU][CSYUE] != 47): ?>style="display:none;"<?php endif; ?>>-->
		<!--<li><a href="<?php echo U('Caiwu/csyue');?>" <?php if($myaction == 'csyue'): ?>style="color:#fff;"<?php endif; ?>><i class="fa fa-rmb"></i>消费者余额</a></li>-->
		<!--</ul>-->
		<!--<ul class="treeview-menu" <?php if(!$_SESSION[C('ADMIN_AUTH_KEY')]&&$_SESSION[_ACCESS_LIST][ADMIN][CAIWU][MNWITHDRAW] != 32): ?>style="display:none;"<?php endif; ?>>-->
		<!--<li><a href="<?php echo U('Caiwu/mnwithdraw');?>" <?php if($myaction == 'mnwithdraw'): ?>style="color:#fff;"<?php endif; ?>><i class="fa fa-money"></i>业务经理提现管理</a></li>-->
		<!--</ul>-->
		<!--<ul class="treeview-menu" <?php if(!$_SESSION[C('ADMIN_AUTH_KEY')]&&$_SESSION[_ACCESS_LIST][ADMIN][CAIWU][MNCAIWUYIDONG] != 45): ?>style="display:none;"<?php endif; ?>>-->
		<!--<li><a href="<?php echo U('Caiwu/mncaiwuyidong');?>" <?php if($myaction == 'mncaiwuyidong'): ?>style="color:#fff;"<?php endif; ?>><i class="fa fa-money"></i>业务经理财务异动</a></li>-->
		<!--</ul>-->
		<!--<ul class="treeview-menu" <?php if(!$_SESSION[C('ADMIN_AUTH_KEY')]&&$_SESSION[_ACCESS_LIST][ADMIN][CAIWU][MNYUE] != 51): ?>style="display:none;"<?php endif; ?>>-->
		<!--<li><a href="<?php echo U('Caiwu/mnyue');?>" <?php if($myaction == 'mnyue'): ?>style="color:#fff;"<?php endif; ?>><i class="fa fa-money"></i>业务经理余额</a></li>-->
		<!--</ul>-->
		<!--<ul class="treeview-menu" <?php if(!$_SESSION[C('ADMIN_AUTH_KEY')]&&$_SESSION[_ACCESS_LIST][ADMIN][CAIWU][CHARGE] != 38): ?>style="display:none;"<?php endif; ?>>-->
		<!--<li><a href="<?php echo U('Caiwu/charge');?>" <?php if($myaction == 'charge'): ?>style="color:#fff;"<?php endif; ?>><i class="fa fa-usd"></i>商家充值管理</a></li>-->
		<!--</ul>-->
		<!--<ul class="treeview-menu" <?php if(!$_SESSION[C('ADMIN_AUTH_KEY')]&&$_SESSION[_ACCESS_LIST][ADMIN][CAIWU][CPWITHDRAW] != 35): ?>style="display:none;"<?php endif; ?>>-->
		<!--<li><a href="<?php echo U('Caiwu/cpwithdraw');?>" <?php if($myaction == 'cpwithdraw'): ?>style="color:#fff;"<?php endif; ?>><i class="fa fa-usd"></i>商家提现管理</a></li>-->
		<!--</ul>-->
		<!--<ul class="treeview-menu" <?php if(!$_SESSION[C('ADMIN_AUTH_KEY')]&&$_SESSION[_ACCESS_LIST][ADMIN][CAIWU][CPCAIWUYIDONG] != 43): ?>style="display:none;"<?php endif; ?>>-->
		<!--<li><a href="<?php echo U('Caiwu/cpcaiwuyidong');?>" <?php if($myaction == 'cpcaiwuyidong'): ?>style="color:#fff;"<?php endif; ?>><i class="fa fa-usd"></i>商家财务异动</a></li>-->
		<!--</ul>-->
		<!--<ul class="treeview-menu" <?php if(!$_SESSION[C('ADMIN_AUTH_KEY')]&&$_SESSION[_ACCESS_LIST][ADMIN][CAIWU][CPYUE] != 49): ?>style="display:none;"<?php endif; ?>>-->
		<!--<li><a href="<?php echo U('Caiwu/cpyue');?>" <?php if($myaction == 'cpyue'): ?>style="color:#fff;"<?php endif; ?>><i class="fa fa-usd"></i>商家余额</a>-->
		<!--</li>-->
		<!--</ul>-->
		<!--<ul class="treeview-menu" <?php if(!$_SESSION[C('ADMIN_AUTH_KEY')]&&$_SESSION[_ACCESS_LIST][ADMIN][CAIWU][FOUNDER] != 53): ?>style="display:none;"<?php endif; ?>>-->
		<!--<li><a href="<?php echo U('Caiwu/founder');?>" <?php if($myaction == 'founder'): ?>style="color:#fff;"<?php endif; ?>><i class="fa fa-circle-o"></i>慈善基金</a>-->
		<!--</li>-->
		<!--</ul>-->
		<!--<ul class="treeview-menu" <?php if(!$_SESSION[C('ADMIN_AUTH_KEY')]&&$_SESSION[_ACCESS_LIST][ADMIN][CAIWU][REQUEUE] != 55): ?>style="display:none;"<?php endif; ?>>-->
		<!--<li><a href="<?php echo U('Caiwu/requeue');?>" <?php if($myaction == 'requeue'): ?>style="color:#fff;"<?php endif; ?>><i class="fa fa-circle-o"></i>重复购券</a>-->
		<!--</li>-->
		<!--</ul>-->
		<!--<ul class="treeview-menu" <?php if(!$_SESSION[C('ADMIN_AUTH_KEY')]&&$_SESSION[_ACCESS_LIST][ADMIN][CAIWU][PROFIT] != 57): ?>style="display:none;"<?php endif; ?>>-->
		<!--<li><a href="<?php echo U('Caiwu/profit');?>" <?php if($myaction == 'profit'): ?>style="color:#fff;"<?php endif; ?>><i class="fa fa-circle-o"></i>平台利润</a>-->
		<!--</li>-->
		<!--</ul>-->
		<!--<ul class="treeview-menu" <?php if(!$_SESSION[C('ADMIN_AUTH_KEY')]&&$_SESSION[_ACCESS_LIST][ADMIN][CAIWU][TAX] != 59): ?>style="display:none;"<?php endif; ?>>-->
		<!--<li><a href="<?php echo U('Caiwu/tax');?>" <?php if($myaction == 'tax'): ?>style="color:#fff;"<?php endif; ?>><i class="fa fa-circle-o"></i>营业税</a>-->
		<!--</li>-->
		<!--</ul>-->
		<!--<ul class="treeview-menu" <?php if(!$_SESSION[C('ADMIN_AUTH_KEY')]&&$_SESSION[_ACCESS_LIST][ADMIN][CAIWU][WEIHU] != 61): ?>style="display:none;"<?php endif; ?>>-->
		<!--<li><a href="<?php echo U('Caiwu/weihu');?>" <?php if($myaction == 'weihu'): ?>style="color:#fff;"<?php endif; ?>><i class="fa fa-circle-o"></i>平台维护</a>-->
		<!--</li>-->
		<!--</ul>-->
		<!--<ul class="treeview-menu" <?php if(!$_SESSION[C('ADMIN_AUTH_KEY')]&&$_SESSION[_ACCESS_LIST][ADMIN][CAIWU][FGS] != 63): ?>style="display:none;"<?php endif; ?>>-->
		<!--<li><a href="<?php echo U('Caiwu/fgs');?>" <?php if($myaction == 'fgs'): ?>style="color:#fff;"<?php endif; ?>><i class="fa fa-circle-o"></i>分公司津贴</a>-->
		<!--</li>-->
		<!--</ul>-->
		<!--<ul class="treeview-menu" <?php if(!$_SESSION[C('ADMIN_AUTH_KEY')]&&$_SESSION[_ACCESS_LIST][ADMIN][CAIWU][SMS] != 65): ?>style="display:none;"<?php endif; ?>>-->
		<!--<li><a href="<?php echo U('Caiwu/sms');?>" <?php if($myaction == 'sms'): ?>style="color:#fff;"<?php endif; ?>><i class="fa fa-circle-o"></i>信息费</a>-->
		<!--</li>-->
		<!--</ul>-->
		<!---->
		<!--<ul class="treeview-menu" <?php if(!$_SESSION[C('ADMIN_AUTH_KEY')]): ?>style="display:none;"<?php endif; ?>>-->
		<!--<li><a href="<?php echo U('Caiwu/changetuijianmoney');?>" <?php if($myaction == 'changetuijianmoney'): ?>style="color:#fff;"<?php endif; ?>><i class="fa fa-street-view"></i>修改推荐收入</a></li>-->
		<!--</ul>-->
		
		</li>
		
		<li <?php if($mycontroller == 'Fengongshi'): ?>class="treeview active" <?php else: ?>class="treeview"<?php endif; ?>
		 <?php if(!$_SESSION[C('ADMIN_AUTH_KEY')]&&!$_SESSION[_ACCESS_LIST][ADMIN][CAIWU][FENGONGSHI]): ?>style="display:none;"<?php endif; ?>>
		<!--<a href="#"><i class="fa fa-building  text-primary fa-lg" ></i> <span>分公司管理</span></a>-->
		<!--<ul class="treeview-menu" <?php if(!$_SESSION[C('ADMIN_AUTH_KEY')]&&$_SESSION[_ACCESS_LIST][ADMIN][FENGONGSHI][ADD] != 71): ?>style="display:none;"<?php endif; ?>>-->
		<!--<li><a href="<?php echo U('Fengongshi/add');?>" <?php if($mycontroller == 'Fengongshi' and $myaction == 'add'): ?>style="color:#fff;"<?php endif; ?>><i class="fa fa-cube"></i>添加分公司</a></li>-->
		<!--</ul>-->
		<!--<ul class="treeview-menu" <?php if(!$_SESSION[C('ADMIN_AUTH_KEY')]&&$_SESSION[_ACCESS_LIST][ADMIN][FENGONGSHI][FGSLIST] != 68): ?>style="display:none;"<?php endif; ?>>-->
		<!--<li><a href="<?php echo U('Fengongshi/fgslist');?>" <?php if($myaction == 'fgslist'): ?>style="color:#fff;"<?php endif; ?>><i class="fa  fa-database"></i>分公司管理</a></li>-->
		<!--</ul>-->
				
		</li>
		
		<li <?php if($mycontroller == 'Wenzhang'): ?>class="treeview active" <?php else: ?>class="treeview"<?php endif; ?>
		 <?php if(!$_SESSION[C('ADMIN_AUTH_KEY')]&&!$_SESSION[_ACCESS_LIST][ADMIN][WENZHANG]): ?>style="display:none;"<?php endif; ?>>
		<a href="#"><i class="fa fa-file-text-o  text-primary fa-lg" ></i> <span>文章管理</span></a>
		<ul class="treeview-menu" <?php if(!$_SESSION[C('ADMIN_AUTH_KEY')]&&$_SESSION[_ACCESS_LIST][ADMIN][WENZHANG][ADDCAT] != 87): ?>style="display:none;"<?php endif; ?>>
		<li><a href="<?php echo U('Wenzhang/addcat');?>" <?php if($myaction == 'addcat'): ?>style="color:#fff;"<?php endif; ?>><i class="fa fa-edit"></i>添加分类</a></li>
		</ul>
		<ul class="treeview-menu" <?php if(!$_SESSION[C('ADMIN_AUTH_KEY')]&&$_SESSION[_ACCESS_LIST][ADMIN][WENZHANG][WENZHANGCAT] != 86): ?>style="display:none;"<?php endif; ?>>
		<li><a href="<?php echo U('Wenzhang/wenzhangcat');?>" <?php if($myaction == 'wenzhangcat'): ?>style="color:#fff;"<?php endif; ?>><i class="fa  fa-list"></i>分类管理</a></li>
		</ul>
		<ul class="treeview-menu" <?php if(!$_SESSION[C('ADMIN_AUTH_KEY')]&&$_SESSION[_ACCESS_LIST][ADMIN][WENZHANG][ADD] != 81): ?>style="display:none;"<?php endif; ?>>
		<li><a href="<?php echo U('Wenzhang/add');?>" <?php if($mycontroller == 'Wenzhang' and $myaction == 'add'): ?>style="color:#fff;"<?php endif; ?>><i class="fa fa-file-text-o"></i>添加文章</a></li>
		</ul>
		<ul class="treeview-menu" <?php if(!$_SESSION[C('ADMIN_AUTH_KEY')]&&$_SESSION[_ACCESS_LIST][ADMIN][WENZHANG][WENZHANG] != 77): ?>style="display:none;"<?php endif; ?>>
		<li><a href="<?php echo U('Wenzhang/wenzhang');?>" <?php if($myaction == 'wenzhang'): ?>style="color:#fff;"<?php endif; ?>><i class="fa  fa-file-word-o"></i>文章管理</a></li>
		</ul>	
		<ul class="treeview-menu" <?php if(!$_SESSION[C('ADMIN_AUTH_KEY')]&&$_SESSION[_ACCESS_LIST][ADMIN][WENZHANG][WENZHANG_TRASH] != 78): ?>style="display:none;"<?php endif; ?>>
		<li><a href="<?php echo U('Wenzhang/wenzhang_trash');?>" <?php if($myaction == 'wenzhang_trash'): ?>style="color:#fff;"<?php endif; ?>><i class="fa  fa-file"></i>文章回收站</a></li>
		</ul>		
		</li>
		
		<li <?php if($mycontroller == 'Sys'): ?>class="treeview active" <?php else: ?>class="treeview"<?php endif; ?>
		 <?php if(!$_SESSION[C('ADMIN_AUTH_KEY')]&&!$_SESSION[_ACCESS_LIST][ADMIN][SYS]): ?>style="display:none;"<?php endif; ?>>
		<!--<a href="#"><i class="fa  fa-cogs  text-primary fa-lg" ></i> <span>系统管理</span></a>-->
		<!--<ul class="treeview-menu" <?php if(!$_SESSION[C('ADMIN_AUTH_KEY')]&&$_SESSION[_ACCESS_LIST][ADMIN][SYS][CONFIG] != 93): ?>style="display:none;"<?php endif; ?>>-->
		<!--<li><a href="<?php echo U('Sys/config');?>" <?php if($myaction == 'config'): ?>style="color:#fff;"<?php endif; ?>><i class="fa fa-cogs "></i>业务参数设置</a></li>-->
		<!--</ul>-->

		<!--<ul class="treeview-menu" <?php if(!$_SESSION[C('ADMIN_AUTH_KEY')]&&$_SESSION[_ACCESS_LIST][ADMIN][SYS][BANKS] != 95): ?>style="display:none;"<?php endif; ?>>-->
		<!--<li><a href="<?php echo U('Sys/banks');?>" <?php if($myaction == 'banks'): ?>style="color:#fff;"<?php endif; ?>><i class="fa fa-bank"></i>银行管理</a></li>-->
		<!--</ul>	-->
		<!---->
		<!--<ul class="treeview-menu" <?php if(!$_SESSION[C('ADMIN_AUTH_KEY')]): ?>style="display:none;"<?php endif; ?>>-->
		<!--<li><a href="<?php echo U('Sys/adminlogs');?>" <?php if($myaction == 'adminlogs'): ?>style="color:#fff;"<?php endif; ?>><i class="fa fa-bank"></i>操作日志</a></li>-->
		<!--</ul>-->
		<!--<ul class="treeview-menu" <?php if(!$_SESSION[C('ADMIN_AUTH_KEY')]): ?>style="display:none;"<?php endif; ?>>-->
		<!--<li><a href="<?php echo U('Sys/fgslogs');?>" <?php if($myaction == 'fgslogs'): ?>style="color:#fff;"<?php endif; ?>><i class="fa fa-bank"></i>分公司操作日志</a></li>-->
		<!--</ul>-->
		<!--<ul class="treeview-menu" <?php if(!$_SESSION[C('ADMIN_AUTH_KEY')]): ?>style="display:none;"<?php endif; ?>>-->
		<!--<li><a href="<?php echo U('Sys/quickqueue');?>" <?php if($myaction == 'quickqueue'): ?>style="color:#fff;"<?php endif; ?>><i class="fa fa-bank"></i>调整队列</a></li>-->
		<!--</ul>-->
		<!--<ul class="treeview-menu" <?php if(!$_SESSION[C('ADMIN_AUTH_KEY')]): ?>style="display:none;"<?php endif; ?>>-->
		<!--<li><a href="<?php echo U('Sys/quickqueue2');?>" <?php if($myaction == 'quickqueue2'): ?>style="color:#fff;"<?php endif; ?>><i class="fa fa-bank"></i>调整队列二</a></li>-->
		<!--</ul>-->
		<!--<ul class="treeview-menu" <?php if(!$_SESSION[C('ADMIN_AUTH_KEY')]): ?>style="display:none;"<?php endif; ?>>-->
		<!--<li><a href="<?php echo U('Sys/editqueuelogs');?>" <?php if($myaction == 'editqueuelogs'): ?>style="color:#fff;"<?php endif; ?>><i class="fa fa-bank"></i>调整日志</a></li>-->
		<!--</ul>-->
		<!---->
		
		</li>
		
		<li <?php if($mycontroller == 'Rbac'): ?>class="treeview active" <?php else: ?>class="treeview"<?php endif; ?>
		 <?php if(!$_SESSION[C('ADMIN_AUTH_KEY')]&&!$_SESSION[_ACCESS_LIST][ADMIN][RBAC]): ?>style="display:none;"<?php endif; ?>>
		<a href="#"><i class="fa fa-circle-o text-aqua" ></i> <span>权限管理</span></a>
		<ul class="treeview-menu" <?php if(!$_SESSION[C('ADMIN_AUTH_KEY')]&&$_SESSION[_ACCESS_LIST][ADMIN][RBAC][INDEX] != 99): ?>style="display:none;"<?php endif; ?>>
		<li><a href="<?php echo U('Rbac/index');?>" <?php if($myaction == 'index'): ?>style="color:#fff;"<?php endif; ?>><i class="fa fa-circle-o text-aqua"></i>管理员列表</a></li>
		</ul>		
		<ul class="treeview-menu" <?php if(!$_SESSION[C('ADMIN_AUTH_KEY')]&&$_SESSION[_ACCESS_LIST][ADMIN][RBAC][ROLE] != 100): ?>style="display:none;"<?php endif; ?>>
		<li><a href="<?php echo U('Rbac/role');?>" <?php if($myaction == 'role'): ?>style="color:#fff;"<?php endif; ?>><i class="fa fa-circle-o text-aqua"></i>角色列表</a></li>
		</ul>		
		<ul class="treeview-menu" <?php if(!$_SESSION[C('ADMIN_AUTH_KEY')]&&$_SESSION[_ACCESS_LIST][ADMIN][RBAC][NODE] != 101): ?>style="display:none;"<?php endif; ?>>
		<li><a href="<?php echo U('Rbac/node');?>" <?php if($myaction == 'node'): ?>style="color:#fff;"<?php endif; ?>><i class="fa fa-circle-o text-aqua"></i>节点列表</a></li>
		</ul>		
				
		</li>
        
        <li <?php if($mycontroller == 'Koubei'): ?>class="treeview active" <?php else: ?>class="treeview"<?php endif; ?>
		 <?php if(!$_SESSION[C('ADMIN_AUTH_KEY')]&&!$_SESSION[_ACCESS_LIST][ADMIN][KOUBEI]): ?>style="display:none;"<?php endif; ?>>
		<a href="#"><i class="fa fa-circle-o text-aqua" ></i> <span>线路管理</span></a>
		<ul class="treeview-menu" <?php if(!$_SESSION[C('ADMIN_AUTH_KEY')]&&$_SESSION[_ACCESS_LIST][ADMIN][KOUBEI][INDEX] != 112): ?>style="display:none;"<?php endif; ?>>
		<li><a href="<?php echo U('Koubei/index');?>" <?php if($myaction == 'index'): ?>style="color:#fff;"<?php endif; ?>><i class="fa fa-circle-o text-aqua"></i>线路line</a></li>
		</ul>		
		<ul class="treeview-menu" <?php if(!$_SESSION[C('ADMIN_AUTH_KEY')]&&$_SESSION[_ACCESS_LIST][ADMIN][KOUBEI][SHOPAPPLY] != 119): ?>style="display:none;"<?php endif; ?>>
		<li><a href="<?php echo U('Koubei/stop');?>" <?php if($myaction == 'shopapply'): ?>style="color:#fff;"<?php endif; ?>><i class="fa fa-circle-o text-aqua"></i>站点stop</a></li>
		</ul>		
		<ul class="treeview-menu" <?php if(!$_SESSION[C('ADMIN_AUTH_KEY')]&&$_SESSION[_ACCESS_LIST][ADMIN][KOUBEI][ALBUM] != 121): ?>style="display:none;"<?php endif; ?>>
		<li><a href="<?php echo U('Koubei/album');?>" <?php if($myaction == 'album'): ?>style="color:#fff;"<?php endif; ?>><i class="fa fa-circle-o text-aqua"></i>商家相册</a></li>
		</ul>	
		<ul class="treeview-menu" <?php if(!$_SESSION[C('ADMIN_AUTH_KEY')]&&$_SESSION[_ACCESS_LIST][ADMIN][KOUBEI][COMMENT] != 123): ?>style="display:none;"<?php endif; ?>>
		<li><a href="<?php echo U('Koubei/comment');?>" <?php if($myaction == 'comment'): ?>style="color:#fff;"<?php endif; ?>><i class="fa fa-circle-o text-aqua"></i>评论管理</a></li>
		</ul>	
		<ul class="treeview-menu" <?php if(!$_SESSION[C('ADMIN_AUTH_KEY')]&&$_SESSION[_ACCESS_LIST][ADMIN][KOUBEI][SHOPCAT] != 126): ?>style="display:none;"<?php endif; ?>>
		<li><a href="<?php echo U('Koubei/shopcat');?>" <?php if($myaction == 'shopcat'): ?>style="color:#fff;"<?php endif; ?>><i class="fa fa-circle-o text-aqua"></i>分类管理</a></li>
		</ul>
		
        <ul class="treeview-menu" <?php if(!$_SESSION[C('ADMIN_AUTH_KEY')]&&$_SESSION[_ACCESS_LIST][ADMIN][KOUBEI][COUPON] != 132): ?>style="display:none;"<?php endif; ?>>
		
		<li><a href="<?php echo U('Koubei/coupon');?>" <?php if($myaction == 'coupon'): ?>style="color:#fff;"<?php endif; ?>><i class="fa fa-circle-o text-aqua"></i>券的管理</a></li>
		</ul>		
		<ul class="treeview-menu" <?php if(!$_SESSION[C('ADMIN_AUTH_KEY')]&&$_SESSION[_ACCESS_LIST][ADMIN][KOUBEI][BAOCUO] != 133): ?>style="display:none;"<?php endif; ?>>
		
		<li><a href="<?php echo U('Koubei/baocuo');?>" <?php if($myaction == 'baocuo'): ?>style="color:#fff;"<?php endif; ?>><i class="fa fa-circle-o text-aqua"></i>报错管理</a></li>
		</ul>		
				
		</li>
		
        <li <?php if($mycontroller == 'Ads'): ?>class="treeview active" <?php else: ?>class="treeview"<?php endif; ?>
		 <?php if(!$_SESSION[C('ADMIN_AUTH_KEY')]&&!$_SESSION[_ACCESS_LIST][ADMIN][ADS]): ?>style="display:none;"<?php endif; ?>>
		<!--<a href="#"><i class="fa fa-circle-o text-aqua" ></i> <span>广告管理</span></a>-->
		<!---->
		<!--<ul class="treeview-menu" <?php if(!$_SESSION[C('ADMIN_AUTH_KEY')]&&$_SESSION[_ACCESS_LIST][ADMIN][ADS][HOMEHZSC] != 137): ?>style="display:none;"<?php endif; ?>>-->
		<!--<li><a href="<?php echo U('Ads/homehzsc');?>" <?php if($myaction == 'homehzsc'): ?>style="color:#fff;"<?php endif; ?>><i class="fa fa-circle-o text-aqua"></i>首页合作商城</a></li>-->
		<!--</ul>		-->
		<ul class="treeview-menu" <?php if(!$_SESSION[C('ADMIN_AUTH_KEY')]&&$_SESSION[_ACCESS_LIST][ADMIN][ADS][HOMEMID] != 138): ?>style="display:none;"<?php endif; ?>>
		<li><a href="<?php echo U('Ads/homemid');?>" <?php if($myaction == 'homemid'): ?>style="color:#fff;"<?php endif; ?>><i class="fa fa-circle-o text-aqua"></i>首页中部八格广告</a></li>
		</ul>		
		<ul class="treeview-menu" <?php if(!$_SESSION[C('ADMIN_AUTH_KEY')]&&$_SESSION[_ACCESS_LIST][ADMIN][ADS][HOMETJ] != 139): ?>style="display:none;"<?php endif; ?>>
		<li><a href="<?php echo U('Ads/hometj');?>" <?php if($myaction == 'hometj'): ?>style="color:#fff;"<?php endif; ?>><i class="fa fa-circle-o text-aqua"></i>首页推荐商品</a></li>
		</ul>	
		<ul class="treeview-menu" <?php if(!$_SESSION[C('ADMIN_AUTH_KEY')]&&$_SESSION[_ACCESS_LIST][ADMIN][ADS][KBSLIDER] != 143): ?>style="display:none;"<?php endif; ?>>
		<li><a href="<?php echo U('Ads/kbslider');?>" <?php if($myaction == 'kbslider'): ?>style="color:#fff;"<?php endif; ?>><i class="fa fa-circle-o text-aqua"></i>口碑滚动</a></li>
		</ul>	
		<ul class="treeview-menu" <?php if(!$_SESSION[C('ADMIN_AUTH_KEY')]&&$_SESSION[_ACCESS_LIST][ADMIN][ADS][KBMID] != 144): ?>style="display:none;"<?php endif; ?>>
		<li><a href="<?php echo U('Ads/kbmid');?>" <?php if($myaction == 'kbmid'): ?>style="color:#fff;"<?php endif; ?>><i class="fa fa-circle-o text-aqua"></i>口碑中部四格</a></li>
		</ul>		
				
		</li>
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>
<style>
.mypage{text-align:center;}
.mypage .current{padding:2px 6px;background:#0ae;border:1px solid #ddd;color:#fff;margin:3px;}
.mypage a{padding:2px 6px;background:#fff;border:1px solid #ddd;color:#0ae;margin:3px;}
</style>
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        主页
        <small>管理面板</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content" style="overflow:hidden;">
	<table class="table table-striped">
   <caption>登录用户管理<span style="margin-left:10px;color:#cc0000;">(<?php echo ($usernum); ?>条记录)</span></caption>
   <caption>
	<input type="text" name="keyword" id="keyword" value="<?php echo ($keyword); ?>" placeholder="用户手机或名称"><input type="button" name="serach" id="search" value="搜索">
	<input type="button" name="toexcel" id="toexcel" value="导出Excel">
   </caption>
   <thead>
      <tr>
         <th>编号</th>
         <th>站名</th>
         <th>省份id</th>
         <th>城市id</th>
         <th>区域id</th>
         <th>标记</th>
         <!--<th>是否绑定微信</th>-->
         <!--<th>QQ</th>-->
         <!--<th>性别</th>-->
         <!--<th>绑定银行卡数</th>-->
         <th>上次登录时间</th>
         <!--<th>IP</th>-->
         <!--<th>注册来源</th>-->
         <!--<th>是否激活</th>-->
         <th>操作</th>
      </tr>
   </thead>
   <tbody>
	  <?php if(is_array($userlist)): foreach($userlist as $key=>$v): ?><tr >
         <td><?php echo ($v["id"]); ?></td>
         <td><?php echo ($v["linename"]); ?></td>

         <td><?php echo ($v["province_id"]); ?></td>
         <td><?php echo ($v["city_id"]); ?></td>
         <!--<td><?php echo ($v["area_id"]); ?></td>-->
		  <td><?php echo ($v["remark"]); ?></td>
		  <td><?php echo ($v["is_active"]); ?></td>
		  <td><?php echo (date("Y-m-d H:m",$v["create_time"])); ?></td>
         <!--<td><?php if($v[wx_id] != ''): ?><span style="color:green">是</span>-->
			<!--<button class="btn btn-default btn-xs edit-userwx" data-toggle="modal" data-id="<?php echo ($v["user_id"]); ?>" data-target="#myModal4">解除绑定</button>-->
			<!--<?php else: ?><span style="color:red">否</span><?php endif; ?></td>-->
         <!--<td><?php echo ($v["qq"]); ?></td>-->
         <!--<td></td>-->
         <!--<td>&nbsp;&nbsp;<a href="<?php echo U('Admin/Caiwu/userbank');?>?keyword=<?php echo ($v["phone"]); ?>">查看</a></td>-->
         <!--<td><?php if($v[last_login_time] > 0): echo (date("Y-m-d H:m",$v["last_login_time"])); else: ?>未登录过<?php endif; ?></td>-->
         <!--<td><?php echo ($v["last_login_ip"]); ?></td>-->
         <!--<td><?php echo (getregtype($v["reg_type"])); ?></td>-->
         <!--<td>-->
			<!--<?php if($v[is_active] == 1): ?><span style="color:green">已激活</span><?php endif; ?>-->
			<!--<?php if($v[is_active] == 0): ?><span style="color:red">未激活</span><?php endif; ?>-->
		 <!--</td>-->
         <td>
			<button class="btn btn-default btn-xs edit-userdata" data-toggle="modal"  data-target="#myModal">编辑</button><br><br>
			<!--<button class="btn btn-default btn-xs login-user" data-phone="<?php echo ($v[phone]); ?>" data-pass="<?php echo ($v[password]); ?>">登录</button><br><br>-->
			<?php if($v[is_active] == 1): ?><!--<button class="btn btn-xs active-user" data-toggle="modal" data-id="<?php echo ($v["is_active"]); ?>" data-target="#myModal3">禁用</button>--><?php endif; ?>
			<?php if($v[is_active] == 0): ?><!--<button class="btn btn-success btn-xs active-user" data-toggle="modal" data-id="<?php echo ($v["is_active"]); ?>"  data-target="#myModal3">激活</button>--><?php endif; ?>
		 </td>
      </tr><?php endforeach; endif; ?>
	  <tr>
		<td colspan="15" class="mypage"><?php echo ($page); ?></td>
	  </tr>
   </tbody>
</table>
	</section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  
<!-- 模态框（Modal） -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" 
   aria-labelledby="myModalLabel" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" 
               data-dismiss="modal" aria-hidden="true">
                  &times;
            </button>
            <h4 class="modal-title" id="myModalLabel">
               用户基本信息
            </h4>
         </div>
         <div class="modal-body">
			<form class="form-horizontal" role="form">
			<div class="form-group">
			<label class="col-sm-2 control-label">编号</label>
			<div class="col-sm-10">
			<p class="form-control-static" id="modal-user-id"></p>
			<input type="hidden"  name="user-id">
			</div>
			</div>
			<div class="form-group">
			<label class="col-sm-2 control-label">站名</label>
			<div class="col-sm-10">
			<p class="form-control-static" id="modal-user-name"></p></div>
			</div>
			
			<div class="form-group">
			<label class="col-sm-2 control-label">省份id</label>
			<div class="col-sm-10">
			<input type="text" name="phone" class="form-control"></div>
			</div>
			
			<div class="form-group">
			<label class="col-sm-2 control-label">城市id</label>
			<div class="col-sm-10">
			<input type="text" name="email" class="form-control"></div>
			</div>
			
			<div class="form-group">
			<label class="col-sm-2 control-label">标记</label>
			<div class="col-sm-10">
			<input type="text" name="real_name" class="form-control"></div>
			</div>
			
			<div class="form-group">
			<!--<label class="col-sm-2 control-label">性别</label>-->
			<!--<div class="col-sm-10">-->
			<!--<label class="radio-inline">-->
			<!--<input type="radio" id="sex1" name="sex" value="1"> 男-->
			<!--</label>-->
			<!--<label class="radio-inline">-->
			<!--<input type="radio" id="sex2" name="sex" value="2"> 女-->
			<!--</label>-->
			<!--</div>-->
			</div>
			
			<!--<div class="form-group">-->
			<!--<label for="inputqq" class="col-sm-2 control-label">QQ</label>-->
			<!--<div class="col-sm-10">-->
			<!--<input type="text" name="qq" class="form-control"></div>-->
			<!--</div>-->
			<!---->
			<!--<div class="form-group">-->
			<!--<label for="inputPassword" class="col-sm-2 control-label">密码</label>-->
			<!--<div class="col-sm-10">-->
			<!--<input type="text" name="password" class="form-control"><span style="padding:5px;">留空为不修改</span>-->
			<!--</div>-->
			</div>
			
			</form>
			
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default" 
               data-dismiss="modal">关闭
            </button>
            <button type="button" class="btn btn-primary" id="user-submit">
               提交更改
            </button>
         </div>
      </div><!-- /.modal-content -->
</div><!-- /.modal -->
</div>

<!-- 模态框（Modal） -->
<div class="modal fade" id="myModal3" tabindex="-1" role="dialog" 
   aria-labelledby="myModalLabel" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" 
               data-dismiss="modal" aria-hidden="true">
                  &times;
            </button>
            <h4 class="modal-title" id="myModalLabel">
               会员激活与禁用
            </h4>
         </div>
         <div class="modal-body">
			<form class="form-horizontal" role="form">

			<div class="form-group">
			<div class="col-sm-10">
			确定要提交吗？
			<input type="hidden" name="active_uid" class="form-control">
			<input type="hidden" name="active_id" class="form-control">
			</div>
			</div>
			
			</form>
			
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default" 
               data-dismiss="modal">取消
            </button>
            <button type="button" class="btn btn-primary" id="user-active">
               确定
            </button>
         </div>
      </div><!-- /.modal-content -->
</div><!-- /.modal -->
</div>

<!-- 模态框（Modal） -->
<div class="modal fade" id="myModal4" tabindex="-1" role="dialog" 
   aria-labelledby="myModalLabel" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" 
               data-dismiss="modal" aria-hidden="true">
                  &times;
            </button>
            <h4 class="modal-title" id="myModalLabel">
               会员微信解绑
            </h4>
         </div>
         <div class="modal-body">
			<form class="form-horizontal" role="form">

			<div class="form-group">
			<div class="col-sm-10">
			确定要提交吗？
			<input type="hidden" name="wx_id" class="form-control">
			</div>
			</div>
			
			</form>
			
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default" 
               data-dismiss="modal">取消
            </button>
            <button type="button" class="btn btn-primary" id="douserwx">
               确定
            </button>
         </div>
      </div><!-- /.modal-content -->
</div><!-- /.modal -->
</div>
<script>
$(document).ready(function(e) {
	$('.edit-userdata').click(function(){
		$('#myAlert').remove();
		console.log($(this).parent().siblings().eq(1).text());
		$('#modal-user-id').text($(this).parent().siblings().eq(0).text());
		$('#modal-user-name').text($(this).parent().siblings().eq(1).text());
		$(':input[name="user-id"]').val($(this).parent().siblings().eq(0).text());
		$(':input[name="phone"]').val($(this).parent().siblings().eq(4).text());
		$(':input[name="email"]').val($(this).parent().siblings().eq(3).text());
		$(':input[name="real_name"]').val($(this).parent().siblings().eq(5).text());
		//$(':input[name="sexx"]').val($(this).parent().siblings().eq(7).text());
		if($(this).parent().siblings().eq(8).text()=="男"){
			$('#sex1').attr("checked","checked");
		}
		if($(this).parent().siblings().eq(8).text()=="女"){
			$('#sex2').attr("checked","checked");
		}
		$(':input[name="qq"]').val($(this).parent().siblings().eq(7).text());
	});
	$('#user-submit').click(function(){
		var url="<?php echo U('edit_user');?>?"+$('form').serialize();
		console.log(url);
		$.get(url,function(data,status){
		console.log(data);
		if(data=='edit-success'){
			//$('#myModal').modal('hide');
			$('#myAlert').remove();
			var html='<div id="myAlert" class="alert alert-success"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>修改成功！</strong></div>';
			$('.modal-header').append(html);
			location.href ="<?php echo U('index');?>";
		}
		else{
			var html='<div id="myAlert" class="alert alert-warning"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>提交失败，请检查网络并重试</strong></div>';
			$('.modal-header').append(html);
		}
		});
	});
	
	$('.active-user').click(function(){
		$('#myAlert').remove();
		//console.log($(this).parent().siblings().eq(1).text());
		$(':input[name="active_uid"]').val($(this).parent().siblings().eq(0).text());
		$(':input[name="active_id"]').val($(this).attr('data-id'));
	});
	
	$('#user-active').click(function(){
		var url="<?php echo U('active_user');?>?"+$('form').serialize();
		console.log(url);
		$.get(url,function(data,status){
		console.log(data);
		if(data=='active-success'){
			//$('#myModal').modal('hide');
			$('#myAlert').remove();
			var html='<div id="myAlert" class="alert alert-success"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>操作成功！</strong></div>';
			$('.modal-header').append(html);
			window.location.reload();
		}
		
		else{
			var html='<div id="myAlert" class="alert alert-warning"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>提交失败，请检查网络并重试</strong></div>';
			$('.modal-header').append(html);
		}
		});
	});

	$('.edit-userwx').click(function(){
		$('#myAlert').remove();
		//console.log($(this).parent().siblings().eq(1).text());
		$(':input[name="wx_id"]').val($(this).attr('data-id'));
	});
	
	$('#douserwx').click(function(){
		var url="<?php echo U('dowx_user');?>?"+$('form').serialize();
		console.log(url);
		$.get(url,function(data,status){
		console.log(data);
		if(data=='active-success'){
			//$('#myModal').modal('hide');
			$('#myAlert').remove();
			var html='<div id="myAlert" class="alert alert-success"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>操作成功！</strong></div>';
			$('.modal-header').append(html);
			window.location.reload();
		}
		
		else{
			var html='<div id="myAlert" class="alert alert-warning"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>提交失败，请检查网络并重试</strong></div>';
			$('.modal-header').append(html);
		}
		});
	});
	
	//登录
	$('.login-user').click(function(){
		var phone=$(this).attr('data-phone');
		var password=$(this).attr('data-pass');
		$.get("<?php echo U('loginByphone');?>?phone="+phone+"&password="+password, function(data,status){
			//console.log(data);
			
			if(data>=0){
				//alert("aaaa");
				
				window.open ("<?php echo C('siteurl');?>/User/index");
			}
			else{
				alert("用户登录失败");
			}
		});
				

	
	});
	
	//搜索
	$('#search').click(function(){
	var keyword=$('#keyword').val();
	
	var url = "<?php echo U('index');?>?keyword="+keyword;
	window.location.href=url;
	});
	
	//导出excel
	$('#toexcel').click(function(){
	var keyword=$('#keyword').val();
	
	var url = "<?php echo U('usertoexcel');?>?keyword="+keyword;
	window.location.href=url;
	});

});
</script>
<footer class="main-footer">
    <div class="pull-right hidden-xs">
      <b>Version</b> 0.0.1
    </div>
    <strong>Copyright &copy; 2015-2016 <a target="_blank" href="http://www.bzb898.com">报账宝</a>.</strong> All rights
    reserved.
  </footer>

  <!-- Control Sidebar -->
  
  <!-- /.control-sidebar -->
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->


<!-- jQuery UI 1.11.4 -->
<script src="/lxbus/Application/Admin/View//Public//static/js/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->

<!-- Bootstrap 3.3.5 -->
<script src="/lxbus/Application/Admin/View//Public//bootstrap/js/bootstrap.min.js"></script>
<!-- Morris.js charts -->
<script src="/lxbus/Application/Admin/View//Public//static/js//raphael-min.js"></script>
<script src="/lxbus/Application/Admin/View//Public//plugins/morris/morris.min.js"></script>
<!-- Sparkline -->
<script src="/lxbus/Application/Admin/View//Public//plugins/sparkline/jquery.sparkline.min.js"></script>
<!-- jvectormap -->
<script src="/lxbus/Application/Admin/View//Public//plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="/lxbus/Application/Admin/View//Public//plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
<!-- jQuery Knob Chart -->
<script src="/lxbus/Application/Admin/View//Public//plugins/knob/jquery.knob.js"></script>
<!-- daterangepicker -->
<script src="/lxbus/Application/Admin/View//Public//static/js/moment.min.js"></script>
<script src="/lxbus/Application/Admin/View//Public//plugins/daterangepicker/daterangepicker.js"></script>
<!-- datepicker -->
<script src="/lxbus/Application/Admin/View//Public//plugins/datepicker/bootstrap-datepicker.js"></script>
<!-- Bootstrap WYSIHTML5 -->
<script src="/lxbus/Application/Admin/View//Public//plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
<!-- Slimscroll -->
<script src="/lxbus/Application/Admin/View//Public//plugins/slimScroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="/lxbus/Application/Admin/View//Public//plugins/fastclick/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="/lxbus/Application/Admin/View//Public//dist/js/app.min.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->

<!-- AdminLTE for demo purposes -->
<script src="/lxbus/Application/Admin/View//Public//dist/js/demo.js"></script>
</body>
</html>
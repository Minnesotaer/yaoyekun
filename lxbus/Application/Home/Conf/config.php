<?php
return array(
    'DB_TYPE' => 'mysql', // 数据库类型
    'DB_HOST' => '111.74.238.194', // 服务器地址
    'DB_NAME' => 'lxbus', // 数据库名
    'DB_USER' => 'lxbus', // 用户名
    'DB_PWD' => '@lxbus176!', // 密码
    'DB_PORT' => 3306, // 端口
    'DB_PARAMS' => array(), // 数据库连接参数
    'DB_PREFIX' => 'lxbus_', // 数据库表前缀
    'DB_CHARSET'=> 'utf8', // 字符集
    'DB_DEBUG' => TRUE, // 数据库调试模式 开启后可以记录SQL日志
    'site_base_url' => 'http://127.0.0.1/lxbus/',
    'TMPL_PARSE_STRING' => array(
        '__STATIC__' => __ROOT__.'/Application/'.MODULE_NAME.'/View/' . '/Public/static',
        '__PUBLIC__' => __ROOT__.'/Application/'.MODULE_NAME.'/View/' . '/Public/'
    ),
    //CSRF
    'TOKEN_ON'      =>    true,  // 是否开启令牌验证 默认关闭
    'TOKEN_NAME'    =>    '__hash__',    // 令牌验证的表单隐藏字段名称，默认为__hash__
    'TOKEN_TYPE'    =>    'md5',  //令牌哈希验证规则 默认为MD5
    'TOKEN_RESET'   =>    true,  //令牌验证出错后是否重置令牌 默认为true
    //是否开启模板布局 根据个人习惯设置
    'LAYOUT_ON'=>false,

    //rbac验证
    'NOT_AUTH_MODULE' => 'Index', //无需认证的控制器
    'ADMIN_AUTH_KEY'    => 'lxbus',
    'ADMIN_AUTH_KEY_TECH'    => 'bzbtech',
    'USER_AUTH_ON'      => '1',
    'USER_AUTH_TYPE'    => '1',//2为即时验证模式，别的数字为登陆验证
    'RBAC_ROLE_TABLE'   => 'lxbus_role',
    'RBAC_USER_TABLE'   => 'lxbus_role_user',
    'RBAC_ACCESS_TABLE' => 'lxbus_access',
    'RBAC_NODE_TABLE'   => 'lxbus_node',

    //默认错误跳转对应的模板文件
    'TMPL_ACTION_ERROR' => 'Public:error',
    //默认成功跳转对应的模板文件
    'TMPL_ACTION_SUCCESS' => 'Public:success',

    //修改推荐密码
    'TUIJIAN_PASS'   => 'bzb8981688',

    //调整队列密码
    'QUEUE_PASS'   => 'bzb89816888',
);
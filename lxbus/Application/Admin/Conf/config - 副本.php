<?php
return array(
	'DB_TYPE' => 'mysql', // 数据库类型
	'DB_HOST' => '117.21.226.193', // 服务器地址
	'DB_NAME' => 'bzb898', // 数据库名
	'DB_USER' => 'mybzb898', // 用户名
	'DB_PWD' => '@mybzb898176!', // 密码
	'DB_PORT' => 3306, // 端口
	'DB_PARAMS' => array(), // 数据库连接参数
	'DB_PREFIX' => 'bzb_', // 数据库表前缀
	'DB_CHARSET'=> 'utf8', // 字符集
	'DB_DEBUG' => TRUE, // 数据库调试模式 开启后可以记录SQL日志

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
    'LAYOUT_ON'=>false
		);
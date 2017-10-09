<?php



return array(
 //'配置项'=>'配置值'
 
 
//服务器正式数据库
// 'DB_TYPE'  => 'mysql',    //使用的数据库类型
// 'DB_HOST'  => '10.135.72.229',
// 'DB_NAME'  => 'stat',    //数据库名
// 'DB_USER'  => 'root',     //访问数据库账号
// 'DB_PWD'  => '1234',    //访问数据库密码
// 'DB_PORT'  => '3306',    //访问数据库密码
    
//本地测试数据库
 'DB_TYPE'  => 'mysql',    //使用的数据库类型
 'DB_HOST'  => 'localhost',
 'DB_NAME'  => 'stat',    //数据库名
 'DB_USER'  => 'root',     //访问数据库账号
 'DB_PWD'  => '',    //访问数据库密码
 'DB_PORT'  => '3306',    //访问数据库密码
    
    
 'DB_PREFIX'  => '',     //表前缀
 'APP_DEBUG'  => true,     //调试模式开关
 'URL_MODEL'  => 0,      //URL模式：0普通模式 1PATHINFO 2REWRITE 3兼容模式
 'HTML_CACHE_ON' => false,
 'TMPL_CACHE_ON'    => false,
 'SHOW_ERROR_MSG' => true,
 'SHOW_PAGE_TRACE' =>true, //开启页面Trace
 'TOKEN_ON'=> true,  // 是否开启令牌验证
 'TOKEN_NAME'=>'__hash__',    // 令牌验证的表单隐藏字段名称
 'TOKEN_TYPE'=>'md5',  //令牌哈希验证规则 默认为MD5
 'TOKEN_RESET'=>true,  //令牌验证出错后是否重置令牌 默认为true
 'DB_CHARSET' =>  'utf8',
 'COOKIE_EXPIRE'=>15000,
 'DATA_CACHE_SUBDIR'=>true, //启用哈希子目录缓存的方式
 'LOG_RECORD'=>true,  // 进行日志记录
 'wname'=>'', //如果是网站根目录,请留空。如果是子目录写好对应的路径
 'LOAD_EXT_CONFIG' => 'helper',
 
 'DB_CONFIG1' => array(
        'db_type'  => 'mysql',
        'db_user'  => 'root',
        'db_pwd'   => '1234',
        'db_host'  => '10.135.72.229',
        'db_port'  => '3306',
        'db_name'  => 'stat'
    ),
  'DB_CONFIG2' => array(
        'db_type'  => 'mysql',
        'db_user'  => 'root',
        'db_pwd'   => '1234',
        'db_host'  => '10.135.72.229',
        'db_port'  => '3306',
        'db_name'  => 'gdmj'
    ),
    'DB_CONFIG3' => array(
        'db_type'  => 'mysql',
        'db_user'  => 'root',
        'db_pwd'   => '1234',
        'db_host'  => '10.135.72.229',
        'db_port'  => '3306',
        'db_name'  => 'stat'
    ),
);
?>

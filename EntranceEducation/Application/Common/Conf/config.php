<?php
return array(
	//'配置项'=>'配置值'
	
	
	// 'MODULE_ALLOW_LIST'  => array('Student','Admin'),
    // 'DEFAULT_MODULE'     => 'Student',

	'URL_HTML_SUFFIX' => '',  //伪静态设置为空
	'URL_MODEL' => 1,

	'SESSION_AUTO_START' => true,  //开启缓存

	'SHOW_PAGE_TRACE' => false, //开启trace


	// 配置数据库
	'DB_TYPE' => 'mysql', 
	'DB_HOST' => '127.0.0.1',
	'DB_USER' => 'root',
	'DB_PWD' => '',
	'DB_NAME'  => 'app_classtest',
	'DB_PORT' => '', 
	'DB_PREFIX' => 'cn_', 

	
	//解决了数据库默认小写问题
	'DB_PARAMS'    =>    array(\PDO::ATTR_CASE => \PDO::CASE_NATURAL),   

);
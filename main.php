<?php

// 入口文件所在目录
define('ROOT_PATH', dirname(__FILE__));

require ROOT_PATH . '/lib/SwooleServer.php';

$a = SwooleServer::getInstance(ROOT_PATH);

$a->run();

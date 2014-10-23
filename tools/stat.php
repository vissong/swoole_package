<?php

// 入口文件所在目录
define('ROOT_PATH', dirname(__FILE__));

$iniPath = ROOT_PATH . '/conf/serv_conf.ini';
$config = parse_ini_file($iniPath, true);

$_serverType = $config['LISTENING']['type'];
$_serverPort = $config['LISTENING']['port'];

if ($_serverType == 'tcp') {
    $type = SWOOLE_TCP;
} else {
    $type = SWOOLE_UDP;
}

$client = new swoole_client($type);
$client->connect('127.0.0.1', $_serverPort);

$client->send('swoole:stat');
var_dump($client->recv());

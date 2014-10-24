<?php
/**
 * onReceive 回调
 *
 * 可用变量：
 * @param $server  swoole_server 实例
 * @param $fd      连接的文件描述符
 * @param $fromId  来自哪个 poll 进程
 * @param $data    接收到的数据
 */

if (!IN_SWOOLE) {
    exit('this script only work in SwooleServer');
}

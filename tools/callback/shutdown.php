<?php
/**
 * onShutdown 回调
 *
 * 可用变量：
 * @param $server  swoole_server 实例
 */

if (!IN_SWOOLE) {
    exit('this script only work in SwooleServer');
}
<?php
/**
 * onTimer 回调
 *
 * 可用变量：
 * @param $server    swoole_server 实例
 * @param $interval  定时器时间间隔，根据$interval的值来区分是哪个定时器触发的
 */

if (!IN_SWOOLE) {
    exit('this script only work in SwooleServer');
}
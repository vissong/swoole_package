<?php
/**
 * onWorkerStop 回调
 *
 * 可用变量：
 * @param $server     swoole_server 实例
 * @param $workerId   一个从0-$worker_num之间的数字，表示这个worker进程的ID
 */

if (!IN_SWOOLE) {
    exit('this script only work in SwooleServer');
}
<?php
/**
 * onWorkerError 回调
 *
 * 可用变量：
 * @param $server       swoole_server 实例
 * @param $workerId     异常进程的编号
 * @param $workerPId    异常进程的ID
 * @param $errCode      退出的状态码
 */

if (!IN_SWOOLE) {
    exit('this script only work in SwooleServer');
}
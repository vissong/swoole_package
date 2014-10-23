<?php
/**
 * onFinish 回调
 *
 * 可用变量：
 * @param $server    swoole_server 实例
 * @param $taskId    任务ID
 * @param $data      任务处理的结果内容
 */

if (!IN_SWOOLE) {
    exit('this script only work in SwooleServer');
}
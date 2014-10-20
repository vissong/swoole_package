<?php
/**
 * onTask 回调
 *
 * 可用变量：
 * @param $server   swoole_server 实例
 * @param $taskId   任务ID，由swoole扩展内自动生成，用于区分不同的任务，不动 worker 分配的 task id 会重复
 * @param $fromId   来自于哪个worker进程
 * @param $data     任务的内容
 */

if (!IN_SWOOLE) {
    exit('this script only work in SwooleServer');
}
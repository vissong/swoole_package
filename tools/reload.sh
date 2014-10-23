#!/bin/sh

source `dirname $0`/_header.sh

process_count=`ps aux | grep ${main_script} | grep -v 'grep'| wc -l`

#判断server是否在运行
if [[ ${process_count} -eq 0 ]];then
    echo 'SwooleServer is not running'
else
    #主进程pid
    master_pid=`cat ${master_pid_log}`

    #通知重启 kill -SIGUSR1
    kill -USR1 ${master_pid}

    echo 'SwooleServer had reloaded'
fi
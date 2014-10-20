#!/bin/sh

source `dirname $0`/_header.sh

process_count=`ps aux | grep ${main_script} | grep -v 'grep'| wc -l`

#判断server是否在运行
if [[ ${process_count} -eq 0 ]];then
    echo 'SwooleServer is not running'
else
    #主进程pid
    master_pid=`cat ${master_pid_log}`

    #杀掉主进程 kill -15
    `kill ${master_pid}`

    #等3s,其他进程将被杀掉
    sleep 3

    process_count_result=`ps aux | grep ${main_script} | grep -v 'grep'| wc -l`
    if [[ ${process_count_result} -gt 0 ]];then
        ps aux | grep ${main_script} | grep -v 'grep'| awk '{print $2}' | xargs kill -9
    fi

    echo 'SwooleServer had stoped'
fi

#!/bin/sh

source `dirname $0`/_header.sh

master_pid=`cat ${master_pid_log}`
manager_pid=`cat ${manager_pid_log}`
start_script=${path}/start.sh


master=`ps aux | awk '{print $2}' | grep ${master_pid} | grep -v 'grep' | wc -l`
manager=`ps aux | awk '{print $2}' | grep ${manager_pid} | grep -v 'grep' | wc -l`

#如果 master 或者 manager 不存在了，那么重启server
if [[ ${master} -lt 1 ]] || [[ ${manager} -lt 1 ]]; then
    ps aux | grep ${main_script} | grep -v 'grep'| awk '{print $2}' | xargs kill -9
fi

sleep 2;

echo 'restart'

/bin/sh ${start_script}
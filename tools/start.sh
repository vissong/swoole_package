#!/bin/sh

source `dirname $0`/_header.sh

process_count=`ps aux | grep ${main_script} | grep -v 'grep'| wc -l`

#判断server是否在运行
if [[ ${process_count} -gt 0 ]];then
echo 'SwooleServer is running, please check or stop it first'
exit
fi

${phpPath} ${main_script}

master_pid=`cat ${master_pid_log}`
manager_pid=`cat ${manager_pid_log}`

echo "SwooleServer had started, master_pid ${master_pid}, manager_pid ${manager_pid}"
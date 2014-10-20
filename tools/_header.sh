#!/bin/sh

#php 所在路径
phpPath='/usr/bin/php'
#获取当前 shell 的绝对路径
x=`echo $0 | grep "^/"`
y=`echo $0 | grep "^\."`
if test "${x}"; then
    path=`dirname $0`
else
    path=`dirname $(pwd)/$0`
fi

cd ${path}/../
pwd=`pwd`

#main 文件路径
main_script=${pwd}/main.php
#日志目录
log_dir=${pwd}/log
#master_pid 文件路径
master_pid_log=${log_dir}/master_pid.log
#master_pid 文件路径
manager_pid_log=${log_dir}/manager_pid.log

#!/bin/sh

source `dirname $0`/_header.sh

stop_script=${path}/stop.sh
start_script=${path}/start.sh

/bin/sh ${stop_script}

/bin/sh ${start_script}
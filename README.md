swoole_package
==============

### 说明

基于 swoole，封装了在命令行模式下使用的 SwooleServer 类，支持配置。支持初始化框架文件。
完善冷启动，热启动，重启，监控等配套工具。

### 目的

在业务开发过程中需要构建各种不同用途的 sever，为了方便部署，同时保证这些 server 的高可用性。就需要完善的配套工具，来保证这一切。
这个 package，可以用于腾讯内部的包发布系统，同时对于独立部署的场景也可以支持。

### 目录结构

```
├── README.md
├── callback          		// 回调文件所保存的目录
├── check.php 				// 检测服务器是否正常工作脚本
├── conf
│   └── serv_conf.ini 		// server 配置文件
├── lib
│   └── SwooleServer.php 	// 核心类文件
├── log 					// 日志目录
├── main.php 				// 启动 server 的入口文件
├── stat.php 				// 得到统计数据的工具
├── test.php 				// 测试脚本
└── tools					// 工具集合
    ├── monitor.sh 			// 监控脚本
    ├── reload.sh 			// 热重启工具
    ├── restart.sh 			// 冷重启工具
    ├── start.sh 			// 启动工具
    ├── stop.sh 			// 停止工具
```

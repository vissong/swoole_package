<?php
/**
 * SwooleServer
 *
 * 作者: vissong
 * 创建时间: 2014-08-20
 * 修改记录:
 *
 * $Id: Daemon.php 2 2010-10-26 08:02:27Z zhouguoqiang $
 */

define('IN_SWOOLE', true);

class SwooleServer {

    // 服务类型
    protected static $_serverType;

    // 服务端口
    protected static $_serverPort;

    // server 设置
    protected static $_settings;

    // 所在路径
    private static $_path;

    // server name
    private static $_name;

    // swooler 类实例
    private static $_self;

    // 业务自定义配置
    public static $businessSetting;

    /**
     * 单例方法
     *
     * @param $path
     * @return SwooleServer
     */
    public static function getInstance($path) {

        if (!is_a(self::$_self, 'SwooleServer')) {
            self::$_self = new SwooleServer($path);
        }

        return self::$_self;
    }

    /**
     * __construct
     *
     * @param $path
     */
    private function __construct($path) {

        self::$_settings = array(
        );

        self::$_path = $path;

        // 加载配置
        $this->loadConf();
    }

    /**
     * 测试
     */
    public function test() {
        var_dump($this);
    }

    /**
     * 记载配置文件
     */
    public function loadConf() {

        $iniPath = self::$_path . '/conf/serv_conf.ini';
        $config = parse_ini_file($iniPath, true);

        // server log
        $config['SETTING']['log_file'] = self::$_path . '/log/swoole.log';

        self::$_settings = array_merge(self::$_settings, $config['SETTING']);
        self::$_serverType = $config['LISTENING']['type'];
        self::$_serverPort = $config['LISTENING']['port'];
        self::$_name = $config['INFO']['name'];
        self::$businessSetting = $config['BUSINESS'];
    }

    /**
     * 开始执行
     */
    public function run() {

        // 未配置 tcp port 抛异常
        if (!self::$_serverType && !self::$_serverPort) {
            throw new Exception('no port set to the swoole server', 1000);
        }

        // 指定了 tcp 端口，启用tcp server
        if (self::$_serverType == 'tcp') {
            $serverType = SWOOLE_SOCK_TCP;
        } else {
            $serverType = SWOOLE_SOCK_UDP;
        }

        // 初始化 server
        $server = new swoole_server("0.0.0.0", self::$_serverPort, SWOOLE_PROCESS, $serverType);

        // 加载配置
        $server->set(self::$_settings);

        // 配置各个阶段的回调
        // Connect/Close/Receive 必须设置
        $server->on('Start', array($this, 'onStart'));
        $server->on('Connect', array($this, 'onConnect'));
        $server->on('Receive', array($this, 'onReceive'));
        $server->on('Close', array($this, 'onClose'));
        $server->on('Shutdown', array($this, 'onShutdown'));
        $server->on('Timer', array($this, 'onTimer'));
        $server->on('WorkerStart', array($this, 'onWorkerStart'));
        $server->on('WorkerStop', array($this, 'onWorkerStop'));
        $server->on('Task', array($this, 'onTask'));
        $server->on('Finish', array($this, 'onFinish'));
        $server->on('WorkerError', array($this, 'onWorkerError'));
        $server->on('ManagerStart', array($this, 'onManagerStart'));

        // 启动server
        $server->start();
    }

    /**
     * server 启动回调
     * master 进程
     *
     * @param $server swoole_server 实例
     */
    public function onStart($server) {

        // 记录 master 与 manager 的进程id
        self::log('master_pid', $server->master_pid, false, true);
        self::log('manager_pid', $server->manager_pid, false, true);

        require self::_filePath('start');
    }

    /**
     * worker 连接回调
     * worker 进程
     *
     * @param $server  swoole_server 实例
     * @param $fd      连接的文件描述符
     * @param $fromId  来自哪个 poll 进程
     *
     */
    public function onConnect($server, $fd, $fromId) {

        require self::_filePath('connect');
    }

    /**
     * 接受数据回调
     * worker 进程
     *
     * @param $server  swoole_server 实例
     * @param $fd      连接的文件描述符
     * @param $fromId  来自哪个 poll 进程
     * @param $data    接收到的数据
     *
     */
    public function onReceive($server, $fd, $fromId, $data) {

        // 停止与重启
        if ($data == 'swoole:shutdown') {
            $server->send($fd, 'server will be shutdown');
            $server->shutdown();
        } elseif ($data == 'swoole:reload') {
            $server->send($fd, 'server worker will be restart');
            $server->reload();
        } elseif ($data == 'swoole:check') {
            $server->send($fd, 'ok');
        } else {
            require self::_filePath('receive');
        }
    }

    /**
     * 连接关闭
     * worker 进程
     *
     * @param $server  swoole_server 实例
     * @param $fd      连接的文件描述符
     * @param $fromId  来自哪个 poll 进程
     *
     */
    public function onClose($server, $fd, $fromId) {

        require self::_filePath('close');
    }

    /**
     * server 结束
     * master 进程
     *
     * @param $server  swoole_server 实例
     *
     */
    public function onShutdown($server) {

        require self::_filePath('shutdown');
    }

    /**
     * 定时器触发
     *
     * @param $server    swoole_server 实例
     * @param $interval  定时器时间间隔，根据$interval的值来区分是哪个定时器触发的
     *
     */
    public function onTimer($server, $interval) {

        require self::_filePath('timer');
    }

    /**
     * 在worker进程/task_worker启动时发生
     * worker 进程
     *
     * @param $server     swoole_server 实例
     * @param $workerId   一个从0-$worker_num之间的数字，表示这个worker进程的ID
     *
     */
    public function onWorkerStart($server, $workerId) {

        require self::_filePath('workerStart');
    }

    /**
     * 在worker进程/task_worker结束时发生
     * worker 进程
     *
     * @param $server    swoole_server 实例
     * @param $workerId  一个从0-$worker_num之间的数字，表示这个worker进程的ID
     *
     */
    public function onWorkerStop($server, $workerId) {

        require self::_filePath('workerStop');
    }

    /**
     * 在task_worker进程内被调用。worker进程可以使用swoole_server_task函数向task_worker进程投递新的任务。
     * task worker 进程
     *
     * @param $server   swoole_server 实例
     * @param $taskId   任务ID，由swoole扩展内自动生成，用于区分不同的任务，不动 worker 分配的 task id 会重复
     * @param $fromId   来自于哪个worker进程
     * @param $data     任务的内容
     *
     */
    public function onTask($server, $taskId, $fromId, $data) {

        require self::_filePath('task');
    }

    /**
     * 任务执行完毕后，会触发执行
     * worker 进程
     *
     * @param $server    swoole_server 实例
     * @param $taskId    任务ID
     * @param $data      任务处理的结果内容
     *
     */
    public function onFinish($server, $taskId, $data) {

        require self::_filePath('finish');
    }

    /**
     * 当worker/task_worker进程发生异常后会在Manager进程内回调此函数
     * worker 进程
     *
     * @param $server       swoole_server 实例
     * @param $workerId     异常进程的编号
     * @param $workerPId    异常进程的ID
     * @param $errCode      退出的状态码
     *
     */
    public function onWorkerError($server, $workerId, $workerPId, $errCode) {

        require self::_filePath('workerError');
    }

    /**
     * manager 进程启动时
     * manager 进程
     *
     * @param $server   swoole_server 实例
     *
     */
    public function onManagerStart($server) {

        require self::_filePath('managerStart');
    }

    /**
     * 获取callback文件路径
     *
     * @param $filename
     *
     * @return string
     */
    private static function _filePath($filename) {

        return self::$_path . '/callback/' . $filename . '.php';
    }

    /**
     * 记录 log
     *
     * @param $logName
     * @param $content
     * @param $date
     * @param bool $overwrite
     */
    private static function log($logName, $content, $date = true, $overwrite = false) {

        $logFile = self::$_path . '/log/' . $logName . '.log';
        $content .= "\n";

        if ($date) {
            $content = '[' . date('Y-m-d H:i:s') . '] ' . $content;
        }

        if ($overwrite) {
            file_put_contents($logFile, $content);
        } else {
            file_put_contents($logFile, $content, FILE_APPEND);
        }
    }
}



<?php

use App\Common\DbSelector;
use App\Process\MonitorProcess;
use Swoft\Crontab\Process\CrontabProcess;
use Swoft\Db\Pool;
use Swoft\Http\Server\HttpServer;
use Swoft\Log\Handler\FileHandler;
use Swoft\Task\Swoole\SyncTaskListener;
use Swoft\Task\Swoole\TaskListener;
use Swoft\Task\Swoole\FinishListener;
use Swoft\Rpc\Client\Client as ServiceClient;
use Swoft\Rpc\Client\Pool as ServicePool;
use Swoft\Rpc\Server\ServiceServer;
use Swoft\Http\Server\Swoole\RequestListener;
use Swoft\WebSocket\Server\WebSocketServer;
use Swoft\Server\SwooleEvent;
use Swoft\Db\Database;
use Swoft\Redis\RedisDb;

return [
    'lineFormatter'      => [
        // [traceid:%traceid%] [spanid:%spanid%] [parentid:%parentid%]
        'format'     => '%datetime% [%level_name%] [%channel%] [%event%] [tid:%tid%] [cid:%cid%] %messages%',
        'dateFormat' => 'Y-m-d H:i:s',
    ],
    'noticeHandler'      => [
        'class'     => FileHandler::class,
        'logFile'   => '@runtime/logs/notice_testSwoft-%d{Y-m-d}.log',
        'formatter' => \bean('lineFormatter'),
        'levels'    => 'notice,info,debug,trace',
    ],
    'applicationHandler' => [
        'class'     => FileHandler::class,
        'logFile'   => '@runtime/logs/error_testSwoft-%d{Y-m-d}.log',
        'formatter' => \bean('lineFormatter'),
        'levels'    => 'error,warning',
    ],
    'logger'            => [
        'flushRequest' => false,
        'enable'       => true,
        'json'         => false,
    ],
    'httpServer'        => [
        'class'    => HttpServer::class,
        'port'     => 18306,
        'listener' => [
            'rpc' => bean('rpcServer')
        ],
        'process'  => [
            //  'monitor' => bean(MonitorProcess::class),
            //   'crontab' => bean(CrontabProcess::class),
        ],
        'on'       => [
//            SwooleEvent::TASK   => bean(SyncTaskListener::class),  // Enable sync task
            SwooleEvent::TASK   => bean(TaskListener::class),  // Enable task must task and finish event
            SwooleEvent::FINISH => bean(FinishListener::class)
        ],
        /* @see HttpServer::$setting */
        'setting'  => [
            'task_worker_num'       => 12,
            'task_enable_coroutine' => true
        ]
    ],
    'httpDispatcher'    => [
        // Add global http middleware
        'middlewares'      => [
            \App\Http\Middleware\FavIconMiddleware::class,
            // \Swoft\Whoops\WhoopsMiddleware::class,
            // Allow use @View tag
            \Swoft\View\Middleware\ViewMiddleware::class,
            // \Swoft\Swoole\Tracker\Middleware\SwooleTrackerMiddleware::class,
        ],
        'afterMiddlewares' => [
            \Swoft\Http\Server\Middleware\ValidatorMiddleware::class
        ]
    ],
    'db'                => [
        'class'    => Database::class,
        /*        'dsn'      => 'mysql:dbname=test;host=139.9.203.84',
                'username' => 'root',
                'password' => 'yuefan_lipy_0806',*/
        'dsn'      => /*'mysql:dbname=test;host=192.168.5.53'*/ config('application.db_dsn','sdf'),
        'username' => 'root'/*config('application.db_username','2')*/,
        'password' => '123456'/*config('application.db_password','3')*/,
    ],
    'db2'               => [
        'class'      => Database::class,
        'dsn'        => 'mysql:dbname=test2;host=192.168.5.53' /*config('application.db2_dsn','sdf')*/,
        'username'   => 'root',
        'password'   => '123456',
        'dbSelector' => bean(DbSelector::class)
    ],
    'db2.pool'          => [
        'class'    => Pool::class,
        'database' => bean('db2')
    ],
    'db3'               => [
        'class'    => Database::class,
        'dsn'      =>  'mysql:dbname=test2;host=192.168.5.53' ,
        'username'   => 'root',
        'password'   => '123456',
    ],
    'db3.pool'          => [
        'class'    => Pool::class,
        'database' => bean('db3')
    ],
    'migrationManager'  => [
        'migrationPath' => '@app/Migration',
    ],
    'redis'             => [
        'class'    => RedisDb::class,
        'host'     => '192.168.5.53',
        'port'     => 6379,
        'database' => 0,
        'option'   => [
            'prefix' => 'swoft:'
        ]
    ],
    'user'              => [
        'class'   => ServiceClient::class,
        'host'    => '127.0.0.1',
        'port'    => 18307,
        'setting' => [
            'timeout'         => 0.5,
            'connect_timeout' => 1.0,
            'write_timeout'   => 10.0,
            'read_timeout'    => 0.5,
        ],
        'packet'  => bean('rpcClientPacket')
    ],
    'user.pool'         => [
        'class'  => ServicePool::class,
        'client' => bean('user')
    ],
    'rpcServer'         => [
        'class' => ServiceServer::class,
    ],
    /* 'wsServer'          => [
         'class'   => WebSocketServer::class,
         'port'    => 18308,
         'on'      => [
             // Enable http handle
             SwooleEvent::REQUEST => bean(RequestListener::class),
         ],
         'debug'   => 1,
         // 'debug'   => env('SWOFT_DEBUG', 0),
         /* @see WebSocketServer::$setting */
    /*  'setting' => [
          'log_file' => alias('@runtime/swoole.log'),
      ],
  ],*/
    /*  'tcpServer'         => [
          'port'  => 18309,
          'debug' => 1,
      ],
      /** @see \Swoft\Tcp\Protocol */
    /* 'tcpServerProtocol' => [
          'type'            => \Swoft\Tcp\Packer\JsonPacker::TYPE,
        // 'type' => \Swoft\Tcp\Packer\SimpleTokenPacker::TYPE,
         // 'openLengthCheck' => true,
     ],*/
    'cliRouter'         => [
        // 'disabledGroups' => ['demo', 'test'],
    ],
    /* 'processPool' => [
         'class' => \Swoft\Process\ProcessPool::class,
         'workerNum' => 3
     ],*/
    'apollo' => [
        'host'    => '47.113.26.229',
        'port' => 9080,
        'timeout' => 6,
        'appId' => 'testHttpSwoft',
    ],
    'consul' => [
        'host' => '192.168.5.53' /*config('application.consul_host','sdf')*/  /*'139.9.203.84'*/ /*config('application.consul_host','sdf')*/,
    ]
];

<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'console\controllers',
    'components' => [
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'controllerMap' => [
            'swoole' => [
                'class' => 'feehi\console\SwooleController',
                'tcp' => [
                    'host' => '0.0.0.0',
                    'port' => 8081,  // 指定TCP监听端口
                    'worker_num' => 4,
                    'open_tcp_keepalive' => 1,
                    'heartbeat_check_interval' => 60,
                    'heartbeat_idle_time' => 600
                ]
            ]
        ]
    ],
    'params' => $params,
];

<?php

$params = array_merge(
        require __DIR__ . '/../../common/config/params.php',
        require __DIR__ . '/../../common/config/key.php',
        require __DIR__ . '/params.php',
);

return [
    'id' => 'app-api',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'api\controllers',
    'defaultRoute' => 'index/index',
    'bootstrap' => ['log'],
    'components' => [
        'session' => [
            'name' => 'advanced-api',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'logFile' => '@api/runtime/logs/error/error.' . date('Ymd') . '.log',
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'index/error',
            'class' => 'api\extensions\ApiHttpException'
        ],


        'user' => [
            'identityClass' => 'backend\models\Manager',

            'enableAutoLogin' => false,


            'identityCookie'  => [
                'name'     => '_identity',
                'httpOnly' => true,
            ],
        ],
        /**----------------------路由配置--------------------**/

        'urlManager' => [

            'class'           => 'yii\web\UrlManager',

            'enablePrettyUrl' => true,  //这个是生成路由 ?r=site/about--->/site/about

            'showScriptName'  => true,

            'suffix'          => false,//静态

            'rules' => [



            ],




        ],
    ],
    'params' => $params,


];

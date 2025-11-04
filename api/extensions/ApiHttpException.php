<?php

namespace api\extensions;

use yii\web\ErrorHandler;

/**
 * Desc 异常类
 * @author WMX
 */
class ApiHttpException extends ErrorHandler {

    /**
     * 重写渲染异常页面
     * **/
    public function renderException($exception) {
        $params = [
            'code' => $exception->getCode() ? $exception->getCode(): 1,
            'message' => in_array($exception->getCode(), [1, 202, 301])?$exception->getMessage():'服务异常，请联系管理员',
            'data' => [],
        ];
        if(strrpos($exception->getMessage(), 'SQLSTATE') !== false){
            $params['message'] = '服务异常，请联系管理员';
        }
        if (YII_ENV == 'dev') {
            $params['message'] = $exception->getMessage();
            $params['data'] = [
                'file' => str_replace('.php', '', $exception->getFile()),
                'line' => $exception->getLine(),
            ];
        }
        $response = \Yii::$app->getResponse();
        $response->format = \yii\web\Response::FORMAT_JSON;
        $response->data = $params;
        $response->send();
        die();
    }

}

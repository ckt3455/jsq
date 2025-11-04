<?php

namespace api\extensions;
use yii\filters\ContentNegotiator;
use yii\rest\Controller;
use yii\base\DynamicModel;
use yii;
use yii\web\Response;

/**
 * Desc 所有请求的基类(必须继承)
 * @author HUI
 */
class ApiBaseController extends Controller {

    //合法参数
    public $params = [];


    public function beforeAction($action)
    {
        // 验证签名
//        if (!$this->validateSign()) {
//
//
//            throw new \yii\web\BadRequestHttpException('签名错误');
//        }

        return parent::beforeAction($action);
    }


    /**
     * 重写 behaviors
     */
    public function behaviors() {
        return [
            'contentNegotiator' => [
                'class' => ContentNegotiator::class,
                'formats' => [
                    'application/json' => Response::FORMAT_JSON,
                ],
            ],
        ];
    }


    private function validateSign()
    {
        $timestamp = Yii::$app->request->post('timestamp');
        $sign = Yii::$app->request->post('sign');

        // 验证时间戳有效性（防止重放攻击）
        if (abs(time() - $timestamp) > 120) { // 2分钟有效期
            return false;
        }


        // 计算并验证签名
        $data = Yii::$app->request->post();
        unset($data['sign']);
        ksort($data);

        $string = http_build_query($data) . '&key=' . Yii::$app->params['sign_key'];
        $calculatedSign = md5($string);

        return $sign === $calculatedSign;
    }

    /**
     * @desc   json出错返回

     * @param  string $msg
     * @return json
     */
    protected function jsonError($message = '请求异常', $data = []) {
        return ['code' => 1, 'message' => $message, 'data' => $data];
    }

    /**
     * @desc   json成功返回
     * @param  array $data
     * @return json
     */
    protected function jsonSuccess($data = [], $message = '请求成功') {
        return ['code' => 0, 'message' => $message, 'data' => $data];
    }

    /**
     * @desc 前端传参规则校验
     * @param  array $params 参数
     * @param  array $rules 规则
     * **/
   protected function validateParams($params, $rules) {

       foreach ($rules as $k=>$v){

           if(!isset($params[$v[0][0]])){
               return $v[0][0].'未定义';
           }
       }

        $model = DynamicModel::validateData($params, $rules);
        if($model->hasErrors()){
            $errors = [];
            foreach ($model->getErrors() as $value) {
                $errors[] = $value[0];
            }
            return implode(',', $errors);
        }


        return '';
    }

}

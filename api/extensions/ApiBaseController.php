<?php

namespace api\extensions;
use app\components\ParamValidator;
use backend\models\User;
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


    //拼接域名
    protected function setImg($img,$hostInfo=null){
        if(stripos($img,'http') !== 0 && !empty($img)) {
            $hostInfo = empty($hostInfo)?Yii::$app->request->hostInfo:$hostInfo;
            $img = $hostInfo . $img;
        }
        return $img;
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
     * 获取常用字段的验证规则
     * @param array $fields 需要验证的字段列表
     * @param array $customRules 自定义规则
     * @return array
     */
    public static function getRules($fields = [], $customRules = [])
    {
        $commonRules = [];

        // 定义常用字段的基础规则
        $baseRules = [
            'user_id' => [
                ['user_id'], 'required', 'message' => '用户ID不能为空',
            ],
            'username' => [
                ['username'], 'required', 'message' => '用户名不能为空'
            ],
            'name' => [
                ['name'], 'required', 'message' => '名称不能为空'
            ],
            'password' => [
                ['password'], 'required', 'message' => '密码不能为空'
            ],
            're_password' => [
                ['re_password'], 'required', 'message' => '确认密码不能为空'
            ],
            'email' => [
                ['email'], 'required', 'message' => '邮箱不能为空'
            ],
            'mobile' => [
                ['mobile'], 'required', 'message' => '手机号不能为空'
            ],
            'sms' => [
                ['sms'], 'required', 'message' => '短信验证码不能为空'
            ],

            'type' => [
                ['type'], 'required', 'message' => '类型不能为空'
            ],
            'admin_id' => [
                ['admin_id'], 'required', 'message' => '未登录'
            ],
        ];

        // 定义常用字段的扩展验证规则
        $extendedRules = [
            'user_id' => [
                [
                    ['user_id'], 'exist', 'targetClass' => User::class, 'targetAttribute' => 'id', 'message' => '用户ID不存在'],
                    [['user_id'], 'integer', 'min' => 1, 'message' => '用户ID必须为正整数'],
                ],

            're_password' => [
                [['password'], 'compare','compareAttribute' => 're_password', 'message' => '两次输入的密码不一致！']
            ],
            'email' => [
                [['email'], 'email', 'message' => '邮箱格式不正确']
            ],
            'mobile' => [
                [['mobile'], 'match', 'pattern' => '/^1[3-9]\d{9}$/', 'message' => '手机号格式不正确']
            ],
            'phone' => [
                [['phone'], 'match', 'pattern' => '/^1[3-9]\d{9}$/', 'message' => '手机号格式不正确']
            ],

        ];


        // 根据传入的字段构建规则
        foreach ($fields as $field) {
            if (isset($baseRules[$field])) {
                $commonRules[] = $baseRules[$field];
            }
            if (isset($extendedRules[$field])) {
                foreach ($extendedRules[$field] as $rule) {
                    $commonRules[] = $rule;
                }
            }
        }


        // 合并自定义规则
        return array_merge($commonRules, $customRules);
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


    //对比时间和当前时间的剩余天数
    protected   function end_days($time)
    {
        $number=$time-time();
        if($number<0){
            return 0;
        }else{
            return ceil($number/86400);
        }

    }

}

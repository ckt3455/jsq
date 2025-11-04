<?php
namespace common\models;

use Yii;
use yii\base\Model;

/**
 * Login form
 */
 class RegisterForm extends Model
{
    public $mobile_phone;
    public $password;
    public $re_password;
    public $verifyCode;
    public $code;
    public $recommend_id;
    public function rules()

    {

        return [

            // username and password are both required

            [['mobile_phone', 'password','re_password','code','recommend_id'], 'required'],
            ['verifyCode', 'captcha','captchaAction'=>'/html/captcha','message'=>'验证码不正确',],

        ];

    }

     public function attributeLabels()

     {

         return [
             'mobile_phone' => '手机号码',

             'password' => '密码',

             're_password' => '重复输入密码',

             'code' => '短信验证码',

             'recommend_id'=>'推荐id',


         ];

     }







}

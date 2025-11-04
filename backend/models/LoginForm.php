<?php
namespace backend\models;

use Yii;
use yii\base\Model;
/**
 * Login form
 */
class LoginForm extends \common\models\LoginForm
{
    public $verifyCode;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
            ['password', 'validateIp'],
            ['verifyCode', 'captcha'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'username'   => '登录帐号',
            'rememberMe' => '记住我',
            'password'   => '登录密码',
            'verifyCode' => '验证码',
        ];
    }

    /**
     * @param $attribute
     * @param $params
     * 验证ip地址是否正确
     */
    public function validateIp($attribute)
    {
        $ip = Yii::$app->request->userIP;
        $ipList =  Yii::$app->config->info('ADMIN_ALLOW_IP');
        if(!empty($ipList))
        {
            $value  = explode(",",$ipList);
            $result = in_array($ip,$value);

            if(!$result)
            {
                //ip不正确强行登陆
                Yii::$app->actionlog->addLog(400,"login","账号:".$this->username);

                $this->addError($attribute,'禁止登陆');
            }
        }
        else
        {
             return true;
        }
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = Manager::findByUsername($this->username);
        }

        return $this->_user;
    }
}

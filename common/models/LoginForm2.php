<?php
namespace common\models;

use common\components\Helper;
use Yii;
use yii\base\Model;

/**
 * Login form
 */
abstract class LoginForm2 extends Model
{
    public $username;
    public $code;
    public $rememberMe = true;

    protected $_user;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'code'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['code', 'validatePassword'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if(!$user){
                $this->addError($attribute, '账号错误');
            }
            $re=Helper::checkSMS($this->username,$this->code);
            if($re['error']==1){
                $this->addError($attribute, $re['message']);
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return boolean whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        } else {
            return false;
        }
    }
}

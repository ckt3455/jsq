<?php
namespace frontend\models;

use backend\models\Code;
use common\components\Helper;
use Yii;
use yii\base\Model;


/**
 * 修改密码
 */
class Passwd extends Model
{
    public $password;
    public $repassword;
    public $passwd_repetition;
    public $type;
    public $code;
    public $username;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['password', 'repassword', 'code', 'type', 'username'], 'required', 'message' => '不能为空'],
            [['password', 'repassword'], 'string', 'min' => 6, 'max' => 15, 'message' => '只能包含6-15个字符'],
            ['code', 'validateCode'],
            ['repassword', 'validateRepassword','skipOnError' => false],
            ['username', 'validateUsername']
        ];
    }


        public function attributeLabels()
    {
        return [
            'password'            => '密码',
            'repassword'        => '确认密码',

        ];
    }

    public function validateRepassword($attribute){
        if(!$this->repassword){
            $this->addError($attribute, "请再次输入密码.");
        }
        if($this->password!==$this->repassword){
            $this->addError($attribute, "2次输入的密码不一致.");
        }
    }

    /**
     * 验证用户
     */
    public function validateUsername($attribute)
    {
        if (!$this->hasErrors())
        {
            if($this->type==1){
                $user=\backend\models\ProvinceUser::find()->where(['mobile_phone'=>$this->username])->one();
                if(!$user){
                    $this->addError($attribute, '用户不存在');
                }

            }

            if($this->type==2){
                $user=\backend\models\ProvinceUser::find()->where(['email'=>$this->username])->one();
                if(!$user){
                    $this->addError($attribute, '用户不存在');
                }

            }

        }
    }

    /**
     * 验证验证码
     */
    public function validateCode($attribute)
    {
        if (!$this->hasErrors())
        {
            if($this->type==1){
                $re=Helper::checkSMS($this->username,$this->code);
                if($re['error']!==0){
                    $this->addError($attribute, $re['message']);
                }
            }

            if($this->type==2){
                $re=Helper::checkEmail($this->username,$this->code);
                if($re['error']!==0){
                    $this->addError($attribute, $re['message']);
                }
            }

        }
    }


}

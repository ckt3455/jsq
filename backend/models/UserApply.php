<?php

namespace backend\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%user_apply}}".
 *
 * @property string $id
 * @property string $user_id
 * @property string $money
 * @property string $fee
 * @property string $type
 * @property string $bank_name
 * @property string $bank_number
 * @property string $bank
 * @property string $zfb_name
 * @property string $zfb_number
 * @property string $status
 * @property string $created_at
 * @property string $updated_at
 */
class UserApply extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_apply}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'type', 'status', 'created_at', 'updated_at'], 'integer'],
            [['money', 'fee'], 'number'],
            [['bank_name', 'bank_number', 'bank', 'zfb_name', 'zfb_number'], 'string', 'max' => 255],
        ];
    }

    public static $status_message=[
        1=>'待审核',
        2=>'审核通过',
        3=>'审核不通过',
    ];


    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => '用户',
            'money' => '金额',
            'fee' => '手续费',
            'type' => '提现方式',
            'bank_name' => '银行卡账号',
            'bank_number' => '银行卡账户名',
            'bank' => '开户银行',
            'zfb_name' => '支付宝账号',
            'zfb_number' => '支付宝账户名',
            'status' => '状态',
            'created_at' => '添加时间',
            'updated_at' => 'Updated At',
        ];
    }


    public function getUser(){
        return $this->hasOne(User::className(),['id'=>'user_id']);
    }
}

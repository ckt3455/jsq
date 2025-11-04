<?php

namespace backend\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%int_log}}".
 *
 * @property string $id
 * @property string $user_id
 * @property string $number
 * @property string $order_id
 * @property string $created_at
 * @property string $order_number
 * @property string $updated_at
 */
class IntLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%int_log}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'order_id', 'created_at', 'updated_at'], 'integer'],
            [['number'], 'number'],
            [['order_number'], 'string', 'max' => 100],
        ];
    }


    public static $type_message=[
        1=>'积分兑换',
        2=>'手续费换算为积分'
    ];

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
            'number' => '积分',
            'order_id' => 'Order ID',
            'created_at' => '添加时间',
            'content'=>'内容',
            'status'=>'状态',
            'type'=>'类型',
            'order_number' => 'Order Number',
            'updated_at' => 'Updated At',
        ];
    }


    public function getUser(){
        return $this->hasOne(User::className(),['id'=>'user_id']);
    }
}

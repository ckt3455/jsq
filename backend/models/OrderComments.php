<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%order_comments}}".
 *
 * @property string $id
 * @property string $user_id
 * @property string $order_number
 * @property string $number1
 * @property string $number2
 * @property string $number3
 * @property string $order_time
 * @property string $created_at
 */
class OrderComments extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order_comments}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'number1', 'number2', 'number3', 'order_time', 'created_at'], 'integer'],
            [['order_number'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'order_number' => 'Order Number',
            'number1' => 'Number1',
            'number2' => 'Number2',
            'number3' => 'Number3',
            'order_time' => 'Order Time',
            'created_at' => 'Created At',
        ];
    }


    public function getDetail(){
        return $this->hasMany(GoodsComments::className(),['order_number'=>'order_number']);
    }

    public function getOrder(){
        return $this->hasOne(Order::className(),['order_number'=>'order_number']);
    }
}

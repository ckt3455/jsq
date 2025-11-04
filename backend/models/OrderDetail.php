<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%order_detail}}".
 *
 * @property string $id
 * @property string $goods_id
 * @property string $goods_title
 * @property string $goods_image
 * @property string $number
 * @property string $money
 * @property string $price
 * @property string $order_id
 */
class OrderDetail extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order_detail}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['goods_id', 'number', 'order_id','number2'], 'integer'],
            [['money', 'price'], 'number'],
            [['goods_title'], 'string', 'max' => 100],
            [['goods_image'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'goods_id' => 'Goods ID',
            'goods_title' => 'Goods Title',
            'goods_image' => 'Goods Image',
            'number' => 'Number',
            'money' => 'Money',
            'price' => 'Price',
            'order_id' => 'Order ID',
        ];
    }
}

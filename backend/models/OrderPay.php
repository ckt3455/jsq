<?php

namespace backend\models;

use Yii;
use yii\helpers\Url;

/**
 * This is the model class for table "{{%news}}".
 *
 * @property integer $id
 * @property string $order_number
 * @property string $out_trade_no
 * @property string $total_amount
 */
class OrderPay extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order_pays}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_number', 'out_trade_no'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_number'=>'订单编号',
            'out_trade_no'=>'支付单号',
            'total_amount'=>'支付金额',
            'type'=>'支付方式',
            'user_id'=>'用户'
        ];
    }




}

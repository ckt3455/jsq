<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%order_refund}}".
 *
 * @property string $id
 * @property string $user_id
 * @property string $order_number
 * @property string $contact
 * @property string $mobile
 * @property string $type
 * @property string $message
 * @property string $content
 * @property string $created_at
 * @property string $detail_id
 */
class OrderRefund extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order_refund}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'type', 'created_at','status'], 'integer'],
            [['order_number', 'message', 'content', 'detail_id'], 'string', 'max' => 255],
            [['contact', 'mobile'], 'string', 'max' => 50],
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
            'order_number' => '订单号',
            'contact' => '联系人',
            'mobile' => '电话',
            'type' => '类型',
            'message' => '内容',
            'content' => '备注',
            'created_at' => '添加时间',
            'detail_id' => '产品',
            'image'=>'图片',
            'status'=>'状态',
            'money'=>'金额'
        ];
    }

    public static $status_message=[
        1=>'待审核',
        2=>'售后中',
        3=>'已完成',
        -1=>'已取消',
        -2=>'已驳回',
        -3=>'已作废'
    ];

    public static $type_message=[
        1=>'退款退货',
        2=>'退款',
    ];


    public function getOrder(){
        return $this->hasOne(Order::className(),['order_number'=>'order_number']);
    }

    public function getDetail(){
      return $this->hasMany(OrderDetail::className(),['refund_id'=>'id']);
    }

    public function getUser(){
        return $this->hasOne(ProvinceUser::className(),['id'=>'user_id']);
    }
}

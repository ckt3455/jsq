<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%order_invoice}}".
 *
 * @property integer $id
 * @property string $user_id
 * @property string $order_id
 * @property string $email
 * @property integer $type
 * @property string $order_number
 * @property string $title
 * @property string $number
 * @property string $company_name
 * @property string $phone
 * @property string $bank
 * @property string $bank_account
 * @property string $address
 * @property string $created_at
 */
class OrderInvoice extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order_invoice}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'order_id', 'type', 'created_at'], 'integer'],
            [['email', 'order_number', 'title', 'number', 'company_name', 'bank', 'bank_account', 'address','address2','contact'], 'string', 'max' => 255],
            [['phone'], 'string', 'max' => 50],
        ];
    }


    public static $status_message=[
        1=>'待开票',
        2=>'已开票',
        3=>'已作废'
    ];

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'order_id' => 'Order ID',
            'email' => '邮箱',
            'type' => '类型',
            'order_number' => 'Order Number',
            'title' => '发票抬头',
            'number' => '识别号',
            'company_name' => '单位名称',
            'phone' => '电话',
            'bank' => '开户银行',
            'contact'=>'联系人',
            'bank_account' => '银行账户',
            'address' => '注册地址',
            'address2' => '收票地址',
            'created_at' => 'Created At',
        ];
    }

    public function getDetail(){
        return $this->hasMany(OrderDetail::className(),['order_id'=>'order_id']);
    }


    public function getOrder(){
        return $this->hasOne(Order::className(),['id'=>'order_id']);
    }
}

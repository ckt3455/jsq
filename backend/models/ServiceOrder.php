<?php

namespace backend\models;

use common\components\CommonFunction;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%service_order}}".
 *
 * @property integer $id
 * @property integer $type
 * @property integer $user_id
 * @property integer $goods_id
 * @property string $goods_code
 * @property string $goods_image
 * @property string $goods_name
 * @property string $order_number
 * @property string $time
 * @property integer $date
 * @property integer $status
 * @property string $title
 * @property string $contact
 * @property string $phone
 * @property string $province
 * @property string $city
 * @property string $area
 * @property string $address
 * @property integer $created_at
 * @property integer $updated_at
 */
class ServiceOrder extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%service_order}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'user_id', 'goods_id', 'date', 'status', 'created_at', 'updated_at'], 'integer'],
            [['goods_code', 'goods_image', 'goods_name', 'order_number', 'title', 'address'], 'string', 'max' => 255],
            [['time', 'contact'], 'string', 'max' => 100],
            [['phone', 'province', 'city', 'area'], 'string', 'max' => 50],
        ];
    }

    public static $status_message=[
        -1=>'已取消',
        1=>'待处理',
        2=>'已接单',
        3=>'已完成',
    ];

    public static $type_message=[
        1=>'安装工单',
        2=>'维修工单',
        3=>'换芯工单',
    ];

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Type',
            'user_id' => 'User ID',
            'goods_id' => 'Goods ID',
            'goods_code' => 'Goods Code',
            'goods_image' => 'Goods Image',
            'goods_name' => 'Goods Name',
            'order_number' => 'Order Number',
            'time' => 'Time',
            'date' => 'Date',
            'status' => 'Status',
            'title' => 'Title',
            'contact' => 'Contact',
            'phone' => 'Phone',
            'province' => 'Province',
            'city' => 'City',
            'area' => 'Area',
            'address' => 'Address',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

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

    public function beforeSave($insert)

    {
        if($this->isNewRecord){
            $this->order_number=date('YmdHis') . $this->user_id . mt_rand(1000, 9999);
        }
        if($this->image){
            $this->image=CommonFunction::unsetImg($this->image);
        }
        return parent::beforeSave($insert);

    }



    public function getUser()
    {
        return $this->hasOne(User::class,['id'=>'user_id']);

    }
}

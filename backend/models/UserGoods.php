<?php

namespace backend\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%user_goods}}".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $goods_code
 * @property string $goods_name
 * @property string $goods_image
 * @property integer $start_time
 * @property integer $end_time
 * @property integer $is_index
 * @property integer $lx_day
 * @property integer $lx_end_time
 * @property integer $lx_alert
 * @property integer $created_at
 */
class UserGoods extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_goods}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'start_time', 'end_time', 'is_index', 'lx_day', 'lx_end_time', 'lx_alert', 'created_at'], 'integer'],
            [['goods_code', 'goods_name', 'goods_image'], 'string', 'max' => 255],
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
            'goods_code' => '产品编号',
            'goods_name' => '产品名称',
            'goods_image' => '产品图片',
            'start_time' => '激活时间',
            'end_time' => '保险结束时间',
            'is_index' => '首页显示',
            'lx_day' => '滤芯天数',
            'lx_end_time' => '滤芯到期时间',
            'lx_alert' => '滤芯提醒',
            'created_at' => '添加时间',
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
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],
                ],
            ],
        ];
    }


    public function getUser()
    {
        return $this->hasOne(User::class,['id'=>'user_id']);

    }


    //获取保险剩余天数
    public function getEnd_days()
    {
        $number=$this->end_time-time();
        if($number<0){
            return 0;
        }else{
            return ceil($number/86400);
        }

    }

    //获取滤芯剩余天数
    public function getLx_end_days()
    {
        $number=$this->end_time-time();
        if($number<0){
            return 0;
        }else{
            return ceil($number/86400);
        }

    }
    //获取滤芯状态
    public function getLx_status()
    {
        $lx_end_days=$this->lx_end_days;
        if ($lx_end_days > 20) {
            $lx_status = 1;
        } elseif ($lx_end_days > 0) {
            $lx_status = 2;
        } else {
            $lx_status = 3;
        }
        return $lx_status;

    }
}

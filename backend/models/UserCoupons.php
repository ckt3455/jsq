<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%user_coupons}}".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $coupons_id
 * @property integer $status
 * @property integer $start_time
 * @property integer $end_time
 * @property string $money
 * @property integer $created_at
 * @property string $relation
 * @property string $need_money
 */
class UserCoupons extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_coupons}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'need_money'], 'required'],
            [['user_id', 'coupons_id', 'status', 'start_time', 'end_time', 'created_at'], 'integer'],
            [['money', 'need_money'], 'number'],
            [['relation'], 'string', 'max' => 255],
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
            'coupons_id' => '优惠劵',
            'status' => '状态',
            'start_time' => '开始时间',
            'end_time' => '结束时间',
            'money' => '优惠姐',
            'created_at' => '领取时间',
            'relation' => '关联',
            'need_money' => '需满足金额',
        ];
    }
}

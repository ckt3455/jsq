<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%user_money_log}}".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $admin_id
 * @property integer $status
 * @property string $money
 * @property integer $created_at
 */
class UserMoneyLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_money_log}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'status', 'created_at','admin_id'], 'integer'],
            [['money'], 'number'],
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
            'status' => '状态',
            'money' => '金额',
            'created_at' => '添加时间',
            'admin_id'=>'操作人员'
        ];
    }

    public function getUser(){
        return $this->hasOne(ProvinceUser::className(),['id'=>'user_id']);
    }
    public function getAdmin(){
        return $this->hasOne(Manager::className(),['id'=>'admin_id']);
    }
}

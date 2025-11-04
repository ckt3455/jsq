<?php

namespace backend\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%user_history}}".
 *
 * @property string $id
 * @property string $user_id
 * @property string $type
 * @property string $number
 * @property string $status
 * @property string $content
 * @property string $created_at
 * @property string $updated_at
 */
class UserHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_history}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'type', 'status', 'created_at', 'updated_at'], 'integer'],
            [['number'], 'number'],
            [['content'], 'string', 'max' => 255],
        ];
    }


    public static $type_message=[
        1=>'直推奖',
        2=>'间推将',
        3=>'团队奖',
        4=>'管理奖',
        5=>'代理分红',
        6=>'全区分红',
        7=>'提现',
        8=>'提现驳回',
        9=>'后台调整',
        10=>'见单奖',
        11=>'平级奖',
        12=>'发放团队奖'

    ];

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => '用户',
            'type' => '类型',
            'number' => '金额',
            'status' => '状态',
            'content' => '内容',
            'created_at' => '添加时间',
            'updated_at' => 'Updated At',
        ];
    }


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


    public function getUser(){
        return $this->hasOne(User::className(),['id'=>'user_id']);
    }
}

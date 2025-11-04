<?php

namespace backend\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%money_log}}".
 *
 * @property string $id
 * @property string $user_id
 * @property string $content
 * @property string $type
 * @property string $created_at
 * @property string $updated_at
 * @property string $number
 */
class MoneyLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%money_log}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'type', 'created_at', 'updated_at'], 'integer'],
            [['number'], 'number'],
            [['content'], 'string', 'max' => 255],
        ];
    }

    public static $type_message=[
        1=>'管理奖',
        2=>'复购奖',
        3=>'分红',
        4=>'全盘分红',
        5=>'推人奖励'
    ];

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

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => '用户',
            'content' => '内容',
            'type' => '类型',
            'created_at' => '添加时间',
            'updated_at' => 'Updated At',
            'number' => '金额',
        ];
    }
}

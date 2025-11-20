<?php

namespace backend\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%user_message}}".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $title
 * @property string $content
 * @property integer $type
 * @property integer $relation_id
 * @property integer $created_at
 * @property integer $updated_at
 */
class UserMessage extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_message}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'type', 'relation_id', 'created_at', 'updated_at'], 'integer'],
            [['title', 'content'], 'string', 'max' => 255],
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
            'title' => '标题',
            'content' => '内容',
            'type' => '类型',
            'relation_id' => 'Relation ID',
            'created_at' => '添加时间',
            'updated_at' => '修改时间',
        ];
    }


    public static $type_message=[
        1=>'服务',
        2=>'系统'
    ];

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
}

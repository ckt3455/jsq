<?php

namespace backend\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%user_message}}".
 *
 * @property integer $id
 * @property string $title
 * @property string $content
 * @property string $url
 * @property integer $user_id
 * @property integer $created_at
 * @property integer $updated_at
 */
class UserMessage extends \yii\db\ActiveRecord
{

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

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
            [['user_id', 'created_at', 'updated_at'], 'integer'],
            [['title', 'content', 'url'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => '标题',
            'content' => '内容',
            'url' => '链接',
            'user_id' => '用户',
            'created_at' => '添加时间',
            'updated_at' => '更新时间',
        ];
    }
    public static function countMessage($user_id){
        $count=UserMessage::find()->where(['user_id'=>$user_id,'is_read'=>0])->count();
        return $count;
    }
}

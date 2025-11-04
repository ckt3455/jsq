<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%user_read}}".
 *
 * @property string $id
 * @property string $user_id
 * @property string $message_id
 */
class UserRead extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_read}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'message_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'message_id' => 'Message ID',
        ];
    }
}

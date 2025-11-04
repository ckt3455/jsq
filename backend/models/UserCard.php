<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%user_card}}".
 *
 * @property string $id
 * @property string $user_id
 * @property string $bank_name
 * @property string $bank_number
 * @property string $bank
 * @property string $zfb_name
 * @property string $zfb_number
 */
class UserCard extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_card}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'integer'],
            [['bank_name', 'bank_number', 'bank', 'zfb_name', 'zfb_number'], 'string', 'max' => 255],
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
            'bank_name' => 'Bank Name',
            'bank_number' => 'Bank Number',
            'bank' => 'Bank',
            'zfb_name' => 'Zfb Name',
            'zfb_number' => 'Zfb Number',
        ];
    }
}

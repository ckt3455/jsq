<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%contact}}".
 *
 * @property string $id
 * @property string $name
 * @property string $email
 * @property string $country
 * @property string $message
 * @property string $content
 * @property string $created_at
 */
class Contact extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%contact}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'content'], 'required'],
            [['created_at'], 'integer'],
            [['name', 'content'], 'string', 'max' => 255],
            [['email', 'country', 'message'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '姓名',
            'email' => '邮箱',
            'country' => '国家',
            'message' => '产品分类',
            'content' => '内容',
            'created_at' => '时间',
            'company'=>'公司'
        ];
    }
}

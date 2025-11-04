<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%message}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $email
 * @property string $content
 */
class Message extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%message}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'email'], 'string', 'max' => 100],
            [['content'], 'string', 'max' => 1000],
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
            'content' => '内容',
            'mobile' => '手机号',
            'whatsapp' => 'whatsapp',
            'company' => '公司',
            'created_at'=>'添加时间'
        ];
    }
}

<?php

namespace backend\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%goods_message}}".
 *
 * @property string $id
 * @property string $mobile
 * @property string $email
 * @property string $goods_url
 * @property string $created_at
 * @property string $updated_at
 */
class GoodsMessage extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%goods_message}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at'], 'integer'],
            [['mobile'], 'string', 'max' => 50],
            [['email'], 'string', 'max' => 100],
            [['goods_url'], 'string', 'max' => 255],
        ];
    }



    public function behaviors()
    {
        return [
            TimestampBehavior::className(),

        ];
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'mobile' => '电话',
            'email' => '邮箱',
            'goods_url' => '产品链接',
            'created_at' => '添加时间',
        ];
    }
}

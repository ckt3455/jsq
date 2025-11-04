<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%organization}}".
 *
 * @property string $id
 * @property string $name
 * @property string $parent_id
 */
class Organization extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%organization}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent_id'], 'integer'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '名称',
            'parent_id' => '上级',
        ];
    }
}

<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%position_apply}}".
 *
 * @property string $id
 * @property string $position_id
 * @property string $mechanism
 * @property string $name
 * @property string $mobile
 * @property string $education
 * @property string $age
 * @property string $file_value
 * @property string $created_at
 * @property string $language
 */
class PositionApply extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%position_applys}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['position_id', 'created_at'], 'integer'],
            [['mechanism', 'education'], 'string', 'max' => 100],
            [['name', 'mobile'], 'string', 'max' => 50],
            [['age'], 'string', 'max' => 20],
            [['file_value','position'], 'string', 'max' => 255],
            [['language'], 'string', 'max' => 10],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'position_id' => 'Position ID',
            'mechanism' => '应聘公司',
            'position'=>'应聘职位',
            'name' => '姓名',
            'mobile' => '电话',
            'education' => '学历',
            'age' => '年龄',
            'file_value' => '附件',
            'created_at' => '添加时间',
            'language' => 'Language',
        ];
    }
}

<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%department}}".
 *
 * @property string $id
 * @property string $name
 * @property string $parentid
 * @property integer $order
 * @property string $department_leader
 */
class Department extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%department}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parentid', 'order'], 'integer'],
            [['name', 'department_leader'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'parentid' => 'Parentid',
            'order' => 'Order',
            'department_leader' => 'Department Leader',
        ];
    }
}

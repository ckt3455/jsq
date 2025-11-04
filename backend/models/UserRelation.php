<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%user_relation}}".
 *
 * @property string $id
 * @property string $user_id
 * @property string $level
 * @property string $relation
 * @property string $parent_id
 */
class UserRelation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_relation}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'level'], 'integer'],
            [['parent_id','safe'],'safe']
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
            'level' => 'Level',
            'relation' => 'Relation',
            'parent_id' => 'Parent ID',
        ];
    }
}

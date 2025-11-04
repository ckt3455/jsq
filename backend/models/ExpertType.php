<?php

namespace backend\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%expert_type}}".
 *
 * @property string $id
 * @property string $name
 * @property integer $status
 * @property string $sort
 * @property integer $append
 * @property integer $updated
 */
class ExpertType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%expert_type}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['status', 'sort'], 'integer'],
            [['name'], 'string', 'max' => 100],
            [['status'], 'default', 'value' => 1],
            [['sort'], 'default', 'value' => 0],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '分类名称',
            'status' => '状态',
            'sort' => '排序',
            'append' => 'Append',
            'updated' => 'Updated',
        ];
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['append', 'updated'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated'],
                ],
            ],
        ];
    }

    public static function getName($id)
    {
        $one = ExpertType::find()->where(['id'=>$id])->select('name')->one();
        if($one)
        {
            return $one['name'];
        }else
        {
            return '未知分类';
        }
    }
}

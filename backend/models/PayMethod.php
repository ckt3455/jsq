<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%pay_method}}".
 *
 * @property integer $id
 * @property string $title
 * @property integer $sort
 * @property string $content
 */
class PayMethod extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%pay_method}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['sort'], 'integer'],
            [['content'], 'string'],
            [['title'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => '名称',
            'sort' => '排序',
            'content' => '描述',
        ];
    }

    public static function getList(){
        $model=PayMethod::find()->orderBy('sort')->asArray()->all();
        return ArrayHelper::map($model,'id','title');
    }
}

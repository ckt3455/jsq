<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%news_category}}".
 *
 * @property string $id
 * @property string $title
 * @property integer $sort
 */
class NewsCategory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%news_category}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sort'], 'integer'],
            [['title'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => '标题',
            'sort' => '排序',
        ];
    }

    public static function getList(){
        $model=NewsCategory::find()->orderBy('sort asc,id desc')->asArray()->all();

        return ArrayHelper::map($model,'id','title');
    }
}

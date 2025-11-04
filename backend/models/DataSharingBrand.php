<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%data_sharing_brand}}".
 *
 * @property integer $id
 * @property string $title
 * @property integer $sort
 */
class DataSharingBrand extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%data_sharing_brand}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['sort'], 'integer'],
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
            'title' => '品牌标签',
            'sort' => '排序',
        ];
    }

    public static function getList()
    {
        $model = DataSharingBrand::find()->orderBy('sort asc')->asArray()->all();
        return ArrayHelper::map($model,'title','title');
    }
}

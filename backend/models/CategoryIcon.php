<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%category_icon}}".
 *
 * @property string $id
 * @property string $category_id
 * @property string $title
 * @property string $image
 * @property integer $sort
 */
class CategoryIcon extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%category_icon}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category_id', 'sort'], 'integer'],
            [['title'], 'string', 'max' => 100],
            [['image'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'category_id' => 'Category ID',
            'title' => '标题',
            'image' => '图片',
            'sort' => '排序',
        ];
    }
}

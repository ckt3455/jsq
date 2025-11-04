<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%seo_detail}}".
 *
 * @property integer $id
 * @property string $title
 * @property string $keywords
 * @property string $description
 * @property string $type
 * @property integer $relation_id
 */
class SeoDetail extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%seo_detail}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['description'], 'string'],
            [['type'], 'required'],
            [['relation_id'], 'integer'],
            [['title', 'keywords', 'type'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title(标题)',
            'keywords' => 'Keywords(关键词)',
            'description' => 'Description(描述)',
            'type' => 'Type',
            'relation_id' => 'Relation ID',
        ];
    }
}

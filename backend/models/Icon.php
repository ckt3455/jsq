<?php

namespace backend\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%icon}}".
 *
 * @property integer $id
 * @property string $image
 * @property integer $sort
 * @property integer $type
 * @property string $href
 * @property integer $category
 * @property string $appid
 */
class Icon extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%icon}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sort', 'type', 'category'], 'integer'],
            [['image', 'href', 'appid'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'image' => '图片',
            'sort' => '排序',
            'type' => '类型',
            'href' => '链接',
            'category' => '分类',
            'appid' => 'Appid',
        ];
    }


    public static function getList($where,$limit=0){
        if($limit>0){
            $model=Icon::find()->where($where)->limit($limit)->orderBy('sort asc,id desc')->all();
        }else{
            $model=Icon::find()->where($where)->orderBy('sort asc,id desc')->all();
        }

        return $model;
    }


    public static function getOne($where){
        $model=Icon::find()->where($where)->orderBy('sort asc,id desc')->limit(1)->one();
        return $model;
    }
}

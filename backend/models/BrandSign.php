<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%brand_sign}}".
 *
 * @property integer $id
 * @property integer $brand_id
 * @property integer $type
 * @property integer $sort
 * @property string $image
 * @property string $href
 * @property string $content
 */
class BrandSign extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%brand_sign}}';
    }
    public static $type=[
        1=>'图片',
        2=>'普通',
        3=>'链接',
        4=>'弹窗'
    ];

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['brand_id','title'], 'required'],
            [['brand_id', 'type', 'sort'], 'integer'],
            [['content'], 'string'],
            [['image', 'href','title'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title'=>'标题',
            'brand_id' => '品牌',
            'type' => '类型',
            'sort' => '排序',
            'image' => '图片',
            'href' => '链接',
            'content' => '内容',
        ];
    }


    /**
     * 前台标签显示
     */
    public static function modelSign($id){

    }
}

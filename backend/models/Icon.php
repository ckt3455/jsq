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


    public static $type_message=[
        1=>'首页banner',
        2=>'首页广告图',
        3=>'首页图标',
        4=>'服务页banner',
        5=>'服务页图标',
        6=>'个人中心图标',
        7=>'个人中心广告',
        8=>'安装工单图标',
        9=>'维修工单图标',
        10=>'换芯工单图标',
    ];
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

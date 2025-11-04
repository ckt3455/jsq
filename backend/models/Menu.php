<?php

namespace backend\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
/**
 * This is the model class for table "{{%menu}}".
 *
 * @property integer $menu_id
 * @property string $title
 * @property integer $pid
 * @property string $url
 * @property string $main_css
 * @property integer $sort
 * @property integer $status
 * @property string $group
 * @property integer $append
 * @property integer $updated
 */
class Menu extends ActiveRecord
{
    const STATUS_ON     = 1;       //显示
    const STATUS_OFF    = -1;      //隐藏

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%menu}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['title','trim'],
            ['title','required'],

            ['status','required'],

            ['url','trim'],
            ['url', 'default', 'value' => "#"],

            ['sort','trim'],
            ['sort', 'number'],

            [['menu_css','append', 'updated'], 'trim'],
            [['pid','sort'], 'default', 'value' => 0],
            [['level'], 'default', 'value' => 1],
            ['parameter','string']

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'menu_id'  => 'Menu ID',
            'title'    => '标题',
            'pid'      => '上级id',
            'url'      => '路由',
            'menu_css' => '样式图标',
            'sort'     => '排序',
            'status'   => '状态',
            'append'   => '创建时间',
            'updated'  => '修改时间',
            'parameter'=>'参数'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenuChild()
    {
        return $this->hasOne(MenuChild::className(), ['menu_id' => 'menu_id']);
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
    public static function getParameter($id){
        $model=Menu::findOne($id);
        if(isset($model['parameter'])){
            $array=explode(',',$model['parameter']);
            if(is_array($array)){
                $return='?';
                foreach ($array as $k=>$v){
                    $return.=$v.'&&';

                }
                return $return;
            }
        }
        return false;
    }
}

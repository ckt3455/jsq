<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%brand_user_show}}".
 *
 * @property integer $id
 * @property integer $user_level
 * @property integer $is_show
 */
class BrandUserShow extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%brand_user_show}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_level'], 'required'],
            [['user_level', 'is_show','brand_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_level' => '会员级别',
            'is_show' => '是否显示价格',
            'brand_id'=>'品牌'
        ];
    }

    public static function is_show($user_level,$brand_id){
        $model=BrandUserShow::find()->where(['user_level'=>$user_level,'brand_id'=>$brand_id])->one();
        if($model){
            return $model->is_show;
        }
        else{
            return 1;
        }
    }
}

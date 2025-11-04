<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%favorites}}".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $brand_id
 * @property integer $goods_id
 * @property integer $append
 * @property integer $updated
 */
class Favorites extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%favorites}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'brand_code', 'goods_id'], 'required'],
            [['user_id', 'goods_id', 'append', 'updated'], 'integer'],
            [['brand_code'],'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => '用户',
            'brand_code' => '品牌',
            'goods_id' => '产品',
            'append' => '添加时间',
            'updated' => '更新时间',
        ];
    }

    /**
     * @param bool $insert
     * @return bool
     * 自动插入
     */
    public function beforeSave($insert)
    {
        if($this->isNewRecord)
        {
            $this->append = time();
        }
        else
        {
            $this->updated = time();
        }

        return parent::beforeSave($insert);
    }

    /**
     * 关联产品
     */

    public function getGoods(){
        return $this->hasOne(Goods::className(), ['id' => 'goods_id']);
    }

    /**
     * 关联品牌
     */

    public function getBrand(){
        return $this->hasOne(Brand::className(), ['brand_code' => 'brand_code']);
    }

    /**
     * 计算数量
     */

    public static function countNumber($brand_code=""){
        return Favorites::find()->where(['user_id'=>Yii::$app->user->id])->andFilterWhere(['brand_code'=>$brand_code])->count();
    }



    //是否收藏
    public static function isCollect($goods_id,$user_id){
        $model=Favorites::find()->where(['user_id'=>$user_id])->andFilterWhere(['goods_id'=>$goods_id])->one();
        if($model){
            return true;
        }else{
            return false;
        }

    }
}

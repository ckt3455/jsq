<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%ig_order}}".
 *
 * @property integer $id
 * @property integer $ig_goods_id
 * @property integer $sku_id
 * @property integer $number
 * @property integer $user_id
 * @property integer $append
 * @property integer $updated
 */
class IgOrder extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%ig_order}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ig_goods_id', 'user_id'], 'required'],
            [['ig_goods_id', 'sku_id', 'number', 'user_id', 'append', 'updated'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ig_goods_id' => '商品',
            'sku_id' => 'Sku',
            'number' => '数量',
            'user_id' => '用户',
            'append' => '添加时间',
            'updated' => '更新时间',
        ];
    }

    public function beforeSave($insert)
    {
        if ($this->isNewRecord) {
            $this->append = time();
        } else {
            $this->updated = time();
        }
        return parent::beforeSave($insert);
    }

    public function getSku(){
        return $this->hasOne(IgSku::className(), ['id' => 'sku_id']);
    }
    public function getGoods(){
        return $this->hasOne(IgGoods::className(), ['id' => 'ig_goods_id']);
    }

    public function getUser(){
        return $this->hasOne(ProvinceUser::className(), ['id' => 'user_id']);
    }

}

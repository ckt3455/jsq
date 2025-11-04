<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%sku_attribute}}".
 *
 * @property integer $id
 * @property integer $sku_id
 * @property string $title
 * @property string $value
 */
class SkuAttribute extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%sku_attribute}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sku_id', 'title', 'value'], 'required'],
            [['title', 'value'], 'string', 'max' => 50],
            [['sku_id'],'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'sku_id' => 'sku编码',
            'title' => '属性名称',
            'value' => '值',
        ];
    }

    /**
     * 关联sku
     */
    public function getSku(){
        return $this->hasOne(Sku::className(), ['sku_id' => 'sku_id']);
    }


    /**
     * 获取属性值
     * $title 标题 $sku_id sku编码
     */
    public static function getSkuAttributeValue($title,$sku_id){
        $model=SkuAttribute::find()->where(['title'=>$title,'sku_id'=>$sku_id])->one();
        return $model['value'];

    }

}

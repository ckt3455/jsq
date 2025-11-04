<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%brand_price}}".
 *
 * @property integer $id
 * @property string $brand_code
 * @property integer $level
 * @property integer $discount
 */
class BrandPrice extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%brand_price}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['brand_code', 'level'], 'required'],
            [['level','user_level'], 'integer'],
            [['brand_code','discount'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'brand_code' => '品牌编码',
            'level' => '价格级别',
            'discount' => '折扣',
            'user_level'=>'会员等级'
        ];
    }
}

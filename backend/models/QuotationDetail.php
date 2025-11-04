<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%quotation_detail}}".
 *
 * @property integer $id
 * @property integer $quotation_id
 * @property integer $sku_id
 * @property integer $type
 * @property string $title
 * @property string $price
 * @property string $brand
 * @property integer $number
 * @property string $period
 * @property string $specifications
 * @property string $content
 * @property string $weight
 */
class QuotationDetail extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%quotation_detail}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['quotation_id'], 'required'],
            [['quotation_id', 'sku_id', 'type', 'number'], 'integer'],
            [['price','weight'], 'number'],
            [['content'], 'string'],
            [['title', 'brand', 'period', 'specifications'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'quotation_id' => 'Quotation ID',
            'sku_id' => 'Sku ID',
            'type' => 'Type',
            'title' => 'Title',
            'price' => 'Price',
            'brand' => 'Brand',
            'number' => 'Number',
            'period' => 'Period',
            'specifications' => 'Specifications',
            'content' => 'Content',
            'weight'=>'重量'
        ];
    }

}

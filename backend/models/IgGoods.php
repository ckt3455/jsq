<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%goods}}".
 *
 * @property integer $id
 * @property string $title
 * @property string $image
 * @property string $content
 */
class IgGoods extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%ig_goods}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title','price'], 'required'],
            [['content'], 'string'],
            [['price'], 'double'],
            [['title'], 'string', 'max' => 200],
            [['image'], 'string', 'max' => 500],
            [['sort','sales','number'],'default','value' => '0'],
            [['status'],'default','value' => '1'],
            [['start_date','end_date','more_image'],'safe'],
            [['sales','sort','status','number'],'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => '商品名称',
            'start_date' => '开始日期',
            'end_date' => '结束日期',
            'status' => '状态',
            'image' => '缩略图',
            'sort' => '排序',
            'content'=>'详情',
            'price'=>'商品价格',
            'number'=>'商品库存',
            'more_image'=>'多图',
            'sales'=>'销量',
            'append'=>'上传时间',
            'updated'=>'修改时间',
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

        $this->updated = time(); 
        return parent::beforeSave($insert);
    }
    /**
     * 关联sku
     */

    public function getSku(){
        return $this->hasMany(IgSku::className(), ['goods_id' => 'id']);
    }

    /**
     * 关联sku类型
     */

    public function getSkuType(){
        return $this->hasMany(IgType::className(), ['goods_id' => 'id'])->orderBy('id asc');
    }

}
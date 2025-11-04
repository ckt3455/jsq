<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "zs_brand_floor".
 *
 * @property integer $id
 * @property string $title
 * @property integer $floor
 * @property string $goods_category_code
 */
class BrandFloor extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zs_brand_floor';
    }
    public static $type=[
      '1'=>'按游览量',
      '2'=>'按销量',
      '3'=>'自定义选择'
    ];

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'floor', 'goods_category_code','type'], 'required'],
            [['floor','brand_id','type'], 'integer'],
            [['title'], 'string', 'max' => 255],
            [['goods_category_code'], 'string', 'max' => 50],
            [['goods_code'],'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => '名称',
            'floor' => '楼层',
            'goods_category_code' => '产品分类',
            'brand_id'=>'品牌',
            'type'=>'类型',
            'goods_code'=>'产品'
        ];
    }
    //关联产品分类
    public function getGoodsCategory(){
        return $this->hasOne(GoodsCategory::className(), ['code_id' => 'goods_category_code']);
    }
    //关联品牌
    public function getBrand(){
        return $this->hasOne(Brand::className(), ['id' => 'brand_id']);
    }

    /**
     * @param bool $insert
     * @return bool
     * 自动插入
     */
    public function beforeSave($insert)
    {
        if (is_array($this->goods_code)) {
            $this->goods_code = implode('|', $this->goods_code);
        }
        return parent::beforeSave($insert);
    }


    /**
     * 获取产品
     */
    public static function getFloorGoods($id){
        $floor=BrandFloor::findOne($id);
        //如果为自定义楼层
        if($floor->type==3){
            $goods_id=explode('|',$floor->goods_code);
            $goods=Goods::find()->where(['in','id',$goods_id])->All();
        }
        else{
            if($floor->type==1){
                $order='hit desc';
            }
            if($floor->type==2){
                $order='sales desc';
            }
            $category=GoodsCategory::find()->where(['code_id'=>$floor->goods_category_code])->one();
            if ($category['level'] == 1) {
                $models = Goods::find()->where(['category_one' => $category['code_id'],'brand_code'=>$floor->brand->brand_code]);

            }
            if ($category['level'] == 2) {
                $models = Goods::find()->where(['category_two' => $category['code_id'],'brand_code'=>$floor->brand->brand_code]);
            }
            if ($category['level'] == 3) {
                $models = Goods::find()->where(['category_three' => $category['code_id'],'brand_code'=>$floor->brand->brand_code]);
            }
            $goods=$models->orderBy($order)->limit(8)->all();

        }
        return $goods;

    }
}

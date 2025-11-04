<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%brand}}".
 *
 * @property integer $id
 * @property string $brand_code
 * @property string $title
 * @property integer $parent_id
 * @property integer $level
 * @property integer $sort
 * @property integer $append
 * @property integer $updated
 */
class Brand extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%brand}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['brand_code', 'title'], 'required'],
            [['level', 'sort', 'append', 'updated','is_show'], 'integer'],
            [['brand_code','parent_id'], 'string', 'max' => 20],
            [['title'], 'string', 'max' => 50],
            [['parent_id','sort'],'default','value'=>0],
            [['image','english_title','alias','image_2'],'string','max'=>255],
            [['content'],'string'],
            [['simulations'],'double'],
            [['image_more','goods_category_code'],'safe'],
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
            'title' => '名称',
            'parent_id' => '上级编码',
            'level' => '等级',
            'sort' => '排序',
            'append' => '创建时间',
            'updated' => '更新时间',
            'image'=>'品牌logo(180*60)无底色',
            'image_2'=>'品牌logo(168*56)有底色',
            'image_more'=>'品牌页轮播banner图(1920*300)',
            'content'=>'内容',
            'goods_category_code'=>'产品分类编码用“|”隔开',
            'simulations'=>'折扣下浮%',
            'english_title'=>'英文标题',
            'alias'=>'别名',
            'is_show'=>'价格是否可见'
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
        if($this->image_more){
            $this->image_more=serialize($this->image_more);
        }
        if($this->goods_category_code){
            $this->goods_category_code=implode('|', $this->goods_category_code);
        }
        return parent::beforeSave($insert);
    }


    /**
     * 获取品牌下的产品分类及其子类
     */

    public static function getGoodsCategoryList($id){
        $model=Brand::findOne($id);


        if($model){
            $data = GoodsCategory::find()
                ->where(['and',['like','brand_code',$model->brand_code],['level'=>1]])
                ->orderBy('sort ASC')
                ->asArray()
                ->all();
            return $data;
        }
        else{
            return false;
        }
    }
    /**
     * 获取品牌
     */

    public static function getList()
    {
        $model = Brand::find()->where(['level'=>1])->asArray()->all();
        return ArrayHelper::map($model,'id','title');
    }

    public static function getListCode()
    {
        $model = Brand::find()->where(['level'=>1])->asArray()->all();
        return ArrayHelper::map($model,'brand_code','title');
    }


    /**
     * 获取品牌下的产品
     * $type 1:最新产品 2:热门产品 3:销量排行
     */

    public static function getNewGoods($brand_id,$type){
        $brand=Brand::findOne($brand_id);
        $goods=Goods::find()->where(['brand_code'=>$brand['brand_code'],'sign'=>$type])->orderBy('append desc')->limit(6)->all();
        return $goods;

    }
    /**
     * 根据当前产品分类获取品牌
     */
    public static function BrandGoodsCategory($category_id){
        $brand=[];
        $category=GoodsCategory::find()->where(['code_id'=>$category_id])->one();
        if(isset($category['brand_code'])){
            $brand_code=explode('|',$category['brand_code']);
            $brand=Brand::find()->where(['in','brand_code',$brand_code])->all();
        }
        return $brand;
    }

    /**
     * 关联价格级别
     */

    public function getBrandPrice()
    {
        return $this->hasMany(BrandPrice::className(), ['brand_code' => 'brand_code']);
    }


    /**
     * 关联标签
     */

    public function getSign()
    {
        return $this->hasMany(BrandSign::className(), ['brand_id' => 'id']);
    }

    /**
     * 关联楼层
     */

    public function getFloor()
    {
        return $this->hasMany(BrandFloor::className(), ['brand_id' => 'id']);
    }



    /**
     * 是否显示价格
     */
    public static function is_show_price($brand_id,$user_id){
        $brand=Brand::findOne($brand_id);
        $user=ProvinceUser::findOne($user_id);
        if($brand->is_show==1){
                return 1;
        }
        if(!$user_id){
            return 0;
        }
        if(!isset($user->level)){
            return 0;
        }
        $show=BrandUserShow::find()->where(['brand_id'=>$brand_id,'user_level'=>$user->level->id])->one();
        if($show){
            return $show->is_show;
        }
        else{
            return 1;
        }

    }

}

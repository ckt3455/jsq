<?php

namespace backend\models;

use common\components\Helper;
use Yii;
use yii\helpers\Url;

/**
 * This is the model class for table "{{%floor}}".
 *
 * @property integer $id
 * @property integer $category_id
 * @property string $image
 * @property string $goods_code
 * @property integer $number
 * @property integer $append
 * @property integer $updated
 * @property integer $type
 * @property string $goods
 * @property string $brand
 * @property string $category_children
 */
class Floor extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%floor}}';
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
            [['goods_code', 'number'], 'required'],
            [['number', 'append', 'updated','type'], 'integer'],
            [['image','goods_code','title','mobile_image1','mobile_image2'], 'string'],
            [['brand','category_children','goods'],'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'goods_code' => '产品分类',
            'title'=>'标题',
            'image' => '图片(228*300)',
            'number' => '楼层',
            'append' => '添加时间',
            'updated' => '更新时间',
            'brand'=>'品牌(数量最多选择6个)',
            'category_children'=>'子产品分类(最多展示12个分类)',
            'goods'=>'自定义选择(最多16个)',
            'mobile_image1'=>'手机端楼层选中图标(42*40 png)',
            'mobile_image2'=>'手机端楼层未选中图标(42*40 png)',
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
        if (is_array($this->brand)) {
            $this->brand = implode('|', $this->brand);
        }
        if(is_array($this->goods)){
            $this->goods=implode('|', $this->goods);
        }
        if (is_array($this->category_children)) {
            $this->category_children = implode('|', $this->category_children);
        }

        return parent::beforeSave($insert);
    }

    //关联产品分类
    public function getGoodsCategory(){
        return $this->hasOne(GoodsCategory::className(), ['code_id' => 'goods_code']);
    }
    //产品1级分类关联
    public function getGoodsOne(){
        return $this->hasMany(Goods::className(), ['category_one' => 'goods_code']);
    }
    //前台楼层模型
    public static function floorModel($id){
        $floor=Floor::findOne($id);
        //子产品分类
        if($floor->category_children){
            $data=explode('|',$floor->category_children);
            $children=GoodsCategory::find()->where(['in','code_id',$data])->all();
        }
        else{
            $children=[];
        }
        //sku
        if($floor->type==1){
            //按浏览量
            $goods = Goods::find()->where(['category_one' => $floor->goods_code])->orderBy('hit desc')->limit(16)->asArray()->all();
        }
        if($floor->type==2){
            //按销量
            $goods = Goods::find()->where(['category_one' => $floor->goods_code])->orderBy('sales desc')->limit(16)->asArray()->all();

        }

        if($floor->type==3){
            //自定义选择
            $arr=explode('|',$floor->goods);
            $goods = Goods::find()->where(['in','code_id',$arr])->limit(16)->asArray()->all();
        }

        $children_html='';
        foreach ($children as $k=>$v){
            $children_html.='<a target="_blank" href="'.Url::to(['goods/index','category_id'=>$v->code_id]).'">'.$v->name.'</a>';
        }
        $sku_html='';
        foreach ($goods as $k=>$v){
            $sku_html.=Goods::GoodsModel2($v['id']);
        }
        //品牌
        if($floor->brand){
            $brand_data=explode('|',$floor->brand);
            $brand=Brand::find()->where(['in','id',$brand_data])->all();
        }
        else{
            $brand=[];
        }
        $brand_html='';
        foreach ($brand as $k=>$v){
            $image=Helper::default_image($v->image_2,2);
            $brand_html.='<a href="'.Url::to(['brand/brand-list','id'=>$v->id]).'"><img src="'.$image.'"></a>';
        }
        if(count($goods)>8){
            $swiper='  <div class="swiper-button-prev floor1_prev"></div>
                            <div class="swiper-button-next floor1_next"></div>';
        }else{
            $swiper='';
        }



        $html='<div class="floor">
                <div class="cen clearfix">
                    <div class="first_floor">
                        <a  style="display:block"  href="'. Url::to(["goods/index","category_id"=>$floor->goodsCategory->code_id]).'">
                            <div class="first_pic" style="background: url('.$floor->image.')">
                                 <p>'.$floor->title.'</p>

                            </div>
                        </a>

                        <div class="first_href clearfix">
                            '.$children_html.'
                        </div>

                        <div class="brand_lists clearfix">
                           '.$brand_html.'

                        </div>

                    </div>

                    <div class="first_floor_r" >
                        <div class="swiper-container floor_swiper" id="floor_swiper_'.$id.'">
                            <ul class="clearfix swiper-wrapper">
                                '.$sku_html.'
                            </ul>
                           
                           '.$swiper.'
                        </div>
                    </div>

                </div>
            </div>';
        return $html;
    }


    //手机前台楼层模型
    public static function mobileFloorModel($id){
        $floor=Floor::findOne($id);
        //子产品分类
        if($floor->category_children){
            $data=explode('|',$floor->category_children);
            $children=GoodsCategory::find()->where(['in','code_id',$data])->all();
        }
        else{
            $children=[];
        }
        //sku
        if($floor->type==1){
            //按浏览量
            $goods = Goods::find()->where(['category_one' => $floor->goods_code])->orderBy('hit desc')->limit(7)->asArray()->all();
        }
        if($floor->type==2){
            //按销量
            $goods = Goods::find()->where(['category_one' => $floor->goods_code])->orderBy('sales desc')->limit(7)->asArray()->all();

        }

        if($floor->type==3){
            //自定义选择
            $arr=explode('|',$floor->goods);
            $goods = Goods::find()->where(['in','code_id',$arr])->limit(7)->asArray()->all();
        }

        $children_html='';
        foreach ($children as $k=>$v){
            $children_html.='<a target="_blank" href="'.Url::to(['goods/sku','category_id'=>$v->code_id]).'">'.$v->name.'</a>';
        }

        //品牌
        if($floor->brand){
            $brand_data=explode('|',$floor->brand);
            $brand=Brand::find()->where(['in','id',$brand_data])->all();
        }
        else{
            $brand=[];
        }
        $brand_html='';
        foreach ($brand as $k=>$v){
            $image=Helper::default_image($v->image_2,2);
            $brand_html.='<a href="'.Url::to(['brand/brand-list','id'=>$v->id]).'"><img src="'.$image.'"></a>';
        }

        $sku_html='';
        foreach ($goods as $k=>$v){
            if($k==2){
                $sku_html.='<div class="pro_item pro_item1">
                    <div class="first_href">
                         '. $children_html.'
                    </div>
                    <div class="brand_lists">
                        '.$brand_html.'
                    </div>
                </div>'.Goods::MobileGoodsModel2($v['id']);
            }else{
                $sku_html.=Goods::MobileGoodsModel2($v['id']);
            }

        }

        $html=''.$sku_html.'';
        return $html;
    }
}

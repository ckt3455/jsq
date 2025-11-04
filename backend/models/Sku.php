<?php

namespace backend\models;

use Yii;
use yii\helpers\Url;

/**
 * This is the model class for table "{{%sku}}".
 *
 * @property integer $id
 * @property integer $sku_id
 * @property integer $code_id
 * @property string $sku_title
 * @property double $factory_price
 * @property double $cost_price
 * @property integer $min_number
 * @property integer $period
 * @property integer $status
 * @property string $gross_weight
 */
class Sku extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%sku}}';
    }
    public static $period=[
        '0'=>'货期咨询',
        '1'=>'现货',
    ];

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sku_id'], 'required'],
            [['min_number','inventory','price_level','is_html','sort'], 'integer'],
            [[ 'cost_price'], 'number'],
            [['sku_title','number','unit','status','code_id'], 'string', 'max' => 50],
            [['gross_weight'], 'string', 'max' => 10],
            [['sku_id'],'string', 'max' => 20],
            [['specifications','title','feature','fixed_price','period'], 'string', 'max' => 255],
            [['sign'],'default','value'=>0],
            [['sku_keywords','factory_price'],'safe']
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
            'title'=>'sku名称',
            'code_id' => '产品编码',
            'sku_title' => '金蝶名称',
            'factory_price'=>'价格',
            'cost_price'=>'成本价',
            'min_number'=>'最小包装数量',
            'period'=>'订货期',
            'status'=>'状态',
            'gross_weight'=>'毛重',
            'inventory'=>'库存',
            'number'=>'厂家编号',
            'specifications'=>'规格型号',
            'sign'=>'标签',
            'sales'=>'销量',
            'fixed_price'=>'一口价',
            'is_html'=>'是否可见',
            'brand_code'=>'品牌',
            'price_level'=>'价格体系',
            'sort'=>'排序',
           'unit'=>'单位',
            'feature'=>'特征',
            'sku_keywords'=>'关键词',
            'sku_limit'=>'库存紧张',
            'sku_min'=>'库存不足'
        ];
    }


    public function beforeSave($insert)
    {

        //标签
        if (is_array($this->sign)) {
            $this->sign = implode(',', $this->sign);
        }

        return parent::beforeSave($insert);
    }

    /**
     * 关联sku属性
     */

    public function getSkuAttribute(){
        return $this->hasMany(SkuAttribute::className(), ['sku_id' => 'sku_id']);
    }


    /**
     * 关联产品
     */

    public function getGoods(){
        return $this->hasOne(Goods::className(), ['code_id' => 'code_id']);
    }


    /**
     * 计算价格
     */
    public  function getShowPrice(){
        $price=$this->factory_price;
        if(Yii::$app->user->id){
            $user=ProvinceUser::findOne(Yii::$app->user->id);
            $level=UserLevel::find()->where(['<=','experience',$user->experience])->orderBy('experience desc')->limit(1)->one();
            if($level){
                $price=$this->factory_price*($level->discount/100);
            }
        }
        return $price;

    }


    /**
     * sku库存判断
     */
    public static function sku_inventory($id){
        if(!$id){
            return ['message'=>''];
        }
        $model=Sku::findOne($id);


        //库存充足
        if($model->inventory>$model->sku_limit){
            $return['status']=1;
            $return['message']='现货';
        }
        if($model->inventory<=$model->sku_limit and $model->inventory>$model->sku_min){
            $return['status']=2;
            $return['message']='库存紧张';
        }
        if($model->inventory<=$model->sku_min){
            $return['status']=3;
            $return['message']='货期咨询';
        }
        return $return;

    }
    /**
     * sku属性获取
     */

    public static function sku_attribute($sku_id,$title){
        $model=SkuAttribute::find()->where(['sku_id'=>$sku_id,'title'=>$title])->limit(1)->one();
        if($model){
            return $model->value;
        }else{
            return '-';
        }
    }

}


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
class IgSku extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%ig_sku}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['goods_id','type_id','price'], 'required'],
            [['price'],'double'],
            [['type_id'],'string','max'=>200],
            [['goods_id','number'],'integer'],
            [['number'],'default','value' => '0'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'goods_id' => '商品ID',
            'type_id' => '属性ID',
            'price' => '价格',
            'number'=>'库存',
        ];
    }

    /**
     * @param bool $insert
     * @return bool
     * 自动插入
     */
    public function beforeSave($insert)
    {
        /*if($this->isNewRecord)
        {
            $this->append = time();
        }
        else
        {
            $this->updated = time();
        }*/

        return parent::beforeSave($insert);
    }

    public static function is_type($id,$type_id)
    {
        $typeid = explode(',', $type_id);
        if(in_array($id,$typeid))
        {
            return true;
        }else
        {
            return false;
        }
        
    }
    /*
     * sku属性获取
     * */

    public static function sku_attribute($sku_id){
        $sku=IgSku::findOne($sku_id);
        $data='';
        if($sku){
            $type=explode(',',$sku->type_id);
            foreach ($type as $k=>$v){
                $attribute=IgType::findOne($v);
                if($data==''){
                    $data=$attribute->value;
                }
                else{
                    $data.=','.$attribute->value;
                }

            }
        };
        return $data;




    }

}
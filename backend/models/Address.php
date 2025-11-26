<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%address}}".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $province
 * @property string $city
 * @property string $area
 * @property string $content
 * @property string $user
 * @property string $phone
 * @property integer $is_default
 * @property string $is_default2
 */
class Address extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%address}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'province', 'city', 'area', 'user', 'phone'], 'required'],
            [['user_id', 'is_default', 'is_default2'], 'integer'],
            [['content','sign'], 'string'],
            [['province', 'city', 'area', 'user'], 'string', 'max' => 50],
            [['phone'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => '用户id',
            'province' => '省',
            'city' => '市',
            'area' => '区',
            'content' => '详细地址',
            'user' => '收货人',
            'phone' => '电话',
            'is_default' => '默认地址',
            'is_default2' => '发票默认地址',
        ];
    }


    public function beforeSave($insert)

    {


        if($this->isAttributeChanged('is_default') and $this->is_default==1){
            Address::updateAll(['is_default'=>0],['user_id'=>$this->user_id]);
        }


        return parent::beforeSave($insert);

    }

}

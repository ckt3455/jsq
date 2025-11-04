<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%server}}".
 *
 * @property integer $id
 * @property string $title
 * @property integer $type
 * @property string $qq
 * @property string $phone
 * @property string $email
 * @property string $business
 * @property string $business_qq
 * @property string $wx_image
 * @property string $wx_number
 * @property string $telephone
 * @property string $wangwang
 * @property string $fax
 */
class Server extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%server}}';
    }

    public static $type=[
        1=>'普通客服',
        2=>'服务客服'
    ];

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type'], 'integer'],
            [['title', 'email', 'wx_number', 'telephone','fax'], 'string', 'max' => 50],
            [['qq', 'phone'], 'string', 'max' => 20],
            [['business', 'business_qq', 'wx_image'], 'string', 'max' => 255],
            [['wangwang'],'string'],
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
            'type' => '类型',
            'qq' => '个人qq',
            'phone' => '手机',
            'email' => '邮箱',
            'business' => '营业时间',
            'business_qq' => '营销qq',
            'wx_image' => '微信图片',
            'wx_number' => '微信号',
            'telephone' => '固定电话',
            'wangwang' => '旺旺',
            'fax'=>'传真'
        ];
    }

    public static function getList(){
        $model = Server::find()->where(['type'=>2])->asArray()->all();
        return ArrayHelper::map($model, 'id', 'title');
    }
}

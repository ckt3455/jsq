<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%user_level}}".
 *
 * @property string $id
 * @property string $name
 * @property string $number
 * @property string $message
 */
class UserLevel extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_level}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'message'], 'string', 'max' => 255],
            [['number', 'number2','money1','money2','money3'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '名称',
            'number' => '复购赠送',
            'number2' => '复购奖金%',
            'number3' => '管理奖%',
            'message' => '说明',
            'money1'=>'单次消费需要金额',
            'money2'=>'推荐代言人',
            'money3'=>'推荐合伙人',
            'money4'=>'推荐联创'
        ];
    }

    public static function getList(){
        $model=UserLevel::find()->orderBy('id asc')->asArray()->all();

        return ArrayHelper::map($model,'id','name');
    }
}

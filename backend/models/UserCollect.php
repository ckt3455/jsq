<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%user_collect}}".
 *
 * @property string $id
 * @property string $user_id
 * @property string $goods_id
 * @property string $created_at
 */
class UserCollect extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_collect}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'goods_id', 'created_at'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'goods_id' => 'Goods ID',
            'created_at' => 'Created At',
        ];
    }

    public function getGoods(){
        return $this->hasOne(Goods::className(),['id'=>'goods_id']);
    }

    public static function is_collect($user_id,$goods_id){
        $model=UserCollect::find()->where(['goods_id'=>$goods_id,'user_id'=>$user_id])->one();
        if($model){
            return 1;
        }else{
            return 0;
        }
    }
}

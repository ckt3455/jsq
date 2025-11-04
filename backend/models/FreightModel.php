<?php

namespace backend\models;

use common\components\ArrayArrange;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%freight_model}}".
 *
 * @property integer $id
 * @property string $title
 * @property integer $is_default
 * @property integer $type
 * @property integer $sort
 */
class FreightModel extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%freight_model}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['is_default', 'type', 'sort','status'], 'integer'],
            [['title'], 'string', 'max' => 255],
            [['first','first_money','next','next_money'],'double'],
            ['type','validateType'],
            [['content'],'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => '标题',
            'is_default' => '是否默认',
            'type' => '类型',
            'sort' => '排序',
            'content'=>'详细介绍',
            'status'=>'是否使用'
        ];
    }

    public function validateType(){
        if (!$this->hasErrors()) {
            if($this->is_default==1){
                FreightModel::updateAll(['is_default'=>0],['is_default'=>1]);
            }
        }
    }


    public function checkPassword($attribute)
    {
        if($this->isNewRecord){
            if (!$this->hasErrors()) {
                if($this->password_hash!==$this->re_password){
                    $this->addError($attribute, '两次输入的密码不一致');
                }
            }
        }

    }

    /**
     * 关联详情
     */
    public function getDetail(){
        return $this->hasMany(Freight::className(), ['model_id' => 'id']);
    }

    /**
     * 重量,件数计算运费
     */
    public static function Freight($weight,$freight_id,$number,$city_id){
        $money=0;
        $freight=FreightModel::findOne($freight_id);
        if($freight){
            if($freight->type==1 and $weight>0){
                //按重量计费
                $model=Freight::find()->where(['model_id'=>$freight->id])->andWhere(['like','city_id',$city_id])->one();
                if($model){
                    if($weight<=$model->first){
                        $money=$model->first_money;
                    }else{
                        $money=$model->first_money+ceil(($weight-$model->first)/$model->next)*$model->next_money;
                    }


                }else{
                    if($weight<=$freight->first){
                        $money=$freight->first_money;
                    }else{
                        $money=$freight->first_money+ceil(($weight-$freight->first)/$freight->next)*$freight->next_money;
                    }

                }


            }
            if($freight->type==2 and $number>0){
                //按件数计费
                $model=Freight::find()->where(['model_id'=>$freight->id])->andWhere(['like','city_id',$city_id])->one();
                if($model){
                    if($number<=$model->first){
                        $money=$model->first_money;
                    }else{
                        $money=$model->first_money+ceil(($number-$model->first)/$model->next)*$model->next_money;
                    }


                }else{
                    if($number<=$freight->first){
                        $money=$freight->first_money;
                    }else{
                        $money=$freight->first_money+ceil(($number-$freight->first)/$freight->next)*$freight->next_money;
                    }
                }

            }
        }
        return $money;
    }

    /**
     * 获取配送列表
     */
    public static function getList(){
        $model=FreightModel::find()->all();
        return ArrayHelper::map($model,'id','title');
    }


}

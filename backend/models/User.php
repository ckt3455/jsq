<?php

namespace backend\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%user}}".
 *
 * @property string $id
 * @property string $mobile
 * @property string $password
 * @property string $code
 * @property string $parent_id
 * @property string $money
 * @property string $created_at
 * @property string $updated_at
 * @property string $is_buy
 * @property string $name
 */
class User extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mobile', 'password'], 'required'],
            [['parent_id', 'created_at', 'updated_at', 'is_buy', 'level_time', 'level_time2', 'level_time3'], 'integer'],
            [['money'], 'number'],
            [['mobile', 'code'], 'string', 'max' => 20],
            [['password'], 'string', 'max' => 255],
            [['mobile'], 'unique', 'message' => '该号码已存在'],
            [['code'], 'unique', 'message' => '邀请码已存在'],
            ['mobile', 'match', 'pattern' => '/^1[3-9]\d{9}$/','message'=>'手机号格式不正确'],
            [['name', 'image', 'level_id', 'integral', 'dl_type', 'city', 'area'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'mobile' => '手机号',
            'password' => '密码',
            'parent_id' => '直推用户',
            'money' => '余额',
            'created_at' => '添加时间',
            'updated_at' => 'Updated At',
            'is_buy' => 'Is Buy',
            'image' => '头像',
            'name' => '名称',
            'level_id' => '用户等级',
            'integral' => '积分',
            'city' => '城市',
            'area' => '地区',
        ];
    }

    public function beforeSave($insert)

    {


        if ($this->isAttributeChanged('password')) {


            $this->password = md5($this->password.md5(Yii::$app->params['password_code']));


        }


        return parent::beforeSave($insert);

    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
            ],
        ];
    }






    public function getParent(){
        return $this->hasOne(User::className(),['id'=>'parent_id']);
    }


    public static function getList(){
        $model=User::find()->asArray()->all();
        return ArrayHelper::map($model,'id','mobile');
    }


    public static function getList2(){
        $model=User::find()->asArray()->all();
        $arr=[];
        foreach ($model as $k=>$v){
            $arr[$v['id']]=$v['mobile'].'-'.$v['name'];
        }
        return $arr;
    }



    //关联用户等级
    public function  getLevel(){
        return $this->hasOne(UserLevel::className(),['id'=>'level_id']);
    }






}

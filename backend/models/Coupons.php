<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%coupons}}".
 *
 * @property integer $id
 * @property string $title
 * @property string $money
 * @property integer $type
 * @property integer $using_range
 * @property string $need_money
 * @property integer $days
 * @property integer $start_time
 * @property integer $end_time
 * @property integer $time_type
 * @property integer $number_limit
 * @property integer $number
 * @property string $relation
 */
class Coupons extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%coupons}}';
    }


    public static $using_range_message=[
        1=>'全商城',
        2=>'品牌+商品',
        3=>'品牌+价格级别',
        4=>'品牌+SKU'
    ];


    public static $time_type_message=[
        1=>'领取后几天有效',
        2=>'固定时间',
    ];

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['money', 'need_money'], 'number'],
            [['type', 'using_range', 'days', 'start_time', 'end_time', 'time_type', 'number_limit', 'number'], 'integer'],
            [['title'], 'string', 'max' => 50],
            [['relation'], 'string', 'max' => 255],
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
            'money' => '优惠金额',
            'type' => '优惠类型',
            'using_range' => '使用范围',
            'need_money' => '金额要求',
            'days' => '领取后几天有效',
            'start_time' => '开始时间',
            'end_time' => '结束时间',
            'time_type' => '时间类型',
            'number_limit' => '每人领取限制数量',
            'number' => '总数量',
            'relation'=>'使用的关联条件'
        ];
    }


    //用户领取优惠劵
    public static function UserGetCoupons($user_id,$coupons_id){
        $data=[
            'error'=>1,
            'message'=>''
        ];
        $user=ProvinceUser::findOne($user_id);
        if($user){
            $coupons=Coupons::findOne($coupons_id);
            if($coupons){
                if($coupons->number<=0){
                    $data['message']='优惠劵数量不足';
                }else{
                    $coupons_number=UserCoupons::find()->where(['coupons_id'=>$coupons_id])->count();
                    if($coupons_number>=$coupons->number_limit){
                        $data['message']='已经达到该优惠劵领取的上限';
                    }else{
                        $new_coupons=new UserCoupons();
                        $new_coupons->user_id=$user_id;
                        $new_coupons->relation=$coupons->relation;
                        $new_coupons->money=$coupons->money;
                        $new_coupons->need_money=$coupons->need_money;
                        $new_coupons->created_at=time();
                        $new_coupons->coupons_id=$coupons_id;
                        if($coupons->time_type==1){
                            $new_coupons->start_time=time();
                            $new_coupons->end_time=time()+24*3600*$coupons->days;
                        }elseif($coupons->time_type==2){
                            $new_coupons->start_time= $coupons->start_time;
                            $new_coupons->end_time=$coupons->end_time;
                        }
                        if($new_coupons->save()){
                            $data['error']=0;
                            $coupons->updateCounters(['number'=>-1]);
                        }else{
                            $data['message']='领取优惠劵失败';
                        }

                    }
                }

            }else{
                $data['message']='优惠劵不存在';
            }
        }else{
            $data['message']='用户不存在';
        }

        return $data;
    }
}

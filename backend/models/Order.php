<?php

namespace backend\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Exception;

/**
 * This is the model class for table "{{%order}}".
 *
 * @property string $id
 * @property string $user_id
 * @property string $money
 * @property string $freight
 * @property string $content
 * @property string $image
 * @property string $pay_method
 * @property string $status
 * @property string $province
 * @property string $city
 * @property string $area
 * @property string $address
 * @property string $contact
 * @property string $phone
 * @property string $created_at
 * @property string $updated_at
 * @property string $pay_status
 * @property string $paid_time
 * @property string $express
 * @property string $express_number
 * @property string $fh_time
 * @property string $finish_time
 */
class Order extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'pay_method', 'created_at', 'updated_at', 'pay_status', 'paid_time', 'fh_time', 'finish_time'], 'integer'],
            [['money', 'freight', 'status'], 'number'],
            [['content', 'image', 'province', 'city', 'area', 'address'], 'string', 'max' => 255],
            [['contact', 'express', 'express_number'], 'string', 'max' => 100],
            [['phone'], 'string', 'max' => 50],
        ];
    }

    public static $status_message = [
        1 => '待付款',
        2 => '待发货',
        3 => '待收货',
        4 => '已完成',
        -1 => '已取消'
    ];

    public static $type_message = [
        1 => '普通订单',
        2 => '积分订单',
        3 => '复购订单',
        4=>'绿色积分区'
    ];

    //团队极差设置
    public static $money_type=[
        '80000'=>1,
        '180000'=>2,
        '360000'=>3,
        '860000'=>4,
        '1680000'=>5
    ];

    //团队奖最低条件80000
    public static $min_money=80000;

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

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => '用户',
            'money' => '金额',
            'freight' => '运费',
            'content' => '备注',
            'image' => '图片',
            'pay_method' => '支付方式',
            'status' => '状态',
            'province' => '省',
            'city' => '市',
            'area' => '区',
            'address' => '详情地址',
            'contact' => '联系人',
            'phone' => '电话',
            'created_at' => '添加时间',
            'updated_at' => '修改时间',
            'pay_status' => '支付状态',
            'paid_time' => '支付时间',
            'express' => '快递',
            'express_number' => '快递单号',
            'fh_time' => '发货时间',
            'finish_time' => '完成时间',
            'order_number' => '订单编号'
        ];
    }


    //单产品购买
    public static function add_order($user_id, $goods_id, $number, $address_id, $content, $image)
    {
        $return = [
            'error' => 1,
            'message' => ''
        ];
        $transaction = Yii::$app->db->beginTransaction();
        $old = Order::find()->where(['user_id' => $user_id])->andWhere(['>=', 'status', 2])->limit(1)->one();
        $user = User::findOne($user_id);
        try {
            $model = new Order();
            if ($old) {
                $model->type = 3;
            }
            $model->content = $content;
            $address = Address::findOne($address_id);
            if (!$address) {
                throw new Exception('地址不存在');
            }

            $goods = Goods::findOne($goods_id);
            $number = intval($number);
            if (!$goods) {
                throw new Exception('产品不存在');
            }
            if ($number <= 0) {
                throw new Exception('数量不正确');
            }
            if ($goods->number < $number) {
                throw new Exception('库存不足');
            }

            if (!$goods->updateCounters(['number' => -$number])) {
                throw new Exception('库存不足');
            }
            $money = $goods['price'] * intval($number);
            if ($money <= 0) {
                throw new Exception('金额不正确');
            }
            $model->order_number = date('YmdHis') . $user_id . mt_rand(1000, 9999);
            $model->address = $address->content;
            $model->contact = $address->user;
            $model->address = $address->content;
            $model->province = $address->provinces;
            $model->city = $address->city;
            $model->area = $address->area;
            $model->user_id = $user_id;
            $model->money = $money;
            $model->image = $image;
            $model->phone = $address->phone;
            if (!$model->save()) {
                $error = $model->getErrors();
                $error = reset($error);
                throw new Exception($error);
            }
            $detail = new OrderDetail();
            $detail->order_id = $model->id;
            $detail->user_id = $user_id;
            $detail->goods_id = $goods_id;
            $detail->goods_title = $goods->title;
            $detail->goods_image = $goods->image;
            $detail->price = $goods->price;
            $detail->number = intval($number);
            if ($old) {
                $level = UserLevel::findOne($user['level_id']);
                if ($goods['id'] == 2 and $level) {
                    if ($level['id'] == 1) {
                        $detail->number2 = $detail->number * 13;
                    } else {
                        $detail->number2 = $detail->number * 20;
                    }

                } else {
                    if ($level['number']) {
                        $detail->number2 = $detail->number * $level['number'];
                    }
                }

            }
            $detail->money = $money;

            if (!$detail->save()) {
                $error = $detail->getErrors();
                $error = reset($error);
                throw new Exception($error);
            }
            $return['error'] = 0;
            $transaction->commit();
        } catch (Exception $e) {
            $return['message'] = $e->getMessage();
            Yii::warning("\r\n" . print_r($return, true) . "\r\n", 'order_add');

            $transaction->rollBack();
        }
        return $return;
    }


    //积分购买
    public static function add_order2($user_id, $goods_id, $number, $address_id, $content)
    {
        $return = [
            'error' => 1,
            'message' => ''
        ];
        $number = intval($number);
        $transaction = Yii::$app->db->beginTransaction();
        $user = User::findOne($user_id);
        try {
            $model = new Order();
            $model->type = 2;
            $model->status = 2;
            $model->content = $content;
            $address = Address::findOne($address_id);
            if (!$address) {
                throw new Exception('地址不存在');
            }

            $goods = Goods::findOne($goods_id);
            if (!$goods) {
                throw new Exception('产品不存在');
            }
            if ($number <= 0) {
                throw new Exception('数量不正确');
            }
            $money = $goods['price'] * intval($number);
            if ($money <= 0) {
                throw new Exception('积分不正确');
            }
            if ($user->integral < $money) {
                throw new Exception('积分不足');
            }

            if ($goods->number < $number) {
                throw new Exception('库存不足');
            }

            if (!$goods->updateCounters(['number' => -$number])) {
                throw new Exception('库存不足');
            }

            if (!$user->updateCounters(['integral' => -$money])) {
                throw new Exception('积分扣减失败');
            }
            $model->order_number = date('YmdHis') . $user_id . mt_rand(1000, 9999);
            $model->address = $address->content;
            $model->contact = $address->user;
            $model->address = $address->content;
            $model->province = $address->provinces;
            $model->city = $address->city;
            $model->area = $address->area;
            $model->user_id = $user_id;
            $model->money = $money;
            $model->phone = $address->phone;
            if (!$model->save()) {
                $error = $model->getErrors();
                $error = reset($error);
                throw new Exception($error);
            }
            $detail = new OrderDetail();
            $detail->order_id = $model->id;
            $detail->user_id = $user_id;
            $detail->goods_id = $goods_id;
            $detail->goods_title = $goods->title;
            $detail->goods_image = $goods->image;
            $detail->price = $goods->price;
            $detail->number = intval($number);
            $detail->money = $money;

            if (!$detail->save()) {
                $error = $detail->getErrors();
                $error = reset($error);
                throw new Exception($error);
            }
            $log = new IntLog();
            $log->user_id = $user_id;
            $log->number = $money;
            $log->order_id = $model->id;
            $log->order_number = $model->order_number;
            $log->type = 1;
            $log->status = 2;
            $log->content = '积分兑换扣减';
            if (!$log->save()) {
                $error = $log->getErrors();
                $error = reset($error);
                throw new Exception($error);
            }

            $return['error'] = 0;
            $transaction->commit();
        } catch (Exception $e) {
            $return['message'] = $e->getMessage();
            Yii::warning("\r\n" . print_r($return, true) . "\r\n", 'order_add');

            $transaction->rollBack();
        }
        return $return;
    }



    //绿色积分区购买
    public static function add_order4($user_id, $goods_id, $number, $address_id, $content, $image)
    {
        $return = [
            'error' => 1,
            'message' => ''
        ];
        $transaction = Yii::$app->db->beginTransaction();
        $old = Order::find()->where(['user_id' => $user_id])->andWhere(['>=', 'status', 2])->limit(1)->one();
        if($old){
            $return['message']='绿色积分区,只有首单可购买';
            return $return;
        }
        $user = User::findOne($user_id);
        try {
            $model = new Order();
            $model->type=4;
            $model->content = $content;
            $address = Address::findOne($address_id);
            if (!$address) {
                throw new Exception('地址不存在');
            }

            $goods = Goods::findOne($goods_id);
            $number = intval($number);
            if (!$goods) {
                throw new Exception('产品不存在');
            }
            if ($number <= 0) {
                throw new Exception('数量不正确');
            }
            if ($goods->number < $number) {
                throw new Exception('库存不足');
            }

            if (!$goods->updateCounters(['number' => -$number])) {
                throw new Exception('库存不足');
            }
            $money = $goods['price'] * intval($number);
            if ($money <= 0) {
                throw new Exception('金额不正确');
            }
            $model->order_number = date('YmdHis') . $user_id . mt_rand(1000, 9999);
            $model->address = $address->content;
            $model->contact = $address->user;
            $model->address = $address->content;
            $model->province = $address->provinces;
            $model->city = $address->city;
            $model->area = $address->area;
            $model->user_id = $user_id;
            $model->money = $money;
            $model->image = $image;
            $model->phone = $address->phone;
            if (!$model->save()) {
                $error = $model->getErrors();
                $error = reset($error);
                throw new Exception($error);
            }
            $detail = new OrderDetail();
            $detail->order_id = $model->id;
            $detail->user_id = $user_id;
            $detail->goods_id = $goods_id;
            $detail->goods_title = $goods->title;
            $detail->goods_image = $goods->image;
            $detail->price = $goods->price;
            $detail->number = intval($number);
            if ($old) {
                $level = UserLevel::findOne($user['level_id']);
                if ($goods['id'] == 2 and $level) {
                    if ($level['id'] == 1) {
                        $detail->number2 = $detail->number * 13;
                    } else {
                        $detail->number2 = $detail->number * 20;
                    }

                } else {
                    if ($level['number']) {
                        $detail->number2 = $detail->number * $level['number'];
                    }
                }

            }
            $detail->money = $money;

            if (!$detail->save()) {
                $error = $detail->getErrors();
                $error = reset($error);
                throw new Exception($error);
            }
            $return['error'] = 0;
            $transaction->commit();
        } catch (Exception $e) {
            $return['message'] = $e->getMessage();
            Yii::warning("\r\n" . print_r($return, true) . "\r\n", 'order_add');

            $transaction->rollBack();
        }
        return $return;
    }



    //购物车下单
    public static function add_order3($user_id, $cart, $address_id, $content, $image)
    {
        $return = [
            'error' => 1,
            'message' => ''
        ];
        $transaction = Yii::$app->db->beginTransaction();
        $old = Order::find()->where(['user_id' => $user_id])->andWhere(['>=', 'status', 2])->limit(1)->one();
        $user = User::findOne($user_id);
        try {
            $model = new Order();
            if ($old) {
                $model->type = 3;
            }
            $model->content = $content;
            $address = Address::findOne($address_id);
            if (!$address) {
                throw new Exception('地址不存在');
            }
            $money = 0;
            foreach ($cart as $k => $v) {
                $now = UserCart::findOne($k);
                $goods = Goods::findOne($now['goods_id']);
                $number = intval($v);

                if (!$goods) {
                    throw new Exception('产品不存在');
                }
                if ($number <= 0) {
                    throw new Exception('数量不正确');
                }
                if ($goods->number < $number) {
                    throw new Exception('库存不足');
                }

                if (!$goods->updateCounters(['number' => -$number])) {
                    throw new Exception('库存不足');
                }
                $money += $goods['price'] * intval($number);
                if ($money <= 0) {
                    throw new Exception('金额不正确');
                }
            }


            $model->order_number = date('YmdHis') . $user_id . mt_rand(1000, 9999);
            $model->address = $address->content;
            $model->contact = $address->user;
            $model->address = $address->content;
            $model->province = $address->provinces;
            $model->city = $address->city;
            $model->area = $address->area;
            $model->user_id = $user_id;
            $model->money = $money;
            $model->image = $image;
            $model->phone = $address->phone;
            if (!$model->save()) {
                $error = $model->getErrors();
                $error = reset($error);
                throw new Exception($error);
            }
            $arr_id = [];
            foreach ($cart as $k => $v) {
                $arr_id[] = $k;
                $now = UserCart::findOne($k);
                $goods = Goods::findOne($now['goods_id']);
                $detail = new OrderDetail();
                $detail->order_id = $model->id;
                $detail->user_id = $user_id;
                $detail->goods_id = $k;
                $detail->goods_title = $goods->title;
                $detail->goods_image = $goods->image;
                $detail->price = $goods->price;
                $detail->number = intval($v);
                if ($old) {
                    $level = UserLevel::findOne($user['level_id']);
                    if ($level['number']) {
                        $detail->number2 = $detail->number * $level['number'];
                    }
                }
                $detail->money = $money;

                if (!$detail->save()) {
                    $error = $detail->getErrors();
                    $error = reset($error);
                    throw new Exception($error);
                }
            }
            if (!UserCart::deleteAll(['in', 'id', $arr_id])) {
                throw new Exception('购物车删除失败');
            }


            $return['error'] = 0;
            $transaction->commit();
        } catch (Exception $e) {
            $return['message'] = $e->getMessage();
            Yii::warning("\r\n" . print_r($return, true) . "\r\n", 'order_add');

            $transaction->rollBack();
        }
        return $return;
    }


    public function getDetail()
    {
        return $this->hasMany(OrderDetail::className(), ['order_id' => 'id']);
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }


    //确认支付
    public static function order_paid($id)
    {
        $return = [
            'error' => 1,
            'message' => ''
        ];
        $model = Order::findOne($id);
        if ($model->status == 1 ) {
            $money = $model->money;
            if($model->type==4){
                $money=$money*0.8;
            }
            if ($model['type'] == 1 or $model['type']==4) {
                //普通订单
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    $user = User::findOne($model['user_id']);
                    //是否生成过关系链
                    $old_relation = UserRelation2::find()->where(['user_id' => $model['user_id']])->limit(1)->one();
                    if (!$old_relation) {
                        $new_relation = new UserRelation2();
                        $new_relation->user_id = $model['user_id'];
                        $new_relation->parent_id = $user->parent_id;


                        if ($user->parent_id > 0) {
                            $old_parent_relation = UserRelation2::find()->where(['user_id' => $user->parent_id])->limit(1)->one();
                            if ($old_parent_relation) {
                                $new_relation->parent_id2 = $old_parent_relation->parent_id;
                                $new_relation->level = $old_parent_relation->level + 1;
                                $new_relation->relation = $new_relation->user_id . ',' . $old_parent_relation->relation;
                            } else {
                                $new_relation->level = 1;
                                $new_relation->parent_id = 0;
                                $new_relation->relation = $new_relation->user_id;
                            }
                        } else {
                            $new_relation->parent_id = 0;
                            $new_relation->level = 1;
                            $new_relation->relation = $new_relation->user_id;
                        }

                        $parent_relation=UserRelation2::find()->where(['user_id'=>$user['parent_id']])->limit(1)->one();
                        $parent_relation_number=User::find()->where(['parent_id'=>$user['parent_id']])->andWhere(['>=','level_id',1])->count()*1;
                        $new_relation->user_id=$model['user_id'];
                        $parent_now=User::findOne($user['parent_id']);
                        if($parent_relation_number>=2){
                            $new_relation->parent_id=$user['parent_id'];
                        }else{
                            if($parent_relation['parent_id']==0){
                                $new_relation->parent_id=$user['parent_id'];
                            }else{
                                $new_relation->parent_id=$parent_relation['parent_id'];
                            }

                        }
                        if(!$new_relation->save()){
                            $error = $new_relation->getErrors();
                            $error = reset($error);
                            throw new Exception($error);
                        }

                        //上级是否出局
                        if($parent_now and $parent_now['is_leader']==0){
                            if($parent_relation_number>=1){
                                $parent_now->is_leader=1;
                                if(!$parent_now->save()){
                                    $error = $parent_now->getErrors();
                                    $error = reset($error);
                                    throw new Exception($error);
                                }
                            }
                        }
                    }


                    //推荐奖发放
                    //直推20%
                    $relation2 = UserRelation::find()->where(['user_id' => $model['user_id']])->limit(1)->one();
                    if ($relation2->parent_id > 0) {
                        $parent = User::findOne($relation2->parent_id);
                        if ($parent and $parent->level_id >= 1) {
                            $now_money = $money * 0.2;
                            if (!$parent->updateCounters(['money' => $now_money])) {
                                throw new Exception('发放推荐奖，直推失败');
                            } else {
                                $log = new UserHistory();
                                $log->type = 1;
                                $log->number = $now_money;
                                $log->status = 1;
                                $log->user_id = $parent['id'];
                                $log->content = '下级用户' . $user['mobile'] . '订单' . $model['order_number'] . '付款获得直推奖';
                                if (!$log->save()) {
                                    $error = $log->getErrors();
                                    $error = reset($error);
                                    throw new Exception($error);
                                }

                            }

                        }
                    }


                    //见单奖，按点位关系，关系链上第一个出局老板拿10%
                    $dw_relation = UserRelation2::find()->where(['user_id' => $model['user_id']])->limit(1)->one();
                    $jd_money=0;
                    if($dw_relation['parent_id']>0){
                        $level=$dw_relation['level'];
                        while ($level>0){
                            $dw_parent = User::findOne($dw_relation['parent_id']);
                            $dw_parent_children=User::find()->where(['parent_id'=>$dw_parent['id']])->andWhere(['>=','level_id',1])->count()*1;
                            if ($dw_parent and $dw_parent_children>=2) {
                                $jd_money=1;
                                //是老板发放10%见单奖,只发一次
                                if (!$dw_parent->updateCounters(['money' => $money * 0.1])) {
                                    throw new Exception('发放见单奖失败');
                                } else {
                                    $log = new UserHistory();
                                    $log->type = 10;
                                    $log->number = $money * 0.1;
                                    $log->status = 1;
                                    $log->user_id = $dw_parent['id'];
                                    $log->content = $user['mobile'] . '订单' . $model['order_number'] . '获得见单奖';
                                    if (!$log->save()) {
                                        $error = $log->getErrors();
                                        $error = reset($error);
                                        throw new Exception($error);
                                    }
                                }
                                //发放平级奖
                                $dw_parent_relation = UserRelation::find()->where(['user_id' => $dw_parent['id']])->limit(1)->one();
                                $arr_relation2 = explode(',', $dw_parent_relation['relation']);
                                foreach ($arr_relation2 as $k2 => $v2) {
                                    if($k2>0){
                                        $dw_parent_parent = User::findOne($v2);
                                            if($dw_parent_parent->level_id>=2){
                                                if (!$dw_parent_parent->updateCounters(['money' => $money * 0.1])) {
                                                    throw new Exception('发放平级失败');
                                                } else {
                                                    $log = new UserHistory();
                                                    $log->type = 11;
                                                    $log->number = $money * 0.1;
                                                    $log->status = 1;
                                                    $log->user_id = $dw_parent_parent['id'];
                                                    $log->content ='下级'. $dw_parent['mobile'] . '订单' . $model['order_number'] . '获得见单奖您获得平级奖';
                                                    if (!$log->save()) {
                                                        $error = $log->getErrors();
                                                        $error = reset($error);
                                                        throw new Exception($error);
                                                    }
                                                }
                                                break;
                                            }

                                    }

                                }

                                break;

                            }
                            if($dw_relation['parent_id']>0){
                                $dw_relation=UserRelation2::find()->where(['user_id' => $dw_relation['parent_id']])->limit(1)->one();
                                if(!$dw_relation){
                                    break;
                                }
                            }else{
                                break;
                            }

                            $level--;
                        }
                    }

                    //如果见单奖没有发放，按血缘关系再次发放见单和平级，见推拿10%的见单
                    if($jd_money==0){
                        $dw_relation = UserRelation::find()->where(['user_id' => $model['user_id']])->limit(1)->one();
                            $arr_parent=explode(',',$dw_relation['relation']);
                            foreach ($arr_parent as $k2 => $v2) {
                                if($k2==2){
                                    //见推拿
                                    $now_parent=User::findOne($v2);
                                    if($now_parent){
                                        if (!$now_parent->updateCounters(['money' => $money * 0.1])) {
                                            throw new Exception('发放见单奖失败');
                                        } else {
                                            $log = new UserHistory();
                                            $log->type = 10;
                                            $log->number = $money * 0.1;
                                            $log->status = 1;
                                            $log->user_id = $now_parent['id'];
                                            $log->content = $user['mobile'] . '订单' . $model['order_number'] . '获得见单奖';
                                            if (!$log->save()) {
                                                $error = $log->getErrors();
                                                $error = reset($error);
                                                throw new Exception($error);
                                            }
                                            //发放平级奖
                                            $dw_parent_relation = UserRelation::find()->where(['user_id' => $now_parent['id']])->limit(1)->one();
                                            $arr_relation2 = explode(',', $dw_parent_relation['relation']);
                                            foreach ($arr_relation2 as $k2 => $v2) {
                                                if($k2>0){
                                                    $dw_parent_parent = User::findOne($v2);
                                                    if($dw_parent_parent->level_id>=2){
                                                        if (!$dw_parent_parent->updateCounters(['money' => $money * 0.1])) {
                                                            throw new Exception('发放平级失败');
                                                        } else {
                                                            $log = new UserHistory();
                                                            $log->type = 11;
                                                            $log->number = $money * 0.1;
                                                            $log->status = 1;
                                                            $log->user_id = $dw_parent_parent['id'];
                                                            $log->content ='下级'. $dw_parent['mobile'] . '订单' . $model['order_number'] . '获得见单奖您获得平级奖';
                                                            if (!$log->save()) {
                                                                $error = $log->getErrors();
                                                                $error = reset($error);
                                                                throw new Exception($error);
                                                            }
                                                        }
                                                        break;
                                                    }

                                                }

                                            }
                                        }
                                    }
                                }
                            }

                    }


                    //用户升级
                    if ($user->level_id < 2) {

                        if ($model['money'] >= 19800) {
                            //升级成为合伙人
                            $user->level_id = 2;
                            if (!$user->save()) {
                                $error = $model->getErrors();
                                $error = reset($error);
                                throw new Exception($error);
                            } else {
                                //判断是否直推上级升级为董事
                                $parent = User::findOne($user['parent_id']);
                                if ($parent) {
                                    if ($parent['level_id'] >= 2 and $parent['level_id'] <= 4) {
                                        //合伙人升级为银级董事
                                        $count = User::find()->where(['parent_id' => $parent['id']])->andWhere(['>=', 'level_id', 2])->count() * 1;
                                        if ($count >= 3) {
                                            $parent->level_id = 5;
                                            $parent->level_time=date('m');
                                            $parent->save();
                                        }
                                        //上上级是否升级为金董
                                        $parent_parent=User::findOne($parent['parent_id']);
                                        if($parent_parent and $parent_parent['level_id']==5){
                                            $count_parent = User::find()->where(['parent_id' => $parent_parent['id']])->andWhere(['>=', 'level_id', 5])->count() * 1;
                                            if ($count_parent >= 3) {
                                                $parent_parent->level_id = 6;
                                                $parent_parent->level_time2=date('m');
                                                $parent_parent->save();
                                            }
                                        }

                                        //再上级升级为钻董


                                        $parent_parent_parent=User::findOne($parent_parent['parent_id']);
                                        if($parent_parent_parent and $parent_parent_parent['level_id']==6){
                                            $count_parent_parent = User::find()->where(['parent_id' => $parent_parent_parent['id']])->andWhere(['>=', 'level_id', 6])->count() * 1;
                                            if ($count_parent_parent >= 3) {
                                                $parent_parent_parent->level_id = 7;
                                                $parent_parent_parent->level_time3=date('m');
                                                $parent_parent_parent->save();
                                            }
                                        }
                                    } elseif ($parent['level_id'] == 5) {
                                        //银级董事升级为金
                                        $count = User::find()->where(['parent_id' => $parent['id']])->andWhere(['>=', 'level_id', 5])->count() * 1;
                                        if ($count >= 3) {
                                            $parent->level_id = 6;
                                            $parent->level_time2=date('m');
                                            $parent->save();
                                        }

                                        //上级升级为钻董
                                        $parent_parent=User::findOne($parent['parent_id']);
                                        if($parent_parent and $parent_parent['level_id']==6){
                                            $count_parent = User::find()->where(['parent_id' => $parent_parent['id']])->andWhere(['>=', 'level_id', 6])->count() * 1;
                                            if ($count_parent >= 3) {
                                                $parent_parent->level_id = 7;
                                                $parent_parent->level_time3=date('m');
                                                $parent_parent->save();
                                            }
                                        }
                                    } elseif ($parent['level_id'] == 6) {
                                        //金级董事升级为钻石
                                        $count = User::find()->where(['parent_id' => $parent['id']])->andWhere(['>=', 'level_id', 6])->count() * 1;
                                        if ($count >= 3) {
                                            $parent->level_id = 7;
                                            $parent->level_time3=date('m');
                                            $parent->save();
                                        }
                                    }
                                }
                            }
                        } else {
                            if ($model['money'] >= 2980 and $user->level_id == 0) {
                                $user->level_id = 1;
                                if (!$user->save()) {
                                    $error = $model->getErrors();
                                    $error = reset($error);
                                    throw new Exception($error);
                                }
                            }
                        }

                    }


                    //代理分红
                    //区级分红
                    $dl_user = User::find()->where(['dl_type' => 1, 'area' => $model['area']])->limit(1)->one();
                    if ($dl_user) {
                        if (!$dl_user->updateCounters(['money' => $money * 0.05])) {
                            throw new Exception('发放区级代理分红失败');
                        } else {
                            $log = new UserHistory();
                            $log->type = 5;
                            $log->number = $money * 0.05;
                            $log->status = 1;
                            $log->user_id = $dl_user['id'];
                            $log->content = $user['mobile'] . '订单' . $model['order_number'] . '付款获得区级代理分红';
                            if (!$log->save()) {
                                $error = $log->getErrors();
                                $error = reset($error);
                                throw new Exception($error);
                            }
                        }
                    }


                    //市级
                    $dl_user = User::find()->where(['dl_type' => 2, 'city' => $model['city']])->limit(1)->one();
                    if ($dl_user) {
                        if (!$dl_user->updateCounters(['money' => $money * 0.05])) {
                            throw new Exception('发放市级代理分红失败');
                        } else {
                            $log = new UserHistory();
                            $log->type = 5;
                            $log->number = $money * 0.05;
                            $log->status = 1;
                            $log->user_id = $dl_user['id'];
                            $log->content = $user['mobile'] . '订单' . $model['order_number'] . '付款获得市级代理分红';
                            if (!$log->save()) {
                                $error = $log->getErrors();
                                $error = reset($error);
                                throw new Exception($error);
                            }
                        }
                    }


                    $model->status = 2;
                    $model->paid_time = time();
                    $model->pay_status = 2;
                    if (!$model->save()) {
                        $error = $model->getErrors();
                        $error = reset($error);
                        throw new Exception($error);
                    }





                    $return['error'] = 0;
                    $transaction->commit();
                } catch (Exception $e) {
                    $return['message'] = $e->getMessage();
                    Yii::warning("\r\n" . print_r($error, true) . "\r\n", 'order_paid');

                    $transaction->rollBack();
                }
            } elseif ($model['type'] == 3) {
                //复购订单兑换订单,仅产生见单奖、平级奖
                $model->status = 2;

                $transaction = Yii::$app->db->beginTransaction();
                try {

                    $user = User::findOne($model['user_id']);
                    if($user->is_fh3==0){
                        $user->is_fh3=1;
                        if (!$user->save()) {
                            throw new Exception('解锁分红资格失败');
                        }
                    }elseif ($user->is_fh2==0){
                        $user->is_fh2=1;
                        if (!$user->save()) {
                            throw new Exception('解锁分红资格失败');
                        }
                    }elseif ($user->is_fh==0){
                        $user->is_fh=1;
                        if (!$user->save()) {
                            throw new Exception('解锁分红资格失败');
                        }
                    }
                    //用户升级
                    if ($user->level_id < 2) {

                        if ($model['money'] >= 19800) {
                            //升级成为合伙人
                            $user->level_id = 2;
                            if (!$user->save()) {
                                $error = $model->getErrors();
                                $error = reset($error);
                                throw new Exception($error);
                            } else {
                                //判断是否直推上级升级为董事
                                $parent = User::findOne($user['parent_id']);
                                if ($parent) {
                                    if ($parent['level_id'] >= 2 and $parent['level_id'] <= 4) {
                                        //合伙人升级为银级董事
                                        $count = User::find()->where(['parent_id' => $parent['id']])->andWhere(['>=', 'level_id', 2])->count() * 1;
                                        if ($count >= 3) {
                                            $parent->level_id = 5;
                                            $parent->level_time=date('m');
                                            $parent->save();
                                        }
                                        //上上级是否升级为金董
                                        $parent_parent=User::findOne($parent['parent_id']);
                                        if($parent_parent and $parent_parent['level_id']==5){
                                            $count_parent = User::find()->where(['parent_id' => $parent_parent['id']])->andWhere(['>=', 'level_id', 5])->count() * 1;
                                            if ($count_parent >= 3) {
                                                $parent_parent->level_id = 6;
                                                $parent_parent->level_time2=date('m');
                                                $parent_parent->save();
                                            }
                                        }

                                        //再上级升级为钻董


                                        $parent_parent_parent=User::findOne($parent_parent['parent_id']);
                                        if($parent_parent_parent and $parent_parent_parent['level_id']==6){
                                            $count_parent_parent = User::find()->where(['parent_id' => $parent_parent_parent['id']])->andWhere(['>=', 'level_id', 6])->count() * 1;
                                            if ($count_parent_parent >= 3) {
                                                $parent_parent_parent->level_id3 = 7;
                                                $parent_parent_parent->level_time=date('m');
                                                $parent_parent_parent->save();
                                            }
                                        }
                                    } elseif ($parent['level_id'] == 5) {
                                        //银级董事升级为金
                                        $count = User::find()->where(['parent_id' => $parent['id']])->andWhere(['>=', 'level_id', 5])->count() * 1;
                                        if ($count >= 3) {
                                            $parent->level_id = 6;
                                            $parent->level_time2=date('m');
                                            $parent->save();
                                        }

                                        //上级升级为钻董
                                        $parent_parent=User::findOne($parent['parent_id']);
                                        if($parent_parent and $parent_parent['level_id']==6){
                                            $count_parent = User::find()->where(['parent_id' => $parent_parent['id']])->andWhere(['>=', 'level_id', 6])->count() * 1;
                                            if ($count_parent >= 3) {
                                                $parent_parent->level_id = 7;
                                                $parent_parent->level_time3=date('m');
                                                $parent_parent->save();
                                            }
                                        }
                                    } elseif ($parent['level_id'] == 6) {
                                        //金级董事升级为钻石
                                        $count = User::find()->where(['parent_id' => $parent['id']])->andWhere(['>=', 'level_id', 6])->count() * 1;
                                        if ($count >= 3) {
                                            $parent->level_id = 7;
                                            $parent->level_time3=date('m');
                                            $parent->save();
                                        }
                                    }
                                }
                            }
                        } else {
                            if ($model['money'] >= 2980 and $user->level_id == 0) {
                                $user->level_id = 1;
                                if (!$user->save()) {
                                    $error = $model->getErrors();
                                    $error = reset($error);
                                    throw new Exception($error);
                                }
                            }
                        }

                    }


                    //见单奖
                    $parent_now=User::findOne($user['parent_id']);
                    $user_children_number=User::find()->where(['parent_id'=>$user['id']])->andWhere(['>=','level_id',1])->count()*1;
                    if($user_children_number>=2){
                        //如果是已经出局的人，直推上级拿见单奖
                        if($parent_now){
                            if (!$parent_now->updateCounters(['money' => $money * 0.1])) {
                                throw new Exception('发放见单奖失败');
                            } else {
                                $log = new UserHistory();
                                $log->type = 10;
                                $log->number = $money * 0.1;
                                $log->status = 1;
                                $log->user_id = $parent_now['id'];
                                $log->content = $user['mobile'] . '订单' . $model['order_number'] . '获得见单奖';
                                if (!$log->save()) {
                                    $error = $log->getErrors();
                                    $error = reset($error);
                                    throw new Exception($error);
                                }
                                //发放平级奖
                                $dw_parent_relation = UserRelation::find()->where(['user_id' => $parent_now['id']])->limit(1)->one();
                                $arr_relation2 = explode(',', $dw_parent_relation['relation']);
                                foreach ($arr_relation2 as $k2 => $v2) {
                                    if($k2>0){
                                        $dw_parent_parent = User::findOne($v2);
                                            if ($dw_parent_parent->level_id >= 2) {
                                                if (!$dw_parent_parent->updateCounters(['money' => $money * 0.1])) {
                                                    throw new Exception('发放平级失败');
                                                } else {
                                                    $log = new UserHistory();
                                                    $log->type = 11;
                                                    $log->number = $money * 0.1;
                                                    $log->status = 1;
                                                    $log->user_id = $dw_parent_parent['id'];
                                                    $log->content = '下级' . $parent_now['mobile'] . '订单' . $model['order_number'] . '获得见单奖您获得平级奖';
                                                    if (!$log->save()) {
                                                        $error = $log->getErrors();
                                                        $error = reset($error);
                                                        throw new Exception($error);
                                                    }
                                                    $is_pj = 1;
                                                }
                                                break;
                                            }

                                    }

                                }
                            }
                        }

                    }else{


                        //未出局的人，正常发见单奖
                        $dw_relation = UserRelation2::find()->where(['user_id' => $model['user_id']])->limit(1)->one();
                        $jd_money=0;
                        if($dw_relation['parent_id']>0){
                            $level=$dw_relation['level'];
                            while ($level>0){
                                $dw_parent = User::findOne($dw_relation['parent_id']);
                                $dw_parent_children=User::find()->where(['parent_id'=>$dw_parent['id']])->andWhere(['>=','level_id',1])->count()*1;
                                if ($dw_parent and $dw_parent_children>=2) {
                                    $jd_money=1;
                                    //是老板发放10%见单奖,只发一次
                                    if (!$dw_parent->updateCounters(['money' => $money * 0.1])) {
                                        throw new Exception('发放见单奖失败');
                                    } else {
                                        $log = new UserHistory();
                                        $log->type = 10;
                                        $log->number = $money * 0.1;
                                        $log->status = 1;
                                        $log->user_id = $dw_parent['id'];
                                        $log->content = $user['mobile'] . '订单' . $model['order_number'] . '获得见单奖';
                                        if (!$log->save()) {
                                            $error = $log->getErrors();
                                            $error = reset($error);
                                            throw new Exception($error);
                                        }
                                    }
                                    //发放平级奖
                                    $dw_parent_relation = UserRelation::find()->where(['user_id' => $dw_parent['id']])->limit(1)->one();
                                    $arr_relation2 = explode(',', $dw_parent_relation['relation']);
                                    foreach ($arr_relation2 as $k2 => $v2) {
                                        if($k2>0){
                                            $dw_parent_parent = User::findOne($v2);
                                            if($dw_parent_parent->level_id>=2){
                                                if (!$dw_parent_parent->updateCounters(['money' => $money * 0.1])) {
                                                    throw new Exception('发放平级失败');
                                                } else {
                                                    $log = new UserHistory();
                                                    $log->type = 11;
                                                    $log->number = $money * 0.1;
                                                    $log->status = 1;
                                                    $log->user_id = $dw_parent_parent['id'];
                                                    $log->content ='下级'. $dw_parent['mobile'] . '订单' . $model['order_number'] . '获得见单奖您获得平级奖';
                                                    if (!$log->save()) {
                                                        $error = $log->getErrors();
                                                        $error = reset($error);
                                                        throw new Exception($error);
                                                    }
                                                }
                                                break;
                                            }

                                        }

                                    }

                                    break;

                                }
                                if($dw_relation['parent_id']>0){
                                    $dw_relation=UserRelation2::find()->where(['user_id' => $dw_relation['parent_id']])->limit(1)->one();
                                    if(!$dw_relation){
                                        break;
                                    }
                                }else{
                                    break;
                                }

                                $level--;
                            }
                        }

                        //没人拿
                        if($jd_money==0){
                            //没人拿见单奖，直推上级拿
                            if($parent_now){
                                if (!$parent_now->updateCounters(['money' => $money * 0.1])) {
                                    throw new Exception('发放见单奖失败');
                                } else {
                                    $log = new UserHistory();
                                    $log->type = 10;
                                    $log->number = $money * 0.1;
                                    $log->status = 1;
                                    $log->user_id = $parent_now['id'];
                                    $log->content = $user['mobile'] . '订单' . $model['order_number'] . '获得见单奖';
                                    if (!$log->save()) {
                                        $error = $log->getErrors();
                                        $error = reset($error);
                                        throw new Exception($error);
                                    }
                                    //发放平级奖
                                    $dw_parent_relation = UserRelation::find()->where(['user_id' => $parent_now['id']])->limit(1)->one();
                                    $arr_relation2 = explode(',', $dw_parent_relation['relation']);
                                    foreach ($arr_relation2 as $k2 => $v2) {
                                        if($k2>0){
                                            $dw_parent_parent = User::findOne($v2);
                                                if ($dw_parent_parent->level_id >= 2) {
                                                    if (!$dw_parent_parent->updateCounters(['money' => $money * 0.1])) {
                                                        throw new Exception('发放平级失败');
                                                    } else {
                                                        $log = new UserHistory();
                                                        $log->type = 11;
                                                        $log->number = $money * 0.1;
                                                        $log->status = 1;
                                                        $log->user_id = $dw_parent_parent['id'];
                                                        $log->content = '下级' . $parent_now['mobile'] . '订单' . $model['order_number'] . '获得见单奖您获得平级奖';
                                                        if (!$log->save()) {
                                                            $error = $log->getErrors();
                                                            $error = reset($error);
                                                            throw new Exception($error);
                                                        }
                                                        $is_pj = 1;
                                                    }
                                                    break;
                                                }
                                        }

                                    }
                                }
                            }
                        }

                    }


                    $model->status = 2;
                    $model->paid_time = time();
                    $model->pay_status = 2;
                    if (!$model->save()) {
                        $error = $model->getErrors();
                        $error = reset($error);
                        throw new Exception($error);
                    }


                    $return['error'] = 0;
                    $transaction->commit();
                } catch (Exception $e) {
                    $return['message'] = $e->getMessage();
                    Yii::warning("\r\n" . print_r($error, true) . "\r\n", 'order_paid');

                    $transaction->rollBack();
                }


            } elseif ($model['type'] == 2) {
                //积分兑换订单，不发放奖励
                $model->status = 2;
                $model->save();
            }
        } else {
            $return['message'] = '订单状态不正确';
        }


        return $return;
    }


    //发放分红

    public static function order_money()
    {
        //分红金额
        $return = [
            'error' => 1,
            'message' => ''
        ];
        //发放上月分红
        $time=strtotime(date('Y-m-01',time()))-1;
        $start=strtotime(date('Y-m-01 00:00:00',$time));
        $money = Order::find()->where(['is_fh' => 0])->andWhere(['in','type',[1,3]])->andWhere(['>=', 'status', 2])->andWhere(['<=','created_at',$time])->andWhere(['>=','created_at',$start])->sum('money') * 0.05;

        //type=4的类型订单，金额只算80%
        $money2= Order::find()->where(['type' => 4, 'is_fh' => 0])->andWhere(['>=', 'status', 2])->andWhere(['<=','created_at',$time])->andWhere(['>=','created_at',$start])->sum('money') * 0.8*0.05;
        $money=$money2+$money;
        //上月
        $month=date('m',$time);
        if($month==1){
            User::updateAll(['level_time'=>0],['level_time'=>12]);
            User::updateAll(['level_time2'=>0],['level_time2'=>12]);
            User::updateAll(['level_time3'=>0],['level_time3'=>12]);

        }
        $time_month=$month;
        $transaction = Yii::$app->db->beginTransaction();
        try {

            //银董
            $model_count = User::find()->where(['is_fh'=>1])->andWhere(['>=','level_id',5])->andWhere(['<','level_time',$time_month])->count() * 1;
            if ($model_count > 0) {
                $now_money = round($money / $model_count, 2);
                $model_user = User::find()->where(['is_fh'=>1])->andWhere(['>=','level_id',5])->andWhere(['<','level_time',$time_month])->all();
                foreach ($model_user as $k => $v) {
                    $user = User::findOne($v['id']);
                    if (!$user->updateCounters(['money' => $now_money])) {
                        throw new Exception('发放全盘分红失败1');
                    } else {
                        $user->level_time=0;
                        $user->fh_money+=$now_money;
                        if($user->fh_money>=10000){
                            $user->is_fh=0;
                            $user->fh_money=0;
                        }
                        if(!$user->save()){
                            throw new Exception('发放全盘分红失败2');
                        }
                        $log = new UserHistory();
                        $time = date('m');
                        $log->type = 6;
                        $log->number = $now_money;
                        $log->status = 1;
                        $log->user_id = $user['id'];
                        $log->content = '发放' . $time . '月银董分红';
                        if (!$log->save()) {
                            $error = $log->getErrors();
                            $error = reset($error);
                            throw new Exception($error);
                        }

                    }

                }

            }

            //金
            $model_count = User::find()->where(['is_fh2'=>1])->andWhere(['>=','level_id',6]) ->andWhere(['<','level_time2',$time_month])->count() * 1;
            if ($model_count > 0) {
                $now_money = round($money / $model_count, 2);
                $model_user = User::find()->where(['is_fh2'=>1])->andWhere(['>=','level_id',6])->andWhere(['<','level_time2',$time_month])->all();
                foreach ($model_user as $k => $v) {
                    $user = User::findOne($v['id']);
                    if (!$user->updateCounters(['money' => $now_money])) {
                        throw new Exception('发放全盘分红失败3');
                    } else {
                        $user->level_time2=0;
                        $user->fh_money2+=$now_money;
                        if($user->fh_money2>=10000){
                            $user->is_fh2=0;
                            $user->fh_money2=0;
                        }
                        if(!$user->save()){
                            throw new Exception('发放全盘分红失败4');
                        }
                        $log = new UserHistory();
                        $time = date('m');
                        $log->type = 6;
                        $log->number = $now_money;
                        $log->status = 1;
                        $log->user_id = $user['id'];
                        $log->content = '发放' . $time . '月金董分红';
                        if (!$log->save()) {
                            $error = $log->getErrors();
                            $error = reset($error);
                            throw new Exception($error);
                        }

                    }

                }

            }


            //钻石
            $model_count = User::find()->where(['is_fh3'=>1])->andWhere(['>=','level_id',7])->andWhere(['<','level_time3',$time_month])->count() * 1;
            if ($model_count > 0) {
                $now_money = round($money / $model_count, 2);
                $model_user = User::find()->where(['is_fh3'=>1])->andWhere(['>=','level_id',7])->andWhere(['<','level_time3',$time_month])->all();
                foreach ($model_user as $k => $v) {
                    $user = User::findOne($v['id']);
                    if (!$user->updateCounters(['money' => $now_money])) {
                        throw new Exception('发放全盘分红失败');
                    } else {
                        $user->level_time3=0;
                        $user->fh_money3+=$now_money;
                        if($user->fh_money3>=10000){
                            $user->is_fh3=0;
                            $user->fh_money3=0;
                        }
                        if(!$user->save()){
                            throw new Exception('发放全盘分红失败');
                        }
                        $log = new UserHistory();
                        $time = date('m');
                        $log->type = 6;
                        $log->number = $now_money;
                        $log->status = 1;
                        $log->user_id = $user['id'];
                        $log->content = '发放' . $time . '月钻石董分红';
                        if (!$log->save()) {
                            $error = $log->getErrors();
                            $error = reset($error);
                            throw new Exception($error);
                        }

                    }

                }

            }


            $time=strtotime(date('Y-m-01',time()))-1;
            $re = Order::updateAll(['is_fh' => 1],['<=','created_at',$time]);
            if (!$re) {
                throw new Exception('更新失败');
            }


            $return['error'] = 0;
            $transaction->commit();
        } catch (Exception $e) {
            $return['message'] = $e->getMessage();
            $transaction->rollBack();
        }

        return $return;


    }




    //计算业绩,首次先处理不发钱
    public static function order_statistics2()
    {

        User::updateAll(['all_money'=>0]);
        $order=Order::find()->where(['is_statistics'=>0])->andWhere(['>=','status',2])->andWhere(['in','type',[1,3,4]])->all();
        $user=[];
        foreach ($order as $k => $v) {
            $relation=UserRelation::find()->where(['user_id'=>$v['user_id']])->limit(1)->one();
            if($relation){
                $arr_user=explode(',',$relation['relation']);
                if($v['type']==4){
                    $money=$v->money*0.8;
                }else{
                    $money=$v->money;
                }
                foreach ($arr_user as $k1 => $v1) {
                    if(isset($user[$v1])){
                        $user[$v1]=$user[$v1]+$money;
                    }else{
                        $user[$v1]=$money;
                    }
                }

            }
        }
        foreach ($user as $k=>$v){
            $now_user=User::findOne($k);
            if($now_user){
                $now_user->all_money+=$v;
                $now_user->save();
            }
        }

        Order::updateAll(['is_statistics'=>1]);

    }


    //计算新订单业绩,每月
    public static function order_statistics()
    {


        $data=[
            'error'=>1,
            'message'=>''
        ];
        //避免计算时候有订单
        $time=time();
        $order=Order::find()->where(['is_statistics'=>0])->andWhere(['>=','status',2])->andWhere(['in','type',[1,3,4]])->andWhere(['<','created_at',$time])->all();
        $user=[];
        foreach ($order as $k => $v) {
            $relation=UserRelation::find()->where(['user_id'=>$v['user_id']])->limit(1)->one();
            if($relation){
                $arr_user=explode(',',$relation['relation']);
                if($v['type']==4){
                    $money=$v->money*0.8;
                }else{
                    $money=$v->money;
                }
                foreach ($arr_user as $k1 => $v1) {
                    if(isset($user[$v1])){
                        $user[$v1]=$user[$v1]+$money;
                    }else{
                        $user[$v1]=$money;
                    }
                }

            }
        }
        foreach ($user as $k=>$v){
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $now_user=User::findOne($k);
                if($now_user){
                    $now_user->all_money+=$v;
                    $now_user->month_money+=$v;
                    if(!$now_user->save()){
                        throw new Exception('用户id'.$now_user->id.'团队金额增加失败');
                    }
                }
                $transaction->commit();
                Order::updateAll(['is_statistics'=>1],['and',['<','created_at',$time],['>=','status',2]]);
                $data['error']=0;
            } catch (Exception $e) {
                $return['message'] = $e->getMessage();
                Yii::warning("\r\n" . print_r($return, true) . "\r\n", 'order_tuandui_money');

                $transaction->rollBack();
                $data['message']='计算团队金额失败';
            }
        }
        return $data;
    }




    public static function order_statistics3()
    {


        User::updateAll(['month_money'=>0]);
        $data=[
            'error'=>1,
            'message'=>''
        ];
        //避免计算时候有订单
        $time=time();
        $start=strtotime(date('Y-m-01 00:00:00'));
        $order=Order::find()->where(['>=','status',2])->andWhere(['in','type',[1,3,4]])->andWhere(['<','created_at',$time])->andWhere(['>=','created_at',$start])->all();
        $user=[];
        foreach ($order as $k => $v) {
            $relation=UserRelation::find()->where(['user_id'=>$v['user_id']])->limit(1)->one();
            if($relation){
                $arr_user=explode(',',$relation['relation']);
                if($v['type']==4){
                    $money=$v->money*0.8;
                }else{
                    $money=$v->money;
                }
                foreach ($arr_user as $k1 => $v1) {
                    if(isset($user[$v1])){
                        $user[$v1]=$user[$v1]+$money;
                    }else{
                        $user[$v1]=$money;
                    }
                }

            }
        }
        foreach ($user as $k=>$v){
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $now_user=User::findOne($k);
                if($now_user){
                    $now_user->month_money+=$v;
                    if(!$now_user->save()){
                        throw new Exception('用户id'.$now_user->id.'团队金额增加失败');
                    }
                }
                $transaction->commit();
                Order::updateAll(['is_statistics'=>1],['<','created_at',$time]);
                $data['error']=0;
            } catch (Exception $e) {
                $return['message'] = $e->getMessage();
                Yii::warning("\r\n" . print_r($return, true) . "\r\n", 'order_tuandui_money');

                $transaction->rollBack();
                $data['message']='计算团队金额失败';
            }
        }
        return $data;
    }



    //发放团队奖
    public static function group_money()
    {

        $data=[
            'error'=>1,
            'message'=>''
        ];

        //本月的先不发
        $time=strtotime(date('Y-m-01'))-1;
        $order=Order::find()->where(['is_statistics'=>1])->andWhere(['>=','status',2])->andWhere(['in','type',[1,3,4]])->andWhere(['>=','paid_time',$time])->all();

        $user=[];
        foreach ($order as $k => $v) {
            $relation=UserRelation::find()->where(['user_id'=>$v['user_id']])->limit(1)->one();
            if($relation){
                $arr_user=explode(',',$relation['relation']);
                if($v['type']==4){
                    $money=$v->money*0.8;
                }else{
                    $money=$v->money;
                }
                foreach ($arr_user as $k1 => $v1) {
                    if(isset($user[$v1])){
                        $user[$v1]=$user[$v1]+$money;
                    }else{
                        $user[$v1]=$money;
                    }
                }

            }
        }

        foreach ($user as $k=>$v){
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $now_user=User::findOne($k);
                if($now_user){
                    $now_user->all_money-=$v;
                    $now_user->month_money-=$v;
                    if(!$now_user->save()){
                        throw new Exception('用户id'.$now_user->id.'团队金额减少失败');
                    }
                }
                $transaction->commit();
                Order::updateAll(['is_statistics'=>0],['>=','created_at',$time]);
                $data['error']=0;
            } catch (Exception $e) {
                $return['message'] = $e->getMessage();
                Yii::warning("\r\n" . print_r($return, true) . "\r\n", 'order_tuandui_money');

                $transaction->rollBack();
                $data['message']='计算团队金额失败';
            }
        }




        $users=User::find()->where(['>','month_money',0])->andWhere(['>=','level_id',2])->andWhere(['>=','all_money',Order::$min_money])->all();
        $money_type=Order::$money_type;
        $user=[];
        $user_number=count($users);
        if($user_number==0){
            $data['message']='没有需要发放的人员';
            return $data;
        }

        foreach ($users as $k => $v) {

                //分成百分比
                $money_number=0;
                foreach ($money_type as $k1 => $v1) {
                    if($v['all_money']>$k1){
                        $money_number=$v1;
                    }
                }
                $money=$v['month_money']*$money_number/100;

                $children=User::find()->where(['parent_id'=>$v['id']])->andWhere(['>=','all_money',Order::$min_money])->all();
                foreach ($children as $k1 => $v1) {
                    $children_money_number=0;

                    foreach ($money_type as $k2 => $v2) {
                        if($v1['all_money']>$k2){
                            $children_money_number=$v2;
                        }
                    }
                    $money=$money-$v1['month_money']*$children_money_number/100;
                }
                $user[$v['id']]=$money;


        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            foreach ($user as $k=>$v){
                if($v>0){
                    $now_user=User::findOne($k);
                    if($now_user){
                        if (!$now_user->updateCounters(['money' => $v])) {
                            throw new Exception('用户id'.$now_user->id.'团队发放团队奖失败');
                        }
                        $log = new UserHistory();
                        $log->type = 12;
                        $log->number = $v;
                        $log->status = 1;
                        $log->user_id = $k;
                        $log->content = $now_user['mobile'].'获得团队奖';
                        if (!$log->save()) {
                            $error = $log->getErrors();
                            $error = reset($error);
                            throw new Exception($error);
                        }
                    }
                }

            }
            $transaction->commit();
            User::updateAll(['month_money'=>0]);
            $data['error']=0;
        } catch (Exception $e) {
            $return['message'] = $e->getMessage();
            Yii::warning("\r\n" . print_r($return, true) . "\r\n", 'order_tuandui_fafang');
            $transaction->rollBack();
            $data['message']='发放团队金额失败';
        }


        Order::order_statistics();

        return $data;

    }

}

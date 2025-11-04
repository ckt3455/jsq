<?php

namespace backend\models;

use common\components\Helper;
use Yii;

use yii\base\BaseObject;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Exception;
use dosamigos\qrcode\QrCode;
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
//            ['mobile', 'match', 'pattern' => '/^1[3-9]\d{9}$/','message'=>'手机号格式不正确'],
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
            'code' => '邀请码',
            'parent_id' => '直推用户',
            'money' => '余额',
            'created_at' => '添加时间',
            'updated_at' => 'Updated At',
            'is_buy' => 'Is Buy',
            'image' => '头像',
            'name' => '名称',
            'level_id' => '用户等级',
            'integral' => '积分',
            'dl_type' => '代理资格',
            'city' => '城市',
            'area' => '地区',
            'is_leader' => '是否老板',
            'month_money' => '未发团队奖业绩',
            'all_money' => '团队总业绩',
            'level_time' => '升级银董时间',
            'level_time2' => '升级金董时间',
            'level_time3' => '升级钻董时间',
            'is_fh' => '银董',
            'is_fh2' => '金董',
            'is_fh3' => '钻董',
        ];
    }

    public function beforeSave($insert)

    {


        if ($this->isAttributeChanged('password')) {


            $this->password = md5(md5($this->password) . 'ysdf');


        }

        if ($this->isAttributeChanged('money')) {

            $this->money = $this->getOldAttribute('money');

        }
        if ($this->isAttributeChanged('code')) {

            $this->user_code = "";

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


    //保存后事件
    public function afterSave($insert, $changedAttributes)
    {
        //$changedAttributes  要改变的字段，未改变的值
        //$this->字段名  改变保存的值
        parent::afterSave($insert, $changedAttributes);
        if ($insert) {
            $code = $this->id * 1000 + 10024 + date('d') * 100 + date('s');
            User::updateAll(['code' => $code], ['id' => $this->id]);
            //新增处理
            $new = new UserRelation();
            $new->user_id = $this->id;
            $new->parent_id = $this->parent_id;
            if ($this->parent_id > 0) {
                $old = UserRelation::find()->where(['user_id' => $this->parent_id])->limit(1)->one();
                if ($old) {
                    $new->relation = $new->user_id . ',' . $old->relation;
                    $new->level = $old->level + 1;
                } else {
                    $new->relation = $new->user_id;
                    $new->level = 1;
                    $new->parent_id = 0;
                }
            } else {
                $new->parent_id = 0;
                $new->relation = $new->user_id;
                $new->level = 1;
            }
            $new->save();
            //后台直接创建的用户
            if ($this->level_id > 0) {
                //后台创建的用户
                $old = UserRelation2::find()->where(['user_id' => $this->id])->limit(1)->one();
                $new2 = new UserRelation2();
                $new2->user_id = $this->id;
                $new2->parent_id = 0;
                $new2->relation = $new->user_id;
                $new2->level = 1;
                $new2->save();
            }
        }
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


    //更新用户是否可提现
    public static function tx($user_id){
        $user=User::findOne($user_id);
        if($user['is_tx']==0){
            $children=UserRelation::find()->andWhere('FIND_IN_SET(' .$user_id . ',relation)')->all();
            $number=0;
            foreach ($children as $k=>$v){
                if($v['user_id']!=$user_id){
                    $now_user=User::findOne($v['user_id']);
                    if($now_user['level_id']>=1){
                        $number++;
                        if($number>=6){
                            $user->is_tx=1;
                            $user->save();
                            break;
                        }
                    }
                }
            }
        }
        return true;
    }


    //关联用户等级
    public function  getLevel(){
        return $this->hasOne(UserLevel::className(),['id'=>'level_id']);
    }


    //发起提现
    public static function add_money($user_id, $money,$type=1)
    {
        $return = [
            'error' => 1,
            'message' => ''
        ];
        User::tx($user_id);
        $user=User::findOne($user_id);
//        if($user->is_tx==0){
//            $return['message']='您还未有提现资格';
//            return $return;
//        }
        $card=UserCard::find()->where(['user_id'=>$user_id])->limit(1)->one();
        if(!$card){
            $return['message']='提现账户未设置';
            return $return;
        }
        if($type==1 and !$card->bank_name){
            $return['message']='请先填写银行卡账号';
            return $return;
        }

        if($type==2 and !$card->zfb_name){
            $return['message']='请先填写支付宝账号';
            return $return;
        }
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $user=User::findOne($user_id);
            if (!$user) {
                throw new Exception('用户不存在');
            }
            $fee=Yii::$app->config->info2('FEE');//提现手续费
            if($user->money<$money){
                throw new Exception('用户金额不足');
            }
            if(!$user->updateCounters(['money'=>-$money])){
                throw new Exception('扣减金额失败');
            };
            $log=new UserHistory();
            $log->status=2;
            $log->content='提现扣减';
            $log->number=-$money;
            $log->type = 7;
            $log->user_id = $user_id;
            if (!$log->save()) {
                $error = $log->getErrors();
                $error = reset($error);
                throw new Exception($error);
            }

            $apply=new UserApply();
            $apply->type=$type;
            $apply->money=$money;
            $apply->fee=$money*$fee/100;
            $apply->user_id=$user_id;
            $apply->bank=$card->bank;
            $apply->bank_name=$card->bank_name;
            $apply->bank_number=$card->bank_number;
            $apply->zfb_name=$card->zfb_name;
            $apply->zfb_number=$card->zfb_number;
            if (!$apply->save()) {
                $error = $log->getErrors();
                $error = reset($error);
                throw new Exception($error);
            }
            $return['error'] = 0;
            $transaction->commit();
        } catch (Exception $e) {
            $return['message'] = $e->getMessage();
            Yii::warning("\r\n" . print_r($return, true) . "\r\n", 'tixian');
            $transaction->rollBack();
        }
        return $return;
    }

    public function getUserMoney(){
        $number=Order::find()->where(['user_id'=>$this->id])->andWhere(['in','type',[1,3]])->andWhere(['>=','status',2])->sum('money')*1;
        return $number;
    }

    public function getUserCount(){
        $number=Order::find()->where(['user_id'=>$this->id])->andWhere(['in','type',[1,3]])->andWhere(['>=','status',2])->count()*1;
        return $number;
    }



    public function getJdUser()
    {
        $user_relation=UserRelation2::find()->where((['user_id'=>$this->id]))->limit(1)->one();
        if($user_relation){

            $level=$user_relation['level'];
            while ($level>0){
                if($user_relation['parent_id']==0){
                    break;
                }
                $dw_parent = User::findOne($user_relation['parent_id']);
                $dw_parent_children=User::find()->where(['parent_id'=>$dw_parent['id']])->andWhere(['>=','level_id',1])->count()*1;
                if ($dw_parent and $dw_parent_children>=2) {
                    return $dw_parent->name.'-'.$dw_parent->mobile;
                }
                if($user_relation['parent_id']>0){
                    $user_relation=UserRelation2::find()->where(['user_id' => $user_relation['parent_id']])->limit(1)->one();
                    if(!$user_relation){
                        break;
                    }
                }else{
                    break;
                }

                $level--;
            }
        }
        return '';

    }




}

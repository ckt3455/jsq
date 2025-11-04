<?php

namespace backend\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
/**
 * This is the model class for table "{{%sku}}".
 *
 * @property integer $id
 * @property integer $uid
 * @property integer $sku_id
 * @property integer $code_id
 * @property string $sku_title
 * @property double $factory_price
 * @property double $cost_price
 * @property integer $min_number
 * @property integer $period
 * @property integer $status
 * @property string $gross_weight
 */
class Quotation extends \yii\db\ActiveRecord
{
    const status_1 = 0;//待报价
    const status_2 = 1;//受理中
    const status_3 = 2;//已报价
    const status_4 = 3;//已下单

    public static $status = [
        self::status_1 => "待报价",
        self::status_2 => "受理中",
        self::status_3 => "已报价",
        self::status_4 => "已下单",
    ];

    const type_1 = 0;//普通报价
    const type_2 = 1;//非标定制报价

    public static $type = [
        self::type_1 => "普通报价",
        self::type_2 => "非标定制报价",
    ];

    public static $user_type = [
        0 => "正常",
        1 => "已删除",
        2=>'已撤销'
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%quotation}}';
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id','type','name','phone','uid'], 'required'],
            [['type', 'is_call', 'append', 'updated', 'uid'], 'integer'],
            [['datas', 'datas2', 'file', 'content', 'name', 'phone', ], 'string'],
            ['expiration','safe'],
            [['name'], 'string', 'max' => 50],
            [['file'], 'string', 'max' => 500],
            [['order_id'], 'string', 'max' => 100],
            [['phone'],'string', 'max' => 20],
            [['status','is_call','type','delect'],'default','value'=>0]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'uid' => '用户ID',
            'order_id' => '报价单单号',
            'type' => '报价单类型',
            'datas' => '产品信息',
            'datas2' => '产品信息报价',
            'file' => '清单文件',
            'content'=>'备注',
            'is_call'=>'是否电话回复',
            'name'=>'联系人',
            'phone'=>'联系电话',
            'status'=>'状态',
            'delect'=>'删除状态',
            'expiration'=>'到期时间',
            'append'=>'添加时间',
            'updated'=>'修改时间',
        ];
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
                    ActiveRecord::EVENT_BEFORE_INSERT => ['append', 'updated'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated'],
                ],
            ],
        ];
    }

    public static function getNumber($user_id,$where=[],$overdue = '')
    {
        $where2=[];
        if($overdue == 'overdue')
        {
            $where2=['and',['<>','status',0],['<','expiration',time()],['>','expiration',0]];
        }
        $count = self::find()->where(['uid' => $user_id])->andFilterWhere($where)->andFilterWhere($where2)->count();

        return $count;
    }

    public function getDetail(){
        return $this->hasMany(QuotationDetail::className(), ['quotation_id' => 'id']);
    }

    public function getUser(){
        return $this->hasOne(ProvinceUser::className(),['id'=>'uid']);
    }

    public function beforeSave($insert)
    {
        if ($this->isNewRecord) {

        } else {
           if($this->getOldAttribute('status')==0 or $this->getOldAttribute('status')==1){
               if($this->status==2){
                    $message=new UserMessage();
                    $message->user_id=$this->uid;
                    $message->title='客服已报价';
                    $message->content='您的报价单'.$this->order_id.'已报价';
                    $message->url='quotation/details.html?id='.$this->id;
                    $message->save();
               }
           }
        }
        return parent::beforeSave($insert);
    }
}

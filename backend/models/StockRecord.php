<?php namespace backend\models;
/** * This is the model class for table "{{%stock_record}}". * * @property integer $id * @property integer $sku_id * @property integer $order_id * @property integer $number * @property integer $append * @property string $content */
class StockRecord extends \yii\db\ActiveRecord
{
    /**     * @inheritdoc */
    public static function tableName()
    {
        return '{{%stock_record}}';
    }

    /**     * @inheritdoc */
    public function rules()
    {
        return [[['sku_id'], 'required'], [['sku_id', 'order_id', 'number', 'append', 'type'], 'integer'], [['content'], 'string', 'max' => 255],];
    }

    /**     * @inheritdoc */
    public function attributeLabels()
    {
        return ['id' => 'ID', 'sku_id' => 'Sku', 'order_id' => '订单', 'number' => '数量', 'append' => '发生时间', 'content' => '备注', 'type' => '类型'];
    }

    /**     * 自动插入     */
    public function beforeSave($insert)
    {
        if ($this->isNewRecord) {
            $this->append = time();
        }
        return parent::beforeSave($insert);
    }

    /**     * 发货，扣减库存     */
    public static function is_paid($order_id)
    {
        $order = Order::findOne($order_id);
        foreach ($order->detail as $k=>$v){
            $sku=Sku::findOne($v->sku_id);
            if($sku->inventory<$v->number){
                return false;
            }
        }        //库存充足，添加记录扣减库存
        foreach ($order->detail as $k=>$v){
            $sku = Sku::findOne($v->sku_id);  if($sku) {
                $record = new StockRecord();
                $record->sku_id = $v->id;
                $record->order_id = $order_id;
                $record->number = $v->number;
                $record->content = '订单' . $order->order_number . '扣减';
                $record->type = 2;
                if ($record->save()) {
                    $sku->inventory = $sku->inventory - $v->number;
                    $sku->save();
                } else {
                    return false;
                }
            }
        }        //添加用户发货信息
      $message=new UserMessage();
        $message->user_id=$order->user_id;
        $message->title='订单发货';
        $message->content='订单号'.$order->order_number.'订单发货';
        $message->url="user/order-detail.html?id=".$order->id;
        $message->save();
        return true;
    }
    /**     * 关联sku     */
    public function getSku()    {
        return $this->hasOne(Sku::className(), ['id' => 'sku_id']);
    }
    /**     * 关联订单     */
    public function getOrder()    {  
        return $this->hasOne(Order::className(), ['id' => 'order_id']);
    }}
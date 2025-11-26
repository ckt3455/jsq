<?php

namespace backend\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%user_evaluate}}".
 *
 * @property integer $id
 * @property string $image
 * @property integer $service_order_id
 * @property integer $worker_id
 * @property integer $user_id
 * @property string $content
 * @property integer $number1
 * @property integer $number2
 * @property integer $number3
 * @property integer $created_at
 * @property integer $updated_at
 */
class UserEvaluate extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_evaluate}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['service_order_id', 'worker_id', 'user_id', 'number1', 'number2', 'number3', 'created_at', 'updated_at'], 'integer'],
            [['image', 'content'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'image' => 'Image',
            'service_order_id' => 'Service Order ID',
            'worker_id' => 'Worker ID',
            'user_id' => 'User ID',
            'content' => 'Content',
            'number1' => 'Number1',
            'number2' => 'Number2',
            'number3' => 'Number3',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
    * @return array
    */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
            ],
        ];
    }

    public function getOrder()
    {
        return $this->hasOne(ServiceOrder::class,['id'=>'service_order_id']);

    }


    public function getWorker()
    {
        return $this->hasOne(Worker::class,['id'=>'worker_id']);

    }
}

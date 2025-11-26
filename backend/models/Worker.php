<?php

namespace backend\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%worker}}".
 *
 * @property integer $id
 * @property string $worker_name
 * @property string $worker_phone
 * @property string $worker_image
 */
class Worker extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%worker}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['worker_name', 'worker_image'], 'string', 'max' => 255],
            [['worker_phone'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'worker_name' => 'Worker Name',
            'worker_phone' => 'Worker Phone',
            'worker_image' => 'Worker Image',
        ];
    }

}

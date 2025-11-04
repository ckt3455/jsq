<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%freight}}".
 *
 * @property integer $id
 * @property integer $model_id
 * @property string $city_id
 * @property string $first
 * @property string $first_money
 * @property string $next
 * @property string $next_money
 */
class Freight extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%freight}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['city_id','model_id'], 'required'],
            [['first', 'first_money', 'next', 'next_money'], 'number'],
            [['city_id'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'model_id'=>'模版id',
            'city_id' => '包含的城市id',
            'first' => '首件或首重',
            'first_money' => '首件或首重价格',
            'next' => '续重或续件',
            'next_money' => '续重或续件价格',
        ];
    }
}

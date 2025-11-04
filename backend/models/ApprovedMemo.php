<?php

namespace backend\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%approved_memo}}".
 *
 * @property string $id
 * @property string $user_id
 * @property integer $year
 * @property integer $month
 * @property string $day1
 * @property string $day2
 * @property string $day3
 * @property string $day4
 * @property string $day5
 * @property string $number
 * @property string $created_at
 * @property string $updated_at
 * @property string $admin_id
 * @property string $parent_id
 */
class ApprovedMemo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%approved_memo}}';
    }


    public function behaviors()
    {
        return [
            TimestampBehavior::className(),

        ];
    }


    public static $status_message=[
        2=>'待上级评价',
        3=>'待考核',
        4=>'待确认',
        5=>'已完成'
    ];

    public static $status_color=[
        2=>'bgfa fs12 ml10',
        3=>'bge1 fs12 ml10',
        4=>'c1c fs12 ml10',
        5=>'c1c fs12 ml10'

    ];

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'year', 'month', 'day1', 'day2', 'day3', 'day4', 'day5', 'number', 'created_at', 'updated_at','admin_id','parent_id'], 'integer'],
            [['content','content2','content3'],'safe']
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
            'year' => '年',
            'month' => '月',
            'day1' => '应出勤',
            'day2' => '实际上班',
            'day3' => '加班',
            'day4' => '请假',
            'day5' => '调休',
            'number' => '考核得分',
            'created_at' => '添加时间',
            'updated_at' => '修改时间',
            'parent_id'=>'上级',
            'admin_id'=>'打分人',
            'status'=>'状态',
            'content'=>'自我评价',
            'content2'=>'上级评价'
        ];
    }
}

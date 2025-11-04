<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%action_log}}".
 *
 * @property string $id
 * @property string $action_id
 * @property string $user_id
 * @property string $action_ip
 * @property string $model
 * @property string $record_id
 * @property string $remark
 * @property integer $status
 * @property string $append
 */
class ActionLog extends \yii\db\ActiveRecord
{
    const  ACTION_LOGIN    = 1;//登陆
    const  ACTION_LOGOUT   = 2;//退出

    const  ACTION_101 = 101;
    const  ACTION_102 = 102;

    const  ACTION_400 = 400;//ip不正确

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%action_log}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['action_id', 'user_id', 'action_ip', 'record_id', 'status', 'append'], 'integer'],
            [['action_ip'], 'required'],
            [['model'], 'string', 'max' => 50],
            [['remark','log_url'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'        => 'ID',
            'action_id' => 'Action ID',
            'user_id'   => '用户ID',
            'action_ip' => 'IP',
            'model'     => '触发行为的表',
            'record_id' => '触发行为的数据id',
            'remark'    => '说明',
            'status'    => '状态',
            'append'    => '创建时间',
        ];
    }


    /**
     * @param $action_id
     * @param $model
     * @param null $record_id
     * 插入日志
     */
    public function addLog($action_id,$model,$remark=NULL,$record_id = NULL)
    {
        //行为id
        !$record_id && $record_id = 0;

        $logModel = new ActionLog();
        //判断是否登录
        if (!\Yii::$app->user->isGuest)
        {
            $logModel->user_id      = Yii::$app->user->identity->id;
            $logModel->username     = Yii::$app->user->identity->username;
        }
        $logModel->action_ip    = ip2long(Yii::$app->request->userIP);
        $logModel->action_id    = $action_id;
        $logModel->model        = $model;
        $logModel->record_id    = $record_id;
        $logModel->log_url      = Yii::$app->request->getUrl();
        $logModel->remark       = $remark;
        $add = $logModel->save();
    }

    /**
     * @param $action_id
     * @return mixed
     * 备注信息
     */
    static public function remarkBehavior($action_id)
    {
        $behavior = [
            '1'       => "登陆",
            '2'       => "退出",
            '101'     => "新增/修改",
            '102'     => "删除",
            '400'     => "ip不正确",
        ];

        return $behavior[$action_id];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getManager()
    {
        return $this->hasOne(Manager::className(), ['id' => 'user_id']);
    }


    /**
     * @param bool $insert
     * @return bool
     * 自动插入
     */
    public function beforeSave($insert)
    {
        if($this->isNewRecord)
        {
            $this->append = time();
        }

        return parent::beforeSave($insert);
    }
}

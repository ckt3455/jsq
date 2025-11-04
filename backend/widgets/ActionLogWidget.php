<?php
/**
 * 一些站点统计widget
 */
namespace backend\widgets;

use yii;
use yii\base\Widget;
use backend\models\ActionLog;

class ActionLogWidget extends Widget
{
    public function run()
    {
        //登陆日志查询
        $logs = ActionLog::find()->where(['action_id'=>ActionLog::ACTION_LOGIN])->orderBy('id desc')->limit(5)->all();

        return $this->render('logs/login', [
            'logs' => $logs
        ]);
    }
}

?>
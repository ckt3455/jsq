<?php
/**
 * 一些站点统计widget
 */
namespace backend\widgets;

use yii;
use yii\base\Widget;
use common\models\User;
use backend\models\ActionLog;
use backend\models\Manager;

class SiteStatWidget extends Widget
{
    public function run()
    {
        $userCount = Manager::find()->count();
        $logCount  = ActionLog::find()->count();
        $managerVisitor  = Manager::find()->sum('visit_count');

        return $this->render('site/sitestat', [
            'userCount'         => $userCount,
            'logCount'          => $logCount,
            'managerVisitor'    => $managerVisitor,
        ]);
    }
}

?>
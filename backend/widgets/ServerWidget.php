<?php
/**
 * 一些服务器信息widget
 */
namespace backend\widgets;

use yii;
use yii\base\Widget;

class ServerWidget extends Widget
{
    public function run()
    {
        return $this->render('site/server', [
        ]);
    }
}

?>
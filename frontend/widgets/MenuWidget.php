<?php

namespace frontend\widgets;

use backend\models\Brand;
use backend\models\GoodsCategory;
use backend\models\Server;
use backend\models\SetImage;
use yii;
use yii\base\Widget;

class MenuWidget extends Widget
{
    public function run()
    {


        return $this->render('menu/index', [



        ]);
    }
}

?>
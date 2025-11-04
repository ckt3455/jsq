<?php

namespace frontend\widgets;
use backend\models\Article;
use backend\models\ArticleType;
use backend\models\GoodsCategory;
use backend\models\Server;
use backend\models\SetImage;
use yii\base\Widget;
use Yii;

class FooterWidget extends Widget
{
    public function run()
    {
        return $this->render('footer', [


        ]);
    }
}

?>
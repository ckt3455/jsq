<?php

namespace frontend\widgets;

use Yii;
use yii\base\Widget;

class UserWidget extends Widget
{
    public function run()
    {


        return $this->render('user');


    }

}

?>
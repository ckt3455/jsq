<?php

namespace backend\actions;

use yii;

class CreateAction extends \yii\base\Action
{

    public $modelClass;

    public $scenario = 'default';

    public $data = [];

    /** @var string 模板路径，默认为action id  */
    public $viewFile = null;

    /** @var  string|array 编辑成功后跳转地址,此参数直接传给yii::$app->controller->redirect() */
    public $successRedirect;

    /**
     * create创建页
     *
     * @return string|\yii\web\Response
     */
    public function run()
    {
        /* @var $model yii\db\ActiveRecord */
        $model = new $this->modelClass;
        $model->setScenario( $this->scenario );
        if (yii::$app->getRequest()->getIsPost()) {
            if ($model->load(yii::$app->getRequest()->post()) && $model->save()) {
                return $this->controller->render('/layer/close');
            } else {
                $errors = $model->getErrors();
                $err = '';
                foreach ($errors as $v) {
                    $err .= $v[0] . '<br>';
                }
                Yii::$app->getSession()->setFlash('error', $err);
            }
        }
        $model->loadDefaultValues();
        $data = [
            'model' => $model,
        ];
        if( is_array($this->data) ){
            $data = array_merge($data, $this->data);
        }elseif ($this->data instanceof \Closure){
            $data = call_user_func_array($this->data, [$model, $this]);
        }
        $this->viewFile === null && $this->viewFile = $this->id;
        return $this->controller->render($this->viewFile, $data);
    }

}
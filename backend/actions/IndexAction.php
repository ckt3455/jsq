<?php


namespace backend\actions;

use \Closure;
use Yii;
use yii\helpers\Json;


class IndexAction extends \yii\base\Action
{

    public $modelClass;//模型class

    public $scenario = 'default';//场景

    public $data;//回调数据

    /** @var $viewFile string 模板路径，默认为action id  */
    public $viewFile = null;


    public function run()
    {
        $data = $this->data;
        if( $data instanceof Closure){
            $data = call_user_func( $this->data );
        }
        if (Yii::$app->request->post('hasEditable')) {
            $id = Yii::$app->request->post('editableKey');
            $model = call_user_func([$this->modelClass, 'findOne'], $id);
            $name=$model->formName();
            $posted = current(Yii::$app->request->post("$name"));
            $post = ["$name" => $posted];
            //异步编辑数据
            if ($model->load($post) and $model->validate()) {
                if($model->save()){
                    $output = '';
                    foreach ($posted as $k=>$v){

                        //图片
                        if(strpos($k,'image_')!==false){
                            $img=$model->$k;
                            $output = "<img src='$img' width='50px'>";
                        }
                        //标签
                        if(strpos($k,'sign_')!==false){
                            $array = explode(',',$model->$k);
                            $output = '';
                            foreach ($array as $k2 => $v2) {
                                $output .= Yii::$app->params["$k"]["$v2"]['sign'];
                            }
                        }
                        //类型
                        if(strpos($k,'type_')!==false){
                            $array = explode(',',$model->$k);
                            $output = '';
                            foreach ($array as $k2 => $v2) {
                                $output .= Yii::$app->params["$k"]["$v2"]['type'];
                            }
                        }
                    }
                    $out = Json::encode(['output' => $output, 'message' => '']);
                }else{
                    $error=$model->getErrors();
                    $out = Json::encode(['message' =>reset($error)]);
                }
            }
            else{
                $error=$model->getErrors();
                $out = Json::encode(['message' =>reset($error)]);
            }
            return $out;
        }
        $this->viewFile === null && $this->viewFile = $this->id;
        return $this->controller->render($this->viewFile, $data);
    }

}
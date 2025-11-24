<?php

namespace backend\controllers;

use api\extensions\ApiBaseController;
use api\services\IconQueryService;
use api\services\UserGoodsQueryService;
use backend\models\Icon;
use backend\models\UserGoods;
use Yii;

/**
 * DefaultController controller
 */
class IconController extends ApiBaseController
{

    /**
     * 设备列表
     **/
    public function actionList()
    {
        $params = Yii::$app->request->post();
        // 自定义验证规则
        $customRules = [];
        $rules = $this->getRules(['admin_id','type'], $customRules);
        $validate = $this->validateParams($params, $rules);
        if ($validate) {
            return $this->jsonError($validate);
        }
        $params = Yii::$app->request->post();
        $goods = IconQueryService::searchProducts($params);
        return $this->jsonSuccess($goods);
    }


    public function actionDetail()
    {
        $params = Yii::$app->request->post();
        // 自定义验证规则
        $customRules = [
            [['icon_id'], 'required', 'message' => '图片id不能为空'],
            [['image'], 'required', 'message' => '图片不能为空'],
        ];
        $rules = $this->getRules(['admin_id'], $customRules);
        $validate = $this->validateParams($params, $rules);
        if ($validate) {
            return $this->jsonError($validate);
        }
        $data['detail'] =IconQueryService::get_one($params['icon_id']);;

        return $this->jsonSuccess($data);
    }


    public function actionUpdate()
    {
        $params = Yii::$app->request->post();
        $goods_id = YII::$app->request->post('icon_id');

        // 自定义验证规则
        $customRules = [
            [['icon_id'], 'required', 'message' => 'icon_id不能为空'],
        ];
        $rules = $this->getRules(['admin_id'], $customRules);
        $validate = $this->validateParams($params, $rules);
        if ($validate) {
            return $this->jsonError($validate);
        }

        $model = Icon::findOne($goods_id);

        $model->setAttributes($params);
        $data=[
            'message'=>'修改成功'
        ];
        if(!$model->save()){
            return $this->jsonError('修改失败');
        }


        return $this->jsonSuccess($data);
    }



    public function actionAdd()
    {
        $params = Yii::$app->request->post();

        // 自定义验证规则
        $customRules = [
            [['icon_id'], 'required', 'message' => 'icon_id不能为空'],
            [['image'], 'required', 'message' => '图片不能为空'],
            [['type'], 'required', 'message' => '类型为空'],
        ];
        $rules = $this->getRules(['admin_id'], $customRules);
        $validate = $this->validateParams($params, $rules);
        if ($validate) {
            return $this->jsonError($validate);
        }

        $model = new Icon();

        $model->setAttributes($params);
        if($model->type==8 or $model->type==9 or $model->type==10){
            return $this->jsonError('该图片不允许添加');
        }
        $data=[
            'message'=>'添加成功'
        ];
        if(!$model->save()){
            return $this->jsonError('修改失败');
        }


        return $this->jsonSuccess($data);
    }


    public function actionDelete()
    {
        $params = Yii::$app->request->post();

        // 自定义验证规则
        $customRules = [
            [['icon_id'], 'required', 'message' => 'icon_id不能为空'],
        ];
        $rules = $this->getRules(['admin_id'], $customRules);
        $validate = $this->validateParams($params, $rules);
        if ($validate) {
            return $this->jsonError($validate);
        }

        $model = Icon::findOne($params['icon_id']);
        if(!$model){
            return $this->jsonError('找不到图片');
        }
        if($model->type==8 or $model->type==9 or $model->type==10){
            return $this->jsonError('该图片不允许删除');
        }
        $data=[
            'message'=>'删除成功'
        ];
        if(!$model->delete()){
            return $this->jsonError('修改失败');
        }


        return $this->jsonSuccess($data);
    }


}

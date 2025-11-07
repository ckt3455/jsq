<?php

namespace api\controllers;

use api\extensions\ApiBaseController;
use backend\models\SetImage;
use backend\models\User;
use Yii;
/**
 * DefaultController controller
 */
class UserController extends ApiBaseController
{

    /**
     * 用户信息
     **/
    public function actionInfo()
    {
        $params = Yii::$app->request->post();
        $rules = [
            [['user_id'], 'required', 'message' => '{attribute}属必填项'],
        ];
        $validate = $this->validateParams($params, $rules);
        if ($validate) {
            return $this->jsonError($validate);
        }
        $user_id=$params['user_id'];
        $user=User::findOne($user_id);
        if(!$user){
            return $this->jsonError('用户不存在');
        }else{
            $data=[
                'user_id'=>$user_id,
                'name'=>$user['name'],
                'mobile'=>$user['mobile'],
                'image'=>$this->setImg($user['image']),
            ];
        }



        return $this->jsonSuccess($data);
    }

}

<?php

namespace api\controllers;

use api\extensions\ApiBaseController;
use backend\models\Code;
use backend\models\SetImage;
use common\components\Helper;
use common\exception\ApiException;
use Yii;
/**
 * DefaultController controller
 */
class IndexController extends ApiBaseController
{

    /**
     * 首页
     * **/
    public function actionIndex()
    {

        $data = [];
        $banner=SetImage::getList(['type' => 1]);
        foreach ($banner as $k=>$v){
            $data['banner'][]=[
                'image'=>$this->setImg($v['image']),
                'href'=>$v['href'],
            ];
        }

        return $this->jsonSuccess($data);
    }

    public function actionCode()
    {

        $params = Yii::$app->request->post();
        $rules = [
            [['mobile'], 'match', 'pattern' => '/^1[3-9]\d{9}$/','message'=>'手机号格式不正确'],
        ];
        $validate = $this->validateParams($params, $rules);
        if ($validate) {
            return $this->jsonError($validate);
        }
        $mobile=$params['mobile'];
        $model = Code::find()->where(['phone' => $mobile])->one();
        $number = rand(10000, 99999);
        if (count($model) > 0) {

            if ((time() - $model['create_time']) <= 60) {

                return $this->jsonError('短信发送太频繁，请等待1分钟');

            } else {

                $model['number'] = $number;

                $model['phone'] = "$mobile";

                $model['expire_time'] = time() + 300;

                $model['create_time'] = time();

            }

        } else {

            $model = new Code();

            $model['number'] = $number;

            $model['phone'] = "$mobile";

            $model['expire_time'] = time() + 300;

            $model['create_time'] = time();

        }

        if ($model->save()) {



            $re = Helper::phpSendMessage($mobile, $number);

            if (!$re) {
                return $this->jsonError('发送失败1');

            }

        } else {

            return $this->jsonError('发送失败2');

        }


        $data=[
            'message'=>'短信发送成功'
        ];

        return $this->jsonSuccess($data);
    }


    /**
     * 异常入口
     * **/
    public function actionError() {
        return $this->jsonError();
    }
}

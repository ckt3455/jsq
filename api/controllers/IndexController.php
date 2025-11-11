<?php

namespace api\controllers;
use api\extensions\ApiBaseController;
use backend\models\Code;
use backend\models\Message;
use backend\models\SetImage;
use common\components\File;
use common\components\Helper;
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

    //发送验证码
    public function actionCode()
    {

        $params = Yii::$app->request->post();
        // 自定义验证规则
        $customRules = [];
        $rules = $this->getRules(['mobile'],$customRules);
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



    //单页书籍
    public function actionMessage()
    {
        $params = Yii::$app->request->post();
        // 自定义验证规则
        $customRules = [];
        $rules = $this->getRules(['type'],$customRules);
        $validate = $this->validateParams($params, $rules);
        if ($validate) {
            return $this->jsonError($validate);
        }

        $model=Message::find()->where(['type'=>$params['type']])->limit(1)->one();

        $data=[
            'message'=>Helper::imageUrl($model->content,Yii::$app->request->hostInfo)
        ];
        return $this->jsonSuccess($data);

    }


    /**
     * 异常入口
     * **/
    public function actionError() {
        return $this->jsonError();
    }


    //上传图片

    public function actionUpImage()
    {
        if(!isset($_FILES['file'])){
            return $this->jsonError('请上传数据');
        }
        $image = File::UpOneFile($_FILES['file'],array('jpg', 'jpeg', 'gif', 'bmp', 'png'));
        if($image['error']!=0){
            return $this->jsonError($image['msg']);
        }
        $data=[
            'url'=>$this->setImg($image['url'])
        ];
        return $this->jsonSuccess($data);
    }
}

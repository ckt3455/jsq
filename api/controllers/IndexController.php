<?php

namespace api\controllers;
use api\extensions\ApiBaseController;
use backend\models\Code;
use backend\models\Icon;
use backend\models\Message;
use backend\models\UserGoods;
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

        $data = [
            'banner'=>[],
            'banner2'=>[],
            'icon'=>[],
            'goods'=>[],
        ];
        $banner=Icon::getList(['type' => 1]);
        $banner2=Icon::getList(['type' => 2]);
        $icon=Icon::getList(['type' => 3]);
        foreach ($banner as $k=>$v){
            $data['banner'][]=[
                'image'=>$this->setImg($v['image']),
                'href'=>$v['href'],
                'category'=>$v['category'],
                'appid'=>$v['appid'],
            ];
        }

        foreach ($banner2 as $k=>$v){
            $data['banner2'][]=[
                'image'=>$this->setImg($v['image']),
                'href'=>$v['href'],
                'category'=>$v['category'],
                'appid'=>$v['appid'],
            ];
        }
        foreach ($icon as $k=>$v){
            $data['icon'][]=[
                'image'=>$this->setImg($v['image']),
                'href'=>$v['href'],
                'title'=>$v['title'],
                'subtitle'=>$v['subtitle'],
                'category'=>$v['category'],
                'appid'=>$v['appid'],
            ];
        }
        $user_id=Yii::$app->request->post('user_id');
        if($user_id){
            $goods=UserGoods::find()->where(['user_id'=>$user_id,'is_index'=>1])->orderBy('id desc')->limit(5)->all();
            foreach ($goods as $k => $v) {
                $data['goods'][] = [
                    'goods_id' => $v->id,
                    'goods_name' => $v->goods_name,
                    'goods_code' => $v->goods_code,
                    'end_days' => $v->end_days,
                    'lx_end_days' => $v->lx_end_days,
                    'lx_alert' => $v->lx_alert,
                    'goods_image' => $this->setImg($v->goods_image),
                    'lx_status' => $v->lx_status,
                ];
            }
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
            $re = Helper::sendSms2($mobile, $number);
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



    //单页详情
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

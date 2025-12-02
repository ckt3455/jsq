<?php

namespace api\controllers;

use api\extensions\ApiBaseController;
use backend\models\Code;
use backend\models\Icon;
use backend\models\Message;
use backend\models\Order;
use backend\models\OrderDetail;
use backend\models\ServiceOrder;
use backend\models\UserGoods;
use backend\models\Worker;
use common\components\File;
use common\components\Helper;
use common\components\SzApi;
use common\components\WdtClient;
use Detail;
use Yii;
use yii\web\Response;
use function AlibabaCloud\Client\json;

/**
 * DefaultController controller
 */
class IndexController extends ApiBaseController
{


    public function actionTest()
    {

        echo date('Y-m-d H:i:s');
        $new = new WdtClient();
        $new->appkey = Yii::$app->params['appkey'];
        $new->appsecret = Yii::$app->params['appsecret'];
        $new->sid = Yii::$app->params['sid'];
        $new->gatewayUrl = 'https://sandbox.wangdian.cn/openapi2/stockout_order_query_trade.php';

        $new->putApiParam('status', 55);
        $end=date('Y-m-d H:i:s',time()-60);
        $start=date('Y-m-d H:i:s',time()-10*24*3600);
        $new->putApiParam('start_time', $start);
        $new->putApiParam('end_time', $end);
        $new->putApiParam('page_no', '0');
        $new->putApiParam('page_size', '100');
        $json = $new->wdtOpenApi();
        $data_message = json_decode($json, true);
        if ($data_message['code'] == 0) {
            foreach ($data_message['stockout_list'] as $k => $v) {
                $old=Order::find()->where(['order_number'=>$v['src_order_no']])->one();
                if(!$old){
                    $new=new Order();
                    $new->order_number=$v['src_order_no'];
                    $new->phone=$v['receiver_mobile'];
                    $new->contact=$v['receiver_name'];
                    $new->province=$v['receiver_province'];
                    $new->city=$v['receiver_city'];
                    $new->area=$v['receiver_district'];
                    $new->money=$v['receivable'];
                    $new->address=$v['receiver_address'];
                    $new->trade_no=$v['trade_no'];
                    if(!$new->save()){
                        echo '发生错误';
                    }else{
                        foreach ($v['details_list'] as $k1=>$v1){
                            $new_detail=new OrderDetail();
                            $new_detail->order_id=$new->id;
                            $new_detail->goods_title=$v1['goods_name'];
                            $new_detail->goods_code=$v1['goods_no'];
                            $new_detail->number=intval($v1['goods_count']);
                            if(!$new_detail->save()){
                                $errors=$new_detail->getErrors();
                                print_r($errors);
                                $new->delete();
                                break;
                            }
                        }
                    }
                }
            }
        }

        echo 'success';
        exit();
    }

    /**
     * 首页
     * **/
    public function actionIndex()
    {

        $data = [
            'banner' => [],
            'banner2' => [],
            'icon' => [],
            'goods' => [],
        ];
        $banner = Icon::getList(['type' => 1]);
        $banner2 = Icon::getList(['type' => 2]);
        $icon = Icon::getList(['type' => 3]);
        foreach ($banner as $k => $v) {
            $data['banner'][] = [
                'image' => $this->setImg($v['image']),
                'href' => $v['href'],
                'category' => $v['category'],
                'appid' => $v['appid'],
            ];
        }

        foreach ($banner2 as $k => $v) {
            $data['banner2'][] = [
                'image' => $this->setImg($v['image']),
                'href' => $v['href'],
                'category' => $v['category'],
                'appid' => $v['appid'],
            ];
        }
        foreach ($icon as $k => $v) {
            $data['icon'][] = [
                'image' => $this->setImg($v['image']),
                'href' => $v['href'],
                'title' => $v['title'],
                'subtitle' => $v['subtitle'],
                'category' => $v['category'],
                'appid' => $v['appid'],
            ];
        }
        $user_id = Yii::$app->request->post('user_id');
        if ($user_id) {
            $goods = UserGoods::find()->where(['user_id' => $user_id, 'is_index' => 1])->orderBy('id desc')->limit(5)->all();
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
        $rules = $this->getRules(['mobile'], $customRules);
        $validate = $this->validateParams($params, $rules);
        if ($validate) {
            return $this->jsonError($validate);
        }
        $mobile = $params['mobile'];
        $model = Code::find()->where(['phone' => $mobile])->one();
        $number = rand(10000, 99999);
        if ($model) {
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


        $data = [
            'message' => '短信发送成功'
        ];

        return $this->jsonSuccess($data);
    }


    //单页详情
    public function actionMessage()
    {
        $params = Yii::$app->request->post();
        // 自定义验证规则
        $customRules = [];
        $rules = $this->getRules(['type'], $customRules);
        $validate = $this->validateParams($params, $rules);
        if ($validate) {
            return $this->jsonError($validate);
        }

        $model = Message::find()->where(['type' => $params['type']])->limit(1)->one();

        $data = [
            'message' => Helper::imageUrl($model->content, Yii::$app->request->hostInfo)
        ];
        return $this->jsonSuccess($data);

    }


    /**
     * 异常入口
     * **/
    public function actionError()
    {
        return $this->jsonError();
    }


    //上传图片

    public function actionUpImage()
    {
        if (!isset($_FILES['file'])) {
            return $this->jsonError('请上传数据');
        }
        $image = File::UpOneFile($_FILES['file'], array('jpg', 'jpeg', 'gif', 'bmp', 'png'));
        if ($image['error'] != 0) {
            return $this->jsonError($image['msg']);
        }
        $data = [
            'url' => $this->setImg($image['url'])
        ];
        return $this->jsonSuccess($data);
    }

}

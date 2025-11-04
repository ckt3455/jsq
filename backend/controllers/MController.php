<?php
namespace backend\controllers;
use Yii;
use yii\filters\AccessControl;
use common\controllers\BaseController;

class MController extends BaseController
{
    public          $enableCsrfValidation = false;//csrf验证
    public          $_pageSize = 10;        //分页大小

    /**
     * @inheritdoc
     * 独立动作
     */
    public function actions()
    {
        return [
            //错误提示跳转页面
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'upload' => [
                'class' => 'kucha\ueditor\UEditorAction',
                'config' => [
                    //图片
                    "imageUrlPrefix"  => Yii::getAlias("@attachurl"),//图片访问路径前缀
                    "imagePathFormat" => "/upload/image/{yyyy}/{mm}/{dd}/{time}{rand:6}", //上传保存路径
                    "imageRoot"       => Yii::getAlias("@attachment"),//根目录地址
                    "scrawlRoot"=> Yii::getAlias("@attachment"),
                    "imageManagerListPath"=>"/attachment/images/",
                    //视频
                    "videoUrlPrefix"  => Yii::getAlias("@attachurl"),
                    "videoPathFormat" => "/upload/video/{yyyy}/{mm}/{dd}/{time}{rand:6}",
                    "videoRoot"       => Yii::getAlias("@attachment"),
                    //文件
                    "fileUrlPrefix"  => Yii::getAlias("@attachurl"),
                    "filePathFormat" => "/upload/file/{yyyy}/{mm}/{dd}/{time}{rand:6}",
                    "fileRoot"       => Yii::getAlias("@attachment"),
                ],
            ]
        ];
    }

    /**
     * 行为控制
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],//登录
                    ],
                ],
            ],
        ];
    }

    /**
     * @param \yii\base\Action $action
     * @return bool
     * RBAC验证
     */
    public function beforeAction($action)
    {
        //验证是否登录
        if (!\Yii::$app->user->isGuest)
        {
            return true;
            //验证是否超级管理员
            if(Yii::$app->user->identity->id === Yii::$app->params['adminAccount'])
            {
                return true;
            }
        }

        if (!parent::beforeAction($action))
        {
            return false;
        }

        //控制器
        $controller = Yii::$app->controller->id;
        //方法
        $action = Yii::$app->controller->action->id;
        $permissionName = $controller.'/'.$action;

        if(!Yii::$app->user->can($permissionName) && Yii::$app->getErrorHandler()->exception === null)
        {
            throw new \yii\web\UnauthorizedHttpException('对不起，您现在还没获此操作的权限');
        }

        return true;
    }

    /**
     * @param $msgText  -错误内容
     * @param $skipUrl  -跳转链接
     * @param $msgType  -提示类型
     * @param int $closeTime -提示关闭时间
     * @return mixed
     * 错误提示信息
     */
    public function message($msgText,$skipUrl,$msgType="",$closeTime=5)
    {
        $closeTime = (int)$closeTime;

        //如果是成功的提示则默认为3秒关闭时间
        if(!$closeTime && $msgType == "success" || !$msgType)
        {
            $closeTime = 3;
        }

        $html = $this->hintText($msgText,$closeTime);

        switch ($msgType)
        {
            case "success" :

                Yii::$app->getSession()->setFlash('success',$html);

                break;

            case "error" :

                Yii::$app->getSession()->setFlash('error',$html);

                break;

            case "info" :

                Yii::$app->getSession()->setFlash('info',$html);

                break;

            case "warning" :

                Yii::$app->getSession()->setFlash('warning',$html);

                break;

            default :

                Yii::$app->getSession()->setFlash('success',$html);

                break;
        }

        return $skipUrl;
    }

    /**
     * @param $msg
     * @param $closeTime
     * @return string
     */
    public function hintText($msg,$closeTime)
    {
        $text = $msg." <span class='closeTimeYl'>".$closeTime."</span>秒后自动关闭...";
        return $text;
    }





}
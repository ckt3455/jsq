<?php
namespace common\controllers;

use yii;
use yii\web\UploadedFile;
/**
 * Class FileBaseController
 * @package backend\controllers
 * 文件图片上传控制器
 */
abstract class FileBaseController extends yii\web\Controller
{
    public $enableCsrfValidation = false;//关闭csrf验证
    /**
     * 图片上传方法
     */
    public function actionUploadImages()
    {
        //错误状态表
        $stateMap = Yii::$app->params['uploadState'];
        //默认返回状态
        $result = [];
        $result['flg'] = 2;
        $result['msg'] = $stateMap['ERROR_UNKNOWN'];

        $file = $_FILES['file'];
        if ($_FILES['file'])
        {
            $file_size  = $file['size'];//图片大小
            $file_name  = $file['name'];//图片原名称
            $file_exc   = $this->getFileExt($file_name);//图片后缀

            if($file_size > Yii::$app->params['imagesUpload']['imgMaxSize'])//判定图片大小是否超出限制
            {
                $result['msg'] = $stateMap['ERROR_SIZE_EXCEED'];
            }
            else if(!$this->checkType($file_exc))//检测图片类型
            {
                $result['msg'] = $stateMap['ERROR_TYPE_NOT_ALLOWED'];
            }
            else
            {
                //相对路径
                $filePath = $this->getImgPath().Yii::$app->params['imagesUpload']['imgPrefix'].$this->random(10).$file_exc;
                //上传
                $UploadFile     = UploadedFile::getInstanceByName('file');//利用yii2自带的上传图片
                $file_status    = $UploadFile->saveAs(Yii::getAlias("@attachment/").$filePath);//保存图片

                if($file_status == true)
                {
                    $result['flg'] = 1;
                    $result['msg'] = "上传成功";
                    $result['imgName']  = $filePath;//图片上传地址
                    $result['url']  = Yii::getAlias("@attachurl/").$filePath;
                    $result['state']  = 'success';
                }
            }
        }

        echo json_encode($result);
    }




    /**
     * base64编码的图片上传
     * 头像上传
     */
    public function actionUploadBase64Img()
    {
        $result = [];
        $base64Data = $_POST['img'];
        $img = base64_decode($base64Data);
        $file_exc   = ".jpg";//图片后缀
        $file_path      = $this->getImgPath();//图片路径
        $file_new_name  = Yii::$app->params['imagesUpload']['imgPrefix'].$this->random(10).$file_exc;//保存的图片名

        $filePath = Yii::getAlias("@attachment/").$file_path.$file_new_name;
        //移动文件
        if (!(file_put_contents($filePath, $img) && file_exists($filePath))) //移动失败
        {
            $result['flg'] = 2;
            $result['msg'] = "上传失败";
        }
        else //移动成功
        {
            $result['flg'] = 1;
            $result['msg'] = "上传成功";
            $result['imgName']  = $file_path.$file_new_name;//图片上传地址
            $result['imgPath']  = Yii::getAlias("@attachurl/").$file_path.$file_new_name;
            $result['url']  = Yii::getAlias("@attachurl/").$file_path.$file_new_name;
        }

        echo json_encode($result);
    }

    /**
     * 上传文件方法
     */
    public function actionUploadVideos()
    {
//错误状态表
        $stateMap = Yii::$app->params['uploadState'];
        //默认返回状态
        $result = [];
        $result['flg'] = 2;
        $result['msg'] = $stateMap['ERROR_UNKNOWN'];

        $file = $_FILES['file'];
        if ($_FILES['file'])
        {
            $file_size  = $file['size'];//图片大小
            $file_name  = $file['name'];//图片原名称
            $file_exc   = $this->getFileExt($file_name);//图片后缀

            if($file_size > Yii::$app->params['fileUpload']['MaxSize'])//判定图片大小是否超出限制
            {
                $result['msg'] = $stateMap['ERROR_SIZE_EXCEED'];
            }
            else if(!$this->checkType2($file_exc))//检测类型
            {
                $result['msg'] = $stateMap['ERROR_TYPE_NOT_ALLOWED'];
            }
            else
            {
                //相对路径
                $filePath = $this->getImgPath().Yii::$app->params['imagesUpload']['imgPrefix'].$this->random(10).$file_exc;
                //上传
                $UploadFile     = UploadedFile::getInstanceByName('file');//利用yii2自带的上传图片
                $file_status    = $UploadFile->saveAs(Yii::getAlias("@attachment/").$filePath);//保存图片

                if($file_status == true)
                {
                    $result['flg'] = 1;
                    $result['msg'] = "上传成功";
                    $result['imgName']  = $filePath;//图片上传地址
                    $result['url']  = Yii::getAlias("@attachurl/").$filePath;
                    $result['state']  = 'success';
                    $result['type']=2;
                }
            }
        }

        echo json_encode($result);
    }



    public function actionUploadFiles()
    {
//错误状态表
        $stateMap = Yii::$app->params['uploadState'];
        //默认返回状态
        $result = [];
        $result['flg'] = 2;
        $result['msg'] = $stateMap['ERROR_UNKNOWN'];

        $file = $_FILES['file'];
        if ($_FILES['file'])
        {
            $file_size  = $file['size'];//图片大小
            $file_name  = $file['name'];//图片原名称
            $file_exc   = $this->getFileExt($file_name);//图片后缀

            if($file_size > Yii::$app->params['fileUpload']['MaxSize'])//判定图片大小是否超出限制
            {
                $result['msg'] = $stateMap['ERROR_SIZE_EXCEED'];
            }
            else
            {
                //相对路径
                $filePath = $this->getImgPath().Yii::$app->params['imagesUpload']['imgPrefix'].$this->random(10).$file_exc;
                //上传
                $UploadFile     = UploadedFile::getInstanceByName('file');//利用yii2自带的上传图片
                $file_status    = $UploadFile->saveAs(Yii::getAlias("@attachment/").$filePath);//保存图片

                if($file_status == true)
                {
                    $result['flg'] = 1;
                    $result['msg'] = "上传成功";
                    $result['imgName']  = $filePath;//图片上传地址
                    $result['url']  = Yii::getAlias("@attachurl/").$filePath;
                    $result['state']  = 'success';
                    $result['type']=4;
                }
            }
        }

        echo json_encode($result);
    }


    /**
     * @param null $Thumb 是否获取缩略图地址
     * @return string
     * 获取文件路径
     */
    public function getImgPath($thumb = null)
    {
        $file_path   = empty($thumb) ? Yii::$app->params['imagesUpload']['imgPath'] : Yii::$app->params['imagesUpload']['imgThumbPath'];//图片路径
        $ImgSubName  = Yii::$app->params['imagesUpload']['imgSubName'];//图片子路径
        $path    = $file_path.date($ImgSubName,time())."/";
        $addPath = Yii::getAlias("@attachment/").$path;
        $this->mkdirs($addPath);//创建路径

        return $path;
    }

    /**
     * 获取文件扩展名
     * @return string
     */
    public function getFileExt($fileName)
    {
        return strtolower(strrchr($fileName, '.'));
    }

    /**
     * 文件类型检测
     * @return bool
     */
    private function checkType($ext)
    {
        return in_array($ext, Yii::$app->params['imagesUpload']['imgMaxExc']);
    }



    private function checkType2($ext)
    {
        return in_array($ext, Yii::$app->params['fileUpload']['MaxExc']);
    }

    /**
     * 获取随机字符串
     */
    public function random($length, $numeric = FALSE)
    {
        $seed = base_convert(md5(microtime() . $_SERVER['DOCUMENT_ROOT']), 16, $numeric ? 10 : 35);
        $seed = $numeric ? (str_replace('0', '', $seed) . '012340567890') : ($seed . 'zZ' . strtoupper($seed));

        if ($numeric)
        {
            $hash = '';
        }
        else
        {
            $hash = chr(rand(1, 26) + rand(0, 1) * 32 + 64);
            $length--;
        }

        $max = strlen($seed) - 1;
        for ($i = 0; $i < $length; $i++)
        {
            $hash .= $seed{mt_rand(0, $max)};
        }
        return $hash;
    }

    /**
     * 检测目录并循环创建目录
     * @param $path
     */
    public function mkdirs($path)
    {
        if (!file_exists($path))
        {
            $this->mkdirs(dirname($path));
            mkdir($path, 0777);
        }
    }
}
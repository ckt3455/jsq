<?php
/**
 * 文件帮助类
 */

namespace common\components;


use Yii;
use crazyfd\qiniu\Qiniu;

class File
{
    /**
     * @param $data
     * @param $name
     *
     * @return string
     */
    static public function UpVideo($data,$name)
    {
        $url = '';
        if(Yii::$app->config->info('ISQINIU') == 2) {
            $bucket = Yii::$app->config->info('Bucket');
            $domain = Yii::$app->config->info('Domain');
            $ak = Yii::$app->config->info('Ak');
            $sk = Yii::$app->config->info('Sk');

            $qiniu = new Qiniu($ak, $sk, $domain, $bucket);
            $key = date('YmdHis_') . rand('1000', '9999') . '.' . $data['type'][$name];
            $qiniu->uploadFile($data['tmp_name'][$name], $key);
            $url = 'http://' . $qiniu->getLink($key);
        }else{
            $file['type'] = $data['type'][$name];
            $file['tmp_name'] = $data['tmp_name'][$name];
            $file['name'] = $data['name'][$name];
            $error = CommonFunction::UpFile($file,'video');
            if($error['error'] == 0){
                $url = $error['url'];
            }
        }

        return $url;
    }

    /**
     * @param       $data  上传对象
     * @param       $name  字段
     * @param array $typearr 上传格式
     *
     * @return string
     */
    static public function UpFile($data,$name,$typearr=array('jpg', 'jpeg', 'gif', 'bmp', 'png','docx','doc','txt','pdf'))
    {
        $error['error'] = 1;
        $type = substr(strrchr($data['name'][$name], '.'), 1);
        if(!in_array($type,$typearr)){
            $error['msg'] = '非法文件格式';
            return $error;
        }

        if(Yii::$app->config->info('ISQINIU') == 2) {
            $bucket = Yii::$app->config->info('Bucket');
            $domain = Yii::$app->config->info('Domain');
            $ak = Yii::$app->config->info('Ak');
            $sk = Yii::$app->config->info('Sk');

            $qiniu = new Qiniu($ak, $sk, $domain, $bucket);
            $key = date('YmdHis_') . rand('1000', '9999') . '.' . $data['type'][$name];
            $qiniu->uploadFile($data['tmp_name'][$name], $key);
            $url = 'http://' . $qiniu->getLink($key);
            $error['error'] = 0;
            $error['url'] = $url;
            $error['type'] = $type;
        }else{
            $file['type'] = $data['type'][$name];
            $file['tmp_name'] = $data['tmp_name'][$name];
            $error = CommonFunction::UpFile($file);
        }

        return $error;
    }

    /**
     * base64编码的图片上传
     * 头像上传
     */
    static public function UploadBase64Img($base64Data)
    {
        $result = [];
        $img = $base64Data;
        //$img = base64_decode($base64Data);
        $file_exc   = ".jpg";//图片后缀
        $file_path      = File::getImgPath();//图片路径
        $file_new_name  = Yii::$app->params['imagesUpload']['imgPrefix'].File::random(10).$file_exc;//保存的图片名

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

        return $result;
    }

    /**
     * @param $pic_url
     * @return mixed
     * 上传远程文件
     */
    public static function DownloadFile($pic_url)
    {
        $file_exc   = File::getFileExt2($pic_url);//图片后缀
        $file_path      = File::getImgPath();//图片路径
        $file_new_name  = Yii::$app->params['imagesUpload']['imgPrefix'].File::random(10).$file_exc;//保存的图片名

        $filePath = Yii::getAlias("@attachment/").$file_path.$file_new_name;

        ob_start(); //打开输出
        readfile($pic_url); //输出图片文件
        $img = ob_get_contents(); //得到浏览器输出
        ob_end_clean(); //清除输出并关闭
        file_put_contents($filePath, $img);

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

        return $result;
    }

    /**
     * @param       $data  上传对象
     * @param       $name  字段
     * @param array $typearr 上传格式
     *
     * @return string
     */
    static public function UpOneFile($data,$typearr=array('jpg', 'jpeg', 'gif', 'bmp', 'png','docx','doc',
        'txt','pdf'))
    {
        $error['error'] = 1;
        $type = substr(strrchr($data['name'], '.'), 1);
        if($typearr!=false and !in_array($type,$typearr)){
            $error['msg'] = '非法文件格式';
            return $error;
        }

        if(Yii::$app->config->info('ISQINIU') == 2) {
            $bucket = Yii::$app->config->info('Bucket');
            $domain = Yii::$app->config->info('Domain');
            $ak = Yii::$app->config->info('Ak');
            $sk = Yii::$app->config->info('Sk');

            $qiniu = new Qiniu($ak, $sk, $domain, $bucket);
            $key = date('YmdHis_') . rand('1000', '9999') . '.' . $data['type'];
            $qiniu->uploadFile($data['tmp_name'], $key);
            $url = 'http://' . $qiniu->getLink($key);
            $error['error'] = 0;
            $error['url'] = $url;
            $error['type'] = $type;
        }else{
            $error = CommonFunction::UpFile($data);
        }

        return $error;
    }

    /**
     * @param null $Thumb 是否获取缩略图地址
     * @return string
     * 获取文件路径
     */
    static public function getImgPath($thumb = null)
    {
        $file_path   = empty($thumb) ? Yii::$app->params['imagesUpload']['imgPath'] : Yii::$app->params['imagesUpload']['imgThumbPath'];//图片路径
        $ImgSubName  = Yii::$app->params['imagesUpload']['imgSubName'];//图片子路径
        $path    = $file_path.date($ImgSubName,time())."/";
        $addPath = Yii::getAlias("@attachment/").$path;
        File::mkdirs($addPath);//创建路径

        return $path;
    }

    static public function getPath(){
        $file_path   =  Yii::$app->params['fileUpload']['Path'] ;//路径
        $SubName  = Yii::$app->params['fileUpload']['SubName'];//子路径
        $path    = $file_path.date($SubName,time())."/";
        $addPath = Yii::getAlias("@attachment/").$path;
        File::mkdirs($addPath);//创建路径
        return $path;

    }

    /**
     * 获取文件扩展名
     * @return string
     */
    static public function getFileExt($fileName)
    {
        return strtolower(strrchr($fileName, '.'));
    }

    /**
     * 获取文件扩展名
     * @return string
     */
    static public function getFileExt2($fileName)
    {
        return strtolower(strchr(strrchr($fileName, '.'),'?',true));
    }

    /**
     * 文件类型检测
     * @return bool
     */
    static private function checkType($ext)
    {
        return in_array($ext, Yii::$app->params['imagesUpload']['imgMaxExc']);
    }

    /**
     * 获取随机字符串
     */
    static public function random($length, $numeric = FALSE)
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
            $hash .= $seed[mt_rand(0, $max)];
        }
        return $hash;
    }

    /**
     * 检测目录并循环创建目录
     * @param $path
     */
    static public function mkdirs($path)
    {
        if (!file_exists($path))
        {
            File::mkdirs(dirname($path));
            mkdir($path, 0777);
        }
    }

}
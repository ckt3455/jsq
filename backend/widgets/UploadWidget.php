<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/4/24
 * Time: 14:39
 * 图片上传组件
 */

namespace backend\widgets;

use yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\InputWidget;

class UploadWidget extends InputWidget
{
    public $config = [];
    public $value  = '';

    /**
     * type bool [image:单图上传;images:多图上传]
     */
    public function init()
    {
        $_config = [
            'serverUrl'  => Url::to(['file/upload-images']),  //上传服务器地址
            'domainUrl'  => 'http://images.local/',            //图片域名 不填为当前域名
            'inputName'  => 'fileUploader',                   //提交的图片文本框名称
        ];
        $this->config = ArrayHelper::merge($_config, $this->config);
    }

    public function run()
    {
        $config = $this->config;

        if ($this->hasModel())
        {
            $inputName  = Html::getInputName($this->model, $this->attribute);
            $inputValue = Html::getAttributeValue($this->model, $this->attribute);
            $inputId    = Html::getInputId($this->model, $this->attribute);

            return $this->render('upload/picture',[
                'inputName'  => $inputName,
                'inputValue' => $inputValue,
                'inputId'    => $inputId,
                'attribute'  => $this->attribute,
                'uploadType' => $config['uploadType'],//上传类型
                'serverUrl'  => $config['serverUrl'],
                'domainUrl'  => $config['domainUrl'],
            ]);
        }
        else
        {
            return $this->render('upload/picture',[

                'inputName'  => $config['inputName'],
                'inputValue' => $this->value,
                'inputId'    => "pictrue",
                'uploadType' => $config['uploadType'],//上传类型
                'serverUrl'  => $config['serverUrl'],
                'domainUrl'  => $config['domainUrl'],
            ]);
        }

    }


}
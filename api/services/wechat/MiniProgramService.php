<?php

namespace api\services\wechat;

use api\extensions\ApiBaseService;
use common\models\config\SystemConfigModel;
use EasyWeChat\Factory;
use common\tools\Util;

/**
 * Desc 微信小程序服务类
 * @author HUI
 */
class MiniProgramService extends ApiBaseService {

    public $app = null;

    public function __construct() {
        $sys_cofing = SystemConfigModel::getDataOne(['key'=>'WECHAT_CONFIG']);
        $wechat  = json_decode($sys_cofing['content'], true);
        $config = [
            'app_id' => $wechat['app_id'],
            'secret' => $wechat['app_secret'],
            'response_type' => 'array'
        ];
        $this->app = Factory::miniProgram($config);
    }

    /**
     * 获取openid
     * * */
    public function getOpenid($code) {
        $result = $this->app->auth->session($code);
        if (isset($result['errcode']) && $result['errcode'] != 0) {
            return $this->jsonError($result['errmsg']);
        }
        return $this->jsonSuccess(['openid' => $result['openid']]);
    }

    /**
     * 获取用户手机号
     * * */
    public function getPhone($code) {
        $result = $this->app->phone_number->getUserPhoneNumber($code);
        if (isset($result['errcode']) && $result['errcode'] != 0) {
            return $this->jsonError($result['errmsg']);
        }
        return $this->jsonSuccess(['phone' => $result['phone_info']['phoneNumber']]);
    }

    /**
     * 获取微信小程序码
     * * */
    public function getWxacode($path, $scene) {
        try {
            $params = [
                'page' => $path,
                'auto_color' => false,
                'line_color' => ['r' => 0, 'g' => 0, 'b' => 0],
                'is_hyaline' => false
            ];
            
            $response = $this->app->app_code->getUnlimit($scene, $params);
            if(is_array($response) && isset($response['errcode'])){
                throw new \Exception($response['errmsg']);
            }
            if ($response instanceof \EasyWeChat\Kernel\Http\StreamResponse) {
                $result = Util::uploadWxImg($response->getBodyContents());
                if(empty($result)){
                    throw new \Exception('小程序生成异常');
                }
            } 
            return $this->resultSuccess($result);
        } catch (\Exception $exc) {
            return $this->jsonError($exc->getMessage());
        }
    }

    /**
     * 获取微信短链接
     * * */
    public function getSortLink($path) {
        $result = $this->app->short_link->getShortLink($path,'优惠券',true);
        if (isset($result['errcode']) && $result['errcode'] != 0) {
            return $this->jsonError($result['errmsg']);
        }
        return $this->jsonSuccess(['link' => $result['link']]);
    }
    
    /**
     * 获取微信长链接
     * * */
    public function getLink($path,$query) {
        $params = [
            'path' => $path,
            'query' => $query,
        ];
        $result = $this->app->url_link->generate($params);
        if (isset($result['errcode']) && $result['errcode'] != 0) {
            return $this->jsonError($result['errmsg']);
        }
        return $this->jsonSuccess(['link' => $result['url_link']]);
    }

}

<?php

namespace api\services\mall;

use api\extensions\ApiBaseService;
use common\models\mall\MallDocModel;

/**
 * Desc 文档管理服务类
 * @author WMX
 */
class MallDocService extends ApiBaseService
{
    /**
     * 详情
     * @param array $params 参数 
     * **/
    public static function detail($params)
    {
        $condition = ['and',['=', 'key', $params['key']],['=', 'state', MallDocModel::STATE_ENABLE]];
        $data = MallDocModel::getDataOne($condition);
        return self::jsonSuccess($data);
    }

}

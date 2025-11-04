<?php

namespace api\extensions;

use yii\base\Model;

/**
 * Desc 所有api请求验证器基类
 * @author HUI
 */
class ApiBaseValidator extends Model {

    public $account;
    
//    public function init() {
//        parent::init();
//    }

    public function rules() {
        return [[['account'], 'required','message'=>'{attribute}不能为空！']];
    }

}

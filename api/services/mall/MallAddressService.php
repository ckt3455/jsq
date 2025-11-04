<?php

namespace api\services\mall;

use Yii;
use common\models\mall\MallAreaModel;
use common\models\mall\MallAddressModel;
use common\tools\Xss;
use api\extensions\ApiBaseService;

/**
 * 用户地址
 */
class MallAddressService extends ApiBaseService
{

    /**
     * 获取地址
     * @param array $params 参数
     * @return bool
     */
    public static function areaList($params)
    {
        $province_code = trim($params['province_code'] ?? '');
        $city_code = trim($params['city_code'] ?? '');
        $area_code = trim($params['area_code'] ?? '');

        if (empty($province_code)) {
            $data = MallAreaModel::find()->where(['pid' => 0])->select(['id', 'name', 'code'])->asArray()->all();
            return self::jsonSuccess($data);
        }

        // 获取城市
        $prov_id = MallAreaModel::find()->where(['code' => $province_code, 'pid' => 0])->select(['id'])->scalar();
        if (empty($city_code)) {
            $data = MallAreaModel::find()->where(['pid' => $prov_id])->select(['id', 'name', 'code'])->asArray()->all();
            return self::jsonSuccess($data);
        }

        // 获取区域
        $city_id = MallAreaModel::find()->where(['code' => $city_code, 'pid'=>$prov_id])->select(['id'])->scalar();
        if (empty($area_code)) {
            $data = MallAreaModel::find()->where(['pid' => $city_id])->select(['id', 'name', 'code'])->asArray()->all();
            return self::jsonSuccess($data);
        }

        // 获取街道
        $area_id = MallAreaModel::find()->where(['code' => $area_code, 'pid' => $city_id])->select(['id'])->scalar();
        $data = MallAreaModel::find()->where(['pid' => $area_id])->select(['id', 'name','code'])->asArray()->all();
        return self::jsonSuccess($data);
    }

    /**
     * 列表
     * @param array $params
     * * */
    public static function list($params)
    {
        try {
            $user = Yii::$app->user->getInfo();
            $list = MallAddressModel::getDataAll(['user_id'=> $user['id']]);
            
            return self::jsonSuccess($list);
        } catch (\Exception $exc) {
            return self::jsonError($exc->getMessage());
        }
    }

    /**
     * 添加
     * @param array $params
     * * */
    public static function save($params)
    {
        $id = intval($params['id'] ?? 0);
        $name = Xss::remove(trim($params['name'] ?? ''));
        $phone = Xss::remove(trim($params['phone'] ?? ''));
        $province_code = Xss::remove(trim($params['province_code'] ?? ''));
        $city_code = Xss::remove(trim($params['city_code'] ?? ''));
        $area_code = Xss::remove(trim($params['area_code'] ?? ''));
        $street_code = Xss::remove(trim($params['street_code'] ?? ''));
        $address = Xss::remove(trim($params['address'] ?? ''));
        $default = intval($params['default'] ?? MallAddressModel::DEFAULT_NO);

        $user = Yii::$app->user->getInfo();
            
        if (empty($name) || mb_strlen($params['name']) > 7) {
            return self::jsonError('姓名不能为空且必须小于7个字符');
        }
        if (empty($phone) || !preg_match("/^1[3456789]{1}\d{9}$/", $phone, $ph)) {
            return self::jsonError('手机号码格式不正确');
        }
        if (empty($province_code) || empty($city_code) || empty($area_code)) {
            return self::jsonError('地址参数异常');
        }

        $codes = [$province_code, $city_code, $area_code];
        if (!empty($street_code)) {
            $codes[] = $street_code;
        }
        $areas = MallAreaModel::find()->where(['code' => $codes])->indexBy('code')->asArray()->all();
        $province = $areas[$province_code]['name'] ?? '';
        $city = $areas[$city_code]['name'] ?? '';
        $area = $areas[$area_code]['name'] ?? '';
        $street = empty($street_code) ? '' : ($areas[$street_code]['name'] ?? '');
        if (empty($province) || empty($city) || empty($area)) {
            return self::jsonError('地址参数异常');
        }

        $address = str_replace($province, '', $address);
        $address = str_replace($city, '', $address);
        $address = str_replace($area, '', $address);
        if (empty($address)) {
            return self::jsonError('详细地址不能为空');
        }
        if (mb_strlen($address) > 50) {
            return self::jsonError('地址长度不超过50个字符');
        }

        $time = date('Y-m-d H:i:s');
        $data = [
            'user_id' => $user['id'],
            'name' => $name,
            'phone' => $phone,
            'province' => $province,
            'city' => $city,
            'area' => $area,
            'street' => $street,
            'address' => $address,
            'default' => $default,
            'province_code' => $province_code,
            'city_code' => $city_code,
            'area_code' => $area_code,
            'street_code' => $street_code,
            'update_time' => $time
        ];

        if ($id) {
            $model = MallAddressModel::findOne(['id' => $id, 'user_id' => $user['id']]);
            if (empty($model)) {
                return self::jsonError('地址不存在');
            }
        } else {
            $model = new MallAddressModel();
            $data['create_time'] = $time;
        }
        
        $transaction = MallAddressModel::getDb()->beginTransaction();
        try {
            if ($data['default'] == MallAddressModel::DEFAULT_YES) {
                MallAddressModel::updateAll([
                    'default' => MallAddressModel::DEFAULT_NO
                ], [
                    'AND',
                    ['=', 'user_id', $user['id']],
                ]);
            }

            if($model->default == MallAddressModel::DEFAULT_YES && $data['default'] == MallAddressModel::DEFAULT_NO) {
                $last_addr = MallAddressModel::find()->where(['user_id' => $user['id']])->orderBy('update_time desc')->one();
                if($last_addr &&  $last_addr->id != $model->id){
                    $last_addr->default = MallAddressModel::DEFAULT_YES;
                    $last_addr->save();
                } else {
                    $data['default'] = MallAddressModel::DEFAULT_YES;
                }
            }

            $model->setAttributes($data, false);
            if (empty($model->save())) {
                throw new \Exception('操作失败');
            }

            $transaction->commit();
            return self::jsonSuccess($model);
        } catch (\Exception $exc) {
            $transaction->rollBack();
            return self::jsonError($exc->getMessage());
        }
    }

    /*
     * 设置地址为默认
     * @param array $params
     */
    public static function setDefault($params)
    {
        $id = intval($params['id'] ?? 0);
        $user = Yii::$app->user->getInfo();

        $transaction = MallAddressModel::getDb()->beginTransaction();
        try {
            MallAddressModel::updateAll(['default'=> MallAddressModel::DEFAULT_NO], ['user_id' => $user['id']]);
            $res = MallAddressModel::updateAll(['default' => MallAddressModel::DEFAULT_YES], ['user_id' => $user['id'], 'id' => $id]);
            if (empty($res)) {
                throw new \Exception('操作失败');
            }
            $transaction->commit();
            return self::jsonSuccess();
        } catch (\Exception $exc) {
            $transaction->rollBack();
            return self::jsonError($exc->getMessage());
        }
    }

    /**
     * 删除
     * @param array $params 参数
     * **/
    public static function delete($params)
    {
        $id = intval($params['id'] ?? 0);
        if (empty($id)) {
            return self::jsonError('参数异常');
        }
        $user = Yii::$app->user->getInfo();

        $condition = ['id' => $id, 'user_id' => $user['id']];

        $address = MallAddressModel::find()->where($condition)->one();
        if (empty($address)) {
            return self::jsonError('删除失败');
        }
        $default = $address['default'];
        if (!MallAddressModel::deleteAll($condition)) {
            return self::jsonError('删除失败');
        }
        
        //重新设置默认地址
        if($default == MallAddressModel::DEFAULT_YES){
            $address = MallAddressModel::find()->where(['user_id' => $user['id']])->one();
            if($address){
                $address->default = MallAddressModel::DEFAULT_YES;
                $address->save();
            }
        }

        return self::jsonSuccess();
    }

    /*
     * 详情
     * @param array $params
     */
    public static function detail($params)
    {
        $id = intval($params['id'] ?? 0);
        $user = Yii::$app->user->getInfo();
        $condition = ['id' => $id, 'user_id' => $user['id']];
        $info = MallAddressModel::find()->where($condition)->asArray()->one();
        return $info;
    }
}

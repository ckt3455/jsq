<?php

namespace common\components;


use backend\models\Goods;
use Yii;
use backend\models\User;
use yii\base\Security;


//神州联保api集成
class SzApi
{


    const OPENSSL_PADDING = OPENSSL_PKCS1_PADDING;
    public $baseUrl='http://lapi.zhuque.szlb.cc/index.php/';
    public $timeout = 30;

    /**
     * 发送包含加密参数的API请求
     * @param string $endpoint 接口路径
     * @param array $requestData 原始请求数据
     * @param string $platformCode 平台编码
     * @param string $publicKey RSA公钥
     * @return array 响应结果
     */
    public function sendSecureRequest($endpoint, $requestData, $platformCode, $publicKey)
    {
        // 生成32位随机加密密钥
        $encryptKey = $this->generateEncryptKey();

        // 使用AES加密请求数据
        $verifyData = openssl_encrypt(
            json_encode($requestData),
            'AES-256-ECB',
            $encryptKey,
            OPENSSL_RAW_DATA
        );


        // 使用RSA公钥加密encryptKey
        $verifyKey = $this->rsaEncrypt($encryptKey, $publicKey);

        // 准备POST数据
        $postData = [
            'platform_code' => $platformCode,
            'verify_key' => $verifyKey,
            'verify_data' => base64_encode($verifyData),
        ];

        $url = $this->baseUrl . $endpoint;
        $jsonData = json_encode($postData);

        $ch = curl_init();

        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $jsonData,
            CURLOPT_TIMEOUT => $this->timeout,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Content-Length: ' . strlen($jsonData)
            ],
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);

        curl_close($ch);

        if ($error) {
            throw new \Exception('cURL请求失败: ' . $error);
        }

        return [
            'http_code' => $httpCode,
            'response' => json_decode($response, true) ?: $response,
        ];
    }

    /**
     * RSA加密
     * @param string $data 要加密的数据
     * @param string $publicKey 公钥
     * @return string Base64编码的加密结果
     */
    private function rsaEncrypt($data, $publicKey)
    {
        // 确保公钥格式正确
        if (strpos($publicKey, '-----BEGIN PUBLIC KEY-----') === false) {
            $publicKey = "-----BEGIN PUBLIC KEY-----\n" .
                chunk_split($publicKey, 64, "\n") .
                "-----END PUBLIC KEY-----";
        }

        $publicKeyResource = openssl_pkey_get_public($publicKey);
        if ($publicKeyResource === false) {
            throw new \Exception('无效的公钥格式');
        }


        // 获取密钥详细信息
        $keyDetails = openssl_pkey_get_details($publicKeyResource);
        if (!$keyDetails) {
            openssl_free_key($publicKeyResource);
            throw new Exception('无法获取公钥详细信息');
        }

        $encrypted = '';
        $success = openssl_public_encrypt($data, $encrypted, $publicKeyResource, self::OPENSSL_PADDING);
        openssl_free_key($publicKeyResource);

        if (!$success) {
            throw new Exception('RSA加密失败: ' . openssl_error_string());
        }


        return base64_encode($encrypted);
    }


    private function ensureKeyLength($key, $length)
    {
        if (strlen($key) > $length) {
            return substr($key, 0, $length);
        } elseif (strlen($key) < $length) {
            return str_pad($key, $length, "\0");
        }
        return $key;
    }


    /**
     * 生成32位随机加密密钥
     * @return string 32位随机字符串（0-9 a-z A-Z）
     */
    public function generateEncryptKey()
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';

        $security = new Security();

        for ($i = 0; $i < 32; $i++) {
            // 使用安全随机数生成器
            $randomIndex = $security->generateRandomKey(1);
            $randomIndex = ord($randomIndex) % $charactersLength;
            $randomString .= $characters[$randomIndex];
        }

        return $randomString;
    }

}
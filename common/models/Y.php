<?php

namespace common\models;

use Yii;
use yii\helpers\Json;
use yii\base\Exception;

class Y {

    const RANDOM_DIGITS = 'digits';
    const RANDOM_NONCESTRING = 'noncestr';

    public static function randomString($type = self::RANDOM_DIGITS, $len = 4) {
        $code = '';
        switch ($type) {
            case self::RANDOM_DIGITS:
                $chars = '0123456789';
                break;
            case self::RANDOM_NONCESTRING:
                $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
                break;
        }
        $chars_len = strlen($chars);
        while ($len > 0) {
            $code .= substr($chars, rand(0, 10000) % $chars_len, 1);
            $len--;
        }
        return $code;
    }

    public static function curl($url, array $posts = [], $format = 'json') {
        $json_enable = true;
        foreach ($posts as $k => $v) {
            if(is_array($v)) continue;

            if (false !== stripos($v, '@')) {
                $json_enable = false;
            }
        }
        $curlOptions = [
            CURLOPT_USERAGENT => 'WXTPP Client',
            CURLOPT_CONNECTTIMEOUT => 30,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_POST => true,
            //CURLOPT_SAFE_UPLOAD => false,
            CURLOPT_POSTFIELDS => $json_enable ? Json::encode($posts) : $posts,
//            CURLOPT_POSTFIELDS => $posts,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_URL => $url,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSLVERSION => CURL_SSLVERSION_TLSv1,
        ];

        $curlResource = curl_init();
        foreach ($curlOptions as $option => $value) {
            curl_setopt($curlResource, $option, $value);
        }
        $response = curl_exec($curlResource);
        $responseHeaders = curl_getinfo($curlResource);

        // check cURL error
        $errorNumber = curl_errno($curlResource);
        $errorMessage = curl_error($curlResource);

        curl_close($curlResource);

        if ($errorNumber > 0) {
            throw new Exception('Curl error requesting "' . $url . '": #' . $errorNumber . ' - ' . $errorMessage);
        }
        if (strncmp($responseHeaders['http_code'], '20', 2) !== 0) {
            throw new \yii\authclient\InvalidResponseException($responseHeaders, $response, 'Request failed with code: ' . $responseHeaders['http_code'] . ', message: ' . $response);
        }

        if ('json' === $format) {
//            \Yii::warning($response);
//            return Json::decode($response, true);  
            return json_decode($response, true);
        } else if ('xml' === $format) {
            $respObject = @simplexml_load_string($response);
            if (false !== $respObject)
                return json_decode(json_encode($respObject), true);
            else
                throw new Exception('XML error:' . $response);
        }
    }

    //U::getRandomWeightedElement(array('AAA'=>5, 'BBB'=>30, 'CCC'=>65));
    public static function getRandomWeightedElement($weightedValues) {
        $rand = mt_rand(1, (int) array_sum($weightedValues));
        foreach ($weightedValues as $key => $value) {
            $rand -= $value;
            if ($rand <= 0) {
                return $key;
            }
        }
    }

    public static function array_field_assoc($items, $field1, $field2) {
        $iids = array();
        foreach ($items as $item)
            $iids[$item[$field1]] = $item[$field2];
        return ($iids);
    }

    public static function fvalue($str) {
        $pattern = '/[+-]?\d+(\.\d+)?/';
        if (1 === preg_match($pattern, $str, $mathes))
            return floatval($mathes[0]);
        else
            return 0;
    }

    public static function is_json($string) {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }

    public static function current(array $params = [], $scheme = false)
    {
        $currentParams = Yii::$app->getRequest()->getQueryParams();
        $currentParams[0] = '/' . Yii::$app->controller->getRoute();
        $route = \yii\helpers\ArrayHelper::merge($currentParams, $params);
        return \yii\helpers\Url::toRoute($route, $scheme);
    }


}

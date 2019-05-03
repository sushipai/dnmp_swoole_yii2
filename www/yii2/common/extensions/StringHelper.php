<?php

namespace common\extensions;

use yii\helpers\BaseStringHelper;

class StringHelper extends BaseStringHelper
{

    /**
     * 获取数字随机字符串
     * @param int $length 长度
     * @param string $prefix 前缀
     * @return string
     */
    public static function randomNum($length = 8, $prefix = '')
    {
        return $prefix . substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, $length);
    }

    /**
     * 字符串转小写
     * @param string $string
     * @return string
     */
    public static function lower($string)
    {
        return mb_strtolower($string, 'UTF-8');
    }

    /**
     * 字符串转大写
     * @param string $string
     * @return string
     */
    public static function upper($string)
    {
        return mb_strtoupper($string, 'UTF-8');
    }

    /**
     * 手机号隐藏中间四位
     * @param string $mobile
     * @return string
     */
    public static function hiddenMobile($mobile)
    {
        if (VerifyHelper::isMobile($mobile)) {
            return substr_replace($mobile, '****', 3, 4);
        }

        return $mobile;
    }

    /**
     * 邮箱显示@字符串的前一后一
     * @param string $email
     * @return string
     */
    public static function hiddenEmail($email)
    {
        if (VerifyHelper::isEmail($email)) {
            $prefix = strstr($email, '@', true);
            if ($prefix !== false) {
                return substr($prefix, 0, 1) . '****' . substr($prefix, -1) . strstr($email, '@');
            }
        }

        return $email;
    }

    /**
     * 字符加密函数 加密后为44位长度
     * @param string $string
     * @param string $key
     * @return string
     */
    public static function encodeUrl($string, $key = 'jjcms')
    {
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-=+';
        $nh = rand(0, 64);
        $ch = $chars[$nh];
        $mdKey = substr(md5($key . $ch), $nh % 8, $nh % 8 + 7);
        $txtKey = base64_encode($string . $key);
        $tmp = '';
        $i = 0;
        $j = 0;
        $k = 0;

        for ($i = 0; $i < strlen($txtKey); $i++) {
            $k = $k == strlen($mdKey) ? 0 : $k;
            $j = ($nh + strpos($chars, $txtKey[$i]) + ord($mdKey[$k++])) % 64;
            $tmp .= $chars[$j];
        }

        return urlencode(base64_encode($ch . $tmp));
    }

    /**
     * 解密函数
     * @param string $string
     * @param string $key
     * @return string|false
     */
    public static function decodeUrl($string, $key = 'jjcms')
    {
        if (strlen($string) == 44) {
            $string = base64_decode(urldecode($string));
            $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-=+';
            $ch = $string[0];
            $nh = strpos($chars, $ch);
            $mdKey = substr(md5($key . $ch), $nh % 8, $nh % 8 + 7);
            $txtLast = substr($string, 1);
            $tmp = '';
            $i = $j = $k = 0;

            for ($i = 0; $i < strlen($txtLast); $i++) {
                $k = $k == strlen($mdKey) ? 0 : $k;
                $j = strpos($chars, $txtLast[$i]) - $nh - ord($mdKey[$k++]);
                while ($j < 0) {
                    $j += 64;
                }
                $tmp .= $chars[$j];
            }

            return trim(base64_decode($tmp), $key);
        }

        return false;
    }

    /**
     * 字符串IP转数字
     * @param string $ip
     * @return string
     */
    public static function itoa($ip)
    {
        return inet_pton($ip);
    }

    /**
     * 数字转字符串IP
     * @param int $addr
     * @return string
     */
    public static function atoi($addr = 0)
    {
        return inet_ntop($addr);
    }

}

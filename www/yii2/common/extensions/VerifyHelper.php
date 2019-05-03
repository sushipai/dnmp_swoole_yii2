<?php

namespace common\extensions;

class VerifyHelper
{

    /**
     * 检测手机号是否合法
     * @param string $mobile
     * @return boolean
     */
    public static function isMobile($mobile)
    {
        if (preg_match("/1[34578]\d{9}$/", $mobile)) {
            return true;
        }

        return false;
    }

    /**
     * 检测邮箱是否合法
     * @param string $email
     * @return boolean
     */
    public static function isEmail($email)
    {
        if (preg_match('/^[a-zA-Z0-9._%+-]+@(?!.*\.\..*)[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/', $email)) {
            return true;
        }

        return false;
    }

    /**
     * 检测是字符串否是以中文和下划线的组合
     * @param string $string
     * @return boolean
     */
    public static function isChinese($string)
    {
        if (preg_match('/^[a-z0-9_\u4E00-\u9FA5]+[^_]$/', $string)) {
            return true;
        }

        return false;
    }

    /**
     * 检查身份账号的格式是否正确
     * @param string $id 身份证id
     * @return boolean
     */
    public static function isId($id)
    {
        $length = strlen($id);

        if (18 == $length) {
            return self::checkId18($id);
        } elseif (15 == $length) {
            $id = self::idTo18($id);
            return self::checkId18($id);
        }

        return false;
    }

    /**
     * 计算身份证校验码，根据国家标准GB 11643-1999
     * @param string $id 身份证id
     * @return boolean|string
     */
    private static function verifyId($id)
    {
        if (strlen($id) != 17) {
            return false;
        }

        $factor = [7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2]; //加权因子 
        $list = ['1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2']; //校验码对应值 
        $sum = 0;

        for ($i = 0; $i < strlen($id); $i++) {
            $sum += substr($id, $i, 1) * $factor[$i];
        }

        $mod = $sum % 11;

        return $list[$mod];
    }

    /**
     * 将15位身份证升级到18位
     * @param string $id 身份证id
     * @return boolean
     */
    private static function idTo18($id)
    {
        if (strlen($id) != 15) {
            return false;
        }

        if (array_search(substr($id, 12, 3), array('996', '997', '998', '999')) !== false) { // 如果身份证顺序码是996 997 998 999，这些是为百岁以上老人的特殊编码 
            $id = substr($id, 0, 6) . '18' . substr($id, 6, 9);
        } else {
            $id = substr($id, 0, 6) . '19' . substr($id, 6, 9);
        }

        return $id . self::verifyId($id);
    }

    /**
     * 18位身份证校验码有效性检查
     * @param string $id 身份证id
     * @return boolean
     */
    private static function checkId18($id)
    {
        if (strlen($id) != 18) {
            return false;
        }

        if (self::verifyId(substr($id, 0, 17)) != strtoupper(substr($id, 17, 1))) {
            return false;
        }

        return true;
    }

}

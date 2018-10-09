<?php

class AlipayMD5 {

    /**
     * 签名字符串
     * @param $prestr String 需要签名的字符串
     * @param $key String 私钥
     * @return String 签名结果
     */
    public static function md5Sign($prestr, $key) {
        $prestr = $prestr . $key;
        return md5($prestr);
    }

    /**
     * 验证签名
     * @param $prestr String 需要签名的字符串
     * @param $sign String 签名结果
     * @param $key String 私钥
     * @return String 签名结果
     */
    public static function md5Verify($prestr, $sign, $key) {
        $prestr = $prestr . $key;
        $mysgin = md5($prestr);

        if($mysgin == $sign) {
            return true;
        }
        else {
            return false;
        }
    }
}

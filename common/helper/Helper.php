<?php


namespace app\common\helper;

class Helper
{
    /**
     * 获取客户端IP
     * @return mixed
     * @author 王新龙
     * @date   2019/9/5 11:37 AM
     */
    public static function getIp()
    {
        if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown"))
            $ip = getenv("HTTP_CLIENT_IP");
        elseif (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown"))
            $ip = getenv("HTTP_X_FORWARDED_FOR");
        elseif (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown"))
            $ip = getenv("REMOTE_ADDR");
        elseif (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown"))
            $ip = $_SERVER['REMOTE_ADDR'];
        else
            $ip = "";

        $Ips = explode(',', $ip);
        $ip  = $Ips[0];
        $arr = explode(':', $ip);
        return ($arr[count($arr) - 1]);
    }

    /**
     * 检测必传字段
     *
     * @param $fieldArr
     * @param $postData
     *
     * @return bool
     * @author 王新龙
     * @date   2019/11/14 5:54 PM
     */
    public static function checkRequired($fieldArr, $postData)
    {
        if (empty($fieldArr) || empty($postData)) {
            return FALSE;
        }
        if (!is_array($fieldArr) || (!is_array($postData) && !is_object($postData))) {
            return FALSE;
        }
        foreach ($fieldArr as $item) {
            if (!isset($postData[$item])) {
                return FALSE;
            }
        }
        return TRUE;
    }
}

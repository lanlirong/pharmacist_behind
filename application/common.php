<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
function checkUser($limit)
{
    $res = array();
    if (!isset($_COOKIE[session_name()])) { {
            $result = array(
                'data' => null,
                'code' => 4,
                'msg' => "未登录，无操作权限"
            );
        }
        $res[] = false;
        $res[] = $result;
        return $res;
    }
    // 权限验证
    Session_start();
    $sessionData = $_SESSION['userInfo']['limit'];
    if (!in_array($limit, explode(',', $sessionData))) {
        $result = array(
            'data' => null,
            'code' => 5,
            'msg' => "无操作权限"
        );
        $res[] = false;
        $res[] = $result;
        return $res;
    }
    $res[] = true;
    return $res;
}
function Get8Str($len)
{
    $chars_array = array(
        "0", "1", "2", "3", "4", "5", "6", "7", "8", "9",
        "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k",
        "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v",
        "w", "x", "y", "z"
    );
    $charsLen = count($chars_array) - 1;

    $outputstr = "";
    for ($i = 0; $i < $len; $i++) {
        $outputstr .= $chars_array[mt_rand(0, $charsLen)];
    }
    return $outputstr;
}

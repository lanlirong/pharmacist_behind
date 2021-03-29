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

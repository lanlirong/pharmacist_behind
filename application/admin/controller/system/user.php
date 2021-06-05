<?php

namespace app\admin\controller\system;

use think\Controller;
use app\admin\model\system\AdminUser as AdminUserM;


class User extends Controller
{
    public function login()
    {

        $body = file_get_contents('php://input');
        $params = json_decode($body);
        $username =  $params->username;
        $password =  $params->password;

        $AdminUserM = new AdminUserM();
        $user = $AdminUserM->where('username', $username)->find();
        if (!$user) {
            $result = array(
                'data' => null,
                'code' => 4,
                'msg' => "登录失败,用户不存在"
            );
        } else if ($user && $user->password !== $password) {
            $result = array(
                'data' => null,
                'code' => 4,
                'msg' => "登录失败,密码错误"
            );
        } else if ($user && $user->password == $password) {
            Session_start();
            $sessionId = session_id();
            $_SESSION['userInfo'] = [      // 存入信息
                'id' => $user->id,
                'username' => $user->username,
                'limit' => $user->limit
            ];
            setcookie(session_name(), $sessionId, time() + 3600 * 24, '/'); // 24h后失效
            // unset($user->password);
            $user->avatar = 'http://' . $_SERVER['SERVER_NAME'] . ':' . $_SERVER["SERVER_PORT"] . '/static' . $user->avatar;
            $result = array(
                'data' => $user,
                'code' => 1,
                'msg' => "登录成功"
            );
        }
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }
    public function logout()
    {
        if (isset($_COOKIE[session_name()])) {  //判断客户端的cookie文件是否存在,存在的话将其设置为过期.   session_name() 即phpsessid    
            setcookie(session_name(), '', time() - 1, '/');
            session_start();
            session_destroy();
        }

        $result = array(
            'data' => null,
            'code' => 1,
            'msg' => "退出登录成功"
        );
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }
    // public function getList()
    // {
    //     $body = file_get_contents('php://input');
    //     $params = json_decode($body);
    //     $sql = "{$params->type} = '%{$params->searchKey}%'";
    //     if ($params->orderType == "") {
    //         $order = "";
    //     } else {
    //         $order = "convert({$params->orderType} using gbk) COLLATE gbk_chinese_ci {$params->order}";
    //     }

    //     $AdminUserM = new AdminUserM();
    //     $list = $AdminUserM->where($sql)->order($order)->paginate($params->size, false, [
    //         'page' =>  $params->page,
    //     ]);
    //     $result = array(
    //         'data' => $list,
    //         'code' => 1,
    //         'msg' => "登录成功"
    //     );
    //     echo json_encode($result, JSON_UNESCAPED_UNICODE);
    // }
}

<?php

namespace app\index\controller\user;

use think\Controller;
use app\index\model\user\Index_user as UserM;


class Index extends Controller
{

    public function register()
    {
        $body = $_POST;
        $file = $_FILES['avatar'];
        if (($file['type'] == "image/jpeg" || $file['type'] == "image/png") && $file['size'] <= 10 * 1024 * 1024) {
            $fileName = $body['username'] . '.jpg';
            rename($file['tmp_name'], ROOT_PATH . 'public/static/upload/avatar/' . $fileName);
            $body['avatar'] = '/upload/avatar/' .  $fileName;
        }

        $body['createTime'] = date('Y-m-d H:i:s', time());
        $body['updateTime'] =  $body['createTime'];
        $user = new UserM($body);
        $user->allowField(true)->save();
        $result = array(
            'data' => $body,
            'code' => 1,
            'msg' => "注册成功"
        );
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }
    public function login()
    {
        $body = file_get_contents('php://input');
        $params = json_decode($body);
        $userM = new UserM();
        $user = $userM->where('username',  $params->username)->find();
        if ($user &&  $user->password == $params->password) {
            Session_start();
            $sessionId = session_id();
            $_SESSION['index_user'] = [      // 存入信息
                'id' => $user->id,
                'username' => $user->username,
            ];
            setcookie(session_name(), $sessionId, time() + 3600 * 24, '/'); // 24h后失效
            $user->avatar = getWholeImgUrl($user->avatar);
            $result = array(
                'data' => $user,
                'code' => 1,
                'msg' => "登录成功"
            );
        } else {
            $result = array(
                'data' => null,
                'code' => 4,
                'msg' => "账号密码错误"
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
    public function editAvatar()
    {
        $file = $_FILES['avatar'];
        $params = array();
        if (($file['type'] == "image/jpeg" || $file['type'] == "image/png") && $file['size'] <= 10 * 1024 * 1024) {
            Session_start();
            if (array_key_exists("index_user", $_SESSION)) {
                $username = $_SESSION['index_user']['username'];
                $id = $_SESSION['index_user']['id'];
                $fileName = $username . '.jpg';
                rename($file['tmp_name'], ROOT_PATH . 'public/static/upload/avatar/' . $fileName);
                $params['avatar'] = '/upload/avatar/' .  $fileName;
            } else {
                $result = array(
                    'data' =>  null,
                    'code' => 4,
                    'msg' => "未登录"
                );
                echo json_encode($result, JSON_UNESCAPED_UNICODE);
                return;
            }
        }

        $params['updateTime'] =  date('Y-m-d H:i:s', time());
        $user = new UserM();
        $user->save($params, ['id' => $id]);
        $result = array(
            'data' => getWholeImgUrl($params['avatar']),
            'code' => 1,
            'msg' => "修改成功"
        );
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }
    public function editMyInfo()
    {
        $body = file_get_contents('php://input');
        $params = json_decode($body);
        // $userM = new UserM();
        $user = UserM::get($params->id);
        $user[$params->type]     = $params->value;
        $user->save();
        $result = array(
            'data' => null,
            'code' => 1,
            'msg' => "修改成功"
        );
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }
    public function checkUsername()
    {
        $params = input('param.');
        if ($params == [] || $params['username'] == '') {
            $result = array(
                'data' => null,
                'code' => 3,
                'msg' => "出现异常，username没有传值"
            );
        } else {
            $UserM = new UserM();
            $User = $UserM->where('username', $params['username'])->find();
            if ($User) {
                $result = array(
                    'data' => true,
                    'code' => 1,
                    'msg' => "账号已存在"
                );
            } else {
                $result = array(
                    'data' => false,
                    'code' => 1,
                    'msg' => "账号不存在"
                );
            }
        }
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }
    public function checkPhone()
    {
        $params = input('param.');
        if ($params == [] || $params['phone'] == '') {
            $result = array(
                'data' => null,
                'code' => 3,
                'msg' => "出现异常，phone没有传值"
            );
        } else {
            $UserM = new UserM();
            $User = $UserM->where('phone', $params['phone'])->find();
            if ($User) {
                $result = array(
                    'data' => true,
                    'code' => 1,
                    'msg' => "电话已存在"
                );
            } else {
                $result = array(
                    'data' => false,
                    'code' => 1,
                    'msg' => "电话不存在"
                );
            }
        }
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }
    public function checkEmail()
    {
        $params = input('param.');
        if ($params == [] || $params['email'] == '') {
            $result = array(
                'data' => null,
                'code' => 3,
                'msg' => "出现异常，email没有传值"
            );
        } else {
            $UserM = new UserM();
            $User = $UserM->where('email', $params['email'])->find();
            if ($User) {
                $result = array(
                    'data' => true,
                    'code' => 1,
                    'msg' => "邮箱已存在"
                );
            } else {
                $result = array(
                    'data' => false,
                    'code' => 1,
                    'msg' => "邮箱不存在"
                );
            }
        }
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }
}

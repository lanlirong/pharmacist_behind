<?php

namespace app\admin\controller\system;

use think\Controller;
use app\admin\model\system\AdminUser as Admin_userM;
use app\admin\model\system\Limits as LimitsM;

class Index extends Controller
{
    public function getList()
    {
        $check = checkUser('7');
        if (!$check[0]) {
            echo json_encode($check[1], JSON_UNESCAPED_UNICODE);
            return;
        }

        $body = file_get_contents('php://input');
        $params = json_decode($body);
        $sql = "{$params->type} LIKE '%{$params->searchKey}%'";
        if ($params->orderType == "") {
            $order = "";
        } else {
            $order = "convert({$params->orderType} using gbk) COLLATE gbk_chinese_ci {$params->order}";
        }

        $Admin_userM = new Admin_userM();
        $list = $Admin_userM->where($sql)->order($order)->paginate($params->size, false, [
            'page' =>  $params->page,
        ]);

        $result = array(
            'data' => $list,
            'code' => 1,
            'msg' => "查询成功"
        );

        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }
    public function getOneByID()
    {
        $params = input('param.');
        if ($params == [] || $params['id'] == '') {
            $result = array(
                'data' => null,
                'code' => 3,
                'msg' => "出现异常，id没有传值"
            );
        } else {
            $Admin_userM = new Admin_userM();
            $Admin_user = $Admin_userM->where('id', $params['id'])->find();
            $Admin_user->avatar = 'http://' . $_SERVER['SERVER_NAME'] . ':' . $_SERVER["SERVER_PORT"] . '/static' . $Admin_user->avatar;
            $limits = explode(',', $Admin_user->limit);
            $temp = array();
            for ($i = 0; $i < count($limits); $i++) {
                $limitsM = (LimitsM::get((int)$limits[$i]));
                if ($limitsM && $limitsM->name !== '') {
                    // $limits[$i] = $limitsM->name;
                    array_push($temp, $limitsM->name);
                }
            }
            $Admin_user->limits = $temp;

            if ($Admin_user) {
                $result = array(
                    'data' => $Admin_user,
                    'code' => 1,
                    'msg' => "查询成功"
                );
            } else {
                $result = array(
                    'data' => null,
                    'code' => 2,
                    'msg' => "数据没有找到"
                );
            }
        }

        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }
    public function add()
    {
        $check = checkUser('5');
        if (!$check[0]) {
            echo json_encode($check[1], JSON_UNESCAPED_UNICODE);
            return;
        }

        $body = $_POST;
        // $file = $_FILES['avatar'];
        // if (($file['type'] == "image/jpeg" || $file['type'] == "image/png") && $file['size'] <= 10 * 1024 * 1024) {
        //     $fileName = $body['username'] . '.jpg';
        //     rename($file['tmp_name'], ROOT_PATH . 'public/static/upload/avatar/' . $fileName);
        //     $body['avatar'] = '/upload/avatar/' .  $fileName;
        // }

        $body['createTime'] = date('Y-m-d H:i:s', time());
        $body['updateTime'] =  $body['createTime'];
        $body['creator']  =  $_SESSION['userInfo']['username'];
        $Admin_user = new Admin_userM($body);
        $Admin_user->allowField(true)->save();
        $result = array(
            'data' => $body,
            'code' => 1,
            'msg' => "新增成功"
        );
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }
    public function deleteOne()
    {
        $check = checkUser('6');
        if (!$check[0]) {
            echo json_encode($check[1], JSON_UNESCAPED_UNICODE);
            return;
        }
        $params = input('param.');
        if ($params == [] || $params['id'] == '') {
            $result = array(
                'data' => null,
                'code' => 3,
                'msg' => "出现异常，id没有传值"
            );
            echo json_encode($result, JSON_UNESCAPED_UNICODE);
            return;
        }
        Admin_userM::where('id', $params['id'])->delete();
        $result = array(
            'data' => null,
            'code' => 1,
            'msg' => "删除成功"
        );
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }
    public function getCount()
    {
        $Admin_userM = new Admin_userM();
        $count = $Admin_userM->count();
        $result = array(
            'data' => $count,
            'code' => 1,
            'msg' => "查询成功"
        );
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }
}

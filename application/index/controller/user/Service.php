<?php

namespace app\index\controller\user;

use think\Controller;
use app\index\model\user\Index_user as UserM;
use app\index\model\user\Sys_user_msg as Sys_user_msgM;


class Service extends Controller
{

    public function sendUserMsg()
    {
        session_start();
        $body = file_get_contents('php://input');
        $params = json_decode($body);

        $params->userId = $_SESSION['index_user']['id'];
        $params->type = 1;

        $Sys_user_msgM = new Sys_user_msgM($params);
        $Sys_user_msgM->allowField(true)->save();


        $result = array(
            'data' => null,
            'code' => 1,
            'msg' => "数据提交成功"
        );
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }
    public function getMsgList()
    {
        session_start();
        if (array_key_exists("index_user", $_SESSION)) {
            $id = $_SESSION['index_user']['id'];
            $Sys_user_msgM = new Sys_user_msgM();
            $sql = "userId ='" . $id . "' or replyTo = '" . $id . "'";
            $Sys_user_msg = $Sys_user_msgM->where($sql)->select();

            $result = array(
                // 'test' =>  $userIds,
                'data' =>  $Sys_user_msg,
                'code' => 1,
                'msg' => "数据获取成功"
            );
            echo json_encode($result, JSON_UNESCAPED_UNICODE);
        } else {
            $result = array(
                'data' =>  null,
                'code' => 4,
                'msg' => "未登录"
            );
            echo json_encode($result, JSON_UNESCAPED_UNICODE);
        }
    }
}

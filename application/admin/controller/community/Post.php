<?php

namespace app\admin\controller\community;

use think\Controller;
use app\admin\model\community\Post as PostM;
use app\index\model\user\Index_user as UserM;


class Post extends Controller
{
    public function getList()
    {
        $check = checkUser('28');
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

        $PostM = new PostM();
        $list = $PostM->where($sql)->order($order)->paginate($params->size, false, [
            'page' =>  $params->page,
        ]);
        $list->each(function ($item, $key) {
            $item->username =  (UserM::get($item->userId))->username;
        });
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
            $PostM = new PostM();
            $interaction = $PostM->where('id', $params['id'])->find();
            if ($interaction) {
                $result = array(
                    'data' => $interaction,
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
    public function deleteOne()
    {
        $check = checkUser('29');
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
        PostM::where('id', $params['id'])->delete();
        $result = array(
            'data' => null,
            'code' => 1,
            'msg' => "删除成功"
        );
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }
}

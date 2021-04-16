<?php

namespace app\index\controller\interaction;

use think\Controller;
use app\index\model\interaction\Interaction as InteractionM;
use app\index\model\interaction\Raw_interaction as Raw_InteractionM;

class Check extends Controller
{
    public function getRawList()
    {
        $check = checkUser('19');
        if (!$check[0]) {
            echo json_encode($check[1], JSON_UNESCAPED_UNICODE);
            return;
        }

        $body = file_get_contents('php://input');
        $params = json_decode($body);
        $sql = "{$params->type} LIKE '%{$params->searchKey}%'";
        $username = $_SESSION['userInfo']['username'];
        if ($username == 'admin') {
            $sql2 = '';
        } else {
            $sql2 = " creator != '{$username}'";
        }
        if ($params->status === '') {
            $sql3 = '';
        } else {
            $sql3 = " status = '{$params->status}'";
        }
        if ($params->orderType == "") {
            $order = "";
        } else {
            $order = "convert({$params->orderType} using gbk) COLLATE gbk_chinese_ci {$params->order}";
        }

        $Raw_InteractionM = new Raw_InteractionM();
        $list = $Raw_InteractionM->where($sql2)->where($sql)->where($sql3)->order($order)->paginate($params->size, false, [
            'page' =>  $params->page,
        ]);

        $result = array(
            // 'test' => $check,
            'data' => $list,
            'code' => 1,
            'msg' => "查询成功"
        );

        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }
    public function getCheckByID()
    {
        $params = input('param.');
        if ($params == [] || $params['id'] == '') {
            $result = array(
                'data' => null,
                'code' => 3,
                'msg' => "出现异常，id没有传值"
            );
        } else {
            $Raw_InteractionM = new Raw_InteractionM();
            $disease = $Raw_InteractionM->where('id', $params['id'])->find();
            if ($disease) {
                $result = array(
                    'data' => $disease,
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
    public function check()
    {
        $check = checkUser('15');
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
        // 不通过
        if ($params['status'] == 2) {
            $Raw_InteractionM = new Raw_InteractionM;
            $Raw_InteractionM->save([
                'status'  => 2,
                'reviewer' => $_SESSION['userInfo']['username']
            ], ['id' => $params['id']]);

            $result = array(
                'data' => null,
                'code' => 1,
                'msg' => "审核成功"
            );
            echo json_encode($result, JSON_UNESCAPED_UNICODE);
            return;
        }
        // 通过
        if ($params['status'] == 1) {
            $Raw_InteractionM = new Raw_InteractionM();
            $raw = $Raw_InteractionM->where('id', $params['id'])->find();
            if ($raw &&  $raw->isNew) { //更新
                $raw->id = $raw->isNew;
                $disease = json_decode(json_encode($raw), true);
                $InteractionM = new InteractionM();
                $InteractionM->allowField(true)->save($disease, ['id' => $raw->id]);
            } else {  // 新增
                // $raw->id = Get8Str(8);
                unset($raw->id);
                $raw->reviewer = $_SESSION['userInfo']['username'];
                $disease = json_decode(json_encode($raw), true);
                $InteractionM = new InteractionM($disease);
                $InteractionM->allowField(true)->save();
            }

            $Raw_InteractionM = new Raw_InteractionM;
            $Raw_InteractionM->save([
                'status'  => 1,
                'reviewer' => $_SESSION['userInfo']['username']
            ], ['id' => $params['id']]);

            $result = array(
                // 'test' => $disease,
                'data' => null,
                'code' => 1,
                'msg' => "审核成功"
            );
            echo json_encode($result, JSON_UNESCAPED_UNICODE);
        }
    }
}

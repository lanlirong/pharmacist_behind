<?php

namespace app\admin\controller\consult;

use think\Controller;
use app\admin\model\consult\Question as ConsultM;
use app\admin\model\consult\Raw_question as Raw_ConsultM;

class AddRaw extends Controller
{
    private function checkRaw($id)
    {
        $ConsultM = new ConsultM();
        $consult = $ConsultM->where('id', $id)->find();
        if ($consult) {
            return $consult->id;
        } else {
            return false;
        }
    }
    public function addRaw()
    {
        $check = checkUser('24');
        if (!$check[0]) {
            echo json_encode($check[1], JSON_UNESCAPED_UNICODE);
            return;
        }
        $body = file_get_contents('php://input');
        $params = json_decode($body);
        $params->isNew = 0;

        $params->createTime = date('Y-m-d H:i:s', time());
        $params->updateTime  = $params->createTime;
        $params->creator  =  $_SESSION['userInfo']['username'];

        $raw_consult = new Raw_ConsultM($params);
        $raw_consult->allowField(true)->save();
        $result = array(
            'data' => null,
            'code' => 1,
            'msg' => "数据提交成功"
        );
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }
    public function updateRaw()
    {
        $check = checkUser('24');
        if (!$check[0]) {
            echo json_encode($check[1], JSON_UNESCAPED_UNICODE);
            return;
        }
        $body = file_get_contents('php://input');
        $params = json_decode($body);
        $params->updateTime = date('Y-m-d H:i:s', time());
        $params->status = 0;

        $isId = $this->checkRaw($params->id);
        if ($isId) { // 从已有库中新增审核记录
            $params->isNew = $isId;
            // unset($params->id);;
            unset($params->id);
            $params->creator = $_SESSION['userInfo']['username'];
            $raw_consult = new Raw_ConsultM($params);
            $raw_consult->allowField(true)->save();
        } else {
            $raw_consult = new Raw_ConsultM();
            $raw_consult->allowField(true)->save($params, ['id' => $params->id]);
        }
        $result = array(
            'data' => $isId,
            'code' => 1,
            'msg' => "数据修改成功"
        );
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }
    public function getMyRawList()
    {
        session_start();
        $body = file_get_contents('php://input');
        $params = json_decode($body);
        $sql = "{$params->type} LIKE '%{$params->searchKey}%'";
        if ($params->status === '') {
            $sql2 = '';
        } else {
            $sql2 = " status = '{$params->status}'";
        }
        if ($params->orderType == "") {
            $order = "";
        } else {
            $order = "convert({$params->orderType} using gbk) COLLATE gbk_chinese_ci {$params->order}";
        }

        $Raw_ConsultM = new Raw_ConsultM();
        $list = $Raw_ConsultM->where('creator', $_SESSION['userInfo']['username'])->where($sql)->where($sql2)->order($order)->paginate($params->size, false, [
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
    public function deleteRaw()
    {
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
        Raw_ConsultM::where('id', $params['id'])->delete();
        $result = array(
            'data' => null,
            'code' => 1,
            'msg' => "删除成功"
        );
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }
}

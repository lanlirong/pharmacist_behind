<?php

namespace app\index\controller\interaction;

use think\Controller;
use app\index\model\interaction\Interaction as InteractionM;
use app\index\model\interaction\Raw_interaction as Raw_InteractionM;

class AddRaw extends Controller
{
    private function checkRaw($name, $interaction)
    {
        $InteractionM = new InteractionM();
        $sql = "name = '" . $name . "' and interaction = '" . $interaction . "'";
        $Disease = $InteractionM->where($sql)->find();
        if ($Disease) {
            return $Disease->id;
        } else {
            return false;
        }
    }
    public function addRaw()
    {
        $check = checkUser('21');
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

        $raw_disease = new Raw_InteractionM($params);
        $raw_disease->allowField(true)->save();
        $result = array(
            'data' => null,
            'code' => 1,
            'msg' => "数据提交成功"
        );
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }
    public function updateRaw()
    {
        $check = checkUser('16');
        if (!$check[0]) {
            echo json_encode($check[1], JSON_UNESCAPED_UNICODE);
            return;
        }
        $body = file_get_contents('php://input');
        $params = json_decode($body);
        $params->updateTime = date('Y-m-d H:i:s', time());
        $params->status = 0;

        $isId = $this->checkRaw($params->name, $params->interaction);
        if ($isId) { // 从已有库中新增审核记录
            $params->isNew = $isId;
            // unset($params->id);;
            unset($params->id);
            $params->creator = $_SESSION['userInfo']['username'];
            $raw_disease = new Raw_InteractionM($params);
            $raw_disease->allowField(true)->save();
        } else {
            $raw_disease = new Raw_InteractionM();
            $raw_disease->allowField(true)->save($params, ['id' => $params->id]);
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

        $Raw_InteractionM = new Raw_InteractionM();
        $list = $Raw_InteractionM->where('creator', $_SESSION['userInfo']['username'])->where($sql)->where($sql2)->order($order)->paginate($params->size, false, [
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
        Raw_InteractionM::where('id', $params['id'])->delete();
        $result = array(
            'data' => null,
            'code' => 1,
            'msg' => "删除成功"
        );
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }
}

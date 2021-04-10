<?php

namespace app\admin\controller\disease;

use think\Controller;
use app\admin\model\disease\Disease as DiseaseM;
use app\admin\model\disease\Raw_disease as Raw_DiseaseM;

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

        $Raw_DiseaseM = new Raw_DiseaseM();
        $list = $Raw_DiseaseM->where($sql2)->where($sql)->where($sql3)->order($order)->paginate($params->size, false, [
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
            $Raw_DiseaseM = new Raw_DiseaseM();
            $disease = $Raw_DiseaseM->where('id', $params['id'])->find();
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
            $Raw_DiseaseM = new Raw_DiseaseM;
            $Raw_DiseaseM->save([
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
            $Raw_DiseaseM = new Raw_DiseaseM();
            $raw = $Raw_DiseaseM->where('id', $params['id'])->find();
            if ($raw &&  $raw->isNew) { //更新
                $raw->id = $raw->isNew;
                $disease = json_decode(json_encode($raw), true);
                $DiseaseM = new DiseaseM();
                $DiseaseM->allowField(true)->save($disease, ['id' => $raw->id]);
            } else {  // 新增
                $raw->id = Get8Str(8);
                $raw->reviewer = $_SESSION['userInfo']['username'];
                $disease = json_decode(json_encode($raw), true);
                $DiseaseM = new DiseaseM($disease);
                $DiseaseM->allowField(true)->save();
            }

            $Raw_DiseaseM = new Raw_DiseaseM;
            $Raw_DiseaseM->save([
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

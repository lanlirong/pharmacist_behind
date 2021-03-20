<?php

namespace app\index\controller\interaction;

use think\Controller;
use app\index\model\interaction\Interaction as InteractionM;


class Index extends Controller
{
    public function getList()
    {
        $params = input('param.');
        $InteractionM = new InteractionM();
        $searchKey = $params['searchKey'];
        $sql = "name LIKE '%{$searchKey}%'";
        $interactions = $InteractionM->where($sql)->group('name')->select();
        $names = array_column($interactions, 'name');

        $interObjs = [];
        for ($i = 0; $i < count($names); $i++) {
            $interactions = $InteractionM->where('name', $names[$i])->select();
            $temp = array_column($interactions, 'interaction');

            $interObj = new class
            {
            };

            $interObj->name = $names[$i];
            $interObj->interaction = join(' || ', $temp);
            array_push($interObjs,  $interObj);
        }

        $result = array(
            'data' => $interObjs,
            'code' => 1,
            'msg' => "查询成功"
        );
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }
    public function getOne()
    {
        $params = input('param.');
        if ($params == [] || $params['name'] == '') {
            $result = array(
                'data' => null,
                'code' => 3,
                'msg' => "出现异常，name没有传值"
            );
        } else {
            $InteractionM = new InteractionM();
            $interaction = $InteractionM->where('name', $params['name'])->select();
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
    public function getInteractionCount()
    {
        $InteractionM = new InteractionM();
        $count = $InteractionM->count();
        $result = array(
            'data' => $count,
            'code' => 1,
            'msg' => "查询成功"
        );
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }
}

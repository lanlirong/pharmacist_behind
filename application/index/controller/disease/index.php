<?php

namespace app\index\controller\disease;

use think\Controller;
use app\index\model\disease\Disease as DiseaseM;


class Index extends Controller
{
    public function getList()
    {
        $params = input('param.');
        $DiseaseM = new DiseaseM();
        $searchKey = $params['searchKey'];
        $sql = "name LIKE '%{$searchKey}%'";

        if (array_key_exists('order', $params) && in_array($params['order'], ['asc', 'desc'])) {
            $order = "convert(name using gbk) COLLATE gbk_chinese_ci {$params['order']}";
        } else {
            $order = '';
        }

        $diseases = $DiseaseM->where($sql)->order($order)->paginate($params['size'], false, [
            'page' =>  $params['page'],
        ]);
        $result = array(
            'data' => $diseases,
            'code' => 1,
            'msg' => "查询成功"
        );
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }
    public function getOne()
    {
        $params = input('param.');
        if ($params == [] || $params['id'] == '') {
            $result = array(
                'data' => null,
                'code' => 3,
                'msg' => "出现异常，id没有传值"
            );
        } else {
            $DiseaseM = new DiseaseM();
            $disease = $DiseaseM->where('id', $params['id'])->find();
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
    public function getDiseaseCount()
    {
        $DiseaseM = new DiseaseM();
        $count = $DiseaseM->count();
        $result = array(
            'data' => $count,
            'code' => 1,
            'msg' => "查询成功"
        );
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }
}

<?php

namespace app\index\controller\drug;

use think\Controller;
use app\index\model\drug\Drug as DrugM;

class Index extends Controller
{

    public function getList()
    {
        $params = input('param.');
        $types = ['drug_name', 'drug_brand', 'bar_code', 'constituents', 'disease'];
        $sql = "{$types[$params['type']]} LIKE '%{$params['searchKey']}%'";
        if ($params['orderType'] == "") {
            $order = "";
        } else {
            $order = "convert({$params['orderType']} using gbk) COLLATE gbk_chinese_ci {$params['order']}";
        }

        $DrugM = new DrugM();
        $list = $DrugM->where($sql)->order($order)->paginate($params['size'], false, [
            'query' => request()->param(),
        ]);
        $result = array(
            'data' => $list,
            'code' => 1,
            'msg' => "查询成功"
        );


        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }
    public function getDrugByID()
    {
        $params = input('param.');
        if ($params == [] || $params['id'] == '') {
            $result = array(
                'data' => null,
                'code' => 3,
                'msg' => "出现异常，id没有传值"
            );
        } else {
            $DrugM = new DrugM();
            $list = $DrugM->where('id', $params['id'])->find();
            if ($list) {
                $list->picture = 'http://' . $_SERVER['SERVER_NAME'] . '/static' . $list->picture;

                $result = array(
                    'data' => $list,
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
}

<?php

namespace app\index\controller\drug;

use think\Controller;
use app\index\model\drug\Drug as DrugM;

class Index extends Controller
{

    public function getList()
    {
        $params = input('param.');
        $DrugM = new DrugM();
        $list = $DrugM->where('drug_name', $params['searchKey'])->paginate($params['size'], false, [
            'query' => request()->param(),
        ]);
        $result = array(
            'data' => $list,
            'code' => 1,
            'msg' => "查询成功"
        );


        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }
}

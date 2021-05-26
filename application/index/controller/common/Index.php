<?php

namespace app\index\controller\common;

use think\Controller;
use app\index\model\drug\Drug as DrugM;
use app\index\model\disease\Disease as DiseaseM;


class Index extends Controller
{
    public function getSimpleList()
    {
        $body = file_get_contents('php://input');
        $params = json_decode($body);
        $DiseaseM = new DiseaseM();
        $Disease = $DiseaseM->where('name', $params->searchKey)->find();
        $DrugM = new DrugM();
        $sql = "drug_name like '%" . $params->searchKey . "%'";
        $Drug = $DrugM->where($sql)->find();

        $list = array("drug" => "", "disease" => "");
        if ($Drug) $list['drug'] = true;
        if ($Disease) $list['disease'] = $Disease->id;

        $result = array(
            // 'test' => $ids,
            'data' => $list,
            'code' => 1,
            'msg' => "查询成功"
        );


        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }
}

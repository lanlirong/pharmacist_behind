<?php

namespace app\admin\controller\drug;

use think\Controller;
use app\admin\model\drug\Drug as DrugM;
use app\admin\model\drug\Indications as IndicationsM;


class Index extends Controller
{
    public function getList()
    {
        $body = file_get_contents('php://input');
        $params = json_decode($body);
        $sql = "{$params->type} LIKE '%{$params->searchKey}%'";
        if ($params->orderType == "") {
            $order = "";
        } else {
            $order = "convert({$params->orderType} using gbk) COLLATE gbk_chinese_ci {$params->order}";
        }

        $DrugM = new DrugM();
        $list = $DrugM->where($sql)->order($order)->paginate($params->size, false, [
            'page' =>  $params->page,
        ]);
        // 查询主治疾病
        $IndicationsM = new IndicationsM();
        for ($i = 0; $i < count($list); $i++) {
            $indications = $IndicationsM->where('id', $list[$i]->id)->select();
            $temp = array_column($indications, 'disease');
            $list[$i]->mainDiseases = $temp;
        }
        $result = array(
            // 'test' => $sql,
            'data' => $list,
            'code' => 1,
            'msg' => "查询成功"
        );

        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }
    public function getFilterList()
    {
        $body = file_get_contents('php://input');
        $params = json_decode($body);
        // 查询主治疾病时情况不一样
        $IndicationsM = new IndicationsM();
        if ($params->type == 4) {
            $indications = $IndicationsM->where('disease', $params->searchKey)->select();
            $ids = array_column($indications, 'id');
            $idStr = "(" . join(',', $ids) . ")";
            $sql = "id in " . $idStr;
        } else {
            $types = ['drug_name', 'drug_brand', 'bar_code', 'constituents'];
            $sql = "{$types[$params->type]} LIKE '%{$params->searchKey}%'";
        }

        $DrugM = new DrugM();
        $drug_type = $DrugM->where($sql)->group('drug_type')->field('drug_type, COUNT(drug_type) as count')->select();
        $nature_class = $DrugM->where($sql)->group('nature_class')->field('nature_class, COUNT(nature_class) as count')->select();
        $use_class = $DrugM->where($sql)->group('use_class')->field('use_class, COUNT(use_class) as count')->select();
        $manufacturer = $DrugM->where($sql)->group('manufacturer')->field('manufacturer, COUNT(manufacturer) as count')->select();

        $filterObj = new class
        {
        };
        $filterObj->drug_type = $drug_type;
        $filterObj->nature_class = $nature_class;
        $filterObj->use_class = $use_class;
        $filterObj->manufacturer = $manufacturer;

        $result = array(
            'data' => $filterObj,
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
            $drug = $DrugM->where('id', $params['id'])->find();
            // 查找主治疾病
            $IndicationsM = new IndicationsM();
            $indications = $IndicationsM->where('id', $params['id'])->select();
            if ($indications) {
                $temp = array_column($indications, 'disease');
                $drug->mainDiseases = $temp;
            }
            if ($drug) {
                $drug->picture = 'http://' . $_SERVER['SERVER_NAME'] . ':' . $_SERVER["SERVER_PORT"] . '/static' . $drug->picture;

                $result = array(
                    'data' => $drug,
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
    public function getDrugCount()
    {
        $DrugM = new DrugM();
        $count = $DrugM->count();
        $result = array(
            'data' => $count,
            'code' => 1,
            'msg' => "查询成功"
        );
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }
}

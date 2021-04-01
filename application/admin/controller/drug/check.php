<?php

namespace app\admin\controller\drug;

use think\Controller;
use app\admin\model\drug\Drug as DrugM;
use app\admin\model\drug\Raw_drug as Raw_drugM;
use app\admin\model\drug\Indications as IndicationsM;
use app\admin\model\drug\Raw_indications as Raw_indicationsM;

class Check extends Controller
{
    private function checkDrugByCode($bar_code)
    {
        $DrugM = new DrugM();
        $drug = $DrugM->where('bar_code', $bar_code)->find();
        if ($drug) {
            return true;
        } else {
            return false;
        }
    }
    public function getList()
    {
        $check = checkUser('10');
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
            $sql2 = " operator != '{$username}'";
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

        $Raw_drugM = new Raw_drugM();
        $list = $Raw_drugM->where($sql2)->where($sql)->where($sql3)->order($order)->paginate($params->size, false, [
            'page' =>  $params->page,
        ]);
        // 查询主治疾病
        $Raw_indicationsM = new Raw_indicationsM();
        for ($i = 0; $i < count($list); $i++) {
            $indications = $Raw_indicationsM->where('id', $list[$i]->id)->select();
            $temp = array_column($indications, 'disease');
            $list[$i]->mainDiseases = $temp;
        }
        $result = array(
            // 'test' => $check,
            'data' => $list,
            'code' => 1,
            'msg' => "查询成功"
        );

        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }
    public function getDrugByID()
    {
        $check = checkUser('10');
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
        } else {
            $Raw_drugM = new Raw_drugM();
            $drug = $Raw_drugM->where('id', $params['id'])->find();
            // 查找主治疾病
            $Raw_indicationsM = new Raw_indicationsM();
            $indications = $Raw_indicationsM->where('id', $params['id'])->select();
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
    public function checkDrug()
    {
        $check = checkUser('10');
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
            $Raw_drugM = new Raw_drugM;
            $Raw_drugM->save([
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
            $Raw_drugM = new Raw_drugM();
            $raw_drug = $Raw_drugM->where('id', $params['id'])->find();
            // json_decode(json_encode($obj), true)
            if ($raw_drug && $this->checkDrugByCode($raw_drug->bar_code)) { //更新
                $raw_drug->id = null;
                $drugM = new drugM();
                $drugM->allowField(true)->save($raw_drug, ['bar_code' => $raw_drug->bar_code]);
            } elseif ($raw_drug && !$this->checkDrugByCode($raw_drug->bar_code)) {  // 新增
                $raw_drug->id = null;
                $raw_drug = json_decode(json_encode($raw_drug), true);
                $drugM = new drugM($raw_drug);
                $drugM->allowField(true)->save();

                // $indications =  $params->mainDiseases;
                // for ($i = 0; $i < count($indications); $i++) {
                //     $raw_indications = new Raw_indicationsM();
                //     $raw_indications->id     = $raw_drug->id;
                //     $raw_indications->disease  = $indications[$i];
                //     $raw_indications->save();
                // }
            }

            $result = array(
                'test' => $drugM,
                'data' => null,
                'code' => 1,
                'msg' => "审核成功"
            );
            echo json_encode($result, JSON_UNESCAPED_UNICODE);
        }




        // // 查找主治疾病
        // $Raw_indicationsM = new Raw_indicationsM();
        // $indications = $Raw_indicationsM->where('id', $params['id'])->select();
    }
}

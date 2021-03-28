<?php

namespace app\admin\controller\drug;

use think\Controller;
use app\admin\model\drug\Drug as DrugM;
use app\admin\model\drug\Indications as IndicationsM;


class Index extends Controller
{
    public function getList()
    {

        if (isset($_COOKIE[session_name()])) {
            // 权限验证
            Session_start();
            $sessionData = $_SESSION['userInfo']['limit'];
            if (!in_array('3', explode(',', $sessionData))) {
                $result = array(
                    'data' => null,
                    'code' => 5,
                    'msg' => "无操作权限"
                );
                echo json_encode($result, JSON_UNESCAPED_UNICODE);
                return;
            }

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
                // 'test' => ,
                'data' => $list,
                'code' => 1,
                'msg' => "查询成功"
            );
        } else {
            $result = array(
                'data' => null,
                'code' => 4,
                'msg' => "未登录，无操作权限"
            );
        }
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

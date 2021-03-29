<?php

namespace app\admin\controller\drug;

use think\Controller;
use app\admin\model\drug\Drug as DrugM;
use app\admin\model\drug\Indications as IndicationsM;

class Index extends Controller
{
    public function getList()
    {
        $check = checkUser('3');
        if (!$check[0]) {
            echo json_encode($check[1], JSON_UNESCAPED_UNICODE);
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
            // 'test' => $check,
            'data' => $list,
            'code' => 1,
            'msg' => "查询成功"
        );

        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }
    public function getDrugByID()
    {
        $check = checkUser('3');
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
    public function uploadDrugPicture()
    {
        // $body = file_get_contents('php://input');
        // $params = json_decode($body);
        header("Access-Control-Allow-Origin:http://localhost:7000");
        header("Access-Control-Allow-Credentials:true");
        header("Access-Control-Allow-Methods:GET, POST, OPTIONS, DELETE");
        header("Access-Control-Allow-Headers:DNT,X-Mx-ReqToken,Keep-Alive,User-Agent,X-Requested-With,If-Modified-Since,Cache-Control,Content-Type, Accept-Language, Origin, Accept-Encoding");
        if (request()->isOptions()) {
            exit();
        }
        $file = $_FILES['file'];

        if ($file['type'] == "image/jpeg" && $file['size'] <= 800000) {
            copy($file['tmp_name'], ROOT_PATH . 'public/static/' . $file['name']);

            // $file->move(ROOT_PATH . 'public' . DS . 'temp/', $file['name']);
            $result = array(
                'data' => 'http://' . $_SERVER['SERVER_NAME'] . ':' . $_SERVER["SERVER_PORT"] .  '/static/' . $file['name'],
                'code' => 1,
                'msg' => "上传成功"
            );
        } else {
            $result = array(
                'data' => null,
                'code' => 1,
                'msg' => "上传失败"
            );
        }
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }
    public function getDrugCount()
    {
        $check = checkUser('3');
        if (!$check[0]) {
            echo json_encode($check[1], JSON_UNESCAPED_UNICODE);
            return;
        }
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

<?php

namespace app\admin\controller\disease;

use think\Controller;
use app\admin\model\disease\Disease as DiseaseM;

class Index extends Controller
{
    public function getList()
    {
        $check = checkUser('18');
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

        $DiseaseM = new DiseaseM();
        $list = $DiseaseM->where($sql)->order($order)->paginate($params->size, false, [
            'page' =>  $params->page,
        ]);

        $result = array(
            'data' => $list,
            'code' => 1,
            'msg' => "查询成功"
        );

        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }
    public function getOneByID()
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
            $article = $DiseaseM->where('id', $params['id'])->find();
            if ($article) {
                $result = array(
                    'data' => $article,
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
    public function deleteOne()
    {
        $check = checkUser('12');
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
        DiseaseM::where('id', $params['id'])->delete();
        $result = array(
            'data' => null,
            'code' => 1,
            'msg' => "删除成功"
        );
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }
    public function update()
    {
        // $check = checkUser('11');
        // if (!$check[0]) {
        //     echo json_encode($check[1], JSON_UNESCAPED_UNICODE);
        //     return;
        // }
        // $body = file_get_contents('php://input');
        // $params = json_decode($body);
        // // $DiseaseM = new DiseaseM();
        // // $article = $DiseaseM->where('id', $params->id)->find();
        // $article->is

        // $params->updateTime = date('Y-m-d H:i:s', time());
        // $params->status = 0;
        // $raw_article = new Raw_DiseaseM();
        // $raw_article->allowField(true)->save($params, ['id' => $params->id]);
        // $result = array(
        //     'data' => null,
        //     'code' => 1,
        //     'msg' => "数据修改成功"
        // );
        // echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }
    public function getCount()
    {
        $check = checkUser('3');
        if (!$check[0]) {
            echo json_encode($check[1], JSON_UNESCAPED_UNICODE);
            return;
        }
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

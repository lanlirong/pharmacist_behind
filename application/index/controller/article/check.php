<?php

namespace app\index\controller\article;

use think\Controller;
use app\index\model\article\Article as ArticleM;
use app\index\model\article\Raw_article as Raw_ArticleM;

class Check extends Controller
{
    public function getRawList()
    {
        $check = checkUser('15');
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
            $sql2 = " creator != '{$username}'";
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

        $Raw_ArticleM = new Raw_ArticleM();
        $list = $Raw_ArticleM->where($sql2)->where($sql)->where($sql3)->order($order)->field('id,description, title,isNew, status, createTime, updateTime, reviewer, creator')->paginate($params->size, false, [
            'page' =>  $params->page,
        ]);

        $result = array(
            // 'test' => $check,
            'data' => $list,
            'code' => 1,
            'msg' => "查询成功"
        );

        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }
    public function getCheckByID()
    {
        $params = input('param.');
        if ($params == [] || $params['id'] == '') {
            $result = array(
                'data' => null,
                'code' => 3,
                'msg' => "出现异常，id没有传值"
            );
        } else {
            $Raw_ArticleM = new Raw_ArticleM();
            $article = $Raw_ArticleM->where('id', $params['id'])->find();
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
    public function check()
    {
        $check = checkUser('15');
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
            $Raw_ArticleM = new Raw_ArticleM;
            $Raw_ArticleM->save([
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
            $Raw_ArticleM = new Raw_ArticleM();
            $raw = $Raw_ArticleM->where('id', $params['id'])->find();
            if ($raw &&  $raw->isNew) { //更新
                $raw->id = $raw->isNew;
                $article = json_decode(json_encode($raw), true);
                $ArticleM = new ArticleM();
                $ArticleM->allowField(true)->save($article, ['id' => $raw->id]);
            } else {  // 新增
                $raw->id = null;
                $raw->reviewer = $_SESSION['userInfo']['username'];
                $article = json_decode(json_encode($raw), true);
                $ArticleM = new ArticleM($article);
                $ArticleM->allowField(true)->save();
            }

            $Raw_ArticleM = new Raw_ArticleM;
            $Raw_ArticleM->save([
                'status'  => 1,
                'reviewer' => $_SESSION['userInfo']['username']
            ], ['id' => $params['id']]);

            $result = array(
                // 'test' => $article,
                'data' => null,
                'code' => 1,
                'msg' => "审核成功"
            );
            echo json_encode($result, JSON_UNESCAPED_UNICODE);
        }
    }
}

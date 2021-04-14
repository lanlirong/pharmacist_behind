<?php

namespace app\index\controller\science;

use think\Controller;
use app\index\model\science\Article as ArticleM;


class Index extends Controller
{
    public function getList()
    {
        $params = input('param.');
        $ArticleM = new ArticleM();
        $searchKey = $params['searchKey'];
        $sql = "title LIKE '%{$searchKey}%'";

        if (array_key_exists('order', $params) && in_array($params['order'], ['asc', 'desc'])) {
            $order = "convert(name using gbk) COLLATE gbk_chinese_ci {$params['order']}";
        } else {
            $order = '';
        }

        $article = $ArticleM->where($sql)->order($order)->field('id, title, updateTime,description, creator')->paginate($params['size'], false, [
            'page' =>  $params['page'],
        ]);
        $result = array(
            'data' => $article,
            'code' => 1,
            'msg' => "查询成功"
        );
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }
    public function getHotList()
    {
        $params = input('param.');
        $ArticleM = new ArticleM();

        $article = $ArticleM->order('id', 'desc')->limit(6)->select();
        $result = array(
            'data' => $article,
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
            $ArticleM = new ArticleM();
            $article = $ArticleM->where('id', $params['id'])->find();
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
    public function getArticleCount()
    {
        $ArticleM = new ArticleM();
        $count = $ArticleM->count();
        $result = array(
            'data' => $count,
            'code' => 1,
            'msg' => "查询成功"
        );
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }
}

<?php

namespace app\admin\controller\article;

use think\Controller;
use app\admin\model\article\Article as ArticleM;
use app\admin\model\article\Raw_article as Raw_articleM;

class AddRaw extends Controller
{
    private function checkRawById($id)
    {
        $Raw_articleM = new Raw_articleM();
        $drug = $Raw_articleM->where('id', $id)->find();
        if ($drug) {
            return $drug->id;
        } else {
            return false;
        }
    }
    public function addRaw()
    {
        $check = checkUser('11');
        if (!$check[0]) {
            echo json_encode($check[1], JSON_UNESCAPED_UNICODE);
            return;
        }
        $body = file_get_contents('php://input');
        $params = json_decode($body);
        $params->isNew = 0;

        $params->createTime = date('Y-m-d H:i:s', time());
        $params->updateTime  = $params->createTime;
        $params->creator  =  $_SESSION['userInfo']['username'];

        $imgUrls = $params->imgUrls;

        for ($i = 0; $i < count($imgUrls); $i++) {
            rename(ROOT_PATH . 'public/static/upload/tempArticle/' . $imgUrls[$i], ROOT_PATH . 'public/static/upload/article/' . $imgUrls[$i]);
        }
        $params->content = str_replace('/static/upload/tempArticle/', '/static/upload/article/', $params->content);

        $raw_article = new Raw_articleM($params);
        $raw_article->allowField(true)->save();
        $result = array(
            'test' => $_SESSION['uploadArticleName'],
            'data' => null,
            'code' => 1,
            'msg' => "数据提交成功"
        );
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }
    public function updateRaw()
    {
        $check = checkUser('11');
        if (!$check[0]) {
            echo json_encode($check[1], JSON_UNESCAPED_UNICODE);
            return;
        }
        $body = file_get_contents('php://input');
        $params = json_decode($body);
        $params->updateTime = date('Y-m-d H:i:s', time());
        $params->status = 0;
        $imgUrls = $params->imgUrls;
        for ($i = 0; $i < count($imgUrls); $i++) {
            rename(ROOT_PATH . 'public/static/upload/tempArticle/' . $imgUrls[$i], ROOT_PATH . 'public/static/upload/article/' . $imgUrls[$i]);
        }
        $params->content = str_replace('/static/upload/tempArticle/', '/static/upload/article/', $params->content);


        $isId = $this->checkRawById($params->id);
        if ($isId) { // 从已有库中新增审核记录
            $params->isNew = $isId;
            $params->id = null;
            $params->creator = $_SESSION['userInfo']['username'];
            $raw_article = new Raw_articleM($params);
            $raw_article->allowField(true)->save();
        } else {
            $raw_article = new Raw_articleM();
            $raw_article->allowField(true)->save($params, ['id' => $params->id]);
        }
        $result = array(
            'data' => null,
            'code' => 1,
            'msg' => "数据修改成功"
        );
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }
    public function getMyRawList()
    {
        session_start();
        $body = file_get_contents('php://input');
        $params = json_decode($body);
        $sql = "{$params->type} LIKE '%{$params->searchKey}%'";
        if ($params->status === '') {
            $sql2 = '';
        } else {
            $sql2 = " status = '{$params->status}'";
        }
        if ($params->orderType == "") {
            $order = "";
        } else {
            $order = "convert({$params->orderType} using gbk) COLLATE gbk_chinese_ci {$params->order}";
        }

        $Raw_articleM = new Raw_articleM();
        $list = $Raw_articleM->where('creator', $_SESSION['userInfo']['username'])->where($sql)->where($sql2)->order($order)->field('id, title,isNew, status, createTime, updateTime, reviewer, creator')->paginate($params->size, false, [
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
    public function deleteRaw()
    {
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
        Raw_articleM::where('id', $params['id'])->delete();
        $result = array(
            'data' => null,
            'code' => 1,
            'msg' => "删除成功"
        );
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }
    public function uploadPicture()
    {
        $check = checkUser('11');
        if (!$check[0]) {
            echo json_encode($check[1], JSON_UNESCAPED_UNICODE);
            return;
        }
        $file = $_FILES['file'];
        if (($file['type'] == "image/jpeg" || $file['type'] == "image/png") && $file['size'] <= 10 * 1024 * 1024) {
            $fileName = $_SESSION['userInfo']['id'] . time()  . 'article.jpg';
            rename($file['tmp_name'], ROOT_PATH . 'public/static/upload/tempArticle/' . $fileName);

            $url = 'http://' . $_SERVER['SERVER_NAME'] . ':' . $_SERVER["SERVER_PORT"] . '/static';
            $picture = '/upload/tempArticle/' . $fileName;
            $result = array(
                'fileName' =>  $fileName,
                'data' =>  $url . $picture,
                'code' => 1,
                'msg' => "上传成功"
            );
        } else {
            $result = array(
                'data' => false,
                'code' => 1,
                'msg' => "上传失败"
            );
        }
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }
}

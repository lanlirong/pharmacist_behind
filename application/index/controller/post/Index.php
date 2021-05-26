<?php

namespace app\index\controller\post;

use think\Controller;
use app\index\model\post\post as PostM;
use app\index\model\user\Index_user as UserM;


class Index extends Controller
{

    public function publish()
    {
        $body = file_get_contents('php://input');
        $params = json_decode($body);

        $params->createTime = date('Y-m-d H:i:s', time());
        $params->like = 0;
        $params->comment = null;

        $post = new PostM($params);
        $post->allowField(true)->save();

        $result = array(
            'data' => null,
            'code' => 1,
            'msg' => "发布成功"
        );
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }
    public function getList()
    {
        $body = file_get_contents('php://input');
        $params = json_decode($body);

        $postM = new PostM();
        $order = '';
        $sql = '';
        if ($params->type == 1) {
            $order = ['createTime' => 'desc'];
        } elseif ($params->type == 2) {
            $order = ['like' => 'desc'];
        } elseif ($params->type == 0) {
            session_start();
            if (array_key_exists("index_user", $_SESSION)) {
                $id = $_SESSION['index_user']['id'];
                $sql = 'userId ="' . $id . '"';
            }
        }
        $list = $postM->where($sql)->order($order)->paginate($params->size, false, [
            'page' =>  $params->page,
        ]);
        $userM = new UserM();
        for ($i = 0; $i < count($list); $i++) {
            $user = $userM->where('id',  $list[$i]->userId)->find();

            $list[$i]->name = $user->name;
            $list[$i]->avatar = $user->avatar;
        }



        $result = array(
            'data' => $list,
            'code' => 1,
            'msg' => "发布成功"
        );
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }
}

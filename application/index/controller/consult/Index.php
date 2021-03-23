<?php

namespace app\index\controller\consult;

use think\Controller;
use app\index\model\consult\Question as QuestionM;
use app\index\model\consult\Recommend as RecommendModel;

class Index extends Controller
{
  public function getList() // 问题列表
  {
    $body = file_get_contents('php://input');
    $params = json_decode($body);
    $searchKey = $params->searchKey;
    switch ($params->type) {
      case 0:
        $sql = "Q_content LIKE '%{$searchKey}%' or Q_answer LIKE '%{$searchKey}%'  ";
        break;
      case 1: // 书名
        $sql = "B_name LIKE '%{$searchKey}%' ";
        break;
      default:
        $sql = '';
        break;
    }
    $QuestionM = new QuestionM();
    $list = $QuestionM->where($sql)->paginate($params->size, false, [
      'query' => $params->page,
    ]);
    $result = array(
      // 'test' => $ids,
      'data' => $list,
      'code' => 1,
      'msg' => "查询成功"
    );
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
  }


  public function getOne()   // 问题详情
  {
    $params = input('param.');
    $id = trim($params['id']);
    if ($params == [] || $id == '') {
      $result = array(
        'data' => null,
        'code' => 3,
        'msg' => "出现异常，id没有传值"
      );
    } else {
      $QuestionM = new QuestionM();
      // 每页数据集
      $list = $QuestionM->where('id', $id)->find();
      if ($list) {
        $result = array(
          'data' => $list,
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
  public function getConsultCount()
  {
    $QuestionM = new QuestionM();
    $count = $QuestionM->count();
    $result = array(
      'data' => $count,
      'code' => 1,
      'msg' => "查询成功"
    );
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
  }
  public function recommend() // 推荐数据的获取
  {
    // 随机获取六条推荐数据
    // $id = Db::query('SELECT * FROM recommend  ORDER BY  RAND() LIMIT 6');
    $RecommendModel = new RecommendModel();
    // 每页数据集
    $Recommend = $RecommendModel->query("SELECT * FROM recommend  ORDER BY  RAND() LIMIT 6");
    echo json_encode($Recommend, JSON_UNESCAPED_UNICODE);
  }
  public function getBooks()
  {
    $QuestionM = new QuestionM();
    $books = $QuestionM->group('B_ISBN')->field('B_ISBN, B_name')->select();
    $result = array(
      'data' => $books,
      'code' => 1,
      'msg' => "查询成功"
    );
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
  }
}

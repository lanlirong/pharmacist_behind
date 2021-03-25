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
    if ($params->way == 0) {
      switch ($params->type) {
        case 0:
          $sql = "Q_content LIKE '%{$searchKey}%' or Q_answer LIKE '%{$searchKey}%'  or K_link LIKE '%{$searchKey}%'";
          break;
        case 1:  //问题名称
          $sql = "Q_content LIKE '%{$searchKey}%' ";
          break;
        case 2: //回答内容
          $sql = "Q_answer LIKE '%{$searchKey}%' ";
          break;
        case 3: //知识链接
          $sql = "K_link LIKE '%{$searchKey}%' ";
          break;
        default:
          $sql = '';
          break;
      }
    } elseif ($params->way == 1) {
      // 按书名
      $sql = "B_ISBN like '%{$searchKey}%'";
    }

    $QuestionM = new QuestionM();
    $list = $QuestionM->where($sql)->paginate($params->size, false, [
      'page' => $params->page,
    ]);
    $result = array(
      // 'test' => $params->page,
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
      // 拼接答案url
      if ($list->isanswerUrl === 0) {
        $answerUrl1s =  explode(',', $list->answerUrl1);
        foreach ($answerUrl1s as $k => $v) {
          $answerUrl1s[$k] = 'http://' . $_SERVER['SERVER_NAME'] . '/static/images/' . $v;
        }
        $list->answerUrl1 = $answerUrl1s;
        if ($list->answerUrl2) {
          $answerUrl2s =  explode(',', $list->answerUrl2);
          foreach ($answerUrl2s as $k => $v) {
            $answerUrl2s[$k] = 'http://' . $_SERVER['SERVER_NAME'] . '/static/images/' . $v;
          }
          $list->answerUrl2 = $answerUrl2s;
        }
      }
      // 拼接链接url
      if ($list->islinkUrl === 1) {
        $linkUrl1s =  explode(',', $list->linkUrl1);
        foreach ($linkUrl1s as $k => $v) {
          $linkUrl1s[$k] = 'http://' . $_SERVER['SERVER_NAME'] . '/static/images/' . $v;
        }
        $list->linkUrl1 = $linkUrl1s;
        if ($list->linkUrl2) {
          $linkUrl2s =  explode(',', $list->linkUrl2);
          foreach ($linkUrl2s as $k => $v) {
            $linkUrl2s[$k] = 'http://' . $_SERVER['SERVER_NAME'] . '/static/images/' . $v;
          }
          $list->linkUrl2 = $linkUrl2s;
        }
      }
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

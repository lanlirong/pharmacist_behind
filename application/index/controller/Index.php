<?php
namespace app\index\controller;
use think\Db;
use think\Controller;
use app\index\model\Question as QuestionModel;
use app\index\model\Recommend as RecommendModel;
class Index extends Controller
{
	
    public function recommend()// 推荐数据的获取
    {
      // 随机获取六条推荐数据
      // $id = Db::query('SELECT * FROM recommend  ORDER BY  RAND() LIMIT 6');
      $RecommendModel = new RecommendModel();
      // 每页数据集
      $Recommend = $RecommendModel->query("SELECT * FROM recommend  ORDER BY  RAND() LIMIT 6");
      echo json_encode($Recommend,JSON_UNESCAPED_UNICODE);
  
    } 
    public function resultlist()// 问题列表
    {
      $body = file_get_contents('php://input');
      $data=json_decode($body);
      $search=$data->search;
      $way=$data->way;
      $pagenum = $data->pagenum;
      $pagesize = $data->pagesize;

      // $search='糖尿病';
      // $way=1;
     if(strlen($search)==0)
     {
       // 输入为空
      echo "<script>alert('输入不能为空!');document.location = '/index/index/search';</script>";
      die;
     }
     elseif ($way == 1){
       // 按相关度查询
     $sql = "MATCH (Q_content,Q_answer,K_type) AGAINST ('{$search}' IN NATURAL LANGUAGE MODE)";
      
     }
     elseif($way == 2) {
       // 按关键词查
       $sql = "MATCH (Q_content,Q_answer,K_type) AGAINST ('*{$search}*' IN BOOLEAN MODE)";
     }
    
      $QuestionModel = new QuestionModel();
      // 每页数据集
      $resultlist = $QuestionModel->where($sql)->paginate($pagesize,false, [
        'query' => request()->param(),
    ]);
      // 结果de总数量
      // $resultCount=$QuestionModel->where($sql)->count();
      // // 分页
      // $page = $resultlist->render();

        // echo json_encode($page,JSON_UNESCAPED_UNICODE);
       
        echo json_encode($resultlist,JSON_UNESCAPED_UNICODE);  
    } 

 
    public function detail()   // 问题详情
    {
      $data = input('param.');
      $id=trim($data['id']);
      // $id='010001';
      $QuestionModel = new QuestionModel();
      // 每页数据集
      $detail = $QuestionModel->where('id',$id)->find();
      echo json_encode($detail,JSON_UNESCAPED_UNICODE);
      // echo json_encode($id,JSON_UNESCAPED_UNICODE);
     }
    
}

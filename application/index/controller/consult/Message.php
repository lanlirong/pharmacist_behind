<?php
namespace app\index\controller\consult;
// use think\Db;
use think\Controller;
use app\index\model\consult\Drugconsult_check as ConsultCheckModel;
use app\index\model\consult\Error as ErrorModel;

class Message extends Controller
{ 
	public function DrugConsult_check() 
	{
	  $body = file_get_contents('php://input');
      $data=json_decode($body);
      // $search=$data->search;
      $ConsultCheckModel = new ConsultCheckModel();
      $ConsultCheckModel->data([
      	'Q_content' => trim($data->Q_content),
      	'K_type' => trim($data->k_type),
      	'Source' => trim($data->source),
      	'K_link' => trim($data->k_link),
      	'Q_answer' => trim($data->Q_answer),
      	'other' => trim($data->other),
      	'username' => trim($data->username),
      	'status' => 0
      	]);
      	$res=$ConsultCheckModel->save();
	
      	if ($res>0) {
	  		$result = array(
      	  'status'=>200,
      	  'msg'=>"提交成功，请等待审核"
      		);
	  	} else {
	  		$result = array(
      	  'status'=>300,
      	  'msg'=>"提交失败，请重新尝试"
      		);
	  	}

       echo json_encode($result,JSON_UNESCAPED_UNICODE);  
    
	}
	public function addError() 
	{
		$body = file_get_contents('php://input');
      $data=json_decode($body);
      // $search=$data->search;
      $ErrorModel = new ErrorModel();
      $ErrorModel->data([
      	'error' => trim($data->error),
      	'correct' => trim($data->correct),
      	'username' => trim($data->username),
      	'type' => $data->type,
      	'status' => 0
      	]);
      	$res=$ErrorModel->save();
	
      	if ($res>0) {
	  		$result = array(
      	  'status'=>200,
      	  'msg'=>"提交成功，请等待审核"
      		);
	  	} else {
	  		$result = array(
      	  'status'=>300,
      	  'msg'=>"提交失败，请重新尝试"
      		);
	  	}

       echo json_encode($result,JSON_UNESCAPED_UNICODE);  
	}

}
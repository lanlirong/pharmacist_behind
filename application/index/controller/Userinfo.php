<?php
namespace app\index\controller;
// use think\Db;
use think\Controller;
use app\index\model\Userinfo as UserinfoModel;

class Userinfo extends Controller
{ 
	public function login() {
	  $body = file_get_contents('php://input');
      $data=json_decode($body);
      $name=trim($data->name);
      $password=$data->password;
      $UserinfoModel = new UserinfoModel();
      $user = $UserinfoModel->where('username', $name)->find();
	 
	  if ($user->password == $password) {
	  	unset($user->password);
	  	$result = array(
        'status'=>200,
        'msg'=>"登录成功",
        'data'=>$user,
      	);
	  } else {
	  	$result = array(
        'status'=>300,
        'msg'=>"登录失败"
      	);
	  }
      echo json_encode($result,JSON_UNESCAPED_UNICODE);
	}
	public function register() {
	  $body = file_get_contents('php://input');
      $data=json_decode($body);
      $UserinfoModel = new UserinfoModel();

      $has= $UserinfoModel->where('username', trim($data->username))->find();
      if($has) {
      	$result = array(
        'status'=>300,
        'msg'=>"该登录名已存在"
      	);
	  } else {
	  	$UserinfoModel->data([
      	'username' => trim($data->username),
      	'password' => trim($data->password),
      	'name' => trim($data->name),
      	'sex' => trim($data->sex),
      	'tel' => trim($data->tel),
      	'email' => trim($data->email),
      	'job' => trim($data->job)
	
      	]);
      	 $res=$UserinfoModel->save();
	
      	if ($res>0) {
	  		$result = array(
      	  'status'=>200,
      	  'msg'=>"注册成功"
      		);
	  	} else {
	  		$result = array(
      	  'status'=>300,
      	  'msg'=>"注册失败"
      		);
	  	}
	  }
     
      echo json_encode($result,JSON_UNESCAPED_UNICODE);
  }
}
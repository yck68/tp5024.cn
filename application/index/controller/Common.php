<?php
namespace app\index\controller;
use think\Controller;

class Common extends Controller{
	
	// 返回信息
    public function returnMsg($code, $msg='', $data=[]){
    	$msg = [
			'code' => $code,
			'msg'  => $msg,
			'data' => $data
    	];

    	echo json_encode($msg);die();
    }
}
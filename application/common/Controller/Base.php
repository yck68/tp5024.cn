<?php
namespace app\common\controller;
use think\Controller;

class Base extends Controller{
	
	function __construct(){
		parent::__construct();
	}

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
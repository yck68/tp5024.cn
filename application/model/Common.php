<?php
namespace app\model;
use think\Model;
use traits\model\SoftDelete;

class Common extends Model
{
	// 自动时间戳
	protected $autoWriteTimestamp = true;
	// 软删除
	use SoftDelete;
    protected $deleteTime = 'delete_time';

    // 返回信息
    public function returnMsg($code, $msg='', $data=[]){
    	$msg = [
			'code' => $code,
			'msg'  => $msg,
			'data' => $data
    	];

    	echo json_encode($msg);
    	die();
    }
}
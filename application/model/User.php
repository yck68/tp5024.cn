<?php
namespace app\model;
use app\validate\User as UserValidate;

class User extends Common{

	/**
	 * 创建、编辑用户信息
	 * @Author   Kevin
	 * @DateTime 2020-10-20T11:37:41+0800
	 * @param    [type]                   $data [description]
	 * @return   [type]                         [description]
	 */
	public function saveUser($data){
		if(empty($data['id'])){
			$validate = new UserValidate();
			if(!$validate->scene('add')->check($data)){
				return $this->returnMsg(400, $validate->getError());
			}

			$user = new User($data);
			$res = $user->allowField(true)->save();
		}else{
			$validate = new UserValidate();
			if(!$validate->scene('edit')->check($data)){
				return $this->returnMsg(400, $validate->getError());
			}

			$id = $data['id'];
			unset($data['id']);
			$user = new User();
			$res = $user->allowField(true)->save($data, ['id'=>$id]);
		}
		if($res){
			session('accName', $data['nickname']);
			session('headimgurl', '/static/uploads/'.$data['headimgurl']);
			return $this->returnMsg(200, "操作成功！");
		}else{
			return $this->returnMsg(400, "操作失败！");
		}
	}
}
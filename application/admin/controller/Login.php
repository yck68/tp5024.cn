<?php
namespace app\admin\controller;

use think\Controller;
use think\Request;
use app\model\Admin as AdminModel;
class Login extends Controller{
	
	/**
	 * 登录页
	 * @return [type] [description]
	 */
	public function login(){
		$request = Request::instance();
		if($request->isPost()){
			$data = $request->param();
			return (new AdminModel())->login($data);
		}else{
			// if存在session
			$session = $request->session();
			if(!empty($session)){
				return $this->fetch('index/index');
			}
			// if存在cookie
			$cookie = $request->cookie();
			if(!empty($cookie['id'])){
				session('id', $cookie['id']);
				session('accName', $cookie['accName']);
				session('headimgurl', $cookie['headimgurl']);
				return $this->fetch('index/index');
			}

			// session、cookie都不存在
			return $this->fetch();
		}
	}

	/**
	 * 注册页
	 * @return [type] [description]
	 */
	public function register(){
		$request = Request::instance();
		if($request->isPost()){
			$data = $request->param();
			return (new AdminModel())->add($data);
		}else{
			return $this->fetch();
		}
	}

	/**
	 * 退出系统
	 * @return [type] [description]
	 */
	public function signOut(){
		session(null);

		setcookie('id', '', 0);
		setcookie('accName', '', 0);
		setcookie('headimgurl', '', 0);
		return ['code'=>200, 'msg'=>"退出成功！"];
	}
}
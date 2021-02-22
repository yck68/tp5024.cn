<?php
namespace app\admin\controller;

use app\common\controller\Base;

class Common extends Base{
	
	protected $beforeActionList = ['isLogin'];
	protected function isLogin(){
		if(!session('id')){
			return $this->redirect('admin/login/login');
		}
	}
}
<?php
namespace app\admin\controller;

class Index extends Common{

	/**
	 * 欢迎页
	 * @return [type] [description]
	 */
	public function index(){
		return $this->fetch();
	}
}
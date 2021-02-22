<?php
namespace app\index\controller;

class Talk extends Common{

	public function index(){
		return $this->fetch();
	}
}
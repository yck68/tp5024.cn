<?php
namespace app\index\controller;

class Miss extends Common{

	public function miss(){
		return $this->redirect('index/index');
	}
}
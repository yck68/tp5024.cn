<?php
namespace app\admin\controller;
use think\Db;
use think\Request;

class Area extends Common{

	/**
	 * 获取城市数据
	 * @Author   Kevin
	 * @DateTime 2020-10-17T16:11:17+0800
	 * @return   [type]                   [description]
	 */
	public function getCity(){
		$request = Request::instance();
		$id   = $request->param('id');
		$list = Db::name('area')->where('parentId', $id)->column('id,name');
		
		$this->returnMsg(200, '查询城市数据！', $list);
	}

	/**
	 * 获取县/区数据
	 * @Author   Kevin
	 * @DateTime 2020-10-17T16:11:59+0800
	 * @return   [type]                   [description]
	 */
	public function getArea(){
		$request = Request::instance();
		$id = $request->param('id');

		$list = Db::name('area')->where('parentId', $id)->column('id,name');
		$this->returnMsg(200, '查询县/区数据！', $list);
	}
}
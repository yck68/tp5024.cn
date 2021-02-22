<?php
namespace app\admin\controller;

use think\Request;
use app\model\Admin AS AdminModel;
use app\model\User AS UserModel;

class Admin extends Common{

	/**
	 * 账号列表
	 * @Author   Kevin
	 * @DateTime 2021-01-22T16:48:15+0800
	 * @return   [type]                   [description]
	 */
	public function lists(){
		return $this->fetch();
	}

	/**
	 * 获取“账号”信息
	 * @return [type] [description]
	 */
	public function getList(){
		$request = Request::instance();
		$page  = $request->param('page'); //当前页
		$limit = $request->param('limit'); //每页显示条数
		$acc   = $request->param('acc'); //账号
		$createTime = $request->param('create_time'); //创建时间

		if($acc){
			$where['acc'] = array('like', '%'.$acc.'%');
		}
		if($createTime){
			$dataArr = explode('~', $createTime);
			$where['create_time'] = array('between', [strtotime($dataArr[0]), strtotime($dataArr[1].' 23:59:59')]);
		}
		$where['role'] = 1;
		$count = AdminModel::where($where)->count('id');
		if(!$count){
			$this->returnMsg(404, '暂无数据！');
		}
		$list = AdminModel::where($where)
			->field('id,acc,password,role,state,create_time')
			->order('id desc')
			->page($page, $limit)
			->select();
		$returnArr = array(
			"code"   => 0,
			"msg"    => '获取数据！',
			"count"  => $count,
			"data"   => $list
		);
		echo json_encode($returnArr);
	}

	/**
	 * 【查看】账号
	 * @Author   Kevin
	 * @DateTime 2021-02-18T14:49:42+0800
	 * @return   [type]                   [description]
	 */
	public function detail(){
		$request = Request::instance();
		$id = $request->param('id');

		$info = AdminModel::where('id', $id)->field('acc,password,role,state,create_time')->find();
		$this->assign('info', $info);
		return $this->fetch();
	}

	/**
	 * 【编辑】账号
	 * @Author   Kevin
	 * @DateTime 2021-02-18T15:17:20+0800
	 * @return   [type]                   [description]
	 */
	public function edit(){
		$request = Request::instance();
		if($request->isPost()){
			$data = $request->param();
			return (new AdminModel())->edit($data);
		}else{
			$id = $request->param('id');
			$info = AdminModel::where('id', $id)->field('id,acc,password,role,state,create_time')->find();
			$this->assign('info', $info);
			return $this->fetch();
		}
	}

	/**
	 * 【删除】账号
	 * @Author   Kevin
	 * @DateTime 2021-02-18T16:38:01+0800
	 * @return   [type]                   [description]
	 */
	public function del(){
		$request = Request::instance();
		$id = $request->param('id');

		$ADelSuc = AdminModel::destroy($id);
		$UDelSuc = UserModel::destroy(['a_id'=>$id]);
		if($ADelSuc && $UDelSuc){
			$this->returnMsg(200, "删除成功！");
		}else{
			$this->returnMsg(300, "删除失败！");
		}
	}
}
<?php
namespace app\admin\controller;

use think\Request;
use think\Db;
use think\Session;
use app\model\User as UserModel;
class User extends Common{
	
	/**
	 * 用户列表
	 * @Author   Kevin
	 * @DateTime 2021-01-28T17:15:41+0800
	 * @return   [type]                   [description]
	 */
	public function lists(){
		return $this->fetch();
	}

	/**
	 * 获取数据
	 * @Author   Kevin
	 * @DateTime 2021-01-29T10:13:09+0800
	 * @return   [type]                   [description]
	 */
	public function getList(){
		$request = Request::instance();
		$param = $request->param();

		if(!empty($param['nickname'])){
			$where['nickname'] = array('like', '%'.$param['nickname'].'%');
		}
		if(empty($where)){
			$where = 1;
		}
		$count = UserModel::where($where)->count('id');
		if(!$count){
			$this->returnMsg(400, '暂无数据！');
		}

		$list = UserModel::where($where)
			->field('id,nickname,mobile,headimgurl,sex,birthday,star,integral,province,city,area,create_time')
			->order('id desc')
			->page($param['page'], $param['limit'])
			->select()
			->toArray();
		$area = Db::name('area')->order('id asc')->column('id, name');// 省市区
		$area[0] = '';
		$xzArr = getStarZuo();// 星座
		foreach($list as $k=>$v){
			switch ($v['sex']) {
				case 1:
					$sex = '男';
					break;
				case 2:
					$sex = '女';
					break;
				default:
					$sex = '保密';
					break;
			}
			$headimgurl = empty($v['headimgurl']) ? '/static/img/headImg.jpg' : '/static/uploads/'.$v['headimgurl'];
			$list[$k]['headimgurl']  = $headimgurl;
			$list[$k]['sex_title']   = $sex;
			$list[$k]['star_title']  = $xzArr[$v['star']];
			$list[$k]['prov_title']  = $area[$v['province']];
			$list[$k]['city_title']  = $area[$v['city']];
			$list[$k]['area_title']  = $area[$v['area']];
			unset($sex, $headimgurl);
		}

		$returnArr = array(
			"code"   => 0,
			"msg"    => '获取数据！',
			"count"  => $count,
			"data"   => $list
		);
		echo json_encode($returnArr);
	}

	/**
	 * 【查看】用户详情
	 * @return [type] [description]
	 */
	public function detail(){
		$request = Request::instance();
		$id = $request->param("id");

		$info = Db::name('user')
			->where('id', '=', $id)
			->field('nickname,mobile,headimgurl,sex,birthday,star,integral,province,city,area,address,create_time')
			->find();
		$area = Db::name('area')->where('id', 'in', [$info['province'],$info['city'],$info['area']])->column('id, name');
		$area[0] = '';
		$xzArr = getStarZuo(); //星座
		$info['star_title'] = $xzArr[$info['star']];
		$info['prov_title']  = $area[$info['province']];
		$info['city_title']  = $area[$info['city']];
		$info['area_title']  = $area[$info['area']];
		$info['create_time'] = date("Y-m-d H:i:s", $info['create_time']);
		$this->assign('info', $info);
		return $this->fetch();
	}

	/**
	 * 【编辑】用户详情
	 * @return [type] [description]
	 */
	public function edit(){
		$request = Request::instance();
		if($request->isPost()){
			$param = $request->param();

			$param['update_time'] = time();
			unset($param['file']);
			$editSuc = Db::name('user')->update($param);
			if($editSuc){
				$this->returnMsg(200, '操作成功！');
			}else{
				$this->returnMsg(400, '操作失败！');
			}
		}else{
			$id = $request->param('id');
			$info = Db::name('user')
				->where('id', '=', $id)
				->field('id,nickname,mobile,headimgurl,sex,birthday,star,integral,province,city,area,address,create_time')
				->find();
			// 省市区
			$iArea = Db::name('area')->where('id', 'in', [$info['province'],$info['city'],$info['area']])->column('id, name');
			$iArea[0] = '';
			$prov = Db::name('area')->where('parentId', '=', '-1')->column('id,name'); //获取省份
			$city = Db::name('area')->where('parentId', $info['province'])->column('id,name');// 获取城市
			$area = Db::name('area')->where('parentId', $info['city'])->column('id,name');// 获取地区
			$xzArr = getStarZuo();
			$info['star_title']  = $xzArr[$info['star']];
			$info['prov_title']  = $iArea[$info['province']];
			$info['city_title']  = $iArea[$info['city']];
			$info['area_title']  = $iArea[$info['area']];
			$info['create_time'] = date("Y-m-d H:i:s", $info['create_time']);
			
			$assign['info'] = $info;
			$assign['prov'] = $prov;
			$assign['city'] = $city;
			$assign['area'] = $area;
			$this->assign($assign);
			return $this->fetch();
		}
	}

	/**
	 * 【删除】用户详情
	 * @return [type] [description]
	 */
	public function del(){
		$request = Request::instance();
		$id = $request->param('id');

		$delSuc = UserModel::destroy($id);
		if($delSuc){
			$this->returnMsg(200, '删除成功！');
		}else{
			$this->returnMsg(400, '删除失败！');
		}
	}

	public function base(){
		$aid = Session::get('id');
		$info = Db::name('user')->where('a_id', $aid)->find();
		if($info){
			$info['star_title'] = getStarZuo()[$info['star']];
			// 获取城市
			$city = Db::name('area')->where('parentId', $info['province'])->column('id,name');
			// 获取地区
			$area = Db::name('area')->where('parentId', $info['city'])->column('id,name');
		}else{
			$city = $area = '';
			$acc = Db::name('admin')->where('id', $aid)->value('acc');
			// 初始化信息
			$info = [
				'id'         => '',
				'a_id'       => $aid,
				'mobile'     => $acc,
				'headimgurl' => '',
				'nickname'   => '',
				'sex'        => 0,
				'birthday'   => '',
				'star'       => '',
				'star_title' => '',
				'integral'   => 100, // 注册编辑加积分
				'province'   => '',
				'address'    => ''
			];
		}
		// 获取省份
		$province = Db::name('area')->where('parentId', -1)->column('id,name');

		$assign['info']     = $info;
		$assign['province'] = $province;
		$assign['city']     = $city;
		$assign['area']     = $area;
		$this->assign($assign);
		return $this->fetch();
	}

	/**
	 * 上传头像图片
	 * @Author   Kevin
	 * @DateTime 2020-10-20T09:51:12+0800
	 * @return   [type]                   [description]
	 */
	public function uploadHeadImg(){
		$request = Request::instance();
		$file = $request->file('file');
		if($file){
		    // 移动到框架应用根目录/public/static/uploads/ 目录下
		    $info = $file->validate(['ext'=>'jpg,png'])->rule('uniqid')->move('./static/uploads/haedimgs');
	        if($info){
	        	$this->returnMsg(200, '上传成功！', 'haedimgs/'.str_replace('\\', '/',$info->getSaveName()));
	        }else{
	            $this->returnMsg(400, $file->getError());
	        }
		}else{
			$this->returnMsg(400, '未上传图片！');
		}
	}

	/**
	 * 根据生日匹配星座
	 * @Author   Kevin
	 * @DateTime 2020-10-19T16:01:56+0800
	 * @return   [type]                   [description]
	 */
	public function mateStarZuo(){
		$request = Request::instance();
		$date = $request->param('date');
		
		$data = mateStarZuo($date);
		$this->returnMsg(200, '获取成功！', $data);
	}
}
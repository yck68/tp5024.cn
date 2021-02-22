<?php
namespace app\model;

use think\Db;
use app\validate\Admin as AdminValidate;

class Admin extends Common{

	protected $auto = ['pwd'];
    protected $insert = [];
    protected $update = [];
    
    /*protected function setPwdAttr($pwd){
    	return md5($pwd);
    }*/

    /**
     * 注册账号
     * @Author   Kevin
     * @DateTime 2020-10-14T17:59:22+0800
     * @param    [type]                   $data [description]
     */
	public function add($data){
		// 验证数据
		$validate = new AdminValidate();
		if(!$validate->scene('add')->check($data)){
			return $this->returnMsg(400, $validate->getError());
		}

		// 启动事务
		$isSuc = true;
		Db::startTrans();
		try{
			$time = time();
		    // 创建账号
			$aData['acc']         = $data['acc'];
			$aData['password']    = $data['pwd'];
			$aData['pwd']         = md5($data['pwd']);
			$aData['create_time'] = $time;
			$aData['update_time'] = $time;
			$a_id = Db::name('admin')->insertGetId($aData);

			// 创建用户记录
			$uData['a_id']        = $a_id;
			$uData['mobile']      = $data['acc'];
			$uData['integral']    = 100;
			$uData['create_time'] = $time;
			$uData['update_time'] = $time;
			Db::name('user')->insert($uData);
		    // 提交事务
		    Db::commit();
		}catch(\Exception $e){
		    // 回滚事务
		    $isSuc = false;
		    Db::rollback();
		}
		if($isSuc){
			$this->setCookieTime($a_id);
			$this->returnMsg(200, '注册成功！');
		}else{
			$this->returnMsg(300, '注册失败！');
		}
	}

	/**
	 * 编辑账号
	 * @Author   Kevin
	 * @DateTime 2021-02-18T16:22:51+0800
	 * @return   [type]                   [description]
	 */
	public function edit($data){
		// 验证数据
		$validate = new AdminValidate();
		if(!$validate->scene('edit')->check($data)){
			return $this->returnMsg(400, $validate->getError());
		}

		$aData['id']          = $data['id'];
		$aData['password']    = $data['pwd'];
		$aData['pwd']         = md5($data['pwd']);
		$aData['role']        = $data['role'];
		$aData['state']       = $data['state'];
		$aData['update_time'] = time();
		$saveSuc = Db::name('admin')->update($aData);
		if($saveSuc){
			$this->returnMsg(200, '操作成功！');
		}else{
			$this->returnMsg(300, '操作失败！');
		}
	}

	/**
	 * 登录系统
	 * @Author   Kevin
	 * @DateTime 2020-10-15T11:01:52+0800
	 * @param    [type]                   $data [description]
	 * @return   [type]                         [description]
	 */
	public function login($data){
		$info = Admin::where('acc',$data['acc'])->field('id,pwd,state')->find();
		if(!$info){
			$this->returnMsg(400, '账号不存在，请注册！');
		}
		if($info['state'] != 1){
			$this->returnMsg(400, '该账号已被禁用！');
		}
		if(md5($data['pwd']) !== $info['pwd']){
			$this->returnMsg(400, '账号或密码错误！');
		}
		$validate = new AdminValidate();
		if(!$validate->scene('login')->check($data)){
			$this->returnMsg(400, $validate->getError());
		}

		$intSuc = Db::name('user')->where('a_id', $info['id'])->setInc('integral'); // 登录加积分
		
		$remember = empty($data['remember']) ? 0 : $data['remember'];
		$this->setCookieTime($info['id'], $remember); // 设置session
		$this->returnMsg(200, '登录成功！');
	}

	/**
	 * 设置session
	 * @param [type]  $id       Admin表中ID
	 * @param integer $remember 是否记住密码
	 */
	public function setCookieTime($id, $remember=0){
		$user = Db::name('user')->where('a_id', $id)->field('nickname,mobile,headimgurl')->find();
		$accName = empty($user['nickname']) ? $user['mobile'] : $user['nickname'];
		$headimgurl = empty($user['headimgurl']) ? '/static/img/headImg.jpg' : '/static/uploads/'.$user['headimgurl'];
		session('id', $id);
		session('accName', $accName);
		session('headimgurl', $headimgurl);

		if($remember == 1){
			setcookie('id', $id, time()+3600*24*7);
			setcookie('accName', $accName, time()+3600*24*7);
			setcookie('headimgurl', $headimgurl, time()+3600*24*7);
		}
	}
}
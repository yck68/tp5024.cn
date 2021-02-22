<?php
namespace app\validate;
use think\Validate;

class Admin extends Validate{

	protected $rule = [
		['acc', 'require|regex:/^1[34578]\d{9}$/|unique:admin', '账号必填|账号格式错误|账号已存在'],
		['pwd', 'require|alphaNum|length:6,12|confirm', '密码必填|密码格式错误|密码长度6~12位|密码与确认密码不一致'],
		['pwd_confirm', 'require' ,'确认密码必填'],
		['verify', 'require|captcha' ,'验证码必填|验证码错误']
    ];
    
    protected $scene = [
    	'add'  => ['acc','pwd','pwd_confirm','verify'],
        'edit' => ['pwd','pwd_confirm'],
        'login'=> ['verify']
    ];
}
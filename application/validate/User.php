<?php
namespace app\validate;
use think\Validate;

class User extends Validate{

	protected $rule = [
		['a_id', 'require|number', '账号ID不能为空|账号ID格式错误'],
		['nickname', 'require|chsAlphaNum|max:10', '昵称不能为空|昵称是汉字、字母和数字|昵称长度10个字符'],
		['mobile', 'require|regex:/^1[34578]\d{9}$/', '手机号不能为空|手机格式错误'],
		['birthday', 'require|date', '生日不能为空|日期格式错误']
    ];
    
    protected $scene = [
    	// 'add'  => ['a_id','nickname','mobile','birthday'],
    	'add'  => ['a_id','mobile'],
        'edit' => ['nickname','birthday']
    ];
}
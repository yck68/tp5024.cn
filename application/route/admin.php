<?php

use think\Route;

Route::get('admin', 'admin/index/index');

Route::rule('login', 'admin/login/login', 'get|post');
Route::rule('register', 'admin/login/register', 'get|post');
Route::get('signOut', 'admin/login/signOut');

Route::get('lists', 'admin/admin/lists');
Route::post('getList', 'admin/admin/getList');
Route::get('detail', 'admin/admin/detail');
Route::rule('edit', 'admin/admin/edit', 'get|post');
Route::post('del', 'admin/admin/del');

Route::get('uLists', 'admin/user/lists');
Route::post('uGetList', 'admin/user/getList');
Route::get('uDetail', 'admin/user/detail');
Route::rule('uEdit', 'admin/user/edit', 'get|post');
Route::post('upload', 'admin/user/uploadHeadImg');
Route::post('mateStarZuo', 'admin/user/mateStarZuo'); //根据生日匹配星座

Route::post('getCity', 'admin/area/getCity');
Route::post('getArea', 'admin/area/getArea');
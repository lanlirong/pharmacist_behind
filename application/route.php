<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\Route;

Route::rule('consult/detail/:id', 'index/consult.Index/detail');
Route::rule('consult/recommend', 'index/consult.Index/recommend');
// drug
Route::rule('drug/list', 'index/drug.Index/getList');
Route::rule('drug/one', 'index/drug.Index/getDrugByID');
Route::rule('drug/filterList', 'index/drug.Index/getFilterList');
Route::rule('drug/count', 'index/drug.Index/getDrugCount');

Route::rule('admin/drug/list', 'admin/drug.Index/getList');
Route::rule('admin/drug/one', 'admin/drug.Index/getDrugByID');
Route::rule('admin/drug/uploadDrugPicture', 'admin/drug.Index/uploadDrugPicture');
Route::rule('admin/drug/add', 'admin/drug.Index/addDrug');


// interaction
Route::rule('interaction/list', 'index/interaction.Index/getList');
Route::rule('interaction/one', 'index/interaction.Index/getOne');
Route::rule('interaction/count', 'index/interaction.Index/getInteractionCount');

// disease
Route::rule('disease/list', 'index/disease.Index/getList');
Route::rule('disease/one', 'index/disease.Index/getOne');
Route::rule('disease/count', 'index/disease.Index/getDiseaseCount');
// consult 
Route::rule('consult/list', 'index/consult.Index/getList');
Route::rule('consult/one', 'index/consult.Index/getOne');
Route::rule('consult/count', 'index/consult.Index/getConsultCount');
Route::rule('consult/books', 'index/consult.Index/getBooks');

// system
Route::rule('admin/login', 'admin/system.User/login');
Route::rule('admin/logout', 'admin/system.User/logout');

Route::rule('admin/adminuser/list', 'admin/system.User/getList');




return [
    '__pattern__' => [
        'name' => '\w+',
    ],
    '[hello]'     => [
        ':id'   => ['index/hello', ['method' => 'get'], ['id' => '\d+']],
        ':name' => ['index/hello', ['method' => 'post']],
    ],

];

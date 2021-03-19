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



return [
    '__pattern__' => [
        'name' => '\w+',
    ],
    '[hello]'     => [
        ':id'   => ['index/hello', ['method' => 'get'], ['id' => '\d+']],
        ':name' => ['index/hello', ['method' => 'post']],
    ],

];

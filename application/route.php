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

header("Access-Control-Allow-Origin:http://localhost:8081");
header("Access-Control-Allow-Credentials:true");
header("Access-Control-Allow-Methods:GET, POST, OPTIONS, DELETE");
header("Access-Control-Allow-Headers:DNT,X-Mx-ReqToken,Keep-Alive,User-Agent,X-Requested-With,If-Modified-Since,Cache-Control,Content-Type, Accept-Language, Origin, Accept-Encoding");
if (request()->isOptions()) {
    exit();
}
/* 
    drug
 */
Route::rule('drug/list', 'index/drug.Index/getList');
Route::rule('drug/one', 'index/drug.Index/getDrugByID');
Route::rule('drug/filterList', 'index/drug.Index/getFilterList');
Route::rule('drug/count', 'index/drug.Index/getDrugCount');
// admin-drug
Route::rule('admin/drug/list', 'admin/drug.Index/getList');
Route::rule('admin/drug/one', 'admin/drug.Index/getDrugByID');
// admin-addRaw
Route::rule('admin/drug/uploadDrugPicture', 'admin/drug.AddRaw/uploadDrugPicture');
Route::rule('admin/drug/addRaw', 'admin/drug.AddRaw/addRawDrug');
Route::rule('admin/drug/deleteRawDrug', 'admin/drug.AddRaw/deleteRawDrug');
Route::rule('admin/drug/myRawList', 'admin/drug.AddRaw/getList');

// admin-check
Route::rule('admin/drug/rawList', 'admin/drug.Check/getList');
Route::rule('admin/drug/rawOne', 'admin/drug.Check/getDrugByID');
Route::rule('admin/drug/check', 'admin/drug.Check/checkDrug');

/* 
    interaction
*/
Route::rule('interaction/list', 'index/interaction.Index/getList');
Route::rule('interaction/one', 'index/interaction.Index/getOne');
Route::rule('interaction/count', 'index/interaction.Index/getInteractionCount');
/* 
  disease  
*/
Route::rule('disease/list', 'index/disease.Index/getList');
Route::rule('disease/one', 'index/disease.Index/getOne');
Route::rule('disease/count', 'index/disease.Index/getDiseaseCount');
/* 
    consult
*/
Route::rule('consult/list', 'index/consult.Index/getList');
Route::rule('consult/one', 'index/consult.Index/getOne');
Route::rule('consult/count', 'index/consult.Index/getConsultCount');
Route::rule('consult/books', 'index/consult.Index/getBooks');

/* 
    article
*/
// admin-article
Route::rule('admin/article/list', 'admin/article.Index/getList');
Route::rule('admin/article/one', 'admin/article.Index/getOneByID');
Route::rule('admin/article/update', 'admin/article.Index/update');
Route::rule('admin/article/deleteOne', 'admin/article.Index/deleteOne');

// admin-addRaw
Route::rule('admin/article/addRaw', 'admin/article.AddRaw/addRaw');
Route::rule('admin/article/updateRaw', 'admin/article.AddRaw/updateRaw');
Route::rule('admin/article/deleteRaw', 'admin/article.AddRaw/deleteRaw');
Route::rule('admin/article/myRawList', 'admin/article.AddRaw/getMyRawList');
Route::rule('admin/article/uploadPicture', 'admin/article.AddRaw/uploadPicture');


// admin-check
Route::rule('admin/article/rawList', 'admin/article.Check/getRawList');
Route::rule('admin/article/rawOne', 'admin/article.Check/getCheckByID');
Route::rule('admin/article/check', 'admin/article.Check/check');

/* 
    system
*/
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

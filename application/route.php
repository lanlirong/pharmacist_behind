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
// admin-interaction
Route::rule('admin/interaction/list', 'admin/interaction.Index/getList');
Route::rule('admin/interaction/one', 'admin/interaction.Index/getOneByID');
Route::rule('admin/interaction/update', 'admin/interaction.Index/update');
Route::rule('admin/interaction/deleteOne', 'admin/interaction.Index/deleteOne');
// admin-addRaw
Route::rule('admin/interaction/addRaw', 'admin/interaction.AddRaw/addRaw');
Route::rule('admin/interaction/updateRaw', 'admin/interaction.AddRaw/updateRaw');
Route::rule('admin/interaction/deleteRaw', 'admin/interaction.AddRaw/deleteRaw');
Route::rule('admin/interaction/myRawList', 'admin/interaction.AddRaw/getMyRawList');
// admin-check
Route::rule('admin/interaction/rawList', 'admin/interaction.Check/getRawList');
Route::rule('admin/interaction/rawOne', 'admin/interaction.Check/getCheckByID');
Route::rule('admin/interaction/check', 'admin/interaction.Check/check');



/* 
  disease  
*/
Route::rule('disease/list', 'index/disease.Index/getList');
Route::rule('disease/one', 'index/disease.Index/getOne');
Route::rule('disease/count', 'index/disease.Index/getDiseaseCount');
// admin-disease
Route::rule('admin/disease/list', 'admin/disease.Index/getList');
Route::rule('admin/disease/one', 'admin/disease.Index/getOneByID');
Route::rule('admin/disease/update', 'admin/disease.Index/update');
Route::rule('admin/disease/deleteOne', 'admin/disease.Index/deleteOne');
// admin-addRaw
Route::rule('admin/disease/addRaw', 'admin/disease.AddRaw/addRaw');
Route::rule('admin/disease/updateRaw', 'admin/disease.AddRaw/updateRaw');
Route::rule('admin/disease/deleteRaw', 'admin/disease.AddRaw/deleteRaw');
Route::rule('admin/disease/myRawList', 'admin/disease.AddRaw/getMyRawList');
Route::rule('admin/disease/uploadPicture', 'admin/disease.AddRaw/uploadPicture');
// admin-check
Route::rule('admin/disease/rawList', 'admin/disease.Check/getRawList');
Route::rule('admin/disease/rawOne', 'admin/disease.Check/getCheckByID');
Route::rule('admin/disease/check', 'admin/disease.Check/check');



/* 
    consult
*/
Route::rule('consult/list', 'index/consult.Index/getList');
Route::rule('consult/one', 'index/consult.Index/getOne');
Route::rule('consult/count', 'index/consult.Index/getConsultCount');
Route::rule('consult/books', 'index/consult.Index/getBooks');

// admin-consult
Route::rule('admin/consult/list', 'admin/consult.Index/getList');
Route::rule('admin/consult/one', 'admin/consult.Index/getOneByID');
Route::rule('admin/consult/update', 'admin/consult.Index/update');
Route::rule('admin/consult/deleteOne', 'admin/consult.Index/deleteOne');
// admin-addRaw
Route::rule('admin/consult/addRaw', 'admin/consult.AddRaw/addRaw');
Route::rule('admin/consult/updateRaw', 'admin/consult.AddRaw/updateRaw');
Route::rule('admin/consult/deleteRaw', 'admin/consult.AddRaw/deleteRaw');
Route::rule('admin/consult/myRawList', 'admin/consult.AddRaw/getMyRawList');
// admin-check
Route::rule('admin/consult/rawList', 'admin/consult.Check/getRawList');
Route::rule('admin/consult/rawOne', 'admin/consult.Check/getCheckByID');
Route::rule('admin/consult/check', 'admin/consult.Check/check');


/* 
    article
*/
Route::rule('science/list', 'index/science.Index/getList');
Route::rule('science/hotList', 'index/science.Index/getHotList');
Route::rule('science/one', 'index/science.Index/getOne');
Route::rule('science/count', 'index/science.Index/getArticleCount');
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
    User
*/
Route::rule('user/checkUsername', 'index/user.Index/checkUsername');
Route::rule('user/checkPhone', 'index/user.Index/checkPhone');
Route::rule('user/checkEmail', 'index/user.Index/checkEmail');

Route::rule('user/register', 'index/user.Index/register');
Route::rule('user/login', 'index/user.Index/login');
Route::rule('user/logout', 'index/user.Index/logout');
Route::rule('user/editMyInfo', 'index/user.Index/editMyInfo');
Route::rule('user/sendUserMsg', 'index/user.Service/sendUserMsg');
Route::rule('user/getMsgList', 'index/user.Service/getMsgList');


// admin-User
Route::rule('admin/User/list', 'admin/User.Index/getList');
Route::rule('admin/User/one', 'admin/User.Index/getOneByID');
Route::rule('admin/User/update', 'admin/User.Index/update');
Route::rule('admin/User/deleteOne', 'admin/User.Index/deleteOne');



/* 
    system
*/
Route::rule('admin/login', 'admin/system.User/login');
Route::rule('admin/logout', 'admin/system.User/logout');

Route::rule('admin/adminuser/list', 'admin/system.Index/getList');
Route::rule('admin/adminuser/one', 'admin/article.Index/getOneByID');
Route::rule('admin/adminuser/update', 'admin/article.Index/update');
Route::rule('admin/adminuser/deleteOne', 'admin/article.Index/deleteOne');


Route::rule('simpleSearch/list', 'index/common.Index/getSimpleList');

/*
post

*/
Route::rule('post/publish', 'index/post.Index/publish');
Route::rule('post/list', 'index/post.Index/getList');

return [
    '__pattern__' => [
        'name' => '\w+',
    ],
    '[hello]'     => [
        ':id'   => ['index/hello', ['method' => 'get'], ['id' => '\d+']],
        ':name' => ['index/hello', ['method' => 'post']],
    ],

];

<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CardController;
use App\Http\Controllers\CommonController;
use App\Http\Controllers\FieldController;
use App\Http\Controllers\FileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//用户认证
Route::prefix('auth')->controller(AuthController::class)->group(function () {
    Route::post('/login', 'login')->name('auth.login');;//登录
    Route::post('/logout', 'logout')->name('auth.login');;//登录
    Route::post('/register', 'register')->name('auth.register');;//注册
    Route::get('/register', 'getRegisterCode')->name('auth.register');;//注册发码
    Route::put('/reset', 'reset')->name('auth.reset');;//重置密码
    Route::get('/reset', 'getResetCode')->name('auth.reset');;//重置密码发码
});

//认证后
Route::middleware(['auth:admin', 'permission'])->group(function () {
    //认证管理
    Route::prefix('auth')->controller(AuthController::class)->group(function () {
        Route::put('/password', 'updatePassword')->name('auth.password');
    });

    //用户管理
    Route::prefix('user')->controller(AuthController::class)->group(function () {
        Route::get('/', 'index')->name('user.index');//查询
        Route::get('/{id}', 'show')->name('user.show');//查看
        Route::get('/info', 'info')->name('user.info');//查看
        Route::post('/', 'store')->name('user.store');//新增
        Route::put('/{id}', 'edit')->name('user.edit');//修改
        Route::delete('/{id}', 'destroy')->name('user.destroy');//删除
    });

    //公共接口
    Route::prefix('common')->controller(CommonController::class)->group(function () {
        Route::get('/dictionary', 'dictionary')->name('common.dictionary');//查询
    });

    //文件管理
    Route::prefix('file')->controller(FileController::class)->group(function () {
        Route::get('', 'index')->name('file.index');//查询
        Route::get('/{id}', 'show')->name('file.show');//查看
        Route::post('', 'store')->name('file.store');//新增
        Route::put('/{id}', 'edit')->name('file.edit');//修改
        Route::delete('/{id}', 'destroy')->name('file.destroy');//删除
    });

    //卡片管理
    Route::prefix('card')->controller(CardController::class)->group(function () {
        Route::get('', 'index')->name('card.index');//查询
        Route::get('/{id}', 'show')->name('card.show');//查看
        Route::post('', 'store')->name('card.store');//新增
        Route::put('/{id}', 'edit')->name('card.edit');//修改
        Route::delete('/{id}', 'destroy')->name('card.destroy');//删除
    });

    //字段管理
    Route::prefix('field')->controller(FieldController::class)->group(function () {
        Route::get('', 'index')->name('card.index');//查询
        Route::get('/{id}', 'show')->name('card.show');//查看
        Route::post('', 'store')->name('card.store');//新增
        Route::put('/{id}', 'edit')->name('card.edit');//修改
        Route::delete('/{id}', 'destroy')->name('card.destroy');//删除
    });

});



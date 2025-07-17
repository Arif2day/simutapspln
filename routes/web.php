<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PenelusuranPerkaraController;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\Super\UserManagerController;

Sentinel::disableCheckpoints();
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('iniuse', function(){
// 	$role = Sentinel::findRoleById('2');
// 	$credentials = [
// 	    'email'    => 'sipil.ft@uniwa.ac.id',
// 	    'password' => 'sipil.ft717',
// 		'first_name' => 'Unknown',
// 		'last_name' => '',
// 		'phone' => '8xx',
// 		'username' => '07108765',
// 	];
// 	$user = Sentinel::registerAndActivate($credentials);
// 	$role->users()->attach($user);
// });

//UserController
// Route::get('/', [PenelusuranPerkaraController::class,'index']);
Route::get('login', [UserController::class,'login']);
Route::post('login', [UserController::class,'postLogin']);

Route::get('/',[BerandaController::class,'index']);
Route::group(['prefix'=>'penelusuran'],function(){
	Route::get('/all',[PenelusuranPerkaraController::class,'indexAll']);
	Route::get('/perdata-umum',[PenelusuranPerkaraController::class,'indexPerdataUmum']);
});
	Route::post('/get-data-survei',[PenelusuranPerkaraController::class,'getDataSurvei']);
	Route::post('/cetak',[PenelusuranPerkaraController::class,'cetak']);



Route::group(['middleware' => 'sentinelmember'], function(){
	Route::group(['prefix'=>'dashboard'],function(){
		Route::get('/', [DashboardController::class,'index']);
		Route::post('/periode', [DashboardController::class,'getChartSAByPeriode']);
		Route::post('/periode-by-prodi', [DashboardController::class,'getChartKAByPeriode']);
		Route::post('/periode-lulus-by-prodi', [DashboardController::class,'getChartKAByPeriodeLulus']);
		Route::get('/test',[DashboardController::class,'test']);
	});
	

	Route::get('user-profile',[UserController::class,'userProfile']);
	Route::post('user-profile-update-profil',[UserController::class,'update']);
	Route::post('user-profile-update-password',[UserController::class,'updatePassword']);	

	Route::get('logout',[UserController::class,'logout']);
});



Route::group(['middleware' => 'SAmember'],function(){	

	Route::group(['prefix' => 'user-manager'], function(){
			Route::get('/', [UserManagerController::class,'index']);
			Route::post('/list', [UserManagerController::class, 'getUsers']);
			Route::post('/',[UserManagerController::class,'store']);
			Route::post('/update',[UserManagerController::class,'update']);
			Route::delete('', [UserManagerController::class,'destroy']);
	});
});

// No Permission
Route::get('notfound',function(){
	return abort(404);
});
<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PenelusuranPerkaraController;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\Super\UserManagerController;
use App\Http\Controllers\Super\PositionController;
use App\Http\Controllers\Super\UnitTypeController;
use App\Http\Controllers\Super\UnitController;
use App\Http\Controllers\Super\EmployeeStatusController;
use App\Http\Controllers\Peserta\PermohonanMutasiController;
use App\Http\Controllers\Peserta\ApsRequestController;

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

	Route::group(['prefix'=>'permohonan-mutasi'],function(){
		Route::group(['prefix'=>'permohonan'],function(){
			Route::get('/', [PermohonanMutasiController::class,'index']);
			Route::post('/',[PermohonanMutasiController::class,'store']);
			Route::post('/list', [PermohonanMutasiController::class,'getMutationAvailabilityList']);
		});
		Route::group(['prefix'=>'riwayat'],function(){
			Route::get('/', [ApsRequestController::class,'index']);
			Route::get('/{id}', [ApsRequestController::class,'detailApsRequest']);
			Route::post('/list', [ApsRequestController::class,'getApsRequestList']);
			Route::post('/listDoc', [ApsRequestController::class,'getApsDocumentList']);
			Route::post('/response', [ApsRequestController::class,'responseRequest']);
			Route::post('/response-reject', [ApsRequestController::class,'responseRequestReject']);
			Route::post('/response-upload', [ApsRequestController::class,'responseRequestUpload']);
			Route::get('/tes',[ApsRequestController::class, 'tes']);
		});
	});
	

	Route::get('user-profile',[UserController::class,'userProfile']);
	Route::post('user-profile-update-profil',[UserController::class,'update']);
	Route::post('user-profile-update-password',[UserController::class,'updatePassword']);	

	Route::get('logout',[UserController::class,'logout']);
});



Route::group(['middleware' => 'SAmember'],function(){	
	Route::group(['prefix' => 'master'], function(){
		Route::group(['prefix' => 'positions'], function(){
			Route::get('/', [PositionController::class,'index']);
			Route::post('/list', [PositionController::class,'getPositionList']);
			Route::post('/',[PositionController::class,'store']);
			Route::post('/update',[PositionController::class,'update']);
			Route::delete('', [PositionController::class,'destroy']);
		});
		Route::group(['prefix' => 'employee-status'], function(){
			Route::get('/', [EmployeeStatusController::class,'index']);
			Route::post('/list', [EmployeeStatusController::class,'getEmployeeStatusList']);
			Route::post('/',[EmployeeStatusController::class,'store']);
			Route::post('/update',[EmployeeStatusController::class,'update']);
			Route::delete('', [EmployeeStatusController::class,'destroy']);
		});
		Route::group(['prefix' => 'unit-types'], function(){
			Route::get('/', [UnitTypeController::class,'index']);
			Route::post('/list', [UnitTypeController::class,'getUnitTypeList']);
			Route::post('/',[UnitTypeController::class,'store']);
			Route::post('/update',[UnitTypeController::class,'update']);
			Route::delete('', [UnitTypeController::class,'destroy']);
		});
		Route::group(['prefix' => 'units'], function(){
			Route::get('/', [UnitController::class,'index']);
			Route::post('/list', [UnitController::class,'getUnitList']);
			Route::post('/',[UnitController::class,'store']);
			Route::post('/update',[UnitController::class,'update']);
			Route::delete('', [UnitController::class,'destroy']);
		});
	});
	Route::group(['prefix' => 'user-manager'], function(){
			Route::get('/', [UserManagerController::class,'index']);
			Route::post('/list', [UserManagerController::class, 'getUsers']);
			Route::post('/',[UserManagerController::class,'store']);
			Route::post('/update',[UserManagerController::class,'update']);
			Route::delete('', [UserManagerController::class,'destroy']);
			Route::group(['prefix' => 'placement'], function(){
				Route::post('/list', [UserManagerController::class, 'getUserPlacements']);
				Route::post('/',[UserManagerController::class,'storePlacement']);
				Route::post('/update',[UserManagerController::class,'updatePlacement']);
				Route::delete('', [UserManagerController::class,'destroyPlacement']);
			});
	});
});

// No Permission
Route::get('notfound',function(){
	return abort(404);
});
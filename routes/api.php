<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\V1\OpenAPIController;

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
Route::post('cari-pt', [OpenAPIController::class,'cariPT']);
Route::post('cari-prodi-by-ptid', [OpenAPIController::class,'cariProdiByPtid']);
Route::post('cari-wilayah', [OpenAPIController::class,'cariWilayah']);
Route::post('cari-wilayah-by-id', [OpenAPIController::class,'cariWilayahById']);
Route::get('cari-agama', [OpenAPIController::class,'cariAgama']);
Route::post('cari-agama-by-id', [OpenAPIController::class,'cariAgamaById']);
Route::post('cari-negara', [OpenAPIController::class,'cariNegara']);
Route::post('cari-negara-by-id', [OpenAPIController::class,'cariNegaraById']);
Route::get('cari-pendidikan', [OpenAPIController::class,'cariPendidikan']);
Route::post('cari-pendidikan-by-id', [OpenAPIController::class,'cariPendidikanById']);
Route::get('cari-pekerjaan', [OpenAPIController::class,'cariPekerjaan']);
Route::post('cari-pekerjaan-by-id', [OpenAPIController::class,'cariPekerjaanById']);
Route::get('cari-penghasilan', [OpenAPIController::class,'cariPenghasilan']);
Route::post('cari-penghasilan-by-id', [OpenAPIController::class,'cariPenghasilanById']);
Route::post('insert-biodata-camaba', [OpenAPIController::class,'insertBiodataCamaba']);



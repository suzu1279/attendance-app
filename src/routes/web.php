<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RegisteredUserController;
use App\Http\Controllers\ApplicationController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use Illuminate\Http\Request;

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




Route::middleware(['auth','verified'])->group(function(){
    Route::get('/attendance', [AttendanceController::class, 'attendance']);
    Route::post('/attendance/start',[AttendanceController::class, 'startWork'])->name('attendance.start');
    Route::post('/attendance/end', [AttendanceController::class, 'endWork'])->name('attendance.end');
    Route::post('attendance/break/start',[AttendanceController::class,'startBreak'])->name('attendance.break.start');
    Route::post('/attendance/break/end',[AttendanceController::class, 'endBreak'])->name('attendance.break.end');
    Route::get('attendance/list/{year?}/{month?}',[AttendanceController::class, 'list'])->name('attendance.list');
    Route::get('/attendance/detail/{id}',[AttendanceController::class,'detail'])->name('attendance.detail');
    Route::post('/attendance/detail/{id}/application',[ApplicationController::class,'store'])->name('attendance.detail.store');
    Route::get('/application/list',[ApplicationController::class,'index'])->name('application.index');
    Route::post('/application',[ApplicationController::class,'application'])->name('application.store');
    Route::post('/attendance/detail/{id}/update',[AttendanceController::class,'update'])->name('attendance.update');
});

Route::post('/login', [AuthenticatedSessionController::class, 'store'])->middleware('email');
Route::post('/register', [RegisteredUserController::class, 'store']);

Route::post('/logout',[AuthController::class,'logout'])->name('logout');





Route::get('/admin/login', [AuthController::class, 'createAdmin'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'storeAdmin'])
->name('admin.login.post');



Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function(){
    Route::get('/attendance/daily',[AdminController::class, 'daily'])->name('attendance.daily');
    Route::post('/logout
    ',[AuthController::class, 'logout'])->name('logout');
    Route::get('/attendance/{id}',[AdminController::class, 'detail'])->name('attendance.detail');
    Route::post('/attendance/{id}/approve',[AdminController::class, 'approve'])->name('attendance.approve');
    Route::get('/staff/list',[AdminController::class,'staffList'])->name('staffs.index');
    Route::get('/attendance/staff/{id}',[AdminController::class,'show'])->name('attendance.staff.show');
    Route::get('/attendance/{user}/csv',[AdminController::class,'exportCsv'])->name('attendance.export.csv');
    Route::get('/attendance/daily/{date?}',[AdminController::class,'daily'])->name('admin.attendance.daily');
    Route::get('/application/list', [AdminController::class, 'index'])->name('index');
    Route::get('/application/approve/{id}', [AdminController::class, 'modification'])->name('admin.modification');
});
    

Auth::routes();



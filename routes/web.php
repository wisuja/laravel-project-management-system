<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectLabelController;
use App\Http\Controllers\ProjectMemberController;
use App\Http\Controllers\ProjectStatusGroupController;
use App\Http\Controllers\ProjectTaskController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

Auth::routes([
    'reset' => false,
    'verify' => false,
]);

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    Route::prefix('projects')->name('projects.')->group(function () {
        Route::get('/', [ProjectController::class, 'index'])->name('index');
        Route::get('/{project}/members', [ProjectMemberController::class, 'index'])->name('members.index');
        Route::get('/{project}/labels', [ProjectLabelController::class, 'index'])->name('labels.index');
        Route::get('/{project}/tasks/{task}', [ProjectTaskController::class, 'show'])->name('tasks.show');
        Route::get('/{project}/tasks/{task}/edit', [ProjectTaskController::class, 'edit'])->name('tasks.edit');
        Route::get('/{project}/{type?}', [ProjectController::class, 'show'])->name('show');
        Route::post('/', [ProjectController::class, 'store'])->name('store');
        Route::post('/{project}/members/search', [ProjectMemberController::class, 'store'])->name('members.store');
        Route::post('/{project}/status-groups', [ProjectStatusGroupController::class, 'store'])->name('status-groups.store');
        Route::post('/{project}/labels', [ProjectLabelController::class, 'store'])->name('labels.store');
        Route::post('/{project}/tasks', [ProjectTaskController::class, 'store'])->name('tasks.store');
        Route::put('/', [ProjectController::class, 'update'])->name('update');
        Route::put('/{projectId}/status-groups', [ProjectStatusGroupController::class, 'update'])->name('status-groups.update');
        Route::put('/{project}/tasks/{task}', [ProjectTaskController::class, 'update'])->name('tasks.update');
        Route::delete('/{projectId}', [ProjectController::class, 'destroy'])->name('destroy');
        Route::delete('/{projectId}/members/{userId}', [ProjectMemberController::class, 'destroy'])->name('members.destroy');
        Route::delete('/{projectId}/status-groups/{groupId}', [ProjectStatusGroupController::class, 'destroy'])->name('status-groups.destroy');
        Route::delete('/{projectId}/labels/{groupId}', [ProjectLabelController::class, 'destroy'])->name('labels.destroy');
        Route::delete('/{projectId}/tasks/{taskId}', [ProjectTaskController::class, 'destroy'])->name('tasks.destroy');
    });
    Route::post('/save-image', [ImageController::class, 'store'])->name('save-image');
});

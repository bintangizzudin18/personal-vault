<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VaultController;

Route::get(
    '/vault/unlock',
    [VaultController::class, 'showUnlock']
)->name('vault.unlock');

Route::post(
    '/vault/unlock',
    [VaultController::class, 'unlock']
)->name('vault.unlock.process');

Route::get(
    '/vault',
    [VaultController::class, 'dashboard']
)->name('vault.dashboard');

Route::post(
    '/vault/lock',
    [VaultController::class, 'lock']
)->name('vault.lock');

Route::get(
    '/vault/category/{category}',
    [VaultController::class, 'category']
)->name('vault.category');

Route::get(
    '/vault/category/{category}/create',
    [VaultController::class, 'createItem']
)->name('vault.item.create');

Route::post(
    '/vault/category/{category}',
    [VaultController::class, 'storeItem']
)->name('vault.item.store');

Route::get(
    '/vault/category/{category}/item/{id}/edit',
    [VaultController::class, 'editItem']
)->name('vault.item.edit');

Route::put(
    '/vault/category/{category}/item/{id}',
    [VaultController::class, 'updateItem']
)->name('vault.item.update');

Route::delete(
    '/vault/category/{category}/item/{id}',
    [VaultController::class, 'destroyItem']
)->name('vault.item.destroy');

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

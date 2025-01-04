<?php

use Illuminate\Support\Facades\Route;
use Livewire\Livewire;
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

// Livewire::setScriptRoute(function ($handle) {
//     $urlPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
//     $segments = explode('/', trim($urlPath, '/'));
//     // Use the null coalescing operator to simplify the conditional assignment
//     $rootFolder = count($segments) >= 2 ? $segments[0] : 'localhost';
//     return Route::get($rootFolder . '/public/livewire/livewire.js', $handle);
// });

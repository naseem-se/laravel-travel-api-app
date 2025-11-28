<?php

use App\Http\Controllers\Admin\ShiftController;
use Illuminate\Support\Facades\Route;


Route::view('/', 'welcome')->name('welcome');

Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');
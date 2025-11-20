<?php

use App\Http\Controllers\Admin\ShiftController;
use Illuminate\Support\Facades\Route;


Route::view('/', 'welcome')->name('welcome');
Route::view('test',function(){
    echo "test route";
});

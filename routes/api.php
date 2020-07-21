<?php
declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::get('/', 'SearchController@searchShow')->middleware('throttle:60,1');

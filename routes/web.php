<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'namespace'  => '\Bzzix\PageBuilder\Controllers',
    'middleware' => config('bzzix-pagebuilder.middleware')
], function () {

    Route::get(config('bzzix-pagebuilder.create_route'), 'GrapesjsController@getIndex')
        ->name('bzzix-pagebuilder.create_route');

    Route::post(config('bzzix-pagebuilder.create_route'), 'GrapesjsController@postIndex')
        ->name('bzzix-pagebuilder.validate_create_route');
        
        
    Route::get(config('bzzix-pagebuilder.update_route'), 'GrapesjsController@getUpdate')
        ->name('bzzix-pagebuilder.update_route');

    Route::post(config('bzzix-pagebuilder.update_route'), 'GrapesjsController@postUpdate')
        ->name('bzzix-pagebuilder.validate_update_route');

});
<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'namespace'  => '\Bzzix\PageBuilder\Controllers',
    'middleware' => config('bzzix-pagebuilder.middleware')
], function () {

    Route::get(config('bzzix-pagebuilder.create_route'), 'PageBuilderController@getIndex')
        ->name('bzzix-pagebuilder.create_route');

    Route::post(config('bzzix-pagebuilder.create_route'), 'PageBuilderController@postIndex')
        ->name('bzzix-pagebuilder.validate_create_route');
        
        
    Route::get(config('bzzix-pagebuilder.update_route'), 'PageBuilderController@getUpdate')
        ->name('bzzix-pagebuilder.update_route');

    Route::post(config('bzzix-pagebuilder.update_route'), 'PageBuilderController@postUpdate')
        ->name('bzzix-pagebuilder.validate_update_route');

});
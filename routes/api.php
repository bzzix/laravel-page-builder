<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'namespace'  => '\Bzzix\PageBuilder\Controllers',
    'middleware' => ['api'],
    'prefix'     => 'api',
], function () {
    Route::post('pages', 'PageBuilderController@postIndex');
    // يمكنك إضافة المزيد من الراوتات هنا لاحقاً
});

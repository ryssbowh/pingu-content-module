<?php

use Illuminate\Support\Str;
use Pingu\Content\Entities\Content;
use Pingu\Content\Entities\ContentType;
use Pingu\Entity\Entities\BundleField;

/*
|--------------------------------------------------------------------------
| Admin Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register admin web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group prefixed with admin which
| contains the "web" middleware group and the permission middleware "can:access admin area".
|
*/

/**
 * Content
 */
// Route::get(Content::getUri('index'), ['uses' => 'JsGridContentController@index'])
// 	->name('content.admin.content')
// 	->middleware('can:view content');

// Route::get('content/create', ['uses' => 'AdminContentController@createIndex'])
// 	->name('content.admin.create');

// Route::get(Content::getUri('create'), ['uses' => 'AdminContentController@create'])
// 	->middleware('can:create,'.ContentType::routeSlug());
// Route::get(Content::getUri('edit'), ['uses' => 'AdminContentController@edit'])
// 	->middleware('can:edit-content,'.Content::routeSlug());

// Route::post(Content::getUri('store'), ['uses' => 'AdminContentController@store'])
// 	->middleware('can:create-content,'.ContentType::routeSlug());
// Route::put(Content::getUri('update'), ['uses' => 'AdminContentController@update'])
// 	->middleware('can:edit-content,'.Content::routeSlug());

// Route::get(Content::getUri('confirmDestroy'), ['uses' => 'AdminContentController@confirmDestroy'])
// 	->middleware('can:delete-content,'.Content::routeSlug());
// Route::delete(Content::getUri('destroy'), ['uses' => 'AdminContentController@destroy'])
// 	->middleware('can:delete-content,'.Content::routeSlug());
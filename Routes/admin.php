<?php

use Illuminate\Support\Str;
use Pingu\Content\Entities\Content;
use Pingu\Content\Entities\ContentType;
use Pingu\Content\Entities\Field;

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
 * Content types
 */
Route::get(ContentType::getUri('index'), ['uses' => 'JsGridContentTypeController@index'])
	->name('content.admin.contentTypes')
	->middleware('can:view content types');
Route::get(ContentType::getUri('create'), ['uses' => 'AdminContentTypeController@create'])
	->name('content.admin.contentTypes.create')
	->middleware('can:add content types');
Route::post(ContentType::getUri('store'), ['uses' => 'AdminContentTypeController@store'])
	->middleware('can:add content types');
Route::get(ContentType::getUri('edit'), ['uses' => 'AdminContentTypeController@edit'])
	->middleware('can:edit content types');
Route::put(ContentType::getUri('update'), ['uses' => 'AdminContentTypeController@update'])
	->middleware('can:add content types');
Route::get(ContentType::getUri('listFields'), ['uses' => 'AdminContentTypeController@listFields'])
	->middleware('can:view content types');

/**
 * Content type fields
 */
Route::get(ContentType::getUri('addField'), ['uses' => 'AdminContentFieldController@create'])
	->middleware('can:edit content types');
Route::post(ContentType::getUri('storeField'), ['uses' => 'AdminContentFieldController@store'])
	->middleware('can:edit content types');
Route::get(Field::getUri('edit'), ['uses' => 'AdminContentFieldController@edit'])
	->middleware('can:edit content types')
	->middleware('editableModel:'.Field::routeSlug());
Route::put(Field::getUri('update'), ['uses' => 'AdminContentFieldController@update'])
	->middleware('can:edit content types')
	->middleware('editableModel:'.Field::routeSlug());

/**
 * Content
 */
Route::get(Content::getUri('index'), ['uses' => 'JsGridContentController@index'])
	->name('content.admin.content')
	->middleware('can:view content');

Route::get('content/create', ['uses' => 'AdminContentController@createIndex'])
	->name('content.admin.create');

Route::get(Content::getUri('create'), ['uses' => 'AdminContentController@create'])
	->middleware('can:create,'.ContentType::routeSlug());
Route::get(Content::getUri('edit'), ['uses' => 'AdminContentController@edit'])
	->middleware('can:edit-content,'.Content::routeSlug());

Route::post(Content::getUri('store'), ['uses' => 'AdminContentController@store'])
	->middleware('can:create-content,'.ContentType::routeSlug());
Route::put(Content::getUri('update'), ['uses' => 'AdminContentController@update'])
	->middleware('can:edit-content,'.Content::routeSlug());

// Route::get(Content::getUri('confirmDestroy'), ['uses' => 'AdminContentController@confirmDestroy'])
// 	->middleware('can:delete-content,'.Content::routeSlug());
// Route::delete(Content::getUri('destroy'), ['uses' => 'AdminContentController@destroy'])
// 	->middleware('can:delete-content,'.Content::routeSlug());
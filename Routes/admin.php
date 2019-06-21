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
Route::get(ContentType::getAdminUri('index'), ['uses' => 'JsGridContentTypeController@index'])
	->name('content.admin.contentTypes')
	->middleware('can:view content types');
Route::get(ContentType::getAdminUri('create'), ['uses' => 'AdminContentTypeController@create'])
	->name('content.admin.contentTypes.create')
	->middleware('can:add content types');
Route::post(ContentType::getAdminUri('store'), ['uses' => 'AdminContentTypeController@store'])
	->middleware('can:add content types');
Route::get(ContentType::getAdminUri('edit'), ['uses' => 'AdminContentTypeController@edit'])
	->middleware('can:edit content types');
Route::put(ContentType::getAdminUri('update'), ['uses' => 'AdminContentTypeController@update'])
	->middleware('can:add content types');
Route::get(ContentType::getAdminUri('listFields'), ['uses' => 'AdminContentTypeController@listFields'])
	->middleware('can:view content types');

/**
 * Content type fields
 */
Route::get(ContentType::getAdminUri('addField'), ['uses' => 'AdminContentFieldController@create'])
	->middleware('can:edit content types');
Route::post(ContentType::getAdminUri('storeField'), ['uses' => 'AdminContentFieldController@store'])
	->middleware('can:edit content types');
Route::get(Field::getAdminUri('edit'), ['uses' => 'AdminContentFieldController@edit'])
	->middleware('can:edit content types')
	->middleware('editableContentField');
Route::put(Field::getAdminUri('update'), ['uses' => 'AdminContentFieldController@update'])
	->middleware('can:edit content types')
	->middleware('editableContentField');

/**
 * Content
 */
Route::get(Content::getAdminUri('index'), ['uses' => 'JsGridContentController@index'])
	->name('content.admin.content')
	->middleware('can:view content');

Route::get(Content::getAdminUri('create'), ['uses' => 'AdminContentController@create'])
	->middleware('can:create,'.ContentType::routeSlug());
Route::get(Content::getAdminUri('edit'), ['uses' => 'AdminContentController@edit'])
	->middleware('can:edit-content,'.Content::routeSlug());

Route::post(Content::getAdminUri('store'), ['uses' => 'AdminContentController@store'])
	->middleware('can:create-content,'.ContentType::routeSlug());
Route::put(Content::getAdminUri('update'), ['uses' => 'AdminContentController@update'])
	->middleware('can:edit-content,'.Content::routeSlug());

Route::get(Content::getAdminUri('confirmDestroy'), ['uses' => 'AdminContentController@confirmDestroy'])
	->middleware('can:delete-content,'.Content::routeSlug());
Route::delete(Content::getAdminUri('destroy'), ['uses' => 'AdminContentController@destroy'])
	->middleware('can:delete-content,'.Content::routeSlug());
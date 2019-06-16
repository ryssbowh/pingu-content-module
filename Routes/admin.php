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
Route::get(ContentType::getAdminUri('index'), ['uses' => 'ContentTypeJsGridController@index'])
	->name('content.admin.contentTypes')
	->middleware('can:view content types');
Route::get(ContentType::getAdminUri('create'), ['uses' => 'ContentTypeController@create'])
	->name('content.admin.contentTypes.create')
	->middleware('can:add content types');
Route::post(ContentType::getAdminUri('store'), ['uses' => 'ContentTypeController@store'])
	->middleware('can:add content types');
Route::get(ContentType::getAdminUri('edit'), ['uses' => 'ContentTypeController@edit'])
	->middleware('can:edit content types');
Route::put(ContentType::getAdminUri('update'), ['uses' => 'ContentTypeController@update'])
	->middleware('can:add content types');
Route::get(ContentType::getAdminUri('listFields'), ['uses' => 'ContentTypeController@listFields'])
	->middleware('can:view content types');

/**
 * Content type fields
 */
Route::get(ContentType::getAdminUri('addField'), ['uses' => 'ContentFieldController@create'])
	->middleware('can:edit content types');
Route::post(ContentType::getAdminUri('storeField'), ['uses' => 'ContentFieldController@store'])
	->middleware('can:edit content types');
Route::get(Field::getAdminUri('edit'), ['uses' => 'ContentFieldController@edit'])
	->middleware('can:edit content types')
	->middleware('editableContentField');
Route::put(Field::getAdminUri('update'), ['uses' => 'ContentFieldController@update'])
	->middleware('can:edit content types')
	->middleware('editableContentField');

/**
 * Content
 */
Route::get(Content::getAdminUri('index'), ['uses' => 'ContentJsGridController@index'])
	->name('content.admin.content')
	->middleware('can:view content');

Route::get(Content::getAdminUri('create'), ['uses' => 'ContentController@create'])
	->middleware('can:create,'.ContentType::routeSlug());
Route::get(Content::getAdminUri('edit'), ['uses' => 'ContentController@edit'])
	->middleware('can:edit-content,'.Content::routeSlug());

Route::post(Content::getAdminUri('store'), ['uses' => 'ContentController@store'])
	->middleware('can:create-content,'.ContentType::routeSlug());
Route::put(Content::getAdminUri('update'), ['uses' => 'ContentController@update'])
	->middleware('can:edit-content,'.Content::routeSlug());

Route::get(Content::getAdminUri('confirmDestroy'), ['uses' => 'ContentController@confirmDestroy'])
	->middleware('can:delete-content,'.Content::routeSlug());
Route::delete(Content::getAdminUri('destroy'), ['uses' => 'ContentController@destroy'])
	->middleware('can:delete-content,'.Content::routeSlug());
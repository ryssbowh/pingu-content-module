<?php

use Pingu\Content\Entities\Content;
use Pingu\Content\Entities\ContentType;
use Pingu\Content\Entities\Field;

/*
|--------------------------------------------------------------------------
| Ajax Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register ajax web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group prefixed with ajax which
| contains the "ajax" middleware group.
|
*/
	
/**
 * Content types
 */
// Route::get(ContentType::getUri('index'), ['uses' => 'JsGridContentTypeController@jsGridIndex'])
// 	->middleware('can:view content types');
// Route::delete(ContentType::getUri('delete'), ['uses' => 'AjaxContentTypeController@destroy'])
// 	->middleware('can:delete content types');
// Route::put(ContentType::getUri('update'), ['uses' => 'AjaxContentTypeController@update'])
// 	->middleware('can:edit content types');


/**
 * Content type fields
 */
// Route::get(ContentType::getUri('createField'), ['uses' => 'AjaxContentTypeFieldController@create'])
// 	->middleware('can:edit content types');
// Route::post(ContentType::getUri('storeField'), ['uses' => 'AjaxContentTypeFieldController@store'])
// 	->middleware('can:edit content types');
// Route::get(ContentType::getUri('editField'), ['uses' => 'AjaxContentTypeFieldController@edit'])
// 	->middleware('can:edit content types');
// Route::put(ContentType::getUri('updateField'), ['uses' => 'AjaxContentTypeFieldController@update'])
// 	->middleware('can:edit content types');
// Route::delete(ContentType::getUri('deleteField'), ['uses' => 'AjaxContentTypeFieldController@delete'])
// 	->middleware('can:edit content types');
// Route::patch(ContentType::getUri('patchFields'), ['uses' => 'AjaxContentTypeFieldController@patch'])
// 	->middleware('can:edit content types');

/**
 * Content
 */
// Route::get(Content::getUri('index'), ['uses' => 'JsGridContentController@jsGridIndex'])
// 	->middleware('can:view content');
// Route::put(Content::getUri('update'), ['uses' => 'AjaxContentController@update'])
// 	->middleware('can:view content');
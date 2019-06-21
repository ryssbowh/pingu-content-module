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
Route::get(ContentType::getAjaxUri('index'), ['uses' => 'JsGridContentTypeController@jsGridIndex'])
	->middleware('can:view content types');
Route::delete(ContentType::getAjaxUri('delete'), ['uses' => 'AjaxContentTypeController@destroy'])
	->middleware('can:delete content types');
Route::patch(ContentType::getAjaxUri('patchFields'), ['uses' => 'AjaxContentTypeFieldsController@patch'])
	->middleware('can:edit content types');
Route::put(ContentType::getAjaxUri('update'), ['uses' => 'AjaxContentTypeController@update'])
	->middleware('can:edit content types');

/**
 * Content Fields
 */
Route::delete(Field::getAjaxUri('delete'), ['uses' => 'AjaxContentTypeFieldsController@delete'])
	->middleware('can:edit content types')
	->middleware('deletableModel:'.Field::routeSlug());

/**
 * Content
 */
Route::get(Content::getAjaxUri('index'), ['uses' => 'JsGridContentController@jsGridIndex'])
	->middleware('can:view content');
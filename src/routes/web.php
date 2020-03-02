<?php 

Route::group(['middleware' => ['RoleAuthenticate']], function () {
	Route::get('admintemplate','AdminTemplateController@index');
});
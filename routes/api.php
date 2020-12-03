<?php 

use Armincms\Noobar\Http\Controllers\ProfileController;
use Armincms\Noobar\Http\Controllers\AddressController;
use Armincms\Noobar\Http\Controllers\SettingController;

Route::get('user/profile', [
	'uses' => ProfileController::class.'@show',
	'as' => 'profile.show',
]);

Route::get('setting', [
	'uses' => SettingController::class.'@handle',
	'as' => 'setting.show',
]);

Route::put('user/profile', [
	'uses' => ProfileController::class.'@update',
	'as' => 'profile.update',
]);

Route::apiResource('user/address', AddressController::class);
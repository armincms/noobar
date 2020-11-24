<?php 

use Armincms\Noobar\Http\Controllers\ProfileController;
use Armincms\Noobar\Http\Controllers\AddressController;

Route::get('profile', [
	'uses' => ProfileController::class.'@show',
	'as' => 'profile.show',
]);

Route::put('profile', [
	'uses' => ProfileController::class.'@update',
	'as' => 'profile.update',
]);

Route::apiResource('address', AddressController::class);
<?php 

namespace Armincms\Noobar\Http\Controllers;

use Illuminate\Http\Request;
use Armincms\Sofre\Nova\Setting;

class SettingController extends Controller
{
	public function __construct()
	{ 
	}

	/**
	 * Show the user prodile data.
	 * 
	 * @param  \Illuminate\Http\Request $request 
	 * @return array           
	 */
	public function handle(Request $request)
	{
		return [
			'Curreny' 	=> Setting::currency(), 
		];
	} 
}
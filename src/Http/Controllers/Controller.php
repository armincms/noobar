<?php 

namespace Armincms\Noobar\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
	
	public function __construct()
	{
		$this->middleware('auth:sanctum');
	}
}
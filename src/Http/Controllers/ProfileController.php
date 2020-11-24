<?php 

namespace Armincms\Noobar\Http\Controllers;

use Illuminate\Http\Request;
use Armincms\Noobar\NoobarAddress;

class ProfileController extends Controller
{
	/**
	 * Show the user prodile data.
	 * 
	 * @param  \Illuminate\Http\Request $request 
	 * @return array           
	 */
	public function show(Request $request)
	{
		return [
			'firstname' 	=> $request->user()->firstname,
			'lastname' 		=> $request->user()->lastname,
			'displayname' 	=> $request->user()->displayname, 
		];
	}

	/**
	 * Show the user prodile data.
	 * 
	 * @param  \Illuminate\Http\Request $request 
	 * @return array           
	 */
	public function update(Request $request)
	{
		$request->user()->forceFill([
			'firstname' 	=> $request->get('firstname', $request->user()->firstname),
			'lastname' 		=> $request->get('lastname', $request->user()->lastname),
			'displayname' 	=> $request->get('displayname', $request->user()->displayname), 
		])->save();

		return $this->show($request);
	}
}
<?php 

namespace Armincms\Noobar\Http\Controllers;

use Illuminate\Http\Request;
use Armincms\Location\Location;
use Armincms\Noobar\NoobarAddress;

class AddressController extends Controller
{
	/**
	 * Show the user prodile data.
	 * 
	 * @param  \Illuminate\Http\Request $request 
	 * @return array           
	 */
	public function index(Request $request)
	{
		return NoobarAddress::authenticate($request->user())->get()->map([$this, 'addressDetail']);
	}

	/**
	 * Show the user prodile data.
	 * 
	 * @param  \Illuminate\Http\Request $request 
	 * @return array           
	 */
	public function show(Request $request, $id)
	{ 
		return $this->addressDetail(NoobarAddress::with('zone')->findOrFail($id));
	}

	/**
	 * Show the user prodile data.
	 * 
	 * @param  \Illuminate\Http\Request $request 
	 * @return array           
	 */
	public function store(Request $request)
	{
		$request->validate($this->creationRules($request));

		$address = \DB::transaction(function() use ($request) {
			return tap($this->fillFromRequest($request, new NoobarAddress), function($address) use ($request) {
				$address->user()->associate($request->user());
				$address->save();
			});
		});
			 

		return $this->addressDetail($address->load('zone'));
	}

	/**
	 * Show the user prodile data.
	 * 
	 * @param  \Illuminate\Http\Request $request 
	 * @param integer $id
	 * @return array           
	 */
	public function update(Request $request, $id)
	{
		$request->validate($this->creationRules($request));

		$address = \DB::transaction(function() use ($request, $id) { 
			return  tap($this->fillFromRequest($request, NoobarAddress::findOrFail($id)), function($address) {
				$address->save();
			}); 
		});

		return $this->addressDetail($address->load('zone'));
	}

	/**
	 * Show the user prodile data.
	 * 
	 * @param  \Illuminate\Http\Request $request 
	 * @return array           
	 */
	public function destroy(Request $request, $id)
	{
		NoobarAddress::destroy($id);
			 

		return [
		];
	}

	/**
	 * Fill the given model with request data.
	 * 
	 * @param  \Illuminate\Http\Request $request 
	 * @param  \Armincms\Noobar\NoobarAddress $address
	 * @return \Armincms\Noobar\NoobarAddress                
	 */
	public function fillFromRequest(Request $request, NoobarAddress $address)
	{
		$data = array_filter($request->only([
			'name', 'zone_id', 'latitude', 'longitude'
		]));

		$config = array_filter($request->only([
			'address', 'phone', 'mobile', 'mobile', 'zipcode', 'code'
		]));

		return $address->forceFill(array_merge(
			$data, compact('config')
		)); 
	}

	/**
	 * Get the creation validation rules.
	 * 
	 * @param  \Illuminate\Http\Request $request 
	 * @return array
	 */
	public function creationRules(Request $request)
	{
		return [
			'name' => 'required|string',
			'address' => 'required|string', 
			'zone_id' => ['required', function($attribute, $value, $fail) {
				if(is_null($zone = Location::zone()->find($value))) {
					$fail(__('Invalid zone'));
				}
			}], 
		];
	}

	/**
	 * Display the address detail.
	 * 
	 * @param  \Armincms\Noobar\NoobarAddress $address 
	 * @return array                 
	 */
	public function addressDetail(NoobarAddress $address)
	{ 
		return [
			'id' 		=> $address->id,
			'name' 		=> $address->name,
			'address' 	=> $address->config('address'),
			'phone' 	=> $address->config('phone'),
			'mobile' 	=> $address->config('mobile'),
			'zipcode' 	=> $address->config('zipcode'),
			'number' 	=> $address->config('number'),
			'latitude'  => $address->latitude,
			'longitude' => $address->longitude,
			'zone'		=> [
				'id' => optional($address->zone)->id,
				'name' => optional($address->zone)->name,
			],
		]; 
	}
}
<?php 

namespace Armincms\Noobar\Http\Controllers;

use Illuminate\Http\Request;
use Armincms\Snail\Snail;
use Armincms\Sofre\Models\{Restaurant, Menu};  
use Armincms\SofreApi\Snail\{Restaurant as RestaurantResource, Menu as MenuResource};  

class SearchController extends Controller
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
	public function handle(Request $request, $key)
	{
		return response()->json([
			'restaurants' => [
				'data' => $this->serachRestaurants($request, $key),
				'url' => Snail::path().'/'.Snail::currentVersion()."/restaurants?search={$key}"
			],
			'foods' => [
				'data' => $this->serachFoods($request, $key),
				'url' => Snail::path().'/'.Snail::currentVersion()."/menus?search={$key}"
			], 
		], 200, [], JSON_PRESERVE_ZERO_FRACTION);
	} 

	public function serachRestaurants(Request $request, $search)
	{
		return Restaurant::where('name', 'like', "%{$search}%")->limit(5)->get()->mapInto(RestaurantResource::class)->map(function($resource) use ($request) {
			return $this->serializeForDisplay($request, $resource)->only(['Image', 'Logo', 'Name']);
		});
	}

	public function serachFoods(Request $request, $search)
	{
		return  Menu::whereHas('food', function($query) use ($search) {
					$query->where('name->'. app()->getLocale(), 'like', "%{$search}%");
				})
				->with('food', 'restaurant')->limit(5)->get()
				->mapInto(MenuResource::class)
				->map(function($resource) use ($request) {
					return $this->serializeForDisplay($request, $resource);
				}); 
	} 

	public function serializeForDisplay($request, $resource)
	{
		return collect($resource->properties($request))
					->filter->showOnIndex
					->each->resolveForDisplay($resource)
                    ->keyBy('name')
                    ->map->getValue();
	}
}
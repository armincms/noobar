<?php

namespace Armincms\Noobar;

use Armincms\Snail\Http\Requests\SnailRequest;
use Illuminate\Http\Request; 
use Armincms\Snail\Snail;
use Armincms\Snail\Schema;
use Armincms\Snail\Properties\{ID, Text, Number, Integer, Boolean, Collection, Map};
use Armincms\Sofre\Discount as SofreDiscount;
use Armincms\Sofre\Restaurant;
use Armincms\Sofre\Nova\Food;

class Discount extends Schema
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \Armincms\Noobar\NoobarDiscount::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'title';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
    ];

    public static $menus = null;

    public static $discounts = null;

    /**
     * Get the properties displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function properties(Request $request)
    {
        return [
            Text::make('Title')->nullable(),  

            Map::make('Foods', function($resource) {
                    return static::foods($resource); 
                })  
                ->using(function($attribute) { 
                    return  Collection::make($attribute)->properties(function() {
                        return [
                            ID::make(),

                            Text::make('Name'),

                            Map::make('Material', function($resource) {
                                    return collect($resource->material)->map(function($value, $name) {
                                        return compact('name', 'value');
                                    })->values();
                                })
                                ->using(function($attribute) {
                                    return Collection::make($attribute)->properties(function() {
                                        return [
                                            Text::make('Value'),

                                            Text::make('Name'),
                                        ];
                                    });
                                }),

                            Number::make('Old Price', 'pivot->price'),

                            Number::make('Price', 'pivot->price')
                                ->displayUsing(function($value, $resource, $attribute) {
                                    return static::discounts($resource->pivot->restaurant_id)->applyOn($resource); 
                                }),  

                            Text::make('Comments', function($resource) {
                                return  Snail::path().'/'.Snail::currentVersion().'/comments?' . http_build_query([
                                            'viaResource' => Food::uriKey(),
                                            'viaResourceId' => $resource->id,
                                            'viaRelationship' => 'comments'
                                        ]);
                            }),

                            Integer::make('Duration', 'pivot->duration'),

                            Boolean::make('Available', 'pivot->duration'),

                            Collection::make('Image', function($resource) {
                                    return $resource->getConversions($resource->getFirstMedia('image'), [
                                        'food-thumbnail', 'food-medium'
                                    ]);
                                })
                                ->properties(function() {
                                    return [
                                        Text::make('Thumbnail', 'food-thumbnail')->nullable(true, ['']),
                                        
                                        Text::make('Noobar', 'food-medium')->nullable(true, ['']), 
                                    ];
                                }),
                        ];
                    }); 
                }), 
        ];
    } 

    /**
     * Build an "index" query for the given resource.
     *
     * @param  \Armincms\Snail\Http\Requests\SnailRequest  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function indexQuery(SnailRequest $request, $query)
    {
        return $query->whereActive(1);
    } 

    public function foods(NoobarDiscount $discount)
    {
        return boolval($discount->manual) ? static::getManual($discount) : static::getAutomatic($discount);
    }

    public static function getManual(NoobarDiscount $discount)
    { 
        return static::menus()->filter(function($food) use ($discount) {
            return in_array($food->pivot->id, (array) $discount->items);
        })->values(); 
    }


    public static function getAutomatic(NoobarDiscount $discount)
    {
        return [];
    }

    public static function menus()
    {
        if(! isset(static::$menus)) {
            static::$menus = Restaurant::with(['foods'])->get()->flatMap->foods;
        }

        return static::$menus;
    }


    public static function discounts($restaurantId)
    {
        if(! isset(static::$discounts)) {
            static::$discounts = SofreDiscount::available()->get();
        }

        return static::$discounts->filter(function($discount) use ($restaurantId) {
            return $discount->restaurant_id == $restaurantId;
        });
    }
}

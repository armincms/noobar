<?php

namespace Armincms\Noobar;

use Armincms\Snail\Http\Requests\SnailRequest;
use Illuminate\Http\Request; 
use Armincms\Snail\Schema;
use Armincms\Snail\Properties\{Text, Number, Collection, Map};

class Slide extends Schema
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \Armincms\Noobar\NoobarSlide::class;

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

            Text::make('Caption')->nullable(),

            Text::make('Url')->nullable(),

            Number::make('Order')->nullable(),

            Collection::make('Images', function($resource) {
                    return static::newModel()->getConversions($resource->getFirstMedia('image'), [
                        'main', 'noobar.slide', 'thumbnail'
                    ]); 
                }) 
                ->properties(function() {  
                    return [
                        Text::make('Main')->nullable(true, ['']),

                        Text::make('Noobar', 'noobar.slider')->nullable(true, ['']),

                        Text::make('Thumbnail')->nullable(true, ['']),
                    ];
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
        return $query->whereActive(1)->orderBy('order');
    } 
}

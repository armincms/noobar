<?php

namespace Armincms\Noobar\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\{ID, Text, Textarea, Number, Boolean};
use Inspheric\Fields\Url;
use Armincms\Nova\Fields\Images;

class Slide extends Resource
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
    public static $title = 'id';

    /**
     * The logical group associated with the resource.
     *
     * @var string
     */
    public static $group = 'Noobar'; 

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'title', 'caption', 'order'
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make()->sortable(),

            Text::make(__('Slide Title'), 'title')
                ->nullable(),

            Textarea::make(__('Slide Caption'), 'caption')
                ->nullable(),

            Url::make(__('Slide Url'), 'url')
                ->nullable()
                ->nameLabel()
                ->alwaysClickable(),

            Number::make(__('Slide Order'), 'order') 
                ->sortable()
                ->fillUsing(function($request, $model, $attribute, $requestAttribute) {
                    return intval($request->get('order')) ?: (static::newModel()->max('order') + 1);
                }),

            Boolean::make(__('Show Slide'), 'active')
                ->withMeta([
                    'value' => $this->active ?? 1
                ])
                ->sortable(),

            Images::make(__('Image'), 'image')
                ->conversionOnPreview('common-thumbnail') 
                ->conversionOnDetailView('common-thumbnail') 
                ->conversionOnIndexView('common-thumbnail')
                ->fullSize(),
        ];
    } 
}

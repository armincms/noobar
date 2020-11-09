<?php

namespace Armincms\Noobar\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\{ID, Text, Select, Boolean};
use Inspheric\Fields\Url;
use OptimistDigital\MultiselectField\Multiselect;
use Armincms\Nova\Fields\Images;
use Armincms\Fields\Chain;
use Armincms\Sofre\Restaurant;

class Discount extends Resource
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
        'id', 'title'
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

            Text::make(__('Discount Title'), 'title')
                ->required()
                ->rules('required'), 

            $this->manualSelect($request)->exceptOnForms(),

            Chain::as('manual-chain', function($request) {
                return [
                    $this->manualSelect($request),
                ];
            }),

            Chain::with('manual-chain', function($request) { 
                return  $this->filter([
                            $this->when(intval($request->get('manual')) === 1, function() {
                                return  Select::make(__('Meal'), 'meal')
                                            ->options(\Armincms\Sofre\Helper::meals())
                                            ->required()
                                            ->rules('required');
                            }),

                            $this->when(intval($request->get('manual')) === 0, function() {
                                return  Select::make(__('Filter'), 'filter')
                                            ->options([
                                                'percent' => __('Maximum Discount Percentage'),
                                                'amount'  => __('Maximum Discount Amount'),
                                                'latest'  => __('Latest Discount'),
                                            ])
                                            ->required()
                                            ->rules('required');
                            }),
                        ]);  
            }, 'meal-chain'),

            Chain::with('meal-chain.meal', function($request) {
                return $this->filter([
                    $this->when($request->filled('meal'), function() use ($request) {
                        return Multiselect::make(__('Foods'), 'items')
                                ->options($this->foods($request))
                                ->required()
                                ->rules('required')
                                ->saveAsJSON();
                    }),
                ]);
            }), 
        ];
    } 

    public function manualSelect($request)
    {
        return Select::make(__('How To Choose'), 'manual')->options([
                        __('Automatic'), __('Manual')
                    ])
                    ->required()
                    ->rules('required')
                    ->displayUsingLabels();
    }

    public function foods(Request $request)
    { 
        return Restaurant::with('foods')->get()->flatMap(function($restaurant) use ($request) {
            return $restaurant->foods->filter(function($food) use ($request) {
                $meals = explode(',', data_get($food->pivot, strtolower(now()->format('l')))); 

                return in_array($request->get('meal'), (array) $meals);
            })
            ->map(function($food) use ($restaurant) {
                return [
                    'label' => "{$food->name} [{$restaurant->name}]",
                    'value' => $food->pivot->id, 
                ];
            });

        }); 
    }
}

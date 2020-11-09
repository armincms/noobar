<?php

namespace Armincms\Noobar;

use Illuminate\Database\Eloquent\Model; 
use Spatie\MediaLibrary\HasMedia\HasMedia;  
use Armincms\Concerns\HasMediaTrait; 

class NoobarDiscount extends Model implements HasMedia
{
	use HasMediaTrait;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'items' => 'array',
    ];
    
    /**
     * The related medias.
     *
     * @var array
     */
    protected $medias = [
        'icon' => [ 
            'disk'  => 'armin.image', 
            'conversions' => [
                'common', 'noobar'
            ]
        ],
    ];
}

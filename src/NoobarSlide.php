<?php

namespace Armincms\Noobar;

use Illuminate\Database\Eloquent\Model; 
use Spatie\MediaLibrary\HasMedia\HasMedia;  
use Armincms\Concerns\HasMediaTrait; 

class NoobarSlide extends Model implements HasMedia
{
	use HasMediaTrait;
    
    /**
     * The related medias.
     *
     * @var array
     */
    protected $medias = [
        'image' => [ 
            'disk'  => 'armin.image', 
            'conversions' => [
                'common', 'noobar'
            ]
        ],
    ];
}

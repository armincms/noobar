<?php

namespace Armincms\Noobar;

use Illuminate\Database\Eloquent\Model; 
use Spatie\MediaLibrary\HasMedia\HasMedia;  
use Armincms\Concerns\IntractsWithMedia; 

class NoobarSlide extends Model implements HasMedia
{
	use IntractsWithMedia;
    
    /**
     * The related medias.
     *
     * @var array
     */
    protected $medias = [
        'image' => [ 
            'disk'  => 'armin.image',
            'multiple'  => true,
            'schemas' => [
                'noobar.slider', '*'
            ]
        ],
    ];
}

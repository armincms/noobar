<?php

namespace Armincms\Noobar;

use Armincms\Conversion\Conversion; 

class SlideConversion extends Conversion
{ 
    /**
     * Get the registered schemas.
     * 
     * @return array
     */
    public function schemas()
    {
        return array_merge([ 
            'mobile' => [  
                'manipulations' => ['fit' => 'fill', 'crop' => 'crop-center'], // resize type
                'width'         => 480,
                'height'        => 320, 
                'upsize'        => false, // cutting type
                'compress'      => 60,
                'extension'     => null, // save extension
                'placeholder'   => image_placeholder(480, 320),
                'label'         => __('Noobar mobiel slider'),
            ],
        ], parent::schemas());
    } 
}

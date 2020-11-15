<?php

namespace Armincms\Noobar;

use Armincms\Conversion\Conversion; 

class DiscountConversion extends Conversion
{ 
    /**
     * Get the registered schemas.
     * 
     * @return array
     */
    public function schemas()
    {
        return array_merge([ 
            'discount' => [  
                'manipulations' => ['fit' => 'fill', 'crop' => 'crop-center'], // resize type
                'width'         => 300,
                'height'        => 300, 
                'upsize'        => false, // cutting type
                'compress'      => 60,
                'extension'     => null, // save extension
                'placeholder'   => image_placeholder(300, 300),
                'label'         => __('Noobar discount list'),
            ],
        ], parent::schemas());
    } 
}

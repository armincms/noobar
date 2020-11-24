<?php

namespace Armincms\Noobar;

use Illuminate\Database\Eloquent\Model;    
use Armincms\Contracts\Authorizable;
use Armincms\Concerns\Authorization; 
use Armincms\Location\Concerns\Locatable;

class NoobarAddress extends Model implements Authorizable
{ 
    use Authorization, Locatable;   

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = ['zone']; 

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'config' => 'array'
    ];

    public function config(string $key, $default = null)
    {
    	return data_get($this->config, $key, $default);
    }
}

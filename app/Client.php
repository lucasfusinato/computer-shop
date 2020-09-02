<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use SoftDeletes;
    
    /**
     * The attributes that are assignable.
     * 
     * @var array
     */
    protected $fillable = [
        'name',
        'cpf',
        'cep',
        'state_id',
        'city',
        'district',
        'street',
        'number'
    ];
    
    /**
     * Get the related state.
     */
    public function state()
    {
        return $this->belongsTo(State::class);
    }
}

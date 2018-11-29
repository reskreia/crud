<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'orderCode',
        'noTable',
        'status',
        'pelayanId',
        'kasirId'
    ];
    
    public function item()
    {
        return $this->hasMany('App\Models\OrderMenu', 'orderId');
    }

    public function pelayan()
    {
        return $this->hasMany('App\User', 'id', 'pelayanId');
    }
}

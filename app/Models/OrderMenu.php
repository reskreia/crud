<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderMenu extends Model
{
    
    protected $fillable = [
        'orderId',
        'orderItem',
        'itemTotal'
    ];

	public function order()
	{
	    return $this->belongsTo('App\Models\Order');
	}
}

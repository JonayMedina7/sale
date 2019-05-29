<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tax extends Model
{
    protected $fillable = ['tax'];

    protected $guarded = ['id'];

    public function sale ()
    {
    	return $this->belongsTo(Sale::class);
    }

    public function purchase()
    {
    	return $this->belongsTo(Purchase::class);
    }

}

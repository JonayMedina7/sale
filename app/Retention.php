<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Retention extends Model
{
    protected $filleble = ['voucher_num', 'date', 'year', 'month', 'tax', 'total', 'status', 'stamp_url'];

    protected $guarded = ['id'];

    public function purchases ()
    {
    	return $this->hasMany(Purchase::class);
    }

    public function company()
    {
    	return $this->belongsTo(Company::class);
    }

    public function provider()
    {
    	return $this->belongsTo(Provider::class);
    }

}

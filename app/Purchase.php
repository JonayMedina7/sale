<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $table ='purchases';
    protected $fillable = [
    	'provider_id', 'user_id','voucher', 'voucher_serie', 'voucher_num', 'date', 'tax', 'total', 'note_num','doc_type', 'tax_mount', 'exempt', 'status'
    ];

    protected $casts = [
        'created_at' => 'datetime:H:i:s d/m/Y', // Change format
        'updated_at' => 'datetime:H:i:s d/m/Y',
        'datep'      => 'date:d/m/Y',
        'date' => 'date:Y/m/d',
    ];

    protected $guarded = ['id'];

    public function provider()
    {
    	return $this->belongsTo(Provider::class);
    }

    public function user() {
    	return $this->belongsTo(User::class);
    }
    public function retention()
    {
        return $this->hasMany(Retention::class);
    }

}
 
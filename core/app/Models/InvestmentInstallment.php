<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvestmentInstallment extends Model {

    protected $guarded = ['id'];
    public $timestamps = false;
    protected $casts = [
        'given_date' => 'datetime',
        'installment_date' => 'datetime'
    ];
    
    public function investment() {
        return $this->belongsTo(Investment::class, 'investment_id');
    }
}

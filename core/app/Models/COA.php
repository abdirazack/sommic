<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Constants\Status;
use App\Traits\Searchable;
 
class COA extends Model
{
    protected $table = "chart_of_accounts";
    use Searchable;
    use SoftDeletes;
    
    public function category() {
        return $this->belongsTo(COACategory::class, 'category_id');
    }

    public function type() {
        return $this->belongsTo(COAType::class, 'type_id');
    }
    
    public function balance() {
        return $this->hasMany(Ledger::class, 'coa_id')->where('is_coa', 1)->selectRaw('sum(amount) as amount')->groupBy('dr_cr');
    }
    
}
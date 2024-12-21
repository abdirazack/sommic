<?php

namespace App\Models;

use App\Constants\Status;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Searchable;
 
class COAType extends Model
{
    protected $table = "chart_of_account_types";
    
    public function children() {
        return $this->hasMany(COA::class, 'id', 'type_id');
    }
}
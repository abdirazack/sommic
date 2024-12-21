<?php

namespace App\Models;

use App\Constants\Status;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Searchable;
 
class Product extends Model
{
    use Searchable;
    
    protected $casts = [
        'misc' => 'object',
    ];
    
    public function branch() {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function branchStaff() {
        return $this->belongsTo(BranchStaff::class, 'branch_staff_id');
    }
    
    public function account() {
        return $this->belongsTo(COA::class, 'product_account');
    }
    
    public function stock() {
        return $this->belongsTo(Inventory::class, 'id');
    }
}
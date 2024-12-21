<?php

namespace App\Models;

use App\Constants\Status;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Searchable;
 
class Guarantor extends Model
{
    use Searchable;
    
    protected $casts = [
        'misc' => 'object',
        'employment' => 'object',
    ];
    
    public function branch() {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function branchStaff() {
        return $this->belongsTo(BranchStaff::class, 'branch_staff_id');
    }
    
    public function guarantor() {
        return $this->belongsTo(Guarantor::class, 'guarantor_id');
    }
}
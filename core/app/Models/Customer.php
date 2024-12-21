<?php

namespace App\Models;

use App\Constants\Status;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Searchable;
 
class Customer extends Model
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
        return $this->hasMany(Account::class);
    }
    
    public function pendingForManager() {
        $staff      = authStaff();
        $branches   = $staff->assignBranch->pluck('id')->toArray();
        return $this->whereIn('branch_id', $branches)->where('status', 0);
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Constants\Status;
use App\Traits\Searchable;
 
class Account extends Model
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
    
    public function customer() {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
    
    public function organization() {
        return $this->belongsTo(Customer::class, 'organization_id');
    }
    
    public function accountType() {
        return $this->belongsTo(AccountType::class, 'account_type');
    }
    
    public function applications() {
        return $this->hasMany(Application::class, 'account_number', 'account_number');
    }
    
    public function investments() {
        // return $this->through('applications')->has('investments');
        return $this->hasManyThrough(Investment::class, Application::class, 'investment_reference', 'account_number', 'investment_reference', 'account_number');
    }
}
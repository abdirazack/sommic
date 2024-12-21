<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Constants\Status;
use App\Traits\Searchable;
 
class Investment extends Model
{
    use Searchable;
    
    public function branch() {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function branchStaff() {
        return $this->belongsTo(BranchStaff::class, 'branch_staff_id');
    }
    
    public function application() {
        return $this->belongsTo(Application::class, 'investment_reference', 'investment_reference');
    }
    
    public function product() {
        return $this->belongsTo(Product::class, 'product_id');
    }
    
    public function installments() {
        return $this->hasMany(InvestmentInstallment::class);
    }
}
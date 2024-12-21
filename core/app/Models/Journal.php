<?php

namespace App\Models;

use App\Constants\Status;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Searchable;
 
class Journal extends Model
{
    use Searchable;
    
    protected $table = "journal";
    protected $casts = [
        'misc' => 'object',
    ];
    
    public function category() {
        return $this->belongsTo(COACategory::class, 'category_id');
    }

    public function type() {
        return $this->belongsTo(COAType::class, 'type_id');
    }
    
    public function branch() {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function branchStaff() {
        return $this->belongsTo(BranchStaff::class, 'branch_staff_id');
    }
    
    public function coa() {
        return $this->belongsTo(COA::class, 'coa_id');
    }
    
    public function coa2() {
        return $this->belongsTo(COA::class, 'coa_id');
    }
    
    public function account() {
        return $this->belongsTo(Account::class, 'account_id');
    }
    
    public function scopeDebit($query) {
        return $query->where('dr_cr', 1)->selectRaw("SUM(amount) as _debit");
    }
    
    public function scopeCredit($query) {
        return $query->where('dr_cr', 2)->selectRaw("SUM(amount) as _credit");
    }
}
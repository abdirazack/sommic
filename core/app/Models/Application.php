<?php

namespace App\Models;

use App\Constants\Status;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Searchable;
 
class Application extends Model
{
    use Searchable;
    
    protected $table = "investment_applications";
    protected $casts = [
        'misc' => 'object',
        'personal_info' => 'object',
        'business_info' => 'object',
        'obligations_info' => 'object',
        'guarantor_info' => 'object'
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
    
    public function account() {
        return $this->belongsTo(Account::class, 'account_number', 'account_number')
        ->withDefault([
            'account_number'    =>  'N/A'
        ]);
    }
    
    public function investment() {
        return $this->belongsTo(Investment::class, 'investment_reference', 'investment_reference')
        ->withDefault([
            'investment_reference'  =>  'N/A',
            'total_investment_amount'   =>  0,
            'given_installments'    =>  0,
            'total_installments'     =>  0
        ]);
    }
    
    public function investments() {
        return $this->hasMany(Investment::class, 'investment_reference', 'investment_reference');
    }
}
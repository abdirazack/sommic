<?php

namespace App\Http\Controllers\BranchStaff;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Lib\FormProcessor;
use App\Models\BranchStaff;
use App\Models\COA;
use App\Models\COAType;
use App\Models\Journal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class TellerController extends Controller {
    
    private $dr_cr = [
        "1"  => "2",
        "2" => "1"
    ];
    
    public function index() {
        $pageTitle  = 'Tellers';
        $staff      = authStaff();
        $tellers = BranchStaff::query()->where('designation', STATUS::ROLE_TELLER)->with('assignBranch:id,name')
            ->searchable(['name', 'mobile', 'account_number', 'email'])
            ->latest()->paginate(getPaginate());
        
        return view('branch_staff.accounting.teller.index', compact('pageTitle', 'tellers'));
    }
    
    public function store(Request $request) {
        $this->validation($request);
        if($request->id > 0){
            $coa = COA::where('id', $request->id)->firstOrFail();
        }else{
            $coa = new COA();
        }
        
        $coa->name = $request->name;
        $coa->code = $request->code;
        $coa->category_id = $request->category;
        $coa->type_id = $request->type;
        $coa->dr_cr = $request->dr_cr;
        $coa->save();
        
        $notify[] = ['success', 'Account ' . ($request->id > 0 ? 'updated' : 'created') . ' successfully'];
        return back()->withNotify($notify);
    }
    
    public function destroy(Request $request) {
        $coa = COA::find($request->id)->delete();
        
        $notify[] = ['success', 'Account deleted successfully'];
        return back()->withNotify($notify);
    }
    
    public function transferFunds(Request $request) {
        $this->validateTransfer($request);
        
        $sourceAccount = COA::query()->where('id', $request->source_account)->firstOrFail();
        $beneficiaryAccount = COA::query()->where('id', $request->beneficiary_account)->firstOrFail();
        $amount = round($request->amount);
        
        if($sourceAccount->balance < $amount){
            $notify[] = ['error', 'Account has insufficient balance'];
            return back()->withNotify($notify);
        }
        
        $branch_id = authStaff()->id;
        $branch_staff_id = authStaff()->branch()->id;
        $transaction_id = getTrx();
        
        $sourceAccount->balance -= $amount;
        $sourceAccount->save();
        
        $beneficiaryAccount->balance += $amount;
        $beneficiaryAccount->save();
        
        
        // Source Account
        $Journal = new Journal();
        $Journal->branch_id          = $branch_id;
        $Journal->branch_staff_id    = $branch_staff_id;
        
        $Journal->transaction_id     = $transaction_id;
        $Journal->coa_id = $sourceAccount->id;
        $Journal->coa2_id = $beneficiaryAccount->id;
        
        $Journal->amount             = $amount;
        $Journal->remark             = "Fund Transfer";
        
        $Journal->dr_cr              = $sourceAccount->dr_cr;
        
        $Journal->save();
        
        // Beneficiary Account
        $Journal = new Journal();
        $Journal->branch_id          = $branch_id;
        $Journal->branch_staff_id    = $branch_staff_id;
        
        $Journal->transaction_id     = $transaction_id;
        $Journal->coa_id = $beneficiaryAccount->id;
        $Journal->coa2_id = $sourceAccount->id;
        
        $Journal->amount             = $amount;
        $Journal->remark             = "Fund Transfer";
        
        $Journal->dr_cr              = $beneficiaryAccount->dr_cr;
        
        $Journal->save();
        
        $notify[] = ['success', 'Fund transfer successful'];
        return back()->withNotify($notify);
        
    }
    
    private function validateTransfer(Request $request) {
        $request->validate([
            'source_account'    =>  'required|integer|different:beneficiary_account|exists:chart_of_accounts,id',
            'beneficiary_account'   =>  'required|integer|different:source_account|exists:chart_of_accounts,id',
            'amount'    =>  'required|numeric|gt:0'
        ]);
    }
    
    private function validation(Request $request) {
        $request->validate([
            'name'     => 'exclude_unless:id,0|required|string',
            'category'       => 'exclude_unless:id,0|required|in:1,2,3,4,5',
            'type'      => 'exclude_unless:id,0|required|digits_between:1,16',
            'code'        => 'exclude_unless:id,0|required|string|min:4|max:4',
            'dr_cr'     => 'exclude_unless:id,0|required|in:1,2',
        ]);
    }
    
}
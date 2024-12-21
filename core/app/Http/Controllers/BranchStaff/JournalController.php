<?php

namespace App\Http\Controllers\BranchStaff;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Lib\FormProcessor;
use App\Models\Journal;
use App\Models\COA;
use App\Models\COAType;
use App\Rules\FileTypeValidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

class JournalController extends Controller {
    
    public function index() {
        
        $pageTitle  = 'Journal Transactions';
        $staff      = authStaff();
        
        // $COATypes = COAType::with('children')->get();
        // $_ = [];
        // dd($COATypes->first()->children);
        // foreach($COATypes as $COAType) {
        //     dd($COAType->children()->name);
        // }
        // echo '<pre>';
        // dump($COATypes);
        // die('</pre>');
        
        $COA = COA::all();
        $Journal = Journal::query();
        
        $transactions = $Journal->with('COA', 'coa2');
        $request = Request();
        $account_category = ["", "Individual", "Joint", "Corporate"];
        
        if(!empty($request->all())){
            $this->filterValidation($request);
        }
        
        if(!empty($request->start) && !empty($request->end)){
            $transactions = $transactions->whereBetween('created_at',[Carbon::parse($request->start), Carbon::parse($request->end)]);
        }
        
        if(!empty($request->coa)){
            $transactions = $transactions->Where('coa2_id', $request->coa)->orderBy('transaction_id');
        }
        
        $transactions = $transactions->searchable(['transaction_id'])->latest()->paginate(getPaginate());;
        
        return view('branch_staff.accounting.journal.index', compact('pageTitle', 'transactions', 'COA', 'account_category'));
    }
    
    public function detail($transaction){
        $pageTitle  = 'Transaction Detail';
        $Journal = Journal::query();
        $transactions = $Journal->where('transaction_id', $transaction)->get();
        
        return View('branch_staff.accounting.journal.detail', compact('pageTitle', 'transactions'));
    }
    
    public function deposit(Request $request) {
        $this->depositValidation($request);
        
        $staff                  = authStaff();
        $branch                 = $staff->branch();
        
        $transaction_id = Str::ulid()->toBase32();
        $document = $this->upload('document', $request);
        
        // Customer Posting
        $Journal = new Journal();
        $Journal->branch_id = $branch->id;
        $Journal->branch_staff_id = $staff->id;
        
        $Journal->transaction_id = $transaction_id;
        $Journal->account_id = $request->account_id;
        $Journal->coa_id = 7;
        
        $Journal->amount = $request->amount;
        $Journal->description = $request->description;
        
        $Journal->dr_cr = 1;
        $Journal->is_coa = 0;
        $Journal->misc = ["document" => $document];
        
        $Journal->save();
        
        //Chart of Accounts Posting
        $Journal = new Journal();
        $Journal->branch_id = $branch->id;
        $Journal->branch_staff_id = $staff->id;
        
        $Journal->transaction_id = $transaction_id;
        $Journal->account_id = $request->account_id;
        $Journal->coa_id = 7;
        
        $Journal->amount = $request->amount;
        $Journal->description = "Customer Deposit";
        
        $Journal->dr_cr = 2;
        $Journal->is_coa = 1;
        $Journal->misc = ["document" => $document];
        
        $Journal->save();
        
        $notify[] = ['success', 'Deposit pending approval'];
        return back()->withNotify($notify)->withInput();
    }
    
    public function withdraw(Request $request) {
        $this->withdrawValidation($request);
        
        $staff                  = authStaff();
        $branch                 = $staff->branch();
        
        $transaction_id = Str::ulid()->toBase32();
        $document = $this->upload('document', $request);
        
        // Customer Posting
        $Journal = new Journal();
        $Journal->branch_id = $branch->id;
        $Journal->branch_staff_id = $staff->id;
        
        $Journal->transaction_id = $transaction_id;
        $Journal->account_id = $request->account_id;
        $Journal->coa_id = 7;
        
        $Journal->amount = $request->amount;
        $Journal->description = $request->description;
        
        $Journal->dr_cr = 2;
        $Journal->is_coa = 0;
        $Journal->misc = ["document" => $document];
        
        $Journal->save();
        
        // Chart of Accounts Posting
        $Journal = new Journal();
        $Journal->branch_id = $branch->id;
        $Journal->branch_staff_id = $staff->id;
        
        $Journal->transaction_id = $transaction_id;
        $Journal->account_id = $request->account_id;
        $Journal->coa_id = 7;
        
        $Journal->amount = $request->amount;
        $Journal->description = "Customer Withdraw";
        
        $Journal->dr_cr = 1;
        $Journal->is_coa = 1;
        $Journal->misc = ["document" => $document];
        
        $Journal->save();
        
        $notify[] = ['success', 'Withdrawal pending approval'];
        return back()->withNotify($notify)->withInput();
    }
    
    private function upload($input_name, $request){
        $isDocument = in_array($request->file($input_name)->getClientOriginalExtension(), ['pdf', 'docx', 'PDF', 'DOCX']);
        if ($request->hasFile($input_name)) {
            try {
                $file = fileUploader($request->file($input_name), getFilePath('userProfile'), $isDocument ? null : getFileSize('userProfile'));
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Couldn\'t upload your file'];
                return back()->withNotify($notify);
            }
            return $file;
        }
    }
    
    private function filterValidation(Request $request) {
        $date = explode(" - ", $request->date);
        
        if(array_filter($date) != []){
            $date = [
                'start' => $date[0],
                'end'   => $date[1] ?? Carbon::parse($date[0])->addDays(1)
            ];
            $request->merge($date);
        }
        
        $request->validate([
            'start' => 'date|before_or_equal:end',
            'end'   => 'date|after_or_equal:start',
            'coa'   => 'exists:chart_of_accounts,id'
        ]);
    }
    
    private function depositValidation(Request $request) {
        $request->validate([
            'amount'     => 'required|numeric',
            'description'       => 'required|string',
        ]);
    }
    
    private function withdrawValidation(Request $request) {
        $request->validate([
            'amount'     => 'required|numeric',
            'description'       => 'required|string',
            'document' => 'required'
        ]);
    }
    
}
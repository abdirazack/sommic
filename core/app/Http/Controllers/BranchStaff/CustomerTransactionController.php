<?php

namespace App\Http\Controllers\BranchStaff;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Lib\FormProcessor;
use App\Models\Journal;
use App\Models\CustomerTransaction;
use App\Models\COA;
use App\Models\Customer;
use App\Models\Account;
use App\Rules\FileTypeValidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

class CustomerTransactionController extends Controller {
    
    private $dr_cr = [
        "Deposit"  => ["1", "2"],
        "Withdraw" => ["2", "1"]
    ];
    
    private $account_category = ["", "Individual", "Joint", "Corporate"];
    
    private $status = [
        ["label" => "Pending", "badge" => "warning"],
        ["label" => "Active", "badge" => "success"],
        ["label" => "Rejected", "badge" => "danger"],
    ];
    
    public function index() {
        
        $pageTitle  = 'Customer Transactions';
        $staff      = authStaff();
        
        $COA = COA::all();
        $CustomerTransactions = CustomerTransaction::query();
        
        $transactions = $CustomerTransactions;
        $request = Request();
        $account_category = $this->account_category;
        
        if(!empty($request->all())){
            $this->filterValidation($request);
        }
        
        if(!empty($request->start) && !empty($request->end)){
            $transactions = $transactions->whereBetween('created_at',[Carbon::parse($request->start), Carbon::parse($request->end)]);
        }
        
        if($staff->designation != Status::ROLE_ACCOUNTING && !isManager()){
            $transactions->where('branch_staff_id', $staff->id);
        }
        
        $transactions = $transactions->searchable(['transaction_id', 'account_id'])->with('account', 'branchStaff')->latest()->paginate(getPaginate());;
        
        
        return view('branch_staff.transactions.index', compact('pageTitle', 'transactions', 'COA', 'account_category'));
    }
    
    public function detail($transaction){
        $pageTitle  = 'Transaction Detail';
        $staff      = authStaff();
        
        $JournalTransactions = Journal::query()->where('transaction_id', $transaction)->with('coa')->get();
        
        $transaction = CustomerTransaction::query()->where('transaction_id', $transaction)->first();
        
        $transactionAccount = $transaction->account;
        $account_category = $this->account_category;
        
        $customers = Customer::where('id', $transactionAccount->customer_id)
        ->orWhere('id', $transactionAccount->customer2_id)
        ->orWhere('id', $transactionAccount->customer3_id)
        ->orWhere('id', $transactionAccount->customer4_id)
        ->orWhere('id', $transactionAccount->customer5_id)
        ->get();
        
        $status = $this->status;
        
        return View('branch_staff.transactions.detail', compact('pageTitle', 'staff', 'transaction', 'JournalTransactions', 'customers', 'account_category', 'transactionAccount', 'status'));
    }
    
    public function deposit(Request $request) {
        $this->depositValidation($request);
        
        $this->staff_id                  = authStaff()->id;
        $this->branch_id                 = authStaff()->branch()->id;
        
        // $this->transaction_id = Str::ulid()->toBase32();
        $this->transaction_id = getTrx();
        $this->document = $this->upload('document', $request);
        
        $this->account_id = $request->account_id;
        $this->amount = $request->amount;
        $this->description = $request->description;
        
        $this->remark = "Deposit";
        $this->postToCustomerTransaction();
        $this->postToJournal();
        
        $notify[] = ['success', 'Deposit pending approval'];
        return back()->withNotify($notify)->withInput();
    }
    
    public function withdraw(Request $request) {
        $this->withdrawValidation($request);
        
        $account = Account::query()->where('account_number', $request->account_id)->firstOrFail();
        if($account->account_balance < $account->shadow_balance || $account->shadow_balance > 0){
            $notify[] = ['error', 'Account has pending murabaha payment.'];
            return back()->withNotify($notify)->withInput();
        }
        
        $this->staff_id                  = authStaff()->id;
        $this->branch_id                 = authStaff()->branch()->id;
        
        // $this->transaction_id = Str::ulid()->toBase32();
        $this->transaction_id = getTrx();
        $this->document = $this->upload('document', $request);
        
        $this->account_id = $request->account_id;
        $this->amount = $request->amount;
        $this->description = $request->description;
        
        $this->remark = "Withdraw";
        $this->postToCustomerTransaction();
        $this->postToJournal();
        
        $notify[] = ['success', 'Withdrawal pending approval'];
        return back()->withNotify($notify)->withInput();
    }
    
    private function postToCustomerTransaction(){
        
        $CustomerTransaction = new CustomerTransaction();
        $CustomerTransaction->branch_id         = $this->branch_id;
        $CustomerTransaction->branch_staff_id   = $this->staff_id;
        
        $CustomerTransaction->account_id = $this->account_id;
        $CustomerTransaction->transaction_id = $this->transaction_id;
        
        $CustomerTransaction->amount = $this->amount;
        $CustomerTransaction->description = $this->description;
        
        $CustomerTransaction->dr_cr = $this->dr_cr[$this->remark][0];
        $CustomerTransaction->misc = ["document" => $this->document];
        
        $CustomerTransaction->save();
    }
    
    private function postToJournal(){
        // Somcommunity Bank
        $Journal = new Journal();
        $Journal->branch_id          = $this->branch_id;
        $Journal->branch_staff_id    = $this->staff_id;
        
        $Journal->transaction_id     = $this->transaction_id;
        $Journal->coa_id = 1;
        $Journal->coa2_id = 13;
        
        $Journal->amount             = $this->amount;
        $Journal->remark             = "Customer ".$this->remark;
        
        $Journal->dr_cr              = $this->dr_cr[$this->remark][0];
        
        $Journal->save();
        
        // Accounts Payable
        $Journal = new Journal();
        $Journal->branch_id          = $this->branch_id;
        $Journal->branch_staff_id    = $this->staff_id;
        
        $Journal->transaction_id     = $this->transaction_id;
        $Journal->coa_id = 13;
        $Journal->coa2_id = 1;
        
        $Journal->amount             = $this->amount;
        $Journal->remark             = "Customer ".$this->remark;
        
        $Journal->dr_cr              = $this->dr_cr[$this->remark][1];
        
        $Journal->save();
    }
    
    public function approve($transaction_id) {
        $transaction = CustomerTransaction::where('transaction_id', $transaction_id)->firstOrFail();
        $transactionAccount = $transaction->account;
        $transactionType = $transaction->dr_cr;
        $transactionAmount = $transaction->amount;
        
        $balance = $transactionAccount->account_balance - $transactionAmount;
        
        if($transactionType == 1) {
            $balance = $transactionAccount->account_balance + $transactionAmount;
        }elseif($transactionType == 2 && $balance >= 0)  {
            $balance = $transactionAccount->account_balance - $transactionAmount;
        }else{
            $notify[] = ['error', 'Transaction failed'];
            return back()->withNotify($notify);
        }
        
        $account = Account::where('account_number', $transactionAccount->account_number)->update(['account_balance' => round($balance, 2)]);
        $Journal = Journal::where('transaction_id', $transaction_id)->update(['status' =>  1]);
        $transaction = CustomerTransaction::where('transaction_id', $transaction_id)->update(['status' =>  1]);
        
        $notify[] = ['success', 'Transaction approved successfully'];
        return back()->withNotify($notify);
    }
    
    public function reject($transaction) {
        $Journal = Journal::where('transaction_id', $transaction)->update(['status' =>  2]);
        $transaction = CustomerTransaction::where('transaction_id', $transaction)->update(['status' =>  2]);
        
        $notify[] = ['success', 'Transaction approved successfully'];
        return back()->withNotify($notify);
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
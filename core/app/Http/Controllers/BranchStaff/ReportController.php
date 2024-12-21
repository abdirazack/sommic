<?php

namespace App\Http\Controllers\BranchStaff;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Lib\FormProcessor;
use App\Models\AdminNotification;
use App\Models\Form;
use App\Models\Customer;
use App\Models\Account;
use App\Rules\FileTypeValidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class ReportController extends Controller {
    
    private $relations = [
        "Parent", "Sibling", "Spouse", "Child", "Relative", "Beneficiary", "Friend"
    ];
    
    private $account_category = [
        "", "Individual", "Joint", "Corporate"
    ];
    
    private $status = [
        ["label" => "Pending", "badge" => "warning"],
        ["label" => "Active", "badge" => "success"],
        ["label" => "Rejected", "badge" => "danger"],
    ];
    
    public function index() {
        $pageTitle  = 'All Customers';
        $staff      = authStaff();
        $customers   = Customer::query();
        $assignedBranches = null;
        $branchId   = session('branchId');
        $branches   = $staff->designation == Status::ROLE_MANAGER ? $staff->assignBranch : null;

        if(!isset(request()->search) && $staff->designation != Status::ROLE_MANAGER && $staff->designation != Status::ROLE_ACCOUNTING){
            $customers   = $customers->where('branch_id', -1);
        }
        
        if ($staff->designation == Status::ROLE_MANAGER && !isset(request()->search)) {
            $assignedBranches = isset(request()->branch) ? [request()->branch] : $branches->pluck('id')->toArray();
            $customers   = $customers->whereIn('branch_id', $assignedBranches)->where('status', 0);
        }
        
        $customers   = $customers->searchable(['name', 'email', 'mobile'])->with('branch', 'branchStaff')->latest()->paginate(getPaginate());
        
        $status = $this->status;

        return view('branch_staff.user.customer_list', compact('pageTitle', 'customers', 'staff', 'branches', 'branchId', 'status'));
    }
    
    public function find(Request $request){
        $customer = Customer::query();
        $result = $customer->where('mobile', $request->mobile)->where('customer_type', '0')->first();
        return json_encode($result);
    }
    
    public function detail($customerId) {
        $staff   = authStaff();
        
        $customer    = Customer::where('id', $customerId)->first();

        if (!$customer) {
            $notify[] = ['error', 'Customer not found'];
            return back()->withNotify($notify)->withInput();
        }
        
        $countries = $this->getCountries(false);
        
        $relations = $this->relations;
        
        $canChangeStatus = in_array($customer->branch_id, $staff->assignBranch->pluck('id')->toArray());
        
        if($customer->customer_type == 0){
            $accounts = Account::where('customer_id', $customerId)
            ->orWhere('customer2_id', $customerId)
            ->orWhere('customer3_id', $customerId)
            ->orWhere('customer4_id', $customerId)
            ->orWhere('customer5_id', $customerId);
        }else{
            $accounts = Account::where('organization_id', $customerId);
        }
        
        $accounts = $accounts->get();
        
        $account_category = $this->account_category;
        
        $status = $this->status;
        
        $pageTitle = 'Customer Details';
        return view('branch_staff.user.customer_detail', compact('pageTitle', 'customer', 'accounts', 'staff', 'account_category', 'canChangeStatus', 'countries', 'relations', 'status'));
    }
    
    public function approve($customerId){
        $count = Customer::where("id", $customerId)->update(["status" => 1]);
        if($count){
            $notify[] = ['success', 'Customer approved'];
        }else{
            $notify[] = ['success', 'Operation failed'];
        }
        
        return back()->withNotify($notify);
    }
    
    public function reject($customerId){
        $count = Customer::where("id", $customerId)->update(["status" => 2]);
        if($count){
            $notify[] = ['success', 'Customer rejected'];
        }else{
            $notify[] = ['error', 'Operation failed'];
        }
        
        return back()->withNotify($notify);
    }
    
    private function getCountries($asString = true){
        $countryData  = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        $countries = (array) $countryData;
        if($asString) return implode(',', array_keys($countries));
        return $countries;
    }
}

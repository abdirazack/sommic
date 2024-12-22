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

class AccountController extends Controller {

    private $relations = [
        "[INTENTIONALLY BLANK LOL]", "Parent", "Sibling", "Spouse", "Child", "Relative", "Beneficiary", "Friend"
    ];

    private $status = [
        ["label" => "Pending", "badge" => "warning"],
        ["label" => "Active", "badge" => "success"],
        ["label" => "Rejected", "badge" => "danger"],
    ];

    public function accountsAll() {
        $pageTitle  = 'All Accounts';
        $staff      = authStaff();
        $accounts   = Account::query();
        $branchId   = session('branchId');
        $branches   = $staff->designation == Status::ROLE_MANAGER ? $staff->assignBranch : null;

        if ($staff->designation == Status::ROLE_MANAGER) {
            $branchId = request()->branch;
        } else {
            //$accounts   = $accounts->where('branch_staff_id', $staff->id);
        }

        if ($branchId) {
            //$accounts   = $accounts->where('branch_id', $branchId);
        }

        if(!isset(request()->search)){
            $accounts   = $accounts->where('branch_id', -1);
        }

        $accounts   = $accounts->searchable(['account_number'])->latest()->paginate(getPaginate());

        return view('branch_staff.user.accounts_list', compact('pageTitle', 'accounts', 'staff', 'branches', 'branchId'));
    }

    public function findAccount() {
        $result = [];

        $account = Account::query()->where("account_number", Request()->account_number)->first();

        if(!$account){
            return json_encode(['data' => ['success' => false]]);
        }
        $customers = Customer::where('id', $account->customer_id)
        ->orWhere('id', $account->customer2_id)
        ->orWhere('id', $account->customer3_id)
        ->orWhere('id', $account->customer4_id)
        ->orWhere('id', $account->customer5_id)
        ->get();

        $result['success']   = true;

        $result['account'] = [
            'number'    =>  $account->account_number,
            'account_type'  =>  $account->account_type
        ];

        foreach($customers as $customer){
            $result['customers'][] = [
                'id'    =>  $customer->id,
                'name' => $customer->name,
                'mobile'    =>  $customer->mobile
            ];
        }

        return json_encode(['data'  =>  $result]);
    }

    public function accountsIndividual() {
        $pageTitle  = 'Individual Accounts';
        $staff      = authStaff();
        $accounts   = Account::query();
        $branchId   = session('branchId');
        $branches   = $staff->designation == Status::ROLE_MANAGER ? $staff->assignBranch : null;

        if(!isset(request()->search) && $staff->designation != Status::ROLE_MANAGER){
            $accounts   = $accounts->where('branch_id', -1);
        }

        if ($staff->designation == Status::ROLE_MANAGER && !isset(request()->search)) {
            $branch = isset(request()->branch) ? [request()->branch] : $branches->pluck('id')->toArray();
            $accounts   = $accounts->whereIn('branch_id', $branch)->where('status', 0);
        }

        $accounts   = $accounts->where('account_category', 1)->searchable(['account_number'])->with('customer', 'branch:id,name', 'branchStaff')->latest()->paginate(getPaginate());

        $status = $this->status;

        return view('branch_staff.user.individual_list', compact('pageTitle', 'accounts', 'status', 'staff', 'branches', 'branchId'));
    }

    public function accountsJoint() {
        $pageTitle  = 'Joint Accounts';
        $staff      = authStaff();
        $accounts   = Account::query();
        $branchId   = session('branchId');
        $branches   = $staff->designation == Status::ROLE_MANAGER ? $staff->assignBranch : null;

        if(!isset(request()->search) && $staff->designation != Status::ROLE_MANAGER){
            $accounts   = $accounts->where('branch_id', -1);
        }

        if ($staff->designation == Status::ROLE_MANAGER && !isset(request()->search)) {
            $branch = isset(request()->branch) ? [request()->branch] : $branches->pluck('id')->toArray();
            $accounts   = $accounts->whereIn('branch_id', $branch)->where('status', 0);
        }

        $accounts   = $accounts->where('account_category', 2)->searchable(['account_number'])->with('branch', 'branchStaff')->latest()->paginate(getPaginate());

        $status = $this->status;

        return view('branch_staff.user.joint_list', compact('pageTitle', 'accounts', 'status', 'staff', 'branches', 'branchId'));
    }

    public function accountsCorporate() {
        $pageTitle  = 'Corporate Accounts';
        $staff      = authStaff();
        $accounts   = Account::query();
        $branchId   = session('branchId');
        $branches   = $staff->designation == Status::ROLE_MANAGER ? $staff->assignBranch : null;

        if(!isset(request()->search) && $staff->designation != Status::ROLE_MANAGER){
            $accounts   = $accounts->where('branch_id', -1);
        }

        if ($staff->designation == Status::ROLE_MANAGER && !isset(request()->search)) {
            $branch = isset(request()->branch) ? [request()->branch] : $branches->pluck('id')->toArray();
            $accounts   = $accounts->whereIn('branch_id', $branch)->where('status', 0);
        }

        $accounts   = $accounts->where('account_category', 3)->searchable(['account_number'])->with('branch', 'branchStaff')->latest()->paginate(getPaginate());

        $status = $this->status;

        return view('branch_staff.user.corporate_list', compact('pageTitle', 'accounts', 'status', 'staff', 'branches', 'branchId'));
    }

    public function detailIndividual($accountNumber) {
        $staff   = authStaff();
        $account = $accountNumber;
        $account    = Account::where('account_number', $account)->where('account_category', 1)->firstOrFail();

        if(!$account){

            $notify[] = ['error', 'Account not found'];
            return back()->withNotify($notify)->withInput();
        }

        $countries = $this->getCountries(false);

        $relations = $this->relations;

        $canChangeStatus = in_array($account->branch_id, $staff->assignBranch->pluck('id')->toArray());

        $pageTitle  = 'Individual Account Details';
        return view('branch_staff.user.individual_detail', compact('pageTitle', 'account', 'staff', 'canChangeStatus', 'countries', 'relations'));
    }

    public function detailJoint($accountNumber) {
        $staff   = authStaff();
        $account = $accountNumber;
        $account    = Account::where('account_number', $account)->where('account_category', 2)->firstOrFail();

        if (!$account) {
            $notify[] = ['error', 'Account not found'];
            return back()->withNotify($notify)->withInput();
        }

        $customers = Customer::where('id', $account->customer_id)
        ->orWhere('id', $account->customer2_id)
        ->orWhere('id', $account->customer3_id)
        ->orWhere('id', $account->customer4_id)
        ->orWhere('id', $account->customer5_id)
        ->get();


        $countries = $this->getCountries(false);

        $relations = $this->relations;

        $canChangeStatus = in_array($account->branch_id, $staff->assignBranch->pluck('id')->toArray());

        $pageTitle  = 'Joint Account Details';
        return view('branch_staff.user.joint_detail', compact('pageTitle', 'account', 'customers', 'staff', 'canChangeStatus', 'countries', 'relations'));
    }

    public function detailCorporate($accountNumber) {
        $staff   = authStaff();
        $account = $accountNumber;
        $account    = Account::where('account_number', $account)->where('account_category', 3)->firstOrFail();

        if (!$account) {
            $notify[] = ['error', 'Account not found'];
            return back()->withNotify($notify)->withInput();
        }

        $customers = Customer::where('id', $account->customer_id)
        ->orWhere('id', $account->customer2_id)
        ->orWhere('id', $account->customer3_id)
        ->orWhere('id', $account->customer4_id)
        ->orWhere('id', $account->customer5_id)
        ->get();


        $countries = $this->getCountries(false);

        $relations = $this->relations;

        $canChangeStatus = in_array($account->branch_id, $staff->assignBranch->pluck('id')->toArray());

        $pageTitle  = 'Corporate Account Details';
        return view('branch_staff.user.corporate_detail', compact('pageTitle', 'account', 'customers', 'staff', 'canChangeStatus', 'countries', 'relations'));
    }

    public function openIndividual($account = null) {

        $countries  = json_decode(file_get_contents(resource_path('views/partials/country.json')));

        if ($account) {
            $account = User::where('account_number', $account)->firstOrFail();
            $action = route('staff.account.update', @$account->id);
            $pageTitle  = 'Edit Account Details';
        } else {
            $pageTitle  = 'Open New Individual Account';
            $action = route('staff.account.open.individual.save');
        }

        return view('branch_staff.user.form_individual', compact('pageTitle', 'account', 'countries', 'action'));
    }

    public function openJoint($account = null) {

        $countries  = json_decode(file_get_contents(resource_path('views/partials/country.json')));

        if ($account) {
            $account = User::where('account_number', $account)->firstOrFail();
            $action = route('staff.account.update', @$account->id);
            $pageTitle  = 'Edit Account Details';
        } else {
            $pageTitle  = 'Open New Joint Account';
            $action = route('staff.account.open.joint.save');
        }

        return view('branch_staff.user.form_joint', compact('pageTitle', 'account', 'countries', 'action'));
    }

    public function openCorporate($account = null) {

        $countries  = json_decode(file_get_contents(resource_path('views/partials/country.json')));

        if ($account) {
            $account = User::where('account_number', $account)->firstOrFail();
            $action = route('staff.account.update', @$account->id);
            $pageTitle  = 'Edit Account Details';
        } else {
            $pageTitle  = 'Open New Corporate Account';
            $action = route('staff.account.open.corporate.save');
        }

        return view('branch_staff.user.form_corporate', compact('pageTitle', 'account', 'countries', 'action'));
    }

    public function saveIndividual(Request $request) {

        $this->validateIndividual($request);

        $countryData  = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        $countries = (array) $countryData;


        $general = gs();
        $password              = getTrx(8);

        $staff                  = authStaff();
        $branch                 = $staff->branch();

        $customer              = new Customer();

        // if ($general->modules->referral_system && $request->referrer) {

        //     $referrer = User::where('account_number', $request->referrer)->first();

        //     if (!$referrer) {
        //         $notify[] = ['error', 'Referrer account not found'];
        //         return back()->withNotify($notify)->withInput();
        //     }

        //     $user->ref_by = $referrer->id;
        //     $user->referral_commission_count = $general->referral_commission_count;
        // }
        if(! $request->person_id > 0){
            $customer->branch_id = $branch->id;
            $customer->branch_staff_id = $staff->id;

            $customer->name                     = strtoupper($request->fullname);
            $customer->customer_type            = 0;
            $customer->identifier_type          = $request->doc_type;
            $customer->identifier_link          = $this->upload('doc_scan', $request);
            $customer->identifier_expiry_date   = $request->doc_expiry;
            $customer->mobile                   = $request->mobile;
            $customer->email                    = $request->email;
            $customer->region                   = strtoupper($request->region);
            $customer->city                     = strtoupper($request->city);
            $customer->address                  = strtoupper($request->address);
            $customer->misc                     = [
                'image' => $this->upload('image', $request),
                'gender' => $request->gender,
                'nationality' => $request->country,
                'pob' => $request->pob,
                'dob' => $request->dob,
                'marital' => $request->marital,
                'employment_status' => $request->employment_status,
                'employment_detail' => $request->employment_detail
            ];
            // $customer                   = $this->saveUser($request, $user);

            // $adminNotification            = new AdminNotification();

            // $adminNotification->user_id   = $user->id;
            // $adminNotification->title     = 'New account opened from ' . $branch->name;
            // $adminNotification->click_url = urlPath('admin.users.detail', $user->id);
            // $adminNotification->save();

            $customer->save();
        }else{
            $customer->id = $request->person_id;
        }

        //die("{$request->wallet}, {$request->cheque}, {$request->ebank}");

        if($customer->id > 0){
            // die($customer->id);
            $account = new Account();

            $account->branch_id = $branch->id;
            $account->branch_staff_id = $staff->id;
            $account->customer_id = $customer->id;

            $account->account_number = generateAccountNumber();
            $account->account_category = 1;
            $account->account_type = $request->account_type;
            $account->account_balance = 0;
            $account->cheque = $request->cheque == null ? 0 : 1;
            $account->ebank = $request->ebank == null ? 0 : 1;
            $account->wallet = $request->wallet == null ? 0 : 1;
            $account->misc = [
                'daily_withdraw_amount_limit' => $request->withdraw_amount_limit,
                'daily_withdraw_freq_limit' => $request->withdraw_freq_limit,
                'nok' => [
                    'p1_nok' => [
                        'nok1' => [
                            'name' => strtoupper($request->nok1_name),
                            'relation' => $request->nok1_relation,
                            'mobile' => $request->nok1_mobile
                        ],
                        'nok2' => [
                            'name' => strtoupper($request->nok2_name),
                            'relation' => $request->nok2_relation,
                            'mobile' => $request->nok2_mobile
                        ]
                    ]
                ],
                'documents' => [
                    'signature_scan' => $this->upload('signature_scan', $request),
                    'application_scan' => $this->upload('application_scan', $request)
                ]
            ];

            $account->save();

            // notify($customer, 'ACCOUNT_OPENED', [
            //     'email'    => $customer->email
            // ]);

            $notify[] = ['success', 'Form submitted successfully'];
            return back()->withNotify($notify);
        }
    }

    public function saveJoint(Request $request) {

        $this->validateJoint($request);

        $countryData  = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        $countries = (array) $countryData;

        $general = gs();
        $password              = getTrx(8);

        $staff                  = authStaff();
        $branch                 = $staff->branch();

        $persons = $request->p;
        $customersID = $nok = $documents = [];

        foreach($persons as $index => $person){

            $person = (object) $person;

            if(! $person->id > 0){

                $customer = new Customer();

                $customer->branch_id = $branch->id;
                $customer->branch_staff_id = $staff->id;

                $customer->name                     = strtoupper($person->fullname);
                $customer->customer_type            = 0;
                $customer->identifier_type          = $person->doc_type;
                $customer->identifier_link          = $this->upload("p.${index}.doc_scan", $request);
                $customer->identifier_expiry_date   = $person->doc_expiry;
                $customer->mobile                   = $person->mobile;
                $customer->email                    = $person->email;
                $customer->region                   = strtoupper($person->region);
                $customer->city                     = strtoupper($person->city);
                $customer->address                  = strtoupper($person->address);
                $customer->misc                     = [
                    'image' => $this->upload("p.${index}.image", $request),
                    'gender' => $person->gender,
                    'nationality' => $person->country,
                    'pob' => $person->pob,
                    'dob' => $person->dob,
                    'marital' => $person->marital,
                    'employment_status' => $person->employment_status,
                    'employment_detail' => strtoupper($person->employment_detail)
                ];

                $customer->save();

                $customersId[] = $customer->id;
            }else{
                $customersId[] = $person->id;
            }

            $nok["p${index}_nok"] = [
                'nok1' => $person->nok1,
                'nok2' => $person->nok2
            ];

            $documents["p${index}_signature_scan"] = $this->upload("p.${index}.signature_scan", $request);
        }

        $documents['application_scan'] = $this->upload('application_scan', $request);

        if($this->checkCustomers($customersId)){

            $account = new Account();

            $account->branch_id = $branch->id;
            $account->branch_staff_id = $staff->id;
            $account->customer_id = $customersId[0] ?? null;
            $account->customer2_id = $customersId[1] ?? null;
            $account->customer3_id = $customersId[2] ?? null;
            $account->customer4_id = $customersId[3] ?? null;
            $account->customer5_id = $customersId[4] ?? null;

            $account->account_number = generateAccountNumber();
            $account->account_type = $request->account_type;
            $account->account_category = 2;
            $account->account_balance = 0;

            $account->cheque = $request->cheque == null ? 0 : 1;
            $account->ebank = $request->ebank == null ? 0 : 1;
            $account->wallet = $request->wallet == null ? 0 : 1;
            $account->misc = [
                'daily_withdraw_amount_limit' => $request->withdraw_amount_limit,
                'daily_withdraw_freq_limit' => $request->withdraw_freq_limit,
                'nok' => $nok,
                'documents' => $documents
            ];

            $account->save();

            // notify($customer, 'ACCOUNT_OPENED', [
            //     'email'    => $customer->email
            // ]);

            $notify[] = ['success', 'Form submitted successfully'];
            return back()->withNotify($notify);
        }
        $notify[] = ['error', 'Hmmm odd error stikes again!'];
        return back()->withNotify($notify)->withInput();

    }

    public function saveCorporate(Request $request) {
        $this->validateCorporate($request);

        $countryData  = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        $countries = (array) $countryData;

        $general = gs();
        $password              = getTrx(8);

        $staff                  = authStaff();
        $branch                 = $staff->branch();

        $persons = $request->p;
        $customersID = $nok = $documents = [];

        $customer = new Customer();

        $customer->branch_id = $branch->id;
        $customer->branch_staff_id = $staff->id;

        $customer->name                     = strtoupper($request->name);
        $customer->customer_type            = 1;
        $customer->identifier_type          = $request->doc_type;
        $customer->identifier_link          = $this->upload("doc_scan", $request);
        $customer->identifier_expiry_date   = $request->doc_expiry;
        $customer->mobile                   = $request->mobile;
        $customer->email                    = $request->email;
        $customer->region                   = strtoupper($request->region);
        $customer->city                     = strtoupper($request->city);
        $customer->address                  = strtoupper($request->address);
        $customer->misc                     = [
            'organization_type' => $request->organization_type,
            'establishment_date' => $request->establishment_date,
            'country' => $request->country,
            'organization_profile_scan' => $this->upload('organization_profile_scan', $request),
        ];

        $customer->save();
        $organization_id = $customer->id;

        foreach($persons as $index => $person){

            $person = (object) $person;

            if(! $person->id > 0){

                $customer = new Customer();

                $customer->branch_id = $branch->id;
                $customer->branch_staff_id = $staff->id;

                $customer->name                     = strtoupper($person->fullname);
                $customer->identifier_type          = $person->doc_type;
                $customer->identifier_link          = $this->upload("p.${index}.doc_scan", $request);
                $customer->identifier_expiry_date   = $person->doc_expiry;
                $customer->mobile                   = $person->mobile;
                $customer->email                    = $person->email;
                $customer->region                   = strtoupper($person->region);
                $customer->city                     = strtoupper($person->city);
                $customer->address                  = strtoupper($person->address);
                $customer->misc                     = [
                    'image' => $this->upload("p.${index}.image", $request),
                    'gender' => $person->gender,
                    'nationality' => $person->country,
                    'pob' => $person->pob,
                    'dob' => $person->dob,
                    'marital' => $person->marital,
                    'employment_status' => $person->employment_status,
                    'employment_detail' => strtoupper($person->employment_detail)
                ];

                $customer->save();

                $customersId[] = $customer->id;
            }else{
                $customersId[] = $person->id;
            }

            if($request->organization_type == 1){
                $nok["p${index}_nok"] = [
                    'nok1' => $person->nok1,
                    'nok2' => $person->nok2
                ];
            }

            $documents["p${index}_signature_scan"] = $this->upload("p.${index}.signature_scan", $request);
        }

        $documents['application_scan'] = $this->upload('application_scan', $request);

        if($this->checkCustomers($customersId)){

            $account = new Account();

            $account->branch_id = $branch->id;
            $account->branch_staff_id = $staff->id;
            $account->customer_id = $customersId[0] ?? null;
            $account->customer2_id = $customersId[1] ?? null;
            $account->customer3_id = $customersId[2] ?? null;
            $account->customer4_id = $customersId[3] ?? null;
            $account->customer5_id = $customersId[4] ?? null;

            $account->organization_id = $organization_id;

            $account->account_number = generateAccountNumber();
            $account->account_type = $request->account_type;
            $account->account_category = 3;
            $account->account_balance = 0;

            $account->cheque = $request->cheque == null ? 0 : 1;
            $account->ebank = $request->ebank == null ? 0 : 1;
            $account->wallet = $request->wallet == null ? 0 : 1;
            $account->misc = [
                'daily_withdraw_amount_limit' => $request->withdraw_amount_limit,
                'daily_withdraw_freq_limit' => $request->withdraw_freq_limit,
                'nok' => $nok,
                'documents' => $documents
            ];

            $account->save();

            // notify($customer, 'ACCOUNT_OPENED', [
            //     'email'    => $customer->email
            // ]);

            $notify[] = ['success', 'Form submitted successfully'];
            return back()->withNotify($notify);
        }
        $notify[] = ['error', 'Hmmm odd error stikes again!'];
        return back()->withNotify($notify)->withInput();

    }

    public function approve($accountId){
        $nonApprovedCustomers = [];
        $account = Account::query();
        $account = $account->where("id", $accountId)->first();

        $customers = Customer::where('id', $account->customer_id)
        ->orWhere('id', $account->customer2_id)
        ->orWhere('id', $account->customer3_id)
        ->orWhere('id', $account->customer4_id)
        ->orWhere('id', $account->customer5_id)
        ->get();

        foreach($customers as $customer){
            if($customer->status != 1){
                $nonApprovedCustomers[] = $customer->id;
            }
        }

        if(count($nonApprovedCustomers) > 0){
            $notify[] = ['error', 'Approve account holder(s) first'];
        }else{
            $count = Account::where("id", $accountId)->update(["status" => 1]);
            if($count){
                $notify[] = ['success', 'Account approved'];
            }
        }

        return back()->withNotify($notify);
    }

    public function reject($accountId){
        $count = Account::where("id", $accountId)->update(["status" => 2]);
        if($count){
            $notify[] = ['success', 'Account rejected'];
        }else{
            $notify[] = ['error', 'Operation failed'];
        }

        return back()->withNotify($notify);
    }

    private function validateIndividual($request) {
        $countries = $this->getCountries();

        $request->merge(['mobile' => $request->mobile_code . $request->mobile]);

        $request->validate([
            'fullname'     => 'exclude_unless:person_id,0|required|string',
            'gender'       => 'exclude_unless:person_id,0|required|in:1,2',
            'marital'      => 'exclude_unless:person_id,0|required|in:1,2,3,4',
            'email'        => 'exclude_unless:person_id,0|required|string|email',
            'mobile'       => 'exclude_unless:person_id,0|required|numeric|unique:customers',
            'country'      => 'exclude_unless:person_id,0|required|in:' . $countries,
            'pob'          => 'exclude_unless:person_id,0|required|in:' . $countries,
            'dob'          => 'exclude_unless:person_id,0|required|date',
            'region'          => 'exclude_unless:person_id,0|required|string',
            'city'          => 'exclude_unless:person_id,0|required|string',
            'address'          => 'exclude_unless:person_id,0|required|string',
            'employment_status' => 'exclude_unless:person_id,0|required|in:1,2,3,4',
            'employment_detail' => 'exclude_unless:person_id,0|required|string',

            'nok1_name'     => 'required|string',
            'nok1_relation' => 'required|in:1,2,3,4,5,6,7',
            'nok1_mobile'   => 'required|numeric',

            'nok2_name'     => 'required|string',
            'nok2_relation' => 'required|in:1,2,3,4,5,6,7',
            'nok2_mobile'   => 'required|numeric',

            'doc_type'      => 'exclude_unless:person_id,0|required|in:1,2,3,4,5,6',
            'doc_expiry'    => 'exclude_unless:person_id,0|required|date',

            'account_type'  => 'required|in:1,2',
            'withdraw_amount_limit' => 'required|numeric',
            'withdraw_freq_limit'   => 'required|numeric',

        ]);

        if(! $request->person_id > 0){
            $exist = Customer::where('mobile', $request->mobile_code . $request->mobile)->first();
            if ($exist) {
                $notify[] = ['error', 'The mobile number already exists'];
                return back()->withNotify($notify)->withInput();
            }
        }
    }

    private function validateJoint($request) {
        $countries = $this->getCountries();
        $persons = $request->p;
        foreach($persons as &$person){
            if($person['id'] == 0){
                $person['mobile'] = $person['mobile_code'] . $person['mobile'];
            }
        }
        $request->merge(['p' => $persons]);
        $request->validate([

            //Persons Validation
            'p.*.fullname'     => 'exclude_unless:p.*.id,0|required|string',
            'p.*.gender'       => 'exclude_unless:p.*.id,0|required|in:1,2',
            'p.*.marital'      => 'exclude_unless:p.*.id,0|required|in:1,2,3,4',
            'p.*.email'        => 'exclude_unless:p.*.id,0|required|string|email',
            'p.*.mobile'       => 'exclude_unless:p.*.id,0|required|numeric|unique:customers',
            'p.*.country'      => 'exclude_unless:p.*.id,0|required|in:' . $countries,
            'p.*.pob'          => 'exclude_unless:p.*.id,0|required|in:' . $countries,
            'p.*.dob'          => 'exclude_unless:p.*.id,0|required|date',
            'p.*.region'          => 'exclude_unless:p.*.id,0|required|string',
            'p.*.city'          => 'exclude_unless:p.*.id,0|required|string',
            'p.*.address'          => 'exclude_unless:p.*.id,0|required|string',
            'p.*.employment_status' => 'exclude_unless:p.*.id,0|required|in:1,2,3,4',
            'p.*.employment_detail' => 'exclude_unless:p.*.id,0|required|string',

            'p.*.nok1.name'     => 'required|string',
            'p.*.nok1.relation' => 'required|in:1,2,3,4,5,6,7',
            'p.*.nok1.mobile'   => 'required|numeric',

            'p.*.nok2.name'     => 'required|string',
            'p.*.nok2.relation' => 'required|in:1,2,3,4,5,6,7',
            'p.*.nok2.mobile'   => 'required|numeric',

            'p.*.doc_type'      => 'exclude_unless:p.*.id,0|required|in:1,2,3,4,5,6',
            'p.*.doc_expiry'    => 'exclude_unless:p.*.id,0|required|date',


            //Account Validation
            'account_type'  => 'required|in:1,2',
            'withdraw_amount_limit' => 'required|numeric',
            'withdraw_freq_limit'   => 'required|numeric',

        ]);

        // $p1_mobile = ($this->getCountries(0))[$request->p1_country]->dial_code . $request->p1_mobile;

        // $exist = Customer::where('mobile', $p1_mobile)->orWhere('mobile', $p2_mobile)->orWhere('email', $p1_email)->orWhere('email', $p2_email)->first();

        // if ($exist) {
        //     $notify[] = ['error', 'The mobile number already exists'];
        //     return back()->withNotify($notify)->withInput();
        // }
    }

    private function validateCorporate($request) {
        $countries = $this->getCountries();
        $persons = $request->p;
        foreach($persons as &$person){
            if($person['id'] == 0){
                $person['mobile'] = $person['mobile_code'] . $person['mobile'];
            }
        }
        $request->merge(['p' => $persons]);
        $request->validate([

            //Organization Validation
            'name'                  => 'required|string',
            'organization_type'     => 'required|in:1,2,3,4,5,6',
            'establishment_date'    => 'required|date',
            'country'      => 'required|in:' . $countries,
            'region'          => 'required|string',
            'city'          => 'required|string',
            'mobile'       => 'required|numeric',
            'email'        => 'required|string|email',
            'doc_type'      => 'required|in:1,2,3,4,5,6,7',
            'doc_expiry'    => 'required|date',

            //Persons Validation
            'p.*.fullname'     => 'exclude_unless:p.*.id,0|required|string',
            'p.*.gender'       => 'exclude_unless:p.*.id,0|required|in:1,2',
            'p.*.marital'      => 'exclude_unless:p.*.id,0|required|in:1,2,3,4',
            'p.*.email'        => 'exclude_unless:p.*.id,0|required|string|email',
            'p.*.mobile'       => 'exclude_unless:p.*.id,0|required|numeric|unique:customers',
            'p.*.country'      => 'exclude_unless:p.*.id,0|required|in:' . $countries,
            'p.*.pob'          => 'exclude_unless:p.*.id,0|required|in:' . $countries,
            'p.*.dob'          => 'exclude_unless:p.*.id,0|required|date',
            'p.*.region'          => 'exclude_unless:p.*.id,0|required|string',
            'p.*.city'          => 'exclude_unless:p.*.id,0|required|string',
            'p.*.address'          => 'exclude_unless:p.*.id,0|required|string',
            'p.*.employment_status' => 'exclude_unless:p.*.id,0|required|in:1,2,3,4',
            'p.*.employment_detail' => 'exclude_unless:p.*.id,0|required|string',

            'p.*.nok1.name'     => 'exclude_unless:organization_type,1|required|string',
            'p.*.nok1.relation' => 'exclude_unless:organization_type,1|required|in:1,2,3,4,5,6,7',
            'p.*.nok1.mobile'   => 'exclude_unless:organization_type,1|required|numeric',

            'p.*.nok2.name'     => 'exclude_unless:organization_type,1|required|string',
            'p.*.nok2.relation' => 'exclude_unless:organization_type,1|required|in:1,2,3,4,5,6,7',
            'p.*.nok2.mobile'   => 'exclude_unless:organization_type,1|required|numeric',

            'p.*.doc_type'      => 'exclude_unless:p.*.id,0|required|in:1,2,3,4,5,6',
            'p.*.doc_expiry'    => 'exclude_unless:p.*.id,0|required|date',


            //Account Validation
            'account_type'  => 'required|in:1,2',
            'withdraw_amount_limit' => 'required|numeric',
            'withdraw_freq_limit'   => 'required|numeric',

        ]);
    }

    private function getCountries($asString = true){
        $countryData  = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        $countries = (array) $countryData;
        if($asString) return implode(',', array_keys($countries));
        return $countries;
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

    private function checkCustomers($customers){
        for($customer = 0; $customer < count($customers); $customer++) {
            if(!isset($customers[$customer])){
                return false;
            }
        }
        return true;
    }

    public function updateIndividual(Request $request, $id) {
        $account = Account::findOrFail($id);
        $customer = $account->customer;

        if ($customer->customer_type == 0) {
            $this->validateIndividual($request);

            // Update customer details if needed
            if ($customer) {
                $customer->name = strtoupper($request->name);
                $customer->identifier_type = $request->identifier_type;
                if ($request->hasFile('identifier_link')) {
                    $customer->identifier_link = $this->upload('identifier_link', $request);
                }
                $customer->identifier_expiry_date = $request->identifier_expiry_date;

                $misc = json_decode(json_encode($customer->misc), true);
                if ($request->hasFile('image')) {
                    $misc['image'] = $this->upload('image', $request);
                }
                $misc['gender'] = $request->gender;
                $misc['nationality'] = $request->nationality;

                $customer->misc = json_decode(json_encode($misc));
                $customer->save();
            }

            // Update account details
            $account->account_type = $request->account_type;
            $account->cheque = $request->cheque == null ? 0 : 1;
            $account->ebank = $request->ebank == null ? 0 : 1;
            $account->wallet = $request->wallet == null ? 0 : 1;

            $accountMisc = json_decode(json_encode($account->misc), true);
            $accountMisc['daily_withdraw_amount_limit'] = $request->withdraw_amount_limit;
            $accountMisc['daily_withdraw_freq_limit'] = $request->withdraw_freq_limit;

            // Update NOK details
            $accountMisc['nok']['p1_nok']['nok1'] = [
                'name' => strtoupper($request->nok1_name),
                'relation' => $request->nok1_relation,
                'mobile' => $request->nok1_mobile
            ];
            $accountMisc['nok']['p1_nok']['nok2'] = [
                'name' => strtoupper($request->nok2_name),
                'relation' => $request->nok2_relation,
                'mobile' => $request->nok2_mobile
            ];

            // Update document scans if provided
            if ($request->hasFile('signature_scan')) {
                $accountMisc['documents']['signature_scan'] = $this->upload('signature_scan', $request);
            }
            if ($request->hasFile('application_scan')) {
                $accountMisc['documents']['application_scan'] = $this->upload('application_scan', $request);
            }

            $account->misc = json_decode(json_encode($accountMisc));
            $account->save();

            $notify[] = ['success', 'Account updated successfully'];
            return back()->withNotify($notify);
        }
    }


}

<?php

namespace App\Http\Controllers\BranchStaff;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Lib\FormProcessor;
use App\Models\AdminNotification;
use App\Models\Form;
use App\Models\User;
use App\Models\Customer;
use App\Models\Account;
use App\Rules\FileTypeValidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserController extends Controller {

    public function all() {
        $pageTitle  = 'All Accounts';
        $staff      = authStaff();
        $accounts   = User::query();
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
        
        $accounts   = $accounts->searchable(['username', 'email', 'firstname', 'lastname'])->with('branch:id,name', 'branchStaff:id,name')->latest()->paginate(getPaginate());

        return view('branch_staff.user.list', compact('pageTitle', 'accounts', 'staff', 'branches', 'branchId'));
    }
    
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
    
    public function find() {
        return $this->detail(request()->account_number);
    }

    public function detail($accountNumber) {
        $staff   = authStaff();
        $account = $accountNumber;
        $account    = Account::where('account_number', $account)->first();
        
        $accountType = ($account->AccountType->category == 0 ? "Personal" : "Corporate") . " " . $account->AccountType->name;
        
        $misc = json_decode($account->customer->misc);
        
        $countries = $this->getCountries(false);
        
        $misc->nationality = $countries[$misc->nationality]->country;
        
        $misc->account = json_decode($account->misc);
        
        if (!$account) {
            $notify[] = ['error', 'Account not found'];
            return back()->withNotify($notify)->withInput();
        }

        $pageTitle = 'Account Details';
        return view('branch_staff.user.detail', compact('pageTitle', 'account', 'staff', 'accountType', 'misc'));
    }
    
    public function openIndividual($account = null) {

        $countries  = json_decode(file_get_contents(resource_path('views/partials/country.json')));

        if ($account) {
            $account = User::where('account_number', $account)->firstOrFail();
            $action = route('staff.account.update', @$account->id);
            $pageTitle = 'Edit Account Details';
        } else {
            $pageTitle = 'Open New Account';
            $action = route('staff.account.open.individual.save');
        }

        return view('branch_staff.user.form_individual', compact('pageTitle', 'account', 'countries', 'action'));
    }
    
    public function openJoint($account = null) {

        $countries  = json_decode(file_get_contents(resource_path('views/partials/country.json')));

        if ($account) {
            $account = User::where('account_number', $account)->firstOrFail();
            $action = route('staff.account.update', @$account->id);
            $pageTitle = 'Edit Account Details';
        } else {
            $pageTitle = 'Open New Account';
            $action = route('staff.account.open.joint.save');
        }

        return view('branch_staff.user.form_joint', compact('pageTitle', 'account', 'countries', 'action'));
    }
    
    public function openCorporate($account = null) {

        $countries  = json_decode(file_get_contents(resource_path('views/partials/country.json')));

        if ($account) {
            $account = User::where('account_number', $account)->firstOrFail();
            $action = route('staff.account.update', @$account->id);
            $pageTitle = 'Edit Account Details';
        } else {
            $pageTitle = 'Open New Account';
            $action = route('staff.account.open.corporate.save');
        }

        return view('branch_staff.user.form_corporate', compact('pageTitle', 'account', 'countries', 'action'));
    }
    
    public function saveIndividual(Request $request){
        $this->validateIndividual($request);
        
        $countryData  = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        $countries = (array) $countryData;
        
        $dial_code = $countries[$request->country]->dial_code;
        
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
        
        $customer_misc_info = [
            'image' => $this->upload('image', $request),
            'gender' => $request->gender,
            'nationality' => $request->country,
            'pob' => $request->pob,
            'dob' => $request->dob,
            'marital' => $request->marital,
            'employment_status' => $request->employment_status,
            'employment_detail' => $request->employment_detail,
            'nok1' => [
                'name' => $request->nok1_name,
                'relation' => $request->nok1_relation,
                'mobile' => $request->nok1_mobile
            ],
            'nok2' => [
                'name' => $request->nok2_name,
                'relation' => $request->nok2_relation,
                'mobile' => $request->nok2_mobile
            ],
            'documents' => [
                'doc_scan' => $this->upload('doc_scan', $request),
                'application_scan' => $this->upload('application_scan', $request)
            ]
        ];
        $customer->name                     = $request->fullname;
        $customer->identifier_type          = $request->doc_type;
        $customer->identifier_link          = $this->upload('doc_scan', $request);
        $customer->identifier_expiry_date   = $request->doc_expiry;
        $customer->mobile                   = $dial_code.$request->mobile;
        $customer->email                    = $request->email;
        $customer->region                   = $request->region;
        $customer->city                     = $request->city;
        $customer->address                  = $request->address;
        $customer->misc                     = json_encode($customer_misc_info);
        // $customer                   = $this->saveUser($request, $user);

        // $adminNotification            = new AdminNotification();

        // $adminNotification->user_id   = $user->id;
        // $adminNotification->title     = 'New account opened from ' . $branch->name;
        // $adminNotification->click_url = urlPath('admin.users.detail', $user->id);
        // $adminNotification->save();
        
        $customer->save();
        
        //die("{$request->wallet}, {$request->cheque}, {$request->ebank}");
        
        if($customer->id > 0){
            // die($customer->id);
            $account = new Account();
            
            $account->branch_id = $branch->id;
            $account->branch_staff_id = $staff->id;
            $account->customer_id = $customer->id;
            
            $account->account_number = generateAccountNumber();
            $account->account_type = $request->account_type;
            $account->account_balance = 0;
            $account->cheque = $request->cheque == null ? 0 : 1;
            $account->ebank = $request->ebank == null ? 0 : 1;
            $account->wallet = $request->wallet == null ? 0 : 1;
            $account->document = '';
            $account->misc = json_encode([
                'daily_withdraw_amount_limit' => $request->withdraw_amount_limit,
                'daily_withdraw_freq_limit' => $request->withdraw_freq_limit,
                'documents' => [
                'signature_scan' => $this->upload('signature_scan', $request)
            ]
            ]);
            
            $account->save();
            
            // notify($customer, 'ACCOUNT_OPENED', [
            //     'email'    => $customer->email
            // ]);
    
            $notify[] = ['success', 'Account opened successfully'];
            return back()->withNotify($notify);
        }
    }
    
    public function saveJoint(Request $request){
        $this->validateJoint($request);
        
        $countryData  = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        $countries = (array) $countryData;
        
        $p1_dial_code = $countries[$request->p1_country]->dial_code;
        $p2_dial_code = $countries[$request->p2_country]->dial_code;
        
        $general = gs();
        $password              = getTrx(8);
        
        $staff                  = authStaff();
        $branch                 = $staff->branch();
        
        $customer1             = new Customer();
        $customer2             = new Customer();
        
        $customer1_misc_info = [
            'image' => $this->upload('p1_image', $request),
            'gender' => $request->p1_gender,
            'nationality' => $request->p1_country,
            'pob' => $request->p1_pob,
            'dob' => $request->p1_dob,
            'marital' => $request->p1_marital,
            'employment_status' => $request->p1_employment_status,
            'employment_detail' => $request->p1_employment_detail,
            'nok1' => [
                'name' => $request->p1_nok1_name,
                'relation' => $request->p1_nok1_relation,
                'mobile' => $request->p1_nok1_mobile
            ],
            'nok2' => [
                'name' => $request->p1_nok2_name,
                'relation' => $request->p1_nok2_relation,
                'mobile' => $request->p1_nok2_mobile
            ],
            'documents' => [
                'signature_scan' => $this->upload('p1_signature_scan', $request)
            ]
        ];
        
        $customer2_misc_info = [
            'image' => $this->upload('p2_image', $request),
            'gender' => $request->p2_gender,
            'nationality' => $request->p2_country,
            'pob' => $request->p2_pob,
            'dob' => $request->p2_dob,
            'marital' => $request->p2_marital,
            'employment_status' => $request->p2_employment_status,
            'employment_detail' => $request->p2_employment_detail,
            'nok1' => [
                'name' => $request->p2_nok1_name,
                'relation' => $request->p2_nok1_relation,
                'mobile' => $request->p2_nok1_mobile
            ],
            'nok2' => [
                'name' => $request->p2_nok2_name,
                'relation' => $request->p2_nok2_relation,
                'mobile' => $request->p2_nok2_mobile
            ],
            'documents' => [
                'signature_scan' => $this->upload('p2_signature_scan', $request)
            ]
        ];
        
        $customer1->name                     = $request->p1_fullname;
        $customer1->identifier_type          = $request->p1_doc_type;
        $customer1->identifier_link          = $this->upload('p1_doc_scan', $request);
        $customer1->identifier_expiry_date   = $request->p1_doc_expiry;
        $customer1->mobile                   = $p1_dial_code.$request->p1_mobile;
        $customer1->email                    = $request->p1_email;
        $customer1->region                   = $request->p1_region;
        $customer1->city                     = $request->p1_city;
        $customer1->address                  = $request->p1_address;
        $customer1->misc                     = json_encode($customer1_misc_info);
        // $customer                   = $this->saveUser($request, $user);

        // $adminNotification            = new AdminNotification();

        // $adminNotification->user_id   = $user->id;
        // $adminNotification->title     = 'New account opened from ' . $branch->name;
        // $adminNotification->click_url = urlPath('admin.users.detail', $user->id);
        // $adminNotification->save();
        
        $customer1->save();
        
        $customer2->name                     = $request->p2_fullname;
        $customer2->identifier_type          = $request->p2_doc_type;
        $customer2->identifier_link          = $this->upload('p2_doc_scan', $request);
        $customer2->identifier_expiry_date   = $request->p2_doc_expiry;
        $customer2->mobile                   = $p2_dial_code.$request->p2_mobile;
        $customer2->email                    = $request->p2_email;
        $customer2->region                   = $request->p2_region;
        $customer2->city                     = $request->p2_city;
        $customer2->address                  = $request->p2_address;
        $customer2->misc                     = json_encode($customer2_misc_info);
        // $customer                   = $this->saveUser($request, $user);

        // $adminNotification            = new AdminNotification();

        // $adminNotification->user_id   = $user->id;
        // $adminNotification->title     = 'New account opened from ' . $branch->name;
        // $adminNotification->click_url = urlPath('admin.users.detail', $user->id);
        // $adminNotification->save();
        
        $customer2->save();
        
        //die("{$request->wallet}, {$request->cheque}, {$request->ebank}");
        
        if($customer1->id > 0 && $customer2->id){
            // die($customer->id);
            $account = new Account();
            
            $account->branch_id = $branch->id;
            $account->branch_staff_id = $staff->id;
            $account->customer_id = $customer1->id;
            $account->customer2_id = $customer2->id;
            
            $account->account_number = generateAccountNumber();
            $account->account_type = $request->account_type;
            $account->account_balance = 0;
            $account->cheque = $request->cheque == null ? 0 : 1;
            $account->ebank = $request->ebank == null ? 0 : 1;
            $account->wallet = $request->wallet == null ? 0 : 1;
            $account->document = $this->upload('application_scan', $request);
            
            $account->save();
            
            // notify($customer, 'ACCOUNT_OPENED', [
            //     'email'    => $customer->email
            // ]);
    
            $notify[] = ['success', 'Account opened successfully'];
            return back()->withNotify($notify);
        }
    }
    
    public function edit($account){
        $countries  = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        
        $account = User::where('account_number', $account)->firstOrFail();
        $action = route('staff.account.update', @$account->id);
        $pageTitle = 'Edit Account Details';
        return view('branch_staff.user.form', compact('pageTitle', 'countries', 'account', 'action'));
    }

    public function store(Request $request) {
        $this->validation($request);
        $form              = Form::where('act', 'kyc')->first();
        $formData          = $form->form_data;
        $formProcessor     = new FormProcessor();
        $kycValidationRule = $formProcessor->valueValidation($formData);
        $request->validate($kycValidationRule);

        $general = gs();
        $password              = getTrx(8);
        $user                  = new User();

        if ($general->modules->referral_system && $request->referrer) {

            $referrer = User::where('account_number', $request->referrer)->first();

            if (!$referrer) {
                $notify[] = ['error', 'Referrer account not found'];
                return back()->withNotify($notify)->withInput();
            }

            $user->ref_by = $referrer->id;
            $user->referral_commission_count = $general->referral_commission_count;
        }

        $user->password         = Hash::make($password);
        $user->kyc_data         = $formProcessor->processFormData($request, $formData);
        $staff                  = authStaff();
        $branch                 = $staff->branch();
        $user->branch_id        = $branch->id;
        $user->branch_staff_id  = $staff->id;
        $user->account_number   = generateAccountNumber();
        $user->kv               = $general->kv ? Status::NO : Status::YES;
        $user->ev               = $general->ev ? Status::NO : Status::YES;
        $user->sv               = $general->sv ? Status::NO : Status::YES;
        $user->status           = Status::USER_ACTIVE;
        $user->ts               = Status::DISABLE;
        $user->tv               = Status::VERIFIED;
        $user->kv               = 1;
        $user->profile_complete = 1;

        $user                   = $this->saveUser($request, $user);

        $adminNotification            = new AdminNotification();

        $adminNotification->user_id   = $user->id;
        $adminNotification->title     = 'New account opened from ' . $branch->name;
        $adminNotification->click_url = urlPath('admin.users.detail', $user->id);
        $adminNotification->save();

        notify($user, 'ACCOUNT_OPENED', [
            'email'    => $user->email,
            'username' => $user->username,
            'password' => $password,
        ]);

        $notify[] = ['success', 'Account opened successfully'];
        return back()->withNotify($notify);
    }


    public function update(Request $request, $id) {
        $user      = User::where('branch_staff_id', authStaff()->id)->findOrFail($id);
        $oldEmail  = $user->email;
        $oldMobile = $user->mobile;
        $this->validation($request, $id);

        $user      = $this->saveUser($request, $user);

        if ($oldEmail != $user->email) {
            $user->ev = 0;
            $user->save();
        }

        if ($oldMobile != $user->mobile) {
            $user->sv = 0;
            $user->save();
        }

        $notify[] = ['success', 'Account information updated successfully'];
        return back()->withNotify($notify);
    }

    protected function saveUser($request, $user) {
        $countryData  = collect(json_decode(file_get_contents(resource_path('views/partials/country.json'))));
        $country      = $countryData[$request->country];

        $user->firstname       = $request->firstname;
        $user->lastname        = $request->lastname;
        $user->email           = strtolower(trim($request->email));
        $user->username        = $user->account_number;
        $user->country_code    = $request->country;
        $user->mobile          = $country->dial_code . $request->mobile;

        if ($request->hasFile('image')) {
            try {
                $user->image = fileUploader($request->image, getFilePath('userProfile'), getFileSize('userProfile'));
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Couldn\'t upload your image'];
                return back()->withNotify($notify);
            }
        }

        $user->address = [
            'address' => $request->address ?? '',
            'state'   => $request->state ?? '',
            'zip'     => $request->zip ?? '',
            'country' => @$country->country,
            'city'    => $request->city ?? '',
        ];

        $user->save();
        return $user;
    }
    
    private function validateIndividual($request){
        $countries = $this->getCountries();
        $request->validate([
            
            'fullname'     => 'required|string',
            'gender'       => 'required|in:1,2',
            'marital'      => 'required|in:1,2,3,4',
            'email'        => 'required|string|email|unique:customers,email',
            'mobile'       => 'required|numeric|unique:customers,mobile',
            'country'      => 'required|in:' . $countries,
            'pob'          => 'required|in:' . $countries,
            'dob'          => 'required|date',
            'region'          => 'required|string',
            'city'          => 'required|string',
            'address'          => 'required|string',
            'employment_status' => 'required|in:1,2,3,4',
            'employment_detail' => 'required|string',
            
            
            'nok1_name'     => 'required|string',
            'nok1_relation' => 'required|in:1,2,3,4,5,6,7',
            'nok1_mobile'   => 'required|regex:/^([0-9]*)$/',
            
            'nok2_name'     => 'required|string',
            'nok2_relation' => 'required|in:1,2,3,4,5,6,7',
            'nok2_mobile'   => 'required|regex:/^([0-9]*)$/',
            
            
            'account_type'  => 'required|in:1,2',
            'withdraw_amount_limit' => 'required|string',
            'withdraw_freq_limit'   => 'required|string',
            
            'doc_type'      => 'required|in:1,2,3,4,5,6,7',
            'doc_expiry'    => 'required|date'
            
        ]);
        
        
        $exist = Customer::where('mobile', ($this->getCountries(0))[$request->country]->dial_code . $request->mobile)->orWhere('email', $request->email)->first();

        if ($exist) {
            $notify[] = ['error', 'The mobile number already exists'];
            return back()->withNotify($notify)->withInput();
        }
    }
    
    private function validateJoint($request){
        $countries = $this->getCountries();
        $request->validate([
            
            //Person 1 Validation
            'p1_fullname'     => 'required|string',
            'p1_gender'       => 'required|in:1,2',
            'p1_marital'      => 'required|in:1,2,3,4',
            'p1_email'        => 'required|string|email|unique:customers,email',
            'p1_mobile'       => 'required|numeric|unique:customers,mobile',
            'p1_country'      => 'required|in:' . $countries,
            'p1_pob'          => 'required|in:' . $countries,
            'p1_dob'          => 'required|date',
            'p1_region'          => 'required|string',
            'p1_city'          => 'required|string',
            'p1_address'          => 'required|string',
            'p1_employment_status' => 'required|in:1,2,3,4',
            'p1_employment_detail' => 'required|string',
            
            'p1_nok1_name'     => 'required|string',
            'p1_nok1_relation' => 'required|in:1,2,3,4,5,6,7',
            'p1_nok1_mobile'   => 'required|regex:/^([0-9]*)$/',
            
            'p1_nok2_name'     => 'required|string',
            'p1_nok2_relation' => 'required|in:1,2,3,4,5,6,7',
            'p1_nok2_mobile'   => 'required|regex:/^([0-9]*)$/',
            
            'p1_doc_type'      => 'required|in:1,2,3,4,5,6,7',
            'p1_doc_expiry'    => 'required|date',
            
            
            //Person 2 Validation
            'p2_fullname'     => 'required|string',
            'p2_gender'       => 'required|in:1,2',
            'p2_marital'      => 'required|in:1,2,3,4',
            'p2_email'        => 'required|string|email|unique:customers,email',
            'p2_mobile'       => 'required|numeric|unique:customers,mobile',
            'p2_country'      => 'required|in:' . $countries,
            'p2_pob'          => 'required|in:' . $countries,
            'p2_dob'          => 'required|date',
            'p2_region'          => 'required|string',
            'p2_city'          => 'required|string',
            'p2_address'          => 'required|string',
            'p2_employment_status' => 'required|in:1,2,3,4',
            'p2_employment_detail' => 'required|string',
            
            
            'p2_nok1_name'     => 'required|string',
            'p2_nok1_relation' => 'required|in:1,2,3,4,5,6,7',
            'p2_nok1_mobile'   => 'required|regex:/^([0-9]*)$/',
            
            'p2_nok2_name'     => 'required|string',
            'p2_nok2_relation' => 'required|in:1,2,3,4,5,6,7',
            'p2_nok2_mobile'   => 'required|regex:/^([0-9]*)$/',
            
            'p2_doc_type'      => 'required|in:1,2,3,4,5,6,7',
            'p2_doc_expiry'    => 'required|date',
            
            //Account Validation
            'account_type'  => 'required|in:1,2',
            'withdraw_amount_limit' => 'required|string',
            'withdraw_freq_limit'   => 'required|string',
            
        ]);
        
        $p1_mobile = ($this->getCountries(0))[$request->p1_country]->dial_code . $request->p1_mobile;
        $p2_mobile = ($this->getCountries(0))[$request->p2_country]->dial_code . $request->p2_mobile;
        
        $p1_email = $request->p1_email;
        $p2_email = $request->p2_email;
        
        $exist = Customer::where('mobile', $p1_mobile)->orWhere('mobile', $p2_mobile)->orWhere('email', $p1_email)->orWhere('email', $p2_email)->first();

        if ($exist) {
            $notify[] = ['error', 'The mobile number already exists'];
            return back()->withNotify($notify)->withInput();
        }
    }
    
    private function getCountries($asString = true){
        $countryData  = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        $countries = (array) $countryData;
        if($asString) return implode(',', array_keys($countries));
        return $countries;
    }
    
    private function upload($input_name, $request){
        if ($request->hasFile($input_name)) {
            try {
                $file = fileUploader($request->file($input_name), getFilePath('userProfile'), getFileSize('userProfile'));
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Couldn\'t upload your file'];
                return back()->withNotify($notify);
            }
            return $file;
        }
    }
    
    private function validation($request, $id = 0) {
        $countryData  = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        $countryArray = (array) $countryData;
        $countries    = implode(',', array_keys($countryArray));
        $imgValidation = $id ? 'nullable' : 'required';

        $request->validate([
            'firstname'    => 'required|string',
            'lastname'     => 'required|string',
            'email'        => 'required|string|email|unique:users,email,' . $id,
            'mobile'       => 'required|regex:/^([0-9]*)$/',
            // 'username'     => 'required|min:6|unique:users,username,' . $id,
            'country'      => 'required|in:' . $countries,
            'image'        => [$imgValidation, new FileTypeValidate(['jpg', 'jpeg', 'png'])],
            'referrer'     => 'nullable|string'
        ]);

        // if (preg_match('/[^a-z0-9_]/', trim($request->username))) {
        //     $notify[] = ['Username can contain only small letters, numbers and underscore.'];
        //     $notify[] = ['No special character, space or capital letters in username.'];
        //     throw ValidationException::withMessages($notify);
        // }

        $exist = User::where('mobile', $request->mobile_code . $request->mobile)->where('id', $id)->first();

        if ($exist) {
            $notify[] = ['error', 'The mobile number already exists'];
            return back()->withNotify($notify)->withInput();
        }
    }
}



<?php

namespace App\Http\Controllers\BranchStaff;

use App\Constants\Status;
use App\Enums\HouseStatusEnum;

use App\Http\Controllers\Controller;
use App\Lib\FormProcessor;
use App\Models\COA;
use App\Models\Guarantor;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\Purchase;
use App\Models\Inventory;
use App\Models\Investment;
use App\Models\InvestmentInstallment;
use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Carbon;

class MurabahaController extends Controller {
    private $status = [
        ["label" => "Pending", "badge" => "warning"],
        ["label" => "Active", "badge" => "success"],
        ["label" => "Rejected", "badge" => "danger"],
    ];
    
    private $investmentDetails;
    
    public function applications() {
        $pageTitle = 'Applications';
        $staff      = authStaff();
        
        $applications = Application::query()->with('investment')->searchable(['customer_account'])->latest()->paginate(getPaginate());
        
        $status = $this->status;
        
        return view('branch_staff.murabaha.applications.index', compact('pageTitle', 'applications', 'status'));
    }
    
    public function guarantors() {
        $pageTitle = 'Guarantors';
        $staff      = authStaff();
        
        $guarantors = Guarantor::query()->searchable(['name', 'mobile', 'cellphone'])->latest()->paginate(getPaginate());
        $status = $this->status;
        
        return view('branch_staff.murabaha.guarantors.index', compact('pageTitle', 'guarantors', 'status'));
    }
    
    public function products() {
        $pageTitle = 'Products';
        $staff      = authStaff();
        
        $products = Product::query()->searchable(['name'])->with('account', 'stock')->latest()->paginate(getPaginate());
        $accounts = COA::get();
        
        return view('branch_staff.murabaha.products.index', compact('pageTitle', 'products', 'accounts'));
    }
    
    public function suppliers() {
        $pageTitle = 'Suppliers';
        $staff      = authStaff();
        
        $suppliers = Supplier::query()->searchable(['name', 'mobile'])->latest()->paginate(getPaginate());
        
        return view('branch_staff.murabaha.suppliers.index', compact('pageTitle', 'suppliers'));
    }
    
    public function purchases() {
        $pageTitle = 'Purchases';
        $staff      = authStaff();
        
        $searchString = Request()->search;
        $purchases = Purchase::whereHas('product', function ($query) use ($searchString){
            $query->where('name', 'like', '%'.$searchString.'%');
        })
        ->with(['product' => function($query) use ($searchString){
            $query->where('name', 'like', '%'.$searchString.'%');
        }, 'supplier'])->latest()->paginate(getPaginate());
        
        $suppliers = Supplier::get();
        $products = Product::get();
        
        return view('branch_staff.murabaha.purchases.index', compact('pageTitle', 'purchases', 'suppliers' ,'products'));
    }
    
    public function inventory() {
        $pageTitle = 'Inventory';
        $staff      = authStaff();
        
        $products = Purchase::query()->groupBy('product_id')->sum('quantity')->latest()->paginate(getPaginate());
        
        return view('branch_staff.murabaha.suppliers.index', compact('pageTitle', 'suppliers'));
    }
    
    public function purchaseOrders(Request $request) {
        $purchases = Purchase::query()->where('product_id', $request->product_id)->with('supplier')->orderBy('created_at', 'desc')->get();
        $result = [];
        foreach($purchases as $purchase) {
            $result[] = [
                'id'    =>  $purchase->id,
                'value' =>  $purchase->quantity . " at " . $purchase->purchase_date,
                'description'   =>  $purchase->description,
                'supplier'  =>  $purchase->supplier->name,
                'price' =>  $purchase->price,
                'unit_price'    =>  $purchase->unit_price,
                'quantity'  =>  $purchase->quantity,
                'discount'  =>  $purchase->discount,
                'tax'   =>  $purchase->tax,
                'expenses'  =>  $purchase->expenses
            ];
        }
        return json_encode(['data' => $result]);
    }
    
    public function detailApplication($id) {
        $pageTitle = 'Application Detail';
        $staff      = authStaff();
        
        $application = Application::query()->where('id', $id)->first();
        $status = $this->status;
        $countries = $this->getCountries(false);
        
        return view('branch_staff.murabaha.applications.detail', compact('pageTitle', 'application', 'staff', 'status', 'countries'));
    }
    
    public function detailGuarantor($id) {
        $pageTitle = 'Guarantor Detail';
        $staff      = authStaff();
        
        $guarantor = Guarantor::query()->where('id', $id)->first();
        $status = $this->status;
        $countries = $this->getCountries(false);
        
        return view('branch_staff.murabaha.guarantors.detail', compact('pageTitle', 'guarantor', 'staff', 'status', 'countries'));
    }
    
    public function installments($id) {
        $pageTitle = 'Investment Installments';
        $staff      = authStaff();
        
        $investment = Application::query()->with('investment.installments')->where('id', $id)->firstOrFail()->investment;
        
        return view('branch_staff.murabaha.applications.installments', compact('pageTitle', 'investment'));
    }
    
    public function newApplication() {
        $pageTitle = 'New Application';
        $staff      = authStaff();
        
        $countries  = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        $action = route('staff.murabaha.save.application');
        
        return view('branch_staff.murabaha.applications.form', compact('pageTitle', 'action', 'countries'));
    }
    
    public function newGuarantor() {
        $pageTitle = 'New Guarantor';
        $staff      = authStaff();
        
        $countries  = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        $action = route('staff.murabaha.save.guarantor');
        
        return view('branch_staff.murabaha.guarantors.form', compact('pageTitle', 'action', 'countries'));
    }
    
    public function newInvestment($application_id){
        $pageTitle = 'Add Investment';
        $staff      = authStaff();
        
        $products = Product::query()->with('account', 'stock')->orderBy('created_at', 'desc')->get();
        $action = route('staff.murabaha.save.investment');
        
        return view('branch_staff.murabaha.applications.investment_form', compact('pageTitle', 'action', 'products', 'application_id'));
    }
    
    public function saveApplication(Request $request) {
        $this->validationApplication($request);
        
        $person = $request->person;
        $person['image'] = $this->upload('person._image', $request);
        
        $business = $request->business;
        $business["partners"] = $business["partners"] ?? [];
        
        $request->merge([
            'person' => $person,
            'business' => $business
        ]);
        
        $staff                  = authStaff();
        $branch                 = $staff->branch();
        $application            = new application;
        
        $application->branch_id       = $branch->id;
        $application->branch_staff_id = $staff->id;
        $application->guarantor_id  =   $request->guarantor['id'];
        
        $application->account_number   =   $request->customer_account;
        $application->investment_reference =   '';
        
        $application->personal_info =  $request->person;
        $application->business_info =   $request->business;
        $application->obligations_info  =   $request->obligations ?? [];
        $application->guarantor_info    =   $request->guarantor;
        $application->misc  =   [
            'documents' => [
                'application_scan' => $this->upload('documents.application_scan', $request),
                'guarantor_scan' => $this->upload('documents.guarantor_scan', $request)
            ]
        ];
        
        $application->save();
        
        $notify[] = ['success', 'Application saved successfully'];
        return back()->withNotify($notify)->withInput();
    }
    
    public function saveGuarantor(Request $request) {
        $this->validationGuarantor($request);
        
        $staff                  = authStaff();
        $branch                 = $staff->branch();
        $guarantor              = new Guarantor();
        
        $guarantor->branch_id       = $branch->id;
        $guarantor->branch_staff_id = $staff->id;
        
        $guarantor->image   =   $this->upload('image', $request);
        $guarantor->name    =   $request->name;
        $guarantor->mobile  =   $request->_mobile;
        $guarantor->cellphone   =   $request->_cellphone;
        $guarantor->address    =   $request->address;
        $guarantor->pob    =   $request->pob;
        $guarantor->dob    =   $request->dob;
        $guarantor->gender    =   $request->gender;
        $guarantor->id_type    =   $request->id_type;
        $guarantor->id_number    =   $request->id_number;
        $guarantor->house_status    =   $request->house_status;
        $guarantor->civil_status    =   $request->civil_status;
        $guarantor->years_at_address    =   $request->years_at_address;
        $guarantor->dependants    =   $request->dependants;
        $guarantor->employment = $this->getEmployment($request);
        
        $guarantor->save();
        
        $notify[] = ['success', 'Guarantor saved successfully'];
        return back()->withNotify($notify)->withInput();
    }
    
    public function saveProduct(Request $request) {
        $this->validationProduct($request);
        
        $staff                  = authStaff();
        $branch                 = $staff->branch();
        
        if($request->id > 0){
            $product = Product::where('id', $request->id)->firstOrFail();
        }else{
            $product = new Product();
        }
        
        $product->branch_id       = $branch->id;
        $product->branch_staff_id = $staff->id;
        
        $product->name    =   $request->name;
        $product->product_account   =   $request->account;
        
        $product->save();
        
        $notify[] = ['success', 'Product ' . ($request->id > 0 ? 'updated' : 'created') . ' successfully'];
        return back()->withNotify($notify)->withInput();
    }
    
    public function saveSupplier(Request $request) {
        $this->validationSupplier($request);
        
        $staff                  = authStaff();
        $branch                 = $staff->branch();
        
        if($request->id > 0){
            $supplier = Supplier::where('id', $request->id)->firstOrFail();
        }else{
            $supplier = new Supplier();
        }
        
        $supplier->branch_id       = $branch->id;
        $supplier->branch_staff_id = $staff->id;
        
        $supplier->name    =   $request->name;
        $supplier->mobile   =   $request->mobile;
        $supplier->supplier_account = 'N/A';
        
        $supplier->save();
        
        $notify[] = ['success', 'Supplier ' . ($request->id > 0 ? 'updated' : 'created') . ' successfully'];
        return back()->withNotify($notify)->withInput();
    }
    
    public function savePurchase(Request $request) {
        $this->validationPurchase($request);
        
        $staff                  = authStaff();
        $branch                 = $staff->branch();
        
        if($request->id > 0){
            $purchase = Purchase::where('id', $request->id)->firstOrFail();
        }else{
            $purchase = new Purchase();
        }
        
        $unit_price = round($request->unit_price, 2);
        $tax = round($request->tax, 2);
        $discount = round($request->discount, 2);
        $expenses = round($request->expenses, 2);
        $quantity = $purchase->quantity ?? 0;
        
        $purchase->product_id  =  $request->product_id;
        $purchase->supplier_id =  $request->supplier_id;
        
        $purchase->price       =  round(($unit_price * $request->quantity) + ($tax + $expenses) - $discount);
        
        $purchase->unit_price  =  $unit_price;
        $purchase->quantity    =  $request->quantity;
        $purchase->discount    =  $discount;
        $purchase->tax         =  $tax;
        $purchase->expenses    =  $expenses;
        $purchase->purchase_date   =  $request->purchase_date;
        $purchase->delivery_date   =  $request->delivery_date;
        $purchase->description =  $request->description;
        
        $purchase->save();
        
        $inventory = Inventory::firstOrNew(['product_id' => $purchase->product_id]);
        
        $inventory->quantity += $request->quantity > $quantity ? ($request->quantity - $quantity) : - ($quantity - $request->quantity);
        
        $inventory->save();
        
        $notify[] = ['success', 'Purchase ' . ($request->id > 0 ? 'updated' : 'created') . ' successfully'];
        return back()->withNotify($notify)->withInput();
    }
    
    public function saveInvestment(Request $request) {
        $this->validationInvestment($request);
        
        $this->investmentHandler($request);
        $this->installmentsHandler();
        
        $investmentDetails = (object) $this->investmentDetails;
        
        $_ = DB::transaction(function() use ($request, $investmentDetails) {
            
            $purchaseOrder = Purchase::where('id', $request->purchase_order)->firstOrFail();
            $purchaseOrder->update(['status' => 0]);
    
            $investment = new Investment();
            $investment->product_id =   $request->product;
            $investment->service_id =   '';
            $investment->investment_reference   =   $investmentDetails->reference;
            $investment->total_principle_amount   =   $investmentDetails->total_principle_amount;
            $investment->total_profit_amount   =   $investmentDetails->total_profit_amount;
            $investment->total_investment_amount   =   $investmentDetails->total_investment_amount;
            $investment->profit_rate   =   $investmentDetails->profit_rate * 100;
            $investment->principle_per_installment   =   $investmentDetails->principle_per_installment;
            $investment->profit_per_installment   =   $investmentDetails->profit_per_installment;
            $investment->payment_per_installment   =   $investmentDetails->payment_per_installment;
            $investment->total_installments =   $investmentDetails->total_installments;
            $investment->first_installment   =   $investmentDetails->first_installment;
            $investment->save();
            
            $investmentApplication = Application::where('id', $request->application_id)->firstOrFail();
            $investmentApplication->account_number    =   $request->customer_account;
            $investmentApplication->investment_reference =   $investmentDetails->reference;
            $investmentApplication->save();
            
            $investment->installments()->createMany($investmentDetails->installments);
    
            return ['success' => 'Investment added successfully'];
        });
        return back()->withNotify($_);
    }
    
    private function investmentHandler(Request $request){
        $purchase = Purchase::query()->where('id', $request->purchase_order)->first();
        
        $principle = $purchase->price;
        $profit_rate = round(($request->profit_rate) / 100, 2);
        $installments = $request->installments;
        
        $profit = $principle * $profit_rate;
        $total_amount = $principle + $profit;
        
        $principle_per_installment = round(($principle / $installments), 2);
        $profit_per_installment = round(($principle_per_installment * $profit_rate), 2);
        $payment_per_installment = round($principle_per_installment + $profit_per_installment);
        
        $this->investmentDetails = [
            'reference' =>  getTrx(),
            'total_principle_amount'    =>  $principle,
            'total_profit_amount'    =>  $profit,
            'total_investment_amount'  =>  $total_amount,
            'profit_rate'   =>  $profit_rate,
            'principle_per_installment' =>  $principle_per_installment,
            'profit_per_installment'    =>  $profit_per_installment,
            'payment_per_installment'   =>  $payment_per_installment,
            'total_installments'    =>  $installments,
            'first_installment'   => Carbon::parse($request->first_installment)->toDateString(),
        ];
        
    }
    
    private function installmentsHandler() {
        $installments = $this->investmentDetails['total_installments'];
        $installmentDate = Carbon::parse($this->investmentDetails['first_installment']);
        $installmentsList = [];
        for($i = 1; $i <= $installments; $i++) {
            $installmentsList[] = [
                'investment_id' =>  1,
                'installment_date'  =>  $installmentDate->toDateString()
            ];
            $installmentDate = $installmentDate->addMonthNoOverflow();
        }
        $this->investmentDetails['installments'] = $installmentsList;
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
    
    private function getEmployment(Request $request) {
        $employment = [];
        if($request->employment_status == 1){
            $request->work = (object) $request->work;
            $employment =   [
                'status'   =>  $request->employment_status,
                'superior_name'    =>  $request->work->superior_name,
                'organization_name'    =>  $request->work->organization_name,
                'address'  => $request->work->address,
                'mobile'   =>  $request->work->mobile,
                'nature'   =>  $request->work->nature,
                'title'    =>  $request->work->title,
                'years'    =>  $request->work->years,
                'monthly_salary'   =>    $request->work->monthly_salary,
            ];
        }else{
            $request->_self = (object) $request->_self;
            $employment =   [
                'status'   =>  $request->employment_status,
                'name' =>  $request->_self->name,
                'address'  =>  $request->_self->address,
                'nature'   =>  $request->_self->nature,
                'mobile'   =>  $request->_self->mobile,
                'years'    =>  $request->_self->years,
                'monthly_sales'    =>  $request->_self->monthly_sales,
                'partners'  =>  $request->partners,
            ];
        }
        
        return $employment;
    }
    
    public function approveApplication($id) {
        $count = Application::where("id", $id)->update(["status" => 1]);
        if($count){
            $notify[] = ['success', 'Application approved successfully'];
        }else{
            $notify[] = ['error', 'Operation failed'];
        }
        
        return back()->withNotify($notify);
    }
    
    public function rejectApplication($id) {
        $count = Application::where("id", $id)->update(["status" => 2]);
        if($count){
            $notify[] = ['success', 'Application rejected successfully'];
        }else{
            $notify[] = ['error', 'Operation failed'];
        }
        
        return back()->withNotify($notify);
    }
    
    public function approveGuarantor($id) {
        $count = Guarantor::where("id", $id)->update(["status" => 1]);
        if($count){
            $notify[] = ['success', 'Guarantor approved successfully'];
        }else{
            $notify[] = ['error', 'Operation failed'];
        }
        
        return back()->withNotify($notify);
    }
    
    public function rejectGuarantor($id) {
        $count = Guarantor::where("id", $id)->update(["status" => 2]);
        if($count){
            $notify[] = ['success', 'Guarantor rejected successfully'];
        }else{
            $notify[] = ['error', 'Operation failed'];
        }
        
        return back()->withNotify($notify);
    }
    
    private function validationApplication(Request $request) {
        $countries = $this->getCountries();
        
        $person = $request->person;
        
        $person['mobile'] = $person['mobile_code'] . $person['_mobile'];
        $person['cellphone'] = $person['cell_code'] . $person['cell'];
        $request->merge(['person' => $person]);
        
        $request->validate([
            'customer_account' => 'required|string|exists:accounts,account_number',
            'person.name'  =>  'required|string',
            'person.gender'    =>  'required|numeric|in:1,2',
            'person.pob'  =>  'required|string|in:' . $countries,
            'person.dob'   => 'required|date',
            'person.mobile'    =>  'required|numeric',
            'person.cellphone' =>  'numeric',
            'person.id_type'   =>  'required|digits_between:1,6',
            'person.id_number' =>  'required|string',
            'person.address'   =>  'required|string',
            'person.years_at_address'  => 'required|numeric',
            'person.civil_status'  => 'required|digits_between:1,4',
            'person.mother_name'   =>  'required|string',
            'person.dependants'    =>  'required|numeric',
            'person.house_status'  =>  'required|digits_between:1,3',
            'person.occupation'    =>  'required|string',
            'person.education_level' =>  'required|digits_between:1,8',
            
            'business.name'     =>  'required|string',
            'business.type'     =>  'required|digits_between:1,8',
            'business.mobile'   => 'required|numeric',
            'business.address' =>  'required|string',
            'business.employees'  =>  'required|numeric',
            'business.years'   =>  'required|numeric',
            'business.monthly_sales' =>  'required|numeric',
            'business.caregiver'    =>  'required|string',
            
            'business.partners.*.name'    =>  'string',
            'business.partners.*.stake'   =>  'numeric',
            
            'obligations.financiers.*.name'  =>  'string',
            'obligations.financiers.*.stake'    =>  'numeric',
            
            'obligations.reference_financier.name'  =>  'string',
            'obligations.reference_financier.amount'    =>  'numeric',
            
            'guarantor.id'          =>  'required|exists:guarantors,id',
            'guarantor.relation'    =>  'required|numeric|in:1,2',
            'guarantor.years_of_relation'   =>  'required|numeric',
        ]);
    }
    
    private function validationGuarantor(Request $request) {
        $countries = $this->getCountries();
        $request->merge([
            'mobile' => $request->mobile_code . $request->_mobile,
            '_cellphone' => $request->cell_code . $request->cell
        ]);
        $request->validate([
            'name'  =>  'required|string',
            'gender'    =>  'required|numeric|in:1,2',
            'pob'  =>  'required|string|in:' . $countries,
            'dob'   => 'required|date',
            'mobile'    =>  'required|numeric|unique:guarantors',
            'cellphone' =>  'numeric|unique:guarantors',
            'id_type'   =>  'required|digits_between:1,6',
            'id_number' =>  'required|string',
            'address'   =>  'required|string',
            'years_at_address'  => 'required|numeric',
            'civil_status'  =>  'required|digits_between:1,4',
            'dependants'    =>  'required|numeric',
            'house_status'  =>  'required|digits_between:1,3',
            'employment_status' =>  'required|numeric|in:1,2',
            
            '_self.name' =>  'exclude_unless:employment_status,2|required|string',
            '_self.address' =>  'exclude_unless:employment_status,2|required|string',
            '_self.nature' =>  'exclude_unless:employment_status,2|required|string',
            '_self.mobile' =>  'exclude_unless:employment_status,2|required|numeric',
            '_self.years' =>  'exclude_unless:employment_status,2|required|numeric',
            '_self.monthly_sales' =>  'exclude_unless:employment_status,2|required|numeric',
            
            'partners.*.name'    =>  'exclude_unless:employment_status,2|required|string',
            'partners.*.stake'   =>  'exclude_unless:employment_status,2|required|numeric',
            
            'work.superior_name' =>  'exclude_unless:employment_status,1|required|string',
            'work.organization_name' =>  'exclude_unless:employment_status,1|required|string',
            'work.address' =>  'exclude_unless:employment_status,1|required|string',
            'work.mobile' =>  'exclude_unless:employment_status,1|required|numeric',
            'work.nature' =>  'exclude_unless:employment_status,1|required|string',
            'work.title' =>  'exclude_unless:employment_status,1|required|string',
            'work.years' =>  'exclude_unless:employment_status,1|required|numeric',
            'work.monthly_salary' =>  'exclude_unless:employment_status,1|required|numeric',
        ]);
        $exists = Guarantor::where('mobile', $request->_mobile)
                    ->orWhere('cellphone', $request->_mobile)
                    ->orWhere('cellphone', $request->_cellphone)
                    ->orWhere('mobile', $request->_cellphone)
                    ->first();
        if($exists){
            if ($exist) {
                $notify[] = ['error', 'The mobile number already exists'];
                return back()->withNotify($notify)->withInput();
            }
        }
    }
    
    private function validationProduct(Request $request) {
        $request->validate([
            'name'     => 'required|string',
            'account'       => 'required|exists:chart_of_accounts,id',
        ]);
    }
    
    private function validationSupplier(Request $request) {
        $request->validate([
            'name'     => 'required|string',
            'mobile'       => 'required|numeric',
        ]);
    }
    
    private function validationPurchase(Request $request) {
        $request->merge([
            'discount' => $request->discount ?? 0,
            'tax'   =>  $request->tax ?? 0,
            'expenses' => $request->expenses ?? 0,
            'description'   =>  $request->description ?? 'N/A',
        ]);
        
        $request->validate([
            'product_id'     => 'required|numeric|exists:products,id',
            'supplier_id'       => 'required|numeric|exists:suppliers,id',
            'unit_price'  =>  'required|numeric|gt:0',
            'quantity'  =>  'required|numeric|gt:0',
            'discount'  =>  'numeric',
            'tax'  =>  'numeric',
            'expenses'  =>  'numeric',
            'purchase_date'  =>  'required|date',
            'delivery_date'  =>  'required|date',
            'description'  =>  'string',
        ]);
    }
    
    private function validationInvestment(Request $request) {
        $request->validate([
            'customer_account'  =>  'required|string|exists:accounts,account_number',
            'application_id' =>  'required|string|exists:investment_applications,id',
            'product'    =>  'required|integer|exists:products,id',
            'purchase_order'    =>  'required|string|exists:purchases,id',
            'profit_rate'   =>  'required|numeric|gt:0|digits_between:0,100',
            'installments'  =>  'required|integer|gt:0',
            'first_installment' =>  'required|date',
        ]);
    }
}
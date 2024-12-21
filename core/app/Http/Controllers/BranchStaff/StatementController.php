<?php

namespace App\Http\Controllers\BranchStaff;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Lib\FormProcessor;
use App\Models\AdminNotification;
use App\Models\Form;
use App\Models\User;
use App\Rules\FileTypeValidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class StatementController extends Controller {

    public function statement(){
        $pageTitle = 'Account Statement';
        $staff     = authStaff();
        return view('branch_staff.statement', compact('pageTitle', 'staff'));
    }
    
    public function generateStatement($accountNumber){
        $staff   = authStaff();
        $account = $accountNumber;
        $user    = User::where('account_number', $account)->first();

        if (!$user) {
            $notify[] = ['error', 'Account not found'];
            return back()->withNotify($notify)->withInput();
        }
        $pageTitle = 'Account Details';

    }
    
}
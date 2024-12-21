<?php

use Illuminate\Support\Facades\Route;

Route::namespace('Auth')->controller('LoginController')->group(function () {
    Route::get('/', 'showLoginForm')->name('login');
    Route::post('/', 'login')->name('login');
    Route::get('logout', 'logout')->name('logout');

    // Admin Password Reset
    Route::controller('ForgotPasswordController')->group(function () {
        Route::get('password/reset', 'showLinkRequestForm')->name('password.reset');
        Route::post('password/reset', 'sendResetCodeEmail');
        Route::get('password/code-verify', 'codeVerify')->name('password.code.verify');
        Route::post('password/verify-code', 'verifyCode')->name('password.verify.code');
    });

    Route::controller('ResetPasswordController')->group(function () {
        Route::get('password/reset/{token}', 'showResetForm')->name('password.reset.form');
        Route::post('password/reset/change', 'reset')->name('password.change');
    });
});


Route::get('banned-account', 'BranchStaffController@bannedAccount')->name('banned');

Route::middleware('branch.staff')->group(function () {
    Route::controller('BranchStaffController')->group(function () {
        Route::get('set-branch/{id}', 'setBranch')->name('branch.set');
        Route::get('dashboard', 'dashboard')->name('dashboard');
        Route::get('profile', 'profile')->name('profile');
        Route::get('staff-profile/{id}', 'staffProfile')->name('profile.other');
        Route::post('profile', 'profileUpdate')->name('profile.update');
        Route::get('password', 'password')->name('password');
        Route::post('password', 'passwordUpdate')->name('password.update');
        Route::get('download-attachments/{file_hash}', 'downloadAttachment')->name('download.attachment');
    });
    
    Route::get('statement', 'StatementController@statement')->name('statement');
    Route::get('statement/{account}', 'StatementController@generateStatement')->name('statement.generate');

    Route::middleware('checkAccountOfficer')->group(function () {
        Route::post('deposit/{account}', 'DepositController@save')->name('deposit.save');
        Route::post('withdraw/{account}', 'WithdrawController@save')->name('withdraw.save');
        
        Route::controller('CustomerController')->name('customer.')->prefix('customer')->group(function () {
            Route::post('find', 'find')->name('find');
            Route::get('all', 'customersAll')->name('customers.all');
            Route::get('detail/{customer}', 'detail')->name('detail');
            Route::get('edit/{customer}', 'edit')->name('edit');
            Route::get('approve/{customer}', 'approve')->name('approve');
            Route::get('reject/{customer}', 'reject')->name('reject');
        });
        
        Route::controller('AccountController')->name('account.')->prefix('account')->group(function () {
            Route::get('accounts', 'all')->name('all');
            
            Route::get('accounts/all', 'accountsAll')->name('accounts.all');
            Route::post('find', 'findAccount')->name('find');
            
            Route::get('open/individual', 'openIndividual')->name('open.individual')->middleware('checkModule:branch_create_user');
            Route::get('open/joint', 'openJoint')->name('open.joint')->middleware('checkModule:branch_create_user');
            Route::get('open/corporate', 'openCorporate')->name('open.corporate')->middleware('checkModule:branch_create_user');
            
            Route::get('accounts/individual', 'accountsIndividual')->name('accounts.individual');
            Route::get('accounts/joint', 'accountsJoint')->name('accounts.joint');
            Route::get('accounts/corporate', 'accountsCorporate')->name('accounts.corporate');
            
            Route::get('detail/individual/{account}', 'detailIndividual')->name('detail.individual');
            Route::get('detail/joint/{account}', 'detailJoint')->name('detail.joint');
            Route::get('detail/corporate/{account}', 'detailCorporate')->name('detail.corporate');
            
            Route::post('save/individual', 'saveIndividual')->name('open.individual.save')->middleware('checkModule:branch_create_user');
            Route::post('save/joint', 'saveJoint')->name('open.joint.save')->middleware('checkModule:branch_create_user');
            Route::post('save/corporate', 'saveCorporate')->name('open.corporate.save')->middleware('checkModule:branch_create_user');
            
            Route::get('approve/{account}', 'approve')->name('approve');
            Route::get('reject/{account}', 'reject')->name('reject');
            
            Route::get('edit/individual/{account}', 'editIndividual')->name('edit');
            Route::get('edit/corporate/{account}', 'editCorporate')->name('edit');
            
            Route::post('save', 'store')->name('save')->middleware('checkModule:branch_create_user');
            Route::post('update/{account}', 'update')->name('update');
        });
        
    });
    
    
    
    Route::controller('AccountController')->name('account.')->prefix('account')->group(function () {
            
        Route::get('accounts/all', 'accountsAll')->name('accounts.all');
        
        Route::get('accounts', 'all')->name('all');
        Route::get('detail/{account}', 'detail')->name('detail');
    });
    
    Route::controller('CustomerController')->name('customer.')->prefix('customer')->group(function() {
        Route::get('all', 'customersAll')->name('customers.all');
        Route::get('detail/{customer}', 'detail')->name('detail');
        Route::get('edit/{customer}', 'edit')->name('edit');
    });
    
    Route::controller('COAController')->name('coa.')->prefix('coa')->group(function() {
        Route::get('/', 'index')->name('index');
        Route::post('store', 'store')->name('store');
        Route::get('destroy/{id}', 'destroy')->name('destroy');
        Route::post('transfer', 'transferFunds')->name('transfer');
    });
    
    Route::controller('JournalController')->name('journal.')->prefix('journal')->group(function() {
        Route::get('/', 'index')->name('index');
        
        Route::post('deposit', 'deposit')->name('deposit');
        Route::post('withdraw', 'withdraw')->name('withdraw');
    });
    
    Route::controller('TellerController')->name('teller.')->prefix('tellers')->group(function() {
        Route::get('/', 'index')->name('index');
    });
    
    Route::controller('CustomerTransactionController')->name('transactions.')->prefix('transactions')->group(function() {
        Route::get('/', 'index')->name('index');
        Route::get('detail/{transaction}', 'detail')->name('detail');
        
        Route::post('deposit', 'deposit')->name('deposit');
        Route::post('withdraw', 'withdraw')->name('withdraw');
        
        Route::get('approve/{transaction}', 'approve')->name('approve');
        Route::get('reject/{transaction}', 'reject')->name('reject');
    });
    
    Route::controller('MurabahaController')->name('murabaha.')->prefix('murabaha')->group(function() {
        Route::get('applications', 'applications')->name('applications');
        Route::get('guarantors', 'guarantors')->name('guarantors');
        Route::get('products', 'products')->name('products');
        Route::get('suppliers', 'suppliers')->name('suppliers');
        Route::get('purchases', 'purchases')->name('purchases');
        
        Route::post('purchase-orders', 'purchaseOrders')->name('purchase.orders');
        
        Route::get('application/{id}', 'detailApplication')->name('application.detail');
        Route::get('application/{id}/add-investment', 'newInvestment')->name('application.investment');
        Route::get('application/{id}/installments', 'installments')->name('application.installments');
        Route::get('guarantor/{id}', 'detailGuarantor')->name('guarantor.detail');
        
        Route::get('new/application', 'newApplication')->name('new.application');
        Route::get('new/guarantor', 'newGuarantor')->name('new.guarantor');
        
        Route::post('save/application', 'saveApplication')->name('save.application');
        Route::post('save/guarantor', 'saveGuarantor')->name('save.guarantor');
        Route::post('save/product', 'saveProduct')->name('save.product');
        Route::post('save/supplier', 'saveSupplier')->name('save.supplier');
        Route::post('save/purchase', 'savePurchase')->name('save.purchase');
        Route::post('save/investment', 'saveInvestment')->name('save.investment');
        
        Route::get('application/approve/{id}', 'approveApplication')->name('approve.application');
        Route::get('application/reject/{id}', 'rejectApplication')->name('reject.application');
        
        Route::get('guarantor/approve/{id}', 'approveGuarantor')->name('approve.guarantor');
        Route::get('guarantor/reject/{id}', 'rejectGuarantor')->name('reject.guarantor');
    });
    
    Route::controller('ReportController')->name('report.')->prefix('reports')->group(function() {
        Route::get('/', 'index')->name('index');
    });
    
    Route::get('branches', 'BranchStaffController@branches')->name('branches');

    Route::get('deposits', 'DepositController@deposits')->name('deposits');
    Route::get('withdrawals', 'WithdrawController@withdrawals')->name('withdrawals');
    // Route::get('transactions', 'BranchStaffController@transactions')->name('transactions');
});

<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Status;
use App\Models\Loan;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Installment;

class LoanController extends Controller {
    public function index() {
        $this->pageTitle = 'All Financing';
        return $this->loanData();
    }

    public function runningLoans() {
        $this->pageTitle = 'Running Financing';
        return $this->loanData('running');
    }

    public function pendingLoans() {
        $this->pageTitle = 'Pending Financing';
        return $this->loanData('pending');
    }

    public function paidLoans() {
        $this->pageTitle = 'Paid Financing';
        return $this->loanData('paid');
    }

    public function rejectedLoans() {
        $this->pageTitle = 'Rejected Financing';
        return $this->loanData("rejected");
    }

    public function dueInstallment() {
        $this->pageTitle = 'Due Installment Financing';
        return $this->loanData("due");
    }

    public function details($id) {
        $loan         = Loan::where('id', $id)->with('plan', 'user')->firstOrFail();
        $pageTitle    = 'Financing Details';
        return view('admin.loan.details', compact('pageTitle', 'loan'));
    }

    public function approve($id) {
        $loan                        = Loan::with('user', 'plan')->findOrFail($id);
        $loan->status                = Status::LOAN_RUNNING;
        $loan->approved_at            = now();
        $loan->save();
        Installment::saveInstallments($loan, now()->addDays($loan->installment_interval));

        $user = $loan->user;
        $user->balance += getAmount($loan->amount);
        $user->save();

        $transaction               = new Transaction();
        $transaction->user_id      = $user->id;
        $transaction->amount       = $loan->amount;
        $transaction->post_balance = $user->balance;
        $transaction->charge       = 0;
        $transaction->trx_type     = '+';
        $transaction->details      = 'Financing taken';
        $transaction->trx          = getTrx();
        $transaction->remark       = 'loan_taken';
        $transaction->save();

        $shortCodes = $loan->shortCodes();
        $shortCodes['next_installment_date'] = now()->addDays($loan->installment_interval);

        notify($user, "LOAN_APPROVE", $loan->shortCodes());

        $notify[] = ['success', 'Financing approved successfully'];
        return back()->withNotify($notify);
    }

    public function reject(Request $request, $id) {

        $request->validate([
            'reason' => 'required|string'
        ]);

        $loan                 = Loan::where('id', $request->id)->with('user', 'plan')->firstOrFail();
        $loan->status         = 3;
        $loan->admin_feedback = $request->reason;
        $loan->save();

        notify($loan->user, "LOAN_REJECT", $loan->shortCodes());

        $notify[] = ['success', 'Financing rejected successfully'];
        return back()->withNotify($notify);
    }

    protected function loanData($scope = null) {
        $query = Loan::orderBy('id', 'DESC');

        if ($scope) {
            $query->$scope();
        }

        $pageTitle = $this->pageTitle;
        $loans     = $query->searchAble(['loan_number'])->with('user:id,username,account_number', 'plan', 'nextInstallment:id,installment_date')->paginate(getPaginate());
        return view('admin.loan.index', compact('pageTitle', 'loans'));
    }

    public function installments($id) {
        $loan          = Loan::with('installments')->findOrFail($id);
        $installments = $loan->installments()->paginate(getPaginate());
        $pageTitle    = "Installments";
        return view('admin.loan.installments', compact('pageTitle', 'installments', 'loan'));
    }
}

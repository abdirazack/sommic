<div class="table-responsive--sm table-responsive">
    <table class="table--light style--two table">
        <thead>
            <tr>
                <th>@lang('Date')</th>
                <th>@lang('TRX')</th>
                <th>@lang('Account')</th>
                @if (isManager())
                    <th>@lang('Teller')</th>
                @endif
                <th>@lang('Status')</th>
                <th>@lang('Amount')</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $transaction)
                <tr>
                    <td>{{ @$transaction->created_at }}</td>
                    
                    <td>
                        <a href="{{ route('staff.transactions.detail', ['transaction' => @$transaction->transaction_id]) }}">{{ @$transaction->transaction_id }}</a>
                    </td>

                    <td>
                        <a href="{{ route('staff.account.detail.' . lcfirst(@$account_category[@$transaction->account->account_category]), $transaction->account->account_number) }}">
                            {{ @$transaction->account->account_number }}
                        </a>
                    </td>
                    
                    @if (isManager())
                        <td>
                            <a href="{{ route('staff.profile.other', $transaction->branchStaff->id) }}">
                                {{ @$transaction->branchStaff->name }}
                            </a>
                        </td>
                    @endif
                    
                    <td>
                        <span class="badge badge--{{ @$transaction->status == 0 ? 'warning' : (@$transaction->status == 1 ? 'success' : 'danger') }}">
                            {{ @$transaction->status == 0 ? 'Pending' : (@$transaction->status == 1 ? 'Approved' : 'Rejected') }}
                        </span>
                    </td>
                    
                    <td>
                        <span class="fw-bold @if ($transaction->dr_cr == '1') text--success @else text--danger @endif">
                            {{ showAmount(@$transaction->amount) }} {{ __($general->cur_text) }}
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

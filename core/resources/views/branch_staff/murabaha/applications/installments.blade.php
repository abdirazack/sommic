@extends('branch_staff.layouts.app')
@section('panel')
    <div class="row gy-4">
        <div class="col-sm-5 col-lg-3">
            <div class="card custom--card">
                <div class="card-header bg--primary">
                    <h6 class="card-title text--white">@lang('Investment Summary')</h6>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <span class="value">{{ $investment->investment_reference }}</span>
                            <span class="caption">@lang('Investment Number')</span>
                        </li>

                        <li class="list-group-item">
                            <span class="value">{{ $investment->product->name }}</span>
                            <span class="caption">@lang('Product')</span>
                        </li>

                        <li class="list-group-item">
                            <span class="value">{{ $general->cur_sym . $investment->total_principle_amount }}</span>
                            <span class="caption">@lang('Investment Amount')</span>
                        </li>

                        <li class="list-group-item">
                            <span class="value text--base">{{ $general->cur_sym . $investment->total_profit_amount }}</span>
                            <span class="caption">@lang('Profit Amount')</span>
                        </li>

                        <li class="list-group-item">
                            <span class="value text--warning">{{ $general->cur_sym . $investment->total_investment_amount }}</span>
                            <span class="caption">@lang('Receivable Amount')</span>
                        </li>

                        <li class="list-group-item">
                            <span class="value text--base">{{ $general->cur_sym . $investment->payment_per_installment }}</span>
                            <span class="caption">@lang('Per Installment')</span>
                        </li>

                        <li class="list-group-item">
                            <span class="value">{{ $investment->total_installments }}</span>
                            <span class="caption">@lang('Total Installments')</span>
                        </li>

                        <li class="list-group-item">
                            <span class="value">{{ $investment->given_installments }}</span>
                            <span class="caption">@lang('Given Installments')</span>
                        </li>

                    </ul>
                </div>
            </div>
        </div>

        <div class="col-sm-7 col-lg-9">
            @include('branch_staff.partials.installments_table')
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <x-back route="{{ route('staff.murabaha.applications') }}" />
@endpush

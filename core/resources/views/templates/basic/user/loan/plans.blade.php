@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="container">
        <div class="plan-area">@include($activeTemplate . 'partials.loan_plans')</div>
    </div>
@endsection

@push('bottom-menu')
    <li><a href="{{ route('user.loan.plans') }}" class="active">@lang('Financing Plans')</a></li>
    <li><a href="{{ route('user.loan.list') }}">@lang('My Financing List')</a></li>
@endpush

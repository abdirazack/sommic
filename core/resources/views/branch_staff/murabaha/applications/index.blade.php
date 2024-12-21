@extends('branch_staff.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10">
                <div class="card-body p-0">
                    <div class="table-responsive--sm table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('ID')</th>
                                    <th>@lang('Reference No.')</th>
                                    <th>@lang('Customer Name') | @lang('Business Name')</th>
                                    <th>@lang('Amount')</th>
                                    <th>@lang('Installments')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($applications as $application)
                                    <tr>
                                        <td>{{ $loop->index+1}}</td>
                                        <td>
                                            <span>{{ @$application->investment->investment_reference }}</span>
                                            <span class="d-block badge badge--{{ @$status[@$application->status]['badge'] }}">{{ @$status[@$application->status]['label'] }}</span>
                                        </td>
                                        <td>
                                            <span>{{ @$application->personal_info->name }}</span>
                                            <span class="d-block text--info">{{ @$application->business_info->name }}</span>
                                        </td>
                                        <td>{{ "$" . showAmount($application->investment->total_investment_amount ) }}</td>
                                        
                                        <td>{{ (@$application->investment->given_installments) . "/" . (@$application->investment->total_installments) }}</td>
                                        <td>
                                            <a class="btn btn-sm btn-outline--info" href="{{ route('staff.murabaha.application.detail', @$application->id) }}">
                                                <i class="las la-desktop"></i>@lang('Details')
                                            </a>
                                            
                                            @if(@$application->status == 1 && @$application->investment->investment_reference != '')
                                            <a class="btn btn-sm btn-outline--success" href="{{ route('staff.murabaha.application.installments', $application->id) }}">
                                                <i class="las la-history"></i> @lang('Installments')
                                            </a>
                                            @endif
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
                </div>
                @if ($applications->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($applications) }}
                    </div>
                @endif
            </div>
        </div>
    </div>

@endsection

@push('breadcrumb-plugins')
    <a class="btn btn-outline--primary h-45 me-2" href="{{ route('staff.murabaha.new.application') }}">
        <i class="las la-plus"></i>@lang('Add New')
    </a>
    <form class="d-inline">
        <div class="input-group justify-content-end">
            <input type="text" name="search" value="{{ request()->search ?? '' }}" class="form-control bg--white" placeholder="@lang('Name, Mobile, Cellphone')">
            <button class="btn btn--primary input-group-text"><i class="fa fa-search"></i></button>
        </div>
    </form>
@endpush

@push('script')

@endpush

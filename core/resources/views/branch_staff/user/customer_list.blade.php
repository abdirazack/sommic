@extends('branch_staff.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10">
                <div class="card-body p-0">
                    <div class="table-responsive--md table-responsive">
                        <table class="table--light style--two table">
                            <thead>
                                <tr>
                                    <th>@lang('S.N.')</th>
                                    <th>@lang('Name')</th>
                                    <th>@lang('Mobile')</th>
                                    <th>@lang('Email')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Branch')</th>
                                    @if (isManager())
                                        <th>@lang('Registered By')</th>
                                    @endif

                                    <th>@lang('Registered At')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($customers as $customer)
                                    <tr>
                                        <td>{{ __($loop->index + $customers->firstItem()) }}</td>

                                        <td>
                                            {{ @$customer->name }}
                                        </td>

                                        <td>
                                            {{ @$customer->mobile }}
                                        </td>

                                        <td>
                                            {{ @$customer->email }}
                                        </td>
                                        
                                        <td>
                                            <span class="badge badge--{{ @$status[@$customer->status]['badge'] }}">{{ @$status[@$customer->status]['label'] }}</span>
                                        </td>
                                        
                                        <td>
                                            @if ($customer->branch_id)
                                                <span>{{ __(@$customer->branch->name) }}</span>
                                            @else
                                                <span>@lang('Online')</span>
                                            @endif
                                        </td>

                                        @if (isManager())
                                            <td>
                                                @if($customer->branchStaff)
                                                <a href="{{ route('staff.profile.other', @$customer->branchStaff->id) }}">
                                                    {{ @$customer->branchStaff->name }}
                                                </a>
                                                @else
                                                @lang('Online')
                                                @endif
                                            </td>
                                        @endif

                                        <td>{{ showDateTime(@$customer->created_at) }} </td>

                                        <td>
                                            @if ($staff->designation == Status::ROLE_CUSTOMER_SERVICE)
                                                <a class="btn btn-sm btn-outline--primary" href="{{ route('staff.customer.edit', @$customer->id) }}">
                                                    <i class="las la-edit"></i>@lang('Edit')
                                                </a>
                                            @endif

                                            <a class="btn btn-sm btn-outline--info" href="{{ route('staff.customer.detail', @$customer->id) }}">
                                                <i class="las la-desktop"></i>@lang('Details')
                                            </a>
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
                @if ($customers->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($customers) }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    @if ($staff->designation == Status::ROLE_MANAGER)
        <div class="btn-group">
            <button class="btn btn-outline--primary dropdown-toggle" data-bs-toggle="dropdown" type="button">
                @if ($branchId)
                    @php $branch = $branches->where('id', $branchId)->first(); @endphp
                    {{ @$branch->name }}
                @else
                    @lang('All Branch')
                @endif
            </button>

            <ul class="dropdown-menu">
                <li>
                    <a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['branch' => null]) }}">@lang('All Branch')</a>
                </li>
                @foreach ($branches as $branch)
                    <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['branch' => $branch->id]) }}">{{ __($branch->name) }}</a></li>
                @endforeach
            </ul>
        </div>
    @endif
    <x-search-form />
@endpush

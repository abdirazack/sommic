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
                                    <th>@lang('Name')</th>
                                    <th>@lang('Mobile')</th>
                                    <th>@lang('Cellphone')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($guarantors as $guarantor)
                                    <tr>
                                        <td>{{ $loop->index + 1 }}</td>
                                        <td>{{ @$guarantor->name }}</td>
                                        <td>{{ @$guarantor->mobile }}</td>
                                        <td>{{ @$guarantor->cellphone }}</td>
                                        <td>
                                            <span class="badge badge--{{ @$status[@$guarantor->status]['badge'] }}">{{ @$status[@$guarantor->status]['label'] }}</span>
                                        </td>
                                        <td>
                                            <a class="btn btn-sm btn-outline--info" href="{{ route('staff.murabaha.guarantor.detail', @$guarantor->id) }}">
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
                @if ($guarantors->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($guarantors) }}
                    </div>
                @endif
            </div>
        </div>
    </div>

@endsection

@push('breadcrumb-plugins')
    <a class="btn btn-outline--primary h-45 me-2" href="{{ route('staff.murabaha.new.guarantor') }}">
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

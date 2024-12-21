@extends('branch_staff.layouts.app')
@section('panel')
    <div class="row mb-3">
        <div class="col-xl-4 col-md-6">
            <div class="card mb-4">
                <div class="card-body text-center py-5">
                    <img class="account-holder-image rounded border viewable" src="{{ getImage(getFilePath('userProfile') . '/' . @$application->personal_info->image, null, true) }}" alt="account-holder-image">
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title text-center">@lang('Additional Information')</h5>
                </div>
                <div class="card-body py-4">
                    <div class="list-group list-group-flush">
                        
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-8 col-md-6">
            <div class="card">
                <div class="card-header">
                    <div class="position-relative">
                        <h5 class="position-absolute start-50 translate-middle-x">Basic Information</h5>
                        @if ($application->status == 0)
                        <span class="bg--warning px-3 rounded float-end"> <i class="la la-check-circle"></i> @lang('Pending')</span>
                        @elseif($application->status == 1)
                            <span class="bg--success px-3 rounded float-end"> <i class="la la-check-circle"></i> @lang('Active')</span>
                        @else
                            <span class="bg--danger px-3 rounded float-end"> <i class="la la-ban"></i> @lang('Rejected')</span>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    
                    <div class="row">
                        <div class="col-6">
                            <small class="text-muted">@lang('Name')</small>
                            <h6>{{ ucwords(@$application->personal_info->name) }}</h6>
                        </div>
                        
                        <div class="col-6">
                            <small class="text-muted">@lang('Gender')</small>
                            <h6>{{ @$application->personal_info->gender ? "Male" : "Female" }}</h6>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-4">
                            <small class="text-muted">@lang('Date of Birth')</small>
                            <h6>{{ showDateTime(@$application->personal_info->dob, 'd M Y') }}</h6>
                        </div>

                        <div class="col-4">
                            <small class="text-muted">@lang('Mother\'s Name')</small>
                            <h6>{{ ucwords(@$application->personal_info->mother_name) }}</h6>
                        </div>
                        
                        <div class="col-4">
                            <small class="text-muted">@lang('Nationality')</small>
                            <h6>{{ ucfirst(@$countries[@$application->personal_info->pob]->country) }}</h6>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-4">
                            <small class="text-muted">@lang('Mobile Number')</small>
                            <h6>{{ @$application->personal_info->mobile }} </h6>
                        </div>
                        
                        <div class="col-4">
                            <small class="text-muted">@lang('Cellphone')</small>
                            <h6>{{ @$application->personal_info->cellphone }} </h6>
                        </div>
                        
                        <div class="col-4">
                            <small class="text-muted">@lang('Address')</small>
                            <h6>{{ ucfirst(@$application->personal_info->address) }}</h6>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-3">
                            <small class="text-muted">@lang('Education Level')</small>
                            <h6>{{ @$application->personal_info->education_level }} </h6>
                        </div>
                        
                        <div class="col-3">
                            <small class="text-muted">@lang('House Status')</small>
                            <h6>{{ @$application->personal_info->house_status }} </h6>
                        </div>
                        
                        <div class="col-3">
                            <small class="text-muted">@lang('Civil Status')</small>
                            <h6>{{ ucwords(@$application->personal_info->civil_status) }}</h6>
                        </div>
                        
                        <div class="col-3">
                            <small class="text-muted">@lang('Dependants')</small>
                            <h6>{{ @$application->personal_info->dependants }}</h6>
                        </div>
                    </div>
                    <div class="row mt-3 mb-2">
                        <div class="col-4">
                            <small class="text-muted">@lang('Guarantor')</small>
                            <h6>
                                <a href="{{ route('staff.murabaha.guarantor.detail', @$application->guarantor->id) }}">{{ @$application->guarantor->name }}</a>
                            </h6>
                        </div>
                        <div class="col-4">
                            <small class="text-muted">@lang('Registred By')</small>
                            <h6>{{ ucwords(@$application->branchStaff->name) }}</h6>
                            
                        </div>
                        @if ($application->branch)
                            <div class="col-4">
                                <small class="text-muted">@lang('Joined On')</small>
                                <h6>{{ showDateTime(@$application->created_at, 'd M Y, h:i A') }}</h6>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title text-center">@lang('Business Information')</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-4">
                            <small class="text-muted">@lang('Name')</small>
                            <h6>{{ ucwords(@$application->business_info->name) }}</h6>
                        </div>
                        <div class="col-2">
                            <small class="text-muted">@lang('Type')</small>
                            <h6>{{ ucwords(@$application->business_info->type) }}</h6>
                        </div>
                        <div class="col-3">
                            <small class="text-muted">@lang('Mobile')</small>
                            <h6>{{ @$application->business_info->mobile }}</h6>
                        </div>
                        <div class="col-3">
                            <small class="text-muted">@lang('Address')</small>
                            <h6>{{ @$application->business_info->address }}</h6>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-2">
                            <small class="text-muted">@lang('Years in Business')</small>
                            <h6>{{ @$application->business_info->years }} </h6>
                        </div>
                        <div class="col-2">
                            <small class="text-muted">@lang('Employees')</small>
                            <h6>{{ @$application->business_info->employees }}</h6>
                        </div>
                        <div class="col-2">
                            <small class="text-muted">@lang('Assets')</small>
                            <h6>{{ $general->cur_sym . showAmount(@$application->business_info->assets) }}</h6>
                        </div>
                        <div class="col-2">
                            <small class="text-muted">@lang('Monthly Sales')</small>
                            <h6>{{ $general->cur_sym . showAmount(@$application->business_info->monthly_sales) }}</h6>
                        </div>
                        <div class="col-4">
                            <small class="text-muted">@lang('Caregiver')</small>
                            <h6>{{ @$application->business_info->caregiver }}</h6>
                        </div>
                    </div>
                    @if(count(@$application->business_info->partners) > 0)
                    <hr>
                    <div class="mt-3">
                        <h5>Partners: {{ count(@$application->business_info->partners) }}</h5>
                        @foreach(@$application->business_info->partners as $partner)
                        <div class="row mt-2">
                            <div class="col-6">
                                <small class="text-muted">@lang('Partner Name')</small>
                                <h6>{{ @$partner->name }}</h6>
                            </div>
                            
                            <div class="col-6">
                                <small class="text-muted">@lang('Partner Stake')</small>
                                <h6>{{ @$partner->stake . '%' }}</h6>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mb-3">
        <div class="col-xl-12 col-md-12">
            
        </div>
    </div>
    
    <div class="row mb-3">
        <div class="col-xl-12 col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title text-center">@lang('Business Information')</h5>
                </div>
                <div class="card-body">
                    
                </div>
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="viewModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button class="close" data-bs-dismiss="modal" type="button" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <div class="modal-body p-0">
                    <img id="modalImage" style="height:140%;width:100%" src="">
                </div>
            </div>
        </div>
    </div>

@endsection

@push('breadcrumb-plugins')
    @if ($staff->designation == Status::ROLE_MANAGER && $application->status == Status::ACCOUNT_PENDING)
        <a class="btn btn-lg btn--success btn--shadow" href="{{ route('staff.murabaha.approve.application', @$application->id) }}">
            <i class="las la-check-circle"></i> @lang('Approve Application')
        </a>
        <a class="btn btn-lg btn--danger btn--shadow" href="{{ route('staff.murabaha.reject.application', @$application->id) }}">
            <i class="las la-times-circle"></i> @lang('Reject Application')
        </a>
    @endif
    
    @if ($staff->designation == Status::ROLE_ACCOUNTING && $application->status == Status::ACCOUNT_ACTIVE && $application->investment_id == 0)
        <a class="btn btn-lg btn--success btn--shadow" href="{{ route('staff.murabaha.application.investment', @$application->id) }}">
            <i class="las la-plus"></i> @lang('Add Murabaha')
        </a>
    @endif
@endpush

@push('script')
    <script>
        "use strict";
        (function($) {
            
            $('.viewable').on('click', function(){
                var docSrc = $(this).attr('src');
                if(docSrc.split("/").pop().split(".").pop() == "pdf"){
                    window.open(docSrc, "blank");
                }else{
                    var modal = $('#viewModal');
                    var imgModal = $('#modalImage');
                    imgModal.attr("src", docSrc);
                    modal.modal('show');
                }
            });

        })(jQuery);
    </script>
@endpush

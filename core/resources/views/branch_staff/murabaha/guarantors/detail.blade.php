@extends('branch_staff.layouts.app')
@section('panel')
    <div class="row mb-3">
        <div class="col-xl-4 col-md-6">
            <div class="card mb-4">
                <div class="card-body text-center py-5">
                    <img class="account-holder-image rounded border viewable" src="{{ getImage(getFilePath('userProfile') . '/' . @$guarantor->image, null, true) }}" alt="account-holder-image">
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title text-center">@lang('Additional Information')</h5>
                </div>
                <div class="card-body py-4">
                    <div class="list-group list-group-flush">
                        
                        <div class="list-group-item border-0">
                            <small class="text-muted">@lang('House Status')</small>
                            <h6>{{ @$guarantor->house_status }} </h6>
                        </div>
                        
                        <div class="list-group-item border-0">
                            <small class="text-muted">@lang('Civil Status')</small>
                            <h6>{{ ucwords(@$guarantor->civil_status) }}</h6>
                        </div>
                        
                        <div class="list-group-item border-0">
                            <small class="text-muted">@lang('Dependants')</small>
                            <h6>{{ @$guarantor->dependants }}</h6>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-8 col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title text-center">@lang('Basic Information')</h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">

                        <div class="list-group-item d-flex justify-content-between align-items-center flex-wrap border-0">
                            <small class="text-muted">@lang('Guarantor Status')</small>
                            @if ($guarantor->status == 0)
                                <span class="bg--warning py-1 px-3 rounded"> <i class="la la-check-circle"></i> @lang('Pending')</span>
                            @elseif($guarantor->status == 1)
                                <span class="bg--success py-1 px-3 rounded"> <i class="la la-check-circle"></i> @lang('Active')</span>
                            @else
                                <span class="bg--danger py-1 px-3 rounded"> <i class="la la-ban"></i> @lang('Rejected')</span>
                            @endif
                        </div>
                        
                        <div class="list-group-item border-0">
                            <small class="text-muted">@lang('Name')</small>
                            <h6>{{ ucwords(@$guarantor->name) }}</h6>
                        </div>
                        
                        <div class="list-group-item border-0">
                            <small class="text-muted">@lang('Gender')</small>
                            <h6>{{ @$guarantor->gender ? "Male" : "Female" }}</h6>
                        </div>
                        
                        <div class="list-group-item border-0">
                            <small class="text-muted">@lang('Date of Birth')</small>
                            <h6>{{ showDateTime(@$guarantor->dob, 'd M Y') }}</h6>
                        </div>

                        <div class="list-group-item border-0">
                            <small class="text-muted">@lang('Mobile Number')</small>
                            <h6>{{ @$guarantor->mobile }} </h6>
                        </div>
                        
                        <div class="list-group-item border-0">
                            <small class="text-muted">@lang('Cellphone')</small>
                            <h6>{{ @$guarantor->cellphone }} </h6>
                        </div>
                        
                        <div class="list-group-item border-0">
                            <small class="text-muted">@lang('Nationality')</small>
                            <h6>{{ ucfirst(@$countries[@$guarantor->pob]->country) }}</h6>
                        </div>
                        
                        <div class="list-group-item border-0">
                            <small class="text-muted">@lang('Address')</small>
                            <h6>{{ ucfirst(@$guarantor->address) }}</h6>
                        </div>

                        <div class="list-group-item border-0">
                            <small class="text-muted">@lang('Joined On')</small>
                            <h6>{{ showDateTime(@$guarantor->created_at, 'd M Y, h:i A') }}</h6>
                        </div>

                        @if ($guarantor->branch)
                            <div class="list-group-item border-0">
                                <small class="text-muted">@lang('Registred By')</small>
                                <h6>{{ ucwords(@$guarantor->branchStaff->name) }}</h6>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12 col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title text-center">@lang('Employment Information')</h5>
                </div>
                <div class="card-body">
                    @if($guarantor->employment->status == 1)
                    <div class="row">
                        
                        <div class="col-3">
                            <small class="text-muted">@lang('Organization Name')</small>
                            <h6>{{ @$guarantor->employment->organization_name }}</h6>
                        </div>
                        
                        <div class="col-3">
                            <small class="text-muted">@lang('Employer/Superior Name')</small>
                            <h6>{{ @$guarantor->employment->superior_name }}</h6>
                        </div>
                        
                        <div class="col-3">
                            <small class="text-muted">@lang('Mobile')</small>
                            <h6>{{ @$guarantor->employment->mobile }}</h6>
                        </div>
                        
                        <div class="col-3">
                            <small class="text-muted">@lang('Nature of Business')</small>
                            <h6>{{ @$guarantor->employment->nature }}</h6>
                        </div>
                        
                    </div>
                    
                    <div class="row mt-2">
                        
                        <div class="col-3">
                            <small class="text-muted">@lang('Address')</small>
                            <h6>{{ @$guarantor->employment->address }}</h6>
                        </div>
                        
                        <div class="col-3">
                            <small class="text-muted">@lang('Position/Title')</small>
                            <h6>{{ @$guarantor->employment->title }}</h6>
                        </div>
                        
                        <div class="col-3">
                            <small class="text-muted">@lang('Years at Organization')</small>
                            <h6>{{ @$guarantor->employment->years }}</h6>
                        </div>
                        
                        <div class="col-3">
                            <small class="text-muted">@lang('Monthly Salary')</small>
                            <h6>{{ @$general->cur_sym . showAmount(@$guarantor->employment->monthly_salary) }}</h6>
                        </div>
                        
                    </div>
                    @else
                    <div class="row">
                        
                        <div class="col-3">
                            <small class="text-muted">@lang('Business Name')</small>
                            <h6>{{ @$guarantor->employment->name }}</h6>
                        </div>
                        
                        <div class="col-3">
                            <small class="text-muted">@lang('Nature of Business')</small>
                            <h6>{{ @$guarantor->employment->nature }}</h6>
                        </div>
                        
                        <div class="col-3">
                            <small class="text-muted">@lang('Mobile')</small>
                            <h6>{{ @$guarantor->employment->mobile }}</h6>
                        </div>
                        
                        <div class="col-3">
                            <small class="text-muted">@lang('Address')</small>
                            <h6>{{ @$guarantor->employment->address }}</h6>
                        </div>
                        
                    </div>
                    
                    <div class="row mt-2">
                        
                        <div class="col-3">
                            <small class="text-muted">@lang('Years in Business')</small>
                            <h6>{{ @$guarantor->employment->years }}</h6>
                        </div>
                        
                        <div class="col-9">
                            <small class="text-muted">@lang('Monthly Sales')</small>
                            <h6>{{ @$general->cur_sym . showAmount(@$guarantor->employment->monthly_sale) }}</h6>
                        </div>
                        
                    </div>
                    
                    @if(count(@$guarantor->employment->partners) > 0)
                    <hr>
                    <div class="mt-3">
                        <h5>Partners: {{ count(@$guarantor->employment->partners) }}</h5>
                        @foreach(@$guarantor->employment->partners as $partner)
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
                    
                    @endif
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
    @if ($staff->designation == Status::ROLE_MANAGER && $guarantor->status == Status::ACCOUNT_PENDING)
        <a class="btn btn-lg btn--success btn--shadow" href="{{ route('staff.murabaha.approve.guarantor', @$guarantor->id) }}">
            <i class="las la-check-circle"></i> @lang('Approve Guarantor')
        </a>
        <a class="btn btn-lg btn--danger btn--shadow" href="{{ route('staff.murabaha.reject.guarantor', @$guarantor->id) }}">
            <i class="las la-times-circle"></i> @lang('Reject Guarantor')
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

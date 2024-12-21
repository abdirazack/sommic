@extends('branch_staff.layouts.app')
@section('panel')
    <div class="row gy-4">

        <div class="col-xl-4 col-md-6">
            <div class="card mb-4">
                <div class="card-body text-center">
                    <img class="account-holder-image rounded border viewable" src="{{ getImage(getFilePath('userProfile') . '/' . @$customer->misc->image, null, true) }}" alt="account-holder-image">
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title text-center">@lang('Accounts')</h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <center>
                        @foreach($accounts as $account)
                            <div class="list-group-item d-flex justify-content-between flex-wrap border-0">
                                <span class="text-{{ @$status[@$account->status]['badge'] }}">{{ $account->account_number }}</span>
                                <span class="text-muted">{{ @$account_category[@$account->account_category] }}</span>
                                <a href="{{ route('staff.account.detail.' . lcfirst(@$account_category[@$account->account_category]), $account->account_number) }}">@lang('View')</a>
                            </div>
                        @endforeach
                        </center>
                    </div>
                </div>
            </div>

            <div class="card ">
                <div class="card-header">
                    <h5 class="card-title text-center">@lang('Attachments')</h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        
                        <div class="list-group-item d-flex justify-content-between flex-wrap border-0">
                            <span class="text-muted">@lang('Verification Document')</span>
                            <a class="viewable" href="#" src="{{ getImage(getFilePath('userProfile') . '/' . @$customer->identifier_link, null, true) }}">@lang('View')</a>
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
                            <small class="text-muted">@lang('Customer Status')</small>
                            @if ($customer->status == 0)
                                <span class="bg--warning py-1 px-3 rounded"> <i class="la la-check-circle"></i> @lang('Pending')</span>
                            @elseif($customer->status == 1)
                                <span class="bg--success py-1 px-3 rounded"> <i class="la la-check-circle"></i> @lang('Active')</span>
                            @else
                                <span class="bg--danger py-1 px-3 rounded"> <i class="la la-ban"></i> @lang('Rejected')</span>
                            @endif
                        </div>
                        
                        <div class="list-group-item border-0">
                            <small class="text-muted">@lang('Name')</small>
                            <h6>{{ ucwords(@$customer->name) }}</h6>
                        </div>
                        
                        <div class="list-group-item border-0">
                            <small class="text-muted">@lang('Gender')</small>
                            <h6>{{ @$customer->misc->gender ? "Male" : "Female" }}</h6>
                        </div>
                        
                        <div class="list-group-item border-0">
                            <small class="text-muted">@lang('Date of Birth')</small>
                            <h6>{{ showDateTime(@$customer->misc->dob, 'd M Y') }}</h6>
                        </div>

                        <div class="list-group-item border-0">
                            <small class="text-muted">@lang('Mobile Number')</small>
                            <h6>{{ @$customer->mobile }} </h6>
                        </div>
                        
                        <div class="list-group-item border-0">
                            <small class="text-muted">@lang('Email')</small>
                            <h6>{{ @$customer->email }} </h6>
                        </div>
                        
                        <div class="list-group-item border-0">
                            <small class="text-muted">@lang('Country')</small>
                            <h6>{{ ucfirst(@$countries[@$customer->misc->nationality]->country) }}</h6>
                        </div>
                        
                        <div class="list-group-item border-0">
                            <small class="text-muted">@lang('City')</small>
                            <h6>{{ ucfirst(@$customer->city) }}</h6>
                        </div>

                        <div class="list-group-item border-0">
                            <small class="text-muted">@lang('Region')</small>
                            <h6>{{ ucfirst(@$customer->region) }}</h6>
                        </div>

                        <div class="list-group-item border-0">
                            <small class="text-muted">@lang('Address')</small>
                            <h6>{{ ucfirst(@$customer->address) }}</h6>
                        </div>

                        

                        <div class="list-group-item border-0">
                            <small class="text-muted">@lang('Joined On')</small>
                            <h6>{{ showDateTime(@$customer->created_at, 'd M Y, h:i A') }}</h6>
                        </div>

                        @if ($customer->branch)
                            <div class="list-group-item border-0">
                                <small class="text-muted">@lang('Registred By')</small>
                                <h6>{{ ucwords(@$customer->branchStaff->name) }}</h6>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addSubModal" role="dialog" tabindex="-1">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button class="close" data-bs-dismiss="modal" type="button" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form action="" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>@lang('Amount')</label>
                            <div class="input-group">
                                <input class="form-control" name="amount" type="number" value="{{ old('amount') }}" step="any" required>
                                <span class="input-group-text">{{ __($general->cur_text) }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>@lang('Description')</label>
                            <div class="input-group">
                                <input class="form-control" name="description" type="text" value="{{ old('description') }}" step="any" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn--primary h-45 w-100" type="submit">@lang('Submit')</button>
                    </div>
                </form>
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
    @if ($staff->designation == Status::ROLE_MANAGER && $customer->status == Status::ACCOUNT_PENDING && $canChangeStatus)
        <a class="btn btn-lg btn--success btn--shadow" href="{{ route('staff.customer.approve', @$customer->id) }}">
            <i class="las la-check-circle"></i> @lang('Approve Customer')
        </a>
        <a class="btn btn-lg btn--danger btn--shadow" href="{{ route('staff.customer.reject', @$customer->id) }}">
            <i class="las la-times-circle"></i> @lang('Reject Customer')
        </a>
    @endif
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";
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
            
            $('.deposit-btn').on('click', function() {
                var modal = $('#addSubModal');
                modal.find('.modal-title').text('Deposit Money');
                modal.find('form').attr('action', $(this).data('action'));
                modal.modal('show');
            });

            $('.withdraw-btn').on('click', function() {
                var modal = $('#addSubModal');
                modal.find('.modal-title').text('Withdraw Money');
                modal.find('form').attr('action', $(this).data('action'));
                modal.modal('show');
            });
        })(jQuery)
    </script>
@endpush

@push('style')
    <style>
        .account-holder-image {
            height: 180px;
        }
    </style>
@endpush

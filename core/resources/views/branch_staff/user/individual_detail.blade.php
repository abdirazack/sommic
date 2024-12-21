@extends('branch_staff.layouts.app')
@section('panel')
    <div class="d-flex gap-3 mb-4 flex-wrap">
        <div class="flex-fill">
            <x-widget style="2" color="primary" title="Account Number | Account Type" value="{{ @$account->account_number }} | Individual {{ @$account->AccountType->name }}" icon="la la-user" icon_style="solid" />
        </div>

        <div class="flex-fill">
            <x-widget style="2" color="success" title="Balance | Limit: {{ showAmount(@$account->misc->daily_withdraw_amount_limit) }}" value="{{ showAmount(@$account->account_balance) }} {{ @$general->cur_text }}" icon="la la-money" icon_style="solid" />
        </div>

        <div class="flex-fill">
            <x-widget style="2" color="danger" title="Branch Name" value="{{ @$account->branch->name ?? 'Online' }}" icon="la la-map-marker" icon_style="solid" />
        </div>
    </div>

    <div class="row gy-4">

        <div class="col-xl-3 col-md-6">
            <div class="card mb-4">
                <div class="card-title d-flex justify-content-center gap-3 mt-3">
                    <h6>
                        <i class="la la-{{ @$account->ebank ? 'check' : 'times' }}-circle text--{{ @$account->ebank ? 'success' : 'danger' }}"></i>
                        E-Bank
                    </h6>
                    <h6>
                        <i class="la la-{{ @$account->cheque ? 'check' : 'times' }}-circle text--{{ @$account->cheque ? 'success' : 'danger' }}"></i>
                        Cheque Book
                    </h6>
                    <h6>
                        <i class="la la-{{ @$account->wallet ? 'check' : 'times' }}-circle text--{{ @$account->wallet ? 'success' : 'danger' }}"></i>
                        Mobile Wallet
                    </h6>
                </div>
                <div class="card-body text-center">
                    <img class="account-holder-image rounded border viewable" src="{{ getImage(getFilePath('userProfile') . '/' . @$account->customer->misc->image, null, true) }}" alt="account-holder-image">
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title text-center">@lang('Attachments')</h5>
                </div>
                <div class="card-body mb-4">
                    <div class="list-group list-group-flush">
                        
                        <div class="list-group-item d-flex justify-content-between flex-wrap border-0">
                            <span class="text-muted">@lang('Signature')</span>
                            <a class="viewable" href="#" src="{{ getImage(getFilePath('userProfile') . '/' . @$account->misc->documents->signature_scan, null, true) }}">@lang('View')</a>
                        </div>
                        
                        <div class="list-group-item d-flex justify-content-between flex-wrap border-0">
                            <span class="text-muted">@lang('Application Document')</span>
                            <a class="viewable" href="#" src="{{ getImage(getFilePath('userProfile') . '/' . @$account->misc->documents->application_scan, null, true) }}">@lang('View')</a>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-6 col-md-6">

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title text-center">@lang('Basic Information')</h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">

                        <div class="list-group-item d-flex justify-content-between align-items-center flex-wrap border-0">
                            <small class="text-muted">@lang('Customer Status')</small>
                            @if ($account->customer->status == 0)
                                <span class="bg--warning py-1 px-3 rounded"> <i class="la la-check-circle"></i> @lang('Pending')</span>
                            @elseif($account->customer->status == 1)
                                <span class="bg--success py-1 px-3 rounded"> <i class="la la-check-circle"></i> @lang('Active')</span>
                            @else
                                <span class="bg--danger py-1 px-3 rounded"> <i class="la la-ban"></i> @lang('Banned')</span>
                            @endif
                        </div>
                        
                        <div class="list-group-item border-0">
                            <small class="text-muted">@lang('Name')</small>
                            <h6><a href="{{ route('staff.customer.detail', @$account->customer->id) }}">{{ @$account->customer->name }}</a></h6>
                        </div>
                        
                        <div class="list-group-item border-0">
                            <small class="text-muted">@lang('Gender')</small>
                            <h6>{{ @$account->customer->misc->gender == 1? "Male" : "Female" }}</h6>
                        </div>
                        
                        <div class="list-group-item border-0">
                            <small class="text-muted">@lang('Date of Birth')</small>
                            <h6>{{ showDateTime(@$account->customer->dob, 'd M Y') }}</h6>
                        </div>

                        <div class="list-group-item border-0">
                            <small class="text-muted">@lang('Mobile Number')</small>
                            <h6>{{ @$account->customer->mobile }} </h6>
                        </div>
                        
                        <div class="list-group-item border-0">
                            <small class="text-muted">@lang('Email')</small>
                            <h6>{{ @$account->customer->email }} </h6>
                        </div>
                        
                        <div class="list-group-item border-0">
                            <small class="text-muted">@lang('Country')</small>
                            <h6>{{ strtoupper(@$countries[@$account->customer->misc->nationality]->country) }}</h6>
                        </div>
                        
                        <div class="list-group-item border-0">
                            <small class="text-muted">@lang('City')</small>
                            <h6>{{ @$account->customer->city }}</h6>
                        </div>

                        <div class="list-group-item border-0">
                            <small class="text-muted">@lang('Region')</small>
                            <h6>{{ @$account->customer->region }}</h6>
                        </div>

                        <div class="list-group-item border-0">
                            <small class="text-muted">@lang('Address')</small>
                            <h6>{{ @$account->customer->address }}</h6>
                        </div>

                        

                        <div class="list-group-item border-0">
                            <small class="text-muted">@lang('Joined On')</small>
                            <h6>{{ showDateTime(@$account->created_at, 'd M Y, h:i A') }}</h6>
                        </div>

                        @if ($account->branch)
                            <div class="list-group-item border-0">
                                <small class="text-muted">@lang('Registred By')</small>
                                <h6>{{ @$account->branchStaff->name }}</h6>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-3">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title text-center">@lang('Next of Kin 1')</h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">

                        <div class="list-group-item border-0">
                            <small class="text-muted">@lang('Name')</small>
                            <h6>{{ ucwords(@$account->misc->nok->p1_nok->nok1->name) }}</h6>
                        </div>
                        
                        <div class="list-group-item border-0">
                            <small class="text-muted">@lang('Relation')</small>
                            <h6>{{ @$relations[@$account->misc->nok->p1_nok->nok1->relation] }} </h6>
                        </div>

                        <div class="list-group-item  border-0">
                            <small class="text-muted">@lang('Mobile Number')</small>
                            <h6>{{ @$account->misc->nok->p1_nok->nok1->mobile }} </h6>
                        </div>
                        
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title text-center">@lang('Next of Kin 2')</h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">

                        <div class="list-group-item border-0">
                            <small class="text-muted">@lang('Name')</small>
                            <h6>{{ ucwords(@$account->misc->nok->p1_nok->nok2->name) }}</h6>
                        </div>
                        
                        <div class="list-group-item border-0">
                            <small class="text-muted">@lang('Relation')</small>
                            <h6>{{ @$relations[@$account->misc->nok->p1_nok->nok1->relation] }} </h6>
                        </div>

                        <div class="list-group-item border-0">
                            <small class="text-muted">@lang('Mobile Number')</small>
                            <h6>{{ @$account->misc->nok->p1_nok->nok2->mobile }} </h6>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    @if($account->status == Status::ACCOUNT_ACTIVE)
    <div class="modal fade" id="addSubModal" role="dialog" tabindex="-1">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button class="close" data-bs-dismiss="modal" type="button" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form action="" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input name="account_id" type="hidden" value="{{ $account->account_number }}"> 
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="amount">@lang('Amount')</label>
                            <div class="input-group">
                                <input class="form-control" name="amount" id="amount" type="number" value="{{ old('amount') }}" step="any" required>
                                <span class="input-group-text">{{ __(@$general->cur_text) }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="description">@lang('Description')</label>
                            <div class="input-group">
                                <input class="form-control" name="description" id="description" type="text" value="{{ old('description') }}" step="any" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="document">@lang('Document')</label>
                            <div class="input-group">
                                <input class="form-control" name="document" id="document" type="file" accept=".pdf, .png, .jpg, .jpeg" required>
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
    @endif
    
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
    @if ($staff->designation == Status::ROLE_TELLER)
        <button class="btn btn-lg btn--success btn--shadow deposit-btn" {{ $account->status != Status::ACCOUNT_ACTIVE ? 'disabled' : '' }} data-action="{{ route('staff.transactions.deposit', @$account->account_number) }}">
            <i class="las la-plus-circle"></i> @lang('Deposit Money')
        </button>

        <button class="btn btn--danger btn--shadow withdraw-btn" {{ $account->status != Status::ACCOUNT_ACTIVE ? 'disabled' : '' }} data-action="{{ route('staff.transactions.withdraw', @$account->account_number) }}">
            <i class="las la-minus-circle"></i> @lang('Withdraw Money')
        </button>
    @elseif ($staff->designation == Status::ROLE_MANAGER && $account->status == Status::ACCOUNT_PENDING && $canChangeStatus)
        <a class="btn btn-lg btn--success btn--shadow" href="{{ route('staff.account.approve', @$account->id) }}">
            <i class="las la-check-circle"></i> @lang('Approve Account')
        </a>
        <a class="btn btn-lg btn--danger btn--shadow" href="{{ route('staff.account.reject', @$account->id) }}">
            <i class="las la-times-circle"></i> @lang('Reject Account')
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
            height: 300px;
            width: 350px;
        }
    </style>
@endpush

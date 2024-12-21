@extends('branch_staff.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mt-2 text-secondary">
                        {{ @$transaction->transaction_id }}
                        <span class="badge badge--{{ @$status[@$transaction->status]['badge'] }} float-end">{{ @$status[@$transaction->status]['label'] }}</span>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <center>
                            <div class="list-group-item d-flex justify-content-between flex-wrap border-0">
                                <span class="text-muted">Description</span>
                                <h6>{{ ucfirst(@$transaction->description) }}</h6>
                            </div>
                            <div class="list-group-item d-flex justify-content-between flex-wrap border-0">
                                <span class="text-muted">Amount</span>
                                <h6>{{ showAmount(@$transaction->amount) }}</h6>
                            </div>
                            <div class="list-group-item d-flex justify-content-between flex-wrap border-0">
                                <span class="text-muted">Account</span>
                                <a href="{{ route('staff.account.detail.' . lcfirst(@$account_category[@$transactionAccount->account_category]), @$transactionAccount->account_number) }}">
                                    {{ @$transactionAccount->account_number }}
                                </a>
                            </div>
                            <div class="list-group-item d-flex justify-content-between flex-wrap border-0">
                                <span class="text-muted">Date</span>
                                <h6>{{ showDateTime(@$transaction->created_at, 'd M Y') }}</h6>
                            </div>
                            <div class="list-group-item d-flex justify-content-between flex-wrap border-0">
                                <span class="text-muted">Initiator</span>
                                <h6>{{ $transaction->branchStaff->name }}</h6>
                            </div>
                        </center>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mt-2 text-secondary">Documents & Signatories</h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item d-flex justify-content-between flex-wrap border-0">
                            <span class="text-muted">@lang('Transaction Document')</span>
                            <a class="viewable" href="#" src="{{ getImage(getFilePath('userProfile') . '/' . @$transaction->misc->document, null, true) }}">@lang('View')</a>
                        </div>
                        
                        @foreach($customers as $customer)
                        <div class="list-group-item d-flex justify-content-between flex-wrap border-0">
                            <a href="{{ route('staff.customer.detail', @$customer->id) }}">{{ @$customer->name }}</a>
                            <a class="viewable" href="#" src="{{ getImage(getFilePath('userProfile') . '/' . @$transactionAccount->misc->documents->{'p' . ($loop->index + 1) . '_signature_scan'}, null, true) }}">@lang('View')</a>
                        </div>
                        @endforeach
                        
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-12">
            <div class="card b-radius--10">
                <div class="card-body p-0">
                    <div class="table-responsive--sm table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('ID')</th>
                                    <th>@lang('Account')</th>
                                    <th>@lang('Description')</th>
                                    <th>@lang('Debit')</th>
                                    <th>@lang('Credit')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($JournalTransactions as $transaction)
                                    <tr>
                                        <td>{{ __($loop->index + 1) }}</td>
                                        <td>{{ @$transaction->coa->name }}</td>
                                        <td>{{ @$transaction->description }}</td>
                                        <td>{{ @$transaction->dr_cr == 1 ? showAmount(@$transaction->amount) : '' }}</td>
                                        <td>{{ @$transaction->dr_cr == 2 ? showAmount(@$transaction->amount) : '' }}</td>
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
    @if ($staff->designation == Status::ROLE_ACCOUNTING && $transaction->status == Status::ACCOUNT_PENDING)
        <a class="btn btn-lg btn--success btn--shadow" href="{{ route('staff.transactions.approve', @$transaction->transaction_id) }}">
            <i class="las la-check-circle"></i> @lang('Approve Transaction')
        </a>
        <a class="btn btn-lg btn--danger btn--shadow" href="{{ route('staff.transactions.reject', @$transaction->transaction_id) }}">
            <i class="las la-times-circle"></i> @lang('Reject Transaction')
        </a>
    @endif
@endpush

@push('script')
    <script>
    let types = null;
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
            
            $(".modalBtn").on('click', function(e) {
                $("#modal").modal('show');
                $(".modal-title").text($(e.target).data("modal_title"));
                if($(e.target).hasClass("edit")){
                    $("[name=id]").val($(e.target).data("id"));
                    $(".modal-body :input").each(function() {
                        let data = $(e.target).data(this.name);
                        if(["category", "type"].includes(this.name)){
                            $(`#${this.id} option[value='${data}']`).prop('selected', true).trigger('change');
                        }else{
                            $(this).val(data);
                        }
                    });
                }else{
                    $("[name=id]").val("0");
                    $(".modal :input:not([type=hidden]):not(:button)").val('');
                }
            });

        })(jQuery);
    </script>
@endpush

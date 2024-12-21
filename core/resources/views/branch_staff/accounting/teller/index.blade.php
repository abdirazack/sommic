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
                                    <th>@lang('#')</th>
                                    <th>@lang('Name')</th>
                                    <th>@lang('Account Number')</th>
                                    <th>@lang('Branch')</th>
                                    <th>@lang('Balance')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($tellers as $teller)
                                    <tr>
                                        <td>{{ __($loop->index + 1) }}</td>
                                        <td>{{ @$teller->name }}</td>
                                        <td>{{ @$teller->account_number }}</td>
                                        <td>{{ implode(", ", @$teller->assignBranch->pluck('name')->toArray()) }}</td>
                                        <td>{{ $general->cur_sym }}{{ showAmount(@$teller->balance) }}</td>
                                        
                                        <td>
                                            <a class="btn btn-sm btn-outline--primary fs-5" href="{{ route('staff.teller.index', $teller->id) }}">
                                                <i class="la la-user-cog me-0"></i>
                                            </a>
                                            <a class="btn btn-sm btn-outline--primary fs-5" href="{{ route('staff.teller.index', $teller->id) }}">
                                                <i class="la la-history me-0"></i>
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
                @if ($tellers->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($tellers) }}
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="transferModal">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Transfer Funds</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form action="{{ route('staff.coa.transfer') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="source_account">@lang('Source Account')</label>
                                    <select class="form-control" name="source_account" id="source_account" required>
                                        <option selected disabled value>@lang('Select Source Account')</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="beneficiary_account">@lang('Beneficiary Account')</label>
                                    <select class="form-control" name="beneficiary_account" id="beneficiary_account" required>
                                        <option selected disabled value>@lang('Select Beneficiary Account')</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="amount">@lang('Transfer Amount')</label>
                                    <input class="form-control" name="amount" type="text" id="amount" placeholder="@lang('Transfer Amount')" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn--primary w-100 h-45" type="submit">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="transferModal">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Transfer Funds</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form action="{{ route('staff.coa.transfer') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="source_account">@lang('Source Account')</label>
                                    <select class="form-control" name="source_account" id="source_account" required>
                                        <option selected disabled value>@lang('Select Source Account')</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="beneficiary_account">@lang('Beneficiary Account')</label>
                                    <select class="form-control" name="beneficiary_account" id="beneficiary_account" required>
                                        <option selected disabled value>@lang('Select Beneficiary Account')</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="amount">@lang('Transfer Amount')</label>
                                    <input class="form-control" name="amount" type="text" id="amount" placeholder="@lang('Transfer Amount')" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn--primary w-100 h-45" type="submit">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <button type="button" class="btn btn-outline--primary h-45 me-2 transferModalBtn">
        <i class="las la-plus"></i>@lang('Transfer Funds')
    </button>
    
    <form class="d-inline">
        <div class="input-group justify-content-end">
            <input type="text" name="search" value="{{ request()->search ?? '' }}" class="form-control bg--white" placeholder="@lang('Name, Code...')">
            <button class="btn btn--primary input-group-text"><i class="fa fa-search"></i></button>
        </div>
    </form>
@endpush

@push('script')
    <script>
    _ = {};
        "use strict";
        (function($) {
            let types = null;
            $(document).ready(() => {
                
                $("#category").on("change", function(e) {
                    $(`#type option:not(:first)`).remove();
                    $.each(types.categories[e.target.value], function(index, element){
                        $("<option>").val(element.id).text(element.name).appendTo("#type");
                    });
                });
            });
            
            $(".accountModalBtn").on('click', function(e) {
                $("#accountModal").modal('show');
                $("#accountModal .modal-title").text($(e.currentTarget).data("modal_title"));
                if($(e.currentTarget).hasClass("edit")){
                    $("[name=id]").val($(e.currentTarget).data("id"));
                    $("#accountModal .modal-body :input").each(function() {
                        let data = $(e.currentTarget).data(this.name);
                        if(["category", "type"].includes(this.name)){
                            $(`#${this.id} option[value='${data}']`).prop('selected', true).trigger('change');
                        }else{
                            $(this).val(data);
                        }
                    });
                }else{
                    $("#accountModal [name=id]").val("0");
                    $("#accountModal :input:not([type=hidden]):not(:button)").val('');
                }
            });
            
            $(".transferModalBtn").on('click', function(e) {
                $('#transferModal').modal('show');
                $('#transferModal option :not(first)').remove();
                $.each(accounts, function(index, element) {
                    $('#transferModal select').append(
                        $('<option>').val(element.id).text(element.name)
                    );
                });
            });
            
            $("#transferModal select").on('change', function(e) {
                currentAccount = $(e.currentTarget).val();
                targetElement = e.currentTarget.id == "source_account" ? "#beneficiary_account" : "#source_account";
                targetAccount = $(e.currentTarget.id == "source_account" ? `${targetElement} option[value=${currentAccount}]` : `${targetElement} option[value=${currentAccount}]`);
                
                if(_.hasOwnProperty('element')) $(_.element).append(_.option);
                _ = {'element': targetElement, 'option' : targetAccount.remove()};
            });

        })(jQuery);
    </script>
@endpush

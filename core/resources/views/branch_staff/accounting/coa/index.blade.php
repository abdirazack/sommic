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
                                    <th>@lang('Code')</th>
                                    <th>@lang('Name')</th>
                                    <th>@lang('Type')</th>
                                    <th>@lang('Category')</th>
                                    <th>@lang('Balance')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($accounts as $account)
                                    <tr>
                                        <td>{{ __($loop->index + 1) }}</td>
                                        <td>{{ @$account->code }}</td>
                                        <td>
                                            <a href="{{ route('staff.journal.index', ['coa' => $account->id]) }}">{{ @$account->name }}</a>
                                        </td>
                                        <td>{{ @$account->type->name }}</td>
                                        <td>{{ @$account->category->name }}</td>
                                        <td>{{ $general->cur_sym }}{{ showAmount(@$account->balance) }}</td>
                                        
                                        <td>
                                            <button type="button" class="btn btn-sm btn-outline--primary accountModalBtn edit"
                                            data-id="{{ $account->id }}" data-code="{{ $account->code }}" data-name="{{ $account->name }}"
                                            data-category="{{ $account->category->id }}" data-type="{{ $account->type->id }}" data-dr_cr="{{ $account->dr_cr }}"
                                            data-inbound="{{ $account->inbound }}" data-outbound="{{ $account->outbound }}"
                                            data-modal_title="@lang('Edit Account')">
                                                <i class="la la-pencil"></i>@lang('Edit')
                                            </button>
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
                @if ($accounts->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($accounts) }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Create Update Account Modal -->
    <div class="modal fade" id="accountModal">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form action="{{ route('staff.coa.store') }}" method="POST">
                    @csrf
                    <input name="id" type="hidden">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-9">
                                <div class="form-group">
                                    <label for="name">@lang('Name')</label>
                                    <input class="form-control" name="name" type="text" id="name" value="{{ old('name') }}" placeholder="@lang('Name')" required>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="code">@lang('Code')</label>
                                    <input class="form-control" name="code" type="text" id="code" value="{{ old('code') }}" placeholder="@lang('Code')" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="category">@lang('Category')</label>
                                    <select class="form-control" name="category" id="category" required>
                                        <option selected disabled value>@lang('Select Category')</option>
                                        @foreach ($categories as $id => $category)
                                            <option value="{{ $id }}" {{ old('dr_cr') == $id ? 'selected' : ''}}>{{ @$category }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="type">@lang('Type')</label>
                                    <select class="form-control" name="type" id="type" required>
                                        <option selected disabled value>@lang('Select Type')</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="dr_cr">@lang('Account Entry')</label>
                                    <select class="form-control" name="dr_cr" id="dr_cr" required>
                                        <option selected disabled value>@lang('Select Entry')</option>
                                        <option value="1">@lang('Debit')</option>
                                        <option value="2">@lang('Credit')</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="inbound">@lang('Inbound Fund Entry')</label>
                                    <select class="form-control" name="inbound" id="inbound" required>
                                        <option selected disabled value>@lang('Select Entry')</option>
                                        <option value="1">@lang('Debit')</option>
                                        <option value="2">@lang('Credit')</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="outbound">@lang('Outbound Fund Entry')</label>
                                    <select class="form-control" name="outbound" id="outbound" required>
                                        <option selected disabled value>@lang('Select Entry')</option>
                                        <option value="1">@lang('Debit')</option>
                                        <option value="2">@lang('Credit')</option>
                                    </select>
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
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="description">@lang('Description')</label>
                                    <input class="form-control" name="description" type="text" id="description" placeholder="@lang('Description')" required>
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
    
    <button type="button" class="btn btn-outline--primary h-45 me-2 accountModalBtn" data-modal_title="@lang('Create New Account')">
        <i class="las la-plus"></i>@lang('Add New')
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
            let accounts = JSON.parse('{!! json_encode($COAs->toArray()) !!}').sort();
            let types = null;
            $(document).ready(() => {
                
                types = JSON.parse('{!! @$types !!}');
                types.categories = {"1": [], "2": [], "3": [], "4": [], "5": []};
                
                $.each(types, function(index, element) {
                    types.categories[element.category].push(element);
                    delete types[index];
                });
                
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
            
            function rebuildOptions(){
                
            }

        })(jQuery);
    </script>
@endpush

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
                                    <th>@lang('Transaction')</th>
                                    <th>@lang('Account')</th>
                                    <th>@lang('Debit')</th>
                                    <th>@lang('Credit')</th>
                                    <th>@lang('Date')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($transactions as $transaction)
                                    <tr>
                                        <td>{{ __($loop->index + 1) }}</td>
                                        <td>
                                            <a href="{{ route('staff.transactions.index', ['search' => @$transaction->transaction_id]) }}">{{ @$transaction->transaction_id }}</a>
                                        </td>
                                        <td>{{ @$transaction->coa->name }}</td>
                                        <td>{{ $general->cur_sym }}{{ @$transaction->dr_cr == 1 ? showAmount(@$transaction->amount) : showAmount(0) }}</td>
                                        <td>{{ $general->cur_sym }}{{ @$transaction->dr_cr == 2 ? showAmount(@$transaction->amount) : showAmount(0) }}</td>
                                        <td>{{ @$transaction->created_at }}</td>
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
                @if ($transactions->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($transactions) }}
                    </div>
                @endif
            </div>
        </div>
    </div>

@endsection

@push('breadcrumb-plugins')
    <form method="GET" action="{{ route('staff.journal.index') }}" class="row" id="filter">
        <div class="col-6">
            <x-search-date-field dateSearch="yes" />
        </div>
        <div class="col-6">
            <div class="input-group justify-content-end">
                <select class="form-control" name="coa" id="coa">
                    <option selected disabled value>@lang('Select Account')</option>
                    @foreach($COA as $coa)
                        <option value="{{ @$coa->id }}" {{ request()->coa == @$coa->id ? 'selected' : ''}}>{{ @$coa->name }}</option>
                    @endforeach
                </select>
                <button class="btn btn--primary input-group-text"><i class="fa fa-search"></i></button>
            </div>
        </div>
    </form>
@endpush

@push('script')
    <script>
    let types = null;
        "use strict";
        (function($) {
            
            // $(document).ready(() => {
                
            //     types = JSON.parse('{!! @$types !!}');
            //     types.categories = {"1": [], "2": [], "3": [], "4": [], "5": []};
                
            //     $.each(types, function(index, element){
            //         types.categories[element.type].push(element);
            //         delete types[index];
            //     });
                
            //     $("#category").on("change", function(e) {
            //         $(`#type option:not(:first)`).remove();
            //         $.each(types.categories[e.target.value], function(index, element){
            //             $("<option>").val(element.id).text(element.name).appendTo("#type");
            //         });
            //     });
            // });
            
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

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
                                    <th>@lang('Account')</th>
                                    <th>@lang('Quantity')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($products as $product)
                                    <tr>
                                        <td>{{ @$loop->index + 1 }}</td>
                                        <td>{{ @$product->name }}</td>
                                        <td>{{ @$product->account->name }}</td>
                                        <td>{{ @$product->stock->quantity ?? 0 }}</td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-outline--primary modalBtn edit"
                                            data-id="{{ $product->id }}" data-name="{{ $product->name }}" data-account_id="{{ $product->product_account }}"
                                            data-modal_title="@lang('Edit Product')">
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
                @if ($products->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($products) }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Create Update Modal -->
    <div class="modal fade" id="modal">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form action="{{ route('staff.murabaha.save.product') }}" method="POST">
                    @csrf
                    <input name="id" type="hidden">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="name">@lang('Name')</label>
                                    <input class="form-control" name="name" type="text" id="name" value="{{ old('name') }}" placeholder="@lang('Name')" required>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="account">@lang('Product Account')</label>
                                    <select class="form-control" name="account" id="account" required>
                                        <option selected disabled value>@lang('Select Product Account')</option>
                                        @foreach ($accounts as $account)
                                            <option value="{{ $account->id }}" {{ old('account') == $account->id ? 'selected' : ''}}>{{ @$account->name }}</option>
                                        @endforeach
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
@endsection

@push('breadcrumb-plugins')
    <button type="button" class="btn btn-outline--primary h-45 me-2 modalBtn" data-modal_title="@lang('Create New Product')">
        <i class="las la-plus"></i>@lang('Add New')
    </button>
    <form class="d-inline">
        <div class="input-group justify-content-end">
            <input type="text" name="search" value="{{ request()->search ?? '' }}" class="form-control bg--white" placeholder="@lang('Name')">
            <button class="btn btn--primary input-group-text"><i class="fa fa-search"></i></button>
        </div>
    </form>
@endpush

@push('script')
    <script>
    let types = null;
        "use strict";
        (function($) {
            
            $(document).ready(() => {
                
            });
            
            $(".modalBtn").on('click', function(e) {
                $("#modal").modal('show');
                $(".modal-title").text($(e.currentTarget).data("modal_title"));
                if($(e.currentTarget).hasClass("edit")){
                    let account = $(e.currentTarget).data('account_id') ?? 0;
                    
                    $("[name=id]").val($(e.currentTarget).data("id"));
                    $('#name').val($(e.currentTarget).data('name'));
                    $(`#account option[value='${account}']`).prop('selected', true).trigger('change');
                }else{
                    $("[name=id]").val("0");
                    $(".modal :input:not([type=hidden]):not(:button)").val('');
                }
            });

        })(jQuery);
    </script>
@endpush
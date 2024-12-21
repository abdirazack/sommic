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
                                    <th>@lang('Product')</th>
                                    <th>@lang('Supplier')</th>
                                    <th>@lang('Description')</th>
                                    <th>@lang('Price')</th>
                                    <th>@lang('Quantity')</th>
                                    <th>@lang('Purchase Date')</th>
                                    <th>@lang('Delivery Date')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($purchases as $purchase)
                                    <tr>
                                        <td>{{ @$loop->index + 1 }}</td>
                                        <td>{{ @$purchase->product->name }}</td>
                                        <td>{{ @$purchase->supplier->name }}</td>
                                        <td>{{ @$purchase->description }}</td>
                                        <td>{{ @$general->cur_sym . showAmount(@$purchase->price) }}</td>
                                        <td>{{ @$purchase->quantity }}</td>
                                        <td>{{ @$purchase->purchase_date }}</td>
                                        <td>{{ @$purchase->delivery_date }}</td>
                                        
                                        <td>
                                            <button type="button" class="btn btn-sm btn-outline--primary modalBtn edit"
                                            data-id="{{ $purchase->id }}" data-product_id="{{ $purchase->product_id }}" data-supplier_id="{{ $purchase->supplier_id }}"
                                            data-unit_price="{{ $purchase->unit_price }}" data-quantity="{{ $purchase->quantity }}" data-discount="{{ $purchase->discount }}"
                                            data-tax="{{ $purchase->tax }}" data-expenses="{{ $purchase->expenses }}" data-description="{{ $purchase->description }}"
                                            data-purchase_date="{{ $purchase->purchase_date }}" data-delivery_date="{{ $purchase->delivery_date }}"
                                            data-modal_title="@lang('Edit Purchase')">
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
                @if ($purchases->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($purchases) }}
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
                <form action="{{ route('staff.murabaha.save.purchase') }}" method="POST">
                    @csrf
                    <input name="id" type="hidden">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="product_id">@lang('Product')</label>
                                    <select class="form-control" name="product_id" id="product_id" required>
                                        <option selected disabled value>@lang('Select Product')</option>
                                        @foreach ($products as $product)
                                            <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : ''}}>{{ @$product->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="supplier_id">@lang('Supplier')</label>
                                    <select class="form-control" name="supplier_id" id="supplier_id" required>
                                        <option selected disabled value>@lang('Select Supplier')</option>
                                        @foreach ($suppliers as $supplier)
                                            <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : ''}}>{{ @$supplier->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="unit_price">@lang('Unit Price')</label>
                                    <input class="form-control" name="unit_price" type="number" id="unit_price" value="{{ old('unit_price') }}" placeholder="@lang('Unit Price')" step="any" required>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="quantity">@lang('Quantity')</label>
                                    <input class="form-control" name="quantity" type="number" id="quantity" value="{{ old('quantity') }}" placeholder="@lang('Quantity')" required>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="discount">@lang('Discount')</label>
                                    <input class="form-control" name="discount" type="number" id="discount" value="{{ old('discount', '0') }}" placeholder="@lang('Discount')" step="any">
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="tax">@lang('Tax')</label>
                                    <input class="form-control" name="tax" type="number" id="tax" value="{{ old('tax', '0') }}" placeholder="@lang('Tax')" step="any">
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="expenses">@lang('Expenses')</label>
                                    <input class="form-control" name="expenses" type="number" id="expenses" value="{{ old('expenses', '0') }}" placeholder="@lang('Expenses')" step="any">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="purchase_date">@lang('Purchase Date')</label>
                                    <input class="form-control" name="purchase_date" type="date" id="purchase_date" value="{{ old('purchase_date') }}" placeholder="@lang('Purchase Date')" required>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="delivery_date">@lang('Delivery Date')</label>
                                    <input class="form-control" name="delivery_date" type="date" id="delivery_date" value="{{ old('delivery_date') }}" placeholder="@lang('Delivery Date')" required>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="description">@lang('Description')</label>
                                    <input class="form-control" name="description" type="text" id="description" value="{{ old('description') }}" placeholder="@lang('Description')">
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
    <button type="button" class="btn btn-outline--primary h-45 me-2 modalBtn" data-modal_title="@lang('Create New Purchase')">
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
    let types = null;
        "use strict";
        (function($) {
            
            $(document).ready(() => {
                
            });
            
            $(".modalBtn").on('click', function(e) {
                $("#modal").modal('show');
                $(".modal-title").text($(e.currentTarget).data("modal_title"));
                if($(e.currentTarget).hasClass("edit")){
                    
                    $("[name=id]").val($(e.currentTarget).data("id"));
                    $(".modal :input:not([type=hidden]):not(:button)").each( function (){
                        $(`#${this.name}`).val($(e.currentTarget).data(`${this.name}`));
                    });
                }else{
                    $("[name=id]").val("0");
                    $(".modal :input:not([type=hidden]):not(:button)").val('');
                }
            });

        })(jQuery);
    </script>
@endpush

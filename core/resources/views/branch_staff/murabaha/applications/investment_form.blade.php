@extends('branch_staff.layouts.app')
@section('panel')
    <form id="form" method="POST" action="{{ $action }}" enctype="multipart/form-data">
        @csrf
        <div class="card b-radius--10 mb-3">
            <div class="card-header">
                <h3 class="card-title text-center">@lang('Account Information')</h3>
            </div>
            <div class="card-body">
                <div class="row gx-2">
                    
                    <div class="col-xl-10 col-sm-10">
                        <div class="form-group">
                            <label for="search_account">@lang('Customer Account')</label>
                            <input class="form-control" name="search_account" type="text" id="search_account" required>
                            <input name="customer_account" type="hidden" id="customer_account">
                            <input name="application_id" type="hidden" value="{{ @$application_id }}">
                        </div>
                    </div>
                    
                    <div class="col-xl-1 col-sm-1">
                        <div class="form-group">
                            <label for=""></label>
                            <button class="btn btn--info py-2 my-1 form-control" type="button" id="find_account">
                                <i class="fs-4 la la-search"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="col-xl-1 col-sm-1">
                        <div class="form-group">
                            <label for=""></label>
                            <button class="btn btn--success py-2 my-1 form-control" type="button" id="link_account" disabled>
                                <i class="fs-4 la la-link"></i>
                            </button>
                        </div>
                    </div>

                </div>
                <div class="row error" style="display:none">
                    <small class="text-danger">Account not found!</small>
                </div>
                <div class="row customers" style="display:none">
                    <h4>Customers: </h4>
                    <div id="customers"></div>
                </div>
            </div>
        </div>
        
        <div class="card b-radius--10 mb-3">
            <div class="card-header">
                <h3 class="card-title text-center">@lang('Product Details')</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-xl-6 col-sm-6">
                        <div class="form-group">
                            <label for="product">@lang('Product')</label>
                            <select class="form-control" name="product" id="product" required>
                                <option selected disabled value>@lang('Select Product')</option>
                                @foreach($products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-xl-6 col-sm-6">
                        <div class="form-group">
                            <label for="purchase_order">@lang('Purchase Order')</label>
                            <select name="purchase_order" class="form-control" id="purchase_order" required>
                                <option selected disabled value>@lang('Select Purchase Order')</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="table-responsive--sm table-responsive">
                            <table class="table table--light style--two">
                                <thead>
                                    <tr>
                                        <th>@lang('Description')</th>
                                        <th>@lang('Supplier')</th>
                                        <th>@lang('Unit Price')</th>
                                        <th>@lang('Quantity')</th>
                                        <th>@lang('Discount')</th>
                                        <th>@lang('Tax')</th>
                                        <th>@lang('Expenses')</th>
                                        <th>@lang('Price')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="purchase_detail" id="description"></td>
                                        <td class="purchase_detail" id="supplier"></td>
                                        <td class="purchase_detail" id="unit_price"></td>
                                        <td class="purchase_detail" id="quantity"></td>
                                        <td class="purchase_detail" id="discount"></td>
                                        <td class="purchase_detail" id="tax"></td>
                                        <td class="purchase_detail" id="expenses"></td>
                                        <td class="purchase_detail" id="price"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card b-radius--10 mb-3">
            <div class="card-header">
                <h3 class="card-title text-center">@lang('Murabaha Details')</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    
                    <div class="col-xl-3 col-sm-3">
                        <div class="form-group">
                            <label for="profit_rate">@lang('Profit Margin (%)')</label>
                            <input class="form-control" name="profit_rate" type="number" id="profit_rate" required>
                        </div>
                    </div>
                    
                    <div class="col-xl-3 col-sm-3">
                        <div class="form-group">
                            <label for="installments">@lang('Installments')</label>
                            <input class="form-control" name="installments" type="number" id="installments" required>
                        </div>
                    </div>
                    
                    <div class="col-xl-6 col-sm-6">
                        <div class="form-group">
                            <label for="first_installment">@lang('First Installment Date')</label>
                            <input class="form-control" name="first_installment" type="date" id="first_installment" required>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
        
        <div class="card b-radius--10 mb-3">
            <div class="card-header">
                <h3 class="card-title text-center">@lang('Murabaha Analysis')</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    
                    <div class="px-5">
                        
                        <div class="row gx-5 px-5 py-2">
                            <div class="col-xl-6 col-sm-6 ps-5">
                                <div class="list-group list-group-flush ps-5">
                                    <div class="list-group-item d-flex justify-content-between flex-wrap border-bottom border-warning">
                                        <h6 class="text-muted">@lang('Finance Amount')</h6>
                                        <span id="_finance_amount">$0.00</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-6 col-sm-6 pe-5">
                                <div class="list-group list-group-flush pe-5">
                                    <div class="list-group-item d-flex justify-content-between flex-wrap border-bottom border-warning">
                                        <h6 class="text-muted">@lang('Total Payment')</h6>
                                        <span id="_total_payment">$0.00</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                         <div class="row gx-5 px-5 py-2">
                            <div class="col-xl-6 col-sm-6 ps-5">
                                <div class="list-group list-group-flush ps-5">
                                    <div class="list-group-item d-flex justify-content-between flex-wrap border-bottom border-warning">
                                        <h6 class="text-muted">@lang('Profit Rate')</h6>
                                        <span id="_profit_rate">0%</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-6 col-sm-6 pe-5">
                                <div class="list-group list-group-flush pe-5">
                                    <div class="list-group-item d-flex justify-content-between flex-wrap border-bottom border-warning">
                                        <h6 class="text-muted">@lang('Total Profit')</h6>
                                        <span id="_total_profit">$0.00</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row gx-5 px-5 py-2">
                            <div class="col-xl-6 col-sm-6 ps-5">
                                <div class="list-group list-group-flush ps-5">
                                    <div class="list-group-item d-flex justify-content-between flex-wrap border-bottom border-warning">
                                        <h6 class="text-muted">@lang('Months of Financing')</h6>
                                        <span id="_months_of_financing">0</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-6 col-sm-6 pe-5">
                                <div class="list-group list-group-flush pe-5">
                                    <div class="list-group-item d-flex justify-content-between flex-wrap border-bottom border-warning">
                                        <h6 class="text-muted">@lang('Monthly Payment')</h6>
                                        <span id="_monthly_payment">$0.00</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="analysis">
                        <div class="row">
                            <div class="col-12 my-2">
                                <h4 class="text-center">Installments</h4>
                            </div>
                            <div class="col-12">
                                <div class="table-responsive--sm table-responsive">
                                    <table class="table table--light style--two">
                                        <thead>
                                            <tr>
                                                <th>@lang('Installment')</th>
                                                <th>@lang('Date')</th>
                                                <th>@lang('Principle')</th>
                                                <th>@lang('Profit')</th>
                                                <th>@lang('Payment')</th>
                                                <th>@lang('Balance')</th>
                                            </tr>
                                        </thead>
                                        <tbody class="installments">
                                            <tr id="installmentX" style="display:none">
                                                <td class="installment_id"></td>
                                                <td class="installment_date"></td>
                                                <td class="installment_principle"></td>
                                                <td class="installment_profit"></td>
                                                <td class="installment_payment"></td>
                                                <td class="installment_balance"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                    
                </div>
            </div>
        </div>
        
        <button class="btn btn--primary w-100 h-45" type="submit">@lang('Submit')</button>
    </form>
    
    
@endsection

@push('script')
    <script>
        "use strict";
        (function($) {
            let data = {};
            let cur_sym = '{{ $general->cur_sym }}';
            $(document).ready(() => {
                $('#product, #purchase_order').val('').trigger('change');
                $('#customer_account').val($('#search_account').val());
                $('#profit_rate, #installments').trigger('keyup');
            });

            $('#product').on('change', function(e) {

                var url = '{{ route('staff.murabaha.purchase.orders') }}';
                var value = $(this).val();
                var token = '{{ csrf_token() }}';

                var data = {
                    product_id: value,
                    _token: token
                };

                $.post(url, data, function(response) {
                    let _response = JSON.parse(response).data;
                    if(Object.keys(_response).length > 0){
                        $(`#purchase_order option:not(:first)`).remove();
                        $.each(_response, function(index, element){
                            let option = $('<option>');
                            $(option).val(element.id).text(element.value).data(element).appendTo('#purchase_order');
                        });
                        $('#purchase_order').val('').trigger('change');
                    }else{
                        $(`#purchase_order option:not(:first)`).remove().val('').trigger('change');
                    }
                });
            });
            
            $('#purchase_order').on('change', function(e) {

                let data = $('#purchase_order option:selected').data();
                
                if(Object.keys(data).length > 0){
                    $('.purchase_detail').each(function(){
                        $(this).text(data[this.id]);
                    });
                }else{
                    $('.purchase_detail').empty();
                }
            });
            
            $('#find_account').on('click', function(e) {
                var url = '{{ route('staff.account.find') }}';
                var value = $('#search_account').val();
                var token = '{{ csrf_token() }}';

                var data = {
                    account_number: value,
                    _token: token
                };

                $.post(url, data, function(response) {
                    let _response = JSON.parse(response).data;
                    if(_response.success) {
                        $('#link_account').attr('disabled', false);
                        $('.customers').show();
                        $('#customers').empty();
                        $('.error').hide();
                        $.each(_response.customers, function(index, element) {
                            if(index > 0){
                                $('#customers').append(', ');
                            }
                            $('<a>', {
                                'href': '{{ route('staff.customer.detail', '') }}' + '/' + element.id,
                                'target': '_blank',
                                'text': element.name
                            })
                            .appendTo($('<span>')).appendTo('#customers');
                        });
                        $('#link_account').data('loaded', false);
                        
                    }else{
                        $('#customer_account').val('');
                        $('#customers').empty().hide();
                        $('.error').show();
                    }
                    
                });
            });
            
            $('#link_account').on('click', function() {
                let is_loaded = $(this).data('loaded');
                let customer_account = !is_loaded ? $('#search_account').val() : '';
                
                $('#customer_account').val(customer_account);
                $('#find_account, #search_account').attr('disabled', !is_loaded);
                $('#link_account i').removeClass(is_loaded ? 'la-unlink' : 'la-link').addClass(is_loaded ? 'la-link' : 'la-unlink');
                $('#link_account').removeClass(is_loaded ? 'btn--danger' : 'btn--success').addClass(is_loaded ? 'btn--success' : 'btn--danger');
                $(this).attr('disabled', is_loaded);
                $(this).data('loaded', !is_loaded);
                
            });
            
            $('#profit_rate, #installments').on('keyup', function() {
                calculate();
            });
            
            $('#first_installment').on('change', function() {
                $('.installments > :not(#installmentX)').remove();
                showInstallments();
            });
            
            function calculate() {
                let amount = parseFloat($('#purchase_order option:selected').data('price'));
                let profit_rate = parseFloat($('#profit_rate').val()) / 100;
                let installments = parseInt($('#installments').val());
                
                data.amount = isNaN(amount) ? 0 : amount;
                data.profit_rate = isNaN(profit_rate) ? 0 : profit_rate;
                data.installments = isNaN(installments) ? 0 : installments;
                
                data.principle = +(data.amount / data.installments).toFixed(2);
                data.profit = (data.principle * data.profit_rate);
                data.installment_payment = ((data.principle * data.profit_rate) + data.principle).toFixed(2);
                data.total_payment = (data.installment_payment * data.installments).toFixed(2);
                data.total_profit = (data.total_payment - data.amount).toFixed(2);
                
                $('#_finance_amount').text(cur_sym + data.amount);
                $('#_total_payment').text(cur_sym + data.total_payment);
                $('#_profit_rate').text((data.profit_rate * 100) + '%');
                $('#_total_profit').text(cur_sym + data.total_profit);
                $('#_months_of_financing').text(data.installments);
                $('#_monthly_payment').text(cur_sym + data.installment_payment);
                
            }
            
            function showInstallments() {
                let installments = parseInt($('#installments').val());
                let first_installment = $('#first_installment').val();
                let dateValid = moment(first_installment).isValid()
                let dateNotPassed = moment(first_installment).diff(moment(), 'days') >= 0;
                
                let first_installment_date = dateValid && dateNotPassed ? moment(first_installment) : moment();
                let installment_date = first_installment_date;
                let balance = 0.0;
                
                for(let i = 1; i <= installments; i++){
                    
                    balance += parseFloat(data.installment_payment);
                    
                    $('#installmentX').clone(true)
                    .find('.installment_id').each(function () {
                        $(this).text(i);
                    }).end()
                    .find('.installment_date').each(function () {
                        $(this).text(installment_date.format('DD-MM-YYYY'));
                    }).end()
                    .find('.installment_principle').each(function () {
                        $(this).text(cur_sym + data.principle);
                    }).end()
                    .find('.installment_profit').each(function () {
                        $(this).text(cur_sym + data.profit.toFixed(2));
                    }).end()
                    .find('.installment_payment').each(function () {
                        $(this).text(cur_sym + data.installment_payment);
                    }).end()
                    .find('.installment_balance').each(function () {
                        $(this).text(cur_sym + balance.toFixed(2));
                    }).end()
                    .removeAttr('id')
                    .show()
                    .appendTo('.installments');
                    
                    installment_date = installment_date.add(1, 'months');
                }
            }

        })(jQuery);
    </script>
@endpush

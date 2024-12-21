@extends('branch_staff.layouts.app')
@section('panel')
    <form id="form" method="POST" action="{{ $action }}" enctype="multipart/form-data">
        @csrf
        <div class="card b-radius--10 mb-3">
            <div class="card-header">
                <h3 class="card-title text-center">@lang('Personal Details')</h3>
            </div>
            <div class="card-body">
                <input name="person_id" type="hidden" id="person_id" value="0">
                <div class="row" id="person">
                    <div class="col-md-6 col-xl-4">
                        <div class="form-group">
                            <label for="image">@lang('Image')</label>
                            <div class="image-upload">
                                <div class="thumb">
                                    <div class="avatar-preview">
                                        <div class="profilePicPreview" style="background-image: url({{ getImage(getFilePath('userProfile') . '/', getFileSize('userProfile'), true) }})">
                                            <button class="remove-image" type="button"><i class="fa fa-times"></i></button>
                                        </div>
                                    </div>

                                    <div class="avatar-edit">
                                        <input class="profilePicUpload" name="image" id="image" type="file" accept=".pdf, .png, .jpg, .jpeg" required>

                                        <label class="bg--primary" for="image">@lang('Upload Image')</label>

                                        <small class="mt-2">@lang('Supported files'): <b>@lang('jpeg'), @lang('jpg'), @lang('png').</b> @lang('Image will be resized into '){{ getFileSize('userProfile') }} @lang('px') </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-8">
                        <div class="row">

                            <div class="col-xl-9 col-sm-9">
                                <div class="form-group">
                                    <label for="fullname">@lang('Full Name')</label>
                                    <input class="form-control" name="fullname" type="text" id="fullname" value="{{ old('fullname') }}" required>
                                </div>
                            </div>
                            
                            <div class="col-xl-3 col-sm-3">
                                <div class="form-group">
                                    <label for="gender">@lang('Gender')</label>
                                    <select class="form-control" name="gender" id="gender" required>
                                        <option selected disabled value>@lang('Select Gender')</option>
                                        <option value="1" {{ old('gender') == '1' ? 'selected' : ''}}>@lang('Male')</option>
                                        <option value="2" {{ old('gender') == '2' ? 'selected' : ''}}>@lang('Female')</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-xl-4 col-sm-4">
                                <div class="form-group">
                                    <label for="country">@lang('Nationality')</label>
                                    <select class="form-control" name="country" id="country" required>
                                        <option selected disabled value>@lang('Select Nationality')</option>
                                        @foreach ($countries as $key => $country)
                                            <option data-mobile_code="{{ $country->dial_code }}" value="{{ $key }}" {{ old('country') == $key ? "selected" : ($key == "SO" ? "selected" : "" ) }}>{{ __($country->country) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-xl-4 col-sm-4">
                                <div class="form-group">
                                    <label for="pob">@lang('Place of Birth')</label>
                                    <select class="form-control" name="pob" id="pob" required>
                                        <option selected disabled value>@lang('Select Place of Birth')</option>
                                        @foreach ($countries as $key => $country)
                                            <option value="{{ $key }}" {{ old('pob') == $key ? "selected" : ($key == "SO" ? "selected" : "" ) }}>{{ __($country->country) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-xl-4 col-sm-4">
                                <div class="form-group">
                                    <label for="dob">@lang('Date of Birth')</label>
                                    <input class="form-control" name="dob" type="date" id="dob" value="{{ old('dob') }}" required>
                                </div>
                            </div>
                            
                            <div class="col-xl-4 col-sm-4">
                                <div class="form-group">
                                    <label for="region">@lang('Region')</label>
                                    <input class="form-control" name="region" type="text" id="region" value="{{ old('region') }}" required>
                                </div>
                            </div>
                            
                            <div class="col-xl-4 col-sm-4">
                                <div class="form-group">
                                    <label for="city">@lang('City')</label>
                                    <input class="form-control" name="city" type="text" id="city" value="{{ old('city') }}" required>
                                </div>
                            </div>
                            
                            <div class="col-xl-4 col-sm-4">
                                <div class="form-group">
                                    <label for="address">@lang('Address')</label>
                                    <input class="form-control" name="address" type="text" id="address" value="{{ old('address') }}" required>
                                </div>
                            </div>
                            
                            <div class="col-xl-4 col-sm-4">
                                <div class="form-group">
                                    <label for="mobile">@lang('Mobile Number')</label>
                                    <div class="input-group">
                                        <select class="input-group-text mobile-code" name="mobile_code" id="mobile-code" required>
                                            @foreach ($countries as $key => $country)
                                                <option value="{{ $country->dial_code }}"{{ old('mobile_code') == $country->dial_code ? "selected" : ($key == "SO" ? "selected" : "" ) }}>{{ "+".$country->dial_code }}</option>
                                            @endforeach
                                        </select>
                                        <input class="form-control checkUser" name="mobile" type="number" id="mobile" value="{{ old('mobile') }}" required>
                                        <small class="text-danger mobileExist"></small>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-4 col-sm-4">
                                <div class="form-group">
                                    <label for="email">@lang('E-Mail Address')</label>
                                    <input class="form-control" name="email" type="email" id="email" value="{{ old('email') }}" required>
                                </div>
                            </div>
                            
                            <div class="col-xl-4 col-sm-4">
                                <div class="form-group">
                                    <label for="marital">@lang('Marital Status')</label>
                                    <select class="form-control" name="marital" id="marital" required>
                                        <option selected disabled value>@lang('Select Marital Status')</option>
                                        <option value="1" {{ old('marital') == '1' ? 'selected' : '' }}>@lang('Single')</option>
                                        <option value="2" {{ old('marital') == '2' ? 'selected' : '' }}>@lang('Married')</option>
                                        <option value="3" {{ old('marital') == '3' ? 'selected' : '' }}>@lang('Divorcee')</option>
                                        <option value="4" {{ old('marital') == '4' ? 'selected' : '' }}>@lang('Widow')</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-xl-6 col-sm-6">
                                <div class="form-group">
                                    <label for="employment_status">@lang('Employment Status')</label>
                                    <select class="form-control" name="employment_status" id="employment_status" required>
                                        <option selected disabled value>@lang('Select Employment Status')</option>
                                        <option value="1" {{ old('employment_status') == '1' ? 'selected' : '' }}>@lang('Student')</option>
                                        <option value="2" {{ old('employment_status') == '2' ? 'selected' : '' }}>@lang('Self-Employed')</option>
                                        <option value="3" {{ old('employment_status') == '3' ? 'selected' : '' }}>@lang('Employed')</option>
                                        <option value="4" {{ old('employment_status') == '4' ? 'selected' : '' }}>@lang('Unemployed')</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-xl-6 col-sm-6">
                                <div class="form-group">
                                    <label for="employment_detail">@lang('Detail')</label>
                                    <input class="form-control" name="employment_detail" type="text" id="employment_detail" value="{{ old('employment_detail') }}" required>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card b-radius--10 mb-3">
            <div class="card-header">
                <h3 class="card-title text-center">@lang('Next Of Kin Details')</h3>
            </div>
            <div class="card-body">
                <div class="row" id="nok">
                    <div class="nok1 col-6 border-end">
                        <h4 class="my-3">@lang('Next of Kin 1')</h4>
                        <div class="row">
                            
                            <div class="col-xl-4 col-sm-4">
                                <div class="form-group">
                                    <label for="nok1_name">@lang('Name')</label>
                                    <input class="form-control" name="nok1_name" type="text" id="nok1_name" value="{{ old('nok1_name') }}" required>
                                </div>
                            </div>
                            
                            <div class="col-xl-4 col-sm-4">
                                <div class="form-group">
                                    <label for="nok1_relation">@lang('Relationship')</label>
                                    <select class="form-control" name="nok1_relation" id="nok1_relation" required>
                                        <option selected disabled value>@lang('Select Relationship Type')</option>
                                        <option value="1" {{ old('nok1_relation') == '1' ? 'selected' : '' }}>@lang('Parent')</option>
                                        <option value="2" {{ old('nok1_relation') == '2' ? 'selected' : '' }}>@lang('Sibling')</option>
                                        <option value="3" {{ old('nok1_relation') == '3' ? 'selected' : '' }}>@lang('Spouse')</option>
                                        <option value="4" {{ old('nok1_relation') == '4' ? 'selected' : '' }}>@lang('Child')</option>
                                        <option value="5" {{ old('nok1_relation') == '5' ? 'selected' : '' }}>@lang('Relative')</option>
                                        <option value="6" {{ old('nok1_relation') == '6' ? 'selected' : '' }}>@lang('Beneficiary')</option>
                                        <option value="7" {{ old('nok1_relation') == '7' ? 'selected' : '' }}>@lang('Friend')</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-xl-4 col-sm-4">
                                <div class="form-group">
                                    <label for="nok1_mobile">@lang('Mobile Number')</label>
                                    <input class="form-control" name="nok1_mobile" type="text" id="nok1_mobile" value="{{ old('nok1_mobile') }}" required>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                    
                    <div class="nok2 col-6">
                        <h4 class="my-3">@lang('Next of Kin 2')</h4>
                            <div class="row">
                            
                            <div class="col-xl-4 col-sm-4">
                                <div class="form-group">
                                    <label for="nok2_name">@lang('Name')</label>
                                    <input class="form-control" name="nok2_name" type="text" id="nok2_name" value="{{ old('nok2_name') }}" required>
                                </div>
                            </div>
                            
                            <div class="col-xl-4 col-sm-4">
                                <div class="form-group">
                                    <label for="nok2_relation">@lang('Relationship')</label>
                                    <select class="form-control" name="nok2_relation" id="nok2_relation" required>
                                        <option selected disabled value>@lang('Select Relationship Type')</option>
                                        <option value="1" {{ old('nok2_relation') == '1' ? 'selected' : '' }}>@lang('Parent')</option>
                                        <option value="2" {{ old('nok2_relation') == '2' ? 'selected' : '' }}>@lang('Sibling')</option>
                                        <option value="3" {{ old('nok2_relation') == '3' ? 'selected' : '' }}>@lang('Spouse')</option>
                                        <option value="4" {{ old('nok2_relation') == '4' ? 'selected' : '' }}>@lang('Child')</option>
                                        <option value="5" {{ old('nok2_relation') == '5' ? 'selected' : '' }}>@lang('Relative')</option>
                                        <option value="6" {{ old('nok2_relation') == '6' ? 'selected' : '' }}>@lang('Beneficiary')</option>
                                        <option value="7" {{ old('nok2_relation') == '7' ? 'selected' : '' }}>@lang('Friend')</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-xl-4 col-sm-4">
                                <div class="form-group">
                                    <label for="nok2_mobile">@lang('Mobile Number')</label>
                                    <input class="form-control" name="nok2_mobile" type="text" id="nok2_mobile" value="{{ old('nok2_mobile') }}" required>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card b-radius--10 mb-3">
            <div class="card-header">
                <h3 class="card-title text-center">@lang('Account Details')</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    
                    <div class="col-xl-4 col-sm-4">
                        <div class="form-group">
                            <label for="account_type">@lang('Account Type')</label>
                            <select class="form-control" name="account_type" id="account_type" required>
                                <option selected disabled value>@lang('Select Account Type')</option>
                                <option value="1" {{ old('account_type') == '1' ? 'selected' : '' }}>@lang('Saving Account')</option>
                                <option value="2" {{ old('account_type') == '2' ? 'selected' : '' }}>@lang('Current Account')</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-xl-4 col-sm-4">
                        <div class="form-group">
                            <label for="withdraw_amount_limit">@lang('Daily Withdrawal Amount Limit')</label>
                            <input class="form-control" name="withdraw_amount_limit" type="text" id="withdraw_amount_limit" value="{{ old('withdraw_amount_limit') }}" required>
                        </div>
                    </div>
                    
                    <div class="col-xl-4 col-sm-4">
                        <div class="form-group">
                            <label for="withdraw_freq_limit">@lang('Daily Withdrawal Frequency Limit')</label>
                            <input class="form-control" name="withdraw_freq_limit" type="text" id="withdraw_freq_limit" value="{{ old('withdraw_freq_limit') }}" required>
                        </div>
                    </div>
                    
                    <div class="col-xl-4 col-sm-4">
                        <div class="form-check">
                            <input class="form-check-input" name="ebank" type="checkbox" id="ebank" {{ old('') != null ? 'checked' : ''}}>
                            <label class="form-check-label" for="ebank">
                                @lang('Access to E-Banking')
                            </label>
                        </div>
                    </div>
                    
                    <div class="col-xl-4 col-sm-4">
                        <div class="form-check">
                            <input class="form-check-input" name="cheque" type="checkbox" id="cheque" {{ old('') != null ? 'checked' : ''}}>
                            <label class="form-check-label" for="cheque">
                                @lang('Access to Cheque Book')
                            </label>
                        </div>
                    </div>
                    
                    <div class="col-xl-4 col-sm-4">
                        <div class="form-check">
                            <input class="form-check-input" name="wallet" type="checkbox" id="wallet" {{ old('') != null ? 'checked' : ''}}>
                            <label class="form-check-label" for="wallet">
                                @lang('Access to Mobile Wallet')
                            </label>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
        
        <div class="card b-radius--10 mb-3">
            <div class="card-header">
                <h3 class="card-title text-center">@lang('Attachable Documents')</h3>
            </div>
            <div class="card-body">
                <div class="row" id="attachables">
                    
                    <div class="col-xl-4 col-sm-4">
                        <div class="form-group">
                            <label for="doc_type">@lang('Document Type')</label>
                            <select name="doc_type" class="form-control" id="doc_type" required>
                                <option selected disabled value>@lang('Select Document Type')</option>
                                <option value="1" {{ old('doc_type') == '1' ? 'selected' : '' }} >@lang('Passport')</option>
                                <option value="2" {{ old('doc_type') == '2' ? 'selected' : '' }} >@lang('Government ID')</option>
                                <option value="3" {{ old('doc_type') == '3' ? 'selected' : '' }} >@lang('Driver\'s License')</option>
                                <option value="4" {{ old('doc_type') == '4' ? 'selected' : '' }} >@lang('Birth Certificate')</option>
                                <option value="5" {{ old('doc_type') == '5' ? 'selected' : '' }} >@lang('Student ID')</option>
                                <option value="6" {{ old('doc_type') == '6' ? 'selected' : '' }} >@lang('Court Document')</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-xl-4 col-sm-4">
                        <div class="form-group">
                            <label for="doc_expiry">@lang('Document Expiry Date')</label>
                            <input class="form-control" name="doc_expiry" type="date" id="doc_expiry" value="{{ old('doc_expiry') }}" required>
                        </div>
                    </div>
                    
                    <div class="col-xl-4 col-sm-4">
                        <div class="form-group">
                            <label for="doc_scan">@lang('Document Scan')</label>
                            <input class="form-control" name="doc_scan" type="file" accept=".pdf, .png, .jpg, .jpeg" id="doc_scan" required>
                        </div>
                    </div>
                    
                    <div class="col-xl-6 col-sm-6">
                        <div class="form-group">
                            <label for="application_scan">@lang('Application Form Scan')</label>
                            <input class="form-control" name="application_scan" type="file" accept=".pdf, .png, .jpg, .jpeg" id="application_scan" required>
                        </div>
                    </div>
                    
                    <div class="col-xl-6 col-sm-6">
                        <div class="form-group">
                            <label for="signature_scan">@lang('Signature Scan')</label>
                            <input class="form-control" name="signature_scan" type="file" accept=".pdf, .png, .jpg, .jpeg" id="signature_scan" required>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
        <button class="btn btn--primary w-100 h-45" type="submit">@lang('Submit')</button>
    </form>
    <div class="modal fade" id="viewModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button class="close" data-bs-dismiss="modal" type="button" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row justify-content-center">
                        <img id="modalImage" style="height: 200px; width: 200px">
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <label for="">Name</label>
                            <input class="form-control" name="name" type="text" id="name" readonly>
                            <input id="_person_id" type="hidden">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <label for="">Email</label>
                            <input class="form-control" name="email" type="text" id="email" readonly>
                        </div>
                    </div>
                    
                </div>
                <div class="modal-footer">
                    <button class="btn btn--primary" id="load">Load</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        "use strict";
        (function($) {
            let _response = null;
            var modal = $('#viewModal');
            
            $(document).ready(() => {
                let dialCode = $('select[name=country] :selected').data('mobile_code');
                
                $(`.mobile-code option[value='${dialCode}']`).prop('selected', true);
                
                $('select[name=country]').change(function() {
                    let dialCode = $('select[name=country] :selected').data('mobile_code');
                    $(`.mobile-code option[value='${dialCode}']`).prop('selected', true);
                });
            });

            $('#load').on('click', function() {
                if($(".mobileExist #clearCustomer").length == 0){
                    $(`.mobileExist`).html($(`.mobileExist`).html() + ` | <a href="#" id="clearCustomer">clear</a>`);
                }
                $("#person_id").val($('#_person_id').val());
                $("#image").attr({'disabled': true, 'required': false});
                $('#person *').filter(':input:not(:button):not(#image)').each(function(){
                    let id = this.id;
                    $(this).attr({'disabled': true, 'required': false});
                    $(`#person label[for=${id}]:not(.bg--primary)`).removeAttr('class');
                    if(_response.hasOwnProperty(this.name)){
                       $(this).val(_response[this.name]);
                    }else if(_response.misc.hasOwnProperty(this.name)){
                        $(this).val(_response.misc[this.name]);
                    }
                });
                $('#attachables *').filter(':input[name^=doc_]:not(:button)').each(function(){
                    let id = this.id;
                    $(this).attr({'disabled': true, 'required': false});
                    $(`#attachables label[for=${id}]`).removeAttr('class');
                    if(_response.hasOwnProperty(this.name)){
                       $(this).val(_response[this.name]);
                    }
                });
                
            });
            
            $('.mobileExist').on('click', '#viewCustomer', function () {
                var url = '{{ route('staff.customer.find') }}';
                var value = $('#mobile').val();
                var token = '{{ csrf_token() }}';
                
                var element = $(`.mobile-code option:selected`).text().substr(1);
                var mobile = `${element}${value}`;
                
                var data = {
                    mobile: mobile,
                    _token: token
                };
                
                $.post(url, data, function(response) {
                    _response = JSON.parse(response);
                    _response.fullname = _response.name;
                    _response.country = _response.misc.nationality;
                    _response.doc_type = _response.identifier_type;
                    _response.doc_expiry = _response.identifier_expiry_date;
                    delete _response.mobile;
                    
                    var image = $('#modalImage');
                    image.attr('src', "{{ 'https://somcommunitybank.com/' . getFilePath('userProfile') . '/' }}" + _response.misc.image);
                    $('#_person_id').val(_response.id);
                    $('#viewModal *').filter(':input').each(function(){
                        if(_response.hasOwnProperty(this.name)){
                            $(this).val(_response[this.name]);
                        }
                    });
                    modal.modal('show');
                });
            });
            
            $('.mobileExist').on('click', '#clearCustomer', function () {
                $('.mobileExist').empty();
                $("#person_id").val('0');
                $("#image").attr({'disabled': false, 'required': true});
                $('#person *').filter(':input:not(:button):not(#image)').each(function(){
                    let id = this.id;
                    $(this).attr({'disabled': false, 'required': true});
                    $(this).val('');
                    
                    $(`#person label[for=${id}]:not(.bg--primary)`).attr('class', 'required');
                });
                $('#attachables *').filter(':input[name^=doc_]:not(:button)').each(function(){
                    let id = this.id;
                    $(this).attr({'disabled': false, 'required': true});
                    $(this).val('');
                    $(`#attachables label[for=${id}]`).attr('class', 'required');
                });
            });

            $('.checkUser').on('focusout', function(e) {

                var url = '{{ route('user.checkUser') }}';
                var value = $(this).val();
                var token = '{{ csrf_token() }}';

                if ($(this).attr('name') == 'mobile') {
                    var element = $(`.mobile-code option:selected`).text().substr(1);
                    var mobile = `${element}${value}`;
                    
                    var data = {
                        mobile: mobile,
                        _token: token
                    };
                }

                $.post(url, data, function(response) {
                    if (response.data != false) {
                        $(`.${response.type}Exist`).html(`Mobile exists, <a href="#" id="viewCustomer">view</a>?`);
                    } else {
                        $(`.${response.type}Exist`).empty();
                    }
                });
            });

        })(jQuery);
    </script>
@endpush

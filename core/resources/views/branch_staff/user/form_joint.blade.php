@extends('branch_staff.layouts.app')
@section('panel')
    <form id="form" method="POST" action="{{ $action }}" enctype="multipart/form-data">
        @csrf
        <div id="persons">
            
        </div>
        
        <h5 class="small">Linked Customers: <span id="customers"></span></h5>
        <button class="btn btn--info px-5 py-2 mb-3" type="button" id="clone">Add Person</button>
        
        <div class="account">
            <div class="card b-radius--10 mb-3">
                <div class="card-header">
                    <h3 class="card-title text-center"> @lang('Account Information')</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        
                        <div class="col-xl-4 col-sm-4">
                            <div class="form-group">
                                <label for="account_type">@lang('Account Type')</label>
                                <select class="form-control" name="account_type" id="account_type" required>
                                    <option selected disabled value>@lang('Select Account Type')</option>
                                    <option value="1">@lang('Saving Account')</option>
                                    <option value="2">@lang('Current Account')</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-xl-4 col-sm-4">
                            <div class="form-group">
                                <label for="withdraw_amount_limit">@lang('Daily Withdrawal Amount Limit')</label>
                                <input class="form-control" name="withdraw_amount_limit" type="text" id="withdraw_amount_limit" required>
                            </div>
                        </div>
                        
                        <div class="col-xl-4 col-sm-4">
                            <div class="form-group">
                                <label for="withdraw_freq_limit">@lang('Daily Withdrawal Frequency Limit')</label>
                                <input class="form-control" name="withdraw_freq_limit" type="text" id="withdraw_freq_limit" required>
                            </div>
                        </div>
                        
                        <div class="col-xl-4 col-sm-4">
                            <div class="form-check">
                                <input class="form-check-input" name="ebank" type="checkbox" id="ebank">
                                <label class="form-check-label" for="ebank">
                                    @lang('Access to E-Banking')
                                </label>
                            </div>
                        </div>
                        
                        <div class="col-xl-4 col-sm-4">
                            <div class="form-check">
                                <input class="form-check-input" name="cheque" type="checkbox" id="cheque">
                                <label class="form-check-label" for="cheque">
                                    @lang('Access to Cheque Book')
                                </label>
                            </div>
                        </div>
                        
                        <div class="col-xl-4 col-sm-4">
                            <div class="form-check">
                                <input class="form-check-input" name="wallet" type="checkbox" id="wallet">
                                <label class="form-check-label" for="wallet">
                                    @lang('Access to Mobile Wallet')
                                </label>
                            </div>
                        </div>
                        
                        <div class="col-xl-12 col-sm-12">
                            <div class="form-group">
                                <label for="application_scan">@lang('Application Form Scan')</label>
                                <input class="form-control" id="application_scan" name="application_scan" type="file" accept=".png, .jpg, .jpeg, .pdf" id="application_scan" required>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
        
        <button class="btn btn--primary w-100 h-45" type="submit">@lang('Submit')</button>

    </form>
    <div class="personX" style="display:none">
        <div class="card b-radius--10 mb-3">
            <div class="card-header">
                <h3 class="card-title text-center" id="personX">Person X</h3>
            </div>
            <div class="card-body" id="p--">
                <input name="p[--][id]" type="hidden" id="p--id" value="0">
                <div class="row personal">
                    <div class="col-md-6 col-xl-4">
                        <div class="form-group">
                            <label for="p--image">@lang('Image')</label>
                            <div class="image-upload">
                                <div class="thumb">
                                    <div class="avatar-preview">
                                        <div class="profilePicPreview" style="background-image: url({{ getImage(getFilePath('userProfile') . '/', getFileSize('userProfile'), true) }})">
                                            <button class="remove-image" type="button"><i class="fa fa-times"></i></button>
                                        </div>
                                    </div>

                                    <div class="avatar-edit">
                                        <input class="profilePicUpload" name="p[--][image]" type="file" accept=".pdf, .png, .jpg, .jpeg" id="p--image" required>

                                        <label class="bg--primary" for="p--image">@lang('Upload Image')</label>

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
                                    <label for="p--fullname">@lang('Full Name')</label>
                                    <input class="form-control" name="p[--][fullname]" type="text" id="p--fullname" required>
                                </div>
                            </div>
                            
                            <div class="col-xl-3 col-sm-3">
                                <div class="form-group">
                                    <label for="p--gender">@lang('Gender')</label>
                                    <select class="form-control" name="p[--][gender]" id="p--gender" required>
                                        <option selected disabled value>@lang('Select Gender')</option>
                                        <option value="1">@lang('Male')</option>
                                        <option value="2">@lang('Female')</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-xl-4 col-sm-4">
                                <div class="form-group">
                                    <label for="p--country">@lang('Nationality')</label>
                                    <select class="form-control country" name="p[--][country]" id="p--country" required>
                                        <option selected disabled value>@lang('Select Nationality')</option>
                                        @foreach ($countries as $key => $country)
                                            <option data-mobile_code="{{ $country->dial_code }}" value="{{ $key }}" {{ $key == "SO" ? "selected" : "" }}>{{ __($country->country) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-xl-4 col-sm-4">
                                <div class="form-group">
                                    <label for="p--pob">@lang('Place of Birth')</label>
                                    <select class="form-control" name="p[--][pob]" id="p--pob" required>
                                        <option selected disabled value>@lang('Select Place of Birth')</option>
                                        @foreach ($countries as $key => $country)
                                            <option value="{{ $key }}" {{ $key == "SO" ? "selected" : "" }}>{{ __($country->country) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-xl-4 col-sm-4">
                                <div class="form-group">
                                    <label for="p--dob">@lang('Date of Birth')</label>
                                    <input class="form-control" name="p[--][dob]" type="date" id="p--dob" required>
                                </div>
                            </div>
                            
                            <div class="col-xl-4 col-sm-4">
                                <div class="form-group">
                                    <label for="p--region">@lang('Region')</label>
                                    <input class="form-control" name="p[--][region]" type="text" id="p--region" required>
                                </div>
                            </div>
                            
                            <div class="col-xl-4 col-sm-4">
                                <div class="form-group">
                                    <label for="p--city">@lang('City')</label>
                                    <input class="form-control" name="p[--][city]" type="text" id="p--city" required>
                                </div>
                            </div>
                            
                            <div class="col-xl-4 col-sm-4">
                                <div class="form-group">
                                    <label for="p--address">@lang('Address')</label>
                                    <input class="form-control" name="p[--][address]" type="text" id="p--address" required>
                                </div>
                            </div>
                            
                            <div class="col-xl-4 col-sm-4">
                                <div class="form-group">
                                    <label for="p--mobile">@lang('Mobile Number')</label>
                                    <div class="input-group">
                                        <select class="input-group-text mobile-code" name="p[--][mobile_code]" id="p--mobile-code" required>
                                            @foreach ($countries as $key => $country)
                                                <option value="{{ $country->dial_code }}">{{ "+".$country->dial_code }}</option>
                                            @endforeach
                                        </select>
                                        <input class="form-control checkUser" name="p[--][mobile]" type="number" id="p--mobile" required>
                                        <small class="text-danger mobileExist" id="p--mobileExist"></small>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-4 col-sm-4">
                                <div class="form-group">
                                    <label for="p--email">@lang('E-Mail Address')</label>
                                    <input class="form-control" name="p[--][email]" type="email" id="p--email" required>
                                </div>
                            </div>
                            
                            <div class="col-xl-4 col-sm-4">
                                <div class="form-group">
                                    <label for="p--marital">@lang('Marital Status')</label>
                                    <select class="form-control" name="p[--][marital]" id="p--marital" required>
                                        <option selected disabled value>@lang('Select Marital Status')</option>
                                        <option value="1">@lang('Single')</option>
                                        <option value="2">@lang('Married')</option>
                                        <option value="3">@lang('Divorcee')</option>
                                        <option value="4">@lang('Widow')</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-xl-6 col-sm-6">
                                <div class="form-group">
                                    <label for="p--employment_status">@lang('Employment Status')</label>
                                    <select class="form-control" name="p[--][employment_status]" id="p--employment_status" required>
                                        <option selected disabled value>@lang('Select Employment Status')</option>
                                        <option value="1">@lang('Student')</option>
                                        <option value="2">@lang('Self-Employed')</option>
                                        <option value="3">@lang('Employed')</option>
                                        <option value="4">@lang('Unemployed')</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-xl-6 col-sm-6">
                                <div class="form-group">
                                    <label for="p--employment_detail">@lang('Detail')</label>
                                    <input class="form-control" name="p[--][employment_detail]" type="text" id="p--employment_detail" required>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                
                <div class="nok">
                    <div class="row">
                        <div class="nok1 col-6 border-end">
                            <h4 class="my-3">@lang('Next of Kin 1')</h4>
                            <div class="row">
                                
                                <div class="col-xl-4 col-sm-4">
                                    <div class="form-group">
                                        <label for="p--nok1_name">@lang('Name')</label>
                                        <input class="form-control" name="p[--][nok1][name]" type="text" id="p--nok1_name" required>
                                    </div>
                                </div>
                                
                                <div class="col-xl-4 col-sm-4">
                                    <div class="form-group">
                                        <label for="p--nok1_relation">@lang('Relationship')</label>
                                        <select class="form-control" name="p[--][nok1][relation]" id="p--nok1_relation" required>
                                            <option selected disabled value>@lang('Select Relationship Type')</option>
                                            <option value="1">@lang('Parent')</option>
                                            <option value="2">@lang('Sibling')</option>
                                            <option value="3">@lang('Spouse')</option>
                                            <option value="4">@lang('Child')</option>
                                            <option value="5">@lang('Relative')</option>
                                            <option value="6">@lang('Beneficiary')</option>
                                            <option value="7">@lang('Friend')</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-xl-4 col-sm-4">
                                    <div class="form-group">
                                        <label for="p--nok1_mobile">@lang('Mobile Number')</label>
                                        <input class="form-control" name="p[--][nok1][mobile]" type="text" id="p--nok1_mobile" required>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                        
                        <div class="nok2 col-6">
                            <h4 class="my-3">@lang('Next of Kin 2')</h4>
                            <div class="row">
                                
                                <div class="col-xl-4 col-sm-4">
                                    <div class="form-group">
                                        <label for="p--nok2_name">@lang('Name')</label>
                                        <input class="form-control" name="p[--][nok2][name]" type="text" id="p--nok2_name" required>
                                    </div>
                                </div>
                                
                                <div class="col-xl-4 col-sm-4">
                                    <div class="form-group">
                                        <label for="p--nok2_relation">@lang('Relationship')</label>
                                        <select class="form-control" name="p[--][nok2][relation]" id="p--nok2_relation" required>
                                            <option selected disabled value>@lang('Select Relationship Type')</option>
                                            <option value="1">@lang('Parent')</option>
                                            <option value="2">@lang('Sibling')</option>
                                            <option value="3">@lang('Spouse')</option>
                                            <option value="4">@lang('Child')</option>
                                            <option value="5">@lang('Relative')</option>
                                            <option value="6">@lang('Beneficiary')</option>
                                            <option value="7">@lang('Friend')</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-xl-4 col-sm-4">
                                    <div class="form-group">
                                        <label for="p--nok2_mobile">@lang('Mobile Number')</label>
                                        <input class="form-control" name="p[--][nok2][mobile]" type="text" id="p--nok2_mobile" required>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
                <div class="attachables col-12">
                    <h4 class="card-title my-3 text-center"> @lang('Attachable Documents')</h4>
                    <div class="row">
                        
                        <div class="col-xl-4 col-sm-4">
                            <div class="form-group">
                                <label for="p--doc_type">@lang('Document Type')</label>
                                <select class="form-control" name="p[--][doc_type]" id="p--doc_type" required>
                                    <option selected disabled value>@lang('Select Document Type')</option>
                                    <option value="1">@lang('Passport')</option>
                                    <option value="2">@lang('Government ID')</option>
                                    <option value="3">@lang('Driver\'s License')</option>
                                    <option value="4">@lang('Birth Certificate')</option>
                                    <option value="5">@lang('Student ID')</option>
                                    <option value="6">@lang('Court Document')</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-xl-4 col-sm-4">
                            <div class="form-group">
                                <label for="p--doc_expiry">@lang('Document Expiry Date')</label>
                                <input class="form-control" name="p[--][doc_expiry]" type="date" id="p--doc_expiry" required>
                            </div>
                        </div>
                        
                        <div class="col-xl-4 col-sm-4">
                            <div class="form-group">
                                <label for="p--doc_scan">@lang('Document Scan')</label>
                                <input class="form-control" name="p[--][doc_scan]" type="file" accept=".pdf, .png, .jpg, .jpeg" id="p--doc_scan" required>
                            </div>
                        </div>
                        
                        <div class="col-xl-12 col-sm-12">
                            <div class="form-group">
                                <label for="p--signature_scan">@lang('Signature Scan')</label>
                                <input class="form-control" name="p[--][signature_scan]" type="file" accept=".pdf, .png, .jpg, .jpeg" id="p--signature_scan" required>
                            </div>
                        </div>
                        
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
                <div class="modal-body">
                    <div class="row justify-content-center">
                        <img id="modalImage" style="height: 200px; width: 200px">
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <label for="">Name</label>
                            <input class="form-control" name="name" type="text" id="name" readonly>
                            <input id="_person_id" type="hidden" data-person=''>
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
            let ind = 1;
            let _response = null;
            var modal = $('#viewModal');
            
            $(document).ready(() => {
                $("#clone").click();
                $("#clone").click();
            });
            
            $('#persons').on('change', '.country', function () {
                let person = (this.name.split("_"))[0] + '_';
                let mobileCode = $('option:selected', this).data('mobile_code');
                setMobileCode(person, mobileCode);
            });
            
            function setMobileCode(person, mobileCode) {
                $(`#${person}mobile-code option[value='${mobileCode}']`).prop('selected', true);
            }
            
            $('#clone').on('click', function(e) {
                if(ind <= 5){
                    let person = `p${ind}_`;
                    $(".personX").clone(true)
                    .find('#p--').each(function () {
                        this.id = this.id.replace('p--', person);
                    }).end()
                    .find('#personX').each(function () {
                        this.id = `${person}_title`;
                        $(this).text(`Person ${ind}`);
                    }).end()
                    .find(':input').each(function () {
                        this.name = this.name.replace('p[--]', `p[${ind}]`);
                        this.id = this.id.replace('p--', person);
                    }).end()
                    .find('label').each(function () {
                        $(this).attr('for', $(this).attr('for').replace('p--', person));
                    }).end()
                    .find('.mobile-code').each(function () {
                        this.id = this.id.replace('p--', person);
                    }).end()
                    .find('.mobileExist').each(function () {
                        this.id = this.id.replace('p--', person);
                    }).end()
                    .attr({class: 'person', id: `person_${ind}`, style: ''})
                    .appendTo('#persons');
                    $('html, body').animate({
                        scrollTop: $(ind <= 2 ? '#person_1' : `#person_${ind}`).offset().top
                    }, 1000);
                    
                    let mobileCode = $(`#p${ind}_country option[value='SO']`).prop('selected', true);
                    setMobileCode(person, mobileCode.data('mobile_code'));
                    $("#customers").text(ind);
                    ind++;
                }
            });
            
            $('#load').on('click', function() {
                let person = $('#_person_id').data('person');
                let personIndex = person.replace(/[^0-9]/g, '');
                if($(`#${person}mobileExist .clearCustomer`).length == 0){
                    $(`#${person}mobileExist`).html($(`#${person}mobileExist`).html() + ` | <a href="#" class="clearCustomer">clear</a>`);
                }
                $(`#${person}id`).val($('#_person_id').val());
                $(`#${person}image`).attr({'disabled': true, 'required': false});
                $(`#${person} .personal *`).filter(`:input:not(:button):not(#${person}image)`).each(function(){
                    let id = this.id;
                    let name = this.name.replace(`p[${personIndex}][`, '').replace(']', '');
                    $(this).attr({'disabled': true, 'required': false});
                    $(`#${person} label[for=${id}]:not(.bg--primary)`).removeAttr('class');
                    if(_response.hasOwnProperty(name)){
                       $(this).val(_response[name]);
                    }else if(_response.misc.hasOwnProperty(name)){
                        $(this).val(_response.misc[name]);
                    }
                });
                $(`#${person} .attachables *`).filter(`:input[name*=doc_]:not(:button)`).each(function(){
                    let id = this.id;
                    let name = this.name.replace(`p[${personIndex}][`, '').replace(']', '');
                    $(this).attr({'disabled': true, 'required': false});
                    $(`#${person} .attachables label[for=${id}]`).removeAttr('class');
                    if(_response.hasOwnProperty(name)){
                       $(this).val(_response[name]);
                    }
                });
                
            });

            $('#persons').on('click', '.viewCustomer', function () {
                let person = $(this).parent().attr('id').split("_")[0] + '_';
                var url = '{{ route('staff.customer.find') }}';
                var value = $(`#${person}mobile`).val();
                var token = '{{ csrf_token() }}';
                
                var element = $(`#${person}mobile-code option:selected`).text().substr(1);
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
                    $(`#_person_id`).val(_response.id);
                    $(`#_person_id`).data('person', person);
                    $('#viewModal *').filter(':input').each(function(){
                        if(_response.hasOwnProperty(this.name)){
                            $(this).val(_response[this.name]);
                        }
                    });
                    modal.modal('show');
                });
            });
            
            $('#persons').on('click', '.clearCustomer', function () {
                let person = $(this).parent().attr('id').split("_")[0] + '_';
                $(`#${person}mobileExist`).empty();
                $(`#${person}id`).val('0');
                $(`#${person}image`).attr({'disabled': false, 'required': true});
                $(`#${person} *`).filter(`:input:not(:button):not(#${person}image)`).each(function(){
                    let id = this.id;
                    $(this).attr({'disabled': false, 'required': true});
                    $(this).val('');
                    
                    $(`#person label[for=${id}]:not(.bg--primary)`).attr('class', 'required');
                });
                $(`#${person} #attachables *`).filter(':input[name^=doc_]:not(:button)').each(function(){
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
                var person = '';
                
                if ($(this).attr('name').indexOf('mobile') != -1) {
                    person = (this.id.split("_"))[0] + '_';
                    var element = $(`#${person}mobile-code option:selected`).text().substr(1);
                    var mobile = `${element}${value}`;
                    
                    var data = {
                        mobile: mobile,
                        _token: token
                    };
                }

                $.post(url, data, function(response) {
                    if (response.data != false) {
                        $(`#${person}${response.type}Exist`).html(`Mobile exists, <a href="#" class="viewCustomer">view</a>?`);
                    } else {
                        $(`.${response.type}Exist`).empty();
                    }
                });
            });

        })(jQuery);
    </script>
@endpush

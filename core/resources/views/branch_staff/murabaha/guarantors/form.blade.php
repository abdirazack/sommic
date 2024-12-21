@extends('branch_staff.layouts.app')
@section('panel')
    <form id="form" method="POST" action="{{ $action }}" enctype="multipart/form-data">
        @csrf
        <div class="card b-radius--10 mb-3">
            <div class="card-header">
                <h3 class="card-title text-center">@lang('Personal Details')</h3>
            </div>
            <div class="card-body">
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
                                        <input class="profilePicUpload" name="_image" id="image" type="file" accept=".pdf, .png, .jpg, .jpeg" required>

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
                                    <label for="name">@lang('Name')</label>
                                    <input class="form-control" name="name" type="text" id="name" value="{{ old('name') }}" required>
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
                            
                            <div class="col-xl-6 col-sm-6">
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
                            
                            <div class="col-xl-6 col-sm-6">
                                <div class="form-group">
                                    <label for="dob">@lang('Date of Birth')</label>
                                    <input class="form-control" name="dob" type="date" id="dob" value="{{ old('dob') }}" required>
                                </div>
                            </div>
                            
                            <div class="col-xl-6 col-sm-6">
                                <div class="form-group">
                                    <label for="mobile">@lang('Mobile Number')</label>
                                    <div class="input-group">
                                        <select class="input-group-text mobile-code" name="mobile_code" id="mobile-code" required>
                                            @foreach ($countries as $key => $country)
                                                <option value="{{ $country->dial_code }}"{{ old('mobile_code') == $country->dial_code ? "selected" : ($key == "SO" ? "selected" : "" ) }}>{{ "+".$country->dial_code }}</option>
                                            @endforeach
                                        </select>
                                        <input class="form-control checkUser" name="_mobile" type="number" id="mobile" value="{{ old('_mobile') }}" required>
                                        <small class="text-danger mobileExist"></small>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-xl-6 col-sm-6">
                                <div class="form-group">
                                    <label for="cell">@lang('Cellphone')</label>
                                    <div class="input-group">
                                        <select class="input-group-text cell-code" name="cell_code" id="cell-code">
                                            @foreach ($countries as $key => $country)
                                                <option value="{{ $country->dial_code }}"{{ old('cell_code') == $country->dial_code ? "selected" : ($key == "SO" ? "selected" : "" ) }}>{{ "+".$country->dial_code }}</option>
                                            @endforeach
                                        </select>
                                        
                                        <input class="form-control checkUser" name="cell" type="number" id="cell" value="{{ old('cell') }}">
                                        <small class="text-danger mobileExist"></small>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-xl-6 col-sm-6">
                                <div class="form-group">
                                    <label for="id_type">@lang('ID Type')</label>
                                    <select name="id_type" class="form-control" id="id_type" required>
                                        <option selected disabled value>@lang('Select ID Type')</option>
                                        <option value="1" {{ old('id_type') == '1' ? 'selected' : '' }} >@lang('Passport')</option>
                                        <option value="2" {{ old('id_type') == '2' ? 'selected' : '' }} >@lang('Government ID')</option>
                                        <option value="3" {{ old('id_type') == '3' ? 'selected' : '' }} >@lang('Driver\'s License')</option>
                                        <option value="4" {{ old('id_type') == '4' ? 'selected' : '' }} >@lang('Birth Certificate')</option>
                                        <option value="5" {{ old('id_type') == '5' ? 'selected' : '' }} >@lang('Student ID')</option>
                                        <option value="6" {{ old('id_type') == '6' ? 'selected' : '' }} >@lang('Court Document')</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-xl-6 col-sm-6">
                                <div class="form-group">
                                    <label for="id_number">@lang('ID Number')</label>
                                    <input class="form-control" name="id_number" type="text" id="id_number" value="{{ old('id_number') }}" required>
                                </div>
                            </div>
                            
                            <div class="col-xl-9 col-sm-9">
                                <div class="form-group">
                                    <label for="address">@lang('Address')</label>
                                    <input class="form-control" name="address" type="text" id="address" value="{{ old('address') }}" required>
                                </div>
                            </div>
                            
                            <div class="col-xl-3 col-sm-3">
                                <div class="form-group">
                                    <label for="years_at_address">@lang('Years at Address')</label>
                                    <input class="form-control" name="years_at_address" type="text" id="years_at_address" value="{{ old('years_at_address') }}" required>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card b-radius--10 mb-3">
            <div class="card-header">
                <h3 class="card-title text-center">@lang('Additional Information')</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-xl-9 col-sm-9">
                        <div class="form-group">
                            <label for="civil_status">@lang('Civil Status')</label>
                            <select class="form-control" name="civil_status" id="civil_status" required>
                                <option selected disabled value>@lang('Select Civil Status')</option>
                                <option value="1" {{ old('civil_status') == '1' ? 'selected' : '' }}>@lang('Single')</option>
                                <option value="2" {{ old('civil_status') == '2' ? 'selected' : '' }}>@lang('Married')</option>
                                <option value="3" {{ old('civil_status') == '3' ? 'selected' : '' }}>@lang('Divorcee')</option>
                                <option value="4" {{ old('civil_status') == '4' ? 'selected' : '' }}>@lang('Widow')</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-xl-3 col-sm-3">
                        <div class="form-group">
                            <label for="dependants">@lang('Dependants')</label>
                            <input class="form-control" name="dependants" type="text" id="dependants" value="{{ old('dependants') }}" required>
                        </div>
                    </div>
                    
                    <div class="col-xl-12 col-sm-12">
                        <div class="form-group">
                            <label for="house_status">@lang('House Status')</label>
                            <select class="form-control" name="house_status" id="house_status" required>
                                <option selected disabled value>@lang('Select House Status')</option>
                                <option value="1" {{ old('house_status') == '1' ? 'selected' : '' }}>@lang('Owned')</option>
                                <option value="2" {{ old('house_status') == '2' ? 'selected' : '' }}>@lang('Rented')</option>
                                <option value="3" {{ old('house_status') == '3' ? 'selected' : '' }}>@lang('Mortgaged')</option>
                            </select>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
        
        <div class="card b-radius--10 mb-3">
            <div class="card-header">
                <h3 class="card-title text-center">@lang('Employment & Economic Activity')</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label for="employment_status">@lang('Employment')</label>
                            <select class="form-control" name="employment_status" id="employment_status" required>
                                <option selected disabled value>@lang('Select Employment Status')</option>
                                <option value="1" {{ old('employment_status') == '1' ? 'selected' : '' }}>@lang('Employed')</option>
                                <option value="2" {{ old('employment_status') == '2' ? 'selected' : '' }}>@lang('Self-Employed')</option>
                            </select>
                        </div>
                    </div>
                    <div id="employment"></div>
                    
                </div>
            </div>
        </div>
        
        <button class="btn btn--primary w-100 h-45" type="submit">@lang('Submit')</button>
    </form>
    <div class="templates">
        <!-- Partner -->
        <div class="row partnerX" style="display:none">
            <div class="col-xl-7 col-sm-7">
                <div class="form-group">
                    <label for="partners[--]_name">@lang('Name')</label>
                    <input class="form-control" name="partners[--][name]" type="text" id="partners[--]_name" value="{{ old('partners.*.name') }}" required>
                </div>
            </div>
            <div class="col-xl-4 col-sm-4">
                <div class="form-group">
                    <label for="partners[--]_stake">@lang('Stake in Business')</label>
                    <input class="form-control" name="partners[--][stake]" type="text" id="partners[--]_stake" value="{{ old('partners.*.stake') }}" required>
                </div>
            </div>
            <div class="col-xl-1 col-sm-1">
                <div class="form-group">
                    <label for=""></label>
                    <button class="btn btn-light border border-danger w-100 h-45 form-control remove" type="button">
                        <i class="text-danger fs-4 la la-trash"></i>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Self-Employed -->
        <div class="self-employed col-12" style="display:none">
            <div class="row">
                <div class="col-xl-4 col-sm-4">
                    <div class="form-group">
                        <label for="_self_name">@lang('Business Name')</label>
                        <input class="form-control" name="_self[name]" type="text" id="_self_name" value="{{ old('self[name]') }}" required>
                    </div>
                </div>
                
                <div class="col-xl-4 col-sm-4">
                    <div class="form-group">
                        <label for="_self_nature">@lang('Nature of Business')</label>
                        <input class="form-control" name="_self[nature]" type="text" id="_self_nature" value="{{ old('_self[nature]') }}" required>
                    </div>
                </div>
                
                <div class="col-xl-4 col-sm-4">
                    <div class="form-group">
                        <label for="_self_address">@lang('Address')</label>
                        <input class="form-control" name="_self[address]" type="text" id="_self_address" value="{{ old('_self[address]') }}" required>
                    </div>
                </div>
                
                <div class="col-xl-4 col-sm-4">
                    <div class="form-group">
                        <label for="_self_mobile">@lang('Mobile Number')</label>
                        <input class="form-control" name="_self[mobile]" type="text" id="_self_mobile" value="{{ old('_self[mobile]') }}" required>
                    </div>
                </div>
                
                <div class="col-xl-4 col-sm-4">
                    <div class="form-group">
                        <label for="_self_years">@lang('Years in Business')</label>
                        <input class="form-control" name="_self[years]" type="text" id="_self_years" value="{{ old('_self[years]') }}" required>
                    </div>
                </div>
                
                <div class="col-xl-4 col-sm-4">
                    <div class="form-group">
                        <label for="_self_monthly_sales">@lang('Regular Monthly Sales')</label>
                        <input class="form-control" name="_self[monthly_sales]" type="text" id="_self_monthly_sales" value="{{ old('_self[monthly_sales]') }}" required>
                    </div>
                </div>
                
                <div class="col-xl-4 col-sm-4">
                    <div class="form-check">
                        <input class="form-check-input" name="ebank" type="checkbox" id="has_partners" {{ old('has_partners') != null ? 'checked' : ''}}>
                        <label class="form-check-label" for="has_partners">
                            @lang('Has partner(s)?')
                        </label>
                    </div>
                </div>
                
                <div class="col-12 partners">
                    
                    <button class="col-12 btn btn-info text-light h-45" type="button" id="add-partner" style="display:none">Add Partner</button>
                    
                </div>
                
            </div>
        </div>
        
        <!-- Employed -->
        <div class="employed col-12" style="display:none">
            <div class="row">
                
                <div class="col-xl-6 col-sm-6">
                    <div class="form-group">
                        <label for="work_organization_name">@lang('Organization Name')</label>
                        <input class="form-control" name="work[organization_name]" type="text" id="work_organization_name" value="{{ old('work[organization_name]') }}" required>
                    </div>
                </div>
                
                <div class="col-xl-6 col-sm-6">
                    <div class="form-group">
                        <label for="work_superior_name">@lang('Employer/Superior Name')</label>
                        <input class="form-control" name="work[superior_name]" type="text" id="work_superior_name" value="{{ old('work[superior_name]') }}" required>
                    </div>
                </div>
                
                <div class="col-xl-4 col-sm-4">
                    <div class="form-group">
                        <label for="work_address">@lang('Address')</label>
                        <input class="form-control" name="work[address]" type="text" id="work_address" value="{{ old('work[address]') }}" required>
                    </div>
                </div>
                
                <div class="col-xl-4 col-sm-4">
                    <div class="form-group">
                        <label for="work_mobile">@lang('Mobile Number')</label>
                        <input class="form-control" name="work[mobile]" type="text" id="work_mobile" value="{{ old('work[mobile]') }}" required>
                    </div>
                </div>
                
                <div class="col-xl-4 col-sm-4">
                    <div class="form-group">
                        <label for="work_nature">@lang('Nature of Business')</label>
                        <input class="form-control" name="work[nature]" type="text" id="work_nature" value="{{ old('work[nature]') }}" required>
                    </div>
                </div>
                
                <div class="col-xl-4 col-sm-4">
                    <div class="form-group">
                        <label for="work_position">@lang('Position at Organization')</label>
                        <input class="form-control" name="work[title]" type="text" id="work_position" value="{{ old('work[title]') }}" required>
                    </div>
                </div>
                
                <div class="col-xl-4 col-sm-4">
                    <div class="form-group">
                        <label for="work_years">@lang('Years at Organization')</label>
                        <input class="form-control" name="work[years]" type="text" id="work_years" value="{{ old('work[years]') }}" required>
                    </div>
                </div>
                
                <div class="col-xl-4 col-sm-4">
                    <div class="form-group">
                        <label for="work_monthly_salary">@lang('Regular Monthly Salary')</label>
                        <input class="form-control" name="work[monthly_salary]" type="text" id="work_monthly_salary" value="{{ old('work[monthly_salary]') }}" required>
                    </div>
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
            let index = 0;
            var modal = $('#viewModal');
            
            $(document).ready(() => {
                let dialCode = $('select[name=pob] :selected').data('mobile_code');
                
                $(`.mobile-code option[value='${dialCode}']`).prop('selected', true);
                
                $('select[name=pob]').change(function() {
                    let dialCode = $('select[name=pob] :selected').data('mobile_code');
                    $(`.mobile-code option[value='${dialCode}']`).prop('selected', true);
                });
                $('#employment_status').trigger('change');
            });
            
            $('#employment_status').on('change', function() {
                let form = '';
                if($(this).val() == 1 || $(this).val() == 2){
                    let showForm = $(this).val() == 1 ? '.employed' : '.self-employed';
                    form = $(showForm).clone(true).removeAttr('style');
                }
                $('#employment').html(form);
            });
            
            $('#has_partners').on('change', function() {
                if($(this).is(':checked') && index == 0){
                    $('#add-partner').show();
                    $('#add-partner').trigger('click');
                }else{
                    $('.partner').remove();
                    $('#add-partner').hide();
                    index = 0;
                }
            });
            
            $('#add-partner').on('click', function() {
                if($('#has_partners').is(':not(:checked)')){
                    $('#has_partners').prop('checked', true);
                }
                $('.partnerX').clone(true)
                .find('.remove').each(function () {
                    $(this).data('id', `partner_${index}`);
                }).end()
                .find(':input[type=text]').each(function () {
                    this.name = this.name.replace('[--]', `[${index}]`);
                    this.id = this.id.replace('[--]', `_${index}`);
                }).end()
                .find('label').each(function () {
                    $(this).attr('for', $(this).attr('for').replace('[--]', `_${index}`));
                }).end()
                .attr({'class': 'row partner', 'id': `partner_${index}`})
                .removeAttr('style')
                .insertBefore('#add-partner');
                index++;
            });
            
            $('.remove').on('click', function() {
                let id = $(this).data('id');
                $(`#${id}`).remove();
                rebuildPartners();
            });
            
            function rebuildPartners(){
                index = 0;
                if($('.partner').length > 0){
                    $('.partner').each(function () {
                        this.id = `partner_${index}`;
                        $(this).find('.remove').each(function() {
                            $(this).data('id', `partner_${index}`);
                        }).end()
                        .find(':input[type=text]').each(function () {
                            this.name = this.name.replace(/\d/, index);
                            this.id = this.id.replace(/\d/, index);
                        }).end()
                        .find('label').each(function () {
                            $(this).attr('for', $(this).attr('for').replace(/\d/, index));
                        }).end();
                        index++;
                    });
                }else{
                    $('#add-partner').hide();
                    $('#has_partners').prop('checked', false);
                }
            }

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

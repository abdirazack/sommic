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
                                        <input class="profilePicUpload" name="person[_image]" id="image" type="file" accept=".pdf, .png, .jpg, .jpeg" required>

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
                                    <label for="name">@lang('Full Name')</label>
                                    <input class="form-control" name="person[name]" type="text" id="name" value="{{ old('person[name]') }}" required>
                                </div>
                            </div>
                            
                            <div class="col-xl-3 col-sm-3">
                                <div class="form-group">
                                    <label for="gender">@lang('Gender')</label>
                                    <select class="form-control" name="person[gender]" id="gender" required>
                                        <option selected disabled value>@lang('Select Gender')</option>
                                        <option value="1" {{ old('person[gender]') == '1' ? 'selected' : ''}}>@lang('Male')</option>
                                        <option value="2" {{ old('person[gender]') == '2' ? 'selected' : ''}}>@lang('Female')</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-xl-6 col-sm-6">
                                <div class="form-group">
                                    <label for="pob">@lang('Place of Birth')</label>
                                    <select class="form-control" name="person[pob]" id="pob" required>
                                        <option selected disabled value>@lang('Select Place of Birth')</option>
                                        @foreach ($countries as $key => $country)
                                            <option value="{{ $key }}" {{ old('person[pob]') == $key ? "selected" : ($key == "SO" ? "selected" : "" ) }}>{{ __($country->country) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-xl-6 col-sm-6">
                                <div class="form-group">
                                    <label for="dob">@lang('Date of Birth')</label>
                                    <input class="form-control" name="person[dob]" type="date" id="dob" value="{{ old('person[dob]') }}" required>
                                </div>
                            </div>
                            
                            <div class="col-xl-6 col-sm-6">
                                <div class="form-group">
                                    <label for="mobile">@lang('Mobile Number')</label>
                                    <div class="input-group">
                                        <select class="input-group-text mobile-code" name="person[mobile_code]" id="mobile-code" required>
                                            @foreach ($countries as $key => $country)
                                                <option value="{{ $country->dial_code }}"{{ old('person[mobile_code]') == $country->dial_code ? "selected" : ($key == "SO" ? "selected" : "" ) }}>{{ "+".$country->dial_code }}</option>
                                            @endforeach
                                        </select>
                                        <input class="form-control" name="person[_mobile]" type="number" id="mobile" value="{{ old('person[_mobile]') }}" required>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-xl-6 col-sm-6">
                                <div class="form-group">
                                    <label for="cell">@lang('Cellphone')</label>
                                    <div class="input-group">
                                        <select class="input-group-text cell-code" name="person[cell_code]" id="cell-code">
                                            @foreach ($countries as $key => $country)
                                                <option value="{{ $country->dial_code }}"{{ old('person[cell_code]') == $country->dial_code ? "selected" : ($key == "SO" ? "selected" : "" ) }}>{{ "+".$country->dial_code }}</option>
                                            @endforeach
                                        </select>
                                        
                                        <input class="form-control" name="person[cell]" type="number" id="cell" value="{{ old('person[cell]') }}">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-xl-6 col-sm-6">
                                <div class="form-group">
                                    <label for="id_type">@lang('ID Type')</label>
                                    <select name="person[id_type]" class="form-control" id="id_type" required>
                                        <option selected disabled value>@lang('Select ID Type')</option>
                                        <option value="1" {{ old('person[id_type]') == '1' ? 'selected' : '' }} >@lang('Passport')</option>
                                        <option value="2" {{ old('person[id_type]') == '2' ? 'selected' : '' }} >@lang('Government ID')</option>
                                        <option value="3" {{ old('person[id_type]') == '3' ? 'selected' : '' }} >@lang('Driver\'s License')</option>
                                        <option value="4" {{ old('person[id_type]') == '4' ? 'selected' : '' }} >@lang('Birth Certificate')</option>
                                        <option value="5" {{ old('person[id_type]') == '5' ? 'selected' : '' }} >@lang('Student ID')</option>
                                        <option value="6" {{ old('person[id_type]') == '6' ? 'selected' : '' }} >@lang('Court Document')</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-xl-6 col-sm-6">
                                <div class="form-group">
                                    <label for="id_number">@lang('ID Number')</label>
                                    <input class="form-control" name="person[id_number]" type="text" id="id_number" value="{{ old('person[id_number]') }}" required>
                                </div>
                            </div>
                            
                            <div class="col-xl-9 col-sm-9">
                                <div class="form-group">
                                    <label for="address">@lang('Address')</label>
                                    <input class="form-control" name="person[address]" type="text" id="address" value="{{ old('person[address]') }}" required>
                                </div>
                            </div>
                            
                            <div class="col-xl-3 col-sm-3">
                                <div class="form-group">
                                    <label for="years_at_address">@lang('Years at Address')</label>
                                    <input class="form-control" name="person[years_at_address]" type="text" id="years_at_address" value="{{ old('person[years_at_address]') }}" required>
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
                    <div class="col-xl-4 col-sm-4">
                        <div class="form-group">
                            <label for="civil_status">@lang('Civil Status')</label>
                            <select class="form-control" name="person[civil_status]" id="civil_status" required>
                                <option selected disabled value>@lang('Select Civil Status')</option>
                                <option value="1" {{ old('person[civil_status]') == '1' ? 'selected' : '' }}>@lang('Single')</option>
                                <option value="2" {{ old('person[civil_status]') == '2' ? 'selected' : '' }}>@lang('Married')</option>
                                <option value="3" {{ old('person[civil_status]') == '3' ? 'selected' : '' }}>@lang('Divorcee')</option>
                                <option value="4" {{ old('person[civil_status]') == '4' ? 'selected' : '' }}>@lang('Widow')</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-xl-4 col-sm-4">
                        <div class="form-group">
                            <label for="mother_name">@lang('Mother\'s Name')</label>
                            <input class="form-control" name="person[mother_name]" type="text" id="mother_name" value="{{ old('person[mother_name]') }}" required>
                        </div>
                    </div>
                    
                    <div class="col-xl-4 col-sm-4">
                        <div class="form-group">
                            <label for="dependants">@lang('Dependants')</label>
                            <input class="form-control" name="person[dependants]" type="text" id="dependants" value="{{ old('person[dependants]') }}" required>
                        </div>
                    </div>
                    
                    <div class="col-xl-4 col-sm-4">
                        <div class="form-group">
                            <label for="house_status">@lang('House Status')</label>
                            <select class="form-control" name="person[house_status]" id="house_status" required>
                                <option selected disabled value>@lang('Select House Status')</option>
                                <option value="1" {{ old('person[house_status]') == '1' ? 'selected' : '' }}>@lang('Owned')</option>
                                <option value="2" {{ old('person[house_status]') == '2' ? 'selected' : '' }}>@lang('Rented')</option>
                                <option value="3" {{ old('person[house_status]') == '3' ? 'selected' : '' }}>@lang('Mortgaged')</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-xl-4 col-sm-4">
                        <div class="form-group">
                            <label for="occupation">@lang('Occupation')</label>
                            <input class="form-control" name="person[occupation]" type="text" id="occupation" value="{{ old('person[occupation]') }}" required>
                        </div>
                    </div>
                    
                    <div class="col-xl-4 col-sm-4">
                        <div class="form-group">
                            <label for="education_level">@lang('Education Level')</label>
                            <select class="form-control" name="person[education_level]" id="education_level" required>
                                <option selected disabled value>@lang('Select Education Level')</option>
                                <option value="1" {{ old('person[education_level]') == '1' ? 'selected' : '' }}>@lang('Islamic Madrassa')</option>
                                <option value="2" {{ old('person[education_level]') == '2' ? 'selected' : '' }}>@lang('Kindergarten')</option>
                                <option value="3" {{ old('person[education_level]') == '3' ? 'selected' : '' }}>@lang('Primary School')</option>
                                <option value="4" {{ old('person[education_level]') == '4' ? 'selected' : '' }}>@lang('High School')</option>
                                <option value="5" {{ old('person[education_level]') == '5' ? 'selected' : '' }}>@lang('Diploma')</option>
                                <option value="6" {{ old('person[education_level]') == '6' ? 'selected' : '' }}>@lang('Bachelor\'s Degree')</option>
                                <option value="7" {{ old('person[education_level]') == '7' ? 'selected' : '' }}>@lang('Master\'s Degree')</option>
                                <option value="8" {{ old('person[education_level]') == '8' ? 'selected' : '' }}>@lang('PhD')</option>
                            </select>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
        
        <div class="card b-radius--10 mb-3">
            <div class="card-header">
                <h3 class="card-title text-center">@lang('Business Information')</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    
                    <div class="col-xl-6 col-sm-6">
                        <div class="form-group">
                            <label for="business_name">@lang('Name')</label>
                            <input class="form-control" name="business[name]" type="text" id="business_name" value="{{ old('business[name]') }}" required>
                        </div>
                    </div>
                    
                    <div class="col-xl-6 col-sm-6">
                        <div class="form-group">
                            <label for="business_type">@lang('Type')</label>
                            <select class="form-control" name="business[type]" id="business_type" required>
                                <option selected disabled value>@lang('Select Business Type')</option>
                                <option value="1" {{ old('business[type]') == '1' ? 'selected' : '' }}>@lang('Retail')</option>
                                <option value="2" {{ old('business[type]') == '2' ? 'selected' : '' }}>@lang('Wholesale')</option>
                                <option value="3" {{ old('business[type]') == '3' ? 'selected' : '' }}>@lang('Service')</option>
                                <option value="4" {{ old('business[type]') == '4' ? 'selected' : '' }}>@lang('Manufacturing')</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-xl-6 col-sm-6">
                        <div class="form-group">
                            <label for="business_mobile">@lang('Mobile')</label>
                            <input class="form-control" name="business[mobile]" type="text" id="business_mobile" value="{{ old('business[mobile]') }}" required>
                        </div>
                    </div>
                    
                    <div class="col-xl-6 col-sm-6">
                        <div class="form-group">
                            <label for="business_address">@lang('Address')</label>
                            <input class="form-control" name="business[address]" type="text" id="business_address" value="{{ old('business[address]') }}" required>
                        </div>
                    </div>
                    
                    <div class="col-xl-6 col-sm-6">
                        <div class="form-group">
                            <label for="business_years">@lang('Years in Business')</label>
                            <input class="form-control" name="business[years]" type="text" id="business_years" value="{{ old('business[years]') }}" required>
                        </div>
                    </div>
                    
                    <div class="col-xl-6 col-sm-6">
                        <div class="form-group">
                            <label for="business_employees">@lang('Number of Employees')</label>
                            <input class="form-control" name="business[employees]" type="text" id="business_employees" value="{{ old('business[employees]') }}" required>
                        </div>
                    </div>
                    
                    <div class="col-xl-6 col-sm-6">
                        <div class="form-group">
                            <label for="business_assets">@lang('Estimated Assets')</label>
                            <input class="form-control" name="business[assets]" type="text" id="business_assets" value="{{ old('business[assets]') }}" required>
                        </div>
                    </div>
                    
                    <div class="col-xl-6 col-sm-6">
                        <div class="form-group">
                            <label for="business_monthly_sales">@lang('Estimated Monthly Sales')</label>
                            <input class="form-control" name="business[monthly_sales]" type="text" id="business_monthly_sales" value="{{ old('business[monthly_sales]') }}" required>
                        </div>
                    </div>
                    
                    <div class="col-xl-12 col-sm-12">
                        <div class="form-group">
                            <label for="business_caregiver">@lang('Business Caregiver')</label>
                            <input class="form-control" name="business[caregiver]" type="text" id="business_caregiver" value="{{ old('business[caregiver]') }}" required>
                        </div>
                    </div>
                    
                    <div class="col-xl-4 col-sm-4">
                        <div class="form-check">
                            <input class="form-check-input" name="_partners" type="checkbox" id="has_partners" {{ old('') != null ? 'checked' : ''}}>
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
        </div>
        
        <div class="card b-radius--10 mb-3">
            <div class="card-header">
                <h3 class="card-title text-center">@lang('Present Obligations')</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    
                    <div class="current-financing">
                        <div class="col-xl-4 col-sm-4">
                            <div class="form-check">
                                <input class="form-check-input" name="current_financing" type="checkbox" id="current-financing" {{ old('') != null ? 'checked' : ''}}>
                                <label class="form-check-label" for="current-financing">
                                    @lang('Is there any outstanding financing?')
                                </label>
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <button class="col-12 btn btn-info text-light h-45 mb-2" type="button" id="add-financier" style="display:none">Add Financier</button>
                        </div>
                    </div>
                    <hr>
                    
                    <div class="previous-financing">
                        <div class="col-xl-4 col-sm-4">
                            <div class="form-check">
                                <input class="form-check-input" name="previous_financing" type="checkbox" id="previous-financing" {{ old('') != null ? 'checked' : ''}}>
                                <label class="form-check-label" for="previous-financing">
                                    @lang('Is there any previous financing?')
                                </label>
                            </div>
                        </div>
                        
                        <div class="col-12" style="display: none" id="previous-financier">
                            <div class="row">
                                <div class="col-xl-7 col-sm-7">
                                    <div class="form-group">
                                        <label for="reference_financier_name">@lang('Creditor/Supplier Name')</label>
                                        <input class="form-control" name="obligations[reference_financier][name]" type="text" id="reference_financier_name" value="{{ old('reference_financier[name]') }}" disabled>
                                    </div>
                                </div>
                                
                                <div class="col-xl-5 col-sm-5">
                                    <div class="form-group">
                                        <label for="reference_financier_amount">@lang('Amount')</label>
                                        <input class="form-control" name="obligations[reference_financier][amount]" type="text" id="reference_financier_amount" value="{{ old('reference_financier[amount]') }}" disabled>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
        
        <div class="card b-radius--10 mb-3">
            <div class="card-header">
                <h3 class="card-title text-center">@lang('Guarantor Information')</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <input name="guarantor[id]" type="hidden" id="guarantor_id" value="0">
                    <div class="col-xl-8 col-sm-8">
                        <div class="form-group">
                            <label for="guarantor_name">@lang('Name')</label>
                            <input class="form-control" name="guarantor[name]" type="text" id="guarantor_name" value="{{ old('guarantor[name]') }}" disabled>
                        </div>
                    </div>
                    
                    <div class="col-xl-4 col-sm-4">
                        <div class="form-group">
                            <label for="guarantor_telephone">@lang('Telephone')</label>
                            <input class="form-control checkGuarantor" name="guarantor[telephone]" type="text" id="guarantor_telephone" value="{{ old('guarantor[telephone]') }}" required>
                            <small class="text-danger guarantorExist"></small>
                        </div>
                    </div>
                    
                    <div class="col-xl-8 col-sm-8">
                        <div class="form-group">
                            <label for="guarantor_relation">@lang('Relationship with Investee')</label>
                            <select class="form-control" name="guarantor[relation]" id="guarantor_relation" required>
                                <option selected disabled value>@lang('Select Relationship with Investee')</option>
                                <option value="1" {{ old('guarantor[relation]') == '1' ? 'selected' : '' }}>@lang('Associate')</option>
                                <option value="2" {{ old('guarantor[relation]') == '2' ? 'selected' : '' }}>@lang('Friend')</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-xl-4 col-sm-4">
                        <div class="form-group">
                            <label for="guarantor_years_of_relation">@lang('Years of Relationship')</label>
                            <input class="form-control" name="guarantor[years_of_relation]" type="text" id="guarantor_years_of_relation" value="{{ old('guarantor[years_of_relation]') }}" required>
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
                    
                    <div class="col-xl-6 col-sm-6">
                        <div class="form-group">
                            <label for="application_scan">@lang('Application Form Scan')</label>
                            <input class="form-control" name="documents[application_scan]" type="file" accept=".pdf, .png, .jpg, .jpeg" id="application_scan" required>
                        </div>
                    </div>
                    
                    <div class="col-xl-6 col-sm-6">
                        <div class="form-group">
                            <label for="guarantor_scan">@lang('Guarantor Form Scan')</label>
                            <input class="form-control" name="documents[guarantor_scan]" type="file" accept=".pdf, .png, .jpg, .jpeg" id="guarantor_scan" required>
                        </div>
                    </div>
                    
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
                    <input class="form-control" name="business[partners][--][name]" type="text" id="partners[--]_name" value="{{ old('business.partners.*.name') }}" required>
                </div>
            </div>
            <div class="col-xl-4 col-sm-4">
                <div class="form-group">
                    <label for="partners[--]_stake">@lang('Stake in Business')</label>
                    <input class="form-control" name="business[partners][--][stake]" type="text" id="partners[--]_stake" value="{{ old('business.partners.*.stake') }}" required>
                </div>
            </div>
            <div class="col-xl-1 col-sm-1">
                <div class="form-group">
                    <label for=""></label>
                    <button class="btn btn-light border border-danger w-100 h-45 form-control remove-partner" type="button">
                        <i class="text-danger fs-4 la la-trash"></i>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Financier -->
        <div class="row financierX" style="display:none">
            <div class="col-xl-7 col-sm-7">
                <div class="form-group">
                    <label for="financier[--]_name">@lang('Creditor/Supplier Name')</label>
                    <input class="form-control" name="obligations[financiers][--][name]" type="text" id="financier[--]_name" value="{{ old('financiers.*.name') }}" required>
                </div>
            </div>
            
            <div class="col-xl-4 col-sm-4">
                <div class="form-group">
                    <label for="financier[--]_amount">@lang('Amount')</label>
                    <input class="form-control" name="obligations[financiers][--][amount]" type="text" id="financier[--]_amount" value="{{ old('financiers.*.amount') }}" required>
                </div>
            </div>

        
            <div class="col-xl-1 col-sm-1">
                <div class="form-group">
                    <label for=""></label>
                    <button class="btn btn-light border border-danger w-100 h-45 form-control remove-financier" type="button">
                        <i class="text-danger fs-4 la la-trash"></i>
                    </button>
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
            let partner_index = 0;
            let financier_index = 0;
            
            $(document).ready(() => {
                let dialCode = $('select[name=pob] :selected').data('mobile_code');
                
                $(`.mobile-code option[value='${dialCode}']`).prop('selected', true);
                
                $('select[name=pob]').change(function() {
                    let dialCode = $('select[name=pob] :selected').data('mobile_code');
                    $(`.mobile-code option[value='${dialCode}']`).prop('selected', true);
                });
                
                $('#has_partners').prop('checked', false);
                $('#current-financing').prop('checked', false);
                $('#previous-financing').prop('checked', false);
            });
            
            $('#previous-financing').on('click', function() {
                if($(this).is(':checked')){
                    $('#previous-financier').show();
                    $('#previous-financier :input[type=text]').each(function() {
                        $(this).attr({'disabled': false, 'required': true});
                    });
                }else{
                    $('#previous-financier').hide();
                    $('#previous-financier :input[type=text]').each(function() {
                        $(this).attr({'disabled': true, 'required': false});
                    });
                }
            });
            
            $('#current-financing').on('change', function() {
                if($(this).is(':checked') && financier_index == 0){
                    $('#add-financier').show();
                    $('#add-financier').trigger('click');
                }else{
                    $('.financier').remove();
                    $('#add-financier').hide();
                    financier_index = 0;
                }
            });
            
            $('#add-financier').on('click', function() {
                if($('#current-financing').is(':not(:checked)')){
                    $('#current-financing').prop('checked', true);
                }
                $('.financierX').clone(true)
                .find('.remove-financier').each(function () {
                    $(this).data('id', `f_${financier_index}`);
                }).end()
                .find(':input[type=text]').each(function () {
                    this.name = this.name.replace('[--]', `[${financier_index}]`);
                    this.id = this.id.replace('[--]', `_${financier_index}`);
                }).end()
                .find('label').each(function () {
                    $(this).attr('for', $(this).attr('for').replace('[--]', `_${financier_index}`));
                }).end()
                .attr({'class': 'row financier', 'id': `f_${financier_index}`})
                .removeAttr('style')
                .insertBefore('#add-financier');
                financier_index++;
            });
            
            $('.remove-financier').on('click', function() {
                let id = $(this).data('id');
                $(`#${id}`).remove();
                rebuildFinanciers();
            });
            
            $('#has_partners').on('change', function() {
                if($(this).is(':checked') && partner_index == 0){
                    $('#add-partner').show();
                    $('#add-partner').trigger('click');
                }else{
                    $('.partner').remove();
                    $('#add-partner').hide();
                    partner_index = 0;
                }
            });
            
            $('#add-partner').on('click', function() {
                if($('#has_partners').is(':not(:checked)')){
                    $('#has_partners').prop('checked', true);
                }
                $('.partnerX').clone(true)
                .find('.remove-partner').each(function () {
                    $(this).data('id', `partner_${partner_index}`);
                }).end()
                .find(':input[type=text]').each(function () {
                    this.name = this.name.replace('[--]', `[${partner_index}]`);
                    this.id = this.id.replace('[--]', `_${partner_index}`);
                }).end()
                .find('label').each(function () {
                    $(this).attr('for', $(this).attr('for').replace('[--]', `_${partner_index}`));
                }).end()
                .attr({'class': 'row partner', 'id': `partner_${partner_index}`})
                .removeAttr('style')
                .insertBefore('#add-partner');
                partner_index++;
            });
            
            $('.remove-partner').on('click', function() {
                let id = $(this).data('id');
                $(`#${id}`).remove();
                rebuildPartners();
            });
            
            function rebuildPartners(){
                partner_index = 0;
                if($('.partner').length > 0){
                    $('.partner').each(function () {
                        this.id = `partner_${partner_index}`;
                        $(this).find('.remove').each(function() {
                            $(this).data('id', `partner_${partner_index}`);
                        }).end()
                        .find(':input[type=text]').each(function () {
                            this.name = this.name.replace(/\d/, partner_index);
                            this.id = this.id.replace(/\d/, partner_index);
                        }).end()
                        .find('label').each(function () {
                            $(this).attr('for', $(this).attr('for').replace(/\d/, partner_index));
                        }).end();
                        partner_index++;
                    });
                }else{
                    $('#add-partner').hide();
                    $('#has_partners').prop('checked', false);
                }
            }
            
            function rebuildFinanciers(){
                financier_index = 0;
                if($('.financier').length > 0){
                    $('.financier').each(function () {
                        this.id = `f_${financier_index}`;
                        $(this).find('.remove-financier').each(function() {
                            $(this).data('id', `f_${financier_index}`);
                        }).end()
                        .find(':input[type=text]').each(function () {
                            this.name = this.name.replace(/\d/, financier_index);
                            this.id = this.id.replace(/\d/, financier_index);
                        }).end()
                        .find('label').each(function () {
                            $(this).attr('for', $(this).attr('for').replace(/\d/, financier_index));
                        }).end();
                        financier_index++;
                    });
                }else{
                    $('#add-financier').hide();
                    $('#current-financing').prop('checked', false);
                }
            }
            
            $('.guarantorExist').on('click', '#load', function() {
                $("#guarantor_id").val(_response.id);
                $('#guarantor_name').val(_response.name);
                
            });

            $('.checkGuarantor').on('focusout', function(e) {

                var url = '{{ route('user.checkGuarantor') }}';
                var value = $(this).val();
                var token = '{{ csrf_token() }}';
                    
                var data = {
                    mobile: value,
                    _token: token
                };

                $.post(url, data, function(response) {
                    if (response.data != false) {
                        $(`.${response.type}Exist`).html(`Guarantor exists, <a href="#load" id="load">load</a>?`);
                        _response = response.data;
                    } else {
                        $('#guarantor_id').val('0');
                        $('#guarantor_name').val('');
                        $(`.${response.type}Exist`).empty();
                    }
                });
            });

        })(jQuery);
    </script>
@endpush

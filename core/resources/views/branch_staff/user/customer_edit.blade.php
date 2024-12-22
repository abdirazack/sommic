@extends('branch_staff.layouts.app')
@section('panel')
    <form id="form" method="POST" action="{{route('staff.account.updateIndividual', $customer->account()->first()->id)}}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="card b-radius--10 mb-3">
            <div class="card-header">
                <h3 class="card-title text-center">@lang('Personal Details')</h3>
            </div>
            <div class="card-body">
                <input name="person_id" type="hidden" id="person_id" value="{{ $customer->id }}">
                <div class="row" id="person">
                    <div class="col-md-6 col-xl-4">
                        <div class="form-group">
                            <label for="image">@lang('Image')</label>
                            <div class="image-upload">
                                <div class="thumb">
                                    <div class="avatar-preview">
                                        <div class="profilePicPreview" style="background-image: url({{ getImage(getFilePath('userProfile').'/'.$customer->misc->image, getFileSize('userProfile')) }})">
                                            <button class="remove-image" type="button"><i class="fa fa-times"></i></button>
                                        </div>
                                    </div>

                                    <div class="avatar-edit">
                                        <input class="profilePicUpload" name="image" id="image" type="file" accept=".pdf, .png, .jpg, .jpeg">
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
                                    <input class="form-control" name="name" type="text" id="name" value="{{ old('name', $customer->name) }}" required>
                                </div>
                            </div>

                            <div class="col-xl-3 col-sm-3">
                                <div class="form-group">
                                    <label for="gender">@lang('Gender')</label>
                                    <select class="form-control" name="gender" id="gender" required>
                                        <option selected disabled value>@lang('Select Gender')</option>
                                        <option value="1" {{ old('gender', $customer->misc->gender) == '1' ? 'selected' : ''}}>@lang('Male')</option>
                                        <option value="2" {{ old('gender', $customer->misc->gender) == '2' ? 'selected' : ''}}>@lang('Female')</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-xl-4 col-sm-4">
                                <div class="form-group">
                                    <label for="nationality">@lang('Nationality')</label>
                                    <select class="form-control" name="nationality" id="nationality" required>
                                        <option selected disabled value>@lang('Select Nationality')</option>
                                        @foreach ($countries as $key => $country)
                                            <option data-mobile_code="{{ $country->dial_code }}" value="{{ $key }}" {{ old('nationality', $customer->misc->nationality) == $key ? "selected" : "" }}>{{ __($country->country) }}</option>
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
                                            <option value="{{ $key }}" {{ old('pob', $customer->misc->pob) == $key ? "selected" : "" }}>{{ __($country->country) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-xl-4 col-sm-4">
                                <div class="form-group">
                                    <label for="dob">@lang('Date of Birth')</label>
                                    <input class="form-control" name="dob" type="date" id="dob" value="{{ old('dob', $customer->misc->dob) }}" required>
                                </div>
                            </div>

                            <div class="col-xl-4 col-sm-4">
                                <div class="form-group">
                                    <label for="region">@lang('Region')</label>
                                    <input class="form-control" name="region" type="text" id="region" value="{{ old('region', $customer->region) }}" required>
                                </div>
                            </div>

                            <div class="col-xl-4 col-sm-4">
                                <div class="form-group">
                                    <label for="city">@lang('City')</label>
                                    <input class="form-control" name="city" type="text" id="city" value="{{ old('city', $customer->city) }}" required>
                                </div>
                            </div>

                            <div class="col-xl-4 col-sm-4">
                                <div class="form-group">
                                    <label for="address">@lang('Address')</label>
                                    <input class="form-control" name="address" type="text" id="address" value="{{ old('address', $customer->address) }}" required>
                                </div>
                            </div>

                            <div class="col-xl-4 col-sm-4">
                                <div class="form-group">
                                    <label for="mobile">@lang('Mobile Number')</label>
                                    <input class="form-control checkUser" name="mobile" type="text" id="mobile" value="{{ old('mobile', $customer->mobile) }}" required>
                                    <small class="text-danger mobileExist"></small>
                                </div>
                            </div>

                            <div class="col-xl-4 col-sm-4">
                                <div class="form-group">
                                    <label for="email">@lang('E-Mail Address')</label>
                                    <input class="form-control" name="email" type="email" id="email" value="{{ old('email', $customer->email) }}" required>
                                </div>
                            </div>

                            <div class="col-xl-4 col-sm-4">
                                <div class="form-group">
                                    <label for="marital">@lang('Marital Status')</label>
                                    <select class="form-control" name="marital" id="marital" required>
                                        <option selected disabled value>@lang('Select Marital Status')</option>
                                        <option value="1" {{ old('marital', $customer->misc->marital) == '1' ? 'selected' : '' }}>@lang('Single')</option>
                                        <option value="2" {{ old('marital', $customer->misc->marital) == '2' ? 'selected' : '' }}>@lang('Married')</option>
                                        <option value="3" {{ old('marital', $customer->misc->marital) == '3' ? 'selected' : '' }}>@lang('Divorcee')</option>
                                        <option value="4" {{ old('marital', $customer->misc->marital) == '4' ? 'selected' : '' }}>@lang('Widow')</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-xl-6 col-sm-6">
                                <div class="form-group">
                                    <label for="employment_status">@lang('Employment Status')</label>
                                    <select class="form-control" name="employment_status" id="employment_status" required>
                                        <option selected disabled value>@lang('Select Employment Status')</option>
                                        <option value="1" {{ old('employment_status', $customer->misc->employment_status) == '1' ? 'selected' : '' }}>@lang('Student')</option>
                                        <option value="2" {{ old('employment_status', $customer->misc->employment_status) == '2' ? 'selected' : '' }}>@lang('Self-Employed')</option>
                                        <option value="3" {{ old('employment_status', $customer->misc->employment_status) == '3' ? 'selected' : '' }}>@lang('Employed')</option>
                                        <option value="4" {{ old('employment_status', $customer->misc->employment_status) == '4' ? 'selected' : '' }}>@lang('Unemployed')</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-xl-6 col-sm-6">
                                <div class="form-group">
                                    <label for="employment_detail">@lang('Detail')</label>
                                    <input class="form-control" name="employment_detail" type="text" id="employment_detail" value="{{ old('employment_detail', $customer->misc->employment_detail) }}" required>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card b-radius--10 mb-3">
            <div class="card-header">
                <h3 class="card-title text-center">@lang('Next of Kin Details')</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-xl-6 col-sm-6">
                        <div class="form-group">
                            <label for="nok1_name">@lang('Full Name')</label>
                            <input class="form-control" name="nok1_name" type="text" id="nok1_name" value="{{ old('nok1_name', $customer->account->first() && $customer->account->first()->misc ? $customer->account->first()->misc->nok->p1_nok->nok1->name : '') }}" required>
                        </div>
                    </div>

                    <div class="col-xl-6 col-sm-6">
                        <div class="form-group">
                            <label for="nok1_relation">@lang('Relationship')</label>
                            <select class="form-control" name="nok1_relation" id="nok1_relation" required>
                                <option selected disabled value>@lang('Select Relationship')</option>
                                <option value="1" {{ old('nok1_relation', $customer->account->first() && $customer->account->first()->misc ? $customer->account->first()->misc->nok->p1_nok->nok1->relation : '') == '1' ? 'selected' : '' }}>@lang('Parent')</option>
                                <option value="2" {{ old('nok1_relation', $customer->account->first() && $customer->account->first()->misc ? $customer->account->first()->misc->nok->p1_nok->nok1->relation : '') == '2' ? 'selected' : '' }}>@lang('Sibling')</option>
                                <option value="3" {{ old('nok1_relation', $customer->account->first() && $customer->account->first()->misc ? $customer->account->first()->misc->nok->p1_nok->nok1->relation : '') == '3' ? 'selected' : '' }}>@lang('Spouse')</option>
                                <option value="4" {{ old('nok1_relation', $customer->account->first() && $customer->account->first()->misc ? $customer->account->first()->misc->nok->p1_nok->nok1->relation : '') == '4' ? 'selected' : '' }}>@lang('Child')</option>
                                <option value="5" {{ old('nok1_relation', $customer->account->first() && $customer->account->first()->misc ? $customer->account->first()->misc->nok->p1_nok->nok1->relation : '') == '5' ? 'selected' : '' }}>@lang('Relative')</option>
                                <option value="6" {{ old('nok1_relation', $customer->account->first() && $customer->account->first()->misc ? $customer->account->first()->misc->nok->p1_nok->nok1->relation : '') == '6' ? 'selected' : '' }}>@lang('Beneficiary')</option>
                                <option value="7" {{ old('nok1_relation', $customer->account->first() && $customer->account->first()->misc ? $customer->account->first()->misc->nok->p1_nok->nok1->relation : '') == '7' ? 'selected' : '' }}>@lang('Friend')</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-xl-6 col-sm-6">
                        <div class="form-group">
                            <label for="nok1_mobile">@lang('Mobile Number')</label>
                            <input class="form-control" name="nok1_mobile" type="text" id="nok1_mobile" value="{{ old('nok1_mobile', $customer->account->first() && $customer->account->first()->misc ? $customer->account->first()->misc->nok->p1_nok->nok1->mobile : '') }}" required>
                        </div>
                    </div>

                    <div class="col-xl-6 col-sm-6">
                        <div class="form-group">
                            <label for="nok2_name">@lang('Full Name')</label>
                            <input class="form-control" name="nok2_name" type="text" id="nok2_name" value="{{ old('nok2_name', $customer->account->first() && $customer->account->first()->misc ? $customer->account->first()->misc->nok->p1_nok->nok2->name : '') }}" required>
                        </div>
                    </div>

                    <div class="col-xl-6 col-sm-6">
                        <div class="form-group">
                            <label for="nok2_relation">@lang('Relationship')</label>
                            <select class="form-control" name="nok2_relation" id="nok2_relation" required>
                                <option selected disabled value>@lang('Select Relationship')</option>
                                <option value="1" {{ old('nok2_relation', $customer->account->first() && $customer->account->first()->misc ? $customer->account->first()->misc->nok->p1_nok->nok2->relation : '') == '1' ? 'selected' : '' }}>@lang('Parent')</option>
                                <option value="2" {{ old('nok2_relation', $customer->account->first() && $customer->account->first()->misc ? $customer->account->first()->misc->nok->p1_nok->nok2->relation : '') == '2' ? 'selected' : '' }}>@lang('Sibling')</option>
                                <option value="3" {{ old('nok2_relation', $customer->account->first() && $customer->account->first()->misc ? $customer->account->first()->misc->nok->p1_nok->nok2->relation : '') == '3' ? 'selected' : '' }}>@lang('Spouse')</option>
                                <option value="4" {{ old('nok2_relation', $customer->account->first() && $customer->account->first()->misc ? $customer->account->first()->misc->nok->p1_nok->nok2->relation : '') == '4' ? 'selected' : '' }}>@lang('Child')</option>
                                <option value="5" {{ old('nok2_relation', $customer->account->first() && $customer->account->first()->misc ? $customer->account->first()->misc->nok->p1_nok->nok2->relation : '') == '5' ? 'selected' : '' }}>@lang('Relative')</option>
                                <option value="6" {{ old('nok2_relation', $customer->account->first() && $customer->account->first()->misc ? $customer->account->first()->misc->nok->p1_nok->nok2->relation : '') == '6' ? 'selected' : '' }}>@lang('Beneficiary')</option>
                                <option value="7" {{ old('nok2_relation', $customer->account->first() && $customer->account->first()->misc ? $customer->account->first()->misc->nok->p1_nok->nok2->relation : '') == '7' ? 'selected' : '' }}>@lang('Friend')</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-xl-6 col-sm-6">
                        <div class="form-group">
                            <label for="nok2_mobile">@lang('Mobile Number')</label>
                            <input class="form-control" name="nok2_mobile" type="text" id="nok2_mobile" value="{{ old('nok2_mobile', $customer->account->first() && $customer->account->first()->misc ? $customer->account->first()->misc->nok->p1_nok->nok2->mobile : '') }}" required>
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
                    <div class="col-xl-6 col-sm-6">
                        <div class="form-group">
                            <label for="account_type">@lang('Account Type')</label>
                            <select class="form-control" name="account_type" id="account_type" required>
                                <option selected disabled value>@lang('Select Account Type')</option>
                                <option value="1" {{ old('account_type', $customer->account->first()->account_type) == '1' ? 'selected' : '' }}>@lang('Savings')</option>
                                <option value="2" {{ old('account_type', $customer->account->first()->account_type) == '2' ? 'selected' : '' }}>@lang('Current')</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-xl-6 col-sm-6">
                        <div class="form-group">
                            <label for="account_number">@lang('Account Number')</label>
                            <input class="form-control" name="account_number" type="text" id="account_number" value="{{ old('account_number', $customer->account()->first()->account_number) }}" readonly>
                        </div>
                    </div>

                    <div class="col-xl-6 col-sm-6">
                        <div class="form-group">
                            <label for="withdraw_amount_limit">@lang('Daily Withdrawal Amount Limit')</label>
                            <input class="form-control" name="withdraw_amount_limit" type="number" id="withdraw_amount_limit" value="{{ old('withdraw_amount_limit', $customer->account()->first()->misc->daily_withdraw_amount_limit) }}" required>
                        </div>
                    </div>

                    <div class="col-xl-6 col-sm-6">
                        <div class="form-group">
                            <label for="withdraw_freq_limit">@lang('Daily Withdrawal Frequency Limit')</label>
                            <input class="form-control" name="withdraw_freq_limit" type="number" id="withdraw_freq_limit" value="{{ old('withdraw_freq_limit', $customer->account()->first()->misc->daily_withdraw_freq_limit) }}" required>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card b-radius--10 mb-3">
            <div class="card-header">
                <h3 class="card-title text-center">@lang('Identification Details')</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-xl-4 col-sm-4">
                        <div class="form-group">
                            <label for="identifier_type">@lang('Document Type')</label>
                            <select name="identifier_type" class="form-control" id="identifier_type" required>
                                <option selected disabled value>@lang('Select Document Type')</option>
                                <option value="1" {{ old('identifier_type', $customer->identifier_type) == '1' ? 'selected' : '' }} >@lang('Passport')</option>
                                <option value="2" {{ old('identifier_type', $customer->identifier_type) == '2' ? 'selected' : '' }} >@lang('Government ID')</option>
                                <option value="3" {{ old('identifier_type', $customer->identifier_type) == '3' ? 'selected' : '' }} >@lang('Driver\'s License')</option>
                                <option value="4" {{ old('identifier_type', $customer->identifier_type) == '4' ? 'selected' : '' }} >@lang('Birth Certificate')</option>
                                <option value="5" {{ old('identifier_type', $customer->identifier_type) == '5' ? 'selected' : '' }} >@lang('Student ID')</option>
                                <option value="6" {{ old('identifier_type', $customer->identifier_type) == '6' ? 'selected' : '' }} >@lang('Court Document')</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-xl-4 col-sm-4">
                        <div class="form-group">
                            <label for="identifier_expiry_date">@lang('Document Expiry Date')</label>
                            <input class="form-control" name="identifier_expiry_date" type="date" id="identifier_expiry_date" value="{{ old('identifier_expiry_date', $customer->identifier_expiry_date) }}" required>
                        </div>
                    </div>

                    <div class="col-xl-4 col-sm-4">
                        <div class="form-group">
                            <label for="identifier_link">@lang('Document Scan')</label>
                            <input class="form-control" name="identifier_link" type="file" accept=".pdf, .png, .jpg, .jpeg" id="identifier_link">
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
            $(document).ready(() => {
                let dialCode = $('select[name=nationality] :selected').data('mobile_code');
                $(`.mobile-code option[value='${dialCode}']`).prop('selected', true);

                $('select[name=nationality]').change(function() {
                    let dialCode = $('select[name=nationality] :selected').data('mobile_code');
                    $(`.mobile-code option[value='${dialCode}']`).prop('selected', true);
                });
            });

            $('.checkUser').on('focusout', function(e) {
                var url = '{{ route('user.checkUser') }}';
                var value = $(this).val();
                var token = '{{ csrf_token() }}';

                if ($(this).attr('name') == 'mobile') {
                    var mobile = value;

                    var data = {
                        mobile: mobile,
                        _token: token
                    };
                }

                $.post(url, data, function(response) {
                    if (response.data != false && response.data.id != {{ $customer->id }}) {
                        $(`.${response.type}Exist`).html(`Mobile exists`);
                    } else {
                        $(`.${response.type}Exist`).empty();
                    }
                });
            });
        })(jQuery);
    </script>
@endpush

@extends('layouts.main')

@section('main-content')
<div class="col-md-8 offset-md-2">
  @if(session()->has('error_status'))
  <div class="alert alert-warning" role="alert">
    {{ session('error_status') }}
  </div>
  @endif
  <div class="card card-info">
    {{-- form title --}}
    <div class="card-header">
      <h3 class="card-title">{{ __('Company') }}{{ __('License') }} {{ __('Create') }}</h3>
    </div>

    {{-- form for store restaurant --}}
    <form action="{{ route('company.license.store') }}" method="POST">
      @csrf
      <div class="card-body">

        {{-- company name input and return error message --}}
        <div class="form-group row">
          <label class="col-sm-4 col-form-label offset-sm-1 col-form-label required">{{__('Company')}} {{__('Name') }}</label>
          <select class="form-control col-sm-6" name="company_id">
            <option value="" selected>{{__('Select Company Name')}}</option>
            {{-- company data list --}}
            @foreach($companyList as $data)
            <option value="{{$data->id}}">{{$data->name}}</option>
            @endforeach
          </select>
        </div>
        <div class="col-sm-6 offset-sm-5">
          @error('company_id')
          <label class="text-danger">&nbsp;*{{ $message }}</label>
          @enderror
        </div>

        {{-- start date input and return error message --}}
        <div class="form-group row">
          <label class="col-sm-4 col-form-label offset-sm-1 col-form-label required">{{ __('Start Date') }}</label>
          <input type="date" class="form-control col-sm-6" name="start_date" placeholder="{{ __('Start Date') }}" value="{{old('start_date')}}">
        </div>
        <div class="col-sm-6 offset-sm-5">
          @error('start_date')
          <label class="text-danger">&nbsp;*{{ $message }}</label>
          @enderror
        </div>

        {{-- end date input and return error message --}}
        <div class="form-group row">
          <label class="col-sm-4 col-form-label offset-sm-1 col-form-label required">{{ __('End Date') }}</label>
          <input type="date" class="form-control col-sm-6" name="end_date" placeholder="{{ __('End Date') }}" value="{{old('end_date')}}">
        </div>
        <div class="col-sm-6 offset-sm-5">
          @error('end_date')
          <label class="text-danger">&nbsp;*{{ $message }}</label>
          @enderror
        </div>

        {{-- status input --}}
        <div class="form-group row">
          <label class="col-form-label col-sm-4 offset-sm-1 required">{{__('Status')}}</label>
          <select name="status" class="form-control col-sm-6">
              <option value="" selected>{{__('Select Status Type')}}</option>
              <option value= "{{ config('constants.COMPANY_LICENSE_INACTIVE') }}" {{ old('status') == config('constants.COMPANY_LICENSE_INACTIVE') ? 'selected': ''}} >Company License Inactive</option>
              <option value= "{{ config('constants.COMPANY_LICENSE_ACTIVE') }}" {{ old('status') == config('constants.COMPANY_LICENSE_ACTIVE') ? 'selected': ''}} >Company License Active</option>
              <option value= "{{ config('constants.COMPANY_LICENSE_EXPIRE') }}" {{ old('status') == config('constants.COMPANY_LICENSE_EXPIRE') ? 'selected': ''}} >Company License Expire</option>
              <option value= "{{ config('constants.COMPANY_LICENSE_BLOCK') }}" {{ old('status') == config('constants.COMPANY_LICENSE_BLOCK') ? 'selected': ''}} >Company License Block</option>
          </select>        
        </div>
        <div class="col-sm-6 offset-sm-5">
          @error('status')
          <label class="text-danger">&nbsp;*{{ $message }}</label>
          @enderror
        </div>

        {{-- type input and return error message --}}
        <div class="form-group row">
          <label class="col-sm-4 col-form-label offset-sm-1 col-form-label required">{{ __('License') }} {{ __('Type') }}</label>
          <select name="license_type" class="form-control col-sm-6">
              <option value="" selected>{{__('Select License Type')}}</option>
              <option value= "{{ config('constants.STANDALONE_POS') }}" {{ old('license_type') == config('constants.STANDALONE_POS') ? 'selected': ''}} >Standalone POS</option>
              <option value= "{{ config('constants.STANDALONE_POS_INVENTORY') }}" {{ old('license_type') == config('constants.STANDALONE_POS_INVENTORY') ? 'selected': ''}} >Standalone POS Inventory</option>
              <option value= "{{ config('constants.MULTI_POS') }}" {{ old('license_type') == config('constants.MULTI_POS') ? 'selected': ''}} >Multi POS</option>
              <option value= "{{ config('constants.MULTI_POS_INVENTORY') }}" {{ old('license_type') == config('constants.MULTI_POS_INVENTORY') ? 'selected': ''}} >Multi POS Inventory</option>
          </select>        
        </div>
        <div class="col-sm-6 offset-sm-5">
          @error('license_type')
          <label class="text-danger">&nbsp;*{{ $message }}</label>
          @enderror
        </div>

        {{-- user count input and return error message --}}
        <div class="form-group row">
          <label class="col-sm-4 col-form-label offset-sm-1 col-form-label required">{{ __('Available Users') }}</label>
          <select name="user_count" class="form-control col-sm-6">
              <option value="" selected>{{__('Select User Count')}}</option>
              <option value= "1" {{ old('user_count') == 1 ? 'selected': ''}} >1</option>
              <option value= "3" {{ old('user_count') == 3 ? 'selected': ''}} >3</option>
              <option value= "5" {{ old('user_count') == 5 ? 'selected': ''}} >5</option>
              <option value= "10" {{ old('user_count') == 10 ? 'selected': ''}} >10</option>
          </select>        
        </div>
        <div class="col-sm-6 offset-sm-5">
          @error('user_count')
          <label class="text-danger">&nbsp;*{{ $message }}</label>
          @enderror
        </div>

        {{-- payment input and return error message --}}
        <div class="form-group row">
          <label class="col-sm-4 col-form-label offset-sm-1 col-form-label required">{{ __('Payment Amount') }}</label>
          <input type="number" min="0" max="10000000" class="form-control col-sm-6" name="payment_amount" placeholder="{{ __('Payment Amount') }}" value="{{old('payment_amount')}}">
        </div>
        <div class="col-sm-6 offset-sm-5">
          @error('payment_amount')
          <label class="text-danger">&nbsp;*{{ $message }}</label>
          @enderror
        </div>

        {{-- discount input and return error message --}}
        <div class="form-group row">
          <label class="col-sm-4 col-form-label offset-sm-1 col-form-label">{{ __('Discount Amount') }}</label>
          <input type="number" min="0" max="10000000" value=0 class="form-control col-sm-6" name="discount_amount" placeholder="{{ __('Discount Amount') }}" value="{{old('discount_amount')}}">
        </div>
        <div class="col-sm-6 offset-sm-5">
          @error('discount_amount')
          <label class="text-danger">&nbsp;*{{ $message }}</label>
          @enderror
        </div>

        {{-- contact person generate checkboxes and return error message --}}
        <div class="form-group row">
          <div class="col-sm-5 offset-sm-5">
            <input type="checkbox" class="contact_check" name="contact_check" value="1" checked>
            <label class="form-check-label">{{__('Same Company Contact Flag')}}</label><br />
            <input type="checkbox" class="contact_check" name="contact_check" value="2">
            <label class="form-check-label">{{__('Input Contact Person manually')}}</label>
          </div>
        </div>

        {{-- name input and return error message --}}
        <div class="form-group row">
          <label class="col-sm-4 col-form-label offset-sm-1 col-form-label">{{ __('Contact Name') }}</label>
          <input type="text" class="form-control col-sm-6"  id="contact_person_name" disabled="" name="contact_person" placeholder="{{ __('Contact Name') }}" value="{{old('contact_person')}}">
        </div>
        <div class="col-sm-6 offset-sm-5">
          @error('contact_person')
          <label class="text-danger">&nbsp;*{{ $message }}</label>
          @enderror
        </div>

        {{-- phone input and return error message --}}
        <div class="form-group row">
          <label class="col-sm-4 col-form-label offset-sm-1 col-form-label">{{ __('Contact Phone') }}</label>
          <input type="text" class="form-control col-sm-6"  id="contact_person_phone" disabled="" name="contact_phone" placeholder="{{ __('Contact Phone') }}" value="{{old('contact_phone')}}">
        </div>
        <div class="col-sm-6 offset-sm-5">
          @error('contact_phone')
          <label class="text-danger">&nbsp;*{{ $message }}</label>
          @enderror
        </div>

        {{-- email input and return error message --}}
        <div class="form-group row">
          <label class="col-sm-4 col-form-label offset-sm-1 col-form-label required">{{ __('Contact Email') }}</label>
          <input type="email" class="form-control col-sm-6" name="contact_email" placeholder="{{ __('Contact Email') }}" value="{{old('contact_email')}}">
        </div>
        <div class="col-sm-6 offset-sm-5">
          @error('contact_email')
          <label class="text-danger">&nbsp;*{{ $message }}</label>
          @enderror
        </div>
        
        {{-- submit and back button --}}
        <div class="card-footer row">
          <button type="submit" class="btn btn-primary offset-sm-5">{{ __('Submit') }}</button>
          <a href="{{ url('company/license') }}" class="btn btn-info mx-sm-4">{{ __('Back') }}</a>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection
@section("js")
<script src="{{asset('js/companylicense/create.js')}}"></script>
@endsection

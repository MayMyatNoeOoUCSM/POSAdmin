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
      <h3 class="card-title">{{ __('Shop') }} {{ __('Create') }}</h3>
    </div>

    {{-- form for store shop --}}
    <form action="{{ route('shop.store') }}" method="POST">
      @csrf
      <div class="card-body">
        {{-- name input and return error message --}}
        <div class="form-group row">
          <label class="col-sm-4 col-form-label offset-sm-1 col-form-label required">{{ __('Shop') }} {{ __('Name') }}</label>
          <input type="text" class="form-control col-sm-6" name="name" placeholder="{{ __('Shop') }} {{ __('Name') }}" value="{{old('name')}}">
        </div>
        <div class="col-sm-6 offset-sm-5">
          @error('name')
          <label class="text-danger">&nbsp;*{{ $message }}</label>
          @enderror
        </div>

        {{-- shop type input --}}
          <div class="form-group row">
            <label class="col-sm-4 col-form-label offset-sm-1 col-form-label required">{{__('Shop')}} {{__('Type')}}</label>
            <select name="shop_type" class="form-control col-sm-6">
              <option value="" selected>{{ __('Select Shop Type') }}</option>
              <option value= "{{ config('constants.RETAILS_SHOP') }}" {{ old('shop_type') == config('constants.RETAILS_SHOP') ? 'selected': ''}}>Retails</option>
              <option value= "{{ config('constants.RESTAURANT_SHOP') }}" {{ old('shop_type') == config('constants.RESTAURANT_SHOP') ? 'selected': ''}}>Restaurant</option>
            </select>
          </div>
        <div class="col-sm-6 offset-sm-5">
          @error('shop_type')
          <label class="text-danger">&nbsp;*{{ $message }}</label>
          @enderror
        </div>

        {{-- address input and return error message --}}
        <div class="form-group row">
          <label class="col-sm-4 col-form-label offset-sm-1 col-form-label required">{{ __('Address') }}</label>
          <textarea class="form-control col-sm-6" name="address" placeholder="{{ __('Address')}}">{{old('address')}}</textarea>
        </div>
        <div class="col-sm-6 offset-sm-5">
          @error('address')
          <label class="text-danger">&nbsp;*{{ $message }}</label>
          @enderror
        </div>

        {{-- primary phone input and return error message --}}
        <div class="form-group row">
          <label class="col-sm-4 col-form-label offset-sm-1">{{ __('Primary Phone') }}</label>
          <input type="text" class="form-control col-sm-6" name="phone_number_1" placeholder="{{ __('Primary Phone') }}" value="{{old('phone_number_1')}}">
        </div>
        <div class="col-sm-6 offset-sm-5">
          @error('phone_number_1')
          <label class="text-danger">&nbsp;*{{ $message }}</label>
          @enderror
        </div>

        {{-- secondary phone input and return error message --}}
        <div class="form-group row">
          <label class="col-sm-4 col-form-label offset-sm-1">{{ __('Secondary Phone') }}</label>
          <input type="text" class="form-control col-sm-6" name="phone_number_2" placeholder="{{ __('Secondary Phone') }}" value="{{old('phone_number_2')}}">
        </div>
        <div class="col-sm-6 offset-sm-5">
          @error('phone_number_2')
          <label class="text-danger">&nbsp;*{{ $message }}</label>
          @enderror
        </div>

        {{-- submit and back button --}}
        <div class="card-footer row">
          <button type="submit" class="btn btn-primary offset-sm-5">{{ __('Submit') }}</button>
          <a href="{{ url('shop') }}" class="btn btn-info mx-sm-4">{{ __('Back') }}</a>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection

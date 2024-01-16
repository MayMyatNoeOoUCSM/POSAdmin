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
      <h3 class="card-title">{{ __('Company') }} {{ __('Create') }}</h3>
    </div>

    {{-- form for store company --}}
    <form action="{{ route('company.store') }}" enctype="multipart/form-data"  method="POST">
      @csrf
      <div class="card-body">
        {{-- name input and return error message --}}
        <div class="form-group row">
          <label class="col-sm-4 col-form-label offset-sm-1 col-form-label required">{{ __('Company') }} {{ __('Name') }}</label>
          <input type="text" class="form-control col-sm-6" name="name" placeholder="{{ __('Company') }} {{ __('Name') }}" value="{{old('name')}}">
        </div>
        <div class="col-sm-6 offset-sm-5">
          @error('name')
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

        {{-- company logo input and return error message --}}
        <div class="form-group row">
          <label class="col-sm-4 offset-sm-1 col-form-label">{{ __('Company')}} {{__('Profile')}}</label>
          <input type="file" name="image" id="profile" onchange="putImage();"/>
        </div>
        <div class="form-group offset-sm-5">
          <img id="target" width="100" style="display:none;" />
        </div>
        <div class="offset-sm-5">
          @error('image')
          <label class="text-danger">&nbsp;*{{ $message }}</label>
          @enderror
        </div>

        {{-- primary phone input and return error message --}}
        <div class="form-group row">
          <label class="col-sm-4 col-form-label offset-sm-1">{{ __('Primary Phone') }}</label>
          <input type="text" class="form-control col-sm-6" name="primary_phone" placeholder="{{ __('Primary Phone') }}" value="{{old('phone_number_1')}}">
        </div>
        <div class="col-sm-6 offset-sm-5">
          @error('primary_phone')
          <label class="text-danger">&nbsp;*{{ $message }}</label>
          @enderror
        </div>

        {{-- secondary phone input and return error message --}}
        <div class="form-group row">
          <label class="col-sm-4 col-form-label offset-sm-1">{{ __('Secondary Phone') }}</label>
          <input type="text" class="form-control col-sm-6" name="secondary_phone" placeholder="{{ __('Secondary Phone') }}" value="{{old('phone_number_2')}}">
        </div>
        <div class="col-sm-6 offset-sm-5">
          @error('secondary_phone')
          <label class="text-danger">&nbsp;*{{ $message }}</label>
          @enderror
        </div>

        {{-- submit and back button --}}
        <div class="card-footer row">
          <button type="submit" class="btn btn-primary offset-sm-5">{{ __('Submit') }}</button>
          <a href="{{ url('company') }}" class="btn btn-info mx-sm-4">{{ __('Back') }}</a>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection
@section("js")
<script src="{{asset('js/company/create.js')}}"></script>
@endsection



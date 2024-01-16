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
      <h3 class="card-title">{{ __('Warehouse') }} {{ __('Edit') }}</h3>
    </div>

    {{-- form update warehouse --}}
    <form action="{{  url('/warehouse/' . $warehouse->id ) }}" method="POST">
      @csrf
      @method('PUT')
      <div class="card-body">
        {{-- name input and return error message --}}
        <div class="form-group row">
          <label class="col-sm-4 col-form-label offset-sm-1">{{ __('Warehouse') }} {{ __('Name') }}</label>
          <input type="text" class="form-control col-sm-6" name="name" placeholder="{{ __('Warehouse') }} {{ __('Name') }}" value="{{$warehouse->name}}" readonly>
        </div>
        <div class="col-sm-6 offset-sm-5">
          @error('name')
          <label class="text-danger">&nbsp;*{{ $message }}</label>
          @enderror
        </div>

        {{-- address input and return error message --}}
        <div class="form-group row">
          <label class="col-sm-4 col-form-label offset-sm-1 col-form-label required">{{ __('Address') }}</label>
          <textarea class="form-control col-sm-6" name="address" placeholder="{{ __('Address') }}">{{$warehouse->address}}</textarea>
        </div>
        <div class="col-sm-6 offset-sm-5">
          @error('address')
          <label class="text-danger">&nbsp;*{{ $message }}</label>
          @enderror
        </div>

        {{-- primary phone input and return error message --}}
        <div class="form-group row">
          <label class="col-sm-4 col-form-label offset-sm-1">{{ __('Primary Phone') }}</label>
          <input type="text" class="form-control col-sm-6" name="phone_number_1" placeholder="{{ __('Primary Phone') }}" value="{{$warehouse->phone_number_1}}">
        </div>
        <div class="col-sm-6 offset-sm-5">
          @error('phone_number_1')
          <label class="text-danger">&nbsp;*{{ $message }}</label>
          @enderror
        </div>

        {{-- secondary phone input and return error message --}}
        <div class="form-group row">
          <label class="col-sm-4 col-form-label offset-sm-1">{{ __('Secondary Phone') }}</label>
          <input type="text" class="form-control col-sm-6" name="phone_number_2" placeholder="{{ __('Secondary Phone') }}" value="{{$warehouse->phone_number_2}}">
        </div>
        <div class="col-sm-6 offset-sm-5">
          @error('phone_number_2')
          <label class="text-danger">&nbsp;*{{ $message }}</label>
          @enderror
        </div>

        {{-- submit and back button --}}
        <div class="card-footer row">
          <button type="submit" class="btn btn-primary offset-sm-5">{{ __('Submit') }}</button>
          <a href="{{ url('warehouse') }}" class="btn btn-info mx-sm-4">{{ __('Back') }}</a>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection

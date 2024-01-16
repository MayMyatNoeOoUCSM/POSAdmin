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
      <h3 class="card-title">{{ __('Restaurant Table') }} {{ __('Edit') }}</h3>
    </div>

    {{-- form update restaurant --}}
    <form action="{{  url('/restaurant/' . $restaurant->id ) }}" method="POST">
       <input type="hidden" name="id" value="{{$restaurant->id}}">
      @csrf
      @method('PUT')
      <div class="card-body">
        {{-- name input and return error message --}}
        <div class="form-group row">
          <label class="col-sm-4 col-form-label offset-sm-1 required">{{ __('Table') }} {{ __('Name') }}</label>
          <input type="text" class="form-control col-sm-6" name="name" placeholder="{{ __('Table') }} {{ __('Name') }}" value="{{$restaurant->name}}">
        </div>
        <div class="col-sm-6 offset-sm-5">
          @error('name')
          <label class="text-danger">&nbsp;*{{ $message }}</label>
          @enderror
        </div>

        {{-- shop name input and return error message --}}
        <div class="form-group row">
          <label class="col-sm-4 col-form-label offset-sm-1 col-form-label required">{{ __('Shop') }} {{ __('Name') }}</label>
           <input type="text" class="form-control col-sm-6" name="shop_name" value="{{$restaurant->shop->name}}" readonly>
           <input type="hidden" class="form-control col-sm-6" name="shop_id" value="{{$restaurant->shop_id}}" readonly>
         <!--  <select class="form-control col-sm-6" name="shop_id">
            {{-- shop data list --}}
            @foreach($shopList as $data)
            <option value="{{$data->id}}" {{$restaurant->shop_id == $data->id ? 'selected':''}}>{{$data->name}}</option>
            @endforeach
          </select> -->
        </div>
        <div class="col-sm-6 offset-sm-5">
          @error('address')
          <label class="text-danger">&nbsp;*{{ $message }}</label>
          @enderror
        </div>

        {{-- total seat people input and return error message --}}
        <div class="form-group row">
          <label class="col-sm-4 col-form-label offset-sm-1 col-form-label required">{{ __('Total') }} {{ __('Seat') }} {{ __('People') }}</label>
          <input type="number" class="form-control col-sm-6" name="total_seat_people" placeholder="{{ __('Total') }} {{ __('Seat') }} {{ __('People') }}" value="{{$restaurant->total_seats_people}}">
        </div>
        <div class="col-sm-6 offset-sm-5">
          @error('total_seat_people')
          <label class="text-danger">&nbsp;*{{ $message }}</label>
          @enderror
        </div>

        {{-- status input --}}
        <div class="form-group row">
          <label class="col-sm-4 col-form-label offset-sm-1">{{__('Available')}} {{__('Status')}}</label>
          <input type="checkbox" class="col-form-input" name="active" {{ $restaurant->available_status == config('constants.ACTIVE') ? 'checked' : '' }}>
        </div>

        {{-- submit and back button --}}
        <div class="card-footer row">
          <button type="submit" class="btn btn-primary offset-sm-5">{{ __('Submit') }}</button>
          <a href="{{ url('restaurant') }}" class="btn btn-info mx-sm-4">{{ __('Back') }}</a>
        </div>
      </div>
    </form>
  </div>
</div>
<style type="text/css">
  input[type=checkbox]{
    margin-top: 10px !important;
  }
</style>
@endsection

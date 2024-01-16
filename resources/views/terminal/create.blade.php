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
      <h3 class="card-title">{{ __('Terminal') }} {{ __('Create') }}</h3>
    </div>

    {{-- form for store terminal --}}
    <form action="{{ route('terminal.store') }}" method="POST">
      @csrf
      <div class="card-body">
        {{-- name input and return error message --}}
        <div class="form-group row">
          <label class="col-sm-4 col-form-label offset-sm-1 col-form-label required">{{ __('Terminal') }} {{ __('Name') }}</label>
          <input type="text" class="form-control col-sm-6" name="name" placeholder="{{ __('Terminal') }} {{ __('Name') }}" value="{{old('name')}}">
        </div>
        <div class="col-sm-6 offset-sm-5">
          @error('name')
          <label class="text-danger">&nbsp;*{{ $message }}</label>
          @enderror
        </div>

        {{-- shop name input and return error message --}}
        <div class="form-group row">
          <label class="col-sm-4 col-form-label offset-sm-1 col-form-label required">{{ __('Shop') }} {{ __('Name') }}</label>
          <select class="form-control col-sm-6" name="shop_id">
            <option value="" selected>{{__('Select Shop')}}</option>
            {{-- shop data list --}}
            @foreach($shopList as $data)
            <option value="{{$data->id}}" {{old('shop_id')==$data->id?'selected':''}}>{{$data->name}}</option>
            @endforeach
          </select>
        </div>
        <div class="col-sm-6 offset-sm-5">
          @error('shop_id')
          <label class="text-danger">&nbsp;*{{ $message }}</label>
          @enderror
        </div>

        {{-- submit and back button --}}
        <div class="card-footer row">
          <button type="submit" class="btn btn-primary offset-sm-5">{{ __('Submit') }}</button>
          <a href="{{ url('terminal') }}" class="btn btn-info mx-sm-4">{{ __('Back') }}</a>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection

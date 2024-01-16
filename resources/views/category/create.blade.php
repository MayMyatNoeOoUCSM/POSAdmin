@extends('layouts.main')

@section('title','Category Create')

@section('main-content')
<div class = "col-md-8 offset-md-2">
  @if(session()->has('error_status'))
    <div class="alert alert-warning" role="alert">
    {{ session('error_status') }}
    </div>
  @endif
  <div class="card card-info">
    {{-- form title --}}
    <div class="card-header">
      <h3 class="card-title">{{ __('Category') }} {{ __('Create') }}</h3>
    </div>

    {{-- form for store category --}}
    <form action="{{ route('category.store') }}" method = "POST">
      @csrf
      <div class="card-body">
        {{-- name input and return error message --}}
        <div class="form-group row">
          <label class="col-sm-4 col-form-label offset-sm-1 required">{{ __('Category') }} {{ __('Name') }}</label>
          <input type="text" class="form-control col-sm-6" name="name" placeholder="{{ __('Category') }} {{ __('Name') }}" value="{{old('name')}}">
        </div>
        <div class="col-sm-6 offset-sm-5">
        @error('name')
          <label class="text-danger">&nbsp;*{{ $message }}</label>
        @enderror
        </div>

        {{-- parent category id input and return error message --}}
        <div class="form-group row">
          <label class="col-sm-4 col-form-label offset-sm-1">{{ __('Parent') }} {{ __('Category') }}</label>
          <select name="parent_category_id" class="form-control col-sm-6">
            <option value="" selected>{{ __('Select Parent Category Name') }}</option>
            @foreach($categoryList as $category)
              <option value= "{{$category->id}}">{{$category->name}}</option>
            @endforeach
          </select>
        </div>

        {{-- shop input and return error message --}}
        <div class="form-group row">
          <label class="col-sm-4 offset-sm-1 col-form-label required">{{ __('Shop') }} {{__('Name') }}</label>
          <select name="shop_id[]" class="multi-select form-control col-sm-6" multiple>
              @foreach($shopCategoryList as $shopCategory)
              <option value="{{$shopCategory->id}}"
                 @if(in_array($shopCategory->id,old('shop_id')??[])) selected @endif>   
            {{$shopCategory->name}} </option>
              @endforeach
          </select>
        </div>
        <div class="offset-sm-5">
          @error('shop_id')
          <label class="text-danger">&nbsp;*{{ $message }}</label>
          @enderror
        </div>

        {{-- description input and return error message --}}
        <div class="form-group row">
          <label class="col-sm-4 col-form-label offset-sm-1">{{ __('Description') }}</label>
          <textarea class="form-control col-sm-6" name="description" placeholder="{{ __('Description') }}"></textarea>
        </div>
        <div class="col-sm-6 offset-sm-5">
        @error('description')
          <label class="text-danger">&nbsp;*{{ $message }}</label>
        @enderror
        </div>

        {{-- submit and back button --}}
        <div class="card-footer row">
          <button type="submit" class="btn btn-primary offset-sm-5">{{ __('Submit') }}</button>
          <a href="{{ url('category') }}" class="btn btn-info mx-sm-4">{{ __('Back') }}</a>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection

@section('js')
<script src="/js/category/create.js"></script>
@endsection

@extends('layouts.main')

@section('main-content')
<div class="col-md-12">
  @if(session()->has('error_status'))
  <div class="alert alert-warning" role="alert">
    {{ session('error_status') }}
  </div>
  @endif
  <div class="alert alert-warning error" role="alert" style="display:none;">
    <p class="error_status"></p>
  </div>
  <div class="card card-info">
    {{-- form title --}}
    <div class="card-header">
      <h3 class="card-title"> {{__('Stock')}} {{__('Create')}}</h3>
    </div>

    {{-- form for store stock item --}}
    <form action="{{ route('stock.store') }}" method="POST" id="stock_store_form">
      @csrf
      <div class="card-body">
        <div class="form-group row">
          {{-- product category input --}}
          <label class="col-sm-2 col-form-label">{{__('Product')}} {{__('Category')}}</label>
          <div class="col-sm-3">
            <select name="product_category" id="product_category" class="form-control select2" onchange="getSubCategory()">
              <option value="" selected>{{ __('Select Category') }}</option>
              {{-- product category data list --}}
              @foreach($productCategoryList as $category)
              <option value="{{$category->id}}">{{$category->name}}</option>
              @endforeach
            </select>
          </div>
          {{-- sub category input --}}
          <label class="col-sm-2 offset-sm-1 col-form-label">{{__('Sub Category')}}</label>
          <div class="col-sm-3">
            <select name="product_sub_category" id="product_sub_category" class="form-control select2" onchange="getProduct()">
              <option value="" selected>{{ __('Select Sub Category') }}</option>
            </select>
          </div>
        </div>

        {{-- product name input --}}
        <div class="form-group row">
          <label class="col-sm-2 col-form-label">{{__('Product')}} {{__('Name')}}</label>
          <div class="col-sm-3">
            <select name="product_id" id="selectProduct" class="form-control select2">
              <option value="" selected>{{ __('Choose Product') }}</option>
              {{-- product data list --}}
              @foreach($productList as $product)
              <option value="{{$product->id}}" {{ old('product_name') == $product->id ? 'selected' : '' }}> {{$product->name}} </option>
              @endforeach
            </select>
          </div>
        </div>

        {{-- search button --}}
        <div class="form-group">
          <input type="button" class="btn btn-info " id="search" value="{{__('Search')}}">
        </div>

        {{-- stock table --}}
        <div class="table-responsive">
        <table id="productDetailTable" class="table table-bordered text-nowrap">
          <thead class="thead-light">
            <tr>
              <th style="">{{__('Product')}} {{__('Name')}}</th>
              <th style="">{{__('Choose')}} {{__('Warehouse')}} / {{__('Shop')}}</th>
              <th style="">{{__('Name')}}</th>
              <th style="">{{__('Quantity')}}</th>
              <th style="">{{__('Minimum')}} {{__('Quantity')}}</th>
              <th style="">{{__('Unit Price')}}</th>
              <th style="width: 200px">{{__('Remark')}}</th>
              <th style=""></th>
            </tr>
          </thead>
        </table>
        </div>
      </div>

      {{-- submit and back buttons --}}
      <div class="card-footer">
        <button type="button" id="btn_store" class="btn btn-primary">{{__('Submit')}}</button>
        <a href="{{ url('stock') }}" class="btn btn-info mx-sm-2">{{__('Back')}}</a>
      </div>
    </form>
  </div>
</div>
<style type="text/css">
  input[type='text']{
    width: 100px;
    display: block;
    margin-left: auto;
    margin-right: auto;
  }
  select .form-control {
    width: 150px;
    display: block;
    margin-left: auto;
    margin-right: auto;
  }
</style>
@endsection

@section('js')
<script type="text/javascript">
  $('#btn_store').prop('disabled', true);
</script>
<script src="{{asset('js/stock/create.js')}}"></script>
@endsection

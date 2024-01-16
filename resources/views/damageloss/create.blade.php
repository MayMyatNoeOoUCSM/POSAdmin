@extends('layouts.main')

@section('main-content')
<div class="col-md-12">
  @if(session()->has('success_status'))
  <div class="alert alert-info" role="alert">
    {{ session('success_status') }}
  </div>
  @endif
  @if(session()->has('error_status'))
  <div class="alert alert-warning" role="alert">
    {{ session('error_status') }}
  </div>
  @endif
  <div class="alert alert-warning error" role="alert" style="display:none;">
    <p class="error_status"></p>
  </div>

  {{-- form for damage/loss store --}}
  <form action="{{ route('damageloss.store') }}" method="POST" id="damage_loss_store_form">
    <div class="card card-info">
      {{-- form title --}}
      <div class="card-header">
        <h3 class="card-title">{{__('Damage Loss')}} {{__('Create')}}</h3>
      </div>

      @csrf
      <div class="card-body">
        <div class="row">
          {{-- product category input --}}
          <label class="col-sm-2 col-form-label">{{__('Product')}} {{__('Category')}}</label>
          <div class="col-sm-3">
            <select name="product_category" id="product_category" class="form-control select2" onchange="getSubCategory()">
              <option value="" selected>{{__('Select Category')}}</option>
              {{-- productcategory data list --}}
              @foreach($productCategoryList as $category)
              <option value="{{$category->id}}">{{$category->name}}</option>
              @endforeach
            </select>
          </div>
          {{-- sub category input --}}
          <label class="col-sm-2 offset-sm-1 col-form-label">{{__('Sub Category')}}</label>
          <div class="col-sm-3">
            <select name="product_sub_category" id="product_sub_category" class="form-control select2" onchange="getProduct()">
              <option value="" selected>{{__('Select Sub Category')}}</option>
            </select>
          </div>
          {{-- product name input --}}
          <label class="col-sm-2 col-form-label mt-3" id="label_product_name">{{__('Product')}} {{__('Name')}}</label>
          <div class="col-sm-3 mt-3" id="div_product_name">
            <select name="product_id" id="selectProduct" class="form-control select2">
              <option value="" selected>{{__('Choose Product')}}</option>
              {{-- product data list --}}
              @foreach($productList as $product)
              <option value="{{$product->id}}" {{ old('product_name') == $product->id ? 'selected' : '' }}> {{$product->name}} </option>
              @endforeach
            </select>
          </div>
        </div>

        <div class="form-group row">
        </div>
        @if(Auth::guard('staff')->user()->role == config('constants.COMPANY_ADMIN'))
        <div class="form-group row">
          {{-- warehouse name input and return error message --}}
          <label class="col-sm-2 col-form-label">{{__('Warehouse')}} {{__('Name')}}</label>
          <div class="col-sm-3">
            <select name="warehouse_id" id="selectWarehouse" class="form-control damagePlaceSelect select2">
              <option value="" selected>{{__('Choose Warehouse')}}</option>
              @foreach($warehouseList as $warehouse)
              <option value="{{$warehouse->id}}" {{ old('warehouse_name') == $warehouse->id ? 'selected' : '' }}> {{$warehouse->name}} </option>
              @endforeach
            </select>
            @error('warehouse_id')
            <label class="text-danger">&nbsp;*{{ $message }}</label>
            @enderror
          </div>
          {{-- shop name input and return error message --}}
          <label class="col-sm-2 offset-sm-1 col-form-label ">{{__('Shop')}} {{__('Name')}}</label>
          <div class="col-sm-3">
            <select name="shop_id" id="selectShop" class="form-control damagePlaceSelect select2">
              <option value="" selected>{{__('Choose Shop')}}</option>
              @foreach($shopList as $shop)
              <option value="{{$shop->id}}" {{ old('shop_name') == $shop->id ? 'selected' : '' }}> {{$shop->name}} </option>
              @endforeach
            </select>
            @error('shop_id')
            <label class="text-danger">&nbsp;*{{ $message }}</label>
            @enderror
          </div>
           <label class="col-sm-10 text-danger" id="warhouse_shop_required"></label>
        </div>
        @else
        <div class="form-group row">
          {{-- shop name input and return error message --}}
          <label class="col-sm-2 col-form-label ">{{__('Shop')}} {{__('Name')}}</label>
          <div class="col-sm-3">
            <select name="shop_id" id="selectShop" class="form-control damagePlaceSelect select2">
              <option value="" selected>{{__('Choose Shop')}}</option>
              @foreach($shopList as $shop)
              <option value="{{$shop->id}}" {{ old('shop_name') == $shop->id ? 'selected' : '' }}> {{$shop->name}} </option>
              @endforeach
            </select>
            @error('shop_id')
            <label class="text-danger">&nbsp;*{{ $message }}</label>
            @enderror
          </div>
           <label class="col-sm-10 text-danger" id="warhouse_shop_required"></label>
        </div>
        @endif
        {{-- add button --}}
        <div class="form-group">
          <input type="button" class="btn btn-info" id="search" value="{{__('Add To List')}}">
        </div>

        <div class="table-responsive">
          {{-- product detail table --}}
          <table id="productDetailTable" class="table table-bordered text-nowrap">
            <thead class="thead-light">
              <tr>
                <th>{{__('Product')}} {{__('Name')}}</th>
                <th>{{__('Choose')}} {{__('Damage')}}/{{__('Loss')}}</th>
                <th>{{__('Quantity')}}</th>
                <th>{{__('Unit Price')}}</th>
                <th>{{__('Remark')}}</th>
                <th></th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>

      {{-- submit and back buttons --}}
      <div class="card-footer">
        <button type="button" id="btn_store" class="btn btn-primary" disabled="">{{__('Submit')}}</button>
        <a href="{{ route('damageloss') }}" class="btn btn-info mx-sm-2">{{__('Back')}}</a>
      </div>
    </div>
  </form>
</div>
{{-- confirm delete modal --}}
<div class="modal fade" id="deleteModalCenter" tabindex="-1" role="dialog" aria-labelledby="deleteModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalCenterTitle">{{ __('Confirm Delete') }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">
         {{ __('message.A0009') }}
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" onclick="rowDelete();">Yes</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>

<style type="text/css">
  .select2 {
    width: 100% !important;
  }
  @media (max-width: 576px) {

    #label_product_name,
    #div_product_name {
      margin-top: 0px !important;
    }
  }
  table input[type='text']{
    margin-bottom: 5px;
  }
  .req {
    bottom: -18;
    left: 25%;
  }
</style>
@endsection

@section('js')
<script type="text/javascript">
  let productList = {!! json_encode($productList) !!};
</script>
<script src="{{asset('js/damageloss/create.js')}}"></script>
@endsection

@extends('layouts.main')

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
      <h3 class="card-title">{{ __('Transfer Stock Form')}}</h3>
    </div>
    
    {{-- form for stock transfer store --}}
    <form action="{{ route('stock.transfer_store') }}" method = "POST">
        @csrf
      <div class="card-body">
        {{-- product code input --}}
        <div class="form-group row">
          <label class="col-sm-3 offset-sm-2">{{ __('Product')}} {{ __('Code')}}</label>
          <label class="col-sm-3">{{ $warehouseShopProduct->product_code }}</label>
          <input type="hidden" class="form-control" name="product_id" value="{{ $warehouseShopProduct->product_id }}"><br>
        </div>

        {{-- product name input --}}
        <div class="form-group row">
          <label class="col-sm-3 offset-sm-2">{{ __('Product')}} {{ __('Name')}}</label>
          <label class="col-sm-3">{{ $warehouseShopProduct->product_name }}</label>
        </div>

        {{-- warehouse name input --}}
        <div class="form-group row" style= {{ $warehouseShopProduct->warehouse_id != null  ? "display:flex;" : "display:none;"}}>
          <label class="col-sm-3 offset-sm-2">{{ __('Warehouse')}} {{ __('Name')}}</label>
          <label class="col-sm-3">{{ $warehouseShopProduct->warehouse_name }}</label>
          <input type="hidden" class="form-control" name="warehouse_id" value="{{ $warehouseShopProduct->warehouse_id }}"><br>
        </div>

        {{-- shop name input --}}
        <div class="form-group row" style= {{ $warehouseShopProduct->shop_id != null  ? "display:flex;" : "display:none;"}}>
          <label class="col-sm-3 offset-sm-2">{{ __('Shop')}} {{ __('Name')}}</label>
          <label class="col-sm-3">{{ $warehouseShopProduct->shop_name }}</label>
          <input type="hidden" class="form-control" name="shop_id" value="{{ $warehouseShopProduct->shop_id }}"><br>
        </div>

        {{-- warehouse stock input and return error message --}}
        <div class="form-group row">
          <label class="col-sm-3 offset-sm-2 col-form-label">{{ __('Warehouse')}} {{ __('Stock')}}</label>
          <div class="col-sm-5">
            <select name="selected_warehouse_id" id="selectWarehouse" class="form-control" onchange="warehouseChange()">
              <option value="" selected>Choose Warehouse Stock</option>
              {{-- warehousestock data list --}}
              @foreach($warehouseStockList as $warehouseStock)
                <option value= "{{$warehouseStock->id}}" {{ old('selected_warehouse_id') == $warehouseStock->id ? 'selected' : '' }}> {{$warehouseStock->name}} </option>
              @endforeach
            </select>
          </div>
        </div>
        <div class="offset-sm-5">
          @error('selected_warehouse_id')
            <label class="text-danger">&nbsp;*{{ $message }}</label>
          @enderror
        </div>

        {{-- quantity input and return error message --}}
        <div class="form-group row">
          <label class="col-sm-3 offset-sm-2 col-form-label">{{ __('Quantity')}}</label>
          <div class="col-sm-5 col-form-label">
            <input type="hidden" id="old_qty" name="old_qty">
            <input type="text" class="form-control" id="qty" name="qty" placeholder="Transfer Count" value="{{old('qty')}}">
          </div>
        </div>
        <div class="offset-sm-5">
          @error('qty')
            <label class="text-danger">&nbsp;*{{ $message }}</label>
          @enderror
        </div>

        {{-- price input and return error message --}}
        <div class="form-group row">
          <label class="col-sm-3 offset-sm-2 col-form-label">{{ __('Price')}}</label>
          <div class="col-sm-5 col-form-label">
            <input type="text" class="form-control" id="price" name="price" placeholder="Price For Sales" value="{{old('price')}}" readonly>
          </div>
        </div>
        <div class="offset-sm-5">
          @error('price')
            <label class="text-danger">&nbsp;*{{ $message }}</label>
          @enderror
        </div>

        {{-- submit and back buttons --}}
        <div class="card-footer row">
          <div class="offset-sm-3">
            <button type="submit" class="btn btn-primary">{{__('Submit')}}</button>
            <a href="{{ url('stock') }}" class="btn btn-info mx-sm-2">{{__('Back')}}</a>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection

@section('js')
<script type="text/javascript">
let warehouseStockList = {!! json_encode($warehouseStockList) !!};
</script>
<script src="{{asset('js/stock/transfer.js')}}"></script>
@endsection

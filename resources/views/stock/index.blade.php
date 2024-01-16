@extends('layouts.main')

@section('main-content')
{{-- stock search form --}}
<form action="{{ route('stock')}}" method="get" id="frm_stock">
  <div class = "col-md-12" id="content">
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
    @csrf
    <div class="row">
      {{-- product name input for stock search --}}
      <label class="col-sm-2 col-form-label">{{__('Product')}} {{__('Name')}}</label>
      <div class="col-sm-3">
        <input type="text" class="form-control" name="product_name" placeholder="{{ __('Name') }}" value = "{{ app('request')->input('product_name') != '' ? app('request')->input('product_name') : '' }}"><br>
      </div>
      {{-- shop name input and return error message--}}
      <label class="col-sm-2 col-form-label offset-sm-1">{{__('Shop')}} {{__('Name')}}</label>
      <div class="col-sm-3">
        <select name="shop_id" id="selectShop" class="form-control  select2">
          <option value="" selected>{{ __('Choose Shop') }}</option>
          {{-- shop data list --}}
          @foreach($shopList as $shop)
          <option value="{{$shop->id}}" {{ request()->shop_id == $shop->id ? 'selected' : '' }}> {{$shop->name}} </option>
        @endforeach
        </select>
        @error('shop_id')
        <label class="text-danger">&nbsp;*{{ $message }}</label>
        @enderror
      </div>
    </div>

    <div class="row">
      {{-- warehouse name input and return error message --}}
      <label class="col-sm-2 col-form-label">{{__('Warehouse')}} {{__('Name')}}</label>
      <div class="col-sm-3">
        <select name="warehouse_id" id="selectWarehouse" class="form-control select2">
          <option value="" selected>{{ __('Choose Warehouse') }}</option>
          {{-- warehouse data list --}}
          @foreach($warehouseList as $warehouse)
          <option value="{{$warehouse->id}}" {{ request()->warehouse_id == $warehouse->id ? 'selected' : '' }}> {{$warehouse->name}} </option>
          @endforeach
        </select>
        @error('warehouse_id')
        <label class="text-danger">&nbsp;*{{ $message }}</label>
        @enderror
      </div>
      {{-- show less checkbox --}}
      <div class="col-sm-2 col-form-label offset-sm-1">
        <input type="checkbox" class="form-check-input" name="less_stock" value="1"  {{ app('request')->input('less_stock') == config('constants.ACTIVE') ? 'checked' : '' }}>
        <label class="form-check-label" >{{__('Show Less')}} {{__('Stock')}}</label>
      </div>
      {{-- sort by quantity checkbox --}}
      <div class="col-sm-2 col-form-label">
        <input type="checkbox" class="form-check-input" name="order_by_qty" value="1"  {{ app('request')->input('order_by_qty') == config('constants.ACTIVE') ? 'checked' : '' }}>
        <label class="form-check-label" >{{__('Sort By')}} {{__('Quantity')}}</label>
      </div>
    </div>
    {{-- search and reset buttons --}}
    <div class="form-group">
      <input class="btn btn_search" name = "search" type="submit" value="{{__('Search')}}">
      <button class="btn btn_search" name = "search" type="reset">{{__('Reset')}}</button>
    </div>  
  </div>

  {{-- add button --}}
  <div class="form-group" style="margin-top:15px">
      <a href="{{ url('stock/create') }}" class="btn btn_add" id="btn_add">{{__('Add')}}</a>
  </div>

  <div class="card card-info">
    {{-- form title --}}
    <div class="card-header">
      <h3 class="card-title">{{__('Stock')}} {{__('List')}}</h3>
    </div>

    <div class="card-body">
      <div class="table-responsive">
        {{-- stock table --}}
        <table id="example1" class="table table-bordered text-nowrap">
          <thead class="thead-light">
            <tr>
              <th></th>
              <th>{{__('Product')}} {{__('Code')}}</th>
              <th>{{__('Product')}} {{__('Name')}}</th>
              <th>{{__('Shop')}} {{__('Name')}}</th>
              <th>{{__('Warehouse')}} {{__('Name')}}</th>
              <th>{{__('Stock')}} {{__('Quantity')}}</th>
              <th>{{__('Price')}}</th>
              @if(Auth::guard('staff')->user()->role == config('constants.COMPANY_ADMIN'))
              <th></th>
              @endif
            </tr>
          </thead>
          <tbody>
            {{-- stock data list --}}
            @forelse($stockList as $stock)
            <tr class= "{{ $stock->quantity <= $stock->minimum_quantity ? 'table-warning' : '' }}">
                <td><img src="
                  @if($stock->product_image) 
                  {{$stock->product_image}}
                  @else
                  {{'uploads/products/noproductimage.png'}}
                  @endif
                  "
                 width="150" height="100" /></td>
                <td>{{ $stock->product_code}}</td>
                <td>{{ $stock->product_name}}</td>
                <td>{{ $stock->shop_name ?? '-'}}</td>
                <td>{{ $stock->warehouse_name ?? '-' }}</td>
                <td>{{ $stock->quantity}}</td>
                <td>{{ number_format($stock->price,2)}}</td>
                @if(Auth::guard('staff')->user()->role == config('constants.COMPANY_ADMIN'))
                <td class="text-center"><a href="{{ route('stock.transfer',  array('warehouse_id' => $stock->warehouse_id ?? 0, 'shop_id' => $stock->shop_id ?? 0, 'product_id' => $stock->product_id ?? 0)) }}"><button type="button" class="btn bg-gradient-success">Stock Transfer</button></a></td>
                @endif
            </tr>
            @empty
            <tr>
              <td colspan="8" class="text-center">No results found.</td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    <div class="card-footer">
      {{-- stock list pagination size filters --}}
      <nav>
        <label class="mr-sm-2 mx-sm-2 pt-1" for="inlineFormCustomSelect">{{ __('Show Items') }}</label>
        <select class="custom-select mr-sm-2 custom_pg_size" id="inlineFormCustomSelect" style="width:34%" name="custom_pg_size">
          <option value="10" {{request()->get('custom_pg_size')=='10' || ''?'selected':''}}>10</option>
          <option value="20" {{ request()->get('custom_pg_size')=='20'?'selected':''}}>20</option>
          <option value="30" {{request()->get('custom_pg_size')=='30'?'selected':''}}>30</option>
        </select>
      </nav>
       {{-- stock list pagination --}}
       {{ $stockList->appends($_GET)->links() }}
    </div>
  </div>
</form>

<style type="text/css">
  .select2 {
    width: 100% !important;
  }
  @media (max-width: 576px) { 
    .form-check{
      margin-bottom:5px;
      margin-left:7px;
    }
    #content{
      padding: 0px 7.5px !important;
    }
  }
  .form-group{
    margin-bottom: 5px;
    margin-top: 10px;
  }
  #btn_add{
    margin:5px 0px;
  }
  .bg-gradient-success{
    width: 150px;
  }
  .form-check-input {
    margin-left: 0px;
  }
  .form-check-label {
    margin-left: 20px;
  }
  .select2 {
  touch-action: none;
  }
  img {
    display: block;
    margin-left: auto;
    margin-right: auto;
    width: 120px;
  }
</style>
@endsection

@section("js")
<script type="text/javascript" src="{{asset('js/stock/list.js')}}"></script>
@endsection
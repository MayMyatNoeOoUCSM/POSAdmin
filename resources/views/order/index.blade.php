@extends('layouts.main')

@section('main-content')
{{-- order search form --}}
<form id="frm_order" action="{{ route('order') }}" method="GET">
  <div class="col-md-12" id="content">
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
    <input type="hidden" name="sorting_column" id="sorting_column">
    <input type="hidden" name="sorting_order" id="sorting_order" value="{{ app('request')->input('sorting_order') != '' ? app('request')->input('sorting_order') : 'asc' }}">

    <div class="row">
      {{-- invoice input for sale search --}}
      <label class="col-sm-2 col-form-label">{{__("Invoice")}}</label>
      <div class="col-sm-3">
        <input type="text" class="form-control" name="search_invoice_no" placeholder="{{ __('Invoice')}} {{ __('No') }}" value="{{ app('request')->input('search_invoice_no') != '' ? app('request')->input('search_invoice_no') : old('search_invoice_no') }}"><br>
      </div>
      {{-- status input for order search --}}
      <label class="col-sm-2 col-form-label">{{__("Status")}}</label>
      <div class="col-sm-3">
        <select name="search_order_status" class="form-control">
          <option value="" selected>{{ __('Select Status') }}</option>
          <option value="{{ config('constants.ORDER_CREATE') }}" {{ app('request')->input('search_order_status') ?? old('search_order_status') == config('constants.ORDER_CREATE') ? 'selected' : '' }}>Order Create (Waiter)</option>
          <option value="{{ config('constants.ORDER_CONFIRM') }}" {{ app('request')->input('search_order_status') ?? old('search_order_status') == config('constants.ORDER_CONFIRM') ? 'selected' : '' }}>Order Confirm (Kitchen)</option>
          <option value="{{ config('constants.ORDER_INVOICE') }}" {{ app('request')->input('search_order_status') ?? old('search_order_status') == config('constants.ORDER_INVOICE') ? 'selected' : '' }}>Order Invoice (Cashier)</option>
        </select>
      </div>
    </div>

    <div class="row">
      {{-- order date from input for order search --}}
      <label class="col-sm-2 col-form-label">{{__('From Date')}}</label>
      <div class="col-sm-3">
        <input type="text" onfocus="(this.type='date')" class="form-control datetimepicker-input" name="search_order_date_from" placeholder="{{ __('From Date') }}" value="{{ app('request')->input('search_order_date_from') != '' ? app('request')->input('search_order_date_from') : (old('search_order_date_from') !=''? old('search_order_date_from'):'') }}" />
        <br>
      </div>
      {{-- order date to input for order search --}}
      <label class="col-sm-2 col-form-label">{{__('To Date')}}</label>
      <div class="col-sm-3">
        <input type="text" onfocus="(this.type='date')" class="form-control datetimepicker-input" name="search_order_date_to" placeholder="{{ __('To Date') }}" value="{{ app('request')->input('search_order_date_to') != '' ? app('request')->input('search_order_date_to') : old('search_order_date_to')  }}" />
        <br>
      </div>
    </div>

    <div class="row">
      {{-- shop name input for order search --}}
      <label class="col-sm-2 col-form-label">{{__("Shop")}}</label>
        <div class="col-sm-3">
          <select name="search_shop_name" class="form-control">
            <option value="" selected>{{ __('Select Shop')}}</option>
            {{-- shop data list --}}
            @foreach($shopList as $shop)
            <option value="{{$shop->name}}" {{ app('request')->input('search_shop_name') ?? old('search_shop_name') == $shop->name ? 'selected' : '' }}> {{$shop->name}} </option>
            @endforeach
          </select>
        </div>
        {{-- search by input --}}
        <label class="col-sm-2 col-form-label">{{__("SearchBy")}}</label>
        {{-- checkboxes group --}}
        <div class="col-sm-5 checkboxes">
        <input type="checkbox"  class="orderdateby" name="orderdateby" value="today" {{ (app('request')->input('orderdateby') ??  old('orderdateby')) =="today"? 'checked':''}} >
        <label for="orderdateby" class="col-form-label">{{__("Today")}}</label>
        <input type="checkbox"  class="orderdateby" name="orderdateby" value="weekly" {{ (app('request')->input('orderdateby') ??  old('orderdateby')) =="weekly"? 'checked':''}} >
        <label for="orderdateby" class="col-form-label">{{__("Weekly")}}</label>
        <input type="checkbox" class="orderdateby" name="orderdateby" value="monthly" {{ (app('request')->input('orderdateby') ??  old('orderdateby'))  =='monthly'? 'checked':''}}>
        <label for="orderdateby" class="col-form-label">{{__("Monthly")}}</label>
        <input type="checkbox" class="orderdateby" name="orderdateby" value="yearly" {{ (app('request')->input('orderdateby') ??  old('orderdateby'))  =='yearly'? 'checked':''}}>
        <label for="orderdateby" class="col-form-label">{{__("Yearly")}}</label>
       </div>
    </div>

    {{-- return error message for search order date to --}}
    <div class="row">
        @error('search_order_date_to')
        <label class="col-sm-4 text-danger">&nbsp;*{{ $message }}</label>
        @enderror
    </div>

    {{-- buttons group --}}
    <div class="form-group rowMarginTop">
      <input id="saleSearch" class="btn btn_search" name="search" type="submit" value="{{__('Search')}}">
      <input id="btn_reset" class="btn btn_search" name="search" type="button" value="{{__('Reset')}}">
      <input id="download" class="btn btn_download" name="download" type="submit" value="{{__('Download')}}">
    </div>
  </div>

  <div class="card card-info">
    {{-- form title --}}
    <div class="card-header">
      <h3 class="card-title">{{ __("Order")}} {{__("List")}}</h3>
    </div>

    <div class="card-body">
      <div class="table-responsive">
        {{-- order table --}}
        <table id="orderTable" class="table table-bordered text-nowrap">
          <thead class="thead-light">
            <tr>
              <th class="sorting">{{__("Date")}} <i class="fas fa-sort" id="order_date"></i></th>
              <th>{{__("Invoice Number")}} <i class="fas fa-sort" id="invoice_number"></i></th>
              <th>{{__("Shop")}}{{__("Name")}} <i class="fas fa-sort" id="shop_name"></i></th>
              <th>{{__("Terminal")}}{{__("Name")}} <i class="fas fa-sort" id="terminal_name"></i></th>
              <th>{{__("Restaurant")}}{{__("Name")}} <i class="fas fa-sort" id="restaurant_name"></i></th>
              <th>{{__("Amount")}}{{__("Tax")}} <i class="fas fa-sort" id="amount_tax"></i></th>
              <th>{{__("Amount")}} <i class="fas fa-sort" id="amount"></i></th>
              <th>{{__("Total")}} <i class="fas fa-sort" id="total"></i></th>
              <th>{{__("Remark")}}</th>
              <th>{{__("Status")}}</th>
              <th></th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            {{-- order data list --}}
            @forelse($orderList as $orderInfo)
            <tr class="{{ $orderInfo->order_status == 3 ? 'table-active' : 'table-inactive' }}">
              <td>{{ $orderInfo->order_date}}</td>
              <td>{{ $orderInfo->invoice_number}}</td>
              <td>{{ $orderInfo->shop_name}}</td>
              <td>{{ $orderInfo->terminal_name}}
                <input type="hidden" class="form-control" id="terminal_name" name="terminal_name" value="{{$orderInfo->terminal_name}}">
              </td>
              <td>{{ $orderInfo->restaurant_name}}
                <input type="hidden" class="form-control" id="restaurant" name="restaurant_name" value="{{$orderInfo->restaurant_name}}">
              </td>
              <td class="text-center">{{number_format($orderInfo->amount_tax, 2)}}</td>
              <td class="text-center">{{number_format($orderInfo->amount, 2)}}</td>
              <td class="text-center">{{number_format($orderInfo->total, 2)}}</td>
              <td>{{ $orderInfo->remark ?? "-"}}</td>
              <td class="text-center">
               <!--  @if( $orderInfo->order_status ==0)
                <span class="text-danger"><b>Create Order By Waiter</b></span>
                @endif -->
                @if( $orderInfo->order_status ==1)
                <span class="text-danger"><b>Create Order By Waiter</b></span>
                @endif
                @if( $orderInfo->order_status ==2)
                <span class="text-danger"><b>Confirm Order By Kitchen</b></span>
                @endif
                 @if( $orderInfo->order_status ==3)
                <span class="text-danger"><b>Invoice Order By Cashier</b></span>
                @endif
              </td>
              <td class="text-center">
                
                <a href="{{ url('/order/' . $orderInfo->id . '/edit') }}">
                  <button type="button" class="btn bg-gradient-success">{{__('Check Order Details')}}</button>
                </a>
          
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="10" class="text-center">No results found.</td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    <div class="card-footer">
      {{-- order list pagination size filters --}}
      <nav>
        <label class="mr-sm-2 mx-sm-2 pt-1" for="inlineFormCustomSelect">{{ __('Show Items') }}</label>
        <select class="custom-select mr-sm-2 custom_pg_size" id="inlineFormCustomSelect" style="width:34%" name="custom_pg_size">
          <option value="10" {{request()->get('custom_pg_size')=='10' || ''?'selected':''}}>10</option>
          <option value="20" {{ request()->get('custom_pg_size')=='20'?'selected':''}}>20</option>
          <option value="30" {{request()->get('custom_pg_size')=='30'?'selected':''}}>30</option>
        </select>
      </nav>
      {{-- order list pagination --}}
      {{ $orderList->withQueryString()->links() }}
    </div>
  </div>
</div>
</form>

<style type="text/css">
  input[type=date]{
    font-size: 14px;
  }
 /* .bg-gradient-success{
    width: 150px !important;
  }*/
  #content {
    border: 3px solid #dee2e6;
    margin-bottom: 3px;
    border-radius: 5px;
    padding: 10px 15px;
  }
  .card-footer>nav {
    display: inline;
    float: left;
  }
  @media (max-width: 576px) {
    .btn {
      margin-top: 15px;
    }
  }
  .fa-sort{
    margin-left: 5px;
  }

</style>
@endsection

@section("js")
<script type="text/javascript" src="{{asset('js/order/list.js')}}"></script>
@endsection

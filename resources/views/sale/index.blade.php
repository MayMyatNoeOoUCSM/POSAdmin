@extends('layouts.main')

@section('main-content')
{{-- sale search form --}}
<form id="frm_sale" action="{{ route('sale') }}" method="GET">
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
        <input type="text" class="form-control" name="search_invoice_no" placeholder="{{ __('Invoice') }} {{ __('No') }}" value="{{ app('request')->input('search_invoice_no') != '' ? app('request')->input('search_invoice_no') : old('search_invoice_no') }}"><br>
      </div>
      {{-- status input for sale search --}}
      <label class="col-sm-2 col-form-label">{{__("Status")}}</label>
      <div class="col-sm-3">
        <select name="search_invoice_status" class="form-control">
          <option value="" selected>{{ __('Select Status') }}</option>
          <option value="{{ config('constants.INVOICE_CONFIRM') }}" {{ app('request')->input('search_invoice_status') ?? old('search_invoice_status') == config('constants.INVOICE_CONFIRM') ? 'selected' : '' }}>Confirmed</option>
          <option value="{{ config('constants.INVOICE_CANCELLED') }}" {{ app('request')->input('search_invoice_status') ?? old('search_invoice_status') == config('constants.INVOICE_CANCELLED') ? 'selected' : '' }}>Cancelled</option>
        </select>
      </div>
    </div>

    <div class="row">
      {{-- sale date from input for sale search --}}
      <label class="col-sm-2 col-form-label">{{__('From Date')}}</label>
      <div class="col-sm-3">
        <input type="date" class="form-control datetimepicker-input" name="search_sale_date_from" placeholder="{{ __('From Date') }}" value="{{ app('request')->input('search_sale_date_from') != '' ? app('request')->input('search_sale_date_from') : (old('search_sale_date_from') !=''? old('search_sale_date_from'):'') }}" />
        <br>
      </div>
      {{-- sale date to input for sale search --}}
      <label class="col-sm-2 col-form-label">{{__('To Date')}}</label>
      <div class="col-sm-3">
        <input type="date" class="form-control datetimepicker-input" name="search_sale_date_to" placeholder="{{ __('To Date') }}" value="{{ app('request')->input('search_sale_date_to') != '' ? app('request')->input('search_sale_date_to') : old('search_sale_date_to')  }}" />
        <br>
      </div>
    </div>

    <div class="row">
      {{-- shop name input for sale search --}}
      <label class="col-sm-2 col-form-label">{{__("Shop")}}</label>
        <div class="col-sm-3">
          <select name="search_shop_name" class="form-control">
            <option value="" selected>{{ __('Select Shop') }}</option>
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
        <input type="checkbox"  class="saledateby" name="saledateby" value="today" {{ (app('request')->input('saledateby') ??  old('saledateby')) =="today"? 'checked':''}} >
        <label for="saledateby" class="col-form-label">{{__("Today")}}</label>
        <input type="checkbox"  class="saledateby" name="saledateby" value="weekly" {{ (app('request')->input('saledateby') ??  old('saledateby')) =="weekly"? 'checked':''}} >
        <label for="saledateby" class="col-form-label">{{__("Weekly")}}</label>
        <input type="checkbox" class="saledateby" name="saledateby" value="monthly" {{ (app('request')->input('saledateby') ??  old('saledateby')) =='monthly'? 'checked':''}}>
        <label for="saledateby" class="col-form-label">{{__("Monthly")}}</label>
        <input type="checkbox" class="saledateby" name="saledateby" value="yearly" {{ (app('request')->input('saledateby') ??  old('saledateby')) =='yearly'? 'checked':''}}>
        <label for="saledateby" class="col-form-label">{{__("Yearly")}}</label>
       </div>
    </div>

    {{-- return error message for search sale date to --}}
    <div class="row">
        @error('search_sale_date_to')
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
      <h3 class="card-title">{{ __("Sales")}} {{__("List")}}</h3>
    </div>

    <div class="card-body">
      <div class="table-responsive">
        {{-- sale table --}}
        <table id="saleTable" class="table table-bordered text-nowrap">
          <thead class="thead-light">
            <tr>
              <th class="sorting">{{__("Date")}} <i class="fas fa-sort" id="sale_date"></i></th>
              <th>{{__("Invoice Number")}} <i class="fas fa-sort" id="invoice_number"></i></th>
              <th>{{__("Shop")}}{{__("Name")}} <i class="fas fa-sort" id="shop_name"></i></th>
              <th>{{__("Terminal")}}{{__("Name")}} <i class="fas fa-sort" id="terminal_name"></i></th>
              <th>{{__("Staff")}}{{__("Name")}} <i class="fas fa-sort" id="staff_name"></i></th>
              <th>{{__("Amount")}}{{__("Tax")}} <i class="fas fa-sort" id="amount_tax"></i></th>
              <th>{{__("Amount")}} <i class="fas fa-sort" id="amount"></i></th>
              <th>{{__("Total")}} <i class="fas fa-sort" id="total"></i></th>
              <th>{{__("Remark")}}</th>
              <th></th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            {{-- sale data list --}}
            @forelse($salesList as $saleInfo)
            <tr class="{{ $saleInfo->invoice_status == 3 ? 'table-active' : 'table-inactive' }}">
              <td>{{ date('m/d/Y',strtotime($saleInfo->sale_date))}}</td>
              <td>{{ $saleInfo->invoice_number}}</td>
              <td>{{ $saleInfo->shop_name}}</td>
              <td>{{ $saleInfo->terminal_name}}
                <input type="hidden" class="form-control" id="terminal_name" name="terminal_name" value="{{$saleInfo->terminal_name}}">
              </td>
              <td>{{ $saleInfo->staff_name}}
                <input type="hidden" class="form-control" id="staff" name="staff_name" value="{{$saleInfo->staff_name}}">
              </td>
              <td class="text-center">{{number_format($saleInfo->amount_tax, 2)}}</td>
              <td class="text-center">{{number_format($saleInfo->amount, 2)}}</td>
              <td class="text-center">{{number_format($saleInfo->total, 2)}}</td>
              <td>{{ $saleInfo->remark ?? "-"}}</td>
              <td class="text-center">
                @if($saleInfo->invoice_status!=3)
                <a href="{{ url('/sale/' . $saleInfo->id . '/edit') }}">
                  <button type="button" id="cancel_invoice" class="btn btn_primary">{{__('Cancel Invoice')}}</button>
                </a>
                @endif
                @if( $saleInfo->invoice_status ==3)
                <span class="text-danger"><b>Already Cancelled</b></span>
                @endif
              </td>
              <td class="text-center">
              <a href="{{ url('/sale/' . $saleInfo->id . '/download') }}">
                  <button type="button" id="download" class="btn btn-info" name="download">{{__('Export Excel')}}</button>
              </a>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="11" class="text-center">No results found.</td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    <div class="card-footer">
      {{-- sale list pagination size filters --}}
      <nav>
        <label class="mr-sm-2 mx-sm-2 pt-1" for="inlineFormCustomSelect">{{ __('Show Items') }}</label>
        <select class="custom-select mr-sm-2 custom_pg_size" id="inlineFormCustomSelect" style="width:34%" name="custom_pg_size">
          <option value="10" {{request()->get('custom_pg_size')=='10' || ''?'selected':''}}>10</option>
          <option value="20" {{ request()->get('custom_pg_size')=='20'?'selected':''}}>20</option>
          <option value="30" {{request()->get('custom_pg_size')=='30'?'selected':''}}>30</option>
        </select>
      </nav>
      {{-- sale list pagination --}}
      {{ $salesList->withQueryString()->links() }}
    </div>
  </div>
</div>
</form>

<style type="text/css">
  input[type=date]{
    font-size: 14px;
  }
  .bg-gradient-success{
    width: 150px !important;
  }
  #cancel_invoice {
    color: white;
  }
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
<script type="text/javascript" src="{{asset('js/sale/list.js')}}"></script>
@endsection

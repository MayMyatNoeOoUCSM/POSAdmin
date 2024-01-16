@extends('layouts.main')

@section('main-content')
{{-- sale return search form --}}
<form action="{{route('sale_return')}}" method="get" id="frm_sale_return">
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
    {{-- invoice number input for sale return search --}}
    <div class="row">
      <label class="col-sm-2 col-form-label">{{__('Invoice Number')}}</label>
      <div class="col-sm-3">
        <input type="text" class="form-control" name="search_ret_invoice_no" placeholder="{{ __('Invoice') }} {{ __('No') }}" value="{{ app('request')->input('search_ret_invoice_no') != '' ? app('request')->input('search_ret_invoice_no') : old('search_ret_invoice_no') }}"><br>
      </div>
    </div>

    <div class="row">
      {{-- sale return date from input for sale return search --}}
      <label class="col-sm-2 col-form-label">{{__('Return Date From')}}</label>
      <div class="col-sm-3">
        <input type="date"  class="form-control datetimepicker-input" name="search_sale_date_from" placeholder="{{ __('Sale Date From') }}" value="{{ app('request')->input('search_sale_date_from') != '' ? app('request')->input('search_sale_date_from') : old('search_sale_date_from') }}" />
        <br>
      </div>
      {{-- sale return date to input for sale return search --}}
      <label class="col-sm-2 col-form-label">{{__('Return Date To')}}</label>
      <div class="col-sm-3">
        <input type="date"  class="form-control datetimepicker-input" name="search_sale_date_to" placeholder="{{ __('Sale Date To') }}" value="{{ app('request')->input('search_sale_date_to') != '' ? app('request')->input('search_sale_date_to') : old('search_sale_date_to') }}" />
        <br>
      </div>
      {{-- search button --}}
      <div class="col-sm-2">
        <input class="btn btn_search" name="search" type="submit" value="{{__('Search')}}">
      </div>
      {{-- return error message for search sale date to --}}
      @error('search_sale_date_to')
      <label class="col-sm-5 offset-sm-7 text-danger">&nbsp;*{{ $message }}</label>
      @enderror
    </div>
  </div>

  {{-- add button --}}
  <div class="form-group" style="margin-top:15px">
    <a href=" {{ url('sale_return/create') }}" class="btn btn_add">{{ __('Add') }}</a>
  </div>

  <div class="card card-info">
    {{-- form title --}}
    <div class="card-header">
      <h3 class="card-title">{{__('Sales Return')}} {{__('List')}}</h3>
    </div>

    <div class="card-body">
      <div class="table-responsive">
        {{-- sale return table --}}
        <table id="returnTable" class="table table-bordered text-nowrap">
        <thead class="thead-light">
          <tr>
            <th class="sorting">{{__('Return Date')}}</th>
            <th class="sorting">{{__('Invoice Number')}}</th>
            <th>{{__('Sales')}} {{__('Staff')}} {{__('Name')}}</th>
            <th>{{__('Sub-Total')}} {{__('Sales')}} {{__('Quantity')}} </th>
            <th>{{__('Sub-Total')}} {{__('Return')}} {{__('Quantity')}}</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          {{-- salereturn data list --}}
          @forelse($saleReturnList as $retInfo)
          <tr class="table-inactive">
            <td>{{ $retInfo->return_date}}</td>
            <td>{{ $retInfo->return_invoice_number}}</td>
            <td>{{ $retInfo->staff_name}}</td>
            <td class="text-center">{{ $retInfo->total_sale_qty}}</td>
            <td class="text-center">{{ $retInfo->total_return_qty}}</td>
            <td>
              <a href="{{ route('salereturn.details',  array('return_id' => $retInfo->ret_id)) }}"><button type="button" class="btn bg-gradient-success btn_check_details">{{__('Check Details')}}</button></a>
            </td>
          </tr>
          @empty
            <tr>
              <td colspan="6" class="text-center">No results found.</td>
            </tr>
          @endforelse
        </tbody>
        </table>
      </div>
    </div>

    <div class="card-footer">
      {{-- sale return list pagination size filters --}}
      <nav>
        <label class="mr-sm-2 mx-sm-2 pt-1" for="inlineFormCustomSelect">{{ __('Show Items') }}</label>
        <select class="custom-select mr-sm-2 custom_pg_size" id="inlineFormCustomSelect" style="width:34%" name="custom_pg_size">
          <option value="10" {{request()->get('custom_pg_size')=='10' || ''?'selected':''}}>10</option>
          <option value="20" {{ request()->get('custom_pg_size')=='20'?'selected':''}}>20</option>
          <option value="30" {{request()->get('custom_pg_size')=='30'?'selected':''}}>30</option>
        </select>
      </nav>
      {{-- sale return list pagination --}}
      {{ $saleReturnList->withQueryString()->links() }}
    </div>
  </div>
</form>

<style type="text/css">
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
</style>
@endsection

@section('js')
<script type="text/javascript" src="{{asset('js/return/list.js')}}"></script>
@endsection

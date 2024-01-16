@extends('layouts.main')

@section('main-content')
{{-- report search form --}}
<form id="frm_report" action="{{ route('invoice.details.report') }}" method="GET">
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
    <div class="row report_filters">
      <label class="col-sm-2 col-form-label">{{__("Invoice Number")}}</label>
      <div class="col-sm-3">
        <input type="text" class="form-control" name="invoice_number" value="{{ app('request')->input('invoice_number') != '' ? app('request')->input('invoice_number') : '' }}">
      </div>
      
      {{-- return error message for search invoice number --}}
      @error('invoice_number')
      <label class="col-sm-5 offset-sm-7 text-danger">&nbsp;*{{ $message }}</label>
      @enderror
    </div>
    <div class="row mt-3">
      {{-- search and download buttons --}}
       <div class="col-sm-6">
         <input id="saleSearch" class="btn btn-info" name="search" type="submit" value="{{__('Search')}}">
         <input id="download" class="btn btn-info" name="download" type="submit" value="{{__('Download')}}">
       </div>
    </div>

    
   
</div>

<div class="card card-info">
  {{-- form title --}}
  <div class="card-header">
    <h3 class="card-title">{{ __("Results")}} {{__("List")}}</h3>
  </div>

  <div class="card-body">
    <div class="table-responsive">
      {{-- invoice details report table --}}
      <table id="saleTable" class="table table-bordered text-nowrap">
        <thead class="thead-light">
          <tr>
            <th>{{__('Shop')}} {{__('Name')}}</th>
            <th>{{__('Invoice')}} {{__('Number')}}</th>
            <th>{{__('Product')}} {{__('Name')}}</th>
            <th>{{__('Sales')}} {{__('Price')}}</th>
            <th>{{__('Sales')}} {{__('Quantity')}}</th>
            <th>{{__('Sales')}} {{__('Date')}}</th>
          </tr>
        </thead>
        <tbody>
          @forelse($data as $value)
            <tr>
            <td>{{$value->shop_name}}</td>
            <td>{{$value->invoice_number}}</td>
            <td>{{$value->product_name}}</td>
            <td>{{$value->price}}</td>
            <td>{{$value->quantity}}</td>
            <td>{{$value->sale_date}}</td>
            <tr>
         @empty
            <tr>
              <td colspan="6" class="text-center">No results found.</td>
            </tr>
         @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <div class="card-footer" style="{{ (count($data)== 0 ? 'display:none':'')}}">
    {{-- sale report list pagination size filters --}}
    <nav>
      <label class="mr-sm-2 mx-sm-2 pt-1" for="inlineFormCustomSelect">{{ __('Show Items') }}</label>
      <select class="custom-select mr-sm-2 custom_pg_size" id="inlineFormCustomSelect" style="width:34%" name="custom_pg_size">
        <option value="10" {{request()->get('custom_pg_size')=='10' || ''?'selected':''}}>10</option>
        <option value="20" {{ request()->get('custom_pg_size')=='20'?'selected':''}}>20</option>
        <option value="30" {{request()->get('custom_pg_size')=='30'?'selected':''}}>30</option>
      </select>
    </nav>
  </div>
</div>
</div>
</form>

<style type="text/css">
  #content{
    border: 3px solid #dee2e6;
    margin-bottom: 3px;
    border-radius: 5px;
    padding: 10px 15px;
  }
  .card-footer > nav{
    display: inline;
    float:left;
  }
  @media(max-width: 576px) {
    .btn{
      margin-top:20px;
    }
  }
</style>
@endsection

@section('js')

<script src="{{asset('js/report/salereport.js')}}"></script>

@endsection

@extends('layouts.main')

@section('main-content')
{{-- report search form --}}
<form id="frm_report" action="{{ route('topsale.report') }}" method="GET">
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

    {{-- shop input for report search --}}
    @if (Auth::guard('staff')->user()->role == config('constants.COMPANY_ADMIN') )
    <div class="row mt-3 report_filters">
    <label class="col-sm-2 col-form-label">{{__("Shop Name")}}</label>
      <div class="col-sm-3">
        <select name="shop_id" class="form-control">
          <option value="" selected>{{__('Select Shop')}}</option>
          @foreach($shopList as $shop)
          <option value="{{$shop->id}}" {{ app('request')->input('shop_id') == $shop->id ? 'selected' : '' }}> {{$shop->name}} </option>
          @endforeach
        </select>
      </div>
      
    </div>
    @endif
    <div class="row mt-3 report_filters">
      <label class="col-sm-2 col-form-label">{{__('From Date')}}</label>
      <div class="col-sm-3">
        <input type="date"  class="form-control datetimepicker-input" placeholder="{{__('From Date')}}" name="from_date" value="{{ app('request')->input('from_date') != '' ? app('request')->input('from_date') : '' }}">
      </div>
      <label class="col-sm-2 col-form-label">{{__('To Date')}}</label>
      <div class="col-sm-3">
        <input type="date"  class="form-control datetimepicker-input" placeholder="{{__('To Date')}}" name="to_date" value="{{ app('request')->input('to_date') != '' ? app('request')->input('to_date') : '' }}">
      </div>
      {{-- return error message for search sale date to --}}
      @error('to_date')
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
      {{-- sale report table --}}
      <table id="saleTable" class="table table-bordered text-nowrap">
        <thead class="thead-light">
          <tr>
            <th>{{__('Shop')}} {{__('Name')}}</th>
            <th>{{__('Product')}} {{__('Name')}}</th>

            <th>{{__('Total')}} {{__('Sale')}} {{__('Quantity')}}</th>
          <!--   <th>{{__('Total')}} {{__('Quantity')}}</th> -->
          </tr>
        </thead>
        <tbody>
          @forelse($data as $value)
            <tr>
            <td>{{$value->shop_name}}</td>
            <td>{{$value->product_name}}</td>
            <td>{{$value->total_quantity}}</td>
            <tr>
         @empty
            <tr>
              <td colspan="4" class="text-center">No results found.</td>
            </tr>
         @endforelse
        </tbody>
      </table>
    </div>
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

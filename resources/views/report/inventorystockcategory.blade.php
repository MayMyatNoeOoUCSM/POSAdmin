@extends('layouts.main')

@section('main-content')
{{-- report search form --}}
<form id="frm_report" action="{{ route('inventorycategory.report') }}" method="GET">
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
      {{-- search and download buttons --}}
       <div class="col-sm-6">
         <input id="saleSearch" class="btn btn-info" name="search" type="submit" value="{{__('Search')}}">
         <input id="download" class="btn btn-info ml-3" name="download" type="submit" value="{{__('Download')}}">
       </div>
    </div>
    @endif
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
            <th>{{__('Category')}} {{__('Name')}}</th>
            <th>{{__('Stock')}} {{__('Quantity')}}</th>
          </tr>
        </thead>
        <tbody>
          @forelse($data as $value)
            <tr>
            <td>{{$value->shop_name}}</td>
            <td>{{$value->category_name}}</td>
            <td>{{$value->stock_quantity}}</td>
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
    {{ (is_array($data)?"":$data->withQueryString()->links()) }}
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

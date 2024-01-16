@extends('layouts.main')

@section('main-content')
{{-- report search form --}}
<form id="frm_report" action="{{ route('product.report') }}" method="GET">
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
    {{-- shop input for report search --}}
    <div class="row mt-5 mb-5 report_filters" id="best_selling_report_filters">
      <label class="col-sm-1 col-form-label">{{__("Shop")}}</label>
      <div class="col-sm-3">
        <select name="select_shop_name" class="form-control">
          <option value="" selected>{{__('Select Shop')}}</option>
          @foreach($shopList as $shop)
          <option value="{{$shop->id}}" {{ app('request')->input('select_shop_name') == $shop->id ? 'selected' : '' }}> {{$shop->name}} </option>
          @endforeach
        </select>
      </div>
      {{-- search and download buttons --}}
       <div class="col-sm-6">
         <input id="saleSearch" name="search" class="btn btn-info" type="submit" value="{{__('Search')}}">
         <input id="download" name="download" class="btn btn-info" type="submit" value="{{__('Download')}}">
       </div>
    </div>

  <div class="card card-info">
      {{-- form title --}}
      <div class="card-header">
         <h3 class="card-title">{{ __("Results")}} {{__("List")}}</h3>
</div> 
  <div class="card-body">
    <div class="table-responsive">
      {{-- product report table --}}
      <table id="productTable" class="table table-bordered text-nowrap">
        <thead class="thead-light">
          <tr>
            <th>{{__('Shop')}} {{__('Name')}}</th>
            <th>{{__('Product')}} {{__('Name')}}</th>
            <th>{{__('Stock')}} {{__('Quantity')}}</th>
          </tr>
        </thead>
        <tbody>
          @forelse($data as $value)
            <tr>
            <td>{{$value->shop_name}}</td>
            <td>{{$value->product_name}}</td>
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
<script type="text/javascript">

</script>
<script src="{{asset('js/report/list.js')}}"></script>

@endsection

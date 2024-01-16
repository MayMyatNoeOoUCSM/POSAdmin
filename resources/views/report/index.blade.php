@extends('layouts.main')

@section('main-content')
{{-- report search form --}}
<form id="frm_report" action="{{ route('report') }}" method="GET">
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
    {{-- type input for report search --}}
    <div class="row">
      <label class="col-sm-1 col-form-label">{{__("Type")}}</label>
      <div class="col-sm-3">
        <select name="select_report" id="select_report" class="form-control">
          <option value="" selected>{{__('Select Report')}}</option>
          <option value="best_selling_report" {{ app('request')->input('select_report') == "best_selling_report" ? 'selected' : '' }}>Best Selling Products</option>
          <option value="sales_by_product_category_report" {{ app('request')->input('select_report') == "sales_by_product_category_report" ? 'selected' : '' }}>Sales By Product Category</option>
          <option value="damage_and_loss_report" {{ app('request')->input('select_report') == "damage_and_loss_report" ? 'selected' : '' }}>Damage and Loss</option>
        </select>
      </div>
    </div>

    {{-- shop input for report search --}}
    <div class="row mt-3 report_filters" style="display:none;" id="best_selling_report_filters">
      <label class="col-sm-1 col-form-label">{{__("Shop")}}</label>
      <div class="col-sm-3">
        <select name="search_shop_name" class="form-control">
          <option value="" selected>{{__('Select Shop')}}</option>
          @foreach($shopList as $shop)
          <option value="{{$shop->id}}" {{ app('request')->input('search_shop_name') == $shop->id ? 'selected' : '' }}> {{$shop->name}} </option>
          @endforeach
        </select>
      </div>
      {{-- search and download buttons --}}
       <div class="col-sm-6">
         <input id="saleSearch" class="btn btn-info" name="search" type="submit" value="{{__('Search')}}">
         <input id="download" class="btn btn-info" name="download" type="submit" value="{{__('Download')}}">
       </div>
    </div>

    {{-- category input --}}
    <div class="row mt-3 report_filters" style="display:none;" id="sales_by_product_category_report_filters">
      <label class="col-sm-1 col-form-label">{{__("Category")}}</label>
      <div class="col-sm-3">
        <select name="search_category_name" class="form-control">
          <option value="" selected>{{__('Select Category')}}</option>
          {{-- category data list --}}
          @foreach($categoryList as $category)
          <option value="{{$category->id}}" {{ app('request')->input('search_category_name') == $category->id ? 'selected' : '' }}> {{$category->name}} </option>
          @endforeach
        </select>
      </div>
      {{-- search and download buttons }}
       <div class="col-sm-6">
         <input id="saleSearch" class="btn btn-info" name="search" type="submit" value="{{__('Search')}}">
         <input id="download" class="btn btn-info" name="download" type="submit" value="{{__('Download')}}">
       </div>
    </div>

    <div class="row mt-3 report_filters" style="display:none;" id="damage_and_loss_report_filters">
      {{-- shop name input --}}
      <label class="col-sm-1 col-form-label">{{__("Shop")}}</label>
      <div class="col-sm-3">
        <select name="search_shop_name" class="form-control">
          <option value="" selected>{{__('Select Shop')}}</option>
          {{-- shop data list --}}
          @foreach($shopList as $shop)
          <option value="{{$shop->id}}" {{ app('request')->input('search_shop_name') == $shop->id ? 'selected' : '' }}> {{$shop->name}} </option>
          @endforeach
        </select>
      </div>
      {{-- warehouse input --}}
      <label class="col-sm-1 col-form-label">{{__("Warehouse")}}</label>
      <div class="col-sm-3">
        <select name="search_warehouse_name" class="form-control">
          <option value="" selected>{{__('Select Warehouse')}}</option>
          {{-- warehouse data list --}}
          @foreach($warehouseList as $warehouse)
          <option value="{{$warehouse->id}}" {{ app('request')->input('search_warehouse_name') == $warehouse->id ? 'selected' : '' }}> {{$warehouse->name}} </option>
          @endforeach
        </select>
      </div>
      {{-- search and download buttons --}}
       <div class="col-sm-4 mt-3">
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
            @if(app('request')->input('select_report')=='best_selling_report' AND !empty($data))
            <th>{{__($data->header['0'])}}</th>
            <th>{{__($data->header['1'])}}</th>
            <th>{{__($data->header['2'])}}</th>
            <th>{{__($data->header['3'])}}</th>
            @endif
            @if(app('request')->input('select_report')=='sales_by_product_category_report' AND !empty($data))
            <th>{{__($data->header['0'])}}</th>
            <th>{{__($data->header['1'])}}</th>
            <th>{{__($data->header['2'])}}</th>
            @endif
            @if(app('request')->input('select_report')=='damage_and_loss_report' AND !empty($data))
            <th>{{$data->header['0']}}</th>
            <th>{{$data->header['1']}}</th>
            <th>{{$data->header['2']}}</th>
            <th>{{$data->header['3']}}</th>
            @endif
          </tr>
        </thead>
        <tbody>
          @if(app('request')->input('select_report')=='best_selling_report' AND !empty($data))
            @foreach($data as $data1)
            <tr>
              <td>{{$data1->product_code}}</td>
              <td>{{$data1->name}}</td>
              <td>{{$data1->shop_name}}</td>
              <td class="text-center">{{$data1->total_sum}}</td>
            </tr>
            @endforeach
          @endif
          @if(app('request')->input('select_report')=='sales_by_product_category_report' AND !empty($data))
            @foreach($data as $data1)
            <tr>
              <td>{{$data1->shop_name}}</td>
              <td>{{$data1->category_name}}</td>
              <td class="text-center">{{$data1->total}}</td>
            </tr>
            @endforeach
          @endif
          @if(app('request')->input('select_report')=='damage_and_loss_report' AND !empty($data))
            @foreach($data as $data1)
            <tr>
              <td>{{$data1->shop_name ?? "-"}}</td>
              <td>{{$data1->warehouse_name ?? "-"}}</td>
              <td>{{$data1->product_name}}</td>
              <td class="text-center">{{$data1->total_damage_qty}}</td>
            </tr>
            @endforeach
          @endif
        </tbody>
      </table>
    </div>
  </div>

  <div class="card-footer">
    {{-- sale report list pagination size filters --}}
    <nav>
      <label class="mr-sm-2 mx-sm-2 pt-1" for="inlineFormCustomSelect">{{ __('Show Items') }}</label>
      <select class="custom-select mr-sm-2 custom_pg_size" id="inlineFormCustomSelect" style="width:34%" name="custom_pg_size">
        <option value="1" {{request()->get('custom_pg_size')=='10' || ''?'selected':''}}>10</option>
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
    var select_report_type;
    window.select_report_type= "{{ app('request')->input('select_report') ?? 'noselect' }}"
    if(select_report_type=='best_selling_report') {
      $("#best_selling_report_filters").show();
    }else if(select_report_type=='sales_by_product_category_report'){
      $("#sales_by_product_category_report_filters").show();
    }else if(select_report_type=='damage_and_loss_report'){
      $("#damage_and_loss_report_filters").show();
    }
</script>
<script src="{{asset('js/report/list.js')}}"></script>

@endsection

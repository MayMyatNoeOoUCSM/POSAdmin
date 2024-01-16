@extends('layouts.main')

@section('main-content')
{{-- report search form --}}
<form id="frm_report" action="{{ route('sale.report') }}" method="GET">
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
        <input type="date" class="form-control datetimepicker-input" placeholder="{{__('From Date')}}" name="from_date" value="{{ app('request')->input('from_date') != '' ? app('request')->input('from_date') : '' }}">
      </div>
      <label class="col-sm-2 col-form-label">{{__('To Date')}}</label>
      <div class="col-sm-3">
        <input type="date" class="form-control datetimepicker-input" placeholder="{{__('To Date')}}" name="to_date" value="{{ app('request')->input('to_date') != '' ? app('request')->input('to_date') : '' }}">
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
<div class="col-md-12 text-center">
  <!-- <div id="noresults">No results data.</div> -->
</div>
</div>
</form>
<div class="col-sm-8 mt-5">
    <canvas id="graph" height="300px" width="600px"></canvas>
</div>

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
<script type="text/javascript">
  var label = <?php echo json_encode($data['dataLabel']); ?>;
  var data = <?php echo json_encode($data['dataList']); ?>;
  if(label.length > 0 &&  data.reduce((a, b) => a + b, 0) > 0)
  {
    var ctx = document.getElementById('graph').getContext('2d');
    var chart = new Chart(ctx, {
      type: 'bar',
      data: {
          labels: label,
          datasets: [{
              label: 'sale amount',
              backgroundColor: 'green',
              borderColor: 'rgb(115, 160, 140)',
              data: data
          }]
      },
    });
    $("#noresults").hide();
  }else{
    $("#noresults").show();
    var ctx = document.getElementById('graph').getContext('2d');
    var chart = new Chart(ctx, {
      type: 'bar',
      data: {
          labels: [],
          datasets: [{
              label: 'sale amount',
              backgroundColor: 'green',
              borderColor: 'rgb(115, 160, 140)',
              data: []
          }]
      },
    });
  }
</script>
@endsection

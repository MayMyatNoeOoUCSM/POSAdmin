@extends('layouts.main')

@section('main-content')
{{-- report search form --}}
<form id="frm_report" action="{{ route('company.report') }}" method="GET">
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
    <div class="row mt-3 report_filters" id="best_selling_report_filters">
      <label class="col-sm-2 col-form-label">{{__("Company")}}</label>
      <div class="col-sm-3">
        <select name="select_company_name" class="form-control">
          <option value="" selected>{{__('Select Company')}}</option>
          @foreach($companyList as $company)
          <option value="{{$company->id}}" {{ app('request')->input('select_company_name') == $company->id ? 'selected' : '' }}> {{$company->name}} </option>
          @endforeach
        </select>
      </div>
    </div>
    <div class="row mt-3 report_filters">
      <label class="col-sm-2 col-form-label">{{__('From Date')}}</label>
      <div class="col-sm-3">
        <input type="text" onfocus="(this.type='date')" class="form-control datetimepicker-input" placeholder="{{__('From Date')}}" name="from_date" value="{{ app('request')->input('from_date') != '' ? app('request')->input('from_date') : '' }}">
      </div>
      <label class="col-sm-2 col-form-label">{{__('To Date')}}</label>
      <div class="col-sm-3">
        <input type="text" onfocus="(this.type='date')" class="form-control datetimepicker-input" placeholder="{{__('To Date')}}" name="to_date" value="{{ app('request')->input('to_date') != '' ? app('request')->input('to_date') : '' }}">
      </div>
      {{-- return error message for search sale date to --}}
      @error('to_date')
      <label class="col-sm-5 offset-sm-7 text-danger">&nbsp;*{{ $message }}</label>
      @enderror
    </div>
      {{-- search and download buttons --}}
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
      {{-- product report table --}}
      <table id="companyTable" class="table table-bordered text-nowrap">
        <thead class="thead-light">
          <tr>
            <th>{{__('Company')}} {{__('Name')}}</th>
            <th>{{__('Company')}} {{__('Address')}}</th>
            <th>{{__('Primary Phone')}}</th>
            <th>{{__('Secondary Phone')}}</th>
          </tr>
        </thead>
        <tbody>
          @forelse($data as $value)
            <tr>
            <td>{{$value->company_name}}</td>
            <td>{{$value->address}}</td>
            <td>{{$value->primary_phone??'-'}}</td>
            <td>{{$value->seconday_phone??'-'}}</td>
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

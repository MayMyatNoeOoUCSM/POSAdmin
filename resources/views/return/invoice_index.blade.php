@extends('layouts.main')

@section('main-content')
{{-- form for create sale return --}}
<form action="{{route('sale_return.create')}}" method="get" id="frm_sale_return">
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
    {{-- invoice number input --}}
    <div class="row">
      <label class="col-sm-2 col-form-label">{{__('Invoice Number')}}</label>
      <div class="col-sm-3">
        <input type="text" class="form-control" name="search_invoice_no" placeholder="{{__('Invoice')}} {{__('No')}}" value="{{ app('request')->input('search_invoice_no')  ? app('request')->input('search_invoice_no') : old('search_invoice_no') }}"><br>
      </div>
    </div>

    <div class="row">
      {{-- sale date from input --}}
      <label class="col-sm-2 col-form-label">{{__('Sale Date From')}}</label>
      <div class="col-sm-3">
        <input type="date"  class="form-control datetimepicker-input" name="search_sale_date_from" placeholder="{{__('Sale Date From')}}" value="{{ app('request')->input('search_sale_date_from') != '' ? app('request')->input('search_sale_date_from') : old('search_sale_date_from') }}" />
        <br>
      </div>
      {{-- sale date to input --}}
      <label class="col-sm-2 col-form-label">{{__('Sale Date To')}}</label>
      <div class="col-sm-3">
        <input type="date"  class="form-control datetimepicker-input" name="search_sale_date_to" placeholder="{{__('Sale Date To')}}" value="{{ app('request')->input('search_sale_date_to')  != '' ? app('request')->input('search_sale_date_to') : old('search_sale_date_to') }}" />
        <br>
      </div>
      {{-- return error message for search sale date to --}}
      @error('search_sale_date_to')
      <label class="col-sm-4 offset-sm-7 text-danger">&nbsp;*{{ $message }}</label>
      @enderror
  </div>
    {{-- search button --}}
    <div class="form-group">
      <input class="btn btn-info " name="search" type="submit" value="{{__('Search')}}">
    </div>
  </div>

  <div class="card card-info">
    {{-- form title --}}
    <div class="card-header">
      <h3 class="card-title">{{__('Confirm Invoice List')}}</h3>
    </div>

    <div class="card-body">
      <div class="table-responsive">
        {{-- confirm invoice table --}}
        <table id="saleTable" class="table table-bordered text-nowrap">
          <thead class="thead-light">
            <tr>
              <th class="sorting">{{__('Sales')}} {{__('Date')}}</th>
              <th class="sorting">{{__('Invoice Number')}}</th>
              <th>{{__('Shop')}} {{__('Name')}}</th>
              <th>{{__('Terminal')}} {{__('Name')}}</th>
              <th>{{__('Staff')}} {{__('Name')}}</th>
              <th>{{__('Amount')}} {{__('Tax')}}</th>
              <th>{{__('Amount')}}</th>
              <th>{{__('Total')}}</th>
              <th>{{__('Remark')}}</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            {{-- sale data list --}}
            @foreach($salesList as $saleInfo)
            <tr class="{{ $saleInfo->invoice_status == 3 ? 'table-active' : 'table-inactive' }}">
              <td>{{ date("m/d/Y",strtotime($saleInfo->sale_date))}}</td>
              <td>{{ $saleInfo->invoice_number}}</td>
              <td>{{ $saleInfo->shop_name}}</td>
              <td>{{ $saleInfo->terminal_name}}
                <input type="hidden" class="form-control" id="terminal_name" name="terminal_name" value="{{$saleInfo->terminal_name}}">
              </td>
              <td>{{ $saleInfo->staff_name}}
                <input type="hidden" class="form-control" id="staff" name="staff_name" value="{{$saleInfo->staff_name}}">
              </td>
              <td class="text-center">{{ number_format($saleInfo->amount_tax,2)}}</td>
              <td class="text-center">{{ number_format($saleInfo->amount,2)}}</td>
              <td class="text-center">{{ number_format($saleInfo->total,2)}}</td>
              <td>{{ $saleInfo->remark ?? "-"}}</td>
              <td>
                <a href="{{ url('/sale_return/' . $saleInfo->id . '/get_sale_detail') }}">
                  <button type="button" class="btn bg-gradient-success">{{__('Sale Return Entry')}}</button>
                </a>
              </td>
            </tr>
            @endforeach
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
</form>

<style type="text/css">
  .bg-gradient-success{
  width: 100% !important;
  }
</style>
@endsection

@section('js')
<script type="text/javascript">
  $(".custom_pg_size").on("change", function() {
    $("#frm_sale_return").submit();
  });
</script>
@endsection

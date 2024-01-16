@extends('layouts.main')

@section('main-content')
<div class="col-md-12">
  @if(session()->has('success_status'))
  <div class="alert alert-info" role="alert">
    {{ session('success_status') }}
    @php \Session::forget('success_status'); @endphp
  </div>
  @endif
  @if(session()->has('error_status'))
  <div class="alert alert-warning" role="alert">
    {{ session('error_status') }}
  </div>
  @endif
  <div class="card card-info">
    {{-- form title --}}
    <div class="card-header">
      <h3 class="card-title">{{__('Sales')}} {{__('Return')}} {{__('Details')}} {{__('List')}}</h3>
    </div>

    <div class="card-body">
      <div class="row">
        {{-- invoice number input --}}
        <label class="col-sm-2 col-form-label">{{__('Invoice')}}</label>
        <div class="col-sm-3">
        <input type="text" class="form-control" name="search_invoice_no" placeholder="Invoice No" value="{{ $returnDetails->return_invoice_number}}" readonly=""><br>
        </div>
        {{-- sael return date input --}}
        <label class="col-sm-2 col-form-label">{{__('Sales')}}{{__('Return Date')}}</label>
        <div class="col-sm-3">
           <input type="text" class="form-control" name="search_invoice_no" placeholder="Sale Return Date" value="{{ date('m/d/Y',strtotime($returnDetails->return_date))}}" readonly=""><br>
        </div>
      </div>

      {{--sale return detail table --}}
      <table id="example1" class="table table-bordered text-nowrap">
        <thead class="thead-light">
          <tr>
            <th>{{__('Product')}} {{__('Name')}}</th>
            <th>{{__('Price')}}</th>
            <th>{{__('Quantity')}}</th>
            <th>{{__('Remark')}} </th>
          </tr>
        </thead>
        <tbody>
          @php $total =0 @endphp
          @php $qty =0 @endphp
          @foreach($saleReturn as $data)
          <tr class="">
            <td>{{ $data->product_name}}</td>
            <td class="text-center">{{ (int)$data->price}}</td>
            <td class="text-center">{{ (int)$data->quantity}}</td>
            <td>{{ $data->remark ?? '-'}}
              @php $total +=$data->price @endphp
              @php $qty +=$data->quantity @endphp
          </tr>
          @endforeach
          <tr>
            <td colspan="1"></td>
            <td class="text-center"><b>{{ $total}}</b></td>
            <td class="text-center"><b>{{ $qty}}</b></td>
            <td></td>
          </tr>
        </tbody>
      </table>
    </div>

    <div class="card-footer">
      <form method="get" url="{{ route('salereturn.details')}}" id="frm_sale_details">
        {{-- sale return detail list pagination size filters --}}
        <div class="form-group row">
          <input type="hidden" name="return_id" value="{{$return_id}}">
          <label class="mr-sm-2 mx-sm-2 pt-1" for="inlineFormCustomSelect">{{ __('Show Items') }}</label>
          <select class="custom-select mr-sm-2 custom_pg_size" id="inlineFormCustomSelect" style="width:6%" name="custom_pg_size">
            <option value="10" {{request()->get('custom_pg_size')=='10' || ''?'selected':''}}>10</option>
            <option value="20" {{ request()->get('custom_pg_size')=='20'?'selected':''}}>20</option>
            <option value="30" {{request()->get('custom_pg_size')=='30'?'selected':''}}>30</option>
          </select>
        </div>
      </form>
        {{-- back button --}}
        <a href="{{route('sale_return')}}" class=" btn btn-info mx-sm-2">{{__('Back')}}</a>
        {{ $saleReturn->appends(['return_id'=>request()->return_id,'custom_page_size'=>request()->custom_page_size])->links() }}
    </div>
  </div>
</div>

<style type="text/css">
  #shop_damage_div {
    margin-left: 20px;
  }
</style>
@endsection

@section('js')
<script type="text/javascript">
  $(".custom_pg_size").on("change", function() {
    $("#frm_sale_details").submit();
  });
</script>
@endsection

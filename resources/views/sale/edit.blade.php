@extends('layouts.main')

@section('main-content')
<div class="col-md-10 offset-md-1">
  @if(session()->has('error_status'))
  <div class="alert alert-warning" role="alert">
    {{ session('error_status') }}
  </div>
  @endif
  <?php $number = 1;?>
  <div class="card card-info">
    {{-- form title --}}
    <div class="card-header">
      <h3 class="card-title">{{__('Sales')}} {{__('Invoice')}}</h3>
    </div>

    <form action="{{ route('sale.cancel_invoice', $sale->id)}}" enctype="multipart/form-data" method="post">
      @csrf
      @method('PUT')
      <div class="card-body">
        <div class="row">
          {{-- invoice number input --}}
          <label class="col-sm-3 col-form-label">{{__('Invoice Number')}}</label>
          <div class="col-sm-3">
            <input type="text" readonly class="form-control" name="name" placeholder="Name" value="{{$sale->invoice_number}}"><br>
          </div>
          {{-- sale date from input --}}
          <label class="col-sm-3 col-form-label">{{__('Sale Date From')}}</label>
          <div class="col-sm-2">
            <input type="text" readonly class="form-control" name="name" placeholder="Sale Date" value="{{$sale->sale_date}}"><br>
          </div>
        </div>

        <div class="row">
          {{-- staff name input --}}
          <label class="col-sm-3 col-form-label">{{__('Staff')}} {{__('Name')}}</label>
          <div class="col-sm-3">
            <input type="text" readonly class="form-control" name="staff_name" placeholder="Staff Name" value="{{$sale->staff_name}}"><br>
          </div>
          {{-- terminal name input --}}
          <label class="col-sm-3 col-form-label">{{__('Terminal')}} {{__('Name')}}</label>
          <div class="col-sm-2">
            <input type="text" readonly class="form-control" name="terminal_name" placeholder="Terminal Name" value="{{$sale->terminal_name}}"><br>
          </div>
        </div>

        <div class="row">
          {{-- sale amount input --}}
          <label class="col-sm-3 col-form-label">{{__('Amount')}}</label>
          <div class="col-sm-3">
            <input type="text" readonly class="form-control" name="staff_name" placeholder="Amount" value="{{number_format($sale->amount,2)}}"><br>
          </div>
          {{-- sale total input --}}
          <label class="col-sm-3 col-form-label">{{__('Total')}}</label>
          <div class="col-sm-2">
            <input type="text" readonly class="form-control" name="staff_name" placeholder="Total" value="{{number_format($sale->total,2)}}"><br>
          </div>
          <input type="hidden" name="shop_id" value="{{$sale->shop_id}}" />
          <input type="hidden" name="id" value="{{$sale->id}}" />
        </div>

        <div class="row">
          <label class="col-sm-3 col-form-label">{{__('Reason')}}</label>
          <div class="col-sm-8">
            <textarea id="cancellation_reason" class=" form-control" name="cancellation_reason" placeholder="Reason"> {{$sale->reason}} </textarea>
            <span id="errorReason" class="invalid-feedback" role="alert">
              <strong></strong>
            </span>
          </div>
        </div>

        <div class="card-body" id="table_content">
          <div class="table-responsive">
            {{-- sale detail table --}}
            <table id="saleDetailTable" class="table table-bordered text-nowrap">
              <thead class="thead-light">
                <tr>
                  <th class="sorting">{{__('No')}}</th>
                  <th>{{__('Product')}} {{__('Name')}}</th>
                  <th>{{__('Price')}}</th>
                  <th>{{__('Quantity')}}</th>
                  <th>{{__('Remark')}}</th>
                </tr>
              </thead>
              <tbody>
                {{-- sale detail data list --}}
                @foreach($saleDetailsList as $detailInfo)
                <tr class="{{ $detailInfo->invoice_status == Config::get('constants.IN_ACTIVE') ? 'table-active' : '' }}">
                  <td class="text-center">{{ $number }}</td>
                  <td>{{ $detailInfo->product_name}}</td>
                  <td class="text-center">{{ $detailInfo->price}}</td>
                  <td class="text-center">{{ $detailInfo->quantity}}</td>
                  <td>{{$detailInfo->remark}}</td>
                </tr>
                <?php $number++;?>
                @endforeach
              </tbody>
            </table>
          </div>
          {{-- cancel button --}}
          <div class="card-footer">
            <form action="{{ route('sale.cancel_invoice', $sale->id)}}" method="post">
              @csrf
              <button id="cancel_invoice" class="btn btn-primary" type="submit">{{__('Cancel Invoice')}}</button> </form> <a href="{{ url('sale') }}" class="btn btn-info mx-sm-2">{{__('Back')}}</a>
          </div>
        </div>
      </div>

    </form>
  </div>
</div>

<style type="text/css">
  #table_content {
    margin-left: -20px;
    margin-right: -20px;
  }
</style>
@endsection

@section('js')
<script type="text/javascript">
  $("#cancel_invoice").click(function(event) {
    var r = confirm("Are you sure to cancel the whole invoice?!");
    if (r == true) {
      var reason = $('#cancellation_reason').val();
      var error_free = false;
      if (reason.trim().length > 0 && reason != '') {
        error_free = true;
      }

      if (!error_free) {

        $('#cancellation_reason').addClass("is-invalid");

        $('#errorReason').html('Please enter cancellation reason.');
        event.preventDefault();
      }

      return;
    } else {
      event.preventDefault();
      return;
    }
  });
</script>
@endsection
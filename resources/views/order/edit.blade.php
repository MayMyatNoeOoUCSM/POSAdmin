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
      <h3 class="card-title">{{__('Order')}} {{__('Invoice')}}</h3>
    </div>

    <form action="{{ route('order.cancel_order', $order->id)}}" enctype="multipart/form-data" method="post">
      @csrf
      @method('PUT')
      <div class="card-body">
        <div class="row">
          {{-- invoice number input --}}
          <label class="col-sm-3 col-form-label">{{__('Invoice Number')}}</label>
          <div class="col-sm-3">
            <input type="text" readonly class="form-control" name="name" placeholder="Name" value="{{$order->invoice_number}}"><br>
          </div>
          {{-- order date from input --}}
          <label class="col-sm-3 col-form-label">{{__('Order Date From')}}</label>
          <div class="col-sm-2">
            <input type="text" readonly class="form-control" name="name" placeholder="Order Date" value="{{$order->order_date}}"><br>
          </div>
        </div>

        <div class="row">
          {{-- restaurant name input --}}
          <label class="col-sm-3 col-form-label">{{__('Restaurant')}} {{__('Name')}}</label>
          <div class="col-sm-3">
            <input type="text" readonly class="form-control" name="restaurant_name" placeholder="Restaurant Name" value="{{$order->restaurant_name}}"><br>
          </div>
          {{-- terminal name input --}}
          <label class="col-sm-3 col-form-label">{{__('Terminal')}} {{__('Name')}}</label>
          <div class="col-sm-2">
            <input type="text" readonly class="form-control" name="terminal_name" placeholder="Terminal Name" value="{{$order->terminal_name}}"><br>
          </div>
        </div>

        <div class="row">
          {{-- order amount input --}}
          <label class="col-sm-3 col-form-label">{{__('Amount')}}</label>
          <div class="col-sm-3">
            <input type="text" readonly class="form-control" name="order_amount" placeholder="Amount" value="{{number_format($order->amount,2)}}"><br>
          </div>
          {{-- order total input --}}
          <label class="col-sm-3 col-form-label">{{__('Total')}}</label>
          <div class="col-sm-2">
            <input type="text" readonly class="form-control" name="order_total" placeholder="Total" value="{{number_format($order->total,2)}}"><br>
          </div>
          <input type="hidden" name="shop_id" value="{{$order->shop_id}}" />
          <input type="hidden" name="id" value="{{$order->id}}" />
        </div>

     <!--    <div class="row">
          <label class="col-sm-3 col-form-label">{{__('Reason')}}</label>
          <div class="col-sm-8">
            <textarea id="cancellation_reason" class=" form-control" name="cancellation_reason" placeholder="Reason"> {{$order->reason}} </textarea>
            <span id="errorReason" class="invalid-feedback" role="alert">
              <strong></strong>
            </span>
          </div>
        </div> -->

        <div class="card-body" id="table_content">
          <div class="table-responsive">
            {{-- order detail table --}}
            <table id="orderDetailTable" class="table table-bordered text-nowrap">
              <thead class="thead-light">
                <tr>
                  <th class="sorting">No</th>
                  <th>{{__('Product')}} {{__('Name')}}</th>
                  <th>{{__('Price')}}</th>
                  <th>{{__('Quantity')}}</th>
                  <th>{{__('Remark')}}</th>
                </tr>
              </thead>
              <tbody>
                {{-- order detail data list --}}
                @foreach($orderDetailsList as $detailInfo)
                <tr class="{{ $detailInfo->order_status == Config::get('constants.IN_ACTIVE') ? 'table-active' : '' }}">
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
          <!--   <form action="{{ route('order.cancel_order', $order->id)}}" method="post">
              @csrf
              <button id="cancel_order" class="btn btn-primary" type="submit">{{__('Cancel Order')}}</button> 
            </form>  -->
            <a href="{{ url('order') }}" class="btn btn-info mx-sm-2">{{__('Back')}}</a>
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
  $("#cancel_order").click(function(event) {
    var r = confirm("Are you sure to cancel the whole order?!");
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
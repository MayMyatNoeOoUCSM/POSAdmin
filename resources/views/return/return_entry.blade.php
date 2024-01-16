@extends('layouts.main')

@section('main-content')
<div class="col-md-12">
  @if(session()->has('error_status'))
  <div class="alert alert-warning" role="alert">
    {{ session('error_status') }}
  </div>
  @endif
  <?php $number = 1;?>
  <div class="card card-info">
    {{-- form title --}}
    <div class="card-header">
      <h3 class="card-title align-content-center">{{__('Sale Return Entry')}}</h3>
    </div>

    @if ($errors->any())
    <div class="alert alert-danger">
      <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
    @endif

    {{-- form for sale return store --}}
    <form action="{{ route('sale_return.store') }}" enctype="multipart/form-data" id="sale_return_store_form" method="POST">
        @csrf
        <div class="card-body">
          <div class="row">
            {{-- invoice number input --}}
            <label class="col-sm-2 col-form-label">{{__('Invoice Number')}}</label>
            <div class="col-sm-3">
              <input type="text" readonly class="form-control" name="ret_invoice_no" placeholder="Name" value="{{$sale->invoice_number}}"><br>
            </div>
            {{-- sales date input --}}
            <label class="col-sm-2 col-form-label">{{__('Sales')}} {{__('Date')}}</label>
            <div class="col-sm-3">
              <input type="text" readonly class="form-control" name="sale_date" placeholder="Sale Date" value="{{date('m/d/Y',strtotime($sale->sale_date))}}" id="sale_date"><br>
            </div>
          </div>

          <div class="row">
            {{-- staff name input --}}
            <label class="col-sm-2 col-form-label">{{__('Staff')}} {{__('Name')}}</label>
            <div class="col-sm-3">
              <input type="text" readonly class="form-control" name="staff_name" placeholder="Staff Name" value="{{$sale->staff_name}}"><br>
            </div>
            {{-- return date input --}}
            <label class="col-sm-2 col-form-label">{{__('Return Date')}}</label>
            <div class="col-sm-3">
              <input type="date" value="<?php echo date('Y-m-d'); ?>" class="form-control datetimepicker-input" id="return_date" name="return_date">
              <p class="text-danger req" id="return_date_p"></p>
              {{-- return error message for return date --}}
              @error('return_date')
              <label class="text-danger">&nbsp;*{{ $message }}</label>
              @enderror
              <br>
            </div>
          </div>

          <div class="row">
            {{-- shop name input --}}
            <label class="col-sm-2 col-form-label">{{__('Shop')}} {{__('Name')}}</label>
            <div class="col-sm-3">
              <input type="text" readonly class="form-control" name="shop_name" placeholder="Shop Name" value="{{$sale->shop_name}}"><br>
            </div>
            {{-- terminal name input --}}
            <label class="col-sm-2 col-form-label">{{__('Terminal')}} {{__('Name')}}</label>
            <div class="col-sm-3">
              <input type="text" readonly class="form-control" name="terminal_name" placeholder="Terminal Name" value="{{$sale->terminal_name}}"><br>
            </div>
          </div>

          <div class="row">
            {{-- sale amount input --}}
            <label class="col-sm-2 col-form-label">{{__('Amount')}}</label>
            <div class="col-sm-3">
              <input type="text" readonly class="form-control" name="amount" placeholder="Amount" value="{{number_format($sale->amount,2)}}"><br>
            </div>
            {{-- sale total input --}}
            <label class="col-sm-2 col-form-label">{{__('Total')}}</label>
            <div class="col-sm-3">
              <input type="text" readonly class="form-control" name="total" placeholder="Total" value="{{number_format($sale->total, 2)}}"><br>
            </div>
            <input type="hidden" name="shop_id" value="{{$sale->shop_id}}" />
            <input type="hidden" name="ret_sale_id" value="{{$sale->id}}" />
          </div>
        </div>

        <div class="card-body">
          <div class="table-responsive">
            {{-- return entry table --}}
            <table id="retDetailTable" class="table table-bordered text-nowrap">
              <thead class="thead-light">
                <tr>
                  <th style="display:none;" style="width:20px;" class="sorting">No</th>
                  <th>{{__('Product')}}{{__('Name')}}</th>
                  <th>{{__('Price')}}</th>
                  <th>{{__('Sales')}} {{__('Quantity')}}</th>
                  <th>{{__('Total')}} {{__('Return')}} {{__('Quantity')}}</th>
                  <th>{{__('Return')}} {{__('Quantity')}}</th>
                  <th>{{__('Has Damage?')}}</th>
                  <th>{{__('Damage')}} {{__('Quantity')}}</th>
                  <th>{{__('Return')}} {{__('Reason')}}</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                @php ($i = 0)
                {{-- sale detail data list --}}
                @foreach($saleDetailsList as $detailInfo)
                <tr class="{{ $detailInfo->invoice_status == Config::get('constants.IN_ACTIVE') ? 'table-active' : '' }}">
                  <td style="display:none;">{{ $number }}</td>
                  <td>
                    <input type="hidden" name="product_id[]" value="{{$detailInfo->product_id}}" />
                    {{ $detailInfo->product_name}}
                  </td>
                  <td class="text-center">
                    <input type="hidden" name="price[]" value="{{$detailInfo->price}}" />
                    {{number_format($detailInfo->price, 2)}}
                  </td>
                  <td class="text-center">{{ $detailInfo->quantity}}
                    <input type="hidden" name="total_sale_quantity[]" value="{{$detailInfo->quantity}}" id="total_sale_quantity{{$number}}" />
                  </td>
                  <td class="text-center"><input type="text" class="form-control right return_total_qty{{$number}}" id="return_total_qty{{$number}}" name="return_total_qty[]" value="{{$detailInfo->total_return_qty ?? 0}}" disabled=""></td>
                  <td class="text-center position-relative">
                    <input type="text" class="form-control right number_only qty{{$number}}" name="qty[]" id="qty{{$number}}">
                    <span class="text-danger req p{{$number}} position-absolute" id="p{{$number}}" style="display: contents;"></span>
                  </td>
                  <td class="text-center">
                    <label class="custom-checkbox">
                      <input type="checkbox" id="damageChk{{$number}}" name="damageChk[]" class=" custom-checkbox">
                      <span class="custom-control-indicator"></span>
                    </label>
                    <input value="off" type="hidden" name="damageChkState[]">
                  </td>
                  <td class="text-center position-relative">
                    <input readonly type="text" class="form-control right number_only damage_qty{{$number}}" id="damage_qty{{$number}}" name="damage_qty[]" value="0">
                    <span class="text-danger req position-absolute" id="damage_p{{$number}}" style="display: contents;"></span>
                  </td>
                  <td class="text-center position-relative">
                    <textarea class="form-control return_reason{{$number}}" name="reason[]"></textarea>
                    <span class="text-danger req position-absolute" id="return_reason_p{{$number}}" style="display: contents;"></span>
                    @error("reason.$i")
                    <label class="text-danger">&nbsp;*{{ $message }}</label>
                    @enderror
                    @php ($i ++)
                  </td>
                  <td>
                 <!--    <button class="iconButton" type="button" onclick="rowDelete(this);"><span class="nav-icon fas fa-trash-alt iconSize" style="color:red"></span></button> -->
                     <button data-toggle="modal" class='deleteModal iconButton' data-id=this data-target="#deleteModalCenter" type="button">
                    <span style="color:red" class="nav-icon fas fa-trash-alt iconSize"></span></button>
                  </td>
                </tr>
                <?php $number++;?>
                @endforeach
              </tbody>
            </table>
          </div>

          {{-- submit and back buttons --}}
          <div class="card-footer">
            <button id="btnSave" class="btn btn-primary" type="button">{{__('Submit')}}</button>
            <a href="{{route('sale_return.create')}}" class=" btn btn-info mx-sm-2">{{__('Back')}}</a>
          </div>
        </div>
    </form>
  </div>
</div>
{{-- confirm delete modal --}}
<div class="modal fade" id="deleteModalCenter" tabindex="-1" role="dialog" aria-labelledby="deleteModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalCenterTitle">{{__('Confirm Delete')}}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">
         {{ __('message.A0009') }}
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" onclick="rowDelete();">Yes</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>

<style type="text/css">
  .req{
    display: none;
    bottom: 0;
    left:25%;
  }
  table input[type="text"] {
    width: 70px;
  }
  input[type="text"] , input[type="checkbox"], textarea{
    margin:0 auto;
  }
  textarea {
    margin:10px auto;
  }
</style>
@endsection

@section('js')
<script type="text/javascript">
  var object;
   $('.deleteModal').on('click',function(){
      object = $(this);
  });
  $('.number_only').bind('keyup paste', function(){
        this.value = this.value.replace(/[^0-9]/g, '');
  });
  function isInteger(value) {
    return /^\d+$/.test(value);
  }

  function isFutureDate(value) {
    d_now = new Date();
    d_inp = new Date(value);
    return d_now.getTime() <= d_inp.getTime();
  }

  function rowDelete() {

    $('#deleteModalCenter').modal('hide');
    var rowCount = $("#retDetailTable td").closest("tr").length;
    if (rowCount <= 1) {
      alert("You can't delete when there is only one record.");
      $(object).prop('disabled', true);
      return false;
    }
    $(object).closest('tr').remove();

    // if (typeof(btndel) == "object") {
    //   var response = confirm("Are you sure you want to delete this record?");
    //   if (response == true) {
    //     $(btndel).closest('tr').remove();
    //   } else {
    //     return false;
    //   }
    // } else {
    //   return false;
    // }
  }
  $('td input[type="checkbox"]').click(function() {
    if (this.checked == false) {
      $(this).closest('tr').find('input[name="damage_qty[]"]').val('');
      $(this).closest('tr').find('input[name="damageChkState[]"]').val('off');
    }
    if (this.checked == true) {
      $(this).closest('tr').find('input[name="damageChkState[]"]').val('on');
    }
    $(this).closest('tr').find('input[name="damage_qty[]"]').prop('readonly', !this.checked);

  }).change();

  $("#btnSave").click(function() {
    var submitOk = true;
    var rowCount = $("#retDetailTable td").closest("tr").length;
    $('.req').html('');
    var saleDate = $("#sale_date").val();
    var returnDate = $('#return_date').val();

    if (returnDate == "Invalid Date") {
      $("p#return_date_p").text("Please fill!");
    } else if (isFutureDate(returnDate)) {
      $("p#return_date_p").text("Return Date should not be future date.");
      submitOk = false;
    } else if (saleDate > $('#return_date').val()) {
      $("p#return_date_p").text("Return Date must be greater than or equal to Sale Date.");
      submitOk = false;
    }
    var rowNumber = 1;

    for (var i = 0; i < rowCount; i++) {
      var row = $("#retDetailTable td").closest('tr:nth-child(' + (rowNumber) + ')').find("td:first").text();

      var retQty = $('.qty' + row).val();

      var totalRetQty = $("#return_total_qty" + row).val();

      var salQty = $("#retDetailTable td").closest('tr:nth-child(' + (rowNumber) + ')').children('td:nth-child(4)').text();

      var $chkbox = $("#retDetailTable td").closest('tr:nth-child(' + (rowNumber) + ')').find('input[type="checkbox"]');

      var retReason = $('.return_reason' + row).val();

      $("input#qty" + row).css("border", "1px solid #ced4da");
      $("input#damage_qty" + row).css("border", "1px solid #ced4da");
      $("textarea.return_reason" + row).css("border", "1px solid #ced4da");

      // Only check rows that contain a checkbox

      if ($chkbox.length) {
        var status = $chkbox.prop('checked');
        if (status == true) {
          var damageQty = $('.damage_qty' + row).val();
          if (!$('.damage_qty' + row).val()) {
            $("input#damage_qty" + row).css("border", "2px solid red");
            $("span#damage_p" + row).text("Please fill!");
            submitOk = false;
          } else if (damageQty == 0) {
            $("input#damage_qty" + row).css("border", "2px solid red");
            $("span#damage_p" + row).text("Quantity must not be zero.");
            submitOk = false;
          } else if (!isInteger($('.damage_qty' + row).val())) {
            $("input#damage_qty" + row).css("border", "2px solid red");
            $("span#damage_p" + row).text("Numeric");
            submitOk = false;
          } else if (parseInt(damageQty) > parseInt(retQty)) {
            $("input#damage_qty" + row).css("border", "2px solid red");
            $("span#damage_p" + row).text("Damage qauntity must be less than or equal to return quantity.");
            submitOk = false;
          }
        }
      } //
      if (!retQty) {
        $("input#qty" + row).css("border", "2px solid red");
        $("span#p" + row).text("Please fill!");
        submitOk = false;
      } else if (!isInteger(retQty)) {
        $("input#qty" + row).css("border", "2px solid red");
        $("span#p" + row).text("Numeric");
        submitOk = false;
      } else if (retQty == 0) {
        $("input#qty" + row).css("border", "2px solid red");
        $("span#p" + row).text("Quantity must not be zero.");
        submitOk = false;
      } else if (parseInt(retQty) > parseInt(salQty)) {
        $("input#qty" + row).css("border", "2px solid red");
        $("span#p" + row).text("Return quantity must be less than or equal to sale quantity.");
        submitOk = false;
      } else if (parseInt(retQty) + parseInt(totalRetQty) > parseInt(salQty)) {
        $("input#qty" + row).css("border", "2px solid red");
        $("span#p" + row).text("Return quantity + Total return quantity must be less than or equal to sale qty.");
        submitOk = false;
      }
      if (!retReason) {
        $("textarea.return_reason" + row).css("border", "2px solid red");
        $("span#return_reason_p" + row).text("Please fill!");
        submitOk = false;
      }
      rowNumber++;
    }

    if (submitOk) {
      $('#sale_return_store_form').submit();
    } else {
      $(".req").show();
    }
  });
</script>
@endsection

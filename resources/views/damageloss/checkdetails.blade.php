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
      <h3 class="card-title">{{__('Damage')}}/{{__('Loss')}} {{__('Details')}} {{__('List')}}</h3>
    </div>

    <div class="card-body">
      <div class="table-responsive">
        {{-- damage/loss table --}}
        <table id="example1" class="table table-bordered text-nowrap">
          <thead class="thead-light">
            <tr>
              <th>{{__('Shop')}} {{__('Name')}}</th>
              <th>{{__('From Sales Return')}}</th>
              <th>{{__('Warehouse')}} {{__('Name')}}</th>
              <th>{{__('Product')}} {{__('Name')}}</th>
              <th>{{__('Status')}}</th>
              <th>{{__('Damage')}}/{{__('Loss')}} {{__('Quantity')}}</th>
              <th>{{__('Date')}} </th>
            </tr>
          </thead>
          <tbody>
            @php $total =0 @endphp
            @foreach($result as $data)
            <tr class="">
              <td>{{ $data->shop_name ?? "-" }}</td>
              <td>{{ ($data->return_id >=1 ? "Yes" :"-") }}</td>
              <td>{{ ($data->warehouse_name ?? "-") }}</td>
              <td>{{ $data->product_name}}</td>
              <td>{{ $data->product_status}}</td>
              <td class="text-center">{{ $data->totaldamageqty}}</td>
              <td>{{ date('m/d/Y',strtotime($data->damage_loss_date))}}
                @php $total +=$data->totaldamageqty @endphp
            </tr>
            @endforeach
            <tr>
              <td colspan="5">Total Damage Quantity</td>
              <td class="text-center"><b>{{ $total}}</b></td>
              <td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <div class="card-footer">
      <form method="get" url="{{ route('damageloss.details')}}" id="frm_damage_loss_details">
        <input type="hidden" name="warehouse_id" value="{{request()->warehouse_id}}">
        <input type="hidden" name="shop_id" value="{{request()->shop_id}}">
        {{-- damage/loss pagination size filters --}}
        <div class="form-group row">
          <label class="mr-sm-2 mx-sm-2 pt-1" for="inlineFormCustomSelect">{{ __('Show Items') }}</label>
          <select class="custom-select mr-sm-2 custom_pg_size" id="inlineFormCustomSelect" style="width:6%" name="custom_pg_size">
            <option value="10" {{request()->get('custom_pg_size')=='10' || ''?'selected':''}}>10</option>
            <option value="20" {{ request()->get('custom_pg_size')=='20'?'selected':''}}>20</option>
            <option value="30" {{request()->get('custom_pg_size')=='30'?'selected':''}}>30</option>
          </select>
        </div>
      </form>
      {{-- back button --}}
      <a href="{{ url('damage_loss') }}" class="btn btn-info">{{ __('Back') }}</a>
      {{ $result->appends($_GET)->links() }}
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
    $("#frm_damage_loss_details").submit();
  });
</script>
@endsection

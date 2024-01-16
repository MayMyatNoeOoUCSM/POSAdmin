@extends('layouts.main')

@section('main-content')
{{-- damage/loss search form --}}
<form action="{{ route('damageloss')}}" method="get" id="frm_damage_loss">
  @csrf
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
    @if(Auth::guard('staff')->user()->role == config('constants.COMPANY_ADMIN'))
    <div class="form-group row">
      {{-- shop name input for damage/loss search --}}
      <label class="col-sm-2 col-form-label">{{__('Shop')}} {{__('Name')}}</label>
      <div class="col-sm-3">
        <select name="search_shop_name" class="form-control" id="search_shop_name" {{app('request')->input('shop_damage_search_status') ==2 ? 'disabled':''}}>>
          <option value="" selected>{{__('Select Shop')}}</option>
          {{-- shop data list --}}
          @foreach($shopList as $shop)
          <option value="{{$shop->name}}" {{ app('request')->input('search_shop_name') == $shop->name ? 'selected' : '' }}> {{$shop->name}} </option>
          @endforeach
        </select>
      </div>
      {{-- warehouse name input for damage/loss search --}}
      <label class="col-sm-2 col-form-label">{{__('Warehouse')}} {{__('Name')}}</label>
      <div class="col-sm-3">
        <input type="text" class="form-control" name="search_warehouse_name" placeholder="{{__('Name')}}" value="{{ app('request')->input('search_warehouse_name') != '' ? app('request')->input('search_warehouse_name') : '' }}" {{app('request')->input('shop_damage_search_status') ==1 ? 'disabled':(app('request')->input('shop_damage_search_status') ==""?"disabled":"")}}><br>
      </div>
      {{-- search button --}}
      <div class="col-sm-1">
        <input class="btn btn_search" name="search" type="submit" value="{{__('Search')}}">
      </div>
    </div>
    @else
    <div class="form-group row">
      {{-- shop name input for damage/loss search --}}
      <label class="col-sm-2 col-form-label">{{__('Shop')}} {{__('Name')}}</label>
      <div class="col-sm-3">
        <select name="search_shop_name" class="form-control" id="search_shop_name" {{app('request')->input('shop_damage_search_status') ==2 ? 'disabled':''}}>>
          <option value="" selected>{{__('Select Shop')}}</option>
          {{-- shop data list --}}
          @foreach($shopList as $shop)
          <option value="{{$shop->name}}" {{ app('request')->input('search_shop_name') == $shop->name ? 'selected' : '' }}> {{$shop->name}} </option>
          @endforeach
        </select>
      </div>
      <div class="col-sm-1">
        <input class="btn btn_search" name="search" type="submit" value="{{__('Search')}}">
      </div>
    </div>
    @endif
    @if(Auth::guard('staff')->user()->role == config('constants.COMPANY_ADMIN'))
    <div class="form-group row">
      {{-- shop damage/loss checkbox --}}
      <div class="col-sm-2" id="shop_damage_div">
        <input type="checkbox" class="form-check-input" name="shop_damage_search_status" value="1" {{ app('request')->input('shop_damage_search_status') =="" || app('request')->input('shop_damage_search_status') ==1 ? 'checked':''}}>
        <label class="form-check-label">{{__('Shop')}} {{__('Damage')}}/{{__('Loss')}}</label>
      </div>
      {{-- warehouse damage/loss checkbox --}}
      <div class="form-check">
        <input type="checkbox" class="form-check-input" name="shop_damage_search_status" value="2" {{ app('request')->input('shop_damage_search_status') ==2 ? 'checked':''}}>
        <label class="form-check-label">{{__('Warehouse')}} {{__('Damage')}}/{{__('Loss')}}</label>
      </div>
    </div>
    @else
     <div class="form-group row">
      {{-- shop damage/loss checkbox --}}
      <div class="col-sm-2" id="shop_damage_div">
        <input type="checkbox" class="form-check-input" name="shop_damage_search_status" value="1" {{ app('request')->input('shop_damage_search_status') =="" || app('request')->input('shop_damage_search_status') ==1 ? 'checked':''}}>
        <label class="form-check-label">{{__('Shop')}} {{__('Damage')}}/{{__('Loss')}}</label>
      </div>
    </div>
    @endif
  </div>
  {{-- add button --}}
  <div class="form-group" style="margin-top:15px">
    <a href="{{ url('damage_loss/create') }}" class="btn mx-sm-2 btn_add ">{{__('Add')}}</a>
  </div>

  <div class="card card-info">
    {{-- form title --}}
    <div class="card-header">
      <h3 class="card-title">{{__('Current')}} {{__('Damage')}} {{__('Loss')}} {{__('List')}}</h3>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        {{-- damage/loss table --}}
        <table id="damageLossList" class="table table-bordered text-nowrap">
          <thead class="thead-light">
            <tr>
              <th>{{__('Shop')}} {{__('Name')}}</th>
              <th>{{__('Warehouse')}} {{__('Name')}}</th>
              <th>{{__('Damage')}}/{{__('Loss')}} {{__('Quantity')}}</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            @php $total =0 @endphp
            {{-- damageloss data list --}}
            @foreach($damageLossList as $damageLoss)
            <tr class="">
              <td>{{ $damageLoss->shop_name ?? "-"}}</td>
              <td>{{ ($damageLoss->warehouse_name ?? "-") }}</td>
              <td class="text-center">{{ $damageLoss->totaldamageqty}}</td>
              <td><a href="{{ route('damageloss.details',  array('warehouse_id' => $damageLoss->warehouse_id ?? 0, 'shop_id' => $damageLoss->shop_id ?? 0)) }}"><button type="button" class="btn bg-gradient-success" id="btn_check_details">{{__('Check Details')}}</button></a></td>
              @php $total +=$damageLoss->totaldamageqty @endphp
            </tr>
            @endforeach
            <tr>
              <td colspan="2">Total Damage Quantity</td>
              <td class="text-center"><b>{{ $total}}</b></td>
              <td></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <div class="card-footer">
      {{-- damageloss list pagination size filters --}}
      <nav>
        <label class="mr-sm-2 mx-sm-2 pt-1" for="inlineFormCustomSelect">{{ __('Show Items') }}</label>
        <select class="custom-select mr-sm-2 custom_pg_size" id="inlineFormCustomSelect" style="width:34%" name="custom_pg_size">
          <option value="10" {{request()->get('custom_pg_size')=='10' || ''?'selected':''}}>10</option>
          <option value="20" {{ request()->get('custom_pg_size')=='20'?'selected':''}}>20</option>
          <option value="30" {{request()->get('custom_pg_size')=='30'?'selected':''}}>30</option>
        </select>
      </nav>
      {{-- damagelost list pagination --}}
      {{ $damageLossList->appends($_GET)->links() }}
    </div>
  </div>
</form>

<style type="text/css">
  #shop_damage_div {
    margin-left: 20px;
  }
  @media(max-width: 700px) {
    #btn_check_details {
      max-height: 42px;
      font-size: 12px;
    }
  }
  @media (max-width: 576px) {
    .form-group {
      margin-bottom: 0px !important;
    }
    .form-check {
      margin-left: 7px;
    }
    #btn_add {
      margin: 0px 5px;
    }
  }
</style>
@endsection

@section('js')
<script type="text/javascript">
  $(document).on('click', 'input[type="checkbox"]', function() {
    $('input[type="checkbox"]').not(this).prop('checked', false);
    if ($(this).val()==1) {
      $("input[name=search_warehouse_name]").val('');
      $("input[name=search_warehouse_name]").prop('disabled',true);
      $("#search_shop_name").prop('disabled',false);
    } else {
      $("#search_shop_name").prop('selectedIndex',0);
      $("#search_shop_name").prop('disabled',true);
      $("input[name=search_warehouse_name]").prop('disabled',false);
    }
  });
  $(".custom_pg_size").on("change", function() {
    $("#frm_damage_loss").submit();
  });
</script>
@endsection

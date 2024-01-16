@extends('layouts.main')

@section('main-content')
{{-- staff search form --}}
<form action="{{ route('staff')}}" method="get" id="frm_staff">
  <div class="col-md-12 clearfix" id="content">
    @if(session()->has('success_status'))
    <div class="alert alert-info" role="alert">
      {{ session('success_status') }}
    </div>
    @endif
    @csrf
    <div class="row">
      {{-- staff name input for staff search --}}
      <label class="col-form-label col-sm-2">{{__('User')}} {{__('Name')}}</label>
      <div class="col-sm-3 col-lg-3">
        <input type="text" class="form-control" name="search_name" placeholder="{{__('Name')}}" value="{{ app('request')->input('search_name') != '' ? app('request')->input('search_name') : '' }}">
      </div>
      {{-- staff number input for staff search --}}
      <label class="col-form-label col-sm-2">{{__('User')}} {{__('No')}}</label>
      <div class="col-sm-3">
        <select name="search_no" class="form-control">
          <option value="" selected>{{__('Select No')}}</option>
          {{-- staff number --}}
          @foreach($staffNos as $staffNo)
          <option value="{{$staffNo->staff_number}}" {{ app('request')->input('search_no') == $staffNo->staff_number ? 'selected' : '' }}>{{$staffNo->staff_number}}</option>
          @endforeach
        </select>
      </div>
    </div>

    <div class="row rowMarginTop">
      {{-- join from input for staff search --}}
      <label class="col-form-label date col-sm-2">{{__('Join From')}}</label>
        <div class="col-sm-3">
          <input type="text" onfocus="(this.type='date')" class="form-control datetimepicker-input" name="search_join_from" placeholder="{{__('From Date')}}" value="{{ app('request')->input('search_join_from') != '' ? app('request')->input('search_join_from') : '' }}" />
        </div>
        {{-- staff role input for staff search --}}
        <label class="col-form-label col-sm-2">{{__('User')}} {{__('Role')}}</label>
        <div class="col-sm-3">
          <select name="search_role" class="form-control" id="role">
            <option value="" selected>{{__('Select Role')}}</option>
            <option value="{{ config('constants.ADMIN') }}" {{ app('request')->input('search_role') == config('constants.ADMIN') ? 'selected' : '' }}>Admin</option>
            <option value="{{ config('constants.COMPANY_ADMIN') }}" {{ app('request')->input('search_role') == config('constants.COMPANY_ADMIN') ? 'selected' : '' }}>Company Admin</option>
            <option value="{{ config('constants.SHOP_ADMIN') }}" {{ app('request')->input('search_role') == config('constants.SHOP_ADMIN') ? 'selected' : '' }}>Shop Admin</option>
            <option value="{{ config('constants.CASHIER_STAFF') }}" {{ app('request')->input('search_role') == config('constants.CASHIER_STAFF') ? 'selected' : '' }}>Cashier Staff</option>
            <option value="{{ config('constants.KITCHEN_STAFF') }}" {{ app('request')->input('search_role') == config('constants.KITCHEN_STAFF') ? 'selected' : '' }}>Kitchen Staff</option>
            <option value="{{ config('constants.WAITER_STAFF') }}" {{ app('request')->input('search_role') == config('constants.WAITER_STAFF') ? 'selected' : '' }}>Waiter Staff</option>
            <option value="{{ config('constants.SALE_STAFF') }}" {{ app('request')->input('search_role') == config('constants.SALE_STAFF') ? 'selected' : '' }}>Sale Staff</option>
          </select>
        </div>
    </div>

    <div class="form-group row rowMarginTop">
      {{-- staff type input for staff search --}}
      <label class="col-form-label col-sm-2">{{__('User')}} {{__('Type')}}</label>
      <div class="col-sm-3">
        <select name="search_type" class="form-control">
          <option value="" selected>{{__('Select Type')}}</option>
          <option value="{{ config('constants.FULL_TIME') }}" {{ app('request')->input('search_type') == config('constants.FULL_TIME') ? 'selected' : '' }}>Full Time</option>
          <option value="{{ config('constants.PART_TIME') }}" {{ app('request')->input('search_type') == config('constants.PART_TIME') ? 'selected' : '' }}>Part Time</option>
        </select>
      </div>
      {{-- staff position input for staff search --}}
      <label class="col-form-label col-sm-2">{{__('Position')}}</label>
      <div class="col-sm-3">
        <select name="search_position" class="form-control">
          <option value="" selected>{{__('Select Position')}}</option>
          <option value="{{ config('constants.SYSTEM_ADMIN') }}" {{ app('request')->input('search_position') == config('constants.SYSTEM_ADMIN') ? 'selected' : '' }}>System ADMIN</option>
          <option value="{{ config('constants.OWNER') }}" {{ app('request')->input('search_position') == config('constants.OWNER') ? 'selected' : '' }}>Owner</option>
          <option value="{{ config('constants.MANAGER') }}" {{ app('request')->input('search_position') == config('constants.MANAGER') ? 'selected' : '' }}>Manager</option>
          <option value="{{ config('constants.OPERATION_STAFF') }}" {{ app('request')->input('search_position') == config('constants.OPERATION_STAFF') ? 'selected' : '' }}>Operation Staff</option>
        </select>
      </div>
   </div>
   {{-- search button --}}
   <div class="row buttonMargin">
     <div class="col-sm-1">
        <input class="btn btn_search" name="search" type="submit" value="{{__('Search')}}" id="btn_search">
      </div>
    </div>
  </div>

  {{-- add button --}}
  <div class="form-group" style="margin-top:15px">
    <a href="{{ url('staff/create') }}" class="btn mx-sm-2 btn_add ">{{__('Add')}}</a>
    <input class="btn mx-sm-2 btn_download" name="download" type="submit" value="{{__('Download')}}" id="btn_download">
  </div>

  <div class="clearfix card card-info">
    {{-- form title --}}
    <div class="card-header">
      <h3 class="card-title">{{__('User')}} {{__('List')}}</h3>
    </div>

    <div class="card-body">
      <div class="table-responsive">
        {{-- staff table --}}
        <table id="staffList" class="table table-bordered text-nowrap">
          <thead class="thead-light">
            <tr>
              <th></th>
              <th class="sorting">{{__('User')}} {{__('Name')}}</th>
              <th>{{__('User')}} {{__('No')}}</th>
              <th>{{__('Role')}}</th>
              <th>{{__('User')}} {{__('Type')}}</th>
              <th>{{__('Position')}}</th>
              <th>{{__('Current Job Place')}}</th>
              <th>{{__('NRC')}} {{__('No')}}</th>
              <th>{{__('Join From')}}</th>
              <th></th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            {{-- staff data list --}}
            @foreach($staffList as $staff)
            <tr class="{{ $staff->staff_status == config('constants.IN_ACTIVE') ? 'table-active' : '' }}">
            <!--   <td><img src="{{($staff->photo!=""? config('constants.BASE_STORAGE_HOST').config('constants.STAFF_PATH').'/'.$staff->photo:config('constants.BASE_STORAGE_HOST').config('constants.STAFF_PATH').'/'.'defaultadmin.png') }}" /></td> -->
              <td><img src="{{($staff->photo!=""? env('STAFF_PATH').'/'.$staff->photo:env('STAFF_PATH').'/'.'defaultadmin.png') }}" /></td>
              <td>{{$staff->name}}</td>
              <td>{{ $staff->staff_number }}</td>
              
              @if($staff->role == config('constants.ADMIN'))
              <td> Admin </td>
              @elseif($staff->role == config('constants.COMPANY_ADMIN'))
              <td> Company Admin </td>
              @elseif($staff->role == config('constants.SHOP_ADMIN'))
              <td> Shop Admin </td>
              @elseif($staff->role == config('constants.CASHIER_STAFF'))
              <td> Cashier Staff </td>
              @elseif($staff->role == config('constants.KITCHEN_STAFF'))
              <td> Kitchen Staff</td>
              @elseif($staff->role == config('constants.WAITER_STAFF'))
              <td> Waiter Staff</td>
              @elseif($staff->role == config('constants.SALE_STAFF'))
              <td> Sale Staff</td>
              @endif
              <td>{{ $staff->staff_type == config('constants.FULL_TIME') ? "Full Time" : "Part Time" }}</td>
             <!--  <td>{{ $staff->position == config('constants.CASHIER') ? "Cashier" : "Manager" }}</td> -->

              @if($staff->position == config('constants.SYSTEM_ADMIN'))
              <td> System Admin </td>
              @elseif($staff->position == config('constants.OWNER'))
              <td> Owner</td>
              @elseif($staff->position == config('constants.MANAGER'))
              <td> Manager </td>
              @elseif($staff->position == config('constants.OPERATION_STAFF'))
              <td> Operation Staff </td>
              @endif
              @if($staff->warehouse_name != null)
              <td>{{ $staff->warehouse_name }}</td>
              @elseif($staff->shop_name != null)
              <td>{{ $staff->shop_name }}</td>
              @elseif($staff->company_name != null)
              <td>{{ $staff->company_name }}</td>
              @else
              <td>-</td>
              @endif
              <td>{{ $staff->nrc_number }}</td>
              <td>{{ date('m/d/Y', strtotime($staff->join_from)) }}</td>
              <td><a href="{{ url('/staff/' . $staff->id . '/edit') }}" class="nav-icon fas fa-edit iconSize"></a></td>
              <td>
              <!--   <form action="{{ route('staff.delete', $staff->id)}}" method="post">
                  @csrf
                  @method('DELETE') -->
                 <!--  <button onclick="return confirm('Are you sure?')" type="submit"> -->
                   <button data-toggle="modal" class='deleteModal iconButton' data-id="{{$staff->id}}" data-target="#deleteModalCenter" type="button">
                    <span style="color:red" class="nav-icon fas fa-trash-alt iconSize"></span></button>
                <!-- </form> -->
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>

    <div class="card-footer">
      {{-- staff list pagination size filters --}}
      <nav>
        <label class="mr-sm-2 mx-sm-2 pt-1" for="inlineFormCustomSelect">{{ __('Show Items') }}</label>
        <select class="custom-select mr-sm-2 custom_pg_size" id="inlineFormCustomSelect" style="width:34%" name="custom_pg_size">
          <option value="10" {{request()->get('custom_pg_size')=='10' || ''?'selected':''}}>10</option>
          <option value="20" {{ request()->get('custom_pg_size')=='20'?'selected':''}}>20</option>
          <option value="30" {{request()->get('custom_pg_size')=='30'?'selected':''}}>30</option>
        </select>
      </nav>
      {{-- staff list pagination --}}
      {{ $staffList->appends($_GET)->links() }}
    </div>
  </div>
</form>
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
      {{-- staff delete form --}}
      <form action="" method="post" id="deleteForm">
          @csrf
          @method('DELETE')
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Yes</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
      </div>
    </form>
    </div>
  </div>
</div>

<style type="text/css">
  #btn_search {
    margin-top: 38px;
  }
  @media(max-width: 576px) {
    #btn_search {
      margin-top: 10px;
    }
  }
  img{
    width: 100px;
  }
</style>
@endsection

@section('js')
<script type="text/javascript">
  $('.deleteModal').on('click',function(){
      let id = $(this).attr('data-id');
       $('#deleteForm').attr('action', 'staff/'+id);
  });
  $(".custom_pg_size").on("change", function() {
    $("#frm_staff").submit();
  });
</script>
@endsection

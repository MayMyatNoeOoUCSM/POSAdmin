@extends('layouts.main')

@section('main-content')
<div class="col-md-10 offset-md-1">
  @if(session()->has('error_status'))
  <div class="alert alert-warning" role="alert">
    {{ session('error_status') }}
  </div>
  @endif
  <div class="card card-info">
    {{-- form title --}}
    <div class="card-header">
      <h3 class="card-title">{{__('User')}} {{ __('Edit') }}</h3>
    </div>

    {{-- form for update staff --}}
    <form action="{{  url('/staff/' . $staff->id ) }}" enctype="multipart/form-data" method="POST">
      @csrf
      @method('PUT')
      <div class="card-body">
        {{-- staff number input --}}
        <div class="row">
          <label class="col-sm-2 offset-sm-6 col-form-label" >{{__('User')}} {{ __('No')}} : </label>
          <input type="text" class="form-control col-sm-3" style="margin-left: -9;" name="staff_number" value="{{ old('staff_number')==''? $staff->staff_number:old('staff_number') }}" id="staff_number" readonly>
        </div>

        <div class="row">
        </div>

        <div class="row">
          {{-- staff name input --}}
          <div class="col-sm-5">
            <label class="col-form-label required">{{__('User')}} {{ __('Name')}}</label>
            <input type="text" class="form-control" name="name" placeholder="{{__('Name')}}" value="{{ old('name')==''? $staff->name :old('name') }}"><br>
          </div>
          {{-- staff role input --}}
          <div class="col-sm-5 offset-sm-1">
            <label class="col-form-label required">{{__('User')}} {{ __('Role')}}</label>
            <select name="role" class="form-control" id="role" onchange="getStaffNo()">
              <option value="">{{__('Select Role')}}</option>
              <option value="{{ config('constants.ADMIN') }}" {{ (old('role')==''?$staff->role:old('role')) == config('constants.ADMIN') ? 'selected' : ''  }}>Admin</option>
              <option value="{{ config('constants.COMPANY_ADMIN') }}" {{ (old('role')==''?$staff->role:old('role')) == config('constants.COMPANY_ADMIN') ? 'selected' : ''  }}>Company Admin</option>
              <option value="{{ config('constants.SHOP_ADMIN') }}" {{ (old('role')==''?$staff->role:old('role')) == config('constants.SHOP_ADMIN') ? 'selected' : ''  }}>Shop Admin</option>
              <option value="{{ config('constants.CASHIER_STAFF') }}" {{ (old('role')==''?$staff->role:old('role')) == config('constants.CASHIER_STAFF') ? 'selected' : ''  }}>Cashier Staff</option>
              <option value="{{ config('constants.KITCHEN_STAFF') }}" {{ (old('role')==''?$staff->role:old('role')) == config('constants.KITCHEN_STAFF') ? 'selected' : ''  }}>Kitchen Staff</option>
              <option value="{{ config('constants.WAITER_STAFF') }}" {{ (old('role')==''?$staff->role:old('role')) == config('constants.WAITER_STAFF') ? 'selected' : ''  }}>Waiter Staff</option>
              <option value= "{{ config('constants.SALE_STAFF') }}" {{ (old('role')==''?$staff->role:old('role')) == config('constants.SALE_STAFF') ? 'selected': ''}} >Sale Staff</option>
            </select>
          </div>
        </div>

        <div class="row">
          {{-- return error message for name input --}}
          <div class="col-sm-6">
            @error('name')
            <label class="text-danger">&nbsp;*{{ $message }}</label>
            @enderror
          </div>
          {{-- return error message for role input --}}
          <div class="col-sm-6">
            @error('role')
            <label class="text-danger">&nbsp;*{{ $message }}</label>
            @enderror
          </div>
        </div>

        <div class="row">
        </div>

        <div class="form-group row">
          {{-- staff position input --}}
          <div class="col-sm-5">
            <label class="col-form-label required">{{ __('Position')}}</label>
            <select name="position" class="form-control">
              <option value="">{{__('Select Position')}}</option>
              <option value="{{ config('constants.SYSTEM_ADMIN') }}" {{ (old('position')==''?$staff->position:old('position')) == config('constants.SYSTEM_ADMIN') ? 'selected' : ''  }}>System Admin</option>
              <option value="{{ config('constants.OWNER') }}" {{ (old('position')==''?$staff->position:old('position')) == config('constants.OWNER') ? 'selected' : ''  }}>Owner</option>
              <option value="{{ config('constants.MANAGER') }}" {{ (old('position')==''?$staff->position:old('position')) == config('constants.MANAGER') ? 'selected' : ''  }}>Manager</option>
              <option value="{{ config('constants.OPERATION_STAFF') }}" {{ (old('position')==''?$staff->position:old('position')) == config('constants.OPERATION_STAFF') ? 'selected' : ''  }}>Operation Staff</option>
            </select>
          </div>
          {{-- staff type input --}}
          <div class="col-sm-5 offset-sm-1">
            <label class="col-form-label required">{{__('User')}} {{ __('Type')}}</label>
            <select name="type" class="form-control">
              <option value="">{{__('Select Type')}}</option>
              <option value="{{ config('constants.FULL_TIME') }}" {{ (old('staff_type')==''?$staff->staff_type:old('staff_type')) == config('constants.FULL_TIME') ? 'selected' : ''  }}>Full Time</option>
              <option value="{{ config('constants.PART_TIME') }}" {{ (old('staff_type')==''?$staff->staff_type:old('staff_type')) == config('constants.PART_TIME') ? 'selected' : ''  }}>Part Time</option>
            </select>
          </div>
        </div>
        <div class="row">
          {{-- return error message for position input --}}
          <div class="col-sm-6">
            @error('position')
            <label class="text-danger">&nbsp;*{{ $message }}</label>
            @enderror
          </div>
          {{-- return error message for type input --}}
          <div class="col-sm-6">
            @error('type')
            <label class="text-danger">&nbsp;*{{ $message }}</label>
            @enderror
          </div>
        </div>

        <div class="row">
        </div>

        <div class="form-group row">
          {{-- nrc number input --}}
          <div class="col-sm-5">
            <label class="col-form-label required">{{ __('NRC')}} {{ __('No')}}</label>
            <input type="text" class="form-control" name="nrc_number" placeholder="NRC Number" value="{{(old('nrc_number')==''?$staff->nrc_number:old('nrc_number'))}}">
          </div>
          {{-- bank account number input --}}
          <div class="col-sm-5 offset-sm-1">
            <label class="col-form-label">{{__('Bank Account Number')}}</label>
            <input type="text" class="form-control" name="bank_acc_no" placeholder="{{__('Bank Account Number')}}" value="{{old('bank_acc_no')==''?$staff->bank_account_number:old('bank_acc_no')}}">
          </div>
        </div>
        {{-- return error message for nrc number input --}}
        @error('nrc_number')
        <label class="text-danger col-sm-6">&nbsp;*{{ $message }}</label>
        @enderror

        <div class="row">
        </div>

        <div class="form-group row">
          {{-- gender input --}}
          <div class="col-sm-5">
            <label class="col-form-label required">{{__('Gender')}}</label><br/>
            <input type="radio" name="gender" value="{{ config('constants.MALE') }}" {{ (old('gender')==''?$staff->gender:old('gender')) == config('constants.MALE') ? 'checked' : ''  }}> &nbsp; {{__('Male')}} &nbsp;
            <input type="radio" name="gender" value="{{ config('constants.FEMALE') }}" {{ (old('gender')==''?$staff->gender:old('gender')) == config('constants.FEMALE') ? 'checked' : ''  }}> &nbsp; {{__('Female')}} &nbsp;
            <input type="radio" name="gender" value="{{ config('constants.OTHER') }}" {{ (old('gender')==''?$staff->gender:old('gender')) == config('constants.OTHER') ? 'checked' : ''  }}> &nbsp; {{__('Other')}} &nbsp;
          </div>
          {{-- martial status input --}}
          <div class="col-sm-5 offset-sm-1">
            <label class="col-form-label required">{{__('Marital Status')}}</label><br/>
            <input type="radio" name="marital_status" value="{{ config('constants.SINGLE') }}" {{ (old('marital_status')==''?$staff->marital_status:old('marital_status')) == config('constants.SINGLE') ? 'checked' : ''  }}> &nbsp; {{__('Single')}} &nbsp;
            <input type="radio" name="marital_status" value="{{ config('constants.MARRIED') }}" {{ (old('marital_status')==''?$staff->marital_status:old('marital_status')) ==  config('constants.MARRIED') ? 'checked' : ''  }}> &nbsp; {{__('Married')}} &nbsp;
          </div>
        </div>

        <div class="row">
          {{-- return error message for gender input --}}
          <div class="col-sm-6">
            @error('gender')
            <label class="text-danger">&nbsp;*{{ $message }}</label>
            @enderror
          </div>
          {{-- return error message for martial status input --}}
          <div class="col-sm-6">
            @error('marital_status')
            <label class="text-danger">&nbsp;*{{ $message }}</label>
            @enderror
          </div>
        </div>

        <div class="row">
        </div>

        <div class="form-group row">
          {{-- phone number 1 input --}}
          <div class="col-sm-5">
            <label class="col-form-label">{{ __('Phone Number1')}} </label>
            <input type="text" class="form-control" name="ph_no1" placeholder="{{ __('Phone Number1')}}" value="{{ old('ph_no1') == ''?$staff->phone_number_1:old('ph_no1') }}">
          </div>
          {{-- phone number 2 input --}}
          <div class="col-sm-5 offset-sm-1">
            <label class="col-form-label">{{ __('Phone Number2')}}</label>
            <input type="text" class="form-control" name="ph_no2" placeholder="{{ __('Phone Number2')}}" value="{{ old('ph_no2') == ''?$staff->phone_number_2:old('ph_no2') }}">
          </div>
        </div>

        <div class="row">
          {{-- return error message for phone number 1 input --}}
          <div class="col-sm-6">
            @error('ph_no1')
            <label class="text-danger">&nbsp;*{{ $message }}</label>
            @enderror
          </div>
          {{-- return error message for phone number 2 input --}}
          <div class="col-sm-6">
            @error('ph_no2')
            <label class="text-danger">&nbsp;*{{ $message }}</label>
            @enderror
          </div>
        </div>

        <div class="row">
        </div>

        <div class="row">
          {{-- date of birth input --}}
          <div class="date col-sm-5">
            <label class="col-form-label">{{ __('Date Of Birth')}}</label>
            <input type="text" onfocus="(this.type='date')" class="form-control datetimepicker-input" name="dob" placeholder="{{__('Date Of Birth')}}" value="{{ old('dob')==''?$staff->dob:old('dob') }}" />
          </div>
          {{-- graduated university input --}}
          <div class="col-sm-5 offset-sm-1">
            <label class="col-form-label">{{ __('Graduated University')}}</label>
            <input type="text" class="form-control" name="graduated_univeristy" placeholder="{{__('Graduated University')}}" value="{{ old('graduated_univeristy')==''?$staff->graduated_univeristy:old('graduated_univeristy') }}">
          </div>
        </div>

        <div class="row">
        </div>

        <div class="form-group row">
          {{-- race input --}}
          <div class="col-sm-5">
            <label class="col-form-label">{{ __('Race')}}</label>
            <input type="text" class="form-control" name="race" placeholder="{{__('Race')}}" value="{{ old('race')==''?$staff->race:old('race') }}">
          </div>
          {{-- city input --}}
          <div class="col-sm-5 offset-sm-1">
            <label class="col-form-label">{{ __('City')}}</label>
            <input type="text" class="form-control" name="city" placeholder="{{__('City')}}" value="{{ old('city')==''?$staff->city:old('city') }}">
          </div>
        </div>

        <div class="row">
        </div>

        <div class="form-group row">
          {{-- address input --}}
          <div class="col-sm-5">
            <label class="col-form-label">{{ __('Address')}}</label>
            <textarea class="form-control" name="address" placeholder="{{__('Address')}}" value="{{ $staff->address }}">{{ old('address')==''?$staff->address:old('address') }}</textarea>
          </div>
          {{-- profile image input --}}
          <div class="col-sm-2 offset-sm-1">
            <label class="col-form-label">{{__('Profile Image')}}</label>
            <input type="file" id="image" name="image" onchange="putImage();" style="display: none;" />
            <input type="button" value="Browse..." onclick="document.getElementById('image').click();"  style="display: block;" />
            <input type="hidden" name="old_image" value="{{ $staff->photo }}" />
          </div>
          <div class="form-group col-sm-4">
           <!--  <img  id="target" width="110" height="110" style="float: left;margin-top: 10px;"/> -->
            <img id="target"  width="110" height="110" src="{{ ($staff->photo !=''?asset(env('STAFF_PATH').'/'.$staff->photo):'')}}" style="{{ $staff->photo != null ? 'display:block;' : 'display:none;' }}" />
          </div>
        </div>
        {{-- return error message for profile image input --}}
        @error('image')
        <label class="text-danger col-sm-6 offset-sm-6">&nbsp;*{{ $message }}</label>
        @enderror

        <div class="row">
        </div>

        <div class="form-group row">
          {{-- join from input --}}
          <div class="date col-sm-5">
            <label class="col-form-label required">{{ __('Join From')}}</label>
            <input type="date" class="form-control datetimepicker-input" name="join_from" placeholder="{{__('Start Date')}}" value="{{old('join_from')==''?$staff->join_from:old('join_from') }}" />
          </div>
          {{-- join to input --}}
          <div class="date col-sm-5 offset-sm-1">
            <label class="col-form-label">{{ __('Join To')}}</label>
            <input type="date" class="form-control datetimepicker-input" name="join_to" placeholder="{{__('End Date')}}" 
            value="{{ (old('join_to') == '' ? $staff->join_to:old('join_to')) }}" />
          </div>
        </div>
        {{--return error message for join from input --}}
        @error('join_from')
        <label class="text-danger col-sm-6">&nbsp;*{{ $message }}</label>
        @enderror

        {{-- warehouse/shop name input --}}
        <div class="row">
         <!--  <label class="col-sm-4 col-form-label" id="warehouse_label" style = "display:none;" }} >{{ __('Warehouse')}} {{ __('Name')}}</label> -->
         <label class="col-sm-4 col-form-label" id="company_label" style = "display:none;" }} >{{ __('Company')}}{{ __('Name')}}</label>
          <label class="col-sm-4 col-form-label" id="shop_label" style = "display:none;" }} >{{ __('Shop')}}{{ __('Name')}}</label>
        </div>

        <div class="form-group row">
          <!-- <div class="col-sm-5" id="warehouse_id" style = "display:none;" }} >
            <select name="warehouse_id" class="form-control">
              <option value="" selected>{{__('Select Company/Shop')}}</option>
              {{-- warehouse data list --}}
             
            </select>
          </div> -->
          <div class="col-sm-5" id="company_id" style = {{ old('role') != '' && old('role') == config('constants.COMPANY_ADMIN') ? "display:flex;" : "display:none;" }} >
            <select name="company_id" class="form-control">
              <option value="" selected>Select Company</option>
              {{-- company data list --}}
              @foreach($companyList as $company)
                <option value= "{{$company->id}}" {{ (old('company_id')==''?$staff->company_id:old('company_id')) == $company->id ? 'selected' : '' }}> {{$company->name}} </option>
              @endforeach
            </select>
          </div>
          <div class="col-sm-5" id="shop_id" style ={{ old('role') != '' && old('role') != config('constants.COMPANY_ADMIN') && old('role') != config('constants.ADMIN')? "display:flex;" : "display:none;" }}  >
            <select name="shop_id" class="form-control">
              <option value="" selected>Select Shop</option>
              {{-- shop data list --}}
              @foreach($shopList as $shop)
                <option value= "{{$shop->id}}" {{ (old('shop_id')==''?$staff->shop_id:old('shop_id')) == $shop->id ? 'selected' : '' }}> {{$shop->name}} </option>
              @endforeach
            </select>
          </div>
        </div>
        {{--return error message for shop_id input --}}
        @error('shop_id')
        <label class="text-danger col-sm-6">&nbsp;*{{ $message }}</label>
        @enderror
        {{--return error message for company_id input --}}
        @error('company_id')
        <label class="text-danger col-sm-6">&nbsp;*{{ $message }}</label>
        @enderror

        {{-- staff status input --}}
        <div class="form-check">
          <input type="checkbox" class="form-check-input" name="active" {{ $staff->staff_status == config('constants.ACTIVE') ? 'checked' : '' }}>
          <label class="form-check-label">{{ __('Active')}}</label>
        </div>
      </div>

      {{-- submit and back buttons --}}
      <div class="card-footer">
        <button type="submit" class="btn btn-primary">{{__('Submit')}}</button>
        <a href="{{ url('staff') }}" class="btn btn-info mx-sm-2">{{__('Back')}}</a>
      </div>
    </form>
  </div>
</div>

<style type="text/css">
  @media(max-width: 576px) {
    #staff_number{
      margin:0 7px !important;
    }
  }
</style>
@endsection

@section('js')
<script type="text/javascript">
  var staff = {!! json_encode($staff)!!};
  var staff_no_all_role = {!! json_encode($staff_no_all_role)!!};
  var company_admin = {!! json_encode(config('constants.COMPANY_ADMIN'))!!};
  var shop_admin = {!! json_encode(config('constants.SHOP_ADMIN'))!!};
  var cashier_staff = {!! json_encode(config('constants.CASHIER_STAFF'))!!};
  var kitchen_staff = {!! json_encode(config('constants.KITCHEN_STAFF'))!!};
  var waiter_staff = {!! json_encode(config('constants.WAITER_STAFF'))!!};
  var sale_staff = {!! json_encode(config('constants.SALE_STAFF'))!!};
</script>
<script src="{{asset('js/staff/edit.js')}}"></script>
@endsection

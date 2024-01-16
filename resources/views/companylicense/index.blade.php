@extends('layouts.main')

@section('main-content')
<form id="frm_license" method="GET" action="{{ route('company.license')}}">
  <div class="col-md-12">
    @if(session()->has('success_status'))
    <div class="alert alert-info" role="alert">
      {{ session('success_status') }}
    </div>
    @endif
    {{-- add button --}}
    <div class="form-group">
      <a href="{{ url('company/license/create') }}" class="btn btn_add">{{ __('Add') }}</a>
    </div>
    <div class="card card-info">
      {{-- form title --}}
      <div class="card-header">
        <h3 class="card-title">{{ __('Company') }} {{ __('License') }} {{ __('List') }}</h3>
      </div>

      <div class="card-body">
        <div class="table-responsive">
         {{-- company table --}}
          <table id="companyList" class="table table-bordered text-nowrap">
            <thead class="thead-light">
              <tr>
                <th>{{ __('Company')}} {{__('Name')}}</th>
                <th>{{__('License Type') }}</th>
                <th>{{__('License Status') }}</th>
                <th>{{__('Available Users')}}</th>
                <th>{{ __('Start Date') }}</th>
                <th class="table-phone-size">{{ __('End Date') }}</th>
                <th class="table-phone-size">{{ __('Contact Email') }}</th>
                <th class="table-edit-delete-size"></th>
              </tr>
            </thead>
            <tbody>
              {{-- comapany data list --}}
              @forelse($companyList as $company)
              <tr>
                <td>{{ $company->company_name }}</td>
                @if($company->license_type == config('constants.STANDALONE_POS'))
                <td> Standalone POS </td>
                @elseif($company->license_type == config('constants.STANDALONE_POS_INVENTORY'))
                <td> Standalone POS Inventory </td>
                @elseif($company->license_type == config('constants.MULTI_POS'))
                <td> Multi POS </td>
                @elseif($company->license_type == config('constants.MULTI_POS_INVENTORY'))
                <td> Multi POS Inventory </td>
                @endif

                @if($company->status == config('constants.COMPANY_LICENSE_INACTIVE'))
                <td> Inactive </td>
                @elseif($company->status == config('constants.COMPANY_LICENSE_ACTIVE'))
                <td> Active </td>
                @elseif($company->status == config('constants.COMPANY_LICENSE_EXPIRE'))
                <td> Expire </td>
                @elseif($company->status == config('constants.COMPANY_LICENSE_BLOCK'))
                <td> Block </td>
                @endif
                <td>{{ $company->user_count }}</td>
                <td>{{ date('m/d/Y',strtotime($company->start_date)) }}</td>
                <td>{{ date('m/d/Y',strtotime($company->end_date)) }}</td>
                <td>{{ empty($company->contact_email)?'-': $company->contact_email}}</td>
                <td class="text-center"><a href="{{ url('/company/license/' . $company->id . '/edit') }}" class="nav-icon fas fa-edit iconSize"></a>
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="5" class="text-center">No results found.</td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>

      <div class="card-footer">
        {{-- comapany list pagination size filters --}}
        <nav>
          <label class="mr-sm-2 mx-sm-2 pt-1" for="inlineFormCustomSelect">{{ __('Show Items') }}</label>
          <select class="custom-select mr-sm-2 custom_pg_size" id="inlineFormCustomSelect" style="width:34%" name="custom_pg_size">
            <option value="10" {{request()->get('custom_pg_size')=='10' || ''?'selected':''}}>10</option>
            <option value="20" {{ request()->get('custom_pg_size')=='20'?'selected':''}}>20</option>
            <option value="30" {{request()->get('custom_pg_size')=='30'?'selected':''}}>30</option>
          </select>
        </nav>
        {{-- company list pagination --}}
        {{ $companyList->withQueryString()->links() }}
      </div>
    </div>
  </div>
</form>

@endsection

@section('js')
<script type="text/javascript" src="{{asset('js/companylicense/list.js')}}"></script>
@endsection

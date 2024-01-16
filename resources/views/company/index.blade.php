@extends('layouts.main')

@section('main-content')
<form id="frm_company" action="{{ route('company') }}" method="GET">
  <div class="col-md-12">
    @if(session()->has('error_status'))
    <div class="alert alert-warning" role="alert">{{ session('error_status') }}</div>
    @endif
    @if(session()->has('success_status'))
    <div class="alert alert-info" role="alert">
      {{ session('success_status') }}
    </div>
    @endif
    {{-- add button --}}
    <div class="form-group">
      <a href="{{ url('company/create') }}" class="btn btn_add">{{ __('Add') }}</a>
    </div>
    <div class="card card-info">
      {{-- form title --}}
      <div class="card-header">
        <h3 class="card-title">{{ __('Company') }} {{ __('List') }}</h3>
      </div>

      <div class="card-body">
        <div class="table-responsive">
         {{-- company table --}}
          <table id="companyList" class="table table-bordered text-nowrap">
            <thead class="thead-light">
              <tr>
                <th>{{ __('Name') }}</th>
                <th>{{ __('Address') }}</th>
                <th class="table-phone-size">{{ __('Primary Phone') }}</th>
                <th class="table-phone-size">{{ __('Secondary Phone') }}</th>
                <th class="table-edit-delete-size"></th>
                <th class="table-edit-delete-size"></th>
                <th class="table-phone-size"></th>
              </tr>
            </thead>
            <tbody>
              {{-- comapany data list --}}
              @forelse($companyList as $company)
              <tr>
                <td>{{ $company->name }}</td>
                <td>{{ $company->address }}</td>
                <td>{{ empty($company->phone_number_1) ? "-":$company->phone_number_1}}</td>
                <td>{{ empty($company->phone_number_2) ? "-":$company->phone_number_2}}</td>
                <td class="text-center"><a href="{{ url('/company/' . $company->id . '/edit') }}" class="nav-icon fas fa-edit iconSize"></a></td>
                <td>
                   <button data-toggle="modal" class='deleteModal iconButton' data-id="{{$company->id}}" data-target="#deleteModalCenter" type="button">
                   <span style="color:red" class="nav-icon fas fa-trash-alt iconSize"></span></button>
                </td>
                <td>
                  <a href="{{url('/company/login/'.$company->id)}}"><b>{{__('login company')}}</b></a>
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

{{-- confirm delete modal --}}
<div class="modal fade" id="deleteModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
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
      {{-- company delete form --}}
      <form action="{{ url('company/delete') }}" method="post" id="deleteForm">
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
@endsection

@section('js')
<script type="text/javascript" src="{{asset('js/company/list.js')}}"></script>
@endsection

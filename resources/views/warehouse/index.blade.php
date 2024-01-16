@extends('layouts.main')

@section('main-content')
<form id="frm_warehouse">
  <div class="col-md-12">
    @if(session()->has('success_status'))
    <div class="alert alert-info" role="alert">
      {{ session('success_status') }}
    </div>
    @endif
    {{-- add button --}}
    <div class="form-group">
      <a href="{{ url('warehouse/create') }}" class="btn btn_add">{{ __('Add') }}</a>
    </div>

    <div class="card card-info">
      {{-- form title --}}
      <div class="card-header">
        <h3 class="card-title">{{ __('Warehouse') }} {{ __('List') }}</h3>
      </div>

      <div class="card-body">
        <div class="table-responsive">
          {{-- warehouse table --}}
          <table id="example1" class="table table-bordered text-nowrap">
            <thead class="thead-light">
              <tr>
                <th>{{ __('Name') }}</th>
                <th>{{ __('Address') }}</th>
                <th class="table-phone-size">{{ __('Primary Phone') }}</th>
                <th class="table-phone-size">{{ __('Secondary Phone') }}</th>
                <th class="table-edit-delete-size"></th>
              </tr>
            </thead>
            <tbody>
              {{-- warehouse data list --}}
              @forelse($warehouseList as $warehouse)
              <tr>
                <td>{{ $warehouse->name }}</td>
                <td>{{ $warehouse->address }}</td>
                <td>{{ $warehouse->phone_number_1 ?? "-"}}</td>
                <td>{{ $warehouse->phone_number_2 ?? "-"}}</td>
                <td class="text-center"><a href="{{ url('/warehouse/' . $warehouse->id . '/edit') }}" class="nav-icon fas fa-edit iconSize"></a></td>
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
        {{-- warehouse list pagination size filters --}}
        <nav>
          <label class="mr-sm-2 mx-sm-2 pt-1" for="inlineFormCustomSelect">{{ __('Show Items') }}</label>
          <select class="custom-select mr-sm-2 custom_pg_size" id="inlineFormCustomSelect" style="width:34%" name="custom_pg_size">
            <option value="10" {{request()->get('custom_pg_size')=='10' || ''?'selected':''}}>10</option>
            <option value="20" {{ request()->get('custom_pg_size')=='20'?'selected':''}}>20</option>
            <option value="30" {{request()->get('custom_pg_size')=='30'?'selected':''}}>30</option>
          </select>
        </nav>
        {{-- warehouse list pagination --}}
        {{ $warehouseList->withQueryString()->links()  }}
      </div>
    </div>
  </div>
</form>
@endsection

@section("js")
<script type="text/javascript" src="{{asset('js/warehouse/list.js')}}"></script>
@endsection
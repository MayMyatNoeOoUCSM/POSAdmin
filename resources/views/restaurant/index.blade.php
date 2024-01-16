@extends('layouts.main')

@section('main-content')
<div class="col-md-12">
  @if(session()->has('error_status'))
  <div class="alert alert-warning" role="alert">
    {{ session('error_status') }}
  </div>
  @endif
  @if(session()->has('success_status'))
  <div class="alert alert-info" role="alert">
    {{ session('success_status') }}
  </div>
  @endif
  {{-- add button --}}
  <div class="form-group">
    <a href="{{ url('restaurant/create') }}" class="btn btn_add">{{ __('Add') }}</a>
  </div>

  <div class="card card-info">
    {{-- form title --}}
    <div class="card-header">
      <h3 class="card-title">{{ __('Restaurant') }} {{ __('Table') }} {{ __('List') }}</h3>
    </div>

    <div class="card-body">
      {{-- restaurant table --}}
      <table id="example1" class="table table-bordered">
        <thead class="thead-light">
          <tr>
            <th>{{ __('Name') }}</th>
            <th>{{ __('Shop') }}{{ __('Name') }}</th>
            <th>{{ __('Total') }}{{ __('Seat') }}{{ __('People') }}</th>
            <th></th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          {{-- restaurant data list --}}
          @forelse($restaurantList as $restaurant)
          <tr>
            <td>{{ $restaurant->name }}</td>
            <td>{{ $restaurant->shop_name }}</td>
            <td>{{ $restaurant->total_seats_people }}</td>
            <td class="text-center"><a href="{{ url('/restaurant/' . $restaurant->id . '/edit') }}" class="nav-icon fas fa-edit iconSize"></a></td>
            <td class="text-center">
                @csrf
                @method('DELETE')
                <button data-toggle="modal" class='deleteModal iconButton' data-id="{{$restaurant->id}}" data-target="#deleteModalCenter" type="button">
                  <span style="color:red" class="nav-icon fas fa-trash-alt iconSize"></span>
                </button>
              <!-- </form> -->
            </td>
          </tr>
          @empty
            <tr>
              <td colspan="4" class="text-center">No results found.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    {{-- restaurant list pagination --}}
    <div class="card-footer">
      {{ $restaurantList->links() }}
    </div>
  </div>
</div>

{{-- confirm delete modal --}}
<div class="modal fade" id="deleteModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalCenterTitle">Confirm Delete</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">
          {{ __('message.A0009') }}
      </div>
      {{-- restaurant delete form --}}
      <form action="{{ url('restaurant/delete') }}" method="post" id="deleteForm">
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
<script type="text/javascript">
    $('.deleteModal').on('click',function(){
      let id = $(this).attr('data-id');
       $('#deleteForm').attr('action', 'restaurant/'+id);
    })
</script>
@endsection

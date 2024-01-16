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
    <a href="{{ url('terminal/create') }}" class="btn btn_add">{{ __('Add') }}</a>
  </div>

  <div class="card card-info">
    {{-- form title --}}
    <div class="card-header">
      <h3 class="card-title">{{ __('Terminal') }} {{ __('List') }}</h3>
    </div>

    <div class="card-body">
      {{-- terminal table --}}
      <table id="example1" class="table table-bordered">
        <thead class="thead-light">
          <tr>
            <th>{{ __('Name') }}</th>
            <th>{{ __('Shop') }}{{ __('Name') }}</th>
            <th></th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          {{-- terminal data list --}}
          @forelse($terminalList as $terminal)
          <tr>
            <td>{{ $terminal->name }}</td>
            <td>{{ $terminal->shop_name }}</td>
            <td class="text-center"><a href="{{ url('/terminal/' . $terminal->id . '/edit') }}" class="nav-icon fas fa-edit iconSize"></a></td>
            <td class="text-center">
            <!--   <form action="{{ route('terminal.delete', $terminal->id)}}" method="post"> -->
                @csrf
                @method('DELETE')
                <button data-toggle="modal" class='deleteModal iconButton' data-id="{{$terminal->id}}" data-target="#deleteModalCenter" type="button">
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

    {{-- terminal list pagination --}}
    <div class="card-footer">
      {{ $terminalList->links() }}
    </div>
  </div>
</div>

{{-- confirm delete modal --}}
<div class="modal fade" id="deleteModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalCenterTitle">{{ __('Confirm Delete')}}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">
          {{ __('message.A0009') }}
      </div>
      {{-- terminal delete form --}}
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
@endsection

@section('js')
<script type="text/javascript">
    $('.deleteModal').on('click',function(){
      let id = $(this).attr('data-id');
       $('#deleteForm').attr('action', 'terminal/'+id);
    })
</script>
@endsection
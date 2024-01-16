@extends('layouts.main')

@section('main-content')
<div class="col-md-12">
  {{-- category search form --}}
  <form action="{{ route('category')}}" method="get" id="frm_category">
    @csrf
    <input type="hidden" name="custom_pg_size" id="custom_pg_size">
    @if(session()->has('error_status'))
    <div class="alert alert-warning" role="alert">{{ session('error_status') }}</div>
    @endif
    @if(session()->has('success_status'))
    <div class="alert alert-info" role="alert">{{ session('success_status') }}</div>
    @endif

    {{-- search parent category id input --}}
    <div class="col-md-12" id="content">
      <div class="row">
        <label class="col-sm-3 col-form-label">{{__('Parent Category')}} {{__('Name')}}</label>
        <select name="search_parent_category_id" class="form-control col-sm-4">
          <option value="" selected>{{ __('Select Parent Category Name') }}</option>
          @foreach($parentCategoryList as $category)
          <option value="{{$category->id}}" {{ app('request')->input('search_parent_category_id') == $category->id ? 'selected' : '' }}>{{$category->name}}</option>
          @endforeach
        </select>
        <input class="btn btn_search ml-3" name="search" id="btn_search" type="submit" value="{{ __('Search') }}">
      </div>
    </div>

    {{-- add button --}}
    <div class="form-group" style="margin-top:15px">
      <a href="{{ url('category/create') }}" class="btn btn_add">{{ __('Add') }}</a>
    </div>
  </form>

  <div class="card card-info">
    {{-- form title --}}
    <div class="card-header">
      <h3 class="card-title">{{ __('Category') }} {{ __('List') }}</h3>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        {{-- category table  --}}
        <table id="example1" class="table table-bordered text-nowrap">
          <thead class="thead-light">
            <tr>
              <th>{{ __('Name') }}</th>
              <th class="table-phone-size">{{ __('Parent') }} {{ __('Category') }}</th>
              <th>{{ __('Description') }}</th>
              <th class="table-edit-delete-size"></th>
              <th class="table-edit-delete-size"></th>
            </tr>
          </thead>
          <tbody>
            {{-- category data list --}}
            @forelse($categoryList as $category)
            <tr>
              <td>{{ $category->name }}</td>
              <td>{{ $category->parent_category_name ?? "-"}}</td>
              <td>{{ $category->description ?? "-"}}</td>
              <td class="text-center"><a href="{{ url('/category/' . $category->id . '/edit') }}" class="nav-icon fas fa-edit iconSize"></a></td>
              <td class="text-center">
                <button data-toggle="modal" class='deleteModal iconButton' data-id="{{$category->id}}" data-target="#deleteModalCenter" type="button">
                   <span style="color:red" class="nav-icon fas fa-trash-alt iconSize"></span>
                </button>
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

    <div class="card-footer offset-sm-12">
      {{-- category list pagination size filters --}}
      <nav>
        <label class="mr-sm-2 mx-sm-2 pt-1" for="inlineFormCustomSelect">{{ __('Show Items') }}</label>
        <select class="custom-select mr-sm-2 custom_pg_size" id="inlineFormCustomSelect" style="width:34%" name="custom_pg_size">
          <option value="10" {{request()->get('custom_pg_size')=='10' || ''?'selected':''}}>10</option>
          <option value="20" {{ request()->get('custom_pg_size')=='20'?'selected':''}}>20</option>
          <option value="30" {{request()->get('custom_pg_size')=='30'?'selected':''}}>30</option>
        </select>
      </nav>

      {{-- category list pagination and search query parameters --}}
      {{ $categoryList->appends(['search_parent_category_id' => request()->search_parent_category_id,'custom_pg_size' => request()->custom_pg_size])->links() }}
    </div>
  </div>
</div>
{{-- confirm delete modal --}}
<div class="modal fade" id="deleteModalCenter" tabindex="-1" role="dialog" aria-labelledby="deleteModalCenterTitle" aria-hidden="true">
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
      {{-- category delete form --}}
      <form action="" method="post" id="deleteForm">
          @csrf
          @method('DELETE')
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Yes</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </form>
    </div>
  </div>
</div>
@endsection

@section('css')
<style type="text/css">
  @media (max-width: 576px) {
    .row {
      margin-left: 0px;
    }
    input[type=submit] {
      margin-top: 15px;
    }
  }
  @media (min-width: 576px){
    .col-sm-2 {
        -ms-flex: 0 0 18.666667%;
        flex: 0 0 18.666667%;
        max-width: 18.666667%;
    }
  }
  select.form-control.col-sm-3{
    margin-right: 20px;
  }
  #btn_search{
    height: 100%;
  }
</style>
@endsection

@section("js")
<script type="text/javascript" src="{{asset('js/category/list.js')}}"></script>
<script type="text/javascript">
    $('.deleteModal').on('click',function(){
      let id = $(this).attr('data-id');
       $('#deleteForm').attr('action', 'category/'+id);
    })
</script>
@endsection

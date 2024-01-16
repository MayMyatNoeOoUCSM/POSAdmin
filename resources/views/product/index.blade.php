@extends('layouts.main')

@section('main-content')
{{-- product search form --}}
<form action="{{ route('product')}}" method="get" id="frm_product" enctype="multipart/form-data">
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
    <div class="row">
      {{-- product name input for product search --}}
      <label class="col-sm-2 col-form-label">{{ __('Product') }} {{ __('Name') }}</label>
      <input type="text" class="form-control col-sm-3" name="search_name" placeholder="{{ __('Product') }} {{ __('Name') }}" value="{{ app('request')->input('search_name') != '' ? app('request')->input('search_name') : '' }}"><br>
      {{-- product category input for product search --}}
      <label class="col-sm-2 col-form-label">{{ __('Product') }} {{ __('Category') }}</label>
      <select name="search_category" class="form-control col-sm-3">
        <option value="">{{ __('Select Category Name') }}</option>
        {{-- product category data list --}}
        @foreach($productCategoryList as $category)
        <option value="{{$category->id}}" 
          {{ (app('request')->input('search_category') == $category->id ? "selected" : "" )}}>
        {{$category->category_name}}</option>
        @endforeach
      </select>
      {{-- search button --}}
      <div class="col-sm-1">
        <input class="btn btn_search btn-w-100" name="search" type="submit" value="{{ __('Search') }}">
      </div>
    </div>
    <div>
    <input type="hidden" name="sorting_column" id="sorting_column">
      <input type="hidden" name="sorting_order" id="sorting_order" value="{{ app('request')->input('sorting_order') != '' ? app('request')->input('sorting_order') : 'asc' }}">
      <input type="hidden" name="custom_pg_size" id="custom_pg_size">
    </div>
  </div>

  {{-- add button --}}
  <div class="form-group" style="margin-top:15px">
    <a href="{{ url('product/create') }}" class="btn btn_add" id="btn_add" type="button">{{ __('Add') }}</a>
    <input type="file" accept=".xlsx, .xls, .csv"  name="importFile" id="importFile" class="form-control" style="display:none;width:20%;vertical-align: middle;padding:4px;">
    {{-- import excel file button --}}
    <button class="btn mx-sm-2 btn_download import_excel" id="btn_excel" type="button">{{__('Import Excel')}}</button>
    {{-- return error message for import file --}}
    @error('importFile')
    <label class="text-danger" style="margin-left:30%;">&nbsp;*{{ $message }}</label>
    @enderror
  </div>
</form>

<div class="card card-info">
  {{-- form title --}}
  <div class="card-header">
    <h3 class="card-title">{{ __('Product') }} {{ __('List') }}</h3>
  </div>

  <div class="card-body">
    <div class="table-responsive">
      {{-- product table --}}
      <table id="productList" class="table table-bordered text-nowrap">
        <thead class="thead-light">
          <tr>
            <th scope="col"></th>
            <th scope="col">{{ __('Product') }} {{ __('Code') }} <i class="fas fa-sort" id="product_code"></i></th>
            <th scope="col">{{ __('Product') }} {{ __('Name') }}<i class="fas fa-sort" id="name"></i></th>
            <th scope="col">{{ __('Product') }} {{ __('Category') }} <i class="fas fa-sort" id="c.name"></i></th>
            <th scope="col">{{ __('Description') }}</th>
            <!-- <th scope="col">{{ __('Change')}}</th> -->
            <th scope="col"></th>
            <th scope="col"></th>
            <th scope="col"></th>
          </tr>
        </thead>
        <tbody>
          {{-- product data list --}}
          @forelse($productList as $product)
          <!--   <tr class="{{ $product->product_status == 1 ? 'table-active' : 'table-inactive' }}"> -->
          <tr>
            <td ><img src="
                @if($product->product_image_path)
                {{$product->product_image_path}}
                @else
                {{'uploads/products/noproductimage.png'}}
                @endif
              " width="150" height="100" /></td>
            <td>{{ $product->product_code}}</td>
            <td>{{ $product->name}}</td>
            <td>{{ $product->category_name }}</td>
            <td>{{ $product->description ?? "-"}}</td>
           <!--  <td class="text-center">
              <div class="btn-group btn-group-toggle" data-toggle="buttons">
                <label class="btn btn-secondary active" style="background-color: #28a745;">
                  <input type="radio" name="options" id="option1" autocomplete="off" checked="" data-toggle="modal" data-target="#yourModal{{$product->id}}"> Min Qty
                </label>
                <label class="btn btn-secondary" style="background-color: #28a745;">
                   <input type="radio" name="options" id="option2" autocomplete="off" data-toggle="modal" data-target="#changePrice{{$product->id}}" {{$product->price??'disabled'}}> Price
                </label>
              </div>
            </td> -->
            <td class="text-center"><a href="{{ url('/product/' . $product->id . '/edit') }}" class="nav-icon fas fa-edit iconSize"></a></td>
            <td class="text-center">
              <button data-toggle="modal" class='deleteModal iconButton' data-id="{{$product->id}}" data-target="#deleteModalCenter" type="button">
                     <span style="color:red" class="nav-icon fas fa-trash-alt iconSize"></span>
              </button>
            </td>
            <td class="text-center">
                <a href="{{ url('/product/' . $product->id . '/clone') }}" 
                  <button type="button" id="cancel_invoice" class="btn btn_primary">Product Clone</button>
                </a>
            </td>
          </tr>
          @empty
            <tr>
              <td colspan="8" class="text-center">No results found.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    {{-- form for update product quantity --}}
    @foreach($productList as $product)
    <form action="{{ route('product.change_min_qty') }}" method="POST" id="change_min_qty_form_{{$product->id}}">
      @csrf
      <div class="modal fade" id="yourModal{{$product->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            {{-- form title --}}
            <div class="modal-header bg-info">
              <h5>Change Minimum Quantity</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>

            <div class="modal-body">
              {{-- minimum quantity input --}}
              <div class="form-group row">
                <label class="col-sm-5 offset-sm-1 col-form-label">{{ __('Minimum') }} {{ __('Quantity') }}</label>
                <div class="col-sm-5">
                  <input type="text" class="form-control min_qty_{{$product->id}}" name="min_qty" placeholder="Minimum Qty" value="{{$product->minimum_quantity}}">
                  <label style="display:none;" id="required_min_qty_{{$product->id}}" class="validation_message">minimum quantity is required</label>
                  <input type="hidden" class="form-control" name="id" value="{{$product->id}}">
                </div>
              </div>
            </div>

            {{-- submit button --}}
            <div class="modal-footer">
              <button type="button" class="btn btn-info btn_change_qty" data-id="{{$product->id}}">Submit</button>
            </div>
          </div>
        </div>
      </div>
    </form>
    @endforeach

    {{-- form for update product price --}}
    @foreach($productList as $data)
    <form action="{{ route('product.update_price') }}" method="POST" id="change_price_form_{{$data->id}}">
      @csrf
      <div class="modal fade" id="changePrice{{$data->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            {{-- form title --}}
            <div class="modal-header bg-info">
              <h5>Change Price</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>

            <div class="modal-body">
              <div class="form-group row">
                {{-- product name input --}}
                <label class="col-sm-4 offset-sm-1 col-form-label">{{ __('Product') }} {{ __('Name') }}</label>
                <div class="col-sm-5">
                  <input type="text" class="form-control product" name="product" placeholder="" value="{{$data->name}}" readonly="">
                  <input type="hidden" class="form-control change_price_product_id" name="product_id" placeholder="" value="{{$data->id}}" readonly="">
                </div>
                {{-- product price input and return error message --}}
                <label class="col-sm-4 offset-sm-1 col-form-label">{{ __('Price') }}</label>
                <div class="col-sm-5 mt-2">
                  <input type="text" class="form-control price_{{$data->id}}" name="price" placeholder="Price" value="{{number_format($data->price,2)}}">
                  <label style="display:none;" id="required_price_{{$data->id}}" class="validation_message">price is required</label>
                  @error('price')
                  <label class="text-danger">&nbsp;*{{ $message }}</label>
                  @enderror
                </div>
              </div>
            </div>

            {{-- submit button --}}
            <div class="modal-footer">
              <button type="button" class="btn btn-info btn_change_price" data-id="{{$data->id}}">Submit</button>
            </div>
          </div>
        </div>
      </div>
    </form>
    @endforeach
  </div>

  <div class="card-footer">
    {{-- product list pagination size filters --}}
    <nav>
      <label class="mr-sm-2 mx-sm-2 pt-1" for="inlineFormCustomSelect">{{ __('Show Items') }}</label>
      <select class="custom-select mr-sm-2 custom_pg_size" id="inlineFormCustomSelect" style="width:60px;">
        <option value="10" {{request()->get('custom_pg_size')=='10' || ''?'selected':''}}>10</option>
        <option value="20" {{ request()->get('custom_pg_size')=='20'?'selected':''}}>20</option>
        <option value="30" {{request()->get('custom_pg_size')=='30'?'selected':''}}>30</option>
      </select>
    </nav>
    {{-- product list pagination --}}
    {{ $productList->withQueryString()->links() }}
  </div>
</div>
{{-- confirm delete modal --}}
<div class="modal fade" id="deleteModalCenter" tabindex="-1" role="dialog" aria-labelledby="deleteModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalCenterTitle">{__('Confirm Delete')}}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">
         {{ __('message.A0009') }}
      </div>
      {{-- product delete form --}}
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

<style type="text/css">
  img{
    display: block;
    margin-left: auto;
    margin-right: auto;
    width:120px;
  }
  .validation_message {
    color:red;
  }
  @media (max-width: 576px) {
    input[type=submit] {
      margin-top: 15px;
    }
    #btn_add,
    #btn_excel {
      margin-top: 15px;
      /*margin-left: 8px;*/
      background-color: #1bbed8;
      border-color: #1bbed8;
      box-shadow: none;
    }
    .pagination {
      font-size: 2vw;
    }
  }
  .content-wrapper {
    min-height: 600px !important;
  }
  @media (min-width: 576px) {
    .col-sm-2 {
        -ms-flex: 0 0 14.5% !important;
        flex: 0 0 14.5% !important;
        max-width: 14.5% !important;
    }
    .btn-secondary_change:not(:disabled):not(.disabled).active,
    .btn-secondary_change:not(:disabled):not(.disabled):active,
    .show>.btn-secondary_change.dropdown-toggle {
      color: #fff;
      background-color: #28a745;
      border-color: #4e555b;
    }
    .btn-secondary {
      color: #fff;
      background-color: #28a745;
      border-color: #28a745;
      box-shadow: none;
    }
  }
  table tbody tr td {
    vertical-align: middle !important;
    text-align: left !important;
  }
  .fa-sort{
    margin-left: 5px;
  }
  td img{
    /*border-style: groove !important;*/
  }
</style>
@endsection

@section('js')
<script type="text/javascript">
  var importurl;
  window.importurl = "{{ route('product.import_excel')}}"
</script>
<script src="/js/product/list.js"></script>
<script type="text/javascript">
    $('.deleteModal').on('click',function(){
      let id = $(this).attr('data-id');
       $('#deleteForm').attr('action', 'product/'+id);
    });
</script>
@endsection

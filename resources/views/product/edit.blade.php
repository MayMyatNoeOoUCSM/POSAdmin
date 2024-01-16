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
      <h3 class="card-title">{{ __('Product')}} {{ __('Edit')}}</h3>
    </div>

    {{-- form for update product --}}
    <form action="{{ url('/product/' . $product->id ) }}" enctype="multipart/form-data" method="POST">
      @csrf
      @method('PUT')
      <div class="card-body">
        {{-- product name input and return error message --}}
        <div class="form-group row">
          <label class="col-sm-4 offset-sm-1 col-form-label required">{{ __('Product') }} {{ __('Name') }}</label>
          <div class="col-sm-6">
            <input type="text" class="form-control" name="name" placeholder="Name" value="{{$product->name}}"><br>
          </div>
        </div>
        <div class="offset-sm-5">
          @error('name')
          <label class="text-danger">*{{ $message }}</label>
          @enderror
        </div>

        {{-- short name input and return error messages --}}
        <div class="form-group row">
          <label class="col-sm-4 offset-sm-1 col-form-label required">{{ __('Short') }} {{ __('Name') }}</label>
          <div class="col-sm-6">
            <input type="text" class="form-control" name="short_name" placeholder="{{ __('Short') }} {{ __('Name') }}" value="{{$product->short_name}}">
            <label class="col-form-label">{{ __('(20 characters only)') }}</label>
          </div>
        </div>
        <div class="col-sm-6 offset-sm-5">
          @error('short_name')
          <label class="text-danger">&nbsp;*{{ $message }}</label>
          @enderror
        </div>

        {{-- category input and return error message --}}
        <div class="form-group row">
          <label class="col-sm-4 offset-sm-1 col-form-label required">{{ __('Category')}}</label>
          <div class="col-sm-6">
            <input type="text" class="form-control" name="category_name" value="{{$product->category->name}}" disabled>
            <input type="hidden" name="product_category_id" value="{{$product->product_type_id}}">
           <!--  <select name="product_category_id" class="form-control" id="edit_product_category_id" readonly>
            <option value="" selected>{{ __('Select Category Name') }}</option> 
              @foreach($productCategoryList as $productCategory)
              <option value="{{$productCategory->id}}" {{ $product->product_type_id == $productCategory->id ? 'selected' : '' }}> {{$productCategory->name}} </option> 
              @endforeach
            </select> -->
          </div>
        </div>
        <div class="offset-sm-5">
          @error('product_category_id')
          <label class="text-danger">&nbsp;*{{ $message }}</label>
          @enderror
        </div>

        {{-- category input and return error message --}}
        <div class="form-group row">
          <label class="col-sm-4 offset-sm-1 col-form-label required">{{ __('Shop') }} {{__('Name') }}</label>
          <div class="col-sm-6">
          <select name="shop_id[]" class="multi-select form-control" multiple>
              @foreach($shopProductList as $shopProduct)
              <option value="{{$shopProduct->id}}" 
                @if(in_array($shopProduct->id,$shop_id_array)) selected @endif>   
                  {{$shopProduct->name}} 
              </option>
              @endforeach
          </select>
          </div>
        </div>
        <div class="offset-sm-5 col-sm-6"> 
          @error('shop_id')
          <label class="text-danger">&nbsp;*{{ $message }}</label>
          @enderror
        </div>

        {{-- sale price and return error message --}}
        <div class="form-group row">
           <label class="col-sm-4 offset-sm-1 col-form-label required">{{ __('Sale Price') }}</label>
          <div class="col-sm-6">
            <input type="number" class="form-control" name="sale_price" placeholder="{{ __('Sale Price') }}" value="{{$product->sale_price}}">
          </div>
        </div>
        <div class="offset-sm-5">
          @error('sale_price')
          <label class="text-danger">&nbsp;*{{ $message }}</label>
          @enderror
        </div>

        {{-- barcode input and return error message --}}
        <div class="form-group row">
          <label class="col-sm-4 offset-sm-1 col-form-label">{{ __('Barcode') }}</label>
          <div class="col-sm-6">
            <input type="text" name="barcode" class="form-control" value="{{$product->barcode}}" readonly="">
            <input type="hidden" name="barcode_check" value="1">
          </div>
        </div>
        <div class="offset-sm-5">
          @error('barcode')
          <label class="text-danger">&nbsp;*{{ $message }}</label>
          @enderror
        </div>

        {{-- product image input and return error message --}}
        <div class="form-group row">
          <label class="col-sm-4 offset-sm-1 col-form-label">{{ __('Product') }} {{ __('Image') }}</label>
          <div class="col-sm-6">
            <input type="file" id="image" name="image" onchange="putImage();" />
            <input type="hidden" name="old_image" value="{{ $product->product_image_path }}" />
            <input type="file" style="display: none;" name="old_image_file" value="{{ env('BASE_PATH') . $product->product_image_path }}" />
          </div>
          <div class="col-sm-4 offset-sm-5">
            <img id="target" width="100" src="{{ asset($product->product_image_path) }}" style="{{ $product->product_image_path != null ? 'display:block;' : 'display:none;' }}" />
          </div>
        </div>
        <div class="offset-sm-5">
          @error('image')
          <label class="text-danger">&nbsp;*{{ $message }}</label>
          @enderror
        </div>

        {{-- minimum quantity input and return error message --}}
        <div class="form-group row">
          <label class="col-sm-4 offset-sm-1 col-form-label required">{{ __('Minimum') }} {{ __('Quanity') }}</label>
          <div class="col-sm-6 col-form-label">
            <input type="text" class="form-control" name="min_qty" placeholder="Product's Minium Quality For alert" value="{{ $product->minimum_quantity }}"><br>
          </div>
        </div>
        <div class="offset-sm-5">
          @error('min_qty')
          <label class="text-danger">&nbsp;*{{ $message }}</label>
          @enderror
        </div>

        {{-- manufacture data, expired date input and return error message --}}
        <div class="form-group row">
          <label class="col-sm-4 offset-sm-1 col-form-label">{{__('Manufacture Date')}}</label>
          <div class="date col-sm-6">
            <input type="date" class="form-control" name="mfd_date" placeholder="Manafacture Date" value="{{$product->mfd_date}}" />
          </div>
        </div>
        <div class="form-group row">
          <label class="col-sm-4 offset-sm-1 col-form-label">{{ __('Expired Date')}}</label>
          <div class="date col-sm-6">
            <input type="date" class="form-control" name="expire_date" placeholder="Expired Date" value="{{$product->expire_date}}" />
          </div>
        </div>
        <div class="offset-sm-5">
        @error('expire_date')
        <label class="text-danger">&nbsp;*{{ $message }}</label>
        @enderror
        </div>      

        {{-- description input --}}
        <div class="form-group row">
          <label class="col-sm-4 offset-sm-1 col-form-label ">{{ __('Description')}}</label>
          <div class="col-sm-6">
            <textarea class="form-control" name="description" placeholder="{{__('Description')}}"> {{$product->description}} </textarea>
          </div>
        </div>

        {{-- status input --}}
        <div class="form-group offset-sm-1">
          <input type="checkbox" class="col-form-input mx-sm-2" name="active" {{ $product->product_status == config('constants.ACTIVE') ? 'checked' : '' }}>
          <label class="col-form-label">{{ __('Active')}}</label>
        </div>

        {{-- submit and back buttons --}}
        <div class="card-footer row">
            <button type="submit" class="btn btn-primary offset-sm-5">{{ __('Submit')}}</button>
            <a href="{{ url('product') }}" class="btn btn-info mx-sm-4">{{ __('Back')}}</a>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection

@section('js')
<script src="{{asset('js/product/create.js')}}"></script>
@endsection

@extends('layouts.main')

@section('main-content')
<div class = "col-md-8 offset-md-2">
  @if(session()->has('error_status'))
    <div class="alert alert-warning" role="alert">
    {{ session('error_status') }}
    </div>
  @endif
  <div class="card card-info">
    {{-- form title --}}
    <div class="card-header">
      <h3 class="card-title">Change Price Entry Form</h3>
    </div>

    {{-- form for update product price --}}
    <form action="{{ route('product.update_price') }}" method = "POST">
        @csrf
      <div class="card-body">
        {{-- product name input and return error message --}}
        <div class="form-group row">
          <label class="col-sm-3 offset-sm-2 col-form-label">Product Name</label>
          <div class="col-sm-5">
            <select name="product_id" id="selectProduct" class="form-control select2">
              <option value="" selected>Choose Product</option>
              {{-- product data list --}}
              @foreach($productList as $product)
                <option value= "{{$product->product_id}}" {{ old('product_name') == $product->product_id ? 'selected' : '' }}> {{$product->product_name}} </option>
              @endforeach
            </select>
          </div>
        </div>
        <div class="offset-sm-5">
            @error('product_name')
              <label class="text-danger">&nbsp;*{{ $message }}</label>
            @enderror
        </div>

        {{-- product price input and return error message --}}
        <div class="form-group row">
          <label class="col-sm-3 offset-sm-2">Price</label>
          <div class="date col-sm-5">
              <input type="text" class="form-control" name="price" placeholder="Price" value="{{old('price')}}"><br>
          </div>
        </div>
        <div class="offset-sm-5">
          @error('price')
            <label class="text-danger">&nbsp;*{{ $message }}</label>
          @enderror
        </div>

        {{-- submit and back buttons --}}
        <div class="card-footer row">
          <button type="submit" class="btn btn-primary offset-sm-3">Submit</button>
          <a href="{{ url('product') }}" class="btn btn-info mx-sm-4">Back</a>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection

